<?php

namespace Tests\Unit;

use App\Hello;
use PHPUnit\Framework\TestCase;

class HelloTest extends TestCase
{
    public function testGreetReturnsHelloWorld(): void
    {
        $hello = new Hello();

        $this->assertSame('Hello, World!', $hello->greet());
    }
}