<?php

namespace AlexRoden\LibraryApiPhp\Exceptions;

use Exception;

class UndefinedClassException extends Exception
{
    public function __construct(string $table)
    {
        parent::__construct("Class for {$table} is undefined");
    }
}