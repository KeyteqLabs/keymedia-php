<?php

namespace Keyteq\Keymedia\API;

use \Mockery as m;
use \Keyteq\Keymedia\Util\Parameter\HttpHeader;
use \Keyteq\Keymedia\Util\Parameter\Container\ParameterContainer;

class RequestTest extends \PHPUnit_Framework_TestCase
{

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }

    public function testPerform()
    {
        $key = 'testkey';
        $user = 'testuser';
        $host = 'example.com';
        $path = 'testPath';
        $url = "http://{$host}/{$path}";

        $signature = 'testsignature';
        $headers = array(
            'X-Keymedia-Username' => $user,
            'X-Keymedia-Signature' => $signature
        );

        $apiConfig = array(
            'apiUser' => $user,
            'apiHost' => $host,
            'apiKey' => $key
        );

        $curl = $this->getCurlMock($url, $headers);
        $signer = $this->getSignerMock($key, $signature);

        $request = new Request($apiConfig, $curl, $signer);
        $request->setPath($path);
        $request->perform();
    }

    protected function getSignerMock($key, $signature)
    {
        $signer = m::mock('\Keyteq\Keymedia\Util\RequestSigner')
            ->shouldReceive('getSignature')
            ->once()
            ->with('', $key)
            ->andReturn($signature)

            ->getMock();

        return $signer;
    }

    protected function getCurlMock($url, $headers)
    {
        $curl = m::mock('\Keyteq\Keymedia\Util\CurlWrapper')
            ->shouldReceive('setMethod')
            ->once()
            ->with('GET')

            ->shouldReceive('setUrl')
            ->once()
            ->with($url)

            ->shouldReceive('setRequestHeaders')
            ->once()
            ->with($headers)

            ->shouldReceive('perform')
            ->once()

            ->getMock();

        return $curl;
    }
}