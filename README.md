keymedia-php
============

KeyMedia PHP API wrapper

## Basic usage example

### Initialize the client
```php
$client = new KeymediaClient('username', 'apiKey', 'keymediaHost');
```
### List albums
```php
$albums = $client->listAlbums();
```

### Get all media contained in an album
```php
$mediaArray = $client->getAlbum('albumName');
```
###Search by name within an album
```php
$mediaArray = $client->getAlbum('albumName', 'searchTerm');
```

### Combined search by media / album names
```php
$mediaArray = $client->findMedia('searchTerm');
```

### Get a single media object by ID
```php
$media = $client->getMedia('id');
```

### Get media file type
```php
$type = $media->getType();
```

### Check if media is an image
```php
$isImage = $media->isImage();
```

### Get media's public URL
```php
$url = $media->getUrl();
```

### Get the media's thumbnail URL
```php
$thumbnailUrl = $media->getThumbnailUrl($width, $height);
```