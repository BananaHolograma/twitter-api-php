<?php

namespace Database\Factories\Tweets;

use Domain\Tweets\Models\TweetMetrics;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Domain\Models\Tweets\TweetMetrics>
 */
class TweetMetricsFactory extends Factory
{
    protected $model = TweetMetrics::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'impression_count' => fake()->boolean() ? fake()->numberBetween(0, 10000) : 0,
            'reply_count' => fake()->boolean() ? fake()->numberBetween(0, 10000) : 0,
            'like_count' => fake()->boolean() ? fake()->numberBetween(0, 10000) : 0,
            'retweet_count' => fake()->boolean() ? fake()->numberBetween(0, 10000) : 0,
            'quote_count' => fake()->boolean() ? fake()->numberBetween(0, 10000) : 0,
            'video_views_count' => fake()->boolean() ? fake()->numberBetween(0, 10000) : 0,
            'url_link_clicks' => fake()->boolean() ? fake()->numberBetween(0, 10000) : 0,
            'user_profile_clicks' => fake()->boolean() ? fake()->numberBetween(0, 10000) : 0,
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
                'video_views_count' => 0,
                'url_link_clicks' => 0,
                'user_profile_clicks' => 0,
            ];
        });
    }
}
