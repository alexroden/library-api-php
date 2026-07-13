<?php

namespace Tests\Unit\Controllers\Health;

use App\Controllers\HealthController;
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