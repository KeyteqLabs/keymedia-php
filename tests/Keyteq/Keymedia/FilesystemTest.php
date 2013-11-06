<?php

namespace Keyteq\Keymedia;

use org\bovigo\vfs\vfsStream;

abstract class FilesystemTest extends BaseTest
{

    /**
     *
     * @var \org\bovigo\vfs\vfsStreamDirectory
     */
    protected $fsRoot;

    public function setUp()
    {
        parent::setUp();
        $this->fsRoot = vfsStream::setup('root', null, array('dir' => array()));
    }
}
