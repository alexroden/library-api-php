<?php

namespace AlexRoden\LibraryApiPhp\Bus\Listeners;

use AlexRoden\LibraryApiPhp\Bus\EventListener;
use AlexRoden\LibraryApiPhp\Bus\Events\CreateUserEvent;
use AlexRoden\LibraryApiPhp\Mail\Mailer;

class SendWelcomeEmailListener implements EventListener
{
    public function __construct(
        protected Mailer $mail,
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
