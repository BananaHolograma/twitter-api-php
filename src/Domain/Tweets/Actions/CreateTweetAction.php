<?php

namespace Domain\Tweets\Actions;

use Domain\Shared\Models\User;
use Domain\Tweets\DataTransferObjects\UpsertTweetData;
use Domain\Tweets\Models\Tweet;

class CreateTweetAction
{
    public function __construct(
        private readonly CheckUsersAreFromAuthorCircleAction $checkUsersAreFromAuthorCircleAction
    ) {
    }

    public function execute(User $author, UpsertTweetData $data): Tweet
    {
        $this->checkUsersAreFromAuthorCircleAction->execute($author, $data->visible_for);

        return $author->tweets()->save(new Tweet($data->toArray()));
    }
}
