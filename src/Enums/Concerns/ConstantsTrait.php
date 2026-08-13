<?php

namespace AlexRoden\LibraryApiPhp\Enums\Concerns;

use ReflectionClass;

trait ConstantsTrait
{
    /**
     * @return array
     */
    public static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);

        return $oClass->getConstants();
    }
}
