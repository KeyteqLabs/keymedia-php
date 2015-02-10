keymedia-php
============

KeyMedia PHP API wrapper

[![Build Status](https://travis-ci.org/KeyteqLabs/keymedia-php.png?branch=master)](https://travis-ci.org/KeyteqLabs/keymedia-php)
[![Latest Stable Version](https://poser.pugx.org/keyteqlabs/keymedia/v/stable.svg)](https://packagist.org/packages/keyteqlabs/keymedia)

## Basic usage example

`composer require keyteqlabs/keymedia`

### Initialize the client
```php
$client = new Keyteq\Keymedia\KeymediaClient('username', 'keymediaURL', 'apiKey');
//In case you don't have the API key yet
$client = new Keyteq\Keymedia\KeymediaClient('username', 'keymediaURL');
$client->getToken('password');
```

### Working with albums
```php
$albums = $client->listAlbums();

// List album content
$mediaArray = $client->getAlbum('albumName');

// Search within album
$mediaArray = $client->getAlbum('albumName', 'searchTerm');
```

### Search by media / album names
```php
$mediaArray = $client->findMedia('searchTerm');
```

### Get a single media object by ID
```php
$media = $client->getMedia('id');
```

### Accessing media information
```php
$type = $media->getType();
$isImage = $media->isImage();
$url = $media->getUrl();
$thumbnailUrl = $media->getThumbnailUrl($width, $height);
```
