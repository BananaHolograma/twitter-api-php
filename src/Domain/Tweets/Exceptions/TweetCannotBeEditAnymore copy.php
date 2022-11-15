<?php

namespace Domain\Tweets\Exceptions;

use Domain\Tweets\Models\Tweet;
use RuntimeException;

class TweetDoesNotBelongsToAuthor extends RuntimeException
{
}
