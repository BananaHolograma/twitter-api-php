<?php

use App\Http\API\Shared\Controllers\UserController;
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
    Route::prefix('users')
        ->controller(UserController::class)
        ->group(function () {
            Route::get('/followers', 'followers')->name('api.users-followers');
            Route::get('/following', 'following')->name('api.users-following');
            Route::get('/mutes', 'mutes')->name('api.users-mutes');
            Route::get('/blocks', 'blocks')->name('api.users-blocks');
            Route::get('/likes', 'likedTweets')->name('api.users-liked-tweets');
            Route::get('tweets', 'tweets')->name('api.users-tweets');
        });

    Route::prefix('tweets')
        ->controller(TweetController::class)
        ->group(function () {
            Route::get('/{tweet}/likes', 'likesForTweet')->name('api.tweet-likes');

            Route::post('/process', 'processTweet')->name('api.process-tweet');
            Route::post('/', 'create')->name('api.create-tweet');
            Route::put('/', 'update')->name('api.update-tweet');
        });
});
