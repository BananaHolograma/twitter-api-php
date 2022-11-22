<?php

use Domain\Shared\Models\User;
use Domain\Tweets\Actions\VerifyAccountAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Spatie\PestPluginTestTime\testTime;

uses(RefreshDatabase::class)->group('domain/tweets');

beforeEach(function () {
    testTime()->freeze('2021-01-02 12:34:56');
});

it('should verify account when is not verified yet', function () {
    $user = User::factory()->create(['verified_at' => null]);

    app(VerifyAccountAction::class)->execute($user);

    assertEquals($user->verified_at, now());
});

it('should verify account when is not verified yet on the selected date', function () {
    $user = User::factory()->create(['verified_at' => null]);

    app(VerifyAccountAction::class)->execute($user, now()->subWeek());

    assertEquals($user->verified_at, now()->subWeek());
});

it('should not verify account when is already verified', function () {
    $user = User::factory()->create(['verified_at' => now()->subMonth()]);

    app(VerifyAccountAction::class)->execute($user);

    $user->refresh();
    assertEquals($user->verified_at, now()->subMonth());
});
