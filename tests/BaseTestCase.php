<?php

namespace Addeeandra\Droplets\Tests;

use Addeeandra\Droplets\DropletActionServiceProvider;

class BaseTestCase extends \Orchestra\Testbench\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function getEnvironmentSetUp($app): void
    {
        $app['config']->set('digitalocean.connections.main.token', env('DROPLET_TOKEN'));
        $app['config']->set('digitalocean.connections.main.method', 'token');
    }

    protected function getPackageProviders($app): array
    {
        return [DropletActionServiceProvider::class];
    }
}