<?php

namespace AlexRoden\LibraryApiPhp\Tests\Factories;

use AlexRoden\LibraryApiPhp\Models\Role;

class RoleFactory extends Factory
{
    protected function model(): Role
    {
        return new Role();
    }

    protected function definition(): array
    {
        return [
            'name' => $this->faker->word,
        ];
    }
}