<?php

namespace Keyteq\Keymedia\Model;

use Keyteq\Keymedia\BaseTest;

class MediaTest extends BaseTest
{
    public function testConstruct()
    {
        $validJson = '{"media":{"_id":"5252c9f895463493038b456a","attributes":[],"created":1381157368,"file":{"uploaded":true,"size":2408,"type":"image\/png","ending":".png","host":"s3.amazonaws.com","url":"http:\/\/keymedia-customers.s3.amazonaws.com\/\/m-keymedia-dev\/originals\/5252c9f895463493038b456a.png?AWSAccessKeyId=AKIAIMX7J4TCQPKNTGEA&Expires=1382520939&Signature=PQanKVfXs0Gh57ESQAyg8Cjnxpw%3D","bucket":"keymedia-customers","ratio":1,"width":32,"height":32},"modified":1382348391,"name":"brasero.png","slug":"brasero","tags":["album1"],"user":"5252b93195463493038b4568","version":2,"shareUrl":"\/media\/brasero\/brasero.png","status":"ready","host":"m.keymedia.dev","scalesTo":{"quality":75,"ending":"png"}}}';
        $data = json_decode($validJson, true);
        $media = new Media($data['media']);

        $this->assertInstanceOf('Keyteq\Keymedia\Model\Media', $media);
    }

    public function testGetType()
    {
        $expected = 'image/png';
        $data = array('file' => array('type' => $expected));
        $media = new Media($data);
        $actual = $media->getType();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider isImageProvider
     */
    public function testIsImage($data, $expected)
    {
        $media = new Media($data);
        $actual = $media->isImage();
        $this->assertEquals($expected, $actual);
    }

    public function testGetImageThumbnailUrl()
    {
        $width = $height = 100;
        $host = 'keymedia.dev';

        $fixtures = array(
            array('_id' => 'png', 'host' => $host,
                'file' => array('type' => 'image/png'),
                'expected' => "http://{$host}/100x100/png.png"
            ),
            array('_id' => 'jpg', 'host' => $host,
                'file' => array('type' => 'image/jpeg'),
                'expected' => "http://{$host}/100x100/jpg.jpeg"
            ),
            array('_id' => 'svg', 'host' => $host,
                'file' => array('type' => 'image/svg+xml', 'url' => 'relayed-url'),
                'expected' => "relayed-url"
            ),
        );

        foreach ($fixtures as $fixture) {
            $expected = $fixture['expected'];
            unset($fixture['expected']);
            $media = new Media($fixture);
            $this->assertEquals($expected, $media->getThumbnailUrl($width, $height));
        }
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage require dimensions
     */
    public function testGetImageThumbnailUrlThrowsWhenMissingDimensions()
    {
        $type = 'image/png';
        $id = 'media_id';
        $width = 100;
        $host = 'keymedia.dev';

        $data = array('_id' => $id, 'host' => $host, 'file' => array('type' => $type));
        $media = new Media($data);

        $media->getThumbnailUrl($width);
    }

    public function testGetTypeThumbnaulUrl()
    {
        $ending = '.mov';
        $host = 'keymedia.dev';

        $data = array(
            'host' => $host,
            'file' => array(
                'type' => 'video/quicktime',
                'ending' => $ending
            )
        );
        $media = new Media($data);

        $expected = "http://{$host}/images/filetypes/movie.png";
        $actual = $media->getThumbnailUrl();

        $this->assertEquals($expected, $actual);
    }

    public function testGetTypeThumbnaulUrlFallback()
    {
        $ending = '.unknown';
        $host = 'keymedia.dev';
        $data = array(
            'host' => $host,
            'file' => array(
                'type' => 'unknown/type',
                'ending' => $ending
            )
        );
        $media = new Media($data);

        $expected = "http://{$host}/images/filetypes/fileicon_bg.png";
        $actual = $media->getThumbnailUrl();

        $this->assertEquals($expected, $actual);
    }

    public function isImageProvider()
    {
        return array(
            array(array('file' => array('type' => 'image/png')), true),
            array(array('file' => array('type' => 'other/type')), false),
        );
    }
}
