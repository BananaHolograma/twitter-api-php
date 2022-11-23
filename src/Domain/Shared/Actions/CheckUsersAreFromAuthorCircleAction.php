<?php

namespace Domain\Shared\Actions;

use Domain\Shared\Exceptions\UsersDoesNotBelongToAuthorCircle;
use Domain\Shared\Models\User;

class CheckUsersAreFromAuthorCircleAction
{
    public function execute(User $author, array $ids = []): void
    {
        if (count($ids)) {
            $follower_ids = $author->followers()->allRelatedIds();
            $users_not_in_author_circle = [];

            foreach ($ids as $id) {
                if (! $follower_ids->contains($id)) {
                    $users_not_in_author_circle[] = $id;
                }
            }

            if (count($users_not_in_author_circle)) {
                throw new UsersDoesNotBelongToAuthorCircle($author, $users_not_in_author_circle);
            }
        }
    }
}
