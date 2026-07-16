<?php

namespace AlexRoden\LibraryApiPhp\Tests\Unit\Authentication;

use AlexRoden\LibraryApiPhp\Authentication\Jwt;
use AlexRoden\LibraryApiPhp\Tests\AbstractTestCase;

class JwtTest extends AbstractTestCase
{
    public function testEncode(): void
    {
        $jwt = new Jwt('secret');

        $token = $jwt->encode([
            'sub' => 1,
            'email' => 'john.smith@example.com',
        ]);

        $this->assertIsString($token);
        $this->assertNotEmpty($token);
    }
}