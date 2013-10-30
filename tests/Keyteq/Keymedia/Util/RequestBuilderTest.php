<?php

namespace Keyteq\Keymedia\Util;

use Keyteq\Keymedia\BaseTest;

class RequestBuilderTest extends BaseTest
{
    /**
     * @dataProvider requestDataProvider
     */
    public function testBuildRequest($url, $method, $parameters)
    {
        $this->markTestIncomplete();
    }

    public function requestDataProvider()
    {
        return array(
            array(
                'http://host.name/path',
                'GET',
                array()
            )
        );
    }
}
