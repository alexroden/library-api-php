<?php

namespace AlexRoden\LibraryApiPhp\Tests\Features\Users;

use AlexRoden\LibraryApiPhp\Bus\CommandBus;
use AlexRoden\LibraryApiPhp\Bus\Commands\CreateUserCommand;
use AlexRoden\LibraryApiPhp\Http\Controllers\UserController;
use AlexRoden\LibraryApiPhp\Http\Requests\CreateUserRequest;
use AlexRoden\LibraryApiPhp\Tests\AbstractTestCase;
use AlexRoden\LibraryApiPhp\Tests\Factories\UserFactory;
use PHPUnit\Framework\MockObject\MockObject;

class CreateTest extends AbstractTestCase
{
    private CommandBus|MockObject $commandBus;
    protected function setUp(): void
    {
        parent::setUp();

        $this->commandBus = $this->createMock(CommandBus::class);
    }

    public function testCreateUser(): void
    {
        $user = new UserFactory()->create();

        $this->commandBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(function ($command) {
                return $command instanceof CreateUserCommand
                    && $command->email === 'test@example.com';
            }))
            ->willReturn($user);

        $request = $this->createMock(CreateUserRequest::class);

        $request
            ->method('validated')
            ->willReturn([
                'email' => 'test@example.com',
                'password' => 'password',
                'firstName' => 'Test',
                'lastName' => 'User',
                'passwordConfirmation' => 'password',
            ]);

        $controller = new UserController(
            $this->commandBus
        );

        $response = $controller->create($request);

        $this->assertSame(
            $user,
            $response->data['data']
        );
    }
}