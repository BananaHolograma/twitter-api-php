<?php

namespace App\Http\API\Tweets\Controllers;

use App\Http\Controllers\Controller;
use Domain\Tweets\Actions\CreateTweetAction;
use Domain\Tweets\Actions\ProcessTweetAction;
use Domain\Tweets\Actions\UpdateTweetAction;
use Domain\Tweets\DataTransferObjects\UpsertTweetData;
use Domain\Tweets\Models\Tweet;

class TweetController extends Controller
{
    public function __construct(
        private readonly ProcessTweetAction $processTweetAction,
        private readonly CreateTweetAction $createTweetAction,
        private readonly UpdateTweetAction $updateTweetAction
    ) {
    }

    public function processTweet(UpsertTweetData $request)
    {
        $tweet = $this->processTweetAction->execute(auth('api')->user(), $request);

        return response()->json($tweet);
    }

    public function create(UpsertTweetData $request)
    {
        $tweet = $this->createTweetAction->execute(auth('api')->user(), $request);

        return response()->json($tweet);
    }

    public function update(UpsertTweetData $request)
    {
        $tweet = $this->updateTweetAction->execute(auth('api')->user(), $request);

        return response()->json($tweet);
    }
}
