<?php


use AlexRoden\LibraryApiPhp\Bus\Commands\CreateCouncilCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\CreateUserCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\DeleteUserCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\UpdateCouncilCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\UpdateUserCommand;
use AlexRoden\LibraryApiPhp\Bus\Handlers\CreateCouncilCommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Handlers\CreateUserCommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Handlers\DeleteUserCommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Handlers\UpdateCouncilCommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Handlers\UpdateUserCommandHandler;

/*
|--------------------------------------------------------------------------
| This is how the bus commands and there handlers are connected.
|--------------------------------------------------------------------------
*/
return [
    CreateCouncilCommand::class => CreateCouncilCommandHandler::class,
    CreateUserCommand::class => CreateUserCommandHandler::class,
    DeleteUserCommand::class => DeleteUserCommandHandler::class,
    UpdateCouncilCommand::class => UpdateCouncilCommandHandler::class,
    UpdateUserCommand::class => UpdateUserCommandHandler::class,
];