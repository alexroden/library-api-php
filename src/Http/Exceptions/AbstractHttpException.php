<?php

namespace AlexRoden\LibraryApiPhp\Http\Exceptions;

use Exception;

class AbstractHttpException extends Exception
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