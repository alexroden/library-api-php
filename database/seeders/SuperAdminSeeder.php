<?php

namespace Database\Seeders;

use App\Enums\Roles;
use App\Exceptions\NotFountException;
use App\Models\User;

class SuperAdminSeeder extends AbstractSeeder
{
    /**
     * @throws NotFountException
     */
    public function run(): void
    {
        $srv = new User();
        if ($srv->where('email', '=', getenv('SUPER_ADMIN_EMAIL'))->first()) {
            echo "Super admin already exists.\n";
            return;
        }

        $name = explode('-', Roles::SUPER_ADMIN);
        $user = $srv->create([
            'email' => getenv('SUPER_ADMIN_EMAIL'),
            'password' => password_hash(getenv('SUPER_ADMIN_PASSWORD'), PASSWORD_ARGON2ID),
            'first_name' => $name[0],
            'last_name' => $name[1],
        ]);

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

        $stmt->execute([$user->id, $roleRow['id']]);

        echo "Super admin created.\n";
    }
}
