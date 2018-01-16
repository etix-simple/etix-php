<?php

// phpinfo(); exit();

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Etix\Etix;

// Create a log channel
$log = new Logger('requests');
$log->pushHandler(new StreamHandler('requests.log', Logger::DEBUG));

$etix = new Etix($log);
// Get API Key with Username And Password
/*
$apiKey = $etix
    ->login(ETIX_USERNAME, ETIX_PASSWORD)
    ->getApiKey()
;
echo 'API Key: '.$apiKey;
*/
$etix
    ->setBaseUri(ETIX_TEST_URI2)
    ->loginWithApiKey(ETIX_APIKEY1)
;


// Venues
// $venues = $etix->getVenues();
// var_dump($venues);
// $venue = $etix->getVenue(6970);
// var_dump($venue);
// echo 'venue count: '.$etix->getVenueCount();

// Events
// $events = $etix->getEvents([
//     'venueId'       =>  12130,
//     // 'purpose'       =>  'snapshot',
//     'showPrivate'   =>  true,
// ]);
// var_dump($events);
// $event = $etix->getEvent(2154345);
// var_dump($event);
// $eventDataSnapshot = $etix->getEventDataSnapshot(2154345);
// var_dump($eventDataSnapshot);

// $eventTicketsDetails = $etix->getEventTicketsDetails(2154345, [
//     // 'lastTimeISO8601'   =>  '2017-10-01T05:48:03Z',
//     'ticketId'   =>  519527790,
// ]);
// var_dump($eventTicketsDetails);

// Artists
// $artists = $etix->getArtists([
//     // 'pageNum'   =>  1,
//     'count'     =>  true,
//     // 'pageSize'  =>  1,
// ]);
// var_dump($artists);
// $artist = $etix->getArtist(20364);
// var_dump($artist);

// Categories
// $categories = $etix->getCategories();
// var_dump($categories);
// $category = $etix->getCategory(4);
// var_dump($category);

$stats = $etix->getValidationStatistics(2154345);
var_dump($stats);

// Get Time from Etix
// $time = $etix->getTimestamp();
// echo 'Time: '.$time->format('c');

// Logout
$etix->logout();
