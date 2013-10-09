<?php

use Keyteq\Keymedia\RequestSigner;

class RequestSignerTest extends \PHPUnit_Framework_TestCase
{

    protected $apiUser;
    protected $apiKey;
    protected $signer;

    public function setUp()
    {
        parent::setUp();
        $this->apiUser = 'some_user';
        $this->apiKey = 'qwertyuiop[]';
        $this->signer = new RequestSigner($this->apiUser, $this->apiKey);
    }

    /**
     * @dataProvider payloadProvider
     */
    public function testGetSignature($payload)
    {
        $signer = $this->signer;
        $message = $this->getMessageForPayload($payload);
        $signature = hash_hmac('sha1', $message, $this->apiKey);
        $expected = array(
            "X-Keymedia-Username" => $this->apiUser,
            "X-Keymedia-Signature" => $signature
        );
        $actual = $signer->getSignHeaders($payload);
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
