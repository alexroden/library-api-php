<?php

namespace AlexRoden\LibraryApiPhp\Database\Seeders;

use AlexRoden\LibraryApiPhp\Enums\Roles;
use AlexRoden\LibraryApiPhp\Exceptions\ResourceNotFoundException;
use AlexRoden\LibraryApiPhp\Models\Role;
use AlexRoden\LibraryApiPhp\Models\User;

class SuperAdminSeeder extends AbstractSeeder
{
    /**
     * @throws ResourceNotFoundException
     */
    public function run(): void
    {
        $model = new User();
        if ($model->where('email', '=', env('SUPER_ADMIN_EMAIL'))->first()) {
            echo "Super admin already exists.\n";
            return;
        }

        $name = explode('-', Roles::SUPER_ADMIN);
        $user = $model->create([
            'email' => env('SUPER_ADMIN_EMAIL'),
            'password' => env('SUPER_ADMIN_PASSWORD'),
            'first_name' => $name[0],
            'last_name' => $name[1],
        ]);

        $user->assignRole(Roles::SUPER_ADMIN);

        echo "Super admin created.\n";
    }
}
