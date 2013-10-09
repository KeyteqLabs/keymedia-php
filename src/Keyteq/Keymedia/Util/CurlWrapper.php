<?php

namespace Keyteq\Keymedia\Util;

use Keyteq\Keymedia\Util\Container\ParameterContainer;

class CurlWrapper
{

    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';

    protected $ch;
    protected $url;
    protected $method;
    protected $requestHeaders;
    protected $queryParameters;

    public function __construct($url = null, $method = self::METHOD_GET)
    {
        $this->url = $url;
        $this->ch = curl_init($url);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        $this->setMethod($method);
        $this->requestHeaders = new ParameterContainer();
        $this->queryParameters = new ParameterContainer();
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
        curl_setopt($this->ch, CURLOPT_URL, $url);
    }

    public function getRequestHeaders($stringify = true)
    {
        return $this->requestHeaders->getElements($stringify);
    }

    public function getQueryParameters($stringify = true)
    {
        return $this->queryParameters->getElements($stringify);
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setMethod($method)
    {
        switch ($method) {

            case static::METHOD_GET:
                curl_setopt($this->ch, CURLOPT_HTTPGET, true);
                break;
            case static::METHOD_POST:
                curl_setopt($this->ch, CURLOPT_POST, true);
                break;
            case static::METHOD_PUT:
                curl_setopt($this->ch, CURLOPT_PUT, true);
                break;
            default:
                throw new \InvalidArgumentException("Method not supported: {$method}");
        }
        $this->method = $method;
    }

    /**
     * Add a header to the request
     *
     * @param string $name header name
     * @param string $value header value
     */
    public function addRequestHeader($name, $value)
    {
        $header = new HttpHeader($name, $value);
        $this->requestHeaders->add($header);
    }

    /**
     * Add a parameter to the request query string
     *
     * @param string $name parameter name
     * @param string $value parameter value
     */
    public function addQueryParameter($name, $value)
    {
        $param = new QueryParameter($name, $value);
        $this->queryParameters->add($param);
    }

    public function perform()
    {
        $headers = $this->getRequestHeaders();
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);
        return curl_exec($this->ch);
    }
}
