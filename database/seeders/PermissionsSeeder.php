<?php

namespace AlexRoden\LibraryApiPhp\Database\Seeders;

use AlexRoden\LibraryApiPhp\Enums\Permissions;
use AlexRoden\LibraryApiPhp\Enums\Roles;
use AlexRoden\LibraryApiPhp\Models\Permission;

class PermissionsSeeder extends AbstractSeeder
{
    public function run(): void
    {
        $model = new Permission();
        foreach (Permissions::getConstants() as $permission) {
            if ($model->where('name', '=', $permission)->first()) {
                echo "Permission {$permission} already exists.\n";
                continue;
            }

            $model->create([
                'name' => $permission,
            ]);

            echo "Role {$permission} created.\n";
        }
    }
}
