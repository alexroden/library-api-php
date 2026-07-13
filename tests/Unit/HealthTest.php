<?php

namespace Tests\Unit;

use App\Controllers\HealthController;
use PHPUnit\Framework\TestCase;

class HealthTest extends TestCase
{
    public function testHealthReturnsOk(): void
    {
        $controller = new HealthController();

        $response = $controller->index();

        $this->assertSame([
            'status' => 'ok',
        ], $response);
    }
}