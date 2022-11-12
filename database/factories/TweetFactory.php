<?php

namespace Database\Factories;

use App\Models\Tweet;
use App\Models\TweetMetrics;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tweet>
 */
class TweetFactory extends Factory
{
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
            'reply_settings' => fake()->randomElement(['everyone', 'mentioned_users', 'followers']),
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
