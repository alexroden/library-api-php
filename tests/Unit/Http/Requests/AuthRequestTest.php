<?php

namespace AlexRoden\LibraryApiPhp\Tests\Unit\Http\Requests;

use AlexRoden\LibraryApiPhp\Http\Exceptions\ValidationException;
use AlexRoden\LibraryApiPhp\Http\Requests\AuthRequest;
use AlexRoden\LibraryApiPhp\Models\User;
use AlexRoden\LibraryApiPhp\Tests\AbstractTestCase;
use AlexRoden\LibraryApiPhp\Tests\Factories\UserFactory;

class AuthRequestTest extends AbstractTestCase
{
    protected UserFactory $factory;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = new UserFactory();
        $this->user = $this->factory->create();
    }

    public function testRulesPassed(): void
    {
        $body = [
            'email' => $this->user->email,
            'password' => $this->factory->plainPassword,
        ];

        $req = new AuthRequest(body: $body);

        $this->assertEquals($body, $req->validated());
    }

    public function testEmailMustBeValid(): void
    {
        $body = [
            'email' => 'not-an-email',
            'password' => 'password',
        ];

        $this->expectException(ValidationException::class);

        new AuthRequest(body: $body);
    }

    public function testEmailIsRequired(): void
    {
        $body = [
            'password' => 'password',
        ];

        $this->expectException(ValidationException::class);

        new AuthRequest(body: $body);
    }
}