<?php

namespace UltraboldMA\CleverReach\Traits;

use UltraboldMA\CleverReach\Actions\CleverReach\RequestToken;
use UltraboldMA\CleverReach\Models\CleverReachClient;

trait CleverReachToken
{
    public function tokenValid(CleverReachClient $cleverReachClient): void
    {
        $expiresAt = $cleverReachClient->token_expiration
            ? now()->parse($cleverReachClient->token_expiration)
            : null;

        if (! $cleverReachClient->token || ! $expiresAt || $expiresAt->isPast()) {
            $this->requestToken($cleverReachClient);
        }
    }

    public function requestToken(CleverReachClient $cleverReachClient): void
    {
        $tokenRequest = new RequestToken;
        $tokenRequest->handle($cleverReachClient);
    }
}
