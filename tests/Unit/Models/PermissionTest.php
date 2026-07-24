<?php

namespace AlexRoden\LibraryApiPhp\Tests\Unit\Models;

use AlexRoden\LibraryApiPhp\Enums\Permissions;
use AlexRoden\LibraryApiPhp\Models\Permission;
use AlexRoden\LibraryApiPhp\Tests\AbstractTestCase;
use AlexRoden\LibraryApiPhp\Tests\Factories\PermissionFactory;

class PermissionTest extends AbstractTestCase
{
    private Permission $permission;

    protected function setUp(): void
    {
        parent::setUp();

        $this->permission = PermissionFactory::create();
    }

    public function testCreate(): void
    {
        $permission = Permission::create([
            'name' => Permissions::USERS_CREATE,
        ]);

        $this->assertNotNull($permission->id);
    }

    public function testDelete(): void
    {
        $this->permission->delete();

        $permission = Permission::where('id', '=', $this->permission->id)->first();

        $this->assertNull($permission);
    }

    public function testGet(): void
    {
        $permissions = Permission::where('id', '=', $this->permission->id)->get();

        $this->assertCount(1, $permissions);
    }

    public function testFirst(): void
    {
        $permission = Permission::where('id', '=', $this->permission->id)->first();

        $this->assertEquals($this->permission->id, $permission->id);
    }

    public function testUpdate(): void
    {
        $name = Permissions::USERS_DELETE;

        $this->permission->update([
            'name' => $name,
        ]);

        $permission = $this->permission->refresh();

        $this->assertSame([
            'name' => $name,
        ], [
            'name' => $permission->name,
        ]);
    }
}