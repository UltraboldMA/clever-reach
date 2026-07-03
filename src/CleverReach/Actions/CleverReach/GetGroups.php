<?php

namespace UltraboldMA\CleverReach\Actions\CleverReach;

use Illuminate\Support\Facades\Http;
use UltraboldMA\CleverReach\Models\CleverReachClient;
use UltraboldMA\CleverReach\Models\CleverReachGroup;
use UltraboldMA\CleverReach\Traits\CleverReachToken;

class GetGroups
{
    use CleverReachToken;

    public function handle(CleverReachClient $cleverReachClient): array
    {
        $this->tokenValid($cleverReachClient);

        $jsonGroups = Http::acceptJson()
            ->withToken($cleverReachClient->token)
            ->get(config('clever-reach.baseUrl').'/groups.json')
            ->throw()
            ->json();

        $groups = [];
        foreach ($jsonGroups ?? [] as $group) {
            $group['external_id'] = $group['id'];
            $group['clever_reach_client_id'] = $cleverReachClient->id;
            unset($group['id']);
            $groups[] = CleverReachGroup::updateOrCreate([
                'clever_reach_client_id' => $cleverReachClient->id,
                'external_id' => $group['external_id'],
            ], $group);
        }

        return $groups;
    }
}
