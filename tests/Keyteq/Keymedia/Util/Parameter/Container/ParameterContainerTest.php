<?php

use Keyteq\Keymedia\Util\Parameter\Container\ParameterContainer;
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
}
