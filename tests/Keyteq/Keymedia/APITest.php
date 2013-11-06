<?php

namespace Keyteq\Keymedia;

use \Mockery as m;
use Keyteq\Keymedia\Util\RequestBuilder;
use Keyteq\Keymedia\Model\Mapper\MapperFactory;
use Keyteq\Keymedia\API\RestConnector;
use org\bovigo\vfs\vfsStream;

class APITest extends FilesystemTest
{
    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Configuration
     */
    public function testConstructRequiresConfigurationArgument()
    {
        new API();
    }

    public function testListMediaWithNoArguments()
    {
        $response = new \stdClass();
        $expected = new \stdClass();
        $restMocks = array(
            'getCollection' => array(
                'args' => array('media', array()),
                'count' => 1,
                'return' => $response
            )
        );

        $rest = $this->getRestConnectorMock($restMocks);
        $mediaMapper = $this->getMapperMock('Media', 'Collection', array($response), $expected);
        $factoryMocks = array('getMediaMapper' => $mediaMapper);
        $mapperFactory = $this->getMapperFactoryMock($factoryMocks);
        $api = new API($this->apiConfig, $rest, $mapperFactory);

        $actual = $api->listMedia();

        $this->assertSame($expected, $actual);
    }

    public function testListMediaWithAlbum()
    {
        $albumName = 'album_name';
        $response = new \stdClass();
        $expected = new \stdClass();
        $restMocks = array(
            'getCollection' => array(
                'args' => array('media', array('tags' => $albumName)),
                'count' => 1,
                'return' => $response
            )
        );

        $rest = $this->getRestConnectorMock($restMocks);
        $mediaMapper = $this->getMapperMock('Media', 'Collection', array($response), $expected);
        $factoryMocks = array('getMediaMapper' => $mediaMapper);
        $mapperFactory = $this->getMapperFactoryMock($factoryMocks);
        $api = new API($this->apiConfig, $rest, $mapperFactory);

        $actual = $api->listMedia($albumName);

        $this->assertSame($expected, $actual);
    }

    public function testListMediaWithAlbumAndSearch()
    {
        $albumName = 'album_name';
        $search = 'search_term';
        $response = new \stdClass();
        $expected = new \stdClass();
        $restMocks = array(
            'getCollection' => array(
                'args' => array('media', array('tags' => $albumName, 'q' => $search)),
                'count' => 1,
                'return' => $response
            )
        );

        $rest = $this->getRestConnectorMock($restMocks);
        $mediaMapper = $this->getMapperMock('Media', 'Collection', array($response), $expected);
        $factoryMocks = array('getMediaMapper' => $mediaMapper);
        $mapperFactory = $this->getMapperFactoryMock($factoryMocks);
        $api = new API($this->apiConfig, $rest, $mapperFactory);

        $actual = $api->listMedia($albumName, $search);

        $this->assertSame($expected, $actual);
    }

    public function testFindMedia()
    {
        $searchTerm = 'searchTerm';
        $api = m::mock('\Keyteq\Keymedia\API')
            ->makePartial()
            ->shouldReceive('listMedia')
            ->once()
            ->with(array('q' => $searchTerm))
            ->getMock();

        $api->findMediaByName($searchTerm);
    }

    public function testListAlbum()
    {
        $albumName = 'albumName';
        $api = m::mock('\Keyteq\Keymedia\API')
            ->makePartial()
            ->shouldReceive('listMedia')
            ->once()
            ->with(array('tags' => $albumName))
            ->getMock();

        $api->getAlbum($albumName);
    }


    public function testListAlbumWithFilter()
    {
        $albumName = 'albumName';
        $filter = 'filter';
        $api = m::mock('\Keyteq\Keymedia\API')
            ->makePartial()
            ->shouldReceive('listMedia')
            ->once()
            ->with(array('tags' => $albumName, 'q' => $filter))
            ->getMock();

        $api->getAlbum($albumName, $filter);
    }

    public function testListAlbums()
    {
        $response = new \stdClass();
        $expected = new \stdClass();
        $restMocks = array(
            'getCollection' => array(
                'args' => array('tags'),
                'count' => 1,
                'return' => $response
            )
        );

        $rest = $this->getRestConnectorMock($restMocks);
        $albumMapper = $this->getMapperMock('Album', 'Collection', array($response), $expected);
        $factoryMocks = array('getAlbumMapper' => $albumMapper);
        $mapperFactory = $this->getMapperFactoryMock($factoryMocks);
        $api = new API($this->apiConfig, $rest, $mapperFactory);

        $actual = $api->listAlbums();

        $this->assertSame($expected, $actual);
    }

