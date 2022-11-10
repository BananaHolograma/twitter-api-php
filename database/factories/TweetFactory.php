<?php

namespace Database\Factories;

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
            'lang' => 'en',
            'possibly_sensitive' => false,
            'source' => fake()->randomElement(['Twitter Web App', 'API v2', 'Bot']),
            'reply_settings' => fake()->randomElement(['everyone', 'mentioned_users', 'followers']),
            'withheld' =>  ["copyright" => false]
        ];
    }
}
