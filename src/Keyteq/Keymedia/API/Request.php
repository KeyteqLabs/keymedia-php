<?php

namespace Keyteq\Keymedia\API;

use Keyteq\Keymedia\Util\RequestWrapper;
use Keyteq\Keymedia\Util\RequestSigner;
use Keyteq\Keymedia\API\Configuration;
use \Requests;

class Request
{
    protected $config;
    protected $method = Requests::GET;
    protected $url = '';
    protected $signer;
    protected $requestWrapper;
    protected $queryParameters = array();

    public function __construct(Configuration $config, RequestSigner $signer, RequestWrapper $requestWrapper)
    {
        $this->config = $config;
        $this->signer = $signer;
        $this->requestWrapper = $requestWrapper;
    }

    public function setUrl($url)
    {
        $this->url = $url;
        $parsed = parse_url($url);

        if (false === $parsed) {
            throw new \InvalidArgumentException('Malformed URL!');
        }

        if (array_key_exists('query', $parsed)) {
            $this->parseQuery($parsed['query']);
        }

        return $this;
    }

    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    public function perform()
    {
        $headers = $this->getSignHeaders();
        $options = array();
        $response = $this->getResponse($headers, $options);

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

    public static function buildUrl($host, $path, $queryParameters = array())
    {
        $url = sprintf('%s/%s', $host, $path);
        $queryString = '';

        foreach ($queryParameters as $name => $value) {
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
        return $this->config->getApiKey();
    }

    protected function getApiHost()
    {
        return $this->config->getApiHost();
    }

    protected function getApiUser()
    {
        return $this->config->getApiUser();
    }

    protected function parseQuery($query)
    {
        $items = explode('&', $query);
        foreach ($items as $item) {
            list($name, $value) = explode('=', $item);
            $this->addQueryParameter($name, $value);
        }
    }

    protected function getResponse($headers, $options)
    {
        $response = false;
        $method = strtolower($this->method);

        switch ($this->method) {
            case Requests::GET:
                $response = $this->requestWrapper->$method($this->url, $headers, $options);
                break;
            default:
                throw new \LogicException("HTTP method '{$this->method}' is not supported.");
        }

        return $response;
    }
}
