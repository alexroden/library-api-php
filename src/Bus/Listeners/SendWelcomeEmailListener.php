<?php

namespace AlexRoden\LibraryApiPhp\Bus\Listeners;

use AlexRoden\LibraryApiPhp\Bus\EventListener;
use AlexRoden\LibraryApiPhp\Bus\Events\CreateUserEvent;

class SendWelcomeEmailListener implements EventListener
{
    public function handle(object $event): void
    {
        /** @var CreateUserEvent $event */

        // Send email...
    }
}