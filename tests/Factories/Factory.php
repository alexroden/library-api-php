<?php

namespace Tests\Factories;

use App\Database\Query;
use App\Models\AbstractModel;
use Faker\Factory as FakerFactory;
use Faker\Generator;

abstract class Factory
{
    protected Generator $faker;

    public function __construct(
        protected Query $query
    ) {
        $this->faker = FakerFactory::create();
    }

    abstract protected function model(): AbstractModel;

    abstract protected function definition(): array;

    public function create(array $attributes = []): AbstractModel
    {
        $attributes = array_replace(
            $this->definition(),
            $attributes
        );

        $id = $this->model()->create($attributes);

        return $this->model()->first([
            'id' => $id,
        ]);
    }
}