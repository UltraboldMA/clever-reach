<?php

use Illuminate\Support\Facades\Route;
use UltraboldMA\CleverReach\Livewire\CleverReach;
use UltraboldMA\CleverReach\Livewire\CleverReachForms;
use UltraboldMA\CleverReach\Livewire\CleverReachGroups;
use UltraboldMA\CleverReach\Livewire\CleverReachNewsletters;
use UltraboldMA\CleverReach\Livewire\CleverReachSubscribers;

Route::middleware(config('clever-reach.route_middleware', ['web']))
    ->prefix(config('clever-reach.route_prefix', ''))
    ->group(function (): void {
        Route::get('clever-reach', CleverReach::class)->name('clever-reach');
        Route::get('clever-reach-groups', CleverReachGroups::class)->name('clever-reach-groups');
        Route::get('clever-reach-forms', CleverReachForms::class)->name('clever-reach-forms');
        Route::get('clever-reach-newsletters', CleverReachNewsletters::class)->name('clever-reach-newsletters');
        Route::get('clever-reach-subscribers', CleverReachSubscribers::class)->name('clever-reach-subscribers');
    });
