<?php

namespace UltraboldMA\CleverReach\Livewire;

use Livewire\Component;
use UltraboldMA\CleverReach\Actions\CleverReach\GetForms;
use UltraboldMA\CleverReach\Models\CleverReachClient;
use UltraboldMA\CleverReach\Models\CleverReachForm;

class CleverReachForms extends Component
{
    public $forms = [];

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
            $this->loadForms();
        }
    }

    public function render()
    {
        return view('clever-reach::livewire.clever-reach-forms');
    }

    public function refreshForms()
    {
        if (! is_numeric($this->selectedClient)) {
            $this->reset('forms');

            return;
        }

        $client = CleverReachClient::findOrFail($this->selectedClient);
        $this->forms = app(GetForms::class)->handle($client);
    }

    public function loadForms()
    {
        $this->forms = CleverReachForm::where('clever_reach_client_id', $this->selectedClient)->get();
    }
}
