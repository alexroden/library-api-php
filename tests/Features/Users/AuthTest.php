<?php

namespace AlexRoden\LibraryApiPhp\Tests\Features\Users;

use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Models\User;
use AlexRoden\LibraryApiPhp\Tests\Factories\UserFactory;
use AlexRoden\LibraryApiPhp\Tests\Features\AbstractFeaturesTestCase;

class AuthTest extends AbstractFeaturesTestCase
{
    protected UserFactory $factory;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = new UserFactory();
        $this->user = $this->factory->create();
    }

    public function testAuth(): void
    {
        $response = $this->handle(
            Request::create(
                method: 'POST',
                uri: '/api/auth',
                body: [
                    "email" => $this->user->email,
                    "password" => $this->factory->plainPassword,
                ]
            )
        );

        $this->assertEquals(200, $response->status());
        $this->assertNotEmpty($response->headers()['Authorization']);
        $this->assertSame(
            $this->user->toArray(),
            $response->json()['data']
        );
    }
}