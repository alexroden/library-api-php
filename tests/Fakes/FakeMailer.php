<?php

namespace AlexRoden\LibraryApiPhp\Tests\Fakes;

use AlexRoden\LibraryApiPhp\Mail\Mailer;

class FakeMailer implements Mailer
{
    public array $sent = [];

    public function send(
        string $to,
        string $subject,
        string $body
    ): void {
        $this->sent[] = [
            'to' => $to,
            'subject' => $subject,
            'body' => $body,
        ];
    }
}
