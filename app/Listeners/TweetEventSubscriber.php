<?php

namespace App\Listeners;

use App\Events\TweetCreatedEvent;
use App\Models\TweetMetrics;
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
                if (! $tweet->metrics()->exists()) {
                    $tweet->metrics()->save(new TweetMetrics);
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
