<?php

namespace Keyteq\Keymedia\Util;

class CurlWrapper
{

    protected $ch;
    protected $url;

    public function __construct($url = null)
    {
        $this->url = $url;
        $this->ch = curl_init($url);
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
        curl_setopt($this->ch, CURLOPT_URL, $url);
    }
}
