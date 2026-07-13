<?php

namespace Database\Seeders;

class SuperAdminSeeder extends AbstractSeeder
{
    public function run(): void
    {
        $exists = $this->db->prepare(
            'SELECT id FROM users WHERE email = ?'
        );

        $exists->execute([
            $_ENV['SUPER_ADMIN_EMAIL'],
        ]);

        if ($exists->fetch()) {
            echo "Super admin already exists.\n";
            return;
        }

        $stmt = $this->db->prepare(
            'INSERT INTO users (email, password, first_name, last_name)
             VALUES (?, ?, ?, ?)'
        );

        $stmt->execute([
            $_ENV['SUPER_ADMIN_EMAIL'],
            password_hash(
                $_ENV['SUPER_ADMIN_PASSWORD'],
                PASSWORD_ARGON2ID
            ),
            'super',
            'admin',
        ]);

        echo "Super admin created.\n";
    }
}
