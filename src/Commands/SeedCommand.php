<?php

namespace App\Commands;

use App\Config\Config;
use Database\Seeders\PermissionsSeeder;
use Database\Seeders\RolesSeeder;
use Database\Seeders\SuperAdminSeeder;
use PDO;

class SeedCommand
{
    private array $seeders = [
        RolesSeeder::class,
        PermissionsSeeder::class,
        SuperAdminSeeder::class,
    ];

    public function __construct(
        private readonly PDO $db,
    ) {
    }

    public function run(): void
    {
        foreach ($this->seeders as $seeder) {
            echo "Running {$seeder}...\n";

            (new $seeder($this->db))->run();
        }

        echo "Seeding complete.\n";
    }
}