<?php

namespace AlexRoden\LibraryApiPhp\Exceptions;

class NotFountException extends AbstractException
{
    public static function resource(string $resource): self
    {
        return new self("{$resource} does not exist}");
    }
}