<?php

namespace AlexRoden\LibraryApiPhp\Tests\Unit\Models;

use AlexRoden\LibraryApiPhp\Models\User;
use AlexRoden\LibraryApiPhp\Tests\AbstractTestCase;
use AlexRoden\LibraryApiPhp\Tests\Factories\UserFactory;

class UserTest extends AbstractTestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = new UserFactory()->create();
    }

    public function testGet(): void
    {
        $model = new User();
        $users = $model->where('id', '=', $this->user->id)->get();

        $this->assertCount(1, $users);
    }

    public function testFirst(): void
    {
        $model = new User();
        $user = $model->where('id', '=', $this->user->id)->first();

        $this->assertEquals($this->user->id, $user->id);
    }

    public function testCreate(): void
    {
        $model = new User();

        $user = $model->create([
            'email' => 'john.smith@example.com',
            'password' => 'password',
            'first_name' => 'John',
            'last_name' => 'Smith',
        ]);

        $this->assertNotNull($user->id);
    }
}