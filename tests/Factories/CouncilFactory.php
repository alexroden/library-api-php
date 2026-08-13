<?php

namespace AlexRoden\LibraryApiPhp\Tests\Factories;

use AlexRoden\LibraryApiPhp\Models\Council;

class CouncilFactory extends Factory
{
    protected function model(): Council
    {
        return new Council();
    }

    protected function definition(): array
    {
        return [
            'name' => $this->faker->city(),
        ];
    }
}
