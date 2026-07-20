<?php

use AlexRoden\LibraryApiPhp\Bus\Events\CreateUserEvent;
use AlexRoden\LibraryApiPhp\Bus\Listeners\SendWelcomeEmailListener;

/*
|--------------------------------------------------------------------------
| This is how the events are connected to their listeners.
|--------------------------------------------------------------------------
*/
return [
    CreateUserEvent::class => SendWelcomeEmailListener::class,
];