<?php

namespace Keyteq\Keymedia\API;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
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
