<?php

namespace Tests\Unit\Domain\Tweets\Actions;

use Domain\Shared\Models\User;
use Domain\Tweets\Actions\ToggleLikeOnTweetAction;
use Domain\Tweets\Events\TweetLikeCreatedEvent;
use Domain\Tweets\Events\TweetLikeDeletedEvent;
use Domain\Tweets\Models\Tweet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

uses(RefreshDatabase::class)->group('domain/tweets');

it('should attach a new like if tweet does not contain this user', function () {
    Event::fake(TweetLikeCreatedEvent::class);

    $tweet = Tweet::factory()->has(User::factory()->count(3), 'likes')->create();
    $new_user_like = User::factory()->create();

    assertFalse($tweet->likes->contains($new_user_like));

    $updated_tweet = app(ToggleLikeOnTweetAction::class)->execute($new_user_like, $tweet);

    assertTrue($updated_tweet->likes->contains($new_user_like));

    Event::assertDispatched(
        fn (TweetLikeCreatedEvent $event) => $event->tweet->is($updated_tweet)
    );
});

it('should detach the like if tweet likes contains this user', function () {
    Event::fake(TweetLikeDeletedEvent::class);

    $tweet = Tweet::factory()->has(User::factory()->count(3), 'likes')->create();
    $dislike = $tweet->likes()->inRandomOrder()->first();

    $updated_tweet = app(ToggleLikeOnTweetAction::class)->execute($dislike, $tweet);

    assertFalse($updated_tweet->likes->contains($dislike));

    Event::assertDispatched(
        fn (TweetLikeDeletedEvent $event) => $event->tweet->is($updated_tweet)
    );
});
