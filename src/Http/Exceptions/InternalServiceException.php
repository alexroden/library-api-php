<?php

namespace AlexRoden\LibraryApiPhp\Http\Exceptions;

class InternalServiceException extends AbstractHttpException
{
    public function __construct(
        string $message = 'Internal service error',
    ) {
        parent::__construct(500, $message);
    }
}