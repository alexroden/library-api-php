<?php

namespace Database\Seeders;

use App\Config\Config;
use App\Enums\Permissions;
use App\Enums\Roles;
use App\Exceptions\NotFountException;

class RolesSeeder extends AbstractSeeder
{
    /**
     * @throws NotFountException
     */
    public function run(): void
    {
        foreach (Roles::getConstants() as $role) {
            $exists = $this->db->prepare(
                'SELECT id FROM roles WHERE name = ?'
            );
            $exists->execute([$role]);

            if ($row = $exists->fetch()) {
                echo "Role {$role} already exists.\n";
                $this->linkPermissions($row['id'], $role);

                continue;
            }

            $this->db->prepare(
                'INSERT INTO roles (name)
                 VALUES (?)'
            )->execute([$role]);

            $roleId = (int) $this->db->lastInsertId();
            $this->linkPermissions($roleId, $role);

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

