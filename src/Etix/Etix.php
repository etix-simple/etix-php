<?php

namespace Etix;
use Monolog\Logger;

/**
 * PHP SDK for the Etix API
 */
class Etix
{
    /**
     * @var string
     */
    protected $baseUri = 'https://api.etix.com/v1/';

    /**
     * @var Monolog\Logger
     */
    protected $logger;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $apiKeyType;

    /**
     * @var string
     */
    protected $authToken;

    /**
     * @param Monolog\Logger $logger Optional logger instance
     */
    public function __construct(Logger $logger = null) {
        if (isset($logger)) {
            $this->logger = $logger;
        }
    }

    /**
     * @param string $messageFormat
     */
    protected function createGuzzleLoggingMiddleware($messageFormat)
    {
        return \GuzzleHttp\Middleware::log(
            $this->logger,
            new \GuzzleHttp\MessageFormatter($messageFormat)
        );
    }

    /**
     * @param array $messageFormats
     */
    protected function createLoggingHandlerStack($messageFormats)
    {
        $stack = \GuzzleHttp\HandlerStack::create();
        foreach($messageFormats as $messageFormat) {
            // We'll use unshift instead of push, to add the middleware to the bottom of the stack, not the top
            $stack->unshift(
                $this->createGuzzleLoggingMiddleware($messageFormat)
            );
        }
        return $stack;
    }

    /**
     * @param string $baseUri
     * @return \Etix\Etix
     */
    public function setBaseUri($baseUri)
    {
        $this->baseUri = $baseUri;
        return $this;
    }

    /**
     *  @return \GuzzleHttp\Client
     */
    public function getClient()
    {
        if (!isset($this->client)) {
            $opt = [
                'base_uri'  =>  $this->baseUri,
                'verify'    =>  false,
            ];
            if (isset($this->logger)) {
                $opt['handler'] = $this->createLoggingHandlerStack([
                    '{method} {uri} HTTP/{version} {req_body}',
                    'RESPONSE: {code} - {res_body}',
                ]);
            }
            $this->client = new \GuzzleHttp\Client($opt);
        }
        return $this->client;
    }

    /**
     * @param string $username
     * @param string $password
     * @return \Etix\Etix
     */
    public function login($username, $password)
    {
        $client = $this->getClient();
        $res = $client->post('login', [
            'headers'   =>  [
                'username'  =>  $username,
                'password'  =>  $password,
            ]
        ]);
        if ($res->getStatusCode() === 200) {
            $resObj = json_decode($res->getBody(), false);
            $this->setAuthToken($resObj->token);
        }
        return $this;
    }

    /**
     * @return \Etix\Etix
     */
    public function logout()
    {
        $res = $this->getClient()->post('logout', [
            'headers'   =>  [
                'authToken'     =>  $this->getAuthToken(),
            ]
        ]);
        if ($res->getStatusCode() === 200) {
            $this->authToken = null;
        }
        return $this;

    }

    /**
     * @param string $apiKey Validation API Key
     * @return \Etix\Etix
     */
    public function loginWithValidationApiKey($apiKey)
    {
        return $this->loginWithApiKey($apiKey, ApiKeyType::validation);
    }

    /**
     * @param string $apiKey API Key
     * @param string $apiKeyType API Key Type is optional
     * @return \Etix\Etix
     */
    public function loginWithApiKey($apiKey, $apiKeyType = ApiKeyType::eventBooking)
    {
        $res = $this->getClient()->post('apikey/login', [
            'headers'   =>  [
                'apiKey'        =>  $apiKey,
                'apiKeyType'    =>  $apiKeyType,
            ]
        ]);
        if ($res->getStatusCode() === 200) {
            $resObj = json_decode($res->getBody(), false);
            $this->setAuthToken($resObj->token);
        }
        return $this;
    }

    /**
     * @param string $authToken
     * @return \Etix\Etix
     */
    public function setAuthToken($authToken)
    {
        $this->authToken = $authToken;
        return $this;
    }

    /**
     * @return string
     */
    public function getAuthToken()
    {
        return $this->authToken;
    }

    /**
     * @return string
     */
    public function getValidationApiKey()
    {
        return getApiKey(ApiKeyType::validation);
    }

    /**
     *
     * @param string $apiKeyType API Key Type is optional
     * @return string
     */
    public function getApiKey($apiKeyType = ApiKeyType::eventBooking)
    {
        $resObj = $this->get('apikey/exchange', [
            'headers'   =>  [
                'authToken'     =>  $this->getAuthToken(),
                'apiKeyType'    =>  $apiKeyType,
            ]
        ]);
        return $resObj->apiKey;
    }

