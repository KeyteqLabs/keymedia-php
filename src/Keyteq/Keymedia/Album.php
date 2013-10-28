<?php

namespace Keyteq\Keymedia;

class Album
{
    protected $rawJson;

    protected $name;
    protected $total;

    public function __construct($json)
    {
        if ($json) {
            $this->rawJson = $json;
            $parsed = json_decode($json, true);
            if (is_null($parsed)) {
                throw new \InvalidArgumentException('Malformed JSON passed');
            }

            if (!array_key_exists('album', $parsed)) {
                throw new \InvalidArgumentException('Key "album" not found in JSON');
            }

            foreach ($parsed['album'] as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }

            if (!array_key_exists('total', $parsed)) {
                throw new \InvalidArgumentException('Key "total" not found in JSON');
            }
            
            $this->total = $parsed['total'];
        } else {
            throw new \InvalidArgumentException('No JSON provided!');
        }
    }
    
    public function getName()
    {
        return $this->name;
    }

    public function getTotal()
    {
        return $this->total;
    }


}
