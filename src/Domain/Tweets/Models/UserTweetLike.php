<?php

namespace Domain\Tweets\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserTweetLike extends Pivot
{
    protected $table = 'user_tweet_likes';

    protected $fillable = ['user_id', 'tweet_id'];

    protected $dispatches = [
        'created' => TweetLikeCreated::class,
        'deleted' => TweetLikeDeleted::class,
    ];
}
