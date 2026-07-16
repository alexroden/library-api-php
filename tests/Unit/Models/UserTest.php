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

    public function testHasRoles(): void
    {
        $model = new Role();
        $role = $model->create(['name' => Roles::ADMIN]);

        $this->user->attachRole($role);

        $this->assertTrue($this->user->hasRole(Roles::ADMIN));
    }

    public function testHasPermissions(): void
    {
        $model = new Role();
        $role = $model->create(['name' => Roles::ADMIN]);
        $perm = new Permission();
        $permission = $perm->create(['name' => Permissions::USERS_CREATE]);
        $role->attachPermission($permission);

        $this->user->attachRole($role);

        $this->assertTrue($this->user->hasPermission(Permissions::USERS_CREATE));
    }

    public function testRoles(): void
    {
        $model = new Role();
        $role = $model->create(['name' => Roles::ADMIN]);

        $this->user->attachRole($role);

        $roles = $this->user->roles();
        $this->assertCount(1, $roles);
        $this->assertEquals(Roles::ADMIN, $roles[0]->name);
    }

    public function testPermissions(): void
    {
        $model = new Role();
        $role = $model->create(['name' => Roles::ADMIN]);
        $perm = new Permission();
        $permission = $perm->create(['name' => Permissions::USERS_CREATE]);
        $role->attachPermission($permission);

        $this->user->attachRole($role);

        $permissions = $this->user->permissions();
        $this->assertCount(1, $permissions);
        $this->assertEquals(Permissions::USERS_CREATE, $permissions[0]);
    }


}