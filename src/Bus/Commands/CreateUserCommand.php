<?php

namespace AlexRoden\LibraryApiPhp\Bus\Commands;

readonly class CreateUserCommand
{
    public function __construct(
        public string $email,
        public string $password,
        public string $firstName,
        public string $lastName,
        public ?array $roles = [],
    ) {}
}