<?php

namespace Keyteq\Keymedia;

use Keyteq\Keymedia\Util\RequestSigner;
use Keyteq\Keymedia\Util\CurlWrapper;
use Keyteq\Keymedia\Util\Parameter\Container\ParameterContainer;
use Keyteq\Keymedia\Util\Parameter\QueryParameter;

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

    public function listMedia($parameters = array())
    {
        $parameterContainer = new ParameterContainer();
        foreach ($parameters as $key => $value) {
            $item = new QueryParameter($key, $value);
            $parameterContainer->add($item);
        }
        $url = $this->buildUrl('media.json', $parameterContainer);

        return $this->request($url);
    }

    public function findMediaByName($q)
    {
        return $this->listMedia(compact('q'));
    }

    public function getAlbum($album, $filter = false)
    {
        $parameters = array('tags' => $album);
        if ($filter) {
            $parameters['q'] = $filter;
        }

        return $this->listMedia($parameters);
    }

    protected function request($url)
    {
        $headers = $this->signer->getSignHeaders(array());

        foreach($headers as $k => $v) {
            $this->curl->addRequestHeader($k, $v);
        }

        $this->curl->setUrl($url);

        $ret = $this->curl->perform();

        return $ret;
    }

    protected function buildUrl($path, ParameterContainer $parameters = null)
    {
        $url = sprintf('http://%s/%s', $this->apiHost, $path);

        if (!(is_null($parameters) || $parameters->isEmpty())) {
            $url .= '&' . $parameters;
        }

        return $url;
    }
}
