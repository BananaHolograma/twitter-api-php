<?php

use App\Http\API\Tweets\Controllers\TweetController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->group(function () {
    Route::prefix('tweets')
        ->controller(TweetController::class)
        ->group(function () {
            Route::post('/process', 'processTweet')->name('api.process-tweet');
            Route::post('/', 'create')->name('api.create-tweet');
            Route::put('/', 'update')->name('api.update-tweet');
        });
});
