<?php

namespace Domain\Tweets\Events;

use Domain\Shared\Models\User;
use Domain\Tweets\Models\Tweet;
use Domain\Tweets\Models\UserTweetLike;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TweetLikeCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;

    public Tweet $tweet;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(public UserTweetLike $pivot)
    {
        $this->user = $pivot->user;
        $this->tweet = $pivot->tweet;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
