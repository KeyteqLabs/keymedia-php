<?php

namespace Keyteq\Keymedia;

use Keyteq\Keymedia\API\Configuration;

class KeymediaClient
{
    protected $api;

    public function __construct($apiUser, $apiKey, $apiUrl)
    {
        $options = compact('apiUser', 'apiKey', 'apiUrl');
        $config = new Configuration($options);
        $connector = new API\RestConnector($config);
        $this->api = new API($config, $connector);
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
}
