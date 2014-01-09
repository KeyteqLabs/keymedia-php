<?php

namespace Keyteq\Keymedia\API;

use Keyteq\Keymedia\API\Configuration;
use Keyteq\Keymedia\Util\RequestBuilder;

class RestConnector
{
    protected $config;
    protected $requestBuilder;

    public function __construct(Configuration $config, RequestBuilder $requestBuilder)
    {
        $this->config = $config;
        $this->requestBuilder = $requestBuilder;
    }

    public function getResource($resourceName, $resourceId, array $parameters = array())
    {
        $path = "{$resourceName}/{$resourceId}.json";
        $request = $this->buildRequest($path, 'GET', $parameters);

        return $request->perform();
    }

    public function getCollection($resourceName, array $parameters = array())
    {
        $path = "{$resourceName}.json";
        $request = $this->buildRequest($path, 'GET', $parameters);

        return $request->perform();
    }

    public function postResource($resourceName, array $parameters)
    {
        $path = "{$resourceName}.json";
        $request = $this->buildRequest($path, 'POST', $parameters);

        return $request->perform();
    }

    protected function buildRequest($path, $method, $parameters)
    {
        $url = $this->buildUrl($path);
        return $this->requestBuilder->buildRequest($url, $method, $parameters, $this->skipRequestSigning());
    }

    protected function buildUrl($path = '')
    {
        $rootUrl = $this->config->getApiUrl();
        $url = "{$rootUrl}/{$path}";

        return $url;
    }

    protected function skipRequestSigning()
    {
        return !$this->config->getApiKey();
    }
}
