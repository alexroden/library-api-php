<?php

namespace AlexRoden\LibraryApiPhp\Tests\Features\Users;

use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Tests\Factories\UserFactory;
use AlexRoden\LibraryApiPhp\Tests\Features\AbstractFeaturesTestCase;

class GetTest extends AbstractFeaturesTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::create();
    }

    public function testGet(): void
    {
        $this->asAuthorizedUser();

        $response = $this->handle(
            Request::create(
                method: 'GET',
                uri: "/api/users/{$this->user->id}",
            )
        );

        $this->assertEquals(200, $response->status());
        $this->assertSame(
            $this->user->toArray(),
            $response->json()['data']->toArray()
        );
    }
}