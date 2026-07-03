<?php

namespace UltraboldMA\CleverReach\Actions\CleverReach;

use Illuminate\Support\Facades\Http;
use UltraboldMA\CleverReach\Models\CleverReachClient;
use UltraboldMA\CleverReach\Models\CleverReachForm;
use UltraboldMA\CleverReach\Traits\CleverReachToken;

class GetForms
{
    use CleverReachToken;

    public function handle(CleverReachClient $cleverReachClient): array
    {
        $this->tokenValid($cleverReachClient);

        $jsonForms = Http::acceptJson()
            ->withToken($cleverReachClient->token)
            ->get(config('clever-reach.baseUrl').'/forms.json')
            ->throw()
            ->json();

        $forms = [];
        foreach ($jsonForms ?? [] as $form) {
            $form['external_id'] = $form['id'];
            $form['clever_reach_client_id'] = $cleverReachClient->id;
            unset($form['id']);
            $forms[] = CleverReachForm::updateOrCreate([
                'clever_reach_client_id' => $cleverReachClient->id,
                'external_id' => $form['external_id'],
            ], $form);
        }

        return $forms;
    }
}
