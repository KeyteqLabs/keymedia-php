<?php

namespace Keyteq\Keymedia;

use Keyteq\Keymedia\API\Configuration;
use Keyteq\Keymedia\API\RestConnector;
use Keyteq\Keymedia\Model\Mapper\MapperFactory;

class API
{
    protected $apiConfig;
    protected $connector;
    protected $mapperFactory;
    protected $mediaMapper;
    protected $albumMapper;

    public function __construct(Configuration $config, RestConnector $connector, MapperFactory $mapperFactory)
    {
        $this->apiConfig = $config;
        $this->connector = $connector;
        $this->mapperFactory = $mapperFactory;
        $this->mediaMapper = $mapperFactory->getMediaMapper();
        $this->albumMapper = $mapperFactory->getAlbumMapper();
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
        $result = $this->mediaMapper->mapCollection($response);

        return $result;
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
        $response = $this->connector->getCollection('tags');
        $result = $this->albumMapper->mapCollection($response);

        return $result;
    }

    public function getMedia($id)
    {
        $response = $this->connector->getResource('media', $id);
        $result = $this->mediaMapper->mapItem($response);

        return $result;
    }

    public function postMedia($file, $name, array $tags = array(), array $attributes = array())
    {
        $args = compact('file', 'name', 'tags', 'attributes');
        $payload = array_filter($args);
        $response = $this->connector->postResource('media', $payload);
        $result = $this->mediaMapper->mapItem($response);

        return $result;
    }

    public function isConnected()
    {
        $response = $this->connector->getCollection('media', array('q' => ''));
        return !!json_decode($response);
    }

    public function getToken($password)
    {
        $username = $this->apiConfig->getApiUser();
        $parameters = compact('username', 'password');
        $response = $this->connector->postResource('users/token', $parameters);
        $parsedResponse = json_decode($response, true);

        if ($parsedResponse['ok']) {
            $token = $parsedResponse['token'];
            $this->apiConfig->setApiKey($token);

            return $token;
        } else {
            return false;
        }
    }
}
