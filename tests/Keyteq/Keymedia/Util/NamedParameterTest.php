<?php

use Keyteq\Keymedia\Util\NamedParameter;

class NamedParameterTest extends \PHPUnit_Framework_TestCase
{

    public function testConstructSetsName()
    {
        $expected = 'some_name';
        $param = new NamedParameter($expected, null);
        $actual = $param->getName();

        $this->assertEquals($expected, $actual);
    }

    public function testConstructSetsValue()
    {
        $expected = 'some_value';
        $name = 'some_name';
        $param = new NamedParameter($name, $expected);
        $actual = $param->getValue();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider nonStringValues
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Name must be a string!
     */
    public function testConstructThrowsOnNonStringName($name)
    {
        new NamedParameter($name, null);
    }

    public function nonStringValues()
    {
        return array(
            array(null),
            array(false),
            array(new stdClass()),
            array(1)
        );
    }
}
