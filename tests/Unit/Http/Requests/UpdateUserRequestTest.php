<?php

namespace AlexRoden\LibraryApiPhp\Tests\Unit\Http\Requests;

use AlexRoden\LibraryApiPhp\Enums\Roles;
use AlexRoden\LibraryApiPhp\Http\Exceptions\ValidationException;
use AlexRoden\LibraryApiPhp\Http\Requests\UpdateUserRequest;
use AlexRoden\LibraryApiPhp\Tests\AbstractTestCase;

class UpdateUserRequestTest extends AbstractTestCase
{
    public function testRulesPassed(): void
    {
        $password = $this->faker->password();

        $body = [
            'email' => $this->faker->email,
            'password' => $password,
            'password_confirmation' => $password,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'roles' => [Roles::ADMIN],
        ];

        $req = new UpdateUserRequest(body: $body);

        $valid = $req->validated();
        $this->assertEquals($body, [
            'email' => $valid['email'],
            'password' => $valid['password'],
            'password_confirmation' => $valid['passwordConfirmation'],
            'first_name' => $valid['firstName'],
            'last_name' => $valid['lastName'],
            'roles' => [Roles::ADMIN],
        ]);
    }

    public function testEmailMustBeValid(): void
    {
        $body = [
            'email' => 'not-an-email',
        ];

        $this->expectException(ValidationException::class);

        new UpdateUserRequest(body: $body);
    }

    public function testPasswordMustBeValid(): void
    {
        $password = 'foo';

        $body = [
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $this->expectException(ValidationException::class);

        new UpdateUserRequest(body: $body);
    }

    public function testPasswordMustBeConfirmed(): void
    {
        $password = $this->faker->password();

        $body = [
            'password' => $password,
        ];

        $this->expectException(ValidationException::class);

        new UpdateUserRequest(body: $body);
    }
}
