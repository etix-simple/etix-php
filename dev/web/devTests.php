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
    ->setBaseUri(ETIX_TEST_URI3)
    ->setVerifySsl(false)
    // ->login(ETIX_USERNAME, ETIX_PASSWORD)
    ->loginWithApiKey(ETIX_APIKEY1)
;


// Venues
// $venues = $etix->getVenues([
//     'pageNumber'    =>  400,
//     'pageSize'      =>  3,
// ]);
// echo json_encode($venues);
// $venue = $etix->getVenue(6970);
// echo json_encode($venue);


// Events
// $events = $etix->getEvents([
//     'venueId'       =>  12130,
//     // 'purpose'       =>  'snapshot',
//     'showPrivate'   =>  true,
// ]);
// var_dump($events);
// $event = $etix->getEvent(2154345);
// var_dump($event);
// header("Content-Type: application/json");
// echo json_encode($event);
// $eventDataSnapshot = $etix->getEventDataSnapshot(2154345);
// var_dump($eventDataSnapshot);

// $eventTicketsDetails = $etix->getEventTicketsDetails(2154345, [
//     // 'lastTimeISO8601'   =>  '2017-10-01T05:48:03Z',
//     'ticketId'   =>  519527790,
// ]);
// var_dump($eventTicketsDetails);

// Artists
// $artists = $etix->getArtists([
//     'pageNumber'    =>  2,
//     'pageSize'      =>  4,
// ]);
// echo json_encode($artists);
// $artist = $etix->getArtist(20364);
// echo json_encode($artist);

// Categories
// $categories = $etix->getCategories();
// echo json_encode($categories);
// $category = $etix->getCategory(4);
// echo json_encode($category);

// $stats = $etix->getValidationStatistics(2154345);
// var_dump($stats);

// Get Time from Etix
// $time = $etix->getTimestamp();
// echo 'Time: '.$time->format('c');

// Me
// $me = $etix->getMe();
// echo json_encode($me);

// Logout
$etix->logout();
