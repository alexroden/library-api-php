<?php

namespace AlexRoden\LibraryApiPhp\Http\Exceptions;

class UnauthorizedException extends AbstractHttpException
{
    public function __construct(
        string $message = 'Forbidden'
    ) {
        parent::__construct(403, $message);
    }
}
