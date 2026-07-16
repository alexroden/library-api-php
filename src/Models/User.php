<?php

namespace AlexRoden\LibraryApiPhp\Models;

class User extends AbstractModel
{
    protected string $table = 'users';

    protected array $fillable = [
        'email',
        'password',
        'first_name',
        'last_name',
    ];

    public function attachRole(Role $role): void
    {
        $this->DB(
            'user_roles',
            null,
            ['user_id', 'role_id'],
        )->insert([
            'user_id' => $this->id,
            'role_id' => $role->id,
        ]);
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, array_map(fn (Role $role) => $role->name, $this->roles()));
    }

    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->permissions());
    }

    public function roles(): array
    {
        return $this->DB(
            'user_roles',
            Role::class,
        )->where(
            'user_id',
            '=',
            $this->id,
        )->join(
            'roles',
            'role_id',
            'id',
            ['id', 'name', 'created_at', 'updated_at'],
        )->excludeLocalAttributes()->get();
    }

    public function permissions(): array
    {
        return array_map(fn (array$row) => $row['name'], $this->DB(
            'user_roles',
        )->where(
            'user_id',
            '=',
            $this->id,
        )->join(
            'roles',
            'role_id',
            'id',
        )->join(
            'role_permissions',
            'id',
            'role_id',
        )->join(
            'permissions',
            'role_permissions.permission_id',
            'id',
            ['name'],
        )->excludeLocalAttributes()->get(
            null,
            null,
            true,
        ));
    }
}