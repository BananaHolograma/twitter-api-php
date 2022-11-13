<?php

namespace Database\Factories\Shared;

use Domain\Shared\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Domain\Shared\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => Str::of(fake()->name())->limit(50),
            'username' => Str::of(fake()->unique()->userName())->limit(15, ''),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'description' => fake()->realTextBetween(10, 100),
            'location' => fake()->city(),
            'protected' => fake()->boolean(30),
            'url' => fake()->boolean() ? fake()->url() : null,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified_email()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function unverified_account()
    {
        return $this->state(fn (array $attributes) => [
            'verified_at' => false,
        ]);
    }

    public function verified_account()
    {
        return $this->state(fn (array $attributes) => [
            'verified_at' => fake()->dateTimeBetween('-2 months', 'now'),
        ]);
    }

    public function protected()
    {
        return $this->state(fn (array $attributes) => [
            'protected' => true,
        ]);
    }

    public function unprotected()
    {
        return $this->state(fn (array $attributes) => [
            'protected' => false,
        ]);
    }
}
