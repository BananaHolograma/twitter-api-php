<?php

namespace Domain\Shared\DataTransferObjects;

use Carbon\CarbonImmutable;
use Domain\Shared\Models\User;
use Domain\Tweets\DataTransferObjects\TweetData;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Lazy;

class UserData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $username,
        public string $description,
        public bool $protected,
        public CarbonImmutable|null $verified_at,
        #[DataCollectionOf(TweetData::class)]
        public DataCollection|Lazy $tweets,
        #[DataCollectionOf(TweetData::class)]
        public DataCollection|Lazy $likedTweets,
        #[DataCollectionOf(self::class)]
        public DataCollection|Lazy $followers,
        #[DataCollectionOf(self::class)]
        public DataCollection|Lazy $following
    ) {
    }

    public static function fromModel(User $user): self
    {
        return new self(
            $user->id,
            $user->name,
            $user->username,
            $user->description,
            $user->protected,
            $user->verified_at,
            Lazy::create(fn () => TweetData::collection($user->tweets)),
            Lazy::create(fn () => TweetData::collection($user->likedTweets)),
            Lazy::create(fn () => self::collection($user->followers)),
            Lazy::create(fn () => self::collection($user->following)),

        );
    }
}
