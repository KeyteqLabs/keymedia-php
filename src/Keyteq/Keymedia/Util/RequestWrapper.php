<?php

namespace Keyteq\Keymedia\Util;

/**
 * @codeCoverageIgnore
 */
class RequestWrapper
{
    public function get($url, $headers = array())
    {
        $request = \Unirest::get($url, $headers);

        return $this->getResponse($request);
    }

    public function post($url, $headers = array(), $data = array())
    {
        $request = \Unirest::post($url, $headers, $data);

        return $this->getResponse($request);
    }

    public function put($url, $headers = array(), $data = array())
    {
        $request = \Unirest::put($url, $headers, $data);

        return $this->getResponse($request);
    }

    public function delete($url, $headers = array())
    {
        $request = \Unirest::delete($url, $headers);

        return $this->getResponse($request);
    }

    protected function getResponse($request)
    {
        return $request->raw_body;
    }
}
