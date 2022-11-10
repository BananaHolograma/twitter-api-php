<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Tweet;
use App\Models\User;
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
            ->has(Tweet::factory()->count(fake()->numberBetween(1, 25)), 'tweets')
            ->create();
        User::factory(25)->has(Tweet::factory()->count(fake()->numberBetween(1, 25)), 'tweets')
            ->verified_account()
            ->create();
    }
}
