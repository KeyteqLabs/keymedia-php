<?php

namespace Keyteq\Keymedia\Util\Parameter\Container;

use Keyteq\Keymedia\Util\Parameter\NamedParameter;
use \Mockery as m;

class ParameterContainerTest extends \PHPUnit_Framework_TestCase
{

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }

    public function testEmptyByDefault()
    {
        $container = new ParameterContainer();
        $this->assertEmpty($container->getElements());
    }

    public function testAdd()
    {
        $container = new ParameterContainer();
        $item = new NamedParameter('name', 'value');
        $container->add($item);

        $expected = array($item);
        $actual = $container->getElements();

        $this->assertEquals($expected, $actual);
    }

    public function testGetStringifiedElements()
    {
        $stringified = 'key:value';
        $item = m::mock('\Keyteq\Keymedia\Util\Parameter\NamedParameter')
            ->makePartial()
            ->shouldReceive('__toString')
            ->once()
            ->withNoArgs()
            ->andReturn($stringified)
            ->getMock();

        $container = new ParameterContainer();
        $container->add($item);

        $expected = array($stringified);
        $actual = $container->getElements(true);

        $this->assertEquals($expected, $actual);
    }

    public function testCountIsInitiallyZero()
    {
        $container = new ParameterContainer();
        $this->assertEquals(0, $container->count());
    }

    public function testCountIncrementsWhenAdding()
    {
        $container = new ParameterContainer();
        $item = new NamedParameter('key', 'value');
        $container->add($item);

        $this->assertEquals(1, $container->count());
    }

    public function testIsEmpty()
    {
        $container = m::mock('\Keyteq\Keymedia\Util\Parameter\Container\ParameterContainer')
            ->makePartial()
            ->shouldReceive('count')
            ->once()
            ->withNoArgs()
            ->andReturn(0)
            ->getMock();

        $this->assertTrue($container->isEmpty());
    }

    public function testIsNotEmpty()
    {
        $container = m::mock('\Keyteq\Keymedia\Util\Parameter\Container\ParameterContainer')
            ->makePartial()
            ->shouldReceive('count')
            ->once()
            ->withNoArgs()
            ->andReturn(1)
            ->getMock();

        $this->assertFalse($container->isEmpty());
    }

    /**
     * @dataProvider toStringProvider
     */
    public function testToString($items, $separator, $expected)
    {
        $container = new ParameterContainer($separator);
        foreach ($items as $name => $value) {
            $item = new NamedParameter($name, $value);
            $container->add($item);
        }

        $actual = (string) $container;

        $this->assertEquals($expected, $actual);
    }

    public function toStringProvider()
    {
        return array(
            array(
                array('key1' => 'val1', 'key2' => 'val2'),
                '&',
                'key1=val1&key2=val2'
            ),
            array(
                array('k1' => 'v1', 'k2' => ''),
                ';',
                'k1=v1;k2='
            )
        );
    }
}
