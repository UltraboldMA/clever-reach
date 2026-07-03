<?php

namespace UltraboldMA\CleverReach\Tests\Feature;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use UltraboldMA\CleverReach\Actions\CleverReach\GetGroups;
use UltraboldMA\CleverReach\Actions\CleverReach\RegisterSubscriber;
use UltraboldMA\CleverReach\Actions\CleverReach\RequestToken;
use UltraboldMA\CleverReach\Models\CleverReachClient;
use UltraboldMA\CleverReach\Models\CleverReachForm;
use UltraboldMA\CleverReach\Models\CleverReachGroup;
use UltraboldMA\CleverReach\Models\CleverReachNewsletter;
use UltraboldMA\CleverReach\Tests\TestCase;

class CleverReachActionsTest extends TestCase
{
    public function test_request_token_stores_token_and_expiration(): void
    {
        Http::fake([
            'https://rest.cleverreach.com/oauth/token.php' => Http::response([
                'access_token' => 'new-token',
                'expires_in' => 3600,
            ]),
        ]);

        $client = CleverReachClient::create([
            'name' => 'Main',
            'client_id' => 'client-id',
            'client_secret' => 'client-secret',
        ]);

        app(RequestToken::class)->handle($client);

        $client->refresh();

        $this->assertSame('new-token', $client->token);
        $this->assertNotNull($client->token_expiration);
    }

    public function test_group_sync_is_scoped_per_client(): void
    {
        $client = $this->client(['client_id' => 'client-a']);
        $otherClient = $this->client(['client_id' => 'client-b']);

        CleverReachGroup::create([
            'clever_reach_client_id' => $otherClient->id,
            'external_id' => 123,
            'name' => 'Existing group',
            'locked' => false,
            'backup' => false,
            'receiver_info' => '-',
            'stamp' => null,
        ]);

        Http::fake([
            'https://rest.cleverreach.com/v3/groups.json' => Http::response([
                [
                    'id' => 123,
                    'name' => 'Synced group',
                    'locked' => false,
                    'backup' => false,
                    'receiver_info' => '-',
                    'stamp' => 0,
                    'last_mailing' => 0,
                    'last_changed' => 0,
                ],
            ]),
        ]);

        app(GetGroups::class)->handle($client);

        $this->assertDatabaseHas('clever_reach_groups', [
            'clever_reach_client_id' => $client->id,
            'external_id' => 123,
            'name' => 'Synced group',
        ]);
        $this->assertDatabaseHas('clever_reach_groups', [
            'clever_reach_client_id' => $otherClient->id,
            'external_id' => 123,
            'name' => 'Existing group',
        ]);
    }

    public function test_register_subscriber_sends_receiver_and_double_opt_in_requests(): void
    {
        $client = $this->client();
        $group = CleverReachGroup::create([
            'clever_reach_client_id' => $client->id,
            'external_id' => 555,
            'name' => 'Newsletter group',
            'locked' => false,
            'backup' => false,
            'receiver_info' => '-',
            'stamp' => null,
        ]);
        $form = CleverReachForm::create([
            'clever_reach_client_id' => $client->id,
            'external_id' => 777,
            'name' => 'Opt-in form',
        ]);
        CleverReachNewsletter::create([
            'clever_reach_client_id' => $client->id,
            'name' => 'Newsletter',
            'key' => 'main-newsletter',
            'form_id' => $form->id,
            'group_id' => $group->id,
            'language' => 'de',
            'double_opt_in' => true,
            'cl_attributes' => ['firstname'],
            'cl_global_attributes' => ['lastname'],
        ]);

        Http::fake([
            'https://rest.cleverreach.com/v3/groups.json/555/receivers' => Http::response(['id' => 999]),
            'https://rest.cleverreach.com/v3/forms.json/777/send/activate' => Http::response(['success' => true]),
        ]);

        app(RegisterSubscriber::class)->handle('main-newsletter', [
            'email' => 'alexej@example.com',
            'firstname' => 'Alexej',
            'lastname' => 'Krzewitzki',
        ]);

        Http::assertSent(fn (Request $request): bool => $request->url() === 'https://rest.cleverreach.com/v3/groups.json/555/receivers'
            && $request->data()['email'] === 'alexej@example.com'
            && $request->data()['attributes'] === [['firstname' => 'Alexej']]
            && $request->data()['global_attributes'] === [['lastname' => 'Krzewitzki']]);

        Http::assertSent(fn (Request $request): bool => $request->url() === 'https://rest.cleverreach.com/v3/forms.json/777/send/activate'
            && $request->data()['email'] === 'alexej@example.com');
    }

    private function client(array $attributes = []): CleverReachClient
    {
        return CleverReachClient::create(array_merge([
            'name' => 'Main',
            'client_id' => 'client-id',
            'client_secret' => 'client-secret',
            'token' => 'token',
            'token_expiration' => now()->addHour(),
        ], $attributes));
    }
}
