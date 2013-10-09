<?php

use Keyteq\Keymedia\Util\HttpHeader;

class HttpHeaderTest extends \PHPUnit_Framework_TestCase
{
    public function testToString()
    {
        $name = 'name';
        $value = 'value';
        $item = new HttpHeader($name, $value);
        $expected = "{$name}: {$value}";
        $actual = (string) $item;
        $this->assertEquals($expected, $actual);
    }
}
