<?php

namespace Keyteq\Keymedia\API;

use Keyteq\Keymedia\API\Configuration;

class RestConnector
{
    protected $config;
    protected $signer;
    protected $wrapper;

    public function __construct(Configuration $config)
    {
        $this->config = $config;
    }

    public function getResource($resourceName, $resourceId, $parameters = array())
    {
        $path = "{$resourceName}/{$resourceId}.json";
        $url = $this->buildUrl($path, $parameters);
        $request = new Request($this->config, $this->signer, $this->wrapper);
        $request->setMethod('GET')->setUrl($url);

        return $request->perform();
    }

    public function getCollection($resourceName, $parameters = array())
    {
        $path = "{$resourceName}.json";
        $url = $this->buildUrl($path, $parameters);

        $request = new Request($this->config, $this->signer, $this->wrapper);
        $request->setMethod('GET')->setUrl($url);

        return $request->perform();
    }

    protected function buildUrl($path = '', $parameters = array())
    {
        $rootUrl = $this->config->getApiUrl();
        $url = "{$rootUrl}/{$path}";

        if (!empty($parameters)) {
            $url .= '?';
            foreach ($parameters as $k => $v) {
                $url .= $k . '=' . $v;
            }
        }

        return $url;
    }
}
