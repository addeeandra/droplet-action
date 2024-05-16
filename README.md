# Laravel Droplets

Laravel Droplets is a laser-focus metapackage that
utilize [Laravel DigitalOcean](https://github.com/GrahamCampbell/Laravel-DigitalOcean) to configure droplets and take
action on them (e.g. scale) in limited configuration.

## Installation

This version require [PHP](https://www.php.net) 8.1-8.3 and supports [Laravel](https://laravel.com) 8-11.

| Version | L7  | L8                 | L9                 | L10                | L11                |
|---------|-----|--------------------|--------------------|--------------------|--------------------|
| dev     | :x: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: |

## Configuration

The purpose of this package is to scale droplets on limited stage: `on_idle`, `on_usage`, `on_peak`. Each stages are
defined per limited droplets.

This package configuration (droplet-action) contains.

```php
return [
    'scale' => [
        'droplet_1' => [
            'id' => null,
            'on_idle' => 's-1vcpu-2gb',
            'on_usage' => 'gd-2vcpu-8gb',
            'on_peak' => 'c-4',
        ],
        'droplet_2' => [
            'id' => null,
            'on_idle' => 's-1vcpu-2gb',
            'on_usage' => 'gd-2vcpu-8gb',
            'on_peak' => 'c-4',
        ],
        // other staged strategy
    ]
];
```

Insert ID of your droplet in the `id` attribute, and the `DropletAction` will take care of it later on.

## How to use

**DropletAction**

Droplet Action currently have capability to handle three-stage scaling, `on_idle`, `on_usage` and `on_peak`. This class is the most useful when called by your action button or scheduler.

```php
use Addeeandra\Droplets\DropletAction;

# get the DropletAction via binding
$action = app('droplet.action')
$action = app(DropletAction::class)

# scale
/** @var $action DropletAction */
$action->scaleIdle('droplet_1');
$action->scalePeak('droplet_1');
$action->scaleUsage('droplet_1');
```