<?php

namespace App\Models;

use App\Database\Query;

abstract class AbstractModel
{
    protected string $table;

    protected array $fillable = [];

    protected array $attributes = [];

    public function __construct(
        protected Query $query
    ) {
    }

    public function create(array $attributes): int
    {
        $attributes = $this->filterFillable($attributes);

        return $this->query->insert(
            $this->table,
            $attributes
        );
    }

    public function first(array $where): ?static
    {
        $row = $this->query->first(
            $this->table,
            $this->fillable,
            $where
        );

        return $row ? $this->hydrate($row) : null;
    }

    public function __get(string $key): mixed
    {
        return $this->attributes[$key] ?? null;
    }

    public function __set(string $key, mixed $value): void
    {
        $this->attributes[$key] = $value;
    }

    public function toArray(): array
    {
        return $this->attributes;
    }

    protected function filterFillable(array $attributes): array
    {
        return array_intersect_key(
            $attributes,
            array_flip($this->fillable)
        );
    }

    protected function hydrate(array $attributes): static
    {
        $model = new static($this->query);

        $model->attributes = $attributes;

        return $model;
    }
}