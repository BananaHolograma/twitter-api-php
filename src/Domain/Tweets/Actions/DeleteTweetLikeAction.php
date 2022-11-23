<?php

namespace Domain\Tweets\Actions;

use Domain\Shared\Models\User;
use Domain\Tweets\Models\Tweet;

class DeleteTweetLikeAction
{
    public function execute(User $user, Tweet $tweet): Tweet
    {
        if ($tweet->likes->contains($user->id)) {
            $tweet->likes()->detach([$user->id]);

            return $tweet->fresh('likes');
        }

        return $tweet;
    }
}
