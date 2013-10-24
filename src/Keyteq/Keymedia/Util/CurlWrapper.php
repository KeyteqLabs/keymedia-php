<?php

namespace Keyteq\Keymedia\Util;

use Keyteq\Keymedia\Util\Parameter\Container\ParameterContainer;
use Keyteq\Keymedia\Util\Parameter\HttpHeader;
use Keyteq\Keymedia\Util\Parameter\QueryParameter;
use Keyteq\Keymedia\Util\Parameter\PostField;

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
    protected $postFields;

    public function __construct($url = null, $method = self::METHOD_GET)
    {
        $this->url = $url;
        $this->ch = curl_init($url);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        $this->setMethod($method);
        $this->requestHeaders = array();
        $this->queryParameters = new ParameterContainer();
        $this->postFields = new ParameterContainer();
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

    public function getRequestHeaders()
    {
        return $this->requestHeaders;
    }

    public function setRequestHeaders(array $headers)
    {
        $this->requestHeaders = $headers;
    }

    public function getQueryParameters($stringify = true)
    {
        return $this->queryParameters->getElements($stringify);
    }

    public function getPostFields($stringify = true)
    {
        return $this->postFields->getElements($stringify);
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

    public function addPostField($name, $value)
    {
        $field = new PostField($name, $value);
        $this->postFields->add($field);
    }

    public function perform()
    {
        $headers = $this->getRequestHeaders();
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);
        return curl_exec($this->ch);
    }
}
