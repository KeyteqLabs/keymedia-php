<?php

use Keyteq\Keymedia\Util\CurlWrapper;

class CurlWrapperTest extends \PHPUnit_Framework_TestCase
{

    protected $url = 'http://example.com';
    protected $ch;

    public function setUp()
    {
        parent::setUp();
        $this->ch = new CurlWrapper($this->url);
    }

    public function testConstruct()
    {
        $cw = new CurlWrapper($this->url);
        $this->assertInstanceOf('\Keyteq\Keymedia\Util\CurlWrapper', $cw);
    }

    public function testGetUrl()
    {
        $expected = 'http://example2.com';
        $this->ch = new CurlWrapper($expected);
        $actual = $this->ch->getUrl();

        $this->assertEquals($expected, $actual);
    }

    public function testSetUrl()
    {
        $expected = 'http://example2.com';
        $this->ch->setUrl($expected);
        $actual = $this->ch->getUrl();

        $this->assertEquals($expected, $actual);
    }

}
