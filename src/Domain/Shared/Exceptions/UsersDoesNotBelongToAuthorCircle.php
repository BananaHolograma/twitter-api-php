<?php

namespace Domain\Shared\Exceptions;

use Domain\Shared\Models\User;
use RuntimeException;

class UsersDoesNotBelongToAuthorCircle extends RuntimeException
{
    public function __construct(User $author, array $ids)
    {
        parent::__construct(
            "For the author {$author->id} the next users does not belongs to his circle: ".implode(',', $ids)
        );
    }
}
