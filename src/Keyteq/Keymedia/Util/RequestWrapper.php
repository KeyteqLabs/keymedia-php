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

    public function post($url, $headers = array(), $data = array(), $options = array())
    {
        $request = \Requests::post($url, $headers, $data, $options);

        return $this->getResponse($request);
    }

    public function put($url, $headers = array(), $data = array(), $options = array())
    {
        $request = \Requests::put($url, $headers, $data, $options);

        return $this->getResponse($request);
    }

    public function delete($url, $headers = array(), $options = array())
    {
        $request = \Requests::delete($url, $headers, $options);

        return $this->getResponse($request);
    }

    protected function getResponse($request)
    {
        return $request->body;
    }
}
