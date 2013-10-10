<?php

namespace Keyteq\Keymedia\Util\Parameter;

class QueryParameter extends NamedParameter
{
    public function __toString()
    {
        return sprintf('%s=%s', $this->name, $this->value);
    }
}
