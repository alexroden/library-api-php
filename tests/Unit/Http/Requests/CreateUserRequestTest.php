<?php

namespace AlexRoden\LibraryApiPhp\Tests\Unit\Http\Requests;

use AlexRoden\LibraryApiPhp\Enums\Roles;
use AlexRoden\LibraryApiPhp\Http\Exceptions\ValidationException;
use AlexRoden\LibraryApiPhp\Http\Requests\CreateUserRequest;
use AlexRoden\LibraryApiPhp\Tests\AbstractTestCase;

class CreateUserRequestTest extends AbstractTestCase
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

        $req = new CreateUserRequest(body: $body);

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
        $password = $this->faker->password();

        $body = [
            'email' => 'not-an-email',
            'password' => $password,
            'password_confirmation' => $password,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'roles' => [Roles::ADMIN],
        ];

        $this->expectException(ValidationException::class);

        new CreateUserRequest(body: $body);
    }

    public function testPasswordMustBeValid(): void
    {
        $password = 'foo';

        $body = [
            'email' => $this->faker->email,
            'password' => $password,
            'password_confirmation' => $password,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'roles' => [Roles::ADMIN],
        ];

        $this->expectException(ValidationException::class);

        new CreateUserRequest(body: $body);
    }

    public function testPasswordMustBeConfirmed(): void
    {
        $password = $this->faker->password();

        $body = [
            'email' => $this->faker->email,
            'password' => $password,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'roles' => [Roles::ADMIN],
        ];

        $this->expectException(ValidationException::class);

        new CreateUserRequest(body: $body);
    }

    public function testFirstNameIsRequired(): void
    {
        $password = $this->faker->password();

        $body = [
            'email' => $this->faker->email,
            'password' => $password,
            'password_confirmation' => $password,
            'last_name' => $this->faker->lastName,
            'roles' => [Roles::ADMIN],
        ];

        $this->expectException(ValidationException::class);

        new CreateUserRequest(body: $body);
    }

    public function testLastNameIsRequired(): void
    {
        $password = $this->faker->password();

        $body = [
            'email' => $this->faker->email,
            'password' => $password,
            'password_confirmation' => $password,
            'first_name' => $this->faker->firstName,
            'roles' => [Roles::ADMIN],
        ];

        $this->expectException(ValidationException::class);

        new CreateUserRequest(body: $body);
    }

    public function testRolesMustBeAnArray(): void
    {
        $password = $this->faker->password();

        $body = [
            'email' => $this->faker->email,
            'password' => $password,
            'password_confirmation' => $password,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'roles' => Roles::ADMIN,
        ];

        $this->expectException(ValidationException::class);

        new CreateUserRequest(body: $body);
    }
}