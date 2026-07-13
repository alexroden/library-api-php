<?php

namespace Tests\Unit\Models;

use App\Database\Query;
use App\Models\User;
use Tests\AbstractDatabaseTestCase;
use Tests\Factories\UserFactory;

class UserTest extends AbstractDatabaseTestCase
{
    public function testCreate(): void
    {
        $model = new User($this->query);

        $id = $model->create([
            'email' => 'john.smith@example.com',
            'password' => 'password',
            'first_name' => 'John',
            'last_name' => 'Smith',
        ]);

        $this->assertSame(1, $id);
    }

    public function testFirst(): void
    {
        $expected = new UserFactory($this->query)->create();

        $model = new User($this->query);
        $user = $model->first(['id' => 1]);

        $this->assertEquals($expected->id, $user->id);

    }
}