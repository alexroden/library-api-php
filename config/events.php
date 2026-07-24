<?php

use AlexRoden\LibraryApiPhp\Bus\Events\CreateUserEvent;
use AlexRoden\LibraryApiPhp\Bus\Events\DeleteUserEvent;
use AlexRoden\LibraryApiPhp\Bus\Events\UpdateUserEvent;
use AlexRoden\LibraryApiPhp\Bus\Listeners\LeavingEmailListener;
use AlexRoden\LibraryApiPhp\Bus\Listeners\SendWelcomeEmailListener;

/*
|--------------------------------------------------------------------------
| This is how the events are connected to their listeners.
|--------------------------------------------------------------------------
*/
return [
    CreateUserEvent::class => [
        SendWelcomeEmailListener::class,
    ],
    UpdateUserEvent::class => [],
    DeleteUserEvent::class => [
        LeavingEmailListener::class,
    ],
];