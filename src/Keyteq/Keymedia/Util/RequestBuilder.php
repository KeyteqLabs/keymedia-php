<?php

namespace Keyteq\Keymedia\Util;

use Keyteq\Keymedia\API\Configuration;
use Keyteq\Keymedia\API\Request;

class RequestBuilder
{
    public $config;

    public function __construct(Configuration $config)
    {
        $this->config = $config;
    }

    public function buildRequest($url, $method = 'GET', array $parameters = array())
    {
        $request = new Request($this->config, new RequestSigner(), new RequestWrapper());
        $request->setMethod($method)->setUrl($url);

        foreach ($parameters as $name => $value) {
            $request->addParameter($name, $value);
        }

        return $request;
    }
}
