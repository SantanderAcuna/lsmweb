<?php

declare(strict_types=1);

namespace Tests;

use Database\Seeders\GeografiaSeeder;
use Database\Seeders\RolesPermissionsSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function seedAuthAndCatalog(): void
    {
        $this->seed([RolesPermissionsSeeder::class, GeografiaSeeder::class]);
    }
}
