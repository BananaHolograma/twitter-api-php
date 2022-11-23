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
    Route::prefix('me')
        ->controller(UserController::class)
        ->group(function () {
            Route::get('followers', 'followers')->name('api.me-followers');
            Route::get('following', 'following')->name('api.me-following');
            Route::get('mutes', 'mutes')->name('api.me-mutes');
            Route::get('blocks', 'blocks')->name('api.me-blocks');
            Route::get('likes', 'likedTweets')->name('api.me-liked-tweets');
            Route::get('tweets', 'tweets')->name('api.me-tweets');
        });

    Route::prefix('users')
        ->controller(UserController::class)
        ->group(function () {
            Route::get('{user}/followers', 'followers')->name('api.user-followers');
            Route::get('{user}/following', 'following')->name('api.user-following');
            Route::get('{user}/likes', 'likedTweets')->name('api.user-liked-tweets');
            Route::get('{user}/tweets', 'tweets')->name('api.user-tweets');
        });

    Route::prefix('tweets')
        ->controller(TweetController::class)
        ->group(function () {
            Route::get('/{tweet}/likes', 'likesForTweet')->name('api.tweet-likes');
            Route::get('/{tweet}/replies', 'repliesForTweet')->name('api.tweet-replies');

            Route::post('process', 'processTweet')->name('api.process-tweet');
            Route::post('/', 'create')->name('api.create-tweet');
            Route::put('/', 'update')->name('api.update-tweet');

            Route::put('/{user}/like/{tweet}', 'like')->name('api.tweet-like');
            Route::delete('/{user}/like/{tweet}', 'deleteLike')->name('api.delete-tweet-like');
        });
});
