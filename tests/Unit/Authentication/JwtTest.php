<?php

namespace AlexRoden\LibraryApiPhp\Tests\Unit\Authentication;

use AlexRoden\LibraryApiPhp\Authentication\Jwt;
use AlexRoden\LibraryApiPhp\Tests\AbstractTestCase;

class JwtTest extends AbstractTestCase
{
    protected Jwt $jwt;

    protected function setUp(): void
    {
        parent::setUp();

        $this->jwt = new Jwt('secret');
    }

    public function testEncode(): void
    {
        $token = $this->jwt->encode([
            'sub' => 1,
            'email' => 'john.smith@example.com',
        ]);

        $this->assertIsString($token);
        $this->assertNotEmpty($token);
    }

    public function testDecode(): void
    {
        $expected = [
            'sub' => 1,
            'email' => 'john.smith@example.com',
        ];

        $token = $this->jwt->encode($expected);
        $actual = $this->jwt->decode($token);

        $this->assertEquals($expected['sub'], $actual['sub']);
        $this->assertEquals($expected['email'], $actual['email']);
    }
}
