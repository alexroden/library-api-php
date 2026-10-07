<?php

namespace AlexRoden\LibraryApiPhp\Tests\Features\Users;

use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Models\User;
use AlexRoden\LibraryApiPhp\Tests\Factories\UserFactory;
use AlexRoden\LibraryApiPhp\Tests\Features\AbstractFeaturesTestCase;

class ListTest extends AbstractFeaturesTestCase
{
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::create();
    }

    public function testListUsers(): void
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
            $this->user->toArray(),
            $response->json()['data'][0]->toArray()
        );
    }
}
