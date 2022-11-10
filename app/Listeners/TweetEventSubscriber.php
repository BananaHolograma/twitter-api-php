<?php

namespace App\Listeners;

use App\Events\TweetCreatedEvent;

class TweetEventSubscriber
{
    /**
     * Handle user login events.
     */
    public function handleTweetCreation(TweetCreatedEvent $event)
    {
        $tweet = $event->tweet;

        $tweet->public_metrics = [
            "retweet_count" => 0,
            "reply_count" => 0,
            "like_count" => 0,
            "quote_count" => 0
        ];

        $tweet->non_public_metrics = [
            "impression_count" => 0,
            "url_link_clicks" => 0,
            "user_profile_clicks" => 0
        ];

        $tweet->organic_metrics = [
            "impression_count" =>  0,
            "like_count" =>  0,
            "reply_count" =>  0,
            "retweet_count" =>  0,
            "url_link_clicks" =>  0,
            "user_profile_clicks" =>  0
        ];

        $tweet->promoted_metrics = [
            "impression_count" => 0,
            "like_count" => 0,
            "reply_count" => 0,
            "retweet_count" => 0,
            "url_link_clicks" => 0,
            "user_profile_clicks" => 0
        ];

        if (is_null($tweet->edit_controls)) {
            $tweet->edit_controls = [
                "edits_remaining" => 5,
                "is_edit_eligible" => true,
                "editable_until" => now()->addMinutes(30)->toDateTimeString(),
            ];
        }

        $tweet->save();
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
