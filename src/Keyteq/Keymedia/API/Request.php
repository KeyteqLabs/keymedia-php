<?php

namespace Keyteq\Keymedia\API;

use Keyteq\Keymedia\Util\CurlWrapper;
use Keyteq\Keymedia\Util\Parameter\Container\ParameterContainer;
use Keyteq\Keymedia\Util\RequestSigner;
use Keyteq\Keymedia\Util\Parameter\HttpHeader;
use Keyteq\Keymedia\Util\Parameter\QueryParameter;

class Request
{
    protected $apiUser;
    protected $apiKey;
    protected $apiHost;
    protected $curl;
    protected $path = '';
    protected $signer;
    protected $queryParameters;

    public function __construct(array $apiConfig, CurlWrapper $curl, RequestSigner $signer)
    {
        $this->apiUser = $apiConfig['apiUser'];
        $this->apiKey = $apiConfig['apiKey'];
        $this->apiHost = $apiConfig['apiHost'];
        $this->curl = $curl;
        $this->signer = $signer;
        $this->queryParameters = new ParameterContainer();
    }

    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    public function setMethod($method)
    {
        $this->curl->setMethod($method);

        return $this;
    }

    public function perform()
    {
        $this->curl->setMethod(CurlWrapper::METHOD_GET);
        $url = $this->buildUrl($this->path);
        $this->curl->setUrl($url);
        $headers = $this->getSignHeaders();
        $this->curl->setRequestHeaders($headers->getElements());

        return $this->curl->perform();
    }

    public function getQueryParameters()
    {
        return $this->queryParameters;
    }

    public function addQueryParameter($name, $value)
    {
        $parameter = new QueryParameter($name, $value);
        $this->queryParameters->add($parameter);

        return $this;
    }

    protected function buildUrl($path)
    {
        $url = sprintf('http://%s/%s', $this->getApiHost(), $path);

        $parameters = $this->getQueryParameters();
        if (!(is_null($parameters) || $parameters->isEmpty())) {
            $url .= '?' . $parameters;
        }

        return $url;
    }

    protected function getPayload()
    {
        $payload = '';
        if (!is_null($this->getQueryParameters())) {
            $data = $this->queryParameters->getElements(false);
            ksort($data);
            foreach ($data as $parameter) {
                $k = $parameter->getName();
                $v = $parameter->getValue();
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

        $userHeader = new HttpHeader('X-Keymedia-Username', $user);
        $sigHeader = new HttpHeader('X-Keymedia-Signature', $signature);
        $headers = new ParameterContainer(': ');
        $headers->add($userHeader);
        $headers->add($sigHeader);

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
