<?php

namespace Keyteq\Keymedia\Model\Mapper;

class AlbumMapperTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $mapper = new AlbumMapper();
        $this->assertInstanceOf('\Keyteq\Keymedia\Model\Mapper\AlbumMapper', $mapper);
    }

    /**
     * @dataProvider validCollectionProvider
     */
    public function testMapCollection($json, $count)
    {
        $mapper = new AlbumMapper();
        $collection = $mapper->mapCollection($json);

        $this->assertCount($count, $collection);
    }

    public function validCollectionProvider()
    {
        return array(
            array(
                '{"tags":[{"_id":"5264f66795463489038b4567","tag":"album1","modified":1382348391,"created":1382348391,"count":1,"medias":[{"_id":"5252c9f895463493038b456a","attributes":[],"created":1381157368,"file":{"uploaded":true,"size":2408,"type":"image\/png","ending":".png","host":"s3.amazonaws.com","url":"http:\/\/keymedia-customers.s3.amazonaws.com\/\/m-keymedia-dev\/originals\/5252c9f895463493038b456a.png?AWSAccessKeyId=AKIAIMX7J4TCQPKNTGEA&Expires=1383127484&Signature=EAxTGX4K5l1NXd23InOqk7EE%2Bzs%3D","bucket":"keymedia-customers","ratio":1,"width":32,"height":32},"modified":1382348391,"name":"brasero.png","slug":"brasero","tags":["album1"],"user":"5252b93195463493038b4568","version":2,"shareUrl":"\/media\/brasero\/brasero.png","status":"ready","host":"m.keymedia.dev","scalesTo":{"quality":75,"ending":"png"}}]}],"total":1}',
                1
            ),
            array(
                '{"tags":[],"total":0}',
                0
            )
        );
    }
}
