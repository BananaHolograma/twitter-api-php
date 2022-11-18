<?php

use Carbon\Carbon;
use Domain\Shared\Models\User;
use Domain\Tweets\Actions\ProcessTweetAction;
use Domain\Tweets\DataTransferObjects\UpsertTweetData;
use Domain\Tweets\Enums\ReplySettingEnum;
use Domain\Tweets\Exceptions\TweetCannotBeEditAnymore;
use Domain\Tweets\Models\Tweet;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function PHPUnit\Framework\assertEquals;

uses(RefreshDatabase::class)->group('domain/tweets');

it('should create the tweet for the corresponding author when it does not exists', function () {
    assertDatabaseCount('tweets', 0);

    $author = User::factory()->create();
    $data = UpsertTweetData::from([
        'text' => "Text for the world",
        'possibly_sensitive' => true,
        'reply_settings' => ReplySettingEnum::FOLLOWERS
    ]);

    $tweet_created = app(ProcessTweetAction::class)->execute($author, $data);

    assertDatabaseHas('tweets', ['author_id' => $author->id]);

    $this->assertEquals($author->id, $tweet_created->author->id);
    $this->assertEquals(
        $data->only('text', 'possibly_sensitive', 'reply_settings')
            ->toArray(),
        [
            'text' => $tweet_created->text,
            'possibly_sensitive' => $tweet_created->possibly_sensitive,
            'reply_settings' => ReplySettingEnum::FOLLOWERS->value
        ]
    );
});

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
