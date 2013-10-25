<?php

namespace Keyteq\Keymedia\Util;

/**
 * @codeCoverageIgnore
 */
class RequestWrapper
{
    public function get($url, $headers = array(), $options = array())
    {
        return \Requests::get($url, $headers, $options);
    }
}
