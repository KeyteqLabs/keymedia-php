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

        return $this->getResponse($request);
    }

    protected function getResponse($request)
    {
        return $request->body;
    }
}
