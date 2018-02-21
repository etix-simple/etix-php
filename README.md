# Etix PHP SDK

[![Build Status](https://travis-ci.org/etix-simple/etix-php.svg?branch=2.0)](https://travis-ci.org/etix-simple/etix-php)
[![Latest Stable Version](https://poser.pugx.org/etix-simple/etix-php/v/stable)](https://packagist.org/packages/etix-simple/etix-php)
[![Total Downloads](https://poser.pugx.org/etix-simple/etix-php/downloads)](https://packagist.org/packages/etix-simple/etix-php)
[![License](https://poser.pugx.org/etix-simple/etix-php/license)](https://packagist.org/packages/etix-simple/etix-php)

This library uses the REST API, documentation is found at [api.etix.com](https://api.etix.com/).

## Install

    composer require etix-simple/etix-php

## Authenticate

````php
<?php
    
use Etix\Etix;
    
$etix = new Etix();

// $apiKey = $etix->login($username, $password)->getApiKey();
$etix->loginWithApiKey($apiKey);
    
````

## Venues

````php
<?php

$venues = $etix->getVenues(['pageNumber' => 1, 'pageSize' => 20]);
$venue = $etix->getVenue($venueId);
````

## Events

````php
<?php

$events = $etix->getEvents(['venueId' => $venueId, 'pageNumber' => 1, 'pageSize' => 20]);
$event = $etix->getEvents($eventId);
$eventDataSnapshot = $etix->getEventDataSnapshot($eventId);
$eventTicketsDetails = $etix->getEventTicketsDetails($eventId, [
    'lastTimeISO8601'   =>  '2017-10-01T05:48:03Z',
]);

````

## Artists

````php
<?php

$artists = $etix->getArtists(['pageNumber' => 1, 'pageSize' => 20]);
$artist = $etix->getArtist($artistId);
````

## Categories

````php
<?php

$categories = $etix->getCategories(['pageNumber' => 1, 'pageSize' => 20]);
$category = $etix->getCategory($categoryId);
````

## Validate

````php
<?php

$result = $etix->validateTicket($serial);
$stats = $etix->getValidationStatistics($eventId);

````

## Other

````php
<?php

$dateTime = $etix->getTimestamp();
$me = $etix->getMe();

````

## Development

    # TESTS
    phpunit --bootstrap vendor/autoload.php tests/EtixTest.php

    # START LOCAL DOCKER
    docker-compose build && docker-compose up --force-recreate

    # Documentation
    phpDocumentor -d ./src -t docs
