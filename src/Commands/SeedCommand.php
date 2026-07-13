<?php

namespace App\Commands;

use Database\Seeders\RolesSeeder;
use PDO;

class SeedCommand
{
    public function __construct(
        private PDO $db
    ) {
    }

    public function run(): void
    {
        $seeders = [
            RolesSeeder::class,
        ];

        foreach ($seeders as $seeder) {
            echo "Running {$seeder}...\n";

            (new $seeder($this->db))->run();
        }

        echo "Seeding complete.\n";
    }
}