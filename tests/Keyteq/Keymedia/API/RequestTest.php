<?php

namespace Keyteq\Keymedia\API;

use \Mockery as m;
use Keyteq\Keymedia\API\Request;
use Keyteq\Keymedia\BaseTest;
use Keyteq\Keymedia\Util\RequestSigner;
use Keyteq\Keymedia\Util\RequestWrapper;

class RequestTest extends BaseTest
{

    public function testPerformSignsRequestsByDefault()
    {
        $queryKey = 'key';
        $queryValue = 'value';
        $payload = $queryKey . $queryValue;

        $signature = 'test_signature';
        $signer = $this->getSignerMock($payload, $signature);

        $requestWrapper = m::mock('\Keyteq\Keymedia\Util\RequestWrapper')
            ->shouldReceive('get')
            ->once()
            ->withAnyArgs()
            ->getMock();

        $request = new Request($this->apiConfig, $signer, $requestWrapper);
        $request->setUrl('http://m.keymedia.dev');
        $request->addParameter($queryKey, $queryValue);
        $request->perform();
    }

    public function testPerformSkipsSigningWhenInstructed()
    {
        $signer = m::mock('\Keyteq\Keymedia\Util\RequestSigner')
            ->shouldReceive('getSignature')
            ->never()
            ->getMock();

        $requestWrapper = m::mock('\Keyteq\Keymedia\Util\RequestWrapper')
            ->shouldReceive('get')
            ->once()
            ->withAnyArgs()
            ->getMock();

        $request = new Request($this->apiConfig, $signer, $requestWrapper, true);
        $request->setUrl('http://some.url')->perform();
    }

    public function testPerformSendsRequests()
    {
        $queryKey = 'name';
        $queryValue = 'value';
        $path = 'test_path';
        $url = "{$this->apiConfig->getApiUrl()}/{$path}?{$queryKey}={$queryValue}";
        $payload = $queryKey . $queryValue;
        $signature = 'test_signature';
        $signer = $this->getSignerMock($payload, $signature);

        $headers = array(
            'X-Keymedia-Username' => $this->apiConfig->getApiUser(),
            'X-Keymedia-Signature' => $signature
        );

        $requestWrapper = $this->getRequestWrapperMock('get', 1, array($url, $headers, array()));

        $request = new Request($this->getApiConfig(), $signer, $requestWrapper);
        $request->setUrl($url)->addParameter($queryKey, $queryValue)->perform();
    }

    public function testPerformSendsPostFields()
    {
        $queryKey = 'name';
        $queryValue = 'value';
        $params = array($queryKey => $queryValue);
        $path = 'test_path';
        $url = "{$this->apiConfig->getApiUrl()}/{$path}";
        $payload = $queryKey . $queryValue;
        $signature = 'test_signature';
        $signer = $this->getSignerMock($payload, $signature);

        $headers = array(
            'X-Keymedia-Username' => $this->apiConfig->getApiUser(),
            'X-Keymedia-Signature' => $signature
        );

        $requestWrapper = $this->getRequestWrapperMock('post', 1, array($url, $headers, $params, array()));

        $request = new Request($this->getApiConfig(), $signer, $requestWrapper);
        $request->setMethod('POST')->setUrl($url)->addParameter($queryKey, $queryValue)->perform();
    }

    public function testPerformReturnsTheResponse()
    {
        $apiConfig = $this->getApiConfig();
        $expected = new \stdClass();
        $signer = $this->getSignerMock();

        $wrapper = $this->getRequestWrapperMock('get', 1, false, $expected);
        $request = new Request($apiConfig, $signer, $wrapper);

        $actual = $request->setUrl('http://localhost')->perform();

        $this->assertSame($expected, $actual);
    }

    public function testPerformSkipsFileParameterWhenSigning()
    {
        $key = 'file';
        $value = 'file_content';
        $payload = '';
        $signer = $this->getSignerMock($payload);
        $wrapper = $this->getRequestWrapperMock();
        $request = new Request($this->getApiConfig(), $signer, $wrapper);

        $request->setUrl('http://test_host')->addParameter($key, $value)->perform();
    }

    public function testSetUrlStripsQueryString()
    {
        $expected = 'http://host/path';
        $url = $expected . '?q=search';
        $request = new Request($this->getApiConfig(), new RequestSigner(), new RequestWrapper());
        $request->setUrl($url);
        $request = (array) $request;

        $this->assertContains($expected, $request);
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage HTTP method INVALID is not supported
     */
    public function testSetMethodThrowsOnInvalid()
    {
        $request = new Request($this->apiConfig, new RequestSigner(), new RequestWrapper());
        $request->setMethod('INVALID');
    }

    public function testSetMethodAllowsGet()
    {
        $expected = new Request($this->apiConfig, new RequestSigner(), new RequestWrapper());
        $actual = $expected->setMethod('GET');

        $this->assertSame($expected, $actual);
    }

    public function testSetMethodAllowsPost()
    {
        $expected = new Request($this->apiConfig, new RequestSigner(), new RequestWrapper());
        $actual = $expected->setMethod('POST');

        $this->assertSame($expected, $actual);
    }

    public function testSetUrlParsesQueryString()
    {
        $expected = array('q' => 'search');
        $url = 'http://some.host/path?' . http_build_query($expected);
        $request = new Request($this->getApiConfig(), new RequestSigner(), new RequestWrapper());
        $request->setUrl($url);
        $actual = $request->getParameters();

        $this->assertEquals($expected, $actual);
    }

    protected function getRequestWrapperMock($method = 'get', $times = 1, $arguments = array(), $returning = null)
    {
        $mockBuilder = m::mock('\Keyteq\Keymedia\Util\RequestWrapper')
            ->shouldReceive($method)
            ->times($times);

        if ($arguments) {
            $mockBuilder->withArgs($arguments);
        }

        $mockBuilder->andReturn($returning);

        return $mockBuilder->getMock();
    }

    protected function getSignerMock($payload = '', $signature = '')
    {
        $signer = m::mock('\Keyteq\Keymedia\Util\RequestSigner')
            ->shouldReceive('getSignature')
            ->once()
            ->with($payload, $this->apiConfig->getApiKey())
            ->andReturn($signature)
            ->getMock();

        return $signer;
    }
}
