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

        $_POST = [
            'email' => $this->faker->email,
            'password' => $password,
            'password_confirmation' => $password,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'roles' => [Roles::ADMIN],
        ];

        $req = new CreateUserRequest();

        $this->assertEquals($_POST, $req->validated());
    }

    public function testEmailMustBeValid(): void
    {
        $password = $this->faker->password();

        $_POST = [
            'email' => 'not-an-email',
            'password' => $password,
            'password_confirmation' => $password,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'roles' => [Roles::ADMIN],
        ];

        $this->expectException(ValidationException::class);

        new CreateUserRequest();
    }

    public function testPasswordMustBeValid(): void
    {
        $password = 'foo';

        $_POST = [
            'email' => 'not-an-email',
            'password' => $password,
            'password_confirmation' => $password,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'roles' => [Roles::ADMIN],
        ];

        $this->expectException(ValidationException::class);

        new CreateUserRequest();
    }

    public function testPasswordMustBeConfirmed(): void
    {
        $password = $this->faker->password();

        $_POST = [
            'email' => 'not-an-email',
            'password' => $password,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'roles' => [Roles::ADMIN],
        ];

        $this->expectException(ValidationException::class);

        new CreateUserRequest();
    }

    public function testFirstNameIsRequired(): void
    {
        $password = $this->faker->password();

        $_POST = [
            'email' => 'not-an-email',
            'password' => $password,
            'password_confirmation' => $password,
            'last_name' => $this->faker->lastName,
            'roles' => [Roles::ADMIN],
        ];

        $this->expectException(ValidationException::class);

        new CreateUserRequest();
    }

    public function testLastNameIsRequired(): void
    {
        $password = $this->faker->password();

        $_POST = [
            'email' => 'not-an-email',
            'password' => $password,
            'password_confirmation' => $password,
            'first_name' => $this->faker->firstName,
            'roles' => [Roles::ADMIN],
        ];

        $this->expectException(ValidationException::class);

        new CreateUserRequest();
    }

    public function testRolesMustBeAnArray(): void
    {
        $password = $this->faker->password();

        $_POST = [
            'email' => 'not-an-email',
            'password' => $password,
            'password_confirmation' => $password,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'roles' => Roles::ADMIN,
        ];

        $this->expectException(ValidationException::class);

        new CreateUserRequest();
    }
}