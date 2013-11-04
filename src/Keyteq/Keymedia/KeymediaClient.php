<?php

namespace Keyteq\Keymedia;

use Keyteq\Keymedia\API\Configuration;
use Keyteq\Keymedia\Model\Mapper\MapperFactory;
use Keyteq\Keymedia\Util\RequestBuilder;

class KeymediaClient
{
    protected $api;

    public function __construct($apiUser, $apiKey, $apiUrl)
    {
        $options = compact('apiUser', 'apiKey', 'apiUrl');
        $config = new Configuration($options);
        $requestBuilder = new RequestBuilder($config);
        $connector = new API\RestConnector($config, $requestBuilder);
        $mapperFactory = new MapperFactory();
        $this->api = new API($config, $connector, $mapperFactory);
    }

    public function getMedia($mediaId)
    {
        return $this->api->getMedia($mediaId);
    }

    public function listAlbums()
    {
        return $this->api->listAlbums();
    }

    public function listMedia($album = false, $search = false)
    {
        return $this->api->listMedia($album, $search);
    }

    public function postMedia($file, $name, array $tags = array(), array $attributes = array())
    {
        return new Model\Media(array()); // FIXME just a stub for early integration
        return $this->api->postMedia($file, $name, $tags, $attributes);
    }

    public function isConnected()
    {
        return $this->api->isConnected();
    }
}
