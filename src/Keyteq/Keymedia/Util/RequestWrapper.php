<?php

namespace Keyteq\Keymedia\Util;

/**
 * @codeCoverageIgnore
 */
class RequestWrapper
{
    public function get($url, $headers = array(), $options = array())
    {
        $request = \Requests::get($url, $headers, $options);
        return $request->body;
    }
}
