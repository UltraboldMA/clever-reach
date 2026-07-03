<?php

namespace UltraboldMA\CleverReach\Actions\CleverReach;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use UltraboldMA\CleverReach\Models\CleverReachClient;

class RequestToken
{
    public function handle(CleverReachClient $cleverReachClient): void
    {
        $response = Http::acceptJson()
            ->withBasicAuth($cleverReachClient->client_id, $cleverReachClient->client_secret)
            ->post('https://rest.cleverreach.com/oauth/token.php', [
                'grant_type' => 'client_credentials',
            ])
            ->throw()
            ->json();

        if (! Arr::get($response, 'access_token')) {
            throw new RuntimeException('CleverReach token response did not contain an access token.');
        }

        $cleverReachClient->token = Arr::get($response, 'access_token');
        $cleverReachClient->token_expiration = now()->addSeconds((int) Arr::get($response, 'expires_in', 0));
        $cleverReachClient->save();
    }
}
