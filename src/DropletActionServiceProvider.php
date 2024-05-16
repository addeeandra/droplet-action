<?php

namespace Addeeandra\Droplets;

use GrahamCampbell\DigitalOcean\DigitalOceanServiceProvider;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;

class DropletActionServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config' => $this->app->configPath(),
            'droplet-action-config'
        ]);
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/droplet-action.php',
            'droplet-action'
        );

        /**
         * Auto register DigitalOceanServiceProvider when register the provider.
         */
        $this->app->register(DigitalOceanServiceProvider::class);

        $this->app->bind('droplet.action', function (Container $app): DropletAction {
            $config = $app['config'];
            $factory = $app['digitalocean.factory'];

            return new DropletAction($config, $factory);
        });
        $this->app->alias('droplet.action', DropletAction::class);
    }

}