<?php

namespace Keyteq\Keymedia\Util\Parameter;

class NamedParameter
{
    const GLUE = '=';

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

    public function __toString()
    {
        return $this->name . static::GLUE . $this->value;
    }
}
