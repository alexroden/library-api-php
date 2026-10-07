<?php

namespace AlexRoden\LibraryApiPhp\Http\Exceptions;

use Throwable;

class JwtException extends AbstractHttpException
{
    public function __construct(
        string $message = 'Invalid or expired token.',
        int $statusCode = 401,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            statusCode: $statusCode,
            message: $message,
            previous: $previous
        );
    }
}
