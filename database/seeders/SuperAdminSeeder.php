<?php

namespace AlexRoden\LibraryApiPhp\Database\Seeders;

use AlexRoden\LibraryApiPhp\Enums\Roles;
use AlexRoden\LibraryApiPhp\Exceptions\NotFountException;
use AlexRoden\LibraryApiPhp\Models\Role;
use AlexRoden\LibraryApiPhp\Models\User;

class SuperAdminSeeder extends AbstractSeeder
{
    /**
     * @throws NotFountException
     */
    public function run(): void
    {
        $model = new User();
        if ($model->where('email', '=', getenv('SUPER_ADMIN_EMAIL'))->first()) {
            echo "Super admin already exists.\n";
            return;
        }

        $name = explode('-', Roles::SUPER_ADMIN);
        $user = $model->create([
            'email' => getenv('SUPER_ADMIN_EMAIL'),
            'password' => password_hash(getenv('SUPER_ADMIN_PASSWORD'), PASSWORD_ARGON2ID),
            'first_name' => $name[0],
            'last_name' => $name[1],
        ]);

        $role = new Role();
        $row = $role->where('name', '=', Roles::SUPER_ADMIN)->first();
        if (!$row) {
            throw NotFountException::resource('Role - '.Roles::SUPER_ADMIN);
        }

        $stmt = $this->db->prepare(
            'INSERT INTO user_roles (user_id, role_id)
             VALUES (?, ?)'
        );

        $stmt->execute([$user->id, $row->id]);

        echo "Super admin created.\n";
    }
}
