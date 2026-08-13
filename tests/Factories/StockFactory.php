<?php

namespace AlexRoden\LibraryApiPhp\Tests\Factories;

use AlexRoden\LibraryApiPhp\Models\Stock;

class StockFactory extends Factory
{
    protected function model(): Stock
    {
        return new Stock();
    }

    protected function definition(): array
    {
        return [
            'library_id' => LibraryFactory::create()->id,
            'book_id' => BookFactory::create()->id,
            'quantity' => $this->faker->numberBetween(1, 100),
        ];
    }
}
