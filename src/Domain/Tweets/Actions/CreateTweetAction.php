<?php

namespace Domain\Tweets\Actions;

use Domain\Shared\Models\User;
use Domain\Tweets\DataTransferObjects\UpsertTweetData;
use Domain\Tweets\Models\Tweet;

class CreateTweetAction
{
    public function execute(User $author, UpsertTweetData $data): Tweet
    {
        return $author->tweets()->save(new Tweet($data->toArray()));
    }
}
