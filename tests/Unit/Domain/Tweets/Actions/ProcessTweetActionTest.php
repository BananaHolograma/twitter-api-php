<?php

use Carbon\Carbon;
use Domain\Tweets\Actions\ProcessTweetAction;
use Domain\Tweets\DataTransferObjects\UpsertTweetData;
use Domain\Tweets\Exceptions\TweetCannotBeEditAnymore;
use Domain\Tweets\Models\Tweet;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\assertDatabaseCount;
use function PHPUnit\Framework\assertEquals;

uses(RefreshDatabase::class)->group('domain/tweets');


it('should update a tweet if this exists and is still editable', function () {
    $tweet = Tweet::factory()->create();

    assertDatabaseCount('tweets', 1);

    $data = UpsertTweetData::from([
        'id' => $tweet->id,
        'text' => "Editing my content proudly",
    ]);

    $tweet_updated = app(ProcessTweetAction::class)->execute($tweet->author, $data);

    assertDatabaseCount('tweets', 1);
    assertEquals($tweet_updated->id, $data->id);
    assertEquals($tweet_updated->text, $data->text);
});

it('should throw exception when tweet is not edit eligible anymore and tries to be updated', function () {
    Carbon::setTestNow(Carbon::create(2022, 11, 18, 15, 45));

    $tweet = Tweet::factory()->notEditableByTries()->create();
    $data = UpsertTweetData::from([
        'id' => $tweet->id,
        'text' => "Editing my content proudly",
    ]);

    expect(
        fn () => app(ProcessTweetAction::class)->execute($tweet->author, $data)
    )->toThrow(TweetCannotBeEditAnymore::class, "The tweet {$tweet->id} reaches the maximum edits allowed");

    $tweet = Tweet::factory()->notEditableByTimeWindow()->create();

    $data->id = $tweet->id;

    expect(
        fn () => app(ProcessTweetAction::class)->execute($tweet->author, $data)
    )->toThrow(TweetCannotBeEditAnymore::class, "The tweet {$tweet->id} allowed editing time has expired");
});
