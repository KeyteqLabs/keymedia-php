<?php

namespace Keyteq\Keymedia\Model\Mapper;

abstract class AbstractMapper
{
    const JSON_RESPONSE_KEY = null;
    const TARGET_CLASS = null;

    public function __construct()
    {
        $this->checkJsonResponseKey();
        $this->checkTargetClass();
    }

    public function mapItem($json)
    {
        $parsed = $this->jsonToArray($json);
        $data = $this->retrieveByKey($parsed, static::JSON_RESPONSE_KEY);
        $item = $this->createItem($data);

        return $item;
    }

    public function mapCollection($json)
    {
        $parsed = $this->jsonToArray($json);
        $data = $this->retrieveByKey($parsed, static::JSON_RESPONSE_KEY);

        return array_map(array($this, 'createItem'), $data);
    }

    protected function createItem($data)
    {
        $klass = static::TARGET_CLASS;
        return new $klass($data);
    }

    protected function retrieveByKey(array $data, $key)
    {
        if (array_key_exists($key, $data)) {
            return $data[$key];
        } else {
            throw new \InvalidArgumentException(sprintf('Key %s not found in provided JSON', static::JSON_RESPONSE_KEY));
        }
    }

    protected function jsonToArray($json)
    {
        $parsed = json_decode($json, true);

        if (is_null($parsed)) {
            throw new \InvalidArgumentException('Invalid JSON provided');
        }

        return $parsed;
    }

    protected function checkJsonResponseKey()
    {
        if (is_null(static::JSON_RESPONSE_KEY)) {
            throw new \LogicException('Constant JSON_RESPONSE_KEY undefined in ' . get_called_class());
        }
    }

    protected function checkTargetClass()
    {
        if (is_null(static::TARGET_CLASS)) {
            throw new \LogicException('Constant TARGET_CLASS undefined in ' . get_called_class());
        }

        if (!class_exists(static::TARGET_CLASS)) {
            throw new \LogicException(sprintf('Target class %s not found in %s', static::TARGET_CLASS, get_called_class()));
        }
    }
}
