<?php

namespace Tests\Feature\Tweets\API\Controllers;

use Domain\Shared\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\postJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class)->group('api');

it('should throw validation errors when data is wrong on tweet creation', function () {

    actingAsApiUser();
    assertDatabaseCount('tweets', 0);

    postJson(route('api.create-tweet'), [
        'text' => fake()->realText(150),
        'reply_settings' => 'TO THE WORLD',
        'visible_for' => ['superman id', 'spiderman id']
    ])->assertUnprocessable()
        ->assertInvalid([
            'text' => 'The text must not be greater than 140 characters.',
            'reply_settings' => 'The selected reply settings is invalid.',
            "visible_for.0" => [
                "The selected visible_for.0 is invalid."
            ],
            "visible_for.1" => [
                "The selected visible_for.1 is invalid."
            ]
        ]);

    assertDatabaseCount('tweets', 0);
});
