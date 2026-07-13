<?php

namespace Database\Seeders;

use App\Enums\Permissions;
use App\Enums\Roles;

class PermissionsSeeder extends AbstractSeeder
{
    public function run(): void
    {
        foreach (Permissions::getConstants() as $permission) {
            $exists = $this->db->prepare(
                'SELECT id FROM permissions WHERE name = ?'
            );

            $exists->execute([
                $permission,
            ]);

            if ($exists->fetch()) {
                echo "Permission {$permission} already exists.\n";
                continue;
            }

            $stmt = $this->db->prepare(
                'INSERT INTO permissions (name)
                 VALUES (?)'
            );

            $stmt->execute([
                $permission
            ]);

            echo "Role {$permission} created.\n";
        }
    }
}
