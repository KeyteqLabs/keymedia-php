<?php

use Keyteq\Keymedia\Util\QueryParameter;

class QueryParameterTest extends \PHPUnit_Framework_TestCase
{
    public function testToString()
    {
        $name = 'name';
        $value = 'value';
        $item = new QueryParameter($name, $value);

        $expected = "{$name}={$value}";
        $actual = (string) $item;
        $this->assertEquals($expected, $actual);
    }
}
