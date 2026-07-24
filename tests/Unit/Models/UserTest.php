<?php

namespace AlexRoden\LibraryApiPhp\Tests\Unit\Models;

use AlexRoden\LibraryApiPhp\Enums\Permissions;
use AlexRoden\LibraryApiPhp\Enums\Roles;
use AlexRoden\LibraryApiPhp\Models\Permission;
use AlexRoden\LibraryApiPhp\Models\Role;
use AlexRoden\LibraryApiPhp\Models\User;
use AlexRoden\LibraryApiPhp\Tests\AbstractTestCase;
use AlexRoden\LibraryApiPhp\Tests\Factories\UserFactory;

class UserTest extends AbstractTestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::create();
    }

    public function testCreate(): void
    {
        $user = User::create([
            'email' => 'john.smith@example.com',
            'password' => 'password',
            'first_name' => 'John',
            'last_name' => 'Smith',
        ]);

        $this->assertNotNull($user->id);
    }

    public function testGet(): void
    {
        $users = User::where('id', '=', $this->user->id)->get();

        $this->assertCount(1, $users);
    }

    public function testDelete(): void
    {
        $this->user->delete();

        $user = User::where('id', '=', $this->user->id)->first();

        $this->assertNull($user);
    }

    public function testFirst(): void
    {
        $user = User::where('id', '=', $this->user->id)->first();

        $this->assertEquals($this->user->id, $user->id);
    }

    public function testHasRoles(): void
    {
        $role = Role::create(['name' => Roles::ADMIN]);

        $this->user->assignRole($role);

        $this->assertTrue($this->user->hasRole(Roles::ADMIN));
    }

    public function testHasPermissions(): void
    {
        $role = Role::create(['name' => Roles::ADMIN]);
        $permission = Permission::create(['name' => Permissions::USERS_CREATE]);
        $role->assignPermission($permission);

        $this->user->assignRole($role);

        $this->assertTrue($this->user->hasPermission(Permissions::USERS_CREATE));
    }

    public function testRoles(): void
    {
        $role = Role::create(['name' => Roles::ADMIN]);

        $this->user->assignRole($role);

        $roles = $this->user->roles();
        $this->assertCount(1, $roles);
        $this->assertEquals(Roles::ADMIN, $roles[0]->name);
    }

    public function testPermissions(): void
    {
        $role = Role::create(['name' => Roles::ADMIN]);
        $permission = Permission::create(['name' => Permissions::USERS_CREATE]);
        $role->assignPermission($permission);

        $this->user->assignRole($role);

        $permissions = $this->user->permissions();
        $this->assertCount(1, $permissions);
        $this->assertEquals(Permissions::USERS_CREATE, $permissions[0]);
    }

    public function testUnassignRole(): void
    {
        $role = Role::create(['name' => Roles::ADMIN]);

        $this->user->assignRole($role);
        $this->assertTrue($this->user->hasRole(Roles::ADMIN));

        $this->user->unassignRole($role);
        $this->assertFalse($this->user->hasRole(Roles::ADMIN));
    }

    public function testUpdate(): void
    {
        $email = 'john.smith@example.com';
        $firstName = 'John';
        $lastName = 'Smith';

        $this->user->update([
            'email' => $email,
            'first_name' => $firstName,
            'last_name' => $lastName,
        ]);

        $user = $this->user->refresh();

        $this->assertSame([
            'email' => $email,
            'first_name' => $firstName,
            'last_name' => $lastName,
        ], [
            'email' => $user->email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
        ]);
    }
}