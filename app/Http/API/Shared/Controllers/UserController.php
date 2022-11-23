<?php

namespace App\Http\API\Shared\Controllers;

use App\Http\Controllers\Controller;
use Domain\Shared\DataTransferObjects\UserData;
use Domain\Tweets\DataTransferObjects\TweetData;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
    }

    public function tweets(Request $request)
    {
        return TweetData::collection($request->user('api')->tweets()->paginate());
    }

    public function likedTweets(Request $request)
    {
        return TweetData::collection($request->user('api')->likedTweets()->paginate());
    }

    public function mutes(Request $request)
    {
        return UserData::collection(
            $request->user('api')->mutedUsers()->paginate()
        );
    }

    public function blocks(Request $request)
    {
        return UserData::collection(
            $request->user('api')->blockedUsers()->paginate()
        );
    }
}
