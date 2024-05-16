<?php

namespace Addeeandra\Droplets\Tests\Integration;

use Addeeandra\Droplets\DropletAction;
use Addeeandra\Droplets\Tests\BaseTestCase;
use DigitalOceanV2\Entity\Droplet;
use Illuminate\Support\Collection;

class PassiveIntegrationTest extends BaseTestCase
{
    private int $dropletIdUnderTest = 0;
    private string $dropletTagUnderTest = '';

    public function test_action_all_droplets()
    {
        $droplets = $this->manager()->all();

        file_put_contents('__droplets.test.json', json_encode($droplets));

        $this->assertInstanceOf(Collection::class, $droplets);
    }

    public function test_action_one_droplet_by_id()
    {
        $droplet = $this->manager()->find($this->dropletIdUnderTest);

        file_put_contents('__droplet-id.test.json', json_encode($droplet));

        $this->assertInstanceOf(Droplet::class, $droplet);
    }

    public function test_action_one_droplet_by_tag()
    {
        $droplet = $this->manager()->find($this->dropletTagUnderTest);

        file_put_contents('__droplet-tag.test.json', json_encode($droplet));

        $this->assertInstanceOf(Droplet::class, $droplet);
    }

    public function test_action_available_sizes()
    {
        $sizes = $this->manager()->getAllSizes();

        file_put_contents('__sizes.test.json', json_encode($sizes));

        $this->assertInstanceOf(Collection::class, $sizes);
    }

    private function manager(): DropletAction
    {
        return app('droplet.action');
    }
}