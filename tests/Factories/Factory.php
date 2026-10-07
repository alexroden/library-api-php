<?php

namespace AlexRoden\LibraryApiPhp\Tests\Factories;

use AlexRoden\LibraryApiPhp\Models\AbstractModel;
use Faker\Factory as FakerFactory;
use Faker\Generator;

abstract class Factory
{
    protected Generator $faker;

    public function __construct()
    {
        $this->faker = FakerFactory::create();
    }

    abstract protected function model(): AbstractModel;

    abstract protected function definition(): array;

    public static function create(array $attributes = []): ?AbstractModel
    {
        $class = new static();

        return $class->model()->create(array_replace(
            $class->definition(),
            $attributes
        ));
    }
}
