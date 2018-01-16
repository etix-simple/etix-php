<?php

namespace Etix;
use MyCLabs\Enum\Enum;

/**
 * ApiKeyType enum
 */
class ApiKeyType extends Enum
{
    /**
     * @var string
     */
    const validation = ETIX_VALIDATION_KEY_TYPE;
    /**
     * @var string
     */
    const eventBooking = ETIX_EVENTBOOKING_KEY_TYPE;
}
