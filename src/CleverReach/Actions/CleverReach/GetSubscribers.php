<?php

namespace UltraboldMA\CleverReach\Actions\CleverReach;

use Illuminate\Support\Facades\Http;
use UltraboldMA\CleverReach\Models\CleverReachClient;
use UltraboldMA\CleverReach\Models\CleverReachSubscriber;
use UltraboldMA\CleverReach\Traits\CleverReachToken;

class GetSubscribers
{
    use CleverReachToken;

    public function handle(CleverReachClient $cleverReachClient, int $groupId, array $params = []): array
    {
        $this->tokenValid($cleverReachClient);

        $url = config('clever-reach.baseUrl').'/groups.json/'.$groupId.'/receivers?'.http_build_query($params);

        $jsonSubscribers = Http::acceptJson()
            ->withToken($cleverReachClient->token)
            ->get($url)
            ->throw()
            ->json();

        $subscribers = [];

        foreach ($jsonSubscribers ?? [] as $subscriber) {
            $subscribers[] = new CleverReachSubscriber($subscriber);
        }

        return $subscribers;
    }
}
