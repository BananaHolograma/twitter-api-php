<?php

namespace Domain\Tweets\Events;

use Domain\Tweets\Models\Tweet;
use Domain\Tweets\Models\UserTweetLike;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TweetLikeDeletedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Tweet $tweet;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(public UserTweetLike $pivot)
    {
        $this->tweet = $pivot->pivotParent;
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
