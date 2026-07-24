<?php

namespace AlexRoden\LibraryApiPhp\Bus\Handlers;

use AlexRoden\LibraryApiPhp\Bus\CommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Commands\CreateUserCommand;
use AlexRoden\LibraryApiPhp\Bus\EventBus;
use AlexRoden\LibraryApiPhp\Bus\Events\CreateUserEvent;
use AlexRoden\LibraryApiPhp\Exceptions\ResourceNotFoundException;
use AlexRoden\LibraryApiPhp\Exceptions\UndefinedClassException;
use AlexRoden\LibraryApiPhp\Models\User;

readonly class CreateUserCommandHandler implements CommandHandler
{
    public function __construct(
        private EventBus $events,
    ) {}

    /**
     * @throws UndefinedClassException
     * @throws ResourceNotFoundException
     */
    public function handle(object $command): User
    {
        /** @var CreateUserCommand $command */
        $user = User::create([
            'email' => $command->email,
            'password' =>$command->password,
            'first_name' => $command->firstName,
            'last_name' => $command->lastName,
        ]);

        if (count($command->roles) > 0) {
            foreach ($command->roles as $role) {
                $user->assignRole($role);
            }
        }

        $this->events->dispatch(
            new CreateUserEvent($user)
        );

        return $user;
    }
}