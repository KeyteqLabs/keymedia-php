<?php

namespace Keyteq\Keymedia;

use Keyteq\Keymedia\API\Configuration;
use Keyteq\Keymedia\Model\Mapper\MapperFactory;
use Keyteq\Keymedia\Util\RequestBuilder;

class KeymediaClient
{
    protected $api;

    public function __construct($apiUser, $apiUrl, $apiKey = null)
    {
        $options = compact('apiUser', 'apiKey', 'apiUrl');
        $config = new Configuration($options);
        $requestBuilder = new RequestBuilder($config);
        $connector = new API\RestConnector($config, $requestBuilder);
        $mapperFactory = new MapperFactory();
        $this->api = new API($config, $connector, $mapperFactory);
    }

    public function __call($method, $args)
    {
        $target = array($this->api, $method);
        if (is_callable($target)) {
            return call_user_func_array($target, $args);
        }
        throw new \BadMethodCallException("{$method} is not callable or does not exist");
    }

    public function postMedia($file, $name, array $tags = array(), array $attributes = array())
    {
        $file = class_exists('CURLFile') ? new \CURLFile($file) : '@' . $file;
        return $this->api->postMedia($file, $name, $tags, $attributes);
    }

    public function addMediaVersion($id, array $transformations = array())
    {
        if (isset($transformations['coords'])) {
            $transformations['coords'] = implode(',', $transformations['coords']);
        }
        return $this->api->addMediaVersion($id, $transformations);
    }
}
