<?php

namespace AlexRoden\LibraryApiPhp\Bus\Commands;

use AlexRoden\LibraryApiPhp\Models\User;

readonly class UpdateUserCommand
{
    public function __construct(
        public User $user,
        public string $email,
        public string $password,
        public string $firstName,
        public string $lastName,
        public ?array $roles = [],
    ) {}
}