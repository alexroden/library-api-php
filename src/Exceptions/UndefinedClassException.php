<?php

namespace AlexRoden\LibraryApiPhp\Exceptions;

class UndefinedClassException extends AbstractException
{
    public function __construct(string $table)
    {
        parent::__construct("Class for {$table} is undefined");
    }
}