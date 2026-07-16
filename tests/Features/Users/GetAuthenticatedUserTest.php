<?php

namespace AlexRoden\LibraryApiPhp\Tests\Features\Users;

use AlexRoden\LibraryApiPhp\Http\Controllers\UserController;
use AlexRoden\LibraryApiPhp\Http\JsonResponse;
use AlexRoden\LibraryApiPhp\Http\Requests\AuthRequest;
use AlexRoden\LibraryApiPhp\Models\User;
use AlexRoden\LibraryApiPhp\Tests\AbstractTestCase;
use AlexRoden\LibraryApiPhp\Tests\Factories\UserFactory;

class GetAuthenticatedUserTest extends AbstractTestCase
{
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = new UserFactory()->create();
    }

    public function testGetAuthenticatedUser(): void
    {
        $request = $this->createMock(AuthRequest::class);
        $request
            ->method('getUser')
            ->willReturn($this->user);

        $controller = new UserController();
        $resp = $controller->getAuthenticatedUser($request);

        $this->assertJson($this->user->toJson(), json_encode($resp->data));
    }
}