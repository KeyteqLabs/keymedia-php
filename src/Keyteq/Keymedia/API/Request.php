<?php

namespace Keyteq\Keymedia\API;

use Keyteq\Keymedia\Util\RequestWrapper;
use Keyteq\Keymedia\Util\RequestSigner;
use \Requests;

class Request
{
    protected $apiUser;
    protected $apiKey;
    protected $apiHost;
    protected $method = Requests::GET;
    protected $path = '';
    protected $signer;
    protected $requestWrapper;
    protected $queryParameters = array();

    public function __construct(array $apiConfig, RequestSigner $signer, RequestWrapper $requestWrapper)
    {
        $this->apiUser = $apiConfig['apiUser'];
        $this->apiKey = $apiConfig['apiKey'];
        $this->apiHost = $apiConfig['apiHost'];
        $this->signer = $signer;
        $this->requestWrapper = $requestWrapper;
    }

    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    public function perform()
    {
        $url = $this->buildUrl($this->path);
        $headers = $this->getSignHeaders();
        $response = false;
        $options = array();
        $method = strtolower($this->method);

        switch ($this->method) {
            case Requests::GET:
                $response = $this->requestWrapper->$method($url, $headers, $options);
                break;
            default:
                throw new \LogicException("HTTP method '{$this->method}' is not supported.");
        }

        return $response;
    }

    public function getQueryParameters()
    {
        return $this->queryParameters;
    }

    public function addQueryParameter($name, $value)
    {
        $this->queryParameters[$name] = $value;

        return $this;
    }

    protected function buildUrl($path)
    {
        $url = sprintf('http://%s/%s', $this->getApiHost(), $path);
        $queryString = '';

        foreach ($this->queryParameters as $name => $value) {
            $queryString .= $name . '=' . $value;
        }

        if ($queryString) {
            $url .= '?' . $queryString;
        }

        return $url;
    }

    protected function getPayload()
    {
        $payload = '';
        if (!is_null($this->getQueryParameters())) {
            $data = $this->queryParameters;
            ksort($data);
            foreach ($data as $k => $v) {
                if (substr($v, 0, 1) !== '@') {
                    $payload .= $k . $v;
                }
            }
        }

        return $payload;
    }

    protected function getSignature()
    {
        $payload = $this->getPayload();
        $key = $this->getApiKey();

        return $this->signer->getSignature($payload, $key);
    }

    protected function getSignHeaders()
    {
        $user = $this->getApiUser();
        $signature = $this->getSignature();

        $headers = array(
            'X-Keymedia-Username' => $user,
            'X-Keymedia-Signature' => $signature
        );

        return $headers;
    }

    protected function getApiKey()
    {
        return $this->apiKey;
    }

    protected function getApiHost()
    {
        return $this->apiHost;
    }

    protected function getApiUser()
    {
        return $this->apiUser;
    }
}
