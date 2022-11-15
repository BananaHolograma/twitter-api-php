<?php

namespace Domain\Tweets\Actions;

use Domain\Shared\Models\User;
use Domain\Tweets\DataTransferObjects\UpsertTweetData;
use Domain\Tweets\Exceptions\TweetCannotBeEditAnymore;
use Domain\Tweets\Exceptions\TweetDoesNotBelongsToAuthor;
use Domain\Tweets\Models\Tweet;

class UpdateTweetAction
{
    public function execute(User $author, UpsertTweetData $data): Tweet
    {
        $tweet = $this->ensureCanBeEdited($author, $data);
        $tweet->update($data->toArray());

        return $tweet->fresh();
    }

    private function ensureCanBeEdited(User $author, UpsertTweetData $data): Tweet
    {
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
