<?php

namespace Tests\Unit\Database;

use App\Database\Query;
use App\Models\User;
use Tests\AbstractTestCase;
use Tests\Factories\UserFactory;

class QueryTest extends AbstractTestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = (new UserFactory($this->query))->create();
    }

    public function testFirst(): void
    {
        $query = new Query($this->db, 'users', User::class, ['email', 'password', 'first_name', 'last_name']);

        $user = $query->where('id', '=', $this->user->id)->first();

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($this->user->id, $user->id);
    }

    public function testGet(): void
    {
        $query = new Query($this->db, 'users', User::class, ['email', 'password', 'first_name', 'last_name']);

        $users = $query->where('id', '=', $this->user->id)->orWhere('first_name', '=', $this->user->first_name)->get();

        $this->assertIsArray($users);
        $this->assertCount(1, $users);
    }

    public function testInsert(): void
    {
        $query = new Query(
            $this->db,
            'users',
            User::class,
            ['email', 'password', 'first_name', 'last_name'],
        );

        $id = $query->insert([
            'email' => 'user@example.com',
            'password' => 'password',
            'first_name' => 'John',
            'last_name' => 'Doe'
        ]);

        $this->assertNotNull($id);
    }

    public function testUpdate(): void
    {
        $query = new Query(
            $this->db,
            'users',
            User::class,
            ['email', 'password', 'first_name', 'last_name'],
        );

        $email = 'user@example.com';
        $query->where('id', '=', $this->user->id)->update([
            'email' => $email,
        ]);

        $user = $query->where('id', '=', $this->user->id)->first();

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($email, $user->email);
    }
}