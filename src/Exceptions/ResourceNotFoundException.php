<?php

namespace AlexRoden\LibraryApiPhp\Exceptions;

use Exception;

class ResourceNotFoundException extends Exception
{
    public static function resource(string $resource): self
    {
        return new self("{$resource} does not exist}");
    }
}