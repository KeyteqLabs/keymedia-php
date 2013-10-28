<?php

namespace Keyteq\Keymedia\Model;

class Album extends Item
{
    protected $name;
    protected $total;

    public function getName()
    {
        return $this->name;
    }

    public function getTotal()
    {
        return $this->total;
    }
}
