<?php

use AlexRoden\LibraryApiPhp\Bus\Events\CreateCouncilEvent;
use AlexRoden\LibraryApiPhp\Bus\Events\CreateUserEvent;
use AlexRoden\LibraryApiPhp\Bus\Events\DeleteUserEvent;
use AlexRoden\LibraryApiPhp\Bus\Events\UpdateCouncilEvent;
use AlexRoden\LibraryApiPhp\Bus\Events\UpdateUserEvent;
use AlexRoden\LibraryApiPhp\Bus\Listeners\LeavingEmailListener;
use AlexRoden\LibraryApiPhp\Bus\Listeners\SendWelcomeEmailListener;

/*
|--------------------------------------------------------------------------
| This is how the events are connected to their listeners.
|--------------------------------------------------------------------------
*/
return [
    CreateCouncilEvent::class => [],
    CreateUserEvent::class => [
        SendWelcomeEmailListener::class,
    ],
    DeleteUserEvent::class => [
        LeavingEmailListener::class,
    ],
    UpdateCouncilEvent::class => [],
    UpdateUserEvent::class => [],
];