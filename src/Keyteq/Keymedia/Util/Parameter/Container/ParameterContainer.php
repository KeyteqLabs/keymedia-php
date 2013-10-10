<?php

namespace Keyteq\Keymedia\Util\Parameter\Container;

use \Keyteq\Keymedia\Util\Parameter\NamedParameter;

class ParameterContainer
{
    protected $elements = array();

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
}
