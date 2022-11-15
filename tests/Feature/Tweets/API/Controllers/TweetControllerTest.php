<?php

namespace Tests\Feature\Tweets\API\Controllers;

use Domain\Tweets\DataTransferObjects\UpsertTweetData;

use function Pest\Laravel\postJson;

uses()->group('api');

it('should throw validation errors when data is wrong on tweet creation', function () {

    $response = postJson(route('api.create-tweet'), UpsertTweetData::from([
        'text' => fake()->realText(150),
    ])->toArray())
        ->assertInvalid(['text' => '']);
});
