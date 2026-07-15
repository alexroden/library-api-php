<?php

namespace App\Models;

use App\Database\Query;
use JsonSerializable;
use PDO;

abstract class AbstractModel implements JsonSerializable
{
    protected Query $query;
    protected string $table;
    protected array $fillable = [];
    protected array $attributes = [];

    public function __construct() {
        $fillable = $this->fillable;
        if (count($fillable) === 0) {
            $fillable = null;
        }

        $this->query = new Query($this->table, static::class, $fillable);
    }

    public function create(array $attributes): AbstractModel
    {
        $attributes = $this->filterFillable($attributes);

        $id = $this->newQuery()->insert($attributes);

        return $this->where('id', '=', $id)->first();
    }

    public function fill(array $attributes): static
    {
        $this->attributes = $attributes;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return $this->attributes;
    }

    protected function newQuery(): Query
    {
        return new Query(
            $this->table,
            static::class,
            $this->fillable,
        );
    }

    public function update(array $attributes): void
    {
        $attributes = $this->filterFillable($attributes);

        $this->newQuery()->update($attributes);
    }

    public function where(
        string $column,
        string $operator,
        mixed $value,
    ): Query
    {
        return $this->newQuery()->where(
            $column,
            $operator,
            $value,
        );
    }

    public function __get(string $key): mixed
    {
        return $this->attributes[$key] ?? null;
    }

    public function __set(string $key, mixed $value): void
    {
        $this->attributes[$key] = $value;
    }

    protected function filterFillable(array $attributes): array
    {
        return array_intersect_key(
            $attributes,
            array_flip($this->fillable)
        );
    }
}