<?php

namespace UltraboldMA\CleverReach;

use Illuminate\Support\ServiceProvider;
use UltraboldMA\CleverReach\Livewire\CleverReach;
use UltraboldMA\CleverReach\Livewire\CleverReachForms;
use UltraboldMA\CleverReach\Livewire\CleverReachGroups;
use UltraboldMA\CleverReach\Livewire\CleverReachNewsletters;
use UltraboldMA\CleverReach\Livewire\CleverReachSubscribers;

class CleverReachServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'clever-reach');
        $this->loadRoutesFrom(__DIR__.'/../routes/clever-reach.php');

        $this->callAfterResolving('livewire', function ($livewire): void {
            $livewire->component('clever-reach.clients', CleverReach::class);
            $livewire->component('clever-reach.groups', CleverReachGroups::class);
            $livewire->component('clever-reach.forms', CleverReachForms::class);
            $livewire->component('clever-reach.newsletters', CleverReachNewsletters::class);
            $livewire->component('clever-reach.subscribers', CleverReachSubscribers::class);
        });

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/clever-reach.php' => config_path('clever-reach.php'),
            ], ['clever-reach', 'clever-reach-config']);

            $this->publishesMigrations([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'clever-reach-migrations');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/clever-reach'),
            ], ['clever-reach', 'clever-reach-views']);
        }
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/clever-reach.php', 'clever-reach');

        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\PublishCommand::class,
            ]);
        }
    }
}
