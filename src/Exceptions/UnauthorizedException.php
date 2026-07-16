<?php

namespace AlexRoden\LibraryApiPhp\Exceptions;

class UnauthorizedException extends HttpException
{
    public function __construct(
        string $message = 'Forbidden'
    ) {
        parent::__construct(403, $message);
    }
}