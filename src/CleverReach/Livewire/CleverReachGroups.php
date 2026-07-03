<?php

namespace UltraboldMA\CleverReach\Livewire;

use Livewire\Component;
use UltraboldMA\CleverReach\Actions\CleverReach\GetGroups;
use UltraboldMA\CleverReach\Models\CleverReachClient;
use UltraboldMA\CleverReach\Models\CleverReachGroup;

class CleverReachGroups extends Component
{
    public $groups = [];

    public $selectedClient;

    public $clients;

    protected $queryString = [
        'selectedClient' => ['except' => ''],
    ];

    public function mount()
    {
        if (config('clever-reach.singleClient')) {
            $this->selectedClient = $this->selectedClient ?: CleverReachClient::first()?->id;
        } else {
            $this->clients = CleverReachClient::all();
        }

        if ($this->selectedClient) {
            $this->loadGroups();
        }
    }

    public function render()
    {
        return view('clever-reach::livewire.clever-reach-groups');
    }

    public function refreshGroups()
    {
        if (! is_numeric($this->selectedClient)) {
            $this->reset('groups');

            return;
        }

        $client = CleverReachClient::findOrFail($this->selectedClient);
        $this->groups = app(GetGroups::class)->handle($client);
    }

    public function loadGroups()
    {
        $this->groups = CleverReachGroup::where('clever_reach_client_id', $this->selectedClient)->get();
    }
}
