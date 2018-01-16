# Etix PHP SDK
This library uses the REST API, documentation is found at [api.etix.com](https://api.etix.com/docs/).

## NOT PRODUCTION READY
This library is currently in Alpha, not ready for production use.

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


## Tests

    phpunit --bootstrap vendor/autoload.php tests/EtixTest.php


## Development Docker
    
    docker-compose build && docker-compose up --force-recreate

## Documentation

    phpDocumentor -d ./src -t docs
