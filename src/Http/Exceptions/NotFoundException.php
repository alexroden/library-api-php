<?php

namespace AlexRoden\LibraryApiPhp\Http\Exceptions;

class NotFoundException extends AbstractHttpException
{
    public function __construct(
        string $message = 'Not found'
    ) {
        parent::__construct(404, $message);
    }
}