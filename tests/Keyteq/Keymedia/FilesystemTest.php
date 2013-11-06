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

    public function addFile($filename, $content = '', $mode = 0644, $at = null)
    {
        if (is_null($at)) {
            $at = $this->fsRoot;
        }

        $file = vfsStream::newFile($filename, $mode)
            ->withContent($content)
            ->at($at);

        return vfsStream::url($file->path());
    }
}
