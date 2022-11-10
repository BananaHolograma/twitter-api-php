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
        User::factory(100)->create();
        User::factory(50)->verified_account()->create();
        $user = User::factory()->create([
            'email' => 'twitter@admin.com',
        ]);

        Tweet::factory()->create(['author_id' => $user->getKey()]);
    }
}
