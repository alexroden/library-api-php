<?php

namespace AlexRoden\LibraryApiPhp\Models;

use AlexRoden\LibraryApiPhp\Database\DB;
use AlexRoden\LibraryApiPhp\Exceptions\UndefinedClassException;
use JsonSerializable;
use PDO;

/**
 * @template TModel of AbstractModel
 */
abstract class AbstractModel implements JsonSerializable
{
    protected string $table;
    protected array $fillable = [];
    protected array $attributes = [];
    protected array $hidden = [];

    public function __construct() {
        $fillable = $this->fillable;
        if (count($fillable) === 0) {
            $fillable = null;
        }
    }

    /**
     * @return TModel
     *
     * @throws UndefinedClassException
     */
    public function create(array $attributes): AbstractModel
    {
        $id = $this->DB()->insert($this->filterFillable($attributes));

        return $this->where('id', '=', $id)->first();
    }

    public function fill(array $attributes): static
    {
        $this->attributes = $attributes;

        return $this;
    }

    public static function find(int $id): ?static
    {
        $model = new static();

        return $model
            ->DB()
            ->where('id', '=', $id)
            ->first();
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }


    public function toArray(): array
    {
        $attributes = $this->attributes;
        foreach ($this->hidden as $attribute) {
            unset($attributes[$attribute]);
        }

        return $attributes;
    }

    public function update(array $attributes): void
    {
        $this->DB()->update($this->filterFillable($attributes));
    }

    /**
     * @return DB<TModel>
     */
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

    /**
     * @return DB<TModel>
     */
    protected function DB(
        ?string $table = null,
        ?string $class = null,
        ?array $fillable = null,
    ): DB
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