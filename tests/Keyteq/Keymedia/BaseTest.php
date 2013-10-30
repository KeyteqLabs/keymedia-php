<?php

namespace Keyteq\Keymedia;

use Keyteq\Keymedia\API\Configuration;
use \Mockery as m;

abstract class BaseTest extends \PHPUnit_Framework_TestCase
{

    const API_URL = 'http://test.host';
    const API_USER = 'test_user';
    const API_KEY = 'test_key';

    protected $apiConfig;

    public function setUp()
    {
        parent::setUp();
        $this->apiConfig = $this->getApiConfig();
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }

    protected function getApiConfig()
    {
        return new Configuration(
            array(
                'apiUrl' => static::API_URL,
                'apiUser' => static::API_USER,
                'apiKey' => static::API_KEY
            )
        );
    }
}
