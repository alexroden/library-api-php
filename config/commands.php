<?php


use AlexRoden\LibraryApiPhp\Bus\Commands\CreateUserCommand;
use AlexRoden\LibraryApiPhp\Bus\Handlers\CreateUserCommandHandler;

/*
|--------------------------------------------------------------------------
| This is how the bus commands and there handlers are connected.
|--------------------------------------------------------------------------
*/
return [
    CreateUserCommand::class => CreateUserCommandHandler::class,
];