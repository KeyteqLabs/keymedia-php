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

    public function __construct($url = null, $method = self::METHOD_GET)
    {
        $this->url = $url;
        $this->ch = curl_init($url);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        $this->setMethod($method);
        $this->requestHeaders = new ParameterContainer();
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
        return $this->requestHeaders->getElements(true);
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

}
