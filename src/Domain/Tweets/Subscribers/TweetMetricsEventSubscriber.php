<?php

namespace Domain\Tweets\Subscribers;

use Domain\Tweets\Events\TweetLikeCreatedEvent;
use Domain\Tweets\Events\TweetLikeDeletedEvent;

class TweetMetricsEventSubscriber
{
    /**
     * Handle user login events.
     */
    public function handleTweetLiked(TweetLikeCreatedEvent $event)
    {
        $event->tweet->metrics()->increment('like_count');
    }

    public function handleTweetUnLiked(TweetLikeDeletedEvent $event)
    {
        $event->tweet->metrics()->decrement('like_count');
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     * @return void
     */
    public function subscribe($events)
    {
        $events->listen(
            TweetLikeCreatedEvent::class,
            [self::class, 'handleTweetLiked'],
        );

        $events->listen(
            TweetLikeDeletedEvent::class,
            [self::class, 'handleTweetUnLiked'],
        );
    }
}
