<?php

namespace AlexRoden\LibraryApiPhp\Bus\Handlers;

use AlexRoden\LibraryApiPhp\Bus\CommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Commands\UpdateUserCommand;
use AlexRoden\LibraryApiPhp\Bus\EventBus;
use AlexRoden\LibraryApiPhp\Bus\Events\UpdateUserEvent;
use AlexRoden\LibraryApiPhp\Models\User;

readonly class UpdateUserCommandHandler implements CommandHandler
{
    public function __construct(
        private EventBus $events,
    ) {}

    public function handle(object $command): User
    {
        /** @var UpdateUserCommand $command */
        $command->user->update([
            'email' => $command->email,
            'password' =>$command->password,
            'first_name' => $command->firstName,
            'last_name' => $command->lastName,
        ]);

        $user = $command->user->refresh();

        $this->events->dispatch(
            new UpdateUserEvent($user)
        );

        return $user;
    }
}