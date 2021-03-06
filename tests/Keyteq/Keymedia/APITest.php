<?php

namespace Keyteq\Keymedia;

use \Mockery as m;

class APITest extends BaseTest
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

    public function testPostMedia()
    {
        $file = '@filename';
        $name = 'media_name';
        $tags = array('tag');
        $attributes = array('attr1' => 'val1');
        $args = compact('file', 'name', 'tags', 'attributes');

        $response = new \stdClass();
        $expected = new \stdClass();
        $restMocks = array(
            'postResource' => array(
                'args' => array('media', $args),
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
        $file = '@filename';
        $name = 'media_name';
        $tags = array();
        $attributes = array();
        $filteredArgs = compact('file', 'name');

        $response = new \stdClass();
        $expected = new \stdClass();
        $restMocks = array(
            'postResource' => array(
                'args' => array('media', $filteredArgs),
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

    public function testGetTokenWithValidPasswordReturnsToken()
    {
        $username = $this->apiConfig->getApiUser();
        $password = 'password';
        $expected = $this->getApiConfig()->getApiKey();
        $return = array('ok' => true, 'token' => $expected);
        $args = compact('username', 'password');
        $restMocks = array(
            'postResource' => array(
                'args' => array('users/token', $args),
                'count' => 1,
                'return' => json_encode($return)
            )
        );
        $rest = $this->getRestConnectorMock($restMocks);

        $api = new API($this->apiConfig, $rest, new Model\Mapper\MapperFactory());
        $actual = $api->getToken($password);

        $this->assertEquals($expected, $actual);
    }

    public function testGetTokenWithValidPasswordUpdatesConfig()
    {
        $username = $this->apiConfig->getApiUser();
        $password = 'password';
        $expected = 'api_key';
        $return = array('ok' => true, 'token' => $expected);
        $config = m::mock($this->apiConfig)
            ->shouldDeferMissing()
            ->shouldReceive('setApiKey')
            ->once()
            ->with($expected)
            ->getMock();

        $args = compact('username', 'password');
        $restMocks = array(
            'postResource' => array(
                'args' => array('users/token', $args),
                'count' => 1,
                'return' => json_encode($return)
            )
        );
        $rest = $this->getRestConnectorMock($restMocks);

        $api = new API($config, $rest, new Model\Mapper\MapperFactory());
        $api->getToken($password);
    }

    public function testGetTokenWithInvalidPasswordReturnsFalse()
    {
        $username = $this->apiConfig->getApiUser();
        $password = 'invalid_password';
        $apiKey = $this->getApiConfig()->getApiKey();
        $return = array('ok' => false);
        $args = compact('username', 'password');

        $restMocks = array(
            'postResource' => array(
                'args' => array('users/token', $args),
                'count' => 1,
                'return' => json_encode($return)
            )
        );

        $rest = $this->getRestConnectorMock($restMocks);
        $api = new API($this->apiConfig, $rest, new Model\Mapper\MapperFactory());
        $result = $api->getToken($password);

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
