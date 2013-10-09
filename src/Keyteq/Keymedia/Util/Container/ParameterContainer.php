<?php

namespace Keyteq\Keymedia\Util\Container;

use \Keyteq\Keymedia\Util\NamedParameter;
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
