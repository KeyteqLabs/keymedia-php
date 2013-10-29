<?php

namespace Keyteq\Keymedia\API;

use Keyteq\Keymedia\API\Configuration;
use Keyteq\Keymedia\Util\RequestSigner;
use Keyteq\Keymedia\Util\RequestWrapper;

class RestConnector
{
    protected $config;

    public function __construct(Configuration $config)
    {
        $this->config = $config;
    }

    public function getResource($resourceName, $resourceId, array $parameters = array())
    {
        $path = "{$resourceName}/{$resourceId}.json";
        $url = $this->buildUrl($path, $parameters);
        $request = $this->buildRequest($url, $parameters);

        return $request->perform();
    }

    public function getCollection($resourceName, array $parameters = array())
    {
        $path = "{$resourceName}.json";
        $url = $this->buildUrl($path, $parameters);
        $request = $this->buildRequest($url, $parameters);

        return $request->perform();
    }

    protected function buildUrl($path = '')
    {
        $rootUrl = $this->config->getApiUrl();
        $url = "{$rootUrl}/{$path}";

        return $url;
    }

    protected function buildRequest($url, array $parameters = array())
    {
        $request = new Request($this->config, new RequestSigner(), new RequestWrapper());
        $request->setMethod('GET')->setUrl($url);

        foreach ($parameters as $name => $value) {
            $request->addQueryParameter($name, $value);
        }

        return $request;
    }
}
