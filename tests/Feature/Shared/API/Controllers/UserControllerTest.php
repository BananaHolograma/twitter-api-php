<?php

namespace Tests\Feature\Shared\API\Controllers;

use Domain\Shared\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\getJson;

uses(RefreshDatabase::class)->group('api');

it('should retrieve the muted users paginated for the authenticated user even when there is not', function () {
    $user = User::factory()->create();

    actingAsApiUser($user);

    getJson(route('api.me-mutes'))
        ->assertOk()
        ->assertJson([
            'data' => [],
            'meta' => [
                'current_page' => 1,
                'first_page_url' => route('api.me-mutes') . '?page=1',
                'last_page_url' => route('api.me-mutes') . '?page=1',
                'total' => 0,
            ],
        ]);
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
            'meta' => [
                'current_page' => 1,
                'first_page_url' => route('api.me-blocks') . '?page=1',
                'last_page_url' => route('api.me-blocks') . '?page=1',
                'total' => 0,
            ],
        ]);
});

it('should retrieve the blocked users paginated for the authenticated user', function () {
    $user = User::factory()->create();

    actingAsApiUser($user);

    $blocked_users = User::factory()->count(5)->create();

    $user->blockedUsers()->attach($blocked_users->pluck('id'));

    getJson(route('api.me-blocks'))
        ->assertOk()
        ->assertJson([
            'data' => $blocked_users->pluck('id')->map(fn ($id) => compact('id'))->all(),
            'meta' => [
                'current_page' => 1,
                'first_page_url' => route('api.me-blocks') . '?page=1',
                'last_page_url' => route('api.me-blocks') . '?page=1',
                'total' => $blocked_users->count(),
            ],
        ]);
});
