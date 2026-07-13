<?php

namespace Database\Seeders;

use App\Enums\Roles;
use App\Exceptions\NotFountException;

class SuperAdminSeeder extends AbstractSeeder
{
    /**
     * @throws NotFountException
     */
    public function run(): void
    {
        $exists = $this->db->prepare(
            'SELECT id FROM users WHERE email = ?'
        );
        $exists->execute([
            getenv('SUPER_ADMIN_EMAIL'),
        ]);

        if ($exists->fetch()) {
            echo "Super admin already exists.\n";
            return;
        }

        $stmt = $this->db->prepare(
            'INSERT INTO users (email, password, first_name, last_name)
             VALUES (?, ?, ?, ?)'
        );

        $s = explode('-', Roles::SUPER_ADMIN);
        $stmt->execute([
            getenv('SUPER_ADMIN_EMAIL'),
            password_hash(
                getenv('SUPER_ADMIN_PASSWORD'),
                PASSWORD_ARGON2ID
            ),
            $s[0],
            $s[1],
        ]);

        $userId = (int) $this->db->lastInsertId();
        $exists = $this->db->prepare(
            'SELECT id FROM roles WHERE name = ?'
        );

        $exists->execute([Roles::SUPER_ADMIN]);
        $roleRow = $exists->fetch();
        if (!$roleRow) {
            throw NotFountException::resource('Role - '.Roles::SUPER_ADMIN);
        }

        $stmt = $this->db->prepare(
            'INSERT INTO user_roles (user_id, role_id)
             VALUES (?, ?)'
        );

        $stmt->execute([$userId, $roleRow['id']]);

        echo "Super admin created.\n";
    }
}
