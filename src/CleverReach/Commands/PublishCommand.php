<?php

namespace UltraboldMA\CleverReach\Commands;

use Illuminate\Console\Command;

class PublishCommand extends Command
{
    protected $signature = 'clever-reach:publish {--force : Overwrite all previously published assets}';

    protected $description = 'Publish CleverReach configuration, migrations and views';

    public function handle(): int
    {
        $this->call('vendor:publish', ['--tag' => 'clever-reach-config', '--force' => $this->option('force')]);
        $this->call('vendor:publish', ['--tag' => 'clever-reach-migrations', '--force' => $this->option('force')]);
        $this->call('vendor:publish', ['--tag' => 'clever-reach-views', '--force' => $this->option('force')]);

        return self::SUCCESS;
    }
}
