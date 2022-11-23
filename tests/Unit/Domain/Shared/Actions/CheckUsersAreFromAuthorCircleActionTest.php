<?php

use Domain\Shared\Actions\CheckUsersAreFromAuthorCircleAction;
use Domain\Shared\Exceptions\UsersDoesNotBelongToAuthorCircle;
use Domain\Shared\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class)->group('domain/shared');

/**
 * @doesNotPerformAssertions
 */
it('should do nothing if no ids are passed as parameter', function () {
    $author = User::factory()->create();

    $this->expectNotToPerformAssertions();

    app(CheckUsersAreFromAuthorCircleAction::class)->execute($author);
});

it('should throw exception if some user does not belongs to author circle', function () {
    $author = User::factory()->has(User::factory()->count(3), 'followers')->create();
    $user_not_in_circle = User::factory()->create();

    expect(
        fn () => app(CheckUsersAreFromAuthorCircleAction::class)->execute(
            $author,
            $author->followers()->allRelatedIds()->merge([$user_not_in_circle->id])->all()
        )
    )->toThrow(
        UsersDoesNotBelongToAuthorCircle::class,
        "For the author {$author->id} the next users does not belongs to his circle: {$user_not_in_circle->id}"
    );
});

it('should continue the execution if all the users belongs to author circle', function () {
    $author = User::factory()->has(User::factory()->count(3), 'followers')->create();

    $this->expectNotToPerformAssertions();

    app(CheckUsersAreFromAuthorCircleAction::class)->execute(
        $author,
        $author->followers()->allRelatedIds()->all()
    );
});
