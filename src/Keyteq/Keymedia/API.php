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

    public function getMediaInfo($id)
    {
        $url = $this->buildUrl("media/{$id}.json");

        return $this->request($url);
    }

    protected function buildUrl($path, ParameterContainer $parameters = null)
    {
        $url = sprintf('http://%s/%s', $this->apiHost, $path);

        if (!(is_null($parameters) || $parameters->isEmpty())) {
            $url .= '&' . $parameters;
        }

        return $url;
    }

    public function getTypeThumbnailUrl($mediaId)
    {
        $url = '';
        $json = $this->getMediaInfo($mediaId);
        if ($json) {
            $parsed = json_decode($json, true);
            $ending = $parsed['media']['file']['ending'];
            $extension = pathinfo($ending, PATHINFO_EXTENSION);
            $type = $this->mapExtensionToType($extension);
            $url = $this->buildUrl("images/filetypes/{$type}.png");
        }

        return $url;
    }

    public function getImageThumbnailUrl($mediaId, $width, $height)
    {
        return $this->buildUrl("{$width}x{$height}/{$mediaId}.png");
    }

    protected function mapExtensionToType($extension)
    {
        $type = 'fileicon_bg';
        $mappings = $this->getExtensionMappings();
        $extension = strtolower($extension);

        if (array_key_exists($extension, $mappings)) {
            $type = $mappings[$extension];
        }

        return $type;
    }

    protected function getExtensionMappings()
    {
        return array(
            'flv' => 'flash',
            'f4p' => 'flash',
            'f4v' => 'flash',
            'swf' => 'flash',
            'pdf' => 'pdf',
            'xlsx' => 'excel',
            'xls' => 'excel',
            'doc' => 'word',
            'docx' => 'word',
            'avi' => 'movie',
            'mpg' => 'movie',
            'mov' => 'movie',
            'key' => 'keynote',
            'mp3' => 'music',
            'psd' => 'photoshop',
            'ppt' => 'powerpoint',
            'pptx' => 'powerpoint',
            'html' => 'html',
            'css' => 'css'
        );
    }
}
