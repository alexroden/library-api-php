<?php

namespace AlexRoden\LibraryApiPhp\Database\Seeders;

use AlexRoden\LibraryApiPhp\Config\Config;
use AlexRoden\LibraryApiPhp\Enums\Permissions;
use AlexRoden\LibraryApiPhp\Enums\Roles;
use AlexRoden\LibraryApiPhp\Exceptions\NotFountException;
use AlexRoden\LibraryApiPhp\Models\Role;

class RolesSeeder extends AbstractSeeder
{
    /**
     * @throws NotFountException
     */
    public function run(): void
    {
        $model = new Role();

        foreach (Roles::getConstants() as $role) {
            if ($row = $model->where('name', '=', $role)->first()) {
                echo "Role {$role} already exists.\n";
                $this->linkPermissions($row->id, $role);

                continue;
            }

            $row = $model->create(['name' => $role]);
            $this->linkPermissions($row->id, $role);

            echo "Role {$role} created.\n";
        }
    }

    /**
     * @throws NotFountException
     */
    private function linkPermissions(int $roleId, string $role): void
    {
        foreach (Config::get("role-permissions.{$role}") as $permission) {
            $perm = $this->db->prepare(
                'SELECT id FROM permissions WHERE name = ?'
            );
            $perm->execute([$permission]);

            $row = $perm->fetch();
            if (!$row) {
                throw NotFountException::resource("Permission - {$permission}}");
            }

            $exists = $this->db->prepare(
                'SELECT id FROM role_permissions WHERE role_id = ? AND permission_id = ?'
            );
            $exists->execute([$roleId, $row['id']]);

            if ($exists->fetch()) {
                echo "Permission {$permission} for {$role} already exists.\n";
                continue;
            }

            $this->db->prepare(
                'INSERT INTO role_permissions (role_id, permission_id)
                 VALUES (?, ?)'
            )->execute([$roleId, $row['id']]);

            echo "Permission {$permission} for {$role} created.\n";
        }
    }
}

