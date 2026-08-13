<?php

namespace AlexRoden\LibraryApiPhp\Bus;

interface CommandHandler
{
    public function handle(object $command): mixed;
}
