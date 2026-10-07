<?php

namespace AlexRoden\LibraryApiPhp\Tests\Features;

use AlexRoden\LibraryApiPhp\Http\Controllers\HealthController;
use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use PHPUnit\Framework\TestCase;

class HealthEndpointTest extends AbstractFeaturesTestCase
{
    public function testHealthEndpointReturnsOk(): void
    {
        $response = $this->handle(
            Request::create('GET', '/api/_health'),
        );

        $this->assertSame(200, $response->status());
    }
}
