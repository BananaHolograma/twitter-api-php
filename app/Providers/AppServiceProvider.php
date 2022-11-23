<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Testing\TestResponse;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        TestResponse::macro('assertPaginated', function (
            string $uri,
            int $current_page,
            int $start_page,
            int $last_page,
            int $total = 0
        ) {
            /** @var TestResponse $this */
            return $this->assertJson([
                'meta' => [
                    'current_page' => $current_page,
                    'first_page_url' => "$uri?page={$start_page}",
                    'last_page_url' => "$uri?page={$last_page}",
                    'total' => $total,
                ],
            ]);
        });
    }
}
