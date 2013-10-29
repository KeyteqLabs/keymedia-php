<?php

namespace Keyteq\Keymedia\Model\Mapper;

class MediaMapperTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $mapper = new MediaMapper();
        $this->assertInstanceOf('\Keyteq\Keymedia\Model\Mapper\MediaMapper', $mapper);
    }

    /**
     * @dataProvider validItemProvider
     */
    public function testMapItem($json)
    {
        $mapper = new MediaMapper();
        $item = $mapper->mapItem($json);

        $this->assertInstanceOf('\Keyteq\Keymedia\Model\Media', $item);
    }

    /**
     * @dataProvider validCollectionProvider
     */
    public function testMapCollection($json, $count)
    {
        $mapper = new MediaMapper();
        $collection = $mapper->mapCollection($json);

        $this->assertCount($count, $collection);
    }

    public function validItemProvider()
    {
        return array(
            array(
                '{"media":{"_id":"5252c9f895463493038b456a","attributes":[],"created":1381157368,"file":{"uploaded":true,"size":2408,"type":"image\/png","ending":".png","host":"s3.amazonaws.com","url":"http:\/\/keymedia-customers.s3.amazonaws.com\/\/m-keymedia-dev\/originals\/5252c9f895463493038b456a.png?AWSAccessKeyId=AKIAIMX7J4TCQPKNTGEA&Expires=1383127186&Signature=1%2FKNoEPzNsxcSekP01Z%2F%2Bjf8m%2BA%3D","bucket":"keymedia-customers","ratio":1,"width":32,"height":32},"modified":1382348391,"name":"brasero.png","slug":"brasero","tags":["album1"],"user":"5252b93195463493038b4568","version":2,"shareUrl":"\/media\/brasero\/brasero.png","status":"ready","host":"m.keymedia.dev","scalesTo":{"quality":75,"ending":"png"}}}',
            )
        );
    }

    public function validCollectionProvider()
    {
        return array(
            array(
                '{"media":[{"_id":"5266383895463445038b4568","attributes":[],"created":1382430776,"file":{"uploaded":true,"size":841006,"type":"video\/quicktime","ending":".MOV","host":"s3.amazonaws.com","url":"http:\/\/keymedia-customers.s3.amazonaws.com\/\/m-keymedia-dev\/originals\/5266383895463445038b4568.qt?AWSAccessKeyId=AKIAIMX7J4TCQPKNTGEA&Expires=1383126939&Signature=M6YWXuIAxAS3oP8eoER3WjUl29M%3D","bucket":"keymedia-customers"},"modified":1382430779,"name":"IMG_4154.MOV","slug":"img_4154","tags":[],"user":"5252b93195463493038b4568","version":2,"shareUrl":"\/media\/img_4154\/img_4154.MOV","status":"ready","host":"m.keymedia.dev","scalesTo":{"quality":70,"ending":"quicktime"}},{"_id":"526637f095463448038b4567","attributes":[],"created":1382430704,"file":{"uploaded":true,"size":2138588,"type":"audio\/x-flac","ending":".flac","host":"s3.amazonaws.com","url":"http:\/\/keymedia-customers.s3.amazonaws.com\/\/m-keymedia-dev\/originals\/526637f095463448038b4567.flac?AWSAccessKeyId=AKIAIMX7J4TCQPKNTGEA&Expires=1383126939&Signature=%2FU9GmJaJ3sueBufLuOrs8Fob%2FvE%3D","bucket":"keymedia-customers"},"modified":1382430709,"name":"03 The Red Wire.flac","slug":"03-the-red-wire","tags":[],"user":"5252b93195463493038b4568","version":2,"shareUrl":"\/media\/03-the-red-wire\/03-the-red-wire.flac","status":"ready","host":"m.keymedia.dev","scalesTo":{"quality":70,"ending":"x-flac"}},{"_id":"526630a995463484058b4567","attributes":[],"created":1382428841,"file":{"uploaded":true,"size":1936,"type":"application\/zip","ending":".zip","host":"s3.amazonaws.com","url":"http:\/\/keymedia-customers.s3.amazonaws.com\/\/m-keymedia-dev\/originals\/526630a995463484058b4567.zip?AWSAccessKeyId=AKIAIMX7J4TCQPKNTGEA&Expires=1383126939&Signature=%2BkK2d8Ro9RD4U53qpG%2FnkrJ7cXo%3D","bucket":"keymedia-customers"},"modified":1382428842,"name":"img.zip","slug":"img","tags":[],"user":"5252b93195463493038b4568","version":2,"shareUrl":"\/media\/img\/img.zip","status":"ready","host":"m.keymedia.dev","scalesTo":{"quality":70,"ending":"zip"}},{"_id":"5252c9f895463493038b456a","attributes":[],"created":1381157368,"file":{"uploaded":true,"size":2408,"type":"image\/png","ending":".png","host":"s3.amazonaws.com","url":"http:\/\/keymedia-customers.s3.amazonaws.com\/\/m-keymedia-dev\/originals\/5252c9f895463493038b456a.png?AWSAccessKeyId=AKIAIMX7J4TCQPKNTGEA&Expires=1383126939&Signature=7Esbe43zPIaxt4oAS%2Bh0AETj%2Bdw%3D","bucket":"keymedia-customers","ratio":1,"width":32,"height":32},"modified":1382348391,"name":"brasero.png","slug":"brasero","tags":["album1"],"user":"5252b93195463493038b4568","version":2,"shareUrl":"\/media\/brasero\/brasero.png","status":"ready","host":"m.keymedia.dev","scalesTo":{"quality":75,"ending":"png"}},{"_id":"5252ba9895463497038b4567","attributes":[],"created":1381153432,"file":{"uploaded":true,"size":1568,"type":"image\/png","ending":".png","host":"s3.amazonaws.com","url":"http:\/\/keymedia-customers.s3.amazonaws.com\/\/m-keymedia-dev\/originals\/5252ba9895463497038b4567.png?AWSAccessKeyId=AKIAIMX7J4TCQPKNTGEA&Expires=1383126939&Signature=41rQwooj7PyyKR7lNuL%2FvfQMoZU%3D","bucket":"keymedia-customers","ratio":1,"width":32,"height":32},"modified":1381153433,"name":"baobab.png","slug":"baobab","tags":[],"user":"5252b93195463493038b4568","version":2,"shareUrl":"\/media\/baobab\/baobab.png","status":"ready","host":"m.keymedia.dev","scalesTo":{"quality":75,"ending":"png"}},{"_id":"5252b9a895463493038b4569","attributes":[],"created":1381153192,"file":{"uploaded":true,"size":1730,"type":"image\/png","ending":".png","host":"s3.amazonaws.com","url":"http:\/\/keymedia-customers.s3.amazonaws.com\/\/m-keymedia-dev\/originals\/5252b9a895463493038b4569.png?AWSAccessKeyId=AKIAIMX7J4TCQPKNTGEA&Expires=1383126939&Signature=WVLz%2Bb%2FLwt1uJVQa6Q7zjFzNgC4%3D","bucket":"keymedia-customers","ratio":1,"width":32,"height":32},"modified":1381153193,"name":"bluetooth.png","slug":"bluetooth","tags":[],"user":"5252b93195463493038b4568","version":2,"shareUrl":"\/media\/bluetooth\/bluetooth.png","status":"ready","host":"m.keymedia.dev","scalesTo":{"quality":75,"ending":"png"}}],"total":6}',
                6
            ),
            array(
                '{"media":[{"_id":"5252c9f895463493038b456a","attributes":[],"created":1381157368,"file":{"uploaded":true,"size":2408,"type":"image\/png","ending":".png","host":"s3.amazonaws.com","url":"http:\/\/keymedia-customers.s3.amazonaws.com\/\/m-keymedia-dev\/originals\/5252c9f895463493038b456a.png?AWSAccessKeyId=AKIAIMX7J4TCQPKNTGEA&Expires=1383127186&Signature=1%2FKNoEPzNsxcSekP01Z%2F%2Bjf8m%2BA%3D","bucket":"keymedia-customers","ratio":1,"width":32,"height":32},"modified":1382348391,"name":"brasero.png","slug":"brasero","tags":["album1"],"user":"5252b93195463493038b4568","version":2,"shareUrl":"\/media\/brasero\/brasero.png","status":"ready","host":"m.keymedia.dev","scalesTo":{"quality":75,"ending":"png"}}]}',
                1
            ),
            array(
                '{"media":[]}',
                0
            )
        );
    }
}
