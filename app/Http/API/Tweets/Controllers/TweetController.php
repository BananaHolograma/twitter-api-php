<?php

namespace App\Http\API\Tweets\Controllers;

use App\Http\Controllers\Controller;
use Domain\Tweets\Actions\ProcessTweetAction;
use Domain\Tweets\DataTransferObjects\UpsertTweetData;

class TweetController extends Controller
{
    public function __construct(private readonly ProcessTweetAction $processTweetAction)
    {
    }

    public function create(UpsertTweetData $request)
    {
        $tweet = $this->processTweetAction->execute(auth()->user(), $request);

        return response()->json($tweet);
    }
}
