<?php

namespace AlexRoden\LibraryApiPhp\Tests\Unit\Models;

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

        $this->role = new RoleFactory()->create();
    }

    public function testGet(): void
    {
        $model = new Role();
        $roles = $model->where('id', '=', $this->role->id)->get();

        $this->assertCount(1, $roles);
    }

    public function testFirst(): void
    {
        $model = new Role();
        $role = $model->where('id', '=', $this->role->id)->first();

        $this->assertEquals($this->role->id, $role->id);
    }

    public function testCreate(): void
    {
        $model = new Role();

        $role = $model->create([
            'name' => 'admin',
        ]);

        $this->assertNotNull($role->id);
    }
}