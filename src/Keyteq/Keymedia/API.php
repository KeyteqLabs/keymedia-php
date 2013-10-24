<?php

namespace Keyteq\Keymedia;

use Keyteq\Keymedia\Util\RequestSigner;
use Keyteq\Keymedia\Util\CurlWrapper;
use Keyteq\Keymedia\Util\Parameter\Container\ParameterContainer;
use Keyteq\Keymedia\Util\Parameter\QueryParameter;
use Keyteq\Keymedia\API\Request;

class API
{
    protected $apiKey;
    protected $apiHost;
    protected $apiUser;
    protected $curl;
    protected $signer;

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

        return $this->request('media.json', $parameters);
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

    public function listAlbums()
    {
        $path = 'tags.json';

        return $this->request($path);
    }

    protected function request($path, array $parameters = array(), $decodeJson = true)
    {
        $request = new Request($this->getApiConfig(), $this->curl, new RequestSigner());
        $request->setPath($path);

        foreach ($parameters as $k => $v) {
            $request->addQueryParameter($k, $v);
        }

        $response = $request->perform();

        return $decodeJson ? json_decode($response, true) : $response;
    }

    public function getMedia($id)
    {
        $path = "media/{$id}.json";
        $json = $this->request($path, array(), false);

        return new Media($json);
    }

    protected function getApiConfig()
    {
        return array(
            'apiUser' => $this->getApiUser(),
            'apiKey' => $this->getApiKey(),
            'apiHost' => $this->getApiHost()
        );
    }
}
