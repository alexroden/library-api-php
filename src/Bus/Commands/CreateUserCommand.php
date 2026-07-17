<?php

namespace AlexRoden\LibraryApiPhp\Bus\Commands;

class CreateUserCommand
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly ?array $roles = [],
    ) {}
}