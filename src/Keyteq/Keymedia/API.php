<?php

namespace Keyteq\Keymedia;

use Keyteq\Keymedia\Util\RequestSigner;
use Keyteq\Keymedia\API\Request;
use Keyteq\Keymedia\API\Configuration;
use Keyteq\Keymedia\API\RestConnector;

class API
{
    protected $apiConfig;
    protected $connector;


    public function __construct(Configuration $config, RestConnector $connector)
    {
        $this->apiConfig = $config;
        $this->connector = $connector;
    }

    public function getApiConfig()
    {
        return $this->apiConfig;
    }

    public function listMedia($album = false, $search = false)
    {
        $parameters = array();

        if ($search) {
            $parameters['q'] = $search;
        }

        if ($album) {
            $parameters['tags'] = $album;
        }

        $response = $this->connector->getCollection('media', $parameters);

        return $this->parseMediaResponse($response);
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
        $response = $this->request($path);

        $responseObj = json_decode($response, true);
        $albums = array();
        foreach($responseObj['tags'] as $obj) {
            $albums[] = new Album($obj);
        }
        return $albums;
    }

    protected function request($path, array $parameters = array(), $decodeJson = true)
    {
        $request = new Request($this->getApiConfig(), new RequestSigner());
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

    protected function parseMediaResponse($response)
    {
        $parsed = json_decode($response, true);

        if (!array_key_exists('media', $parsed)) {
            throw new \InvalidArgumentException();
        }

        $media = array();

        foreach ($parsed['media'] as $data) {
            $media[]= new Media($data);
        }

        return $media;
    }
}
