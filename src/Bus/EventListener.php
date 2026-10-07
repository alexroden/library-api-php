<?php

namespace AlexRoden\LibraryApiPhp\Bus;

interface EventListener
{
    public function handle(object $event): void;
}
