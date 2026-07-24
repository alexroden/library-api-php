<?php

namespace AlexRoden\LibraryApiPhp\Bus\Handlers;

use AlexRoden\LibraryApiPhp\Bus\EventBus;

abstract class AbstractCommandHandler
{
    public function __construct(
        protected EventBus $events,
    ) {}
}