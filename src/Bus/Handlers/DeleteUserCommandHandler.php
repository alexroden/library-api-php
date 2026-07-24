<?php

namespace AlexRoden\LibraryApiPhp\Bus\Handlers;

use AlexRoden\LibraryApiPhp\Bus\CommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Commands\DeleteUserCommand;
use AlexRoden\LibraryApiPhp\Bus\EventBus;
use AlexRoden\LibraryApiPhp\Bus\Events\DeleteUserEvent;
use AlexRoden\LibraryApiPhp\Exceptions\ResourceNotFoundException;
use AlexRoden\LibraryApiPhp\Exceptions\UndefinedClassException;

readonly class DeleteUserCommandHandler implements CommandHandler
{
    public function __construct(
        private EventBus $events,
    ) {}

    /**
     * @throws UndefinedClassException|ResourceNotFoundException
     */
    public function handle(object $command): null
    {
        /** @var DeleteUserCommand $command */
        $user = $command->user;

        if (count($user->roles()) > 0) {
            foreach ($user->roles() as $role) {
                $user->unassignRole($role);
            }
        }

        $user->delete();

        $this->events->dispatch(
            new DeleteUserEvent($user)
        );

        return null;
    }
}