<?php

namespace App\Http\API\Shared\Controllers;

use App\Http\Controllers\Controller;
use Domain\Shared\DataTransferObjects\UserData;
use Domain\Shared\Models\User;
use Domain\Tweets\DataTransferObjects\TweetData;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
    }

    public function tweets(Request $request, ?User $user = null)
    {
        return TweetData::collection($user ?? $request->user('api')->tweets()->paginate());
    }

    public function likedTweets(Request $request, ?User $user = null)
    {
        return TweetData::collection($user ?? $request->user('api')->likedTweets()->paginate());
    }

    public function mutes(Request $request, ?User $user = null)
    {
        return UserData::collection(
            $user ?? $request->user('api')->mutedUsers()->paginate()
        );
    }

    public function blocks(Request $request, ?User $user = null)
    {
        return UserData::collection(
            $user ?? $request->user('api')->blockedUsers()->paginate()
        );
    }
}
