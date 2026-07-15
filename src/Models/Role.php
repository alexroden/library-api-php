<?php

namespace AlexRoden\LibraryApiPhp\Models;

class Role extends AbstractModel
{
    protected string $table = 'roles';

    protected array $fillable = [
        'name',
    ];

    public function attachPermission(Permission $permission): void
    {
        $this->DB(
            'role_permissions',
            null,
            ['role_id', 'permission_id'],
        )->insert([
            'role_id' => $this->id,
            'permission_id' => $permission->id,
        ]);
    }

    public function permissions(): array
    {
        return array_map(fn(array $row) => $row['name'], $this->DB(
            'role_permissions',
            null,
            null,
        )->where(
            'role_id',
            '=',
            $this->id,
        )->join(
            'permissions',
            'permission_id',
            'id',
            ['name'],
        )->excludeLocalAttributes()->get(
            null,
            null,
            true,
        ));
    }
}