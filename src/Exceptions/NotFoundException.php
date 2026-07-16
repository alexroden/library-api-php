<?php

namespace AlexRoden\LibraryApiPhp\Exceptions;

class NotFoundException extends HttpException
{
    public function __construct(
        string $message = 'Not found'
    ) {
        parent::__construct(404, $message);
    }
}