<?php

namespace AlexRoden\LibraryApiPhp\Tests\Unit\Models;

use AlexRoden\LibraryApiPhp\Enums\Permissions;
use AlexRoden\LibraryApiPhp\Enums\Roles;
use AlexRoden\LibraryApiPhp\Models\Permission;
use AlexRoden\LibraryApiPhp\Models\Role;
use AlexRoden\LibraryApiPhp\Models\User;
use AlexRoden\LibraryApiPhp\Tests\AbstractTestCase;
use AlexRoden\LibraryApiPhp\Tests\Factories\RoleFactory;

class RoleTest extends AbstractTestCase
{
    private Role $role;

    protected function setUp(): void
    {
        parent::setUp();

        $this->role = RoleFactory::create();
    }

    public function testCreate(): void
    {
        $role = Role::create([
            'name' => Roles::ADMIN,
        ]);

        $this->assertNotNull($role->id);
    }

    public function testGet(): void
    {
        $roles = Role::where('id', '=', $this->role->id)->get();

        $this->assertCount(1, $roles);
    }

    public function testDelete(): void
    {
        $this->role->delete();

        $role = Role::where('id', '=', $this->role->id)->first();

        $this->assertNull($role);
    }

    public function testFirst(): void
    {
        $role = Role::where('id', '=', $this->role->id)->first();

        $this->assertEquals($this->role->id, $role->id);
    }

    public function testPermissions(): void
    {
        $permission = Permission::create(['name' => Permissions::USERS_CREATE]);

        $this->role->assignPermission($permission);

        $permissions = $this->role->permissions();
        $this->assertCount(1, $permissions);
        $this->assertEquals(Permissions::USERS_CREATE, $permissions[0]);
    }

    public function testUnassignPermissions(): void
    {
        $permission = Permission::create(['name' => Permissions::USERS_CREATE]);

        $this->role->assignPermission($permission);

        $permissions = $this->role->permissions();
        $this->assertCount(1, $permissions);
        $this->assertEquals(Permissions::USERS_CREATE, $permissions[0]);

        $this->role->unassignPermission($permission);

        $permissions = $this->role->permissions();
        $this->assertEmpty($permissions);
    }

    public function testUpdate(): void
    {
        $name = Roles::SUPER_ADMIN;

        $this->role->update([
            'name' => $name,
        ]);

        $role = $this->role->refresh();

        $this->assertSame([
            'name' => $name,
        ], [
            'name' => $role->name,
        ]);
    }
}