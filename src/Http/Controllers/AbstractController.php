<?php

namespace AlexRoden\LibraryApiPhp\Http\Controllers;

use AlexRoden\LibraryApiPhp\Bus\CommandBus;

abstract class AbstractController
{
    public function __construct(
        protected CommandBus $commandBus,
    )
    {
    }
}
