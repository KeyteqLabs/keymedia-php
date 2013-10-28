<?php

namespace Keyteq\Keymedia\Model;

abstract class Item
{
    public function __construct(array $data)
    {
        foreach ($data as $k => $v) {
            if (property_exists($this, $k)) {
                $this->$k = $v;
            }
        }
    }
    
    public function toArray()
    {
        $arr = array();
        foreach($this as $key => $value) {
            $arr[$key] = $value;
        }
        return $arr;
    }
    
}
