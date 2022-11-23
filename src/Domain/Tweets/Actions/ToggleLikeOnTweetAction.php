<?php

namespace Domain\Tweets\Actions;

use Domain\Shared\Models\User;
use Domain\Tweets\Models\Tweet;

class ToggleLikeOnTweetAction
{
    public function __construct(
        private readonly addTweetLikeAction $addTweetLikeAction,
        private readonly DeleteTweetLikeAction $deleteTweetLikeAction
    ) {
    }
    public function execute(User $user, Tweet $tweet,): Tweet
    {
        $tweet->likes()->toggle([$user->id]);

        return $tweet->fresh('likes');
    }
}
