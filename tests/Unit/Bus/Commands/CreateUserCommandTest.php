<?php

namespace AlexRoden\LibraryApiPhp\Tests\Unit\Bus\Commands;

use AlexRoden\LibraryApiPhp\Bus\Commands\CreateUserCommand;
use AlexRoden\LibraryApiPhp\Bus\EventBus;
use AlexRoden\LibraryApiPhp\Bus\Handlers\CreateUserCommandHandler;
use AlexRoden\LibraryApiPhp\Enums\Roles;
use AlexRoden\LibraryApiPhp\Models\Role;
use AlexRoden\LibraryApiPhp\Models\User;
use AlexRoden\LibraryApiPhp\Tests\AbstractTestCase;
use PHPUnit\Framework\MockObject\MockObject;

class CreateUserCommandTest extends AbstractTestCase
{
    private EventBus|MockObject $events;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => Roles::USER]);

        $this->events = $this->createMock(EventBus::class);
    }

    public function testUserCanBeCreated(): void
    {
        $this->events
            ->expects($this->once())
            ->method('dispatch');

        $handler = new CreateUserCommandHandler(
            $this->events
        );

        $command = new CreateUserCommand(
            email: 'test@example.com',
            password: 'password',
            firstName: 'Alex',
            lastName: 'Roden'
        );

        $user = $handler->handle($command);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertEquals('Alex', $user->first_name);
        $this->assertEquals('Roden', $user->last_name);

        $this->assertTrue(
            password_verify('password', $user->password)
        );
    }
}