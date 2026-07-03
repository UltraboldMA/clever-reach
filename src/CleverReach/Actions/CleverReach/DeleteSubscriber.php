<?php

namespace UltraboldMA\CleverReach\Actions\CleverReach;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use UltraboldMA\CleverReach\Models\CleverReachClient;
use UltraboldMA\CleverReach\Traits\CleverReachToken;

class DeleteSubscriber
{
    use CleverReachToken;

    public function handle(CleverReachClient $cleverReachClient, mixed $subscriberId): bool
    {
        $this->tokenValid($cleverReachClient);

        $url = config('clever-reach.baseUrl').'/receivers.json/'.$subscriberId;

        $response = Http::acceptJson()
            ->withToken($cleverReachClient->token)
            ->delete($url);
        if (! $response->successful()) {
            throw new Exception(Arr::get($response->json(), 'error.message', $response->body()));
        }

        return true;
    }
}
