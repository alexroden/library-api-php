<?php

namespace AlexRoden\LibraryApiPhp\Bus\Listeners;

use AlexRoden\LibraryApiPhp\Bus\EventListener;
use AlexRoden\LibraryApiPhp\Bus\Events\CreateUserEvent;
use AlexRoden\LibraryApiPhp\Mail\Mail;

class SendWelcomeEmailListener implements EventListener
{
    public function __construct(
        protected Mail $mail,
    ) {}

    public function handle(object $event): void
    {
        /** @var CreateUserEvent $event */

        $this->mail->send(
            $event->user->email,
            'Welcome!',
            'Thanks for registering.'
        );
    }
}