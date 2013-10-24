<?php

namespace Keyteq\Keymedia\Util;

class RequestSigner
{
    public function getSignature($payload, $key)
    {
        return hash_hmac('sha1', $payload, $key);
    }
}
