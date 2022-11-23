<?php

namespace App\Http\API\Tweets\Controllers;

use App\Http\Controllers\Controller;
use Domain\Shared\DataTransferObjects\UserData;
use Domain\Tweets\Actions\CreateTweetAction;
use Domain\Tweets\Actions\DeleteTweetLikeAction;
use Domain\Tweets\Actions\ProcessTweetAction;
use Domain\Tweets\Actions\ToggleLikeOnTweetAction;
use Domain\Tweets\Actions\UpdateTweetAction;
use Domain\Tweets\DataTransferObjects\TweetData;
use Domain\Tweets\DataTransferObjects\UpsertTweetData;
use Domain\Tweets\Models\Tweet;
use Illuminate\Http\Request;

class TweetController extends Controller
{
    public function __construct(
        private readonly ProcessTweetAction $processTweetAction,
        private readonly CreateTweetAction $createTweetAction,
        private readonly UpdateTweetAction $updateTweetAction,
        private readonly ToggleLikeOnTweetAction $toggleLikeOnTweetAction,
        private readonly DeleteTweetLikeAction $deleteTweetLikeAction
    ) {
    }

    public function likesForTweet(Tweet $tweet)
    {
        return UserData::collection($tweet->likes()->paginate());
    }

    public function repliesForTweet(Tweet $tweet)
    {
        return TweetData::collection($tweet->replies()->paginate())
            ->include('replies');
    }

    public function processTweet(UpsertTweetData $request)
    {
        $tweet = $this->processTweetAction->execute(auth('api')->user(), $request);

        return TweetData::fromModel($tweet)->toJson();
    }

    public function create(UpsertTweetData $request)
    {
        $tweet = $this->createTweetAction->execute(auth('api')->user(), $request);

        return TweetData::fromModel($tweet)->toJson();
    }

    public function update(UpsertTweetData $request)
    {
        $tweet = $this->updateTweetAction->execute(auth('api')->user(), $request);

        return TweetData::fromModel($tweet)->toJson();
    }

    public function like(Request $request, Tweet $tweet)
    {
        $tweet_updated = $this->toggleLikeOnTweetAction->execute($request->user(), $tweet);

        return TweetData::fromModel($tweet_updated)->toJson();
    }

    public function deleteLike(Request $request, Tweet $tweet)
    {
        $this->deleteTweetLikeAction->execute($request->user(), $tweet);

        return response()->noContent();
    }
}
