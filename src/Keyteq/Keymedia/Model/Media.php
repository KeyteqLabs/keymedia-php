<?php

namespace Keyteq\Keymedia\Model;

use Keyteq\Keymedia\API\Request;

class Media extends Item
{
    protected $_id;
    protected $file;
    protected $name;
    protected $host;

    public function getId()
    {
        return $this->_id;
    }

    public function getName()
    {
        return $this->name;
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

    protected function getHost($protocol = 'http')
    {
        return "{$protocol}://{$this->host}";
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
        return sprintf("%s/%s", $this->getHost(), $path);
    }
}
