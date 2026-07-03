<?php

namespace UltraboldMA\CleverReach\Actions\CleverReach;

use Illuminate\Support\Facades\Http;
use UltraboldMA\CleverReach\Models\CleverReachClient;
use UltraboldMA\CleverReach\Traits\CleverReachToken;

class ShowSubscriber
{
    use CleverReachToken;

    public function handle(CleverReachClient $cleverReachClient, int $subscriberId): array
    {
        $this->tokenValid($cleverReachClient);

        $url = config('clever-reach.baseUrl').'/receivers.json/'.$subscriberId;

        return Http::acceptJson()
            ->withToken($cleverReachClient->token)
            ->get($url)
            ->throw()
            ->json();
    }
}
