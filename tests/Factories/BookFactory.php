<?php

namespace AlexRoden\LibraryApiPhp\Tests\Factories;

use AlexRoden\LibraryApiPhp\Models\Book;

class BookFactory extends Factory
{
    protected function model(): Book
    {
        return new Book();
    }

    protected function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'tags' => implode(',', $this->faker->words(2)),
            'published_at' => $this->faker->date(),
        ];
    }
}
