<?php

namespace AlexRoden\LibraryApiPhp\Models;

use AlexRoden\LibraryApiPhp\Exceptions\ResourceNotFoundException;
use AlexRoden\LibraryApiPhp\Exceptions\UndefinedClassException;

/**
 * @extends AbstractModel<Role>
 */
class Role extends AbstractModel
{
    protected string $table = 'roles';

    protected array $fillable = [
        'name',
    ];

    /**
     * @throws UndefinedClassException
     * @throws ResourceNotFoundException
     */
    public function assignPermission(Permission|string $permission): void
    {
        $permission = $this->getPermission($permission);

        if (
            !$this->DB(
                'role_permissions',
                null,
                ['role_id', 'permission_id'],
            )->where(
                'role_id',
                '=',
                $this->id,
            )->where(
                'permission_id',
                '=',
                $permission->id,
            )->first()
        ) {
            $this->DB(
                'role_permissions',
                null,
                ['role_id', 'permission_id'],
            )->insert([
                'role_id' => $this->id,
                'permission_id' => $permission->id,
            ]);
        }
    }

    public function permissions(): array
    {
        return array_map(fn(array $row) => $row['name'], $this->DB(
            'role_permissions',
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
            excludeModelMapping: true,
        ));
    }

    public function unassignPermission(Permission|string $permission): void
    {
        $this->DB(
            'role_permissions',
        )->where(
            'role_id',
            '=',
            $this->id,
        )->where(
            'permission_id',
            '=',
            $this->getPermission($permission)->id,
        )->delete();
    }

    /**
     * @throws UndefinedClassException
     * @throws ResourceNotFoundException
     */
    protected function getPermission(Permission|string $permission): Permission
    {
        if (is_string($permission)) {
            $model = new Permission();
            $permission = $model->where('name', '=', $permission)->first();
            if (!$permission) {
                throw ResourceNotFoundException::resource("Permission - {$permission}");
            }
        }

        return $permission;
    }
}
