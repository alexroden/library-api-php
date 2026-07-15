<?php

namespace AlexRoden\LibraryApiPhp\Models;

use AlexRoden\LibraryApiPhp\Database\DB;
use JsonSerializable;
use PDO;

abstract class AbstractModel implements JsonSerializable
{
    protected DB $query;
    protected string $table;
    protected array $fillable = [];
    protected array $attributes = [];

    public function __construct() {
        $fillable = $this->fillable;
        if (count($fillable) === 0) {
            $fillable = null;
        }

        $this->query = new DB($this->table, static::class, $fillable);
    }

    public function create(array $attributes): static
    {
        $id = $this->DB()->insert($this->filterFillable($attributes));

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

    public function update(array $attributes): void
    {
        $this->DB()->update($this->filterFillable($attributes));
    }

    public function where(
        string $column,
        string $operator,
        mixed $value,
    ): DB
    {
        return $this->DB()->where(
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

    protected function DB(?string $table = null, ?string $class = null, ?array $fillable = null): DB
    {
        return new DB(
            $table ?? $this->table,
            $class ?? static::class,
            $fillable ?? $this->fillable,
        );
    }

    protected function filterFillable(array $attributes): array
    {
        return array_intersect_key(
            $attributes,
            array_flip($this->fillable)
        );
    }
}