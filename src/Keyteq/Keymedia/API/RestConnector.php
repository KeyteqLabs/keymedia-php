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
        $url = $this->buildUrl($path, $parameters);
        $request = $this->requestBuilder->buildRequest($url, 'GET', $parameters, $this->skipRequestSigning());

        return $request->perform();
    }

    public function getCollection($resourceName, array $parameters = array())
    {
        $path = "{$resourceName}.json";
        $url = $this->buildUrl($path);
        $request = $this->requestBuilder->buildRequest($url, 'GET', $parameters, $this->skipRequestSigning());

        return $request->perform();
    }

    public function postResource($resourceName, array $parameters)
    {
        $path = "{$resourceName}.json";
        $url = $this->buildUrl($path);
        $request = $this->requestBuilder->buildRequest($url, 'POST', $parameters, $this->skipRequestSigning());

        return $request->perform();
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
