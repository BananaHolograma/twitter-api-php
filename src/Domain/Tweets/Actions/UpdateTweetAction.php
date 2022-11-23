<?php

namespace Domain\Tweets\Actions;

use Domain\Shared\Actions\CheckUsersAreFromAuthorCircleAction;
use Domain\Shared\Models\User;
use Domain\Tweets\DataTransferObjects\UpsertTweetData;
use Domain\Tweets\Exceptions\TweetCannotBeEditAnymore;
use Domain\Tweets\Exceptions\TweetDoesNotBelongsToAuthor;
use Domain\Tweets\Models\Tweet;
use Illuminate\Support\Facades\DB;

class UpdateTweetAction
{
    public function __construct(
        private readonly CreateTweetAction $createTweetAction,
        private readonly CheckUsersAreFromAuthorCircleAction $checkUsersAreFromAuthorCircleAction
    ) {
    }

    public function execute(User $author, UpsertTweetData $data): Tweet
    {
        return DB::transaction(function () use ($author, $data) {
            $tweet = $this->ensureCanBeEdited($author, $data);
            $edits_remaining = (int) $tweet->edit_controls['edits_remaining'] - 1;

            $new_tweet = $tweet->replicate()
                ->fill([
                    ...$data->toArray(),
                    'edit_history_tweet_ids' => [
                        ...$tweet->edit_history_tweet_ids ?? [],
                        $tweet->id,
                    ],
                    'edit_controls' => [
                        ...$tweet->edit_controls,
                        'edits_remaining' => $edits_remaining,
                        'is_edit_eligible' => $edits_remaining > 0,
                    ],
                ]);

            $new_tweet->save();
            $tweet->delete();

            return $new_tweet->fresh('author');
        });
    }

    private function ensureCanBeEdited(User $author, UpsertTweetData $data): Tweet
    {
        $this->checkUsersAreFromAuthorCircleAction->execute($author, $data->visible_for);

        $tweet = Tweet::select('id', 'author_id', 'edit_controls')->find($data->id);

        if (!$tweet->author->is($author)) {
            throw new TweetDoesNotBelongsToAuthor("The tweet {$tweet->id} does not belongs to user {$author->id}, it cannot be edited");
        }

        if (!$tweet->is_editable) {
            throw new TweetCannotBeEditAnymore($tweet);
        }

        return $tweet;
    }
}
