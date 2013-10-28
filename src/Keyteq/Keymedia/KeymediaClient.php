<?php

namespace Keyteq\Keymedia;

class KeymediaClient
{
    protected $api;

    public function __construct($apiUser, $apiKey, $apiHost)
    {
        $this->api = new API($apiUser, $apiKey, $apiHost);
    }

    public function getMedia($mediaId)
    {
        return $this->api->getMedia($mediaId);
    }

    public function listAlbums()
    {
        // array(KeymediaAlbum => name, total)
        return $this->api->listAlbums();
    }

    public function listMedia($thumbnailWidth = null, $thumbnailHeight = null, $album = '', $search = '')
    {
        return $this->api->listMedia($thumbnailHeight, $thumbnailWidth, $album, $search);
        // array(KeymediaItem => mediaId, name, type, isImage, url, thumbnailUrl)
    }
}
