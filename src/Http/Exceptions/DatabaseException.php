<?php

namespace AlexRoden\LibraryApiPhp\Http\Exceptions;

use PDOException;

class DatabaseException extends AbstractHttpException
{
    public function __construct(
        string $message = 'A database error occurred.',
        int $dbCode = 0,
        ?PDOException $previous = null,
    ) {
        $statusCode = 500;
        switch ($dbCode) {
            case '23000':
                $message = 'A record with this value already exists.';
                $statusCode = 409;
                break;
        }

        parent::__construct(
            statusCode: $statusCode,
            message: $message,
            previous: $previous,
        );
    }
}
