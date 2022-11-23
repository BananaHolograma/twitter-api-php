<?php

namespace App\Http\API\Shared\Controllers;

use App\Http\Controllers\Controller;
use Domain\Shared\DataTransferObjects\UserData;
use Domain\Shared\Models\User;
use Domain\Tweets\DataTransferObjects\TweetData;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function tweets(Request $request, ?User $user = null)
    {
        $user = $user ?? $request->user('api');

        return TweetData::collection($user->tweets()->paginate());
    }

    public function likedTweets(Request $request, ?User $user = null)
    {
        $user = $user ?? $request->user('api');

        return TweetData::collection($user->likedTweets()->paginate());
    }

    public function mutes(Request $request, ?User $user = null)
    {
        $user = $user ?? $request->user('api');

        return UserData::collection($user->mutedUsers()->paginate());
    }

    public function blocks(Request $request, ?User $user = null)
    {
        $user = $user ?? $request->user('api');

        return UserData::collection($user->blockedUsers()->paginate());
    }
}
