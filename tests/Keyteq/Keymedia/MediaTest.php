<?php

namespace Keyteq\Keymedia;

class MediaTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $validJson = '{"media":{"_id":"5252c9f895463493038b456a","attributes":[],"created":1381157368,"file":{"uploaded":true,"size":2408,"type":"image\/png","ending":".png","host":"s3.amazonaws.com","url":"http:\/\/keymedia-customers.s3.amazonaws.com\/\/m-keymedia-dev\/originals\/5252c9f895463493038b456a.png?AWSAccessKeyId=AKIAIMX7J4TCQPKNTGEA&Expires=1382520939&Signature=PQanKVfXs0Gh57ESQAyg8Cjnxpw%3D","bucket":"keymedia-customers","ratio":1,"width":32,"height":32},"modified":1382348391,"name":"brasero.png","slug":"brasero","tags":["album1"],"user":"5252b93195463493038b4568","version":2,"shareUrl":"\/media\/brasero\/brasero.png","status":"ready","host":"m.keymedia.dev","scalesTo":{"quality":75,"ending":"png"}}}';
        $media = new Media($validJson);

        $this->assertInstanceOf('Keyteq\Keymedia\Media', $media);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage No JSON
     */
    public function testConstructThrowsOnFalsishInput()
    {
        new Media(null);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Key "media" not found
     */
    public function testConstructThrowsOnNoMedia()
    {
        new Media('{}');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Malformed
     */
    public function testConstructThrowsOnMalformedJson()
    {
        new Media('{');
    }

    public function testGetType()
    {
        $expected = 'image/png';
        $json = '{"media":{"file":{"type":"' . $expected . '"}}}';
        $media = new Media($json);
        $actual = $media->getType();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider isImageProvider
     */
    public function testIsImage($json, $expected)
    {
        $media = new Media($json);
        $actual = $media->isImage();
        $this->assertEquals($expected, $actual);
    }

    public function testGetImageThumbnailUrl()
    {
        $type = 'image/png';
        $id = 'media_id';
        $width = $height = 100;
        $host = 'http://keymedia.dev';

        $json = sprintf('{"media":{"_id":"%s","host":"%s","file":{"type":"%s"}}}', $id, $host, $type);
        $media = new Media($json);

        $expected = "{$host}/{$width}x{$height}/{$id}.png";
        $actual = $media->getThumbnailUrl($width, $height);

        $this->assertEquals($expected, $actual);
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

        $json = sprintf('{"media":{"_id":"%s","host":"%s","file":{"type":"%s"}}}', $id, $host, $type);
        $media = new Media($json);

        $media->getThumbnailUrl($width);
    }

    public function testGetTypeThumbnaulUrl()
    {
        $ending = '.mov';
        $host = 'http://keymedia.dev';

        $json = sprintf('{"media":{"host":"%s","file":{"type":"video/quicktime","ending":"%s"}}}', $host, $ending);
        $media = new Media($json);

        $expected = "{$host}/images/filetypes/movie.png";
        $actual = $media->getThumbnailUrl();

        $this->assertEquals($expected, $actual);
    }

    public function testGetTypeThumbnaulUrlFallback()
    {
        $ending = '.unknown';
        $host = 'http://keymedia.dev';

        $json = sprintf('{"media":{"host":"%s","file":{"type":"unknown/type","ending":"%s"}}}', $host, $ending);
        $media = new Media($json);

        $expected = "{$host}/images/filetypes/fileicon_bg.png";
        $actual = $media->getThumbnailUrl();

        $this->assertEquals($expected, $actual);
    }

    public function isImageProvider()
    {
        return array(
            array('{"media":{"file":{"type":"image/png"}}}', true),
            array('{"media":{"file":{"type":"other/type"}}}', false),
        );
    }
}