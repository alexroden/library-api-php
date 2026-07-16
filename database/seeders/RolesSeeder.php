<?php

namespace AlexRoden\LibraryApiPhp\Database\Seeders;

use AlexRoden\LibraryApiPhp\Config\Config;
use AlexRoden\LibraryApiPhp\Enums\Permissions;
use AlexRoden\LibraryApiPhp\Enums\Roles;
use AlexRoden\LibraryApiPhp\Exceptions\ResourceNotFoundException;
use AlexRoden\LibraryApiPhp\Exceptions\UndefinedClassException;
use AlexRoden\LibraryApiPhp\Models\Permission;
use AlexRoden\LibraryApiPhp\Models\Role;

class RolesSeeder extends AbstractSeeder
{
    /**
     * @throws ResourceNotFoundException
     */
    public function run(): void
    {
        $model = new Role();

        foreach (Roles::getConstants() as $role) {
            if ($row = $model->where('name', '=', $role)->first()) {
                echo "Role {$role} already exists.\n";
                $this->linkPermissions($row);

                continue;
            }

            $row = $model->create(['name' => $role]);
            $this->linkPermissions($row);

            echo "Role {$role} created.\n";
        }
    }

    /**
     * @throws ResourceNotFoundException|UndefinedClassException
     */
    private function linkPermissions(Role $role): void
    {
        foreach (Config::get("role-permissions.{$role->name}") as $permission) {
            $role->assignPermission($permission);
            echo "Permission {$permission} for {$role->name} created.\n";
        }
    }
}

