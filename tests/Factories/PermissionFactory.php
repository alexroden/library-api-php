<?php

namespace AlexRoden\LibraryApiPhp\Tests\Factories;

use AlexRoden\LibraryApiPhp\Models\Permission;

class PermissionFactory extends Factory
{
    protected function model(): Permission
    {
        return new Permission();
    }

    protected function definition(): array
    {
        return [
            'name' => $this->faker->word,
        ];
    }
}