<?php

namespace Keyteq\Keymedia;

class API
{
    protected $apiKey;
    protected $apiHost;
    protected $apiUser;

    public function __construct($apiUser, $apiKey, $apiHost)
    {
        $this->apiUser = $apiUser;
        $this->apiKey = $apiKey;
        $this->apiHost = $apiHost;
    }

    public function getApiUser()
    {
        return $this->apiUser;
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }

    public function getApiHost()
    {
        return $this->apiHost;
    }

}
