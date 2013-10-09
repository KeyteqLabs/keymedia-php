<?php

namespace Keyteq\Keymedia\Util;

class NamedParameter
{
    protected $name;
    protected $value;

    public function __construct($name, $value)
    {
        if (!is_string($name)) {
            throw new \InvalidArgumentException("Name must be a string!");
        }

        $this->name = $name;
        $this->value = $value;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getValue()
    {
        return $this->value;
    }
}
