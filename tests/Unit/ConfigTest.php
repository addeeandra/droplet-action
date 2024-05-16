<?php

namespace Addeeandra\Droplets\Tests\Unit;

use Addeeandra\Droplets\Tests\BaseTestCase;

class ConfigTest extends BaseTestCase
{

    public function test_token_config_loaded_correctly()
    {
        $this->assertIsArray(config('droplet-action'));
    }

}