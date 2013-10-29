<?php

namespace Keyteq\Keymedia;

use \Mockery as m;

abstract class BaseTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
