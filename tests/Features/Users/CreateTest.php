<?php

namespace AlexRoden\LibraryApiPhp\Tests\Features\Users;

use AlexRoden\LibraryApiPhp\Enums\Roles;
use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Models\User;
use AlexRoden\LibraryApiPhp\Tests\Features\AbstractFeaturesTestCase;

class CreateTest extends AbstractFeaturesTestCase
{
    public function testCreateUser(): void
    {
        $this->asAuthorizedUser();

        $email = $this->faker->email;
        $password = $this->faker->password;

        $response = $this->handle(
            Request::create(
                method: 'POST',
                uri: '/api/users',
                body: [
                    "email" => $email,
                    "password" => $password,
                    "password_confirmation" => $password,
                    'first_name' => $this->faker->firstName,
                    'last_name' => $this->faker->lastName,
                    'roles' => [Roles::USER],
                ]
            )
        );

        $this->assertEquals(200, $response->status());

        $user = User::where('email', '=', $email)->first();
        $this->assertNotNull($user);
        $this->assertEquals($email, $user->email);
        $this->assertNotEmpty($user->roles());
    }
}