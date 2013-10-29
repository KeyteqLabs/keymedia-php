<?php

namespace Keyteq\Keymedia\Model\Mapper;

use Keyteq\Keymedia\BaseTest;

class MapperFactoryTest extends BaseTest
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
