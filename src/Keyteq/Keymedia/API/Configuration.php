<?php

namespace Keyteq\Keymedia\API;

class Configuration
{

    const DEFAULT_PORT = 80;

    protected $apiUrl;
    protected $apiUser;
    protected $apiKey;
    protected $apiHost;
    protected $apiPort;
    protected $apiScheme;

    public function __construct(array $options)
    {
        foreach ($options as $k => $v) {
            if (property_exists($this, $k)) {
                $this->$k = $v;
            }
        }

        $this->parseBaseUrl($this->apiUrl);
    }

    public function getApiUrl()
    {
        return $this->apiUrl;
    }

    public function getApiHost()
    {
        return $this->apiHost;
    }

    public function getApiUser()
    {
        return $this->apiUser;
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }

    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    protected function parseBaseUrl($url)
    {
        $parsedUrl = parse_url($url);

        if (false === $parsedUrl) {
            throw new \InvalidArgumentException('Keymedia URL is malformed!');
        }

        $this->apiHost = $parsedUrl['host'];
        $this->apiScheme = $parsedUrl['scheme'];
        $this->apiPort = array_key_exists('port', $parsedUrl) ? $parsedUrl['port'] : static::DEFAULT_PORT;
    }
}
