<?php

namespace Tests\Feature\Tweets\API\Controllers;

use Domain\Shared\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;

uses()->group('api');

it('should throw validation errors when data is wrong on tweet creation', function () {
    $user = User::factory()->create();

    actingAs($user, 'api');

    postJson(route('api.create-tweet'), [
        'text' => fake()->realText(150),
    ])->assertUnprocessable()
        ->assertInvalid(['text' => '']);
});
