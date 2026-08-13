<?php

namespace AlexRoden\LibraryApiPhp\Bus\Handlers;

use AlexRoden\LibraryApiPhp\Bus\CommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Commands\CreateUserCommand;
use AlexRoden\LibraryApiPhp\Bus\Events\CreateUserEvent;
use AlexRoden\LibraryApiPhp\Enums\Roles;
use AlexRoden\LibraryApiPhp\Exceptions\ResourceNotFoundException;
use AlexRoden\LibraryApiPhp\Exceptions\UndefinedClassException;
use AlexRoden\LibraryApiPhp\Models\User;

class CreateUserCommandHandler extends AbstractCommandHandler implements CommandHandler
{
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

        $roles = $command->roles;
        if (count($roles) === 0) {
            $roles[] = Roles::USER;
        }

        $user->assignRole(...$roles);

        $this->events->dispatch(
            new CreateUserEvent($user)
        );

        return $user;
    }
}
