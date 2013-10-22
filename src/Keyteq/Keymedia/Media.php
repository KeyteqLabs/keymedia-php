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

    public function getThumbnailUrl($width, $height)
    {
        // TODO
    }
}
