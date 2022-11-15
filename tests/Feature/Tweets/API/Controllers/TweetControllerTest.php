<?php

namespace Tests\Feature\Tweets\API\Controllers;

use App\Events\Tweets\TweetCreatedEvent;
use Domain\Shared\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\postJson;

uses(RefreshDatabase::class)->group('api');

it('should throw validation errors when data is wrong on tweet creation', function () {
    actingAsApiUser();
    assertDatabaseCount('tweets', 0);

    postJson(route('api.create-tweet'), [
        'text' => fake()->realText(150),
        'reply_settings' => 'TO THE WORLD',
        'visible_for' => ['superman id', 'spiderman id'],
    ])->assertUnprocessable()
        ->assertInvalid([
            'text' => 'The text must not be greater than 140 characters.',
            'reply_settings' => 'The selected reply settings is invalid.',
            'visible_for.0' => [
                'The selected visible_for.0 is invalid.',
            ],
            'visible_for.1' => [
                'The selected visible_for.1 is invalid.',
            ],
        ]);

    assertDatabaseCount('tweets', 0);
});

it('should create a new tweet succesfully', function () {
    Event::fake([TweetCreatedEvent::class]);

    $user = User::factory()->create();

    actingAsApiUser($user);

    assertDatabaseMissing('tweets', ['author_id' => $user->id]);

    postJson(route('api.create-tweet'), [
        'text' => 'This tweet is not a tweet',
        'lang' => 'en',
        'visible_for' => []
    ])->assertOk();

    Event::assertDispatched(function (TweetCreatedEvent $event) use ($user) {
        return $event->tweet->author_id === $user->id;
    });

    assertDatabaseHas('tweets', ['author_id' => $user->id]);
});
