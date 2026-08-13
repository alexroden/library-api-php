<?php

use AlexRoden\LibraryApiPhp\Bus\Events\CreateAuthorEvent;
use AlexRoden\LibraryApiPhp\Bus\Events\CreateBookEvent;
use AlexRoden\LibraryApiPhp\Bus\Events\CreateCategoryEvent;
use AlexRoden\LibraryApiPhp\Bus\Events\CreateCouncilEvent;
use AlexRoden\LibraryApiPhp\Bus\Events\CreateLibraryEvent;
use AlexRoden\LibraryApiPhp\Bus\Events\CreateStockEvent;
use AlexRoden\LibraryApiPhp\Bus\Events\CreateUserEvent;
use AlexRoden\LibraryApiPhp\Bus\Events\DeleteCategoryEvent;
use AlexRoden\LibraryApiPhp\Bus\Events\DeleteCouncilEvent;
use AlexRoden\LibraryApiPhp\Bus\Events\DeleteLibraryEvent;
use AlexRoden\LibraryApiPhp\Bus\Events\DeleteUserEvent;
use AlexRoden\LibraryApiPhp\Bus\Events\UpdateAuthorEvent;
use AlexRoden\LibraryApiPhp\Bus\Events\UpdateBookEvent;
use AlexRoden\LibraryApiPhp\Bus\Events\UpdateCategoryEvent;
use AlexRoden\LibraryApiPhp\Bus\Events\UpdateCouncilEvent;
use AlexRoden\LibraryApiPhp\Bus\Events\UpdateLibraryEvent;
use AlexRoden\LibraryApiPhp\Bus\Events\UpdateStockEvent;
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
    CreateBookEvent::class => [],
    CreateCategoryEvent::class => [],
    CreateCouncilEvent::class => [],
    CreateLibraryEvent::class => [],
    CreateStockEvent::class => [],
    CreateUserEvent::class => [
        SendWelcomeEmailListener::class,
    ],
    DeleteCategoryEvent::class => [],
    DeleteCouncilEvent::class => [],
    DeleteLibraryEvent::class => [],
    DeleteUserEvent::class => [
        LeavingEmailListener::class,
    ],
    UpdateAuthorEvent::class => [],
    UpdateBookEvent::class => [],
    UpdateCategoryEvent::class => [],
    UpdateCouncilEvent::class => [],
    UpdateLibraryEvent::class => [],
    UpdateStockEvent::class => [],
    UpdateUserEvent::class => [],
];
