<?php

namespace UltraboldMA\CleverReach\Livewire;

use Illuminate\Support\Str;
use Livewire\Component;
use UltraboldMA\CleverReach\Models\CleverReachClient;
use UltraboldMA\CleverReach\Models\CleverReachForm;
use UltraboldMA\CleverReach\Models\CleverReachGroup;
use UltraboldMA\CleverReach\Models\CleverReachNewsletter;

class CleverReachNewsletters extends Component
{
    public $editId;

    public $showForm = false;

    public CleverReachNewsletter $cleverReachNewsletter;

    public $clAttributes = [];

    public $globalAttributes = [];

    protected $queryString = [
        'editId' => ['except' => ''],
        'showForm' => ['except' => false],
    ];

    protected $rules = [
        'cleverReachNewsletter.name' => 'required|min:3',
        'cleverReachNewsletter.clever_reach_client_id' => 'required',
        'cleverReachNewsletter.key' => 'required',
        'cleverReachNewsletter.form_id' => 'required',
        'cleverReachNewsletter.group_id' => 'required',
        'cleverReachNewsletter.language' => 'required',
        'cleverReachNewsletter.double_opt_in' => 'required',
    ];

    public function mount()
    {
        if ($this->editId) {
            $this->showForm = true;
            $this->cleverReachNewsletter = CleverReachNewsletter::findOrFail($this->editId);
            $this->fillAttributes();

            return;
        }

        $this->cleverReachNewsletter = new CleverReachNewsletter;

        if ($this->showForm) {
            $this->fillDefaults();
        }
    }

    public function render()
    {
        return view('clever-reach::livewire.clever-reach-newsletters')->with([
            'newsletters' => CleverReachNewsletter::all(),
            'groups' => CleverReachGroup::with('clever_reach_client')->get(),
            'forms' => CleverReachForm::with('clever_reach_client')->get(),
            'clients' => CleverReachClient::all(),
        ]);
    }

    public function create()
    {
        $this->showForm = true;
        $this->cleverReachNewsletter = new CleverReachNewsletter;
        $this->fillDefaults();
    }

    public function edit(CleverReachNewsletter $cleverReachNewsletter)
    {
        $this->editId = $cleverReachNewsletter->id;
        $this->showForm = true;
        $this->cleverReachNewsletter = $cleverReachNewsletter;
        $this->fillAttributes();
    }

    public function save()
    {
        $this->validate();

        $this->cleverReachNewsletter->cl_attributes = $this->clAttributes;
        $this->cleverReachNewsletter->cl_global_attributes = $this->globalAttributes;

        $this->cleverReachNewsletter->save();

        $this->resetExcept('cleverReachNewsletter');
    }

    public function cancel()
    {
        $this->resetExcept('cleverReachNewsletter');
    }

    public function delete(CleverReachNewsletter $cleverReachNewsletter)
    {
        $cleverReachNewsletter->delete();
    }

    public function addGlobalAttribute()
    {
        $this->globalAttributes[] = '';
    }

    public function deleteGlobalAttribute($attribute_index)
    {
        unset($this->globalAttributes[$attribute_index]);
        $this->globalAttributes = array_values($this->globalAttributes);
    }

    public function addAttribute()
    {
        $this->clAttributes[] = '';
    }

    public function deleteAttribute($attribute_index)
    {
        unset($this->clAttributes[$attribute_index]);
        $this->clAttributes = array_values($this->clAttributes);
    }

    private function fillDefaults(): void
    {
        $this->cleverReachNewsletter->double_opt_in = true;
        $this->cleverReachNewsletter->key = (string) Str::uuid();
        $this->cleverReachNewsletter->clever_reach_client_id = CleverReachClient::first()?->id;
    }

    private function fillAttributes(): void
    {
        $this->clAttributes = $this->cleverReachNewsletter->cl_attributes ?? [];
        $this->globalAttributes = $this->cleverReachNewsletter->cl_global_attributes ?? [];
    }
}
