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

    public function roles(): array
    {
        return $this->DB(
            'user_roles',
            Role::class,
            null,
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

    public function hasRole($role): bool
    {
        return in_array($role, array_map(fn (Role $role) => $role->name, $this->roles()));
    }
}