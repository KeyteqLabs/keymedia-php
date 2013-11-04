<?php

namespace Keyteq\Keymedia\API;

use Keyteq\Keymedia\BaseTest;
use \Mockery as m;

class RestConnectorTest extends BaseTest
{

    public function testGetResource()
    {
        $expected = new \stdClass();
        $request = $this->getRequestMock($expected);
        $resourceName = 'resource_name';
        $resourceId = 'resource_id';
        $url = static::API_URL . "/{$resourceName}/{$resourceId}.json";
        $requestBuilder = $this->getRequestBuilderMock($url, 'GET', array(), $request);
        $rest = new RestConnector($this->apiConfig, $requestBuilder);
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
        $requestBuilder = $this->getRequestBuilderMock($url, 'GET', $parameters, $request);
        $rest = new RestConnector($this->apiConfig, $requestBuilder);
        $actual = $rest->getCollection($resourceName, $parameters);

        $this->assertSame($expected, $actual);
    }

    public function testPostResource()
    {
        $expected = new \stdClass();
        $request = $this->getRequestMock($expected);
        $resourceName = 'resource_name';
        $url = static::API_URL . "/{$resourceName}.json";
        $parameters = array();
        $requestBuilder = $this->getRequestBuilderMock($url, 'POST', $parameters, $request);
        $rest = new RestConnector($this->apiConfig, $requestBuilder);
        $actual = $rest->postResource($resourceName, $parameters);

        $this->assertSame($expected, $actual);
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

    protected function getRequestBuilderMock($url, $method, array $parameters, $request)
    {
        $mock = m::mock('\Keyteq\Keymedia\Util\RequestBuilder')
            ->shouldReceive('buildRequest')
            ->once()
            ->with($url, $method, $parameters)
            ->andReturn($request)
            ->getMock();

        return $mock;
    }
}
