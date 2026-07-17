<?php

namespace AlexRoden\LibraryApiPhp\Tests\Unit\Models;

use AlexRoden\LibraryApiPhp\Models\Permission;
use AlexRoden\LibraryApiPhp\Tests\AbstractTestCase;
use AlexRoden\LibraryApiPhp\Tests\Factories\PermissionFactory;

class PermissionTest extends AbstractTestCase
{
    private Permission $permission;

    protected function setUp(): void
    {
        parent::setUp();

        $this->permission = new PermissionFactory()->create();
    }

    public function testGet(): void
    {
        $model = new Permission();
        $permissions = $model->where('id', '=', $this->permission->id)->get();

        $this->assertCount(1, $permissions);
    }

    public function testFirst(): void
    {
        $model = new Permission();
        $permission = $model->where('id', '=', $this->permission->id)->first();

        $this->assertEquals($this->permission->id, $permission->id);
    }

    public function testCreate(): void
    {
        $model = new Permission();

        $permission = $model->create([
            'name' => 'admin',
        ]);

        $this->assertNotNull($permission->id);
    }
}