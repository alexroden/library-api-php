<?php

namespace Database\Seeders;

use App\Enums\Roles;

class RolesSeeder extends AbstractSeeder
{
    public function run(): void
    {
        foreach (Roles::getConstants() as $role) {
            $exists = $this->db->prepare(
                'SELECT id FROM roles WHERE name = ?'
            );

            $exists->execute([
                $_ENV['SUPER_ADMIN_EMAIL'],
            ]);

            if ($exists->fetch()) {
                echo "Role {$role} already exists.\n";
                return;
            }

            $stmt = $this->db->prepare(
                'INSERT INTO roles (name)
                 VALUES (?)'
            );

            $stmt->execute([
                $role
            ]);

            echo "Role {$role} created.\n";
        }
    }
}
