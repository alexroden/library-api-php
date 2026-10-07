<?php

namespace AlexRoden\LibraryApiPhp\Http\Exceptions;

class ValidationException extends AbstractHttpException
{
    public function __construct(
        private readonly array $errors = []
    ) {
        parent::__construct(422, 'The given data was invalid.');
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
