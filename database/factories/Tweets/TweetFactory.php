<?php

namespace Database\Factories\Tweets;

use Domain\Shared\Models\User;
use Domain\Tweets\Enums\ReplySettingEnum;
use Domain\Tweets\Models\Tweet;
use Domain\Tweets\Models\TweetMetrics;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Domain\Models\Tweets\Tweet>
 */
class TweetFactory extends Factory
{
    protected $model = Tweet::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'author_id' => User::factory(),
            'text' => fake()->realText(140),
            'lang' => fake()->languageCode(),
            'possibly_sensitive' => false,
            'in_reply_to_tweet_id' => null,
            'in_reply_to_author_id' => null,
            'retweet_from_tweet_id' => null,
            'source' => fake()->randomElement(['Twitter Web App', 'API v1', 'API v2', 'Bot']),
            'visible_for' => [],
            'edit_controls' => [
                'edits_remaining' => 5,
                'is_edit_eligible' => true,
                'editable_until' => now()
                    ->addMinutes(30)
                    ->toDateTimeString(),
            ],
            'reply_settings' => fake()->randomElement([
                ReplySettingEnum::EVERYONE,
                ReplySettingEnum::MENTIONED_FOLLOWERS,
                ReplySettingEnum::FOLLOWERS,
            ]),
            'withheld' => ['copyright' => false],
        ];
    }

    public function visibleFor(array $users = [])
    {
        return $this->state(function (array $attributes) use ($users) {
            return [
                'visible_for' => $users,
            ];
        });
    }

    public function notEditableByTries()
    {
        return $this->state(function (array $attributes) {
            return [
                'edit_controls' => [
                    'edits_remaining' => 0,
                    'is_edit_eligible' => false,
                    'editable_until' => now()->addMinutes(30)->toDateTimeString(),
                ],
            ];
        });
    }

    public function notEditableByTimeWindow()
    {
        return $this->state(function (array $attributes) {
            return [
                'edit_controls' => [
                    'edits_remaining' => fake()->numberBetween(1, 5),
                    'is_edit_eligible' => false,
                    'editable_until' => now()->subMinutes(5)->toDateTimeString(),
                ],
            ];
        });
    }

    public function replyTo(Tweet $target_tweet, ?User $user)
    {
        return $this->state(function (array $attributes) use ($target_tweet, $user) {
            return [
                'conversation_id' => $target_tweet->id,
                'reply_to_tweet_id' => $target_tweet->id,
                'reply_to_author_id' => $user?->id ?? $target_tweet->author->id
            ];
        });
    }

    public function retweetFrom(Tweet $original_tweet)
    {
        return $this->state(function (array $attributes) use ($original_tweet) {
            return [
                ...$attributes,
                ...Arr::only(
                    $original_tweet->toArray(),
                    ['text', 'lang', 'possibly_sensitive']
                ),
                'retweet_from_tweet_id' => $original_tweet->id
            ];
        });
    }

    public function configure()
    {
        return $this->afterCreating(function (Tweet $tweet) {
            $tweet->metrics()
                ->save(fake()->boolean() ?
                    TweetMetrics::factory()->create(['tweet_id' => $tweet->getKey()]) :
                    TweetMetrics::factory()->asNew()->create(['tweet_id' => $tweet->getKey()]));
        });
    }
}