    public function testGetMedia()
    {
        $mediaId = 'media_id';
        $response = new \stdClass();
        $expected = new \stdClass();
        $restMocks = array(
            'getResource' => array(
                'args' => array('media', $mediaId),
                'count' => 1,
                'return' => $response
            )
        );

        $rest = $this->getRestConnectorMock($restMocks);
        $mediaMapper = $this->getMapperMock('Media', 'Item', array($response), $expected);
        $factoryMocks = array('getMediaMapper' => $mediaMapper);
        $mapperFactory = $this->getMapperFactoryMock($factoryMocks);
        $api = new API($this->apiConfig, $rest, $mapperFactory);

        $actual = $api->getMedia($mediaId);

        $this->assertSame($expected, $actual);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage could not be read
     */
    public function testPostMediaThrowsWhenFileUnreadable()
    {
        $file = '/nonexistent';
        $name = 'media_name';
        $rest = new RestConnector($this->apiConfig, new RequestBuilder($this->apiConfig));
        $mapperFactory = new MapperFactory();
        $api = new API($this->apiConfig, $rest, $mapperFactory);

        $api->postMedia($file, $name);
    }

    public function testPostMedia()
    {
        $filename = 'filename';
        $content = 'content';
        $file = $this->addFile($filename, $content);
        $name = 'media_name';
        $tags = array('tag');
        $attributes = array('attr1' => 'val1');
        $args = compact('file', 'name', 'tags', 'attributes');
        $argsAfterFileRead = $args;
        $argsAfterFileRead['file'] = $content;

        $response = new \stdClass();
        $expected = new \stdClass();
        $restMocks = array(
            'postResource' => array(
                'args' => array('media', $argsAfterFileRead),
                'count' => 1,
                'return' => $response
            )
        );

        $rest = $this->getRestConnectorMock($restMocks);
        $mediaMapper = $this->getMapperMock('Media', 'Item', array($response), $expected);
        $factoryMocks = array('getMediaMapper' => $mediaMapper);
        $mapperFactory = $this->getMapperFactoryMock($factoryMocks);
        $api = new API($this->apiConfig, $rest, $mapperFactory);

        $actual = $api->postMedia($file, $name, $tags, $attributes);

        $this->assertSame($expected, $actual);
    }

    public function testPostMediaFiltersEmptyArguments()
    {
        $filename = 'filename';
        $fileContent = 'content';
        $name = 'media_name';
        $file = $this->addFile($filename, $fileContent);
        $tags = array();
        $attributes = array();
        $filteredArgs = compact('file', 'name');
        $argsAfterFileRead = $filteredArgs;
        $argsAfterFileRead['file'] = $fileContent;

        $response = new \stdClass();
        $expected = new \stdClass();
        $restMocks = array(
            'postResource' => array(
                'args' => array('media', $argsAfterFileRead),
                'count' => 1,
                'return' => $response
            )
        );

        $rest = $this->getRestConnectorMock($restMocks);
        $mediaMapper = $this->getMapperMock('Media', 'Item', array($response), $expected);
        $factoryMocks = array('getMediaMapper' => $mediaMapper);
        $mapperFactory = $this->getMapperFactoryMock($factoryMocks);

        $api = new API($this->apiConfig, $rest, $mapperFactory);
        $api->postMedia($file, $name, $tags, $attributes);
    }

    public function testIsConnectedReturnsTrueIfValidJsonReceived()
    {
        $restMocks = array(
            'getCollection' => array(
                'args' => array('media', array('q' => '')),
                'count' => 1,
                'return' => '{}'
            )
        );

        $rest = $this->getRestConnectorMock($restMocks);
        $api = new API($this->apiConfig, $rest, new Model\Mapper\MapperFactory());
        $result = $api->isConnected();

        $this->assertTrue($result);
    }

    public function testIsConnectedReturnsFalseIfInvalidJsonReceived()
    {
        $restMocks = array(
            'getCollection' => array(
                'args' => array('media', array('q' => '')),
                'count' => 1,
                'return' => 'non-json-response'
            )
        );

        $rest = $this->getRestConnectorMock($restMocks);
        $api = new API($this->apiConfig, $rest, new Model\Mapper\MapperFactory());
        $result = $api->isConnected();

        $this->assertFalse($result);
    }

    protected function getMapperMock($type, $mode, array $input, $output)
    {
        $mapper = m::mock("\Keyteq\Keymedia\Model\Mapper\\{$type}Mapper")
            ->shouldReceive("map{$mode}")
            ->once()
            ->withArgs($input)
            ->andReturn($output);
        return $mapper->getMock();
    }

    protected function getMapperFactoryMock(array $mocks)
    {
        $factory = m::mock('\Keyteq\Keymedia\Model\Mapper\MapperFactory')
            ->makePartial();

        foreach ($mocks as $method => $output) {
            $factory->shouldReceive($method)
                ->once()
                ->andReturn($output);
        }

        return $factory;
    }

    protected function getRestConnectorMock($mocks)
    {
        $rest = m::mock('\Keyteq\Keymedia\API\RestConnector')
            ->shouldIgnoreMissing();

        foreach ($mocks as $method => $options) {
            $rest->shouldReceive($method)
                ->withArgs($options['args'])
                ->times($options['count'])
                ->andReturn($options['return']);
        }

        return $rest;
    }
}
