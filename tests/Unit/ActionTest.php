<?php

namespace Addeeandra\Droplets\Tests\Unit;

use Addeeandra\Droplets\DropletAction;
use Addeeandra\Droplets\Tests\BaseTestCase;
use DigitalOceanV2\Entity\Action;
use DigitalOceanV2\Exception\ExceptionInterface;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\Exception;

class ActionTest extends BaseTestCase
{
    /**
     * @throws Exception|\DigitalOceanV2\Exception\ExceptionInterface
     */
    public function test_stub_empty_droplets_all_and_find()
    {
        $stub = $this->createStub(DropletAction::class);
        $stub->method('all')->willReturn(Collection::empty());
        $stub->method('find')->willReturn(null);

        $emptyDroplets = $stub->all();
        $this->assertInstanceOf(Collection::class, $emptyDroplets);
        $this->assertTrue($emptyDroplets->isEmpty());

        // find by tag should be null
        $nullDroplet = $stub->find('some-tag');
        $this->assertNull($nullDroplet);
    }

    /**
     * @throws ExceptionInterface
     * @throws \Throwable
     * @throws Exception
     */
    public function test_stub_droplet_scale()
    {
        $stub = $this->createStub(DropletAction::class);
        $stub->method('scaleIdle')->willReturn(new Action());
        $stub->method('scaleUsage')->willReturn(new Action());
        $stub->method('scalePeak')->willReturn(new Action());

        $this->assertInstanceOf(Action::class, $stub->scaleIdle(1));
        $this->assertInstanceOf(Action::class, $stub->scaleUsage(1));
        $this->assertInstanceOf(Action::class, $stub->scalePeak(1));
    }
}