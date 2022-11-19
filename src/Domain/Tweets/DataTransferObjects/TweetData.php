<?php

namespace Domain\Tweets\DataTransferObjects;

use Domain\Shared\DataTransferObjects\UserData;
use Domain\Tweets\Enums\ReplySettingEnum;
use Domain\Tweets\Models\Tweet;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Lazy;

class TweetData extends Data
{
    public function __construct(
        public int $id,
        public string $text,
        public bool $possibly_sensitive,
        public ReplySettingEnum $reply_settings,
        public UserData|Lazy $author,
        public TweetMetricsData $metrics,
        /** @var TweetData[] */
        #[DataCollectionOf(TweetData::class)]
        public DataCollection|Lazy $replies,
        public UserData|Lazy|null $reply_to_author,
        public TweetData|Lazy|null $reply_to_tweet,
        #[DataCollectionOf(UserData::class)]
        public DataCollection|Lazy $visible_for,

    ) {
    }

    public static function fromModel(Tweet $tweet): self
    {
        return new self(
            $tweet->id,
            $tweet->text,
            $tweet->possibly_sensitive,
            $tweet->reply_settings,
            Lazy::create(fn () => UserData::fromModel($tweet->author))->defaultIncluded(),
            TweetMetricsData::onlyPublicMetricsFrom($tweet->metrics),
            Lazy::create(fn () => TweetData::collection($tweet->replies))->defaultIncluded(),
            Lazy::create(fn () => UserData::fromModel($tweet->authorReplied)),
            Lazy::create(fn () => TweetData::fromModel($tweet->tweetReplied)),
            Lazy::create(fn () => UserData::collection($tweet->visible_for)),

        );
    }
}
