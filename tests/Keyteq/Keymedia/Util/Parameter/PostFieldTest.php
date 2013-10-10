<?php

namespace Keyteq\Keymedia\Util\Parameter;

class PostFieldTest extends \PHPUnit_Framework_TestCase
{
    public function testToString()
    {
        $name = 'name';
        $value = 'value';
        $item = new PostField($name, $value);

        $expected = "{$name}={$value}";
        $actual = (string) $item;
        $this->assertEquals($expected, $actual);
    }
}