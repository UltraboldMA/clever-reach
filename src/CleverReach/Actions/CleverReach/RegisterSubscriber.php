<?php

namespace UltraboldMA\CleverReach\Actions\CleverReach;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use UltraboldMA\CleverReach\Models\CleverReachNewsletter;
use UltraboldMA\CleverReach\Traits\CleverReachToken;

class RegisterSubscriber
{
    use CleverReachToken;

    public function handle(string $key, array $subscriber): void
    {
        $newsletter = CleverReachNewsletter::where('key', $key)->with('clever_reach_client', 'clever_reach_form', 'clever_reach_group')->firstOrFail();

        $this->tokenValid($newsletter->clever_reach_client);

        // Build request body
        $body = [
            'email' => Arr::get($subscriber, 'email'),
            'deactivated' => $newsletter->double_opt_in ? 1 : 0,
        ];

        // Build attributes
        if (! empty($newsletter->cl_attributes)) {
            $attributes = [];
            foreach ($newsletter->cl_attributes as $attribute) {
                $attributes[] = [
                    $attribute => Arr::get($subscriber, $attribute),
                ];
            }
            $body['attributes'] = $attributes;
        }

        // Build global attributes
        if (! empty($newsletter->cl_global_attributes)) {
            $globalAttributes = [];
            foreach ($newsletter->cl_global_attributes as $ga) {
                $globalAttributes[] = [
                    $ga => Arr::get($subscriber, $ga),
                ];
            }
            $body['global_attributes'] = $globalAttributes;
        }

        // Subscribe user
        $result = Http::acceptJson()
            ->withToken($newsletter->clever_reach_client->token)
            ->post(config('clever-reach.baseUrl').'/groups.json/'.$newsletter->clever_reach_group->external_id.'/receivers', $body);
        // Check if subscription went through
        if (! $result->successful()) {
            throw new Exception(Arr::get($result->json(), 'error.message', $result->body()));
        }
        // Send double opt-in email if necessary
        if ($newsletter->double_opt_in) {
            $confirmation = Http::acceptJson()
                ->withToken($newsletter->clever_reach_client->token)
                ->post(config('clever-reach.baseUrl').'/forms.json/'.$newsletter->clever_reach_form->external_id.'/send/activate', [
                    'email' => Arr::get($subscriber, 'email'),
                    'doidata' => [
                        'user_ip' => request()->ip(),
                        'referer' => config('app.url'),
                        'user_agent' => request()->header('user-agent'),
                    ],
                ]);
            if (! $confirmation->successful()) {
                throw new Exception(Arr::get($confirmation->json(), 'error.message', $confirmation->body()));
            }
        }
    }
}
