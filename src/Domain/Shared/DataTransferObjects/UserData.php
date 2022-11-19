<?php

namespace Domain\Shared\DataTransferObjects;

use Carbon\CarbonImmutable;
use Domain\Shared\Models\User;
use Spatie\LaravelData\Data;

class UserData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $username,
        public bool $protected,
        public CarbonImmutable|null $verified_at
    ) {
    }

    public static function fromModel(User $user): self
    {
        return new self($user->id, $user->name, $user->username, $user->protected, $user->verified_at);
    }
}
