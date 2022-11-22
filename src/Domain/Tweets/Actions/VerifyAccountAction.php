<?php

namespace Domain\Tweets\Actions;

use Carbon\Carbon;
use Domain\Shared\Models\User;

class VerifyAccountAction
{
    public function execute(User $user, ?Carbon $date = null): void
    {
        if ($user->isNotVerified()) {
            $user->forceFill(['verified_at' => $date ?? $user->freshTimestamp()])->save();
        }
    }
}
