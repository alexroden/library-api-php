<?php

namespace AlexRoden\LibraryApiPhp\Exceptions;

class JwtException extends HttpException
{
    public function __construct(
        string $message = 'Invalid or expired token.',
        int $statusCode = 401,
        ?\Throwable $previous = null
    ) {
        parent::__construct(
            statusCode: $statusCode,
            message: $message,
            previous: $previous
        );
    }
}