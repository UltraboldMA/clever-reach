<?php

namespace UltraboldMA\CleverReach\Livewire;

use Exception;
use Illuminate\Support\Arr;
use Livewire\Component;
use UltraboldMA\CleverReach\Actions\CleverReach\DeleteSubscriber;
use UltraboldMA\CleverReach\Actions\CleverReach\GetSubscribers;
use UltraboldMA\CleverReach\Models\CleverReachGroup;
use UltraboldMA\CleverReach\Models\CleverReachNewsletter;

class CleverReachSubscribers extends Component
{
    public $selectedNewsletter;

    public $selectedGroup;

    public $availableNewsletters;

    public $availableGroups;

    public $subscribers = [];

    public $currentSubscriber;

    public $page = 0;

    public $pageSize = 25;

    public $showConfirm = false;

    protected $queryString = [
        'selectedNewsletter' => ['except' => ''],
        'selectedGroup' => ['except' => ''],
        'page' => ['except' => 0],
    ];

    public function mount()
    {
        $this->availableNewsletters = CleverReachNewsletter::with('clever_reach_client', 'clever_reach_group')->get();
        $this->availableGroups = CleverReachGroup::with('clever_reach_client')->get();
        if ($this->selectedGroup || $this->selectedNewsletter) {
            $this->loadSubscribers();
        }
    }

    public function render()
    {
        return view('clever-reach::livewire.clever-reach-subscribers');
    }

    public function loadSubscribers()
    {
        [$client, $groupId] = $this->selectedClientAndGroup();

        if (! $client || ! $groupId) {
            $this->reset('subscribers');

            return;
        }

        $subscriberRequest = new GetSubscribers;
        $this->subscribers = $subscriberRequest->handle($client, $groupId, [
            'page' => $this->page,
            'pagesize' => $this->pageSize,
        ]);
    }

    public function updatedSelectedGroup()
    {
        $this->reset('selectedNewsletter', 'subscribers', 'page');
    }

    public function updatedSelectedNewsletter()
    {
        $this->reset('selectedGroup', 'subscribers', 'page');
    }

    public function nextPage()
    {
        $this->page++;
        $this->loadSubscribers();
    }

    public function previousPage()
    {
        $this->page = max(0, $this->page - 1);
        $this->loadSubscribers();
    }

    public function showSubscriber(int $subscriberIndex)
    {
        $this->currentSubscriber = $this->subscribers[$subscriberIndex];
    }

    public function closeSubscriber()
    {
        $this->reset('currentSubscriber');
    }

    public function deleteConfirmSubscriber(int $subscriberIndex)
    {
        $this->currentSubscriber = $this->subscribers[$subscriberIndex];
        $this->showConfirm = true;
    }

    public function deleteSubscriber()
    {
        [$client] = $this->selectedClientAndGroup();

        if (! $client) {
            $this->reset('showConfirm', 'currentSubscriber');

            return;
        }

        try {
            $deleteRequest = new DeleteSubscriber;
            $deleteRequest->handle($client, Arr::get($this->currentSubscriber, 'id'));

            $this->loadSubscribers();
            $this->reset('showConfirm', 'currentSubscriber');

            session()->flash('message', __('Subscriber deleted successfully'));
        } catch (Exception $ex) {
            session()->flash('error', $ex->getMessage());
            $this->reset('showConfirm', 'currentSubscriber');
        }
    }

    public function cancel()
    {
        $this->reset('showConfirm', 'currentSubscriber');
    }

    private function selectedClientAndGroup(): array
    {
        if ($this->selectedNewsletter) {
            $newsletter = $this->availableNewsletters->where('key', $this->selectedNewsletter)->first();

            return [
                $newsletter?->clever_reach_client,
                $newsletter?->clever_reach_group?->external_id,
            ];
        }

        if ($this->selectedGroup) {
            $group = $this->availableGroups->where('external_id', $this->selectedGroup)->first();

            return [
                $group?->clever_reach_client,
                $this->selectedGroup,
            ];
        }

        return [null, null];
    }
}
