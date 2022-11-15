<?php

namespace Domain\Tweets\Exceptions;

use Domain\Tweets\Models\Tweet;
use RuntimeException;

class TweetCannotBeEditAnymore extends RuntimeException
{
    public function __construct(Tweet $tweet)
    {
        $message = $tweet->edit_controls['edits_remaining'] < 0 ?
            __("The tweet {$tweet->id} reaches the maximum edits allowed") :
            __("The tweet {$tweet->id} allowed editing time has expired");

        parent::__construct($message);
    }
}
