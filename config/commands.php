<?php


use AlexRoden\LibraryApiPhp\Bus\Commands\CreateUserCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\UpdateUserCommand;
use AlexRoden\LibraryApiPhp\Bus\Handlers\CreateUserCommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Handlers\UpdateUserCommandHandler;

/*
|--------------------------------------------------------------------------
| This is how the bus commands and there handlers are connected.
|--------------------------------------------------------------------------
*/
return [
    CreateUserCommand::class => CreateUserCommandHandler::class,
    UpdateUserCommand::class => UpdateUserCommandHandler::class,
];