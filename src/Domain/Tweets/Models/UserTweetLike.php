<?php

namespace Domain\Tweets\Models;

use Domain\Shared\Models\User;
use Domain\Tweets\Events\TweetLikeCreatedEvent;
use Domain\Tweets\Events\TweetLikeDeletedEvent;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserTweetLike extends Pivot
{
    protected $table = 'user_tweet_likes';

    protected $fillable = ['user_id', 'tweet_id'];

    protected $dispatchesEvents = [
        'created' => TweetLikeCreatedEvent::class,
        'deleted' => TweetLikeDeletedEvent::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tweet(): BelongsTo
    {
        return $this->belongsTo(Tweet::class);
    }
}
