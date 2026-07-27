<?php

use AlexRoden\LibraryApiPhp\Bus\Events\CreateAuthorEvent;
use AlexRoden\LibraryApiPhp\Bus\Events\CreateCouncilEvent;
use AlexRoden\LibraryApiPhp\Bus\Events\CreateLibraryEvent;
use AlexRoden\LibraryApiPhp\Bus\Events\CreateUserEvent;
use AlexRoden\LibraryApiPhp\Bus\Events\DeleteCouncilEvent;
use AlexRoden\LibraryApiPhp\Bus\Events\DeleteLibraryEvent;
use AlexRoden\LibraryApiPhp\Bus\Events\DeleteUserEvent;
use AlexRoden\LibraryApiPhp\Bus\Events\UpdateAuthorEvent;
use AlexRoden\LibraryApiPhp\Bus\Events\UpdateCouncilEvent;
use AlexRoden\LibraryApiPhp\Bus\Events\UpdateLibraryEvent;
use AlexRoden\LibraryApiPhp\Bus\Events\UpdateUserEvent;
use AlexRoden\LibraryApiPhp\Bus\Listeners\LeavingEmailListener;
use AlexRoden\LibraryApiPhp\Bus\Listeners\SendWelcomeEmailListener;

/*
|--------------------------------------------------------------------------
| This is how the events are connected to their listeners.
|--------------------------------------------------------------------------
*/
return [
    CreateAuthorEvent::class => [],
    CreateCouncilEvent::class => [],
    CreateLibraryEvent::class => [],
    CreateUserEvent::class => [
        SendWelcomeEmailListener::class,
    ],
    DeleteCouncilEvent::class => [],
    DeleteLibraryEvent::class => [],
    DeleteUserEvent::class => [
        LeavingEmailListener::class,
    ],
    UpdateAuthorEvent::class => [],
    UpdateCouncilEvent::class => [],
    UpdateLibraryEvent::class => [],
    UpdateUserEvent::class => [],
];