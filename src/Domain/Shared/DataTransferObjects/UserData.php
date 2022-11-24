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
        public string $username,
        public bool $protected,
        public ?string $name,
        public ?string $description,
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
            $user->username,
            $user->protected,
            $user->name,
            $user->description,
            $user->verified_at,
            Lazy::create(fn () => TweetData::collection($user->tweets)),
            Lazy::create(fn () => TweetData::collection($user->likedTweets)),
            Lazy::create(fn () => self::collection($user->followers)),
            Lazy::create(fn () => self::collection($user->following)),

        );
    }
}
