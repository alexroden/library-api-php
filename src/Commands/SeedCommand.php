<?php

namespace AlexRoden\LibraryApiPhp\Commands;

use AlexRoden\LibraryApiPhp\Database\Seeders\PermissionsSeeder;
use AlexRoden\LibraryApiPhp\Database\Seeders\RolesSeeder;
use AlexRoden\LibraryApiPhp\Database\Seeders\SuperAdminSeeder;
use PDO;

class SeedCommand
{
    private array $seeders = [
        PermissionsSeeder::class,
        RolesSeeder::class,
        SuperAdminSeeder::class,
    ];

    public function run(): void
    {
        foreach ($this->seeders as $seeder) {
            echo "Running {$seeder}...\n";

            (new $seeder())->run();
        }

        echo "Seeding complete.\n";
    }
}