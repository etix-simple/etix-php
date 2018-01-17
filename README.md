# Etix PHP SDK

[![Build Status](https://travis-ci.org/etix-simple/etix-php.svg?branch=master)](https://travis-ci.org/etix-simple/etix-php)
[![Latest Stable Version](https://poser.pugx.org/etix-simple/etix-php/v/stable)](https://packagist.org/packages/etix-simple/etix-php)
[![Total Downloads](https://poser.pugx.org/etix-simple/etix-php/downloads)](https://packagist.org/packages/etix-simple/etix-php)
[![License](https://poser.pugx.org/etix-simple/etix-php/license)](https://packagist.org/packages/etix-simple/etix-php)

This library uses the REST API, documentation is found at [api.etix.com](https://api.etix.com/docs/).

## Install

    composer require etix-simple/etix-php

## Authenticate

````php
<?php
    
use Etix\Etix;
    
$etix = new Etix();

// Ask your Etix service representative for the key type code
define('ETIX_EVENTBOOKING_KEY_TYPE', 'KTEXAMPLE8AC7B18D1DAEEE4DDDDDDD5E0732F40E3EEA1533333333017C6DE32A6');

// $apiKey = $etix->login($username, $password)->getApiKey();
$etix->loginWithApiKey($apiKey);
    
````

## Venues

````php
<?php

$venueCount = $etix->getVenueCount();
$venues = $etix->getVenues(['pageNum' => 1, 'pageSize' => 20]);
$venue = $etix->getVenue($venueId);
````

## Events

````php
<?php

$eventCount = $etix->getEventCount(['venueId' => $venueId]);
$events = $etix->getEvents(['venueId' => $venueId, 'pageNum' => 1, 'pageSize' => 20]);
$event = $etix->getEvents($eventId);
$eventDataSnapshot = $etix->getEventDataSnapshot($eventId);
$eventTicketsDetails = $etix->getEventTicketsDetails($eventId, [
    'lastTimeISO8601'   =>  '2017-10-01T05:48:03Z',
]);

````

## Artists

````php
<?php

$artistCount = $etix->getArtistCount();
$artists = $etix->getArtists(['pageNum' => 1, 'pageSize' => 20]);
$artist = $etix->getArtist($artistId);
````

## Categories

````php
<?php

$categoryCount = $etix->getCategoryCount();
$categories = $etix->getCategories(['pageNum' => 1, 'pageSize' => 20]);
$category = $etix->getCategory($categoryId);
````

## Validate

````php
<?php

$result = $etix->validateTicket($serial);
$stats = $etix->getValidationStatistics($eventId);

````

## Timestamp

````php
<?php

$dateTime = $etix->getTimestamp();

````

## Development

    # TESTS
    phpunit --bootstrap vendor/autoload.php tests/EtixTest.php

    # START LOCAL DOCKER
    docker-compose build && docker-compose up --force-recreate

    # Documentation
    phpDocumentor -d ./src -t docs
