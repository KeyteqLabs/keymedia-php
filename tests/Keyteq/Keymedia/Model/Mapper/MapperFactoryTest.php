<?php

namespace Keyteq\Keymedia\Model\Mapper;

class MapperFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $factory;

    public function setUp()
    {
        parent::setUp();
        $this->factory = new MapperFactory();
    }

    public function testGetMediaMapper()
    {
        $mapper = $this->factory->getMediaMapper();
        $this->assertInstanceOf('\Keyteq\Keymedia\Model\Mapper\MediaMapper', $mapper);
    }

    public function testGetAlbumMapper()
    {
        $mapper = $this->factory->getAlbumMapper();
        $this->assertInstanceOf('\Keyteq\Keymedia\Model\Mapper\AlbumMapper', $mapper);
    }
}
