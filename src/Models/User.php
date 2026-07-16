<?php

namespace AlexRoden\LibraryApiPhp\Models;

use AlexRoden\LibraryApiPhp\Exceptions\ResourceNotFoundException;

/**
 * @extends AbstractModel<User>
 */
class User extends AbstractModel
{
    protected string $table = 'users';
    protected array $hidden = ['password'];

    protected array $fillable = [
        'email',
        'password',
        'first_name',
        'last_name',
    ];

    public function assignRole(Role|string $role): void
    {
        if (is_string($role)) {
            $model = new Role();
            $role = $model->where('name', '=', $role)->first();
            if (!$role) {
                throw ResourceNotFoundException::resource("Role - {$role}");
            }
        }

        if (
            !$this->DB(
                'user_roles',
                null,
                ['user_id', 'role_id'],
            )->where(
                'user_id',
                '=',
                $this->id,
            )->where(
                'role_id',
                '=',
                $role->id,
            )->first()
        ) {
            $this->DB(
                'user_roles',
                null,
                ['user_id', 'role_id'],
            )->insert([
                'user_id' => $this->id,
                'role_id' => $role->id,
            ]);
        }
    }

    public function create(array $attributes): AbstractModel
    {
        if (isset($attributes['password'])) {
            $attributes['password'] = password_hash(
                $attributes['password'],
                PASSWORD_ARGON2ID
            );
        }

        return parent::create($attributes);
    }

    public function authenticate(string $password): bool
    {
        return password_verify($password, $this->password);
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