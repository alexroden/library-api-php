<?php

namespace AlexRoden\LibraryApiPhp\Http\Exceptions;

use Throwable;

class PermissionException extends AbstractHttpException
{
    public function __construct(
        string $message = 'User don\'t have permission to access this resource.',
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
