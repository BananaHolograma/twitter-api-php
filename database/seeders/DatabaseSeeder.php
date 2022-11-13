<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Domain\Shared\Models\User;
use Domain\Tweets\Models\Tweet;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(25)
            ->has(Tweet::factory()->count(fake()->numberBetween(10, 50)), 'tweets')
            ->create();
        User::factory(25)
            ->has(Tweet::factory()->count(fake()->numberBetween(10, 50)), 'tweets')
            ->verified_account()
            ->create();

        Tweet::inRandomOrder()->limit(20)
            ->each(
                fn (Tweet $tweet) => $tweet->likes()->attach(
                    User::select('id')
                        ->inRandomOrder()
                        ->limit(fake()->numberBetween(1, 10))
                        ->pluck('id')
                )
            );
    }
}
