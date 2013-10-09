<?php

namespace Keyteq\Keymedia;

use Keyteq\Keymedia\RequestSigner;

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

        $this->signer = new RequestSigner($apiUser, $apiKey);
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

    public function listMedia()
    {
        $path = "media.json";
        $url = "http://{$this->apiHost}/{$path}";

        return $this->request($url);
    }

    protected function request($url, $method = 'GET')
    {
        $headers = $this->signer->getSignHeaders(array());
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $ret = curl_exec($curl);

        return $ret;
    }
}
