<?php

namespace UltraboldMA\CleverReach\Tests\Feature;

use Illuminate\Support\Facades\Route;
use UltraboldMA\CleverReach\Tests\TestCase;

class PackageRegistrationTest extends TestCase
{
    public function test_it_registers_package_config_routes_and_views(): void
    {
        $this->assertSame('https://rest.cleverreach.com/v3', config('clever-reach.baseUrl'));
        $this->assertTrue(Route::has('clever-reach'));
        $this->assertTrue(view()->exists('clever-reach::livewire.clever-reach'));
    }
}
