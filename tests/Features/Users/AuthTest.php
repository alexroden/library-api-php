<?php

namespace AlexRoden\LibraryApiPhp\Tests\Features\Users;

use AlexRoden\LibraryApiPhp\Http\Controllers\UserController;
use AlexRoden\LibraryApiPhp\Http\Requests\AuthRequest;
use AlexRoden\LibraryApiPhp\Models\User;
use AlexRoden\LibraryApiPhp\Tests\AbstractTestCase;
use AlexRoden\LibraryApiPhp\Tests\Factories\UserFactory;

class AuthTest extends AbstractTestCase
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
        $request = $this->createMock(AuthRequest::class);
        $request
            ->method('input')
            ->willReturnMap([
                ['email', null, $this->user->email],
                ['password', null, $this->factory->plainPassword],
            ]);

        $controller = new UserController();
        $resp = $controller->auth($request);

        $this->assertJson($this->user->toJson(), json_encode($resp->data));
    }
}