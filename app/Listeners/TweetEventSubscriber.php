<?php

namespace App\Listeners;

use App\Events\Tweets\TweetCreatedEvent;
use App\Events\Tweets\TweetUpdatingEvent;
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
                if (! $tweet->metrics()->exists()) {
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

    public function handleTweetEditing(TweetUpdatingEvent $event)
    {
        $tweet = $event->tweet;

        if (! $tweet->wasRecentlyCreated) {
            $edits_remaining = (int) $tweet->edit_controls['edits_remaining'] - 1;

            $tweet->edit_controls = [
                ...$tweet->edit_controls,
                'edits_remaining' => $edits_remaining,
                'is_edit_eligible' => $edits_remaining > 0,
            ];
        }
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

        $events->listen(
            TweetUpdatingEvent::class,
            [self::class, 'handleTweetEditing']
        );
    }
}