    /**
     * @param array $opt Options:
     * [
     *       'pageNum'   =>  0,
     *       'pageSize'  =>  10,
     *       'count'     =>  false,
     *       'sortBy'    =>  'name',
     *       'sortDir'   =>  'ASC'
     * ]
     * @return array
     */
    public function getVenues($opt = [])
    {
        // Default Options
        $opt = (object) array_merge([
            'pageNum'   =>  0,
            'pageSize'  =>  10,
            'count'     =>  false,
            'sortBy'    =>  'name',
            'sortDir'   =>  'ASC',
        ], (array) $opt);

        return $this->get('venues', [
            'query' =>  [
                'pageNum'       =>  intval($opt->pageNum),
                'pageSize'      =>  intval($opt->pageSize),
                'count'         =>  $opt->count === true || $opt->count === 'yes' ? 'yes' : 'no',
                'sortBy'        =>  $opt->sortBy,
                'sortDir'       =>  $opt->sortDir === 'ASC' ? 'ASC' : 'DESC',
            ],
        ]);
    }
    /**
     * @return int
     */
    public function getVenueCount()
    {
        return $this->getVenues(['count'=>true])->count;
    }

    /**
     * @param int $venueId
     * @return object
     */
    public function getVenue($venueId)
    {
        return $this->get('venues/'.intval($venueId));
    }

    /**
     * @param array $opt Options
     * [
     *       'venueId'       =>  9999,
     *       'fields'        =>  null,
     *       'purpose'       =>  null,
     *       'pageNum'       =>  0,
     *       'pageSize'      =>  10,
     *       'count'         =>  false,
     *       'sortBy'        =>  'name',
     *       'sortDir'       =>  'DESC',
     *       'showPrivate'   =>  false
     * ]
     * @return array
     */
    public function getEvents($opt = [])
    {
        // Default Options
        $opt = (object) array_merge([
            'venueId'       =>  0,
            'fields'        =>  null, //'id,name,beginTimestamp,beginTimestamp8601',
            'purpose'       =>  null,
            'pageNum'       =>  0,
            'pageSize'      =>  10,
            'count'         =>  false,
            'sortBy'        =>  'name',
            'sortDir'       =>  'DESC',
            'showPrivate'   =>  false,

        ], (array) $opt);

        return $this->get('events', [
            'query' =>  [
                'venueId'       =>  intval($opt->venueId),
                'fields'        =>  $opt->fields,
                'purpose'       =>  $opt->purpose,
                'pageNum'       =>  intval($opt->pageNum),
                'pageSize'      =>  intval($opt->pageSize),
                'count'         =>  $opt->count === true || $opt->count === 'yes' ? 'yes' : 'no',
                'sortBy'        =>  $opt->sortBy,
                'sortDir'       =>  $opt->sortDir === 'ASC' ? 'ASC' : 'DESC',
                'showPrivate'   =>  $opt->showPrivate === 'true' ? 'true' : 'false',
            ],
        ]);
    }
    /**
     * @param array $opt Options
     * [
     *       'venueId'       =>  9999,
     *       'purpose'       =>  null,
     *       'showPrivate'   =>  false
     * ]
     * @return int
     */
    public function getEventCount($opt = [])
    {
        // Default Options
        $opt = (object) array_merge([
            'venueId'       =>  0,
            'purpose'       =>  null,
            'showPrivate'   =>  false,

        ], (array) $opt, ['count'=>true]);
        return $this->getEvents($opt)->count;
    }

    /**
     * @param int $eventId
     * @return object
     */
    public function getEvent($eventId)
    {
        return $this->get('events/'.intval($eventId));
    }

    /**
     * @param int $eventId
     * @return object
     */
    public function getEventDataSnapshot($eventId)
    {
        return $this->get('events/'.intval($eventId).'/data/snapshot');
    }

    /**
     * @param int $eventId
     * @param array $opt Options
     * @return object With `(array) tickets` and `(string) lastTimeISO8601`
     */
    public function getEventTicketsDetails($eventId, $opt = [])
    {
        // Default Options
        $opt = (object) array_merge([
            'lastTimeISO8601'   =>  null,
            'ticketId'          =>  null,
        ], (array) $opt);
        return $this->get('events/'.intval($eventId).'/tickets/details', [
            'query' =>  [
                'lastTimeISO8601'   =>  $opt->lastTimeISO8601,
                'ticketId'          =>  intval($opt->ticketId),
            ]
        ]);
    }

