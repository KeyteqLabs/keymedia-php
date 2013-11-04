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
        $parsed = parse_url($url);

        if (false === $parsed) {
            throw new \InvalidArgumentException('Malformed URL!');
        }

        if (array_key_exists('query', $parsed)) {
            $this->parseQuery($parsed['query']);
            $url = str_replace('?' . $parsed['query'], '', $url);
        }

        $this->url = $url;

        return $this;
    }

    public function setMethod($method)
    {
        if ($this->isMethodSupported($method)) {
            $this->method = $method;
        }

        return $this;
    }

    public function perform()
    {
        $headers = $this->getSignHeaders();
        $options = array();
        $this->url .= '?' . $this->getQueryParameters(true);
        $response = $this->getResponse($headers, $options);

        return $response;
    }

    public function getQueryParameters($stringify = false)
    {
        return $stringify
            ? http_build_query($this->queryParameters)
            : $this->queryParameters;
    }

    public function addQueryParameter($name, $value)
    {
        $this->queryParameters[$name] = $value;

        return $this;
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

    protected function getResponse($headers, $options, array $data = array())
    {
        $response = false;
        $method = strtolower($this->method);

        switch ($this->method) {
            case Requests::GET:
            case Requests::DELETE:
                $response = $this->requestWrapper->$method($this->url, $headers, $options);
                break;
            case Requests::POST:
            case Requests::PUT:
                $response = $this->requestWrapper->$method($this->url, $headers, $data, $options);
                break;
            default:
                throw new \LogicException("HTTP method '{$this->method}' is not supported.");
        }

        return $response;
    }

    protected function isMethodSupported($method)
    {
        $supportedMethods = $this->supportedMethods();

        if (!in_array($method, $supportedMethods)) {
            throw new \LogicException("HTTP method {$method} is not supported!");
        }

        return true;
    }

    protected function supportedMethods()
    {
        return array(
            Requests::GET,
            Requests::POST,
            Requests::PUT,
            Requests::DELETE
        );
    }
}
