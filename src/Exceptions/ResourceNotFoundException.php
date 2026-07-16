<?php

namespace AlexRoden\LibraryApiPhp\Exceptions;

class ResourceNotFoundException extends AbstractException
{
    public static function resource(string $resource): self
    {
        return new self("{$resource} does not exist}");
    }
}