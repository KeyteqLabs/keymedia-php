<?php

namespace Keyteq\Keymedia\Model\Mapper;

class MapperFactory
{
    public function getMediaMapper()
    {
        return new MediaMapper();
    }

    public function getAlbumMapper()
    {
        return new AlbumMapper();
    }
}
