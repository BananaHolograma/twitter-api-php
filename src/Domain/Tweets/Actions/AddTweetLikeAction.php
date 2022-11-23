<?php

namespace Domain\Tweets\Actions;

use Domain\Shared\Models\User;
use Domain\Tweets\Models\Tweet;

class addTweetLikeAction
{
    public function execute(User $user, Tweet $tweet): Tweet
    {
        if (! $tweet->likes()->contains($user->id)) {
            $tweet->likes()->attach($user->id);

            return $tweet->fresh('likes');
        }

        return $tweet;
    }
}
