<?php

namespace Keyteq\Keymedia\API;

use Keyteq\Keymedia\BaseTest;
use Keyteq\Keymedia\API\Configuration;
use \Mockery as m;

class RestConnectorTest extends BaseTest
{

    const API_URL = 'http://test.host';
    const API_USER = 'test_user';
    const API_KEY = 'test_key';

    protected $config;

    public function setUp()
    {
        parent::setUp();
        $this->config = $this->getApiConfig();
    }

    public function testGetResource()
    {
        $expected = new \stdClass();
        $request = $this->getRequestMock($expected);
        $resourceName = 'resource_name';
        $resourceId = 'resource_id';
        $url = static::API_URL . "/{$resourceName}/{$resourceId}.json";
        $requestBuilder = m::mock('\Keyteq\Keymedia\Util\RequestBuilder')
            ->shouldReceive('buildRequest')
            ->once()
            ->with($url, 'GET', array())
            ->andReturn($request)
            ->getMock();

        $rest = new RestConnector($this->config, $requestBuilder);
        $actual = $rest->getResource($resourceName, $resourceId);

        $this->assertSame($expected, $actual);
    }

    public function testGetCollection()
    {
        $expected = new \stdClass();
        $request = $this->getRequestMock($expected);
        $resourceName = 'collection_name';
        $url = static::API_URL . "/{$resourceName}.json";
        $parameters = array('k1' => 'v1', 'k2' => 'v2');
        $requestBuilder = m::mock('\Keyteq\Keymedia\Util\RequestBuilder')
            ->shouldReceive('buildRequest')
            ->once()
            ->with($url, 'GET', $parameters)
            ->andReturn($request)
            ->getMock();

        $rest = new RestConnector($this->config, $requestBuilder);
        $actual = $rest->getCollection($resourceName, $parameters);

        $this->assertSame($expected, $actual);
    }

    protected function getApiConfig()
    {
        return new Configuration(
            array(
                'apiUrl' => static::API_URL,
                'apiUser' => static::API_USER,
                'apiKey' => static::API_KEY
            )
        );
    }

    protected function getRequestMock($returnValue)
    {
        $request = m::mock('\Keyteq\Keymedia\API\Request')
            ->shouldReceive('perform')
            ->once()
            ->andReturn($returnValue)
            ->getMock();

        return $request;
    }
}
