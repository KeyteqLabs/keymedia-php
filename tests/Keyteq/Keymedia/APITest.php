<?php

namespace Keyteq\Keymedia;

use \Mockery as m;

class APITest extends \PHPUnit_Framework_TestCase
{

    protected $apiKey;
    protected $apiHost;
    protected $apiUser;
    protected $api;

    public function setUp()
    {
        parent::setUp();
        $this->apiKey = 'test_api_key';
        $this->apiHost = 'm.keymedia.dev';
        $this->apiUser = 'admin@keyteq.no';
        $this->api = new API($this->apiUser, $this->apiKey, $this->apiHost);
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }

    public function testConstructSetsApiUser()
    {
        $user = 'some_user';
        $api = new API($user, 'key', 'host');
        $this->assertEquals($user, $api->getApiUser());
    }

    public function testConstructSetsApiKey()
    {
        $key = 'some_key';
        $api = new API('some_user', $key, 'some_host');
        $this->assertEquals($key, $api->getApiKey());
    }

    public function testConstructSetsApiHost()
    {
        $host = 'some_host';
        $api = new API('some_user', 'some_key', $host);
        $this->assertEquals($host, $api->getApiHost());
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Missing argument 1
     */
    public function testConstructRequiresArgument()
    {
        new API();
    }

    public function testListMedia()
    {
        $this->markTestIncomplete('Pending curl wrapper extraction');
        $key = '$1$8L39rZTl$5Q2RFjNAlpwz7SlEVbQl4.';
        $api = new API($this->apiUser, $key, $this->apiHost);
        $expected = '';
        $actual = $api->listMedia();
        $this->assertEquals($expected, $actual);
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

    public function testGetMedia()
    {
        $this->markTestIncomplete('Pending curl wrapper extraction');
        $id = '5252c9f895463493038b456a';
        $key = '$1$8L39rZTl$5Q2RFjNAlpwz7SlEVbQl4.';
        $api = new API($this->apiUser, $key, $this->apiHost);
        $expected = (object) '';
        $actual = $api->getMedia($id);

        $this->assertEquals($expected, $actual);
    }
}
