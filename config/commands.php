<?php


use AlexRoden\LibraryApiPhp\Bus\Commands\CreateAuthorCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\CreateBookCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\CreateCategoryCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\CreateCouncilCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\CreateLibraryCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\CreateStockCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\CreateUserCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\DeleteAuthorCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\DeleteBookCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\DeleteCategoryCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\DeleteCouncilCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\DeleteLibraryCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\DeleteUserCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\UpdateAuthorCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\UpdateBookCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\UpdateCategoryCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\UpdateCouncilCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\UpdateLibraryCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\UpdateStockCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\UpdateUserCommand;
use AlexRoden\LibraryApiPhp\Bus\Handlers\CreateAuthorCommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Handlers\CreateBookCommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Handlers\CreateCategoryCommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Handlers\CreateCouncilCommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Handlers\CreateLibraryCommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Handlers\CreateStockCommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Handlers\CreateUserCommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Handlers\DeleteAuthorCommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Handlers\DeleteBookCommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Handlers\DeleteCategoryCommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Handlers\DeleteCouncilCommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Handlers\DeleteLibraryCommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Handlers\DeleteUserCommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Handlers\UpdateAuthorCommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Handlers\UpdateBookCommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Handlers\UpdateCategoryCommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Handlers\UpdateCouncilCommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Handlers\UpdateLibraryCommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Handlers\UpdateStockCommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Handlers\UpdateUserCommandHandler;

/*
|--------------------------------------------------------------------------
| This is how the bus commands and there handlers are connected.
|--------------------------------------------------------------------------
*/
return [
    CreateAuthorCommand::class => CreateAuthorCommandHandler::class,
    CreateBookCommand::class => CreateBookCommandHandler::class,
    CreateCategoryCommand::class => CreateCategoryCommandHandler::class,
    CreateCouncilCommand::class => CreateCouncilCommandHandler::class,
    CreateLibraryCommand::class => CreateLibraryCommandHandler::class,
    CreateStockCommand::class => CreateStockCommandHandler::class,
    CreateUserCommand::class => CreateUserCommandHandler::class,
    DeleteAuthorCommand::class => DeleteAuthorCommandHandler::class,
    DeleteBookCommand::class => DeleteBookCommandHandler::class,
    DeleteCategoryCommand::class => DeleteCategoryCommandHandler::class,
    DeleteCouncilCommand::class => DeleteCouncilCommandHandler::class,
    DeleteLibraryCommand::class => DeleteLibraryCommandHandler::class,
    DeleteUserCommand::class => DeleteUserCommandHandler::class,
    UpdateAuthorCommand::class => UpdateAuthorCommandHandler::class,
    UpdateBookCommand::class => UpdateBookCommandHandler::class,
    UpdateCategoryCommand::class => UpdateCategoryCommandHandler::class,
    UpdateCouncilCommand::class => UpdateCouncilCommandHandler::class,
    UpdateLibraryCommand::class => UpdateLibraryCommandHandler::class,
    UpdateStockCommand::class => UpdateStockCommandHandler::class,
    UpdateUserCommand::class => UpdateUserCommandHandler::class,
];
