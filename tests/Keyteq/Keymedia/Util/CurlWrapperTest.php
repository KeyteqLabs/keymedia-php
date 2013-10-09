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

    public function testConstructSetsMethodToGetByDefault()
    {
        $expected = CurlWrapper::METHOD_GET;
        $actual = $this->ch->getMethod();
        $this->assertEquals($expected, $actual);
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

    public function testNoRequestHeadersByDefault()
    {
        $this->assertEmpty($this->ch->getRequestHeaders());
    }

    public function testAddRequestHeader()
    {
        $name = 'X-Test';
        $value = 'test_value';
        $this->ch->addRequestHeader($name, $value);

        $expected = "{$name}: {$value}";
        $actual = $this->ch->getRequestHeaders();
        $this->assertContains($expected, $actual);
    }

    public function testAddSameHeaderTwice()
    {
        $name = 'X-Test';
        $value = 'test_value';

        $this->ch->addRequestHeader($name, $value);
        $this->ch->addRequestHeader($name, $value);
        $this->assertCount(2, $this->ch->getRequestHeaders());
    }

    public function testNoQueryParametersByDefault()
    {
        $this->assertEmpty($this->ch->getQueryParameters());
    }

    public function testAddQueryParameter()
    {
        $name = 'name';
        $value = 'value';
        $this->ch->addQueryParameter($name, $value);

        $expected = "{$name}={$value}";
        $actual = $this->ch->getQueryParameters();
        $this->assertContains($expected, $actual);
    }

    public function testAddSameParameterTwice()
    {
        $name = 'name';
        $value = 'value';
        $this->ch->addQueryParameter($name, $value);
        $this->ch->addQueryParameter($name, $value);
        $this->assertCount(2, $this->ch->getQueryParameters());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetMethodThrowsOnInvalidMethod()
    {
        $this->ch->setMethod('invalid method');
    }

    /**
     * @dataProvider validMethods
     * @param string $method HTTP method
     */
    public function testSetMethod($method)
    {
        $this->ch->setMethod($method);
        $this->assertEquals($method, $this->ch->getMethod());
    }


    public function validMethods()
    {
        return array(
            array('GET'),
            array('POST'),
            array('PUT')
        );
    }
}