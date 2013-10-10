<?php

namespace Keyteq\Keymedia\Util\Parameter;

abstract class RequestParameter extends NamedParameter
{
    public function __toString()
    {
        return sprintf('%s=%s', $this->name, $this->value);
    }
}
