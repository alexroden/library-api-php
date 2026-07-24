<?php

namespace AlexRoden\LibraryApiPhp\Tests\Features\Users;

use AlexRoden\LibraryApiPhp\Enums\Roles;
use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Models\User;
use AlexRoden\LibraryApiPhp\Tests\Factories\UserFactory;
use AlexRoden\LibraryApiPhp\Tests\Features\AbstractFeaturesTestCase;

class DeleteTest extends AbstractFeaturesTestCase
{
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::create();
    }

    public function testDeleteUser(): void
    {
        $this->asAuthorizedUser();

        $response = $this->handle(
            Request::create(
                method: 'DELETE',
                uri: "/api/users/{$this->user->id}",
            )
        );

        $this->assertEquals(204, $response->status());

        $user = User::where('id', '=', $this->user->id)->first();
        $this->assertNull($user);
    }
}