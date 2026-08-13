<?php

namespace AlexRoden\LibraryApiPhp\Tests\Factories;

use AlexRoden\LibraryApiPhp\Models\Category;

class CategoryFactory extends Factory
{
    protected function model(): Category
    {
        return new Category();
    }

    protected function definition(): array
    {
        return [
            'name' => $this->faker->city(),
        ];
    }
}
