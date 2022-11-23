<?php

namespace Domain\Tweets\Subscribers;

use Domain\Tweets\Events\TweetCreatedEvent;
use Domain\Tweets\Models\TweetMetrics;
use Illuminate\Support\Facades\DB;

class TweetEventSubscriber
{
    /**
     * Handle user login events.
     */
    public function handleTweetCreation(TweetCreatedEvent $event)
    {
        DB::transaction(function () use ($event) {
            $tweet = $event->tweet;

            if ($tweet->wasRecentlyCreated) {
                if (!$tweet->metrics()->exists()) {
                    $tweet->metrics()->save(new TweetMetrics());
                }

                if (is_null($tweet->edit_controls)) {
                    $tweet->edit_controls = [
                        'edits_remaining' => 5,
                        'is_edit_eligible' => true,
                        'editable_until' => now()
                            ->addMinutes(30)
                            ->toDateTimeString(),
                    ];
                }
                if (is_null($tweet->conversation_id)) {
                    $tweet->conversation_id = $tweet->reply_to_tweet_id ?? $tweet->id;
                }

                $tweet->save();
            }
        });
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
            TweetCreatedEvent::class,
            [self::class, 'handleTweetCreation']
        );
    }
}
