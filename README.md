keymedia-php
============

KeyMedia PHP API wrapper

[![Build Status](https://travis-ci.org/KeyteqLabs/keymedia-php.png?branch=master)](https://travis-ci.org/KeyteqLabs/keymedia-php)
[![Latest Version](https://poser.pugx.org/leaphly/cart-bundle/v/unstable.svg)](https://packagist.org/packages/keyteqlabs/keymedia)

## Basic usage example

`composer require keyteqlabs/keymedia`

### Initialize the client
```php
$client = new KeymediaClient('username', 'keymediaURL', 'apiKey');
```
### In case you don't have the API key yet
```php
$client = new KeymediaClient('username', 'keymediaURL');
$client->getToken('password');
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
