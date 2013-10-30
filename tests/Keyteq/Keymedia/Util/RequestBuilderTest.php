<?php

namespace Keyteq\Keymedia\Util;

use Keyteq\Keymedia\BaseTest;
use Keyteq\Keymedia\Util\RequestBuilder;

class RequestBuilderTest extends BaseTest
{

    protected $builder;

    public function setUp()
    {
        parent::setUp();
        $this->builder = new RequestBuilder($this->apiConfig);
    }

    /**
     * @dataProvider requestDataProvider
     */
    public function testBuildRequestSetsProperUrl($url, $method, $parameters)
    {
        $request = $this->builder->buildRequest($url, $method, $parameters);
        $this->assertContains($url, (array) $request);
    }

    /**
     * @dataProvider requestDataProvider
     */
    public function testBuildRequestSetsProperMethod($url, $method, $parameters)
    {
        $request = $this->builder->buildRequest($url, $method, $parameters);
        $this->assertContains($method, (array) $request);
    }

    /**
     * @dataProvider requestDataProvider
     */
    public function testBuildRequestSetsParameters($url, $method, $parameters)
    {
        $request = $this->builder->buildRequest($url, $method, $parameters);
        $this->assertContains($parameters, (array) $request);
    }

    public function requestDataProvider()
    {
        return array(
            array(
                'http://host.name/path',
                'GET',
                array()
            ), array(
                'http://some.host/some-path',
                'POST',
                array(
                    'k1' => 'v1'
                )
            ), array(
                'http://some.other.host/some-other-path',
                'PUT',
                array(
                    'q' => 'search',
                    'tags' => 'album'
                )
            )
        );
    }
}
