<?php

namespace Tests\Factories;

use App\Database\Query;
use App\Models\AbstractModel;
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

    public function create(array $attributes = []): ?AbstractModel
    {
        return $this->model()->create(array_replace(
            $this->definition(),
            $attributes
        ));
    }
}