<?php

namespace AlexRoden\LibraryApiPhp\Tests\Features\Users;

use AlexRoden\LibraryApiPhp\Enums\Roles;
use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Models\User;
use AlexRoden\LibraryApiPhp\Tests\Factories\UserFactory;
use AlexRoden\LibraryApiPhp\Tests\Features\AbstractFeaturesTestCase;

class UpdateTest extends AbstractFeaturesTestCase
{
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::create();
    }

    public function testUpdateUser(): void
    {
        $this->asAuthorizedUser();

        $email = $this->faker->email;
        $password = $this->faker->password;
        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;

        $response = $this->handle(
            Request::create(
                method: 'PUT',
                uri: "/api/users/{$this->user->id}",
                body: [
                    'email' => $email,
                    'password' => $password,
                    'password_confirmation' => $password,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'roles' => [Roles::USER],
                ]
            )
        );

        $this->assertEquals(200, $response->status());

        $user = User::where('email', '=', $email)->first();
        $this->assertNotNull($user);
        $this->assertSame([
            'email' => $email,
            'first_name' => $firstName,
            'last_name' => $lastName,
        ], [
            'email' => $user->email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
        ]);
        $this->assertNotEmpty($user->roles());
    }
}
