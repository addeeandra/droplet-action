<?php

namespace Addeeandra\Droplets\Tests\Unit;

use Addeeandra\Droplets\DropletAction;
use Addeeandra\Droplets\Tests\BaseTestCase;

class ProviderTest extends BaseTestCase
{
    public function test_ioc()
    {
        $this->assertInstanceOf(DropletAction::class, app('droplet.action'));
        $this->assertInstanceOf(DropletAction::class, app(DropletAction::class));
    }
}