<?php

use \Keyteq\Keymedia\API;

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

}
