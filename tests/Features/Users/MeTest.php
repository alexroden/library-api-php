<?php

namespace AlexRoden\LibraryApiPhp\Tests\Features\Users;

use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Tests\Features\AbstractFeaturesTestCase;

class MeTest extends AbstractFeaturesTestCase
{
    public function testMe(): void
    {
        $this->asAuthorizedUser();

        $response = $this->handle(
            Request::create(
                method: 'GET',
                uri: '/api/users',
            )
        );

        $this->assertEquals(200, $response->status());
        $this->assertSame(
            $this->currentUser->toArray(),
            $response->json()['data'][0]->toArray()
        );
    }
}
