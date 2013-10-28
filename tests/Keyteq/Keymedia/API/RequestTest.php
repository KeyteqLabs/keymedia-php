<?php

namespace Keyteq\Keymedia\API;

use \Mockery as m;
use Keyteq\Keymedia\API\Request;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    protected $apiConfig;

    public function setUp()
    {
        parent::setUp();
        $options = array(
            'apiUrl' => 'http://m.keymedia.dev',
            'apiUser' => 'keymedia-user',
            'apiKey' => 'keymedia-key'
        );
        $this->apiConfig = new Configuration($options);
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }

    public function testPerformSignsRequests()
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
        $request->addQueryParameter($queryKey, $queryValue);
        $request->perform();
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
        $request->setUrl($url)->addQueryParameter($queryKey, $queryValue)->perform();
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

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage HTTP method
     */
    public function testPerformThrowsForInvalidMethod()
    {
        $signer = $this->getSignerMock();
        $wrapper = m::mock('\Keyteq\Keymedia\Util\RequestWrapper');
        $request = new Request($this->getApiConfig(), $signer, $wrapper);
        $request->setMethod('INVALID')->perform();
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

    protected function getApiConfig()
    {
        return $this->apiConfig;
    }
}
