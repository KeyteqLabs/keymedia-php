<?php

use \Keyteq\KeymediaAPI as API;

class KeymediaAPITest extends \PHPUnit_Framework_TestCase
{
    public function testConstructSetsConfig()
    {
        $config = array(1, 2);
        $api = new API($config);
        $this->assertEquals($config, $api->config);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Missing argument 1
     */
    public function testConstructRequiresArgument()
    {
        $api = new API();
    }
}
