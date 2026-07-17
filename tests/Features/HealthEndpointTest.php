<?php

namespace AlexRoden\LibraryApiPhp\Tests\Features;

use AlexRoden\LibraryApiPhp\Http\Controllers\HealthController;
use PHPUnit\Framework\TestCase;

class HealthEndpointTest extends TestCase
{
    public function testHealthEndpointReturnsOk(): void
    {
        $controller = new HealthController();

        $response = $controller->index();

        $this->assertSame(200, $response->status);
        $this->assertSame([
            'status' => 'ok',
        ], $response->data);
    }
}