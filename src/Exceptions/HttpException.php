<?php

namespace AlexRoden\LibraryApiPhp\Exceptions;

use Exception;

class HttpException extends Exception
{
    public function __construct(
        private readonly int $statusCode,
        string $message = '',
        ?Exception $previous = null
    ) {
        parent::__construct($message, 0, $previous);
    }

    public function statusCode(): int
    {
        return $this->statusCode;
    }
}