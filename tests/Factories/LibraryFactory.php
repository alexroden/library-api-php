<?php

namespace AlexRoden\LibraryApiPhp\Tests\Factories;

use AlexRoden\LibraryApiPhp\Models\Library;

class LibraryFactory extends Factory
{
    protected function model(): Library
    {
        return new Library();
    }

    protected function definition(): array
    {
        return [
            'name' => $this->faker->city(),
        ];
    }
}