    /**
     * @param array $opt Options
     * [
     *       'pageNum'       =>  0,
     *       'pageSize'      =>  10,
     *       'count'         =>  false
     * ]
     * @return array
     */
    public function getArtists($opt = [])
    {
        // Default Options
        $opt = (object) array_merge([
            'pageNum'       =>  0,
            'pageSize'      =>  10,
            'count'         =>  false,
        ], (array) $opt);

        return $this->get('artists', [
            'query' =>  [
                'pageNum'       =>  intval($opt->pageNum),
                'pageSize'      =>  intval($opt->pageSize),
                'count'         =>  $opt->count === true || $opt->count === 'yes' ? 'yes' : 'no',
            ],
        ]);
    }
    /**
     * @return int
     */
    public function getArtistCount()
    {
        return $this->getArtists(['count'=>true])->count;
    }
    /**
     * @param int $artistId
     * @return object
     */
    public function getArtist($artistId)
    {
        return $this->get('artists/'.intval($artistId));
    }


    /**
     * @param array $opt Options
     * [
     *       'pageNum'       =>  0,
     *       'pageSize'      =>  10,
     *       'count'         =>  false
     * ]
     * @return array
     */
    public function getCategories($opt = [])
    {
        // Default Options
        $opt = (object) array_merge([
            'pageNum'       =>  0,
            'pageSize'      =>  10,
            'count'         =>  false,
        ], (array) $opt);

        return $this->get('categories', [
            'query' =>  [
                'pageNum'       =>  intval($opt->pageNum),
                'pageSize'      =>  intval($opt->pageSize),
                'count'         =>  $opt->count === true || $opt->count === 'yes' ? 'yes' : 'no',
            ],
        ]);
    }
    /**
     * @return int
     */
    public function getCategoryCount()
    {
        return $this->getCategories(['count'=>true])->count;
    }
    /**
     * @param int $categoryId
     * @return object
     */
    public function getCategory($categoryId)
    {
        return $this->get('categories/'.intval($categoryId));
    }

    /**
     * @param string $serial Ticket serial or barcode
     * @param boolean $out OPTIONAL
     * @param string $timestamp OPTIONAL iso8601 formatted time
     * @return object
     */
    public function validateTicket($serial, $out = false, $timestamp = null)
    {
        $timestamp = !isset($timestamp) ? date('c') : $timestamp;
        $timestamp = date('c', strtotime($timestamp));

        return $this->put('validate', [
            'body'  =>  [
                'serial'    =>  $serial,
                'mode'      =>  $out === true ? 'OUT' : 'IN',
                'timestamp' =>  $timestamp,
            ]
        ]);
    }

    /**
     * @param int $eventId
     * @param int $timeSliceLength
     * @param string $lastTimestamp
     * @return object
     */
    public function getValidationStatistics($eventId, $timeSliceLength = 60, $lastTimestamp = null)
    {
        $lastTimestamp = !isset($lastTimestamp) ? date('c') : $lastTimestamp;
        $lastTimestamp = date('c', strtotime($lastTimestamp));

        return $this->get('validate/statistics', [
            'query' => [
                'performanceId'     =>  intval($eventId),
                'timeSliceLength'   =>  intval($timeSliceLength),
                'lastTimestamp'     =>  $lastTimestamp,
            ]
        ]);
    }

    /**
     * @return DateTime
     */
    public function getTimestamp()
    {
        return new \DateTime($this->get('timestamp')->timestamp);
    }

    /**
     * $param string $route
     * @param array $opt Options
     * [
     *       'headers'       =>  [],
     *       'query'         =>  []
     * ]
     */
    public function get($route, $opt = [])
    {
        return $this->request('GET', $route, $opt);
    }
    /**
     * $param string $route
     * @param array $opt Options
     * [
     *       'headers'       =>  [],
     *       'body'          =>  []
     * ]
     */
    public function put($route, $opt = [])
    {
        return $this->request('PUT', $route, $opt);
    }

    public function request($method, $route, $opt)
    {
        // Defaults
        $opt = array_merge([
            'headers'   =>  [
                'authToken'     =>  $this->getAuthToken(),
            ],
        ], (array) $opt);

        $res = $this->getClient()->request($method, $route, $opt);
        if ($res->getStatusCode() === 200) {
            $resObj = json_decode($res->getBody());
            return $resObj;
        }
    }


    /**
     * Just a test function
     */
    public static function HelloWorld()
    {
        return 'Hello World!';
    }
}
