<?php

namespace Database\Factories\Tweets;

use Domain\Shared\Models\User;
use Domain\Tweets\Enums\ReplySettingEnum;
use Domain\Tweets\Models\{Tweet, TweetMetrics};
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'source' => fake()->randomElement(['Twitter Web App', 'API v2', 'Bot']),
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
                ReplySettingEnum::FOLLOWERS
            ]),
            'withheld' => ['copyright' => false],
        ];
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
