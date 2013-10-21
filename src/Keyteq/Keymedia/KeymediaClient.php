<?php

namespace Keyteq\Keymedia;

class KeymediaClient
{
    protected $apiKey;
    protected $apiHost;
    protected $apiUser;
    protected $api;

    public function __construct($apiUser, $apiKey, $apiHost)
    {
        $this->apiUser = $apiUser;
        $this->apiKey = $apiKey;
        $this->apiHost = $apiHost;

        $this->api = new API($apiUser, $apiKey, $apiHost);
    }

    public function findMedia($searchTerm)
    {
        // TODO
    }

    public function getMedia($mediaId)
    {
        // TODO
    }

    public function getAlbum($albumName)
    {
        // TODO
    }

    public function listAlbums()
    {
        // TODO
    }
}
