<?php

namespace Keyteq\Keymedia;

use Keyteq\Keymedia\API\Configuration;
use \Mockery as m;

class APITest extends \PHPUnit_Framework_TestCase
{
    protected $apiConfig;
    protected $api;

    public function setUp()
    {
        parent::setUp();
        $options = array(
            'apiKey' => 'test_api_key',
            'apiUrl' => 'http://m.keymedia.dev',
            'apiUser' => 'test_user'
        );
        $this->apiConfig = new Configuration($options);
        $this->rest = m::mock('\Keyteq\Keymedia\API\RestConnector');
        $this->api = new API($this->apiConfig, $this->rest);
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Configuration
     */
    public function testConstructRequiresConfigurationArgument()
    {
        new API();
    }

    public function testListMedia()
    {
        $this->markTestIncomplete('Pending curl wrapper extraction');
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
    }
}
