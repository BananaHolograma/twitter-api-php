<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TweetMetrics extends Model
{
    use HasFactory;

    protected $fillable = [
        'impression_count', 'like_count', 'reply_count',
        'retweet_count', 'quote_count', 'url_link_clicks', 'user_profile_clicks',
    ];

    protected $appends = ['public_metrics'];

    public function getPublicMetricsAttribute(): array
    {
        return [
            'reply_count' => $this->reply_count,
            'like_count' => $this->like_count,
            'retweet_count' => $this->retweet_count,
            'quote_count' => $this->quote_count,
        ];
    }

    public function getNonPublicMetricsAttribute(): array
    {
        return [
            'impression_count' => $this->impression_count,
            'url_link_clicks' => $this->url_link_clicks,
            'user_profile_clicks' => $this->user_profile_clicks,
        ];
    }

    public function getOrganicMetricsAttribute(): array
    {
        return [
            'impression_count' => $this->impression_count,
            'reply_count' => $this->reply_count,
            'like_count' => $this->like_count,
            'retweet_count' => $this->retweet_count,
            'url_link_clicks' => $this->url_link_clicks,
            'user_profile_clicks' => $this->user_profile_clicks,
        ];
    }

    public function getPromotedMetricsAttribute(): array
    {
        return [
            'impression_count' => $this->impression_count,
            'reply_count' => $this->reply_count,
            'like_count' => $this->like_count,
            'retweet_count' => $this->retweet_count,
            'url_link_clicks' => $this->url_link_clicks,
            'user_profile_clicks' => $this->user_profile_clicks,
        ];
    }

    public function tweet(): BelongsTo
    {
        return $this->belongsTo(Tweet::class);
    }
}
