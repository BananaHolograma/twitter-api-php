<?php

namespace Domain\Tweets\Actions;

use Domain\Shared\Models\User;

class CheckUsersAreFromAuthorCircleAction
{
    public function execute(User $author, array $ids = []): void
    {
        if (count($ids)) {
        }
    }
}
