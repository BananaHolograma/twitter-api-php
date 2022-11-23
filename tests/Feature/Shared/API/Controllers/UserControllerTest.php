<?php

namespace Tests\Feature\Shared\API\Controllers;

use Domain\Shared\Models\User;
use Domain\Tweets\Models\Tweet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\getJson;

uses(RefreshDatabase::class)->group('api');

it('should retrieve the muted users paginated for the authenticated user even when there is not', function () {
    $user = User::factory()->create();

    actingAsApiUser($user);

    getJson(route('api.me-mutes'))
        ->assertOk()
        ->assertJson(['data' => []])->assertPaginated(route('api.me-mutes'), 1, 1, 1, 0);
});

it('should retrieve the muted users paginated for the authenticated user', function () {
    $user = User::factory()->create();

    actingAsApiUser($user);

    $muted_users = User::factory()->count(5)->create();

    $user->mutedUsers()->attach($muted_users->pluck('id'));

    getJson(route('api.me-mutes'))
        ->assertOk()
        ->assertJson([
            'data' => $muted_users->pluck('id')->map(fn ($id) => compact('id'))->all(),
        ])->assertPaginated(route('api.me-mutes'), 1, 1, 1, $muted_users->count());
});

it('should retrieve the blocked users paginated for the authenticated user even when there is not', function () {
    $user = User::factory()->create();

    actingAsApiUser($user);

    getJson(route('api.me-blocks'))
        ->assertOk()
        ->assertJson([
            'data' => [],
        ])->assertPaginated(route('api.me-blocks'), 1, 1, 1, 0);
});

it('should retrieve the blocked users paginated for the authenticated user', function () {
    $user = User::factory()->create();

    actingAsApiUser($user);

    $blocked_users = User::factory()->count(5)->create();

    $user->blockedUsers()->attach($blocked_users->pluck('id'));

    getJson(route('api.me-blocks'))
        ->assertOk()
        ->assertJson(['data' => $blocked_users->pluck('id')->map(fn ($id) => compact('id'))->all()])
        ->assertPaginated(route('api.me-blocks'), 1, 1, 1, $blocked_users->count());
});

it('should retrieve the liked tweets for authenticated user', function () {
    $user = User::factory()
        ->has(Tweet::factory()->count(5), 'likedTweets')
        ->create();

    actingAsApiUser($user);

    getJson(route('api.me-liked-tweets'))
        ->assertOk()
        ->assertJson(['data' => $user->likedTweets->pluck('id')->map(fn ($id) => compact('id'))->all()])
        ->assertPaginated(route('api.me-liked-tweets'), 1, 1, 1, $user->likedTweets->count());
});

it('should retrieve the tweets for authenticated user', function () {
    $user = User::factory()
        ->has(Tweet::factory()->count(5), 'tweets')
        ->create();

    actingAsApiUser($user);

    getJson(route('api.me-tweets'))
        ->assertOk()
        ->assertJson(['data' => $user->tweets->pluck('id')->map(fn ($id) => compact('id'))->all()])
        ->assertPaginated(route('api.me-tweets'), 1, 1, 1, $user->tweets->count());
});

it('should throw not found exception when liked tweets for selected user endpoint receives an user that does not exists', function () {
    actingAsApiUser(User::factory()->create());

    getJson(route('api.user-liked-tweets', ['user' => 'fake']))
        ->assertNotFound();
});

it('should retrieve the liked tweets for selected user', function () {
    $selected_user = User::factory()
        ->has(Tweet::factory()->count(5), 'likedTweets')
        ->create();

    actingAsApiUser(User::factory()->create());

    $url = route('api.user-liked-tweets', ['user' => $selected_user->id]);

    getJson($url)
        ->assertOk()
        ->assertJson(['data' => $selected_user->likedTweets->pluck('id')->map(fn ($id) => compact('id'))->all()])
        ->assertPaginated($url, 1, 1, 1, $selected_user->likedTweets->count());
});

it('should throw not found exception when tweets for selected user endpoint receives an user that does not exists', function () {
    actingAsApiUser(User::factory()->create());

    getJson(route('api.user-tweets', ['user' => 'fake']))
        ->assertNotFound();
});

it('should retrieve the tweets for selected user', function () {
    $selected_user = User::factory()
        ->has(Tweet::factory()->count(5), 'tweets')
        ->create();

    actingAsApiUser(User::factory()->create());

    $url = route('api.user-tweets', ['user' => $selected_user->id]);

    getJson($url)
        ->assertOk()
        ->assertJson(['data' => $selected_user->tweets->pluck('id')->map(fn ($id) => compact('id'))->all()])
        ->assertPaginated($url, 1, 1, 1, $selected_user->tweets->count());
});
