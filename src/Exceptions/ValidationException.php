<?php

namespace AlexRoden\LibraryApiPhp\Exceptions;

class ValidationException extends HttpException
{
    public function __construct(
        private readonly array $errors
    ) {
        parent::__construct(422, 'The given data was invalid.');
    }

    public function errors(): array
    {
        return $this->errors;
    }
}