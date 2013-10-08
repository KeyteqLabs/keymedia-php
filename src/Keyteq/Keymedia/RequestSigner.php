<?php

namespace Keyteq\Keymedia;

class RequestSigner
{
    protected $apiUser;
    protected $apiKey;

    public function __construct($apiUser, $apiKey)
    {
        $this->apiUser = $apiUser;
        $this->apiKey = $apiKey;
    }

    public function getSignHeaders($payload)
    {
        $signature = $this->getSignature($payload);
        
        return array(
            'X-Keymedia-Username' => $this->apiUser,
            'X-Keymedia-Signature' => $signature
        );
    }

    protected function getSignature($payload)
    {
        $message = '';
        foreach ($payload as $k => $v) {
            if (!is_array($v) && strpos($v, 0, 1) !== '@') {
                $message .= $k . $v;
            }
        }

        $signature = hash_hmac('sha1', $message, $this->apiKey);

        return $signature;
    }

}
