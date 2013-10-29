<?php

namespace Keyteq\Keymedia\Util;

use Keyteq\Keymedia\BaseTest;

class RequestSignerTest extends BaseTest
{

    protected $apiUser;
    protected $apiKey;
    protected $signer;

    public function setUp()
    {
        parent::setUp();
        $this->apiKey = 'qwertyuiop[]';
        $this->signer = new RequestSigner();
    }

    /**
     * @dataProvider payloadProvider
     */
    public function testGetSignature($payload)
    {
        $signer = $this->signer;
        $message = $this->getMessageForPayload($payload);
        $expected = hash_hmac('sha1', $message, $this->apiKey);
        $actual = $signer->getSignature($message, $this->apiKey);
        $this->assertEquals($expected, $actual);
    }

    public function payloadProvider()
    {
        $ret = array();
        $count = 5;
        for ($i = 0; $i < $count; $i++) {
            $ary = array();
            for ($j = 0; $j < 10; $j++) {
                $key = "key{$j}";
                $value = (string) rand();
                $ary[$key] = $value;
            }

            $ret[] = array($ary);
        }

        return $ret;
    }

    private function getMessageForPayload($payload)
    {
        $message = '';

        foreach ($payload as $k => $v) {
            if (!is_array($v) && substr($v, 0, 1) !== '@') {
                $message .= $k . $v;
            }
        }

        return $message;
    }
}
