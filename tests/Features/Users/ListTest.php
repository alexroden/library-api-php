<?php

namespace AlexRoden\LibraryApiPhp\Tests\Features\Users;

use AlexRoden\LibraryApiPhp\Bus\CommandBus;
use AlexRoden\LibraryApiPhp\Http\Controllers\UserController;
use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Http\Requests\AuthRequest;
use AlexRoden\LibraryApiPhp\Models\User;
use AlexRoden\LibraryApiPhp\Tests\AbstractTestCase;
use AlexRoden\LibraryApiPhp\Tests\Factories\UserFactory;
use PHPUnit\Framework\MockObject\MockObject;

class ListTest extends AbstractTestCase
{
    private CommandBus|MockObject $commandBus;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->commandBus = $this->createMock(CommandBus::class);
        $this->user = new UserFactory()->create();
    }

    public function testGetAuthenticatedUser(): void
    {
        $request = $this->createMock(Request::class);

        $controller = new UserController(
            $this->commandBus
        );
        $resp = $controller->list($request);

        $this->assertJson($this->user->toJson(), json_encode($resp->data));
    }
}