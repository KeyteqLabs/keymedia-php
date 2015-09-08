<?php

namespace Keyteq\Keymedia\Model;

use Keyteq\Keymedia\API\Request;

class Media extends Item
{
    protected $_id;
    protected $file;
    protected $name;
    protected $host;
    protected $shareUrl;
    protected $created;
    protected $modified;

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

    public function isImage(array $types = array())
    {
        list($general,$type) = explode('/', $this->getType());
        return $general === 'image' && (!$types || in_array($type, $types));
    }

    public function getUrl()
    {
        return $this->file['url'];
    }

    /**
     * @param string/false $protocol false to get host without protocol
     * @return string
     */
    public function getHost($protocol = 'http')
    {
        return $protocol === false ? $this->host : "{$protocol}://{$this->host}";
    }

    public function getShareUrl()
    {
        return $this->shareUrl;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getModified()
    {
        return $this->modified;
    }

    public function getThumbnailUrl($width = null, $height = null)
    {
        if ($this->isImage(array('png', 'jpg', 'jpeg', 'gif'))) {
            if (!(is_int($width) && is_int($height))) {
                throw new \InvalidArgumentException('Image thumbnails require dimensions!');
            }
            return $this->getImageThumbnailUrl($width, $height);
        }
        elseif ($this->isImage(array('svg+xml'))) {
            return $this->shareUrl;
        }
        return $this->getTypeThumbnailUrl();
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
        $ending = $this->getExtension();
        return $this->buildUrl("{$width}x{$height}/{$this->_id}.{$ending}");
    }

    public function getExtension()
    {
        if ($this->file && isset($this->file['ending'])) {
            $ending = $this->file['ending'];
        }
        else if ($this->file && isset($this->file['type'])) {
            list($void,$ending) = explode('/', $this->file['type']);
            $ending = '.' . $ending;
        }
        else {
            throw new \Exception('Cant get file ending');
        }
        return pathinfo($ending, PATHINFO_EXTENSION);
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
