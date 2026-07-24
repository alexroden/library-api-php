<?php

namespace AlexRoden\LibraryApiPhp\Bus\Commands;

use AlexRoden\LibraryApiPhp\Models\User;

readonly class DeleteUserCommand
{
    public function __construct(
        public User $user,
    ) {}
}