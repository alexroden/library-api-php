<?php


use AlexRoden\LibraryApiPhp\Bus\Commands\CreateAuthorCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\CreateCouncilCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\CreateLibraryCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\CreateUserCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\DeleteAuthorCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\DeleteCouncilCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\DeleteLibraryCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\DeleteUserCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\UpdateAuthorCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\UpdateCouncilCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\UpdateLibraryCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\UpdateUserCommand;
use AlexRoden\LibraryApiPhp\Bus\Handlers\CreateAuthorCommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Handlers\CreateCouncilCommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Handlers\CreateLibraryCommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Handlers\CreateUserCommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Handlers\DeleteAuthorCommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Handlers\DeleteCouncilCommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Handlers\DeleteLibraryCommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Handlers\DeleteUserCommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Handlers\UpdateAuthorCommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Handlers\UpdateCouncilCommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Handlers\UpdateLibraryCommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Handlers\UpdateUserCommandHandler;

/*
|--------------------------------------------------------------------------
| This is how the bus commands and there handlers are connected.
|--------------------------------------------------------------------------
*/
return [
    CreateAuthorCommand::class => CreateAuthorCommandHandler::class,
    CreateCouncilCommand::class => CreateCouncilCommandHandler::class,
    CreateLibraryCommand::class => CreateLibraryCommandHandler::class,
    CreateUserCommand::class => CreateUserCommandHandler::class,
    DeleteAuthorCommand::class => DeleteAuthorCommandHandler::class,
    DeleteCouncilCommand::class => DeleteCouncilCommandHandler::class,
    DeleteLibraryCommand::class => DeleteLibraryCommandHandler::class,
    DeleteUserCommand::class => DeleteUserCommandHandler::class,
    UpdateAuthorCommand::class => UpdateAuthorCommandHandler::class,
    UpdateCouncilCommand::class => UpdateCouncilCommandHandler::class,
    UpdateLibraryCommand::class => UpdateLibraryCommandHandler::class,
    UpdateUserCommand::class => UpdateUserCommandHandler::class,
];