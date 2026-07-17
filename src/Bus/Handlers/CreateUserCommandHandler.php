<?php

namespace AlexRoden\LibraryApiPhp\Bus\Handlers;

use AlexRoden\LibraryApiPhp\Bus\CommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Commands\CreateUserCommand;
use AlexRoden\LibraryApiPhp\Bus\EventBus;
use AlexRoden\LibraryApiPhp\Bus\Events\CreateUserEvent;
use AlexRoden\LibraryApiPhp\Models\User;

class CreateUserCommandHandler implements CommandHandler
{
    public function __construct(
        private EventBus $events,
    ) {}

    public function handle(object $command): User
    {
        /** @var CreateUserCommand $command */
        $user = (new User())->create([
            'email' => $command->email,
            'password' =>$command->password,
            'first_name' => $command->firstName,
            'last_name' => $command->lastName,
        ]);

        $this->events->dispatch(
            new CreateUserEvent($user)
        );

        return $user;
    }
}