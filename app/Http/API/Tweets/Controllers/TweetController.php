<?php

namespace App\Http\API\Controllers;

use App\Http\Controllers\Controller;
use Domain\Tweets\DataTransferObjects\UpsertTweetData;
use Domain\Tweets\Models\Tweet;

class TweetController extends Controller
{
    public function create(UpsertTweetData $request)
    {
        Tweet::create($request->all());

        return '';
    }
}
