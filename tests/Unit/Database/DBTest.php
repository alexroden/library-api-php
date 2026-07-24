<?php

namespace AlexRoden\LibraryApiPhp\Tests\Unit\Database;

use AlexRoden\LibraryApiPhp\Database\DB;
use AlexRoden\LibraryApiPhp\Enums\Permissions;
use AlexRoden\LibraryApiPhp\Enums\Roles;
use AlexRoden\LibraryApiPhp\Exceptions\UndefinedClassException;
use AlexRoden\LibraryApiPhp\Models\Permission;
use AlexRoden\LibraryApiPhp\Models\Role;
use AlexRoden\LibraryApiPhp\Models\User;
use AlexRoden\LibraryApiPhp\Tests\AbstractTestCase;
use AlexRoden\LibraryApiPhp\Tests\Factories\UserFactory;

class DBTest extends AbstractTestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::create();
    }

    public function testDelete(): void
    {
        $db = new DB(
            'users',
            User::class,
            ['email', 'password', 'first_name', 'last_name'],
        );

        $user = $db->where('id', '=', $this->user->id)->first();
        $this->assertInstanceOf(User::class, $user);

        $db->where('id', '=', $user->id)->delete();

        $user = $db->where('id', '=', $this->user->id)->first();
        $this->assertNull($user);
    }

    public function testFirst(): void
    {
        $db = new DB(
            'users',
            User::class,
            ['email', 'password', 'first_name', 'last_name'],
        );

        $user = $db->where('id', '=', $this->user->id)->first();

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($this->user->id, $user->id);
    }

    public function testGet(): void
    {
        $db = new DB(
            'users',
            User::class,
            ['email', 'password', 'first_name', 'last_name'],
        );

        $users = $db->where('id', '=', $this->user->id)->orWhere('first_name', '=', $this->user->first_name)->get();

        $this->assertIsArray($users);
        $this->assertCount(1, $users);
    }

    public function testInsert(): void
    {
        $db = new DB(
            'users',
            User::class,
            ['email', 'password', 'first_name', 'last_name'],
        );

        $id = $db->insert([
            'email' => 'user@example.com',
            'password' => 'password',
            'first_name' => 'John',
            'last_name' => 'Doe'
        ]);

        $this->assertNotNull($id);
    }

    public function testUpdate(): void
    {
        $db = new DB(
            'users',
            User::class,
            ['email', 'password', 'first_name', 'last_name'],
        );

        $email = 'user@example.com';
        $db->update($this->user->id, [
            'email' => $email,
        ]);

        $user = $db->where('id', '=', $this->user->id)->first();

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($email, $user->email);
    }

    public function testJoin(): void
    {
        $roleId = new DB(
            'roles',
            Role::class,
            ['name']
        )->insert(['name' => Roles::ADMIN]);

        $permissionId = new DB(
            'permissions',
            Permission::class,
            ['name']
        )->insert(['name' => Permissions::USERS_CREATE]);

        new DB(
            'role_permissions',
            null,
            ['role_id', 'permission_id'],
        )->insert([
            'role_id' => $roleId,
            'permission_id' => $permissionId,
        ]);

        $perm = new DB(
            'roles',
            Role::class,
            ['name'],
        )->where(
            'name',
            '=',
            Roles::ADMIN,
        )->join(
            'role_permissions',
            'id',
            'role_id',
            null,
        )->join(
            'permissions',
            'role_permissions.id',
            'id',
            ['name'],
        )->excludeLocalAttributes()->get();

        $this->assertCount(1, $perm);
    }
}