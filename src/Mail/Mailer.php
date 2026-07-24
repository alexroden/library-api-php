<?php

namespace AlexRoden\LibraryApiPhp\Mail;

interface Mailer
{
    public function send(
        string $to,
        string $subject,
        string $body
    ): void;
}