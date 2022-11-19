<?php

namespace Domain\Tweets\DataTransferObjects;

use Domain\Tweets\Models\TweetMetrics;
use Spatie\LaravelData\Data;

class TweetMetricsData extends Data
{
    public function __construct(
        public int $reply_count,
        public int $like_count,
        public int $retweet_count,
        public ?int $quote_count,
        public int $video_views_count,
        public ?int $url_link_clicks,
        public ?int $user_profile_clicks,
        public ?int $impression_count,
    ) {
    }

    public static function onlyPublicMetricsFrom(TweetMetrics $metrics): self
    {
        return self::from($metrics->public_metrics);
    }
}
