<?php

namespace Keyteq\Keymedia\Util\Parameter\Container;

use \Keyteq\Keymedia\Util\Parameter\NamedParameter;

class ParameterContainer
{
    protected $elements = array();
    protected $separator;

    public function __construct($separator = '&')
    {
        $this->separator = $separator;
    }

    public function getElements($stringify = false)
    {
        if ($stringify) {
            $ret = array();
            foreach ($this->elements as $element) {
                $ret[] = (string) $element;
            }
        } else {
            $ret = $this->elements;
        }

        return $ret;
    }

    public function add(NamedParameter $param)
    {
        $this->elements[] = $param;
    }

    public function __toString()
    {
        return implode($this->separator, $this->getElements(true));
    }

    public function count()
    {
        return count($this->elements);
    }

    public function isEmpty()
    {
        return (0 === $this->count());
    }
}
