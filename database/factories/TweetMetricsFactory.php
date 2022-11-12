<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TweetMetrics>
 */
class TweetMetricsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'impression_count' => fake()->numberBetween(0, 10000),
            'reply_count' => fake()->numberBetween(0, 10000),
            'like_count' => fake()->numberBetween(0, 10000),
            'retweet_count' => fake()->numberBetween(0, 10000),
            'quote_count' => fake()->numberBetween(0, 10000),
            'url_link_clicks' => fake()->numberBetween(0, 10000),
            'user_profile_clicks' => fake()->numberBetween(0, 10000),
        ];
    }

    public function asNew()
    {
        return $this->state(function (array $attributes) {
            return [
                'impression_count' => 0,
                'reply_count' => 0,
                'like_count' => 0,
                'retweet_count' => 0,
                'quote_count' => 0,
                'url_link_clicks' => 0,
                'user_profile_clicks' => 0,
            ];
        });
    }
}
