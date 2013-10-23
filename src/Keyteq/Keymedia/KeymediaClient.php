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
        return $this->api->findMediaByName($searchTerm);
    }

    public function getMedia($mediaId)
    {
        return $this->api->getMedia($mediaId);
    }

    public function getAlbum($albumName, $searchTerm = false)
    {
        return $this->api->getAlbum($albumName, $searchTerm);
    }

    public function listAlbums()
    {
        return $this->api->listAlbums();
    }
}
