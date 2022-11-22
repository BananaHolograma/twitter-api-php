<?php

namespace Domain\Tweets\Actions;

use Domain\Shared\Models\User;
use Domain\Tweets\DataTransferObjects\UpsertTweetData;
use Domain\Tweets\Models\Tweet;

class ProcessTweetAction
{
    public function __construct(
        private readonly CreateTweetAction $createTweetAction,
        private readonly UpdateTweetAction $updateTweetAction,
    ) {
    }

    public function execute(User $author, UpsertTweetData $data): Tweet
    {
        $tweet = isset($data->id) ?
            $this->updateTweetAction->execute($author, $data) :
            $this->createTweetAction->execute($author, $data);

        return $tweet->fresh('author');
    }
}
