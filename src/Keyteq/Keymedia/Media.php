<?php

namespace Keyteq\Keymedia;

class Media
{

    protected $rawJson;

    protected $_id;
    protected $attributes = array();
    protected $created;
    protected $modified;
    protected $file;
    protected $name;
    protected $slug;
    protected $tags = array();
    protected $status;
    protected $host;
    protected $scalesTo;
    protected $user;
    protected $version;
    protected $shareUrl;

    public function __construct($json)
    {
        if ($json) {
            $this->rawJson = $json;
            $parsed = json_decode($json, true);
            if (is_null($parsed)) {
                throw new \InvalidArgumentException('Malformed JSON passed');
            }

            if (!array_key_exists('media', $parsed)) {
                throw new \InvalidArgumentException('Key "media" not found in JSON');
            }

            foreach ($parsed['media'] as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
        } else {
            throw new \InvalidArgumentException('No JSON provided!');
        }
    }

    public function getType()
    {
        return $this->file['type'];
    }

    public function isImage()
    {
        return ('image' === $this->getGeneralType());
    }

    public function getUrl()
    {
        return $this->file['url'];
    }

    protected function getGeneralType()
    {
        list($type,) = explode('/', $this->getType());
        return $type;
    }

    public function getThumbnailUrl($width = null, $height = null)
    {
        if ($this->isImage()) {
            if (!(is_int($width) && is_int($height))) {
                throw new \InvalidArgumentException('Image thumbnails require dimensions!');
            }

            return $this->getImageThumbnailUrl($width, $height);

        } else {
            return $this->getTypeThumbnailUrl();
        }
    }

    protected function getTypeThumbnailUrl()
    {
        $extension = $this->getExtension();
        $type = $this->mapExtensionToType($extension);
        $url = $this->buildUrl("images/filetypes/{$type}.png");

        return $url;
    }

    protected function getImageThumbnailUrl($width, $height)
    {
        return $this->buildUrl("{$width}x{$height}/{$this->_id}.png");
    }

    protected function getExtension()
    {
        $extension = $this->file['ending'];

        return pathinfo($extension, PATHINFO_EXTENSION);
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

    protected function buildUrl($path)
    {
        $url = sprintf('%s/%s', $this->host, $path);

        return $url;
    }
}
