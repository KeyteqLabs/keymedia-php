<?php

namespace Keyteq\Keymedia\Model;

class Album extends Item
{
    protected $tag;
    protected $count;

    public function getTag()
    {
        return $this->tag;
    }

    public function getCount()
    {
        return $this->count;
    }
}
