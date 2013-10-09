<?php

namespace Keyteq\Keymedia;

use Keyteq\Keymedia\RequestSigner;
use Keyteq\Keymedia\Util\CurlWrapper;

class API
{
    protected $apiKey;
    protected $apiHost;
    protected $apiUser;
    protected $curl;

    public function __construct($apiUser, $apiKey, $apiHost)
    {
        $this->apiUser = $apiUser;
        $this->apiKey = $apiKey;
        $this->apiHost = $apiHost;

        $this->signer = new RequestSigner($apiUser, $apiKey);
        $this->curl = new CurlWrapper();
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
        $url = $this->buildUrl('media.json');

        return $this->request($url);
    }

    protected function request($url, $method = 'GET')
    {
        $headers = $this->signer->getSignHeaders(array());

        foreach($headers as $k => $v) {
            $this->curl->addRequestHeader($k, $v);
        }

        $this->curl->setUrl($url);

        $ret = $this->curl->perform();

        return $ret;
    }

    protected function buildUrl($path)
    {
        return sprintf('http://%s/%s', $this->apiHost, $path);
    }
}
