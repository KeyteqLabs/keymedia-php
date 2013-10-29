<?php

namespace Keyteq\Keymedia\API;

use Keyteq\Keymedia\BaseTest;

class ConfigurationTest extends BaseTest
{

    /**
     * @dataProvider constructProvider
     */
    public function testConstruct($options)
    {
        $config = new Configuration($options);

        $this->assertInstanceOf('\Keyteq\Keymedia\API\Configuration', $config);
    }

    public function constructProvider()
    {
        return array(
            array(array('apiUrl' => 'http://m.keymedia.dev', 'apiUser' => 'user', 'apiKey' => 'key'))
        );
    }
}
