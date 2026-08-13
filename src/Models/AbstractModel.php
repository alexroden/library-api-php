<?php

namespace AlexRoden\LibraryApiPhp\Models;

use AlexRoden\LibraryApiPhp\Database\DB;
use AlexRoden\LibraryApiPhp\Exceptions\UndefinedClassException;
use JsonSerializable;

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
    public static function create(array $attributes): AbstractModel
    {
        $model = new static();

        $id = $model->DB()->insert($model->filterFillable($attributes));

        return $model->where('id', '=', $id)->first();
    }

    public function delete(): void
    {
        $this->DB()->where('id' , '=', $this->attributes['id'])->delete();
    }

    public function fill(array $attributes): static
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * @throws UndefinedClassException
     */
    public static function find(int $id): ?static
    {
        $model = new static();

        return $model
            ->DB()
            ->where('id', '=', $id)
            ->first();
    }

    /**
     * @throws UndefinedClassException
     */
    public static function get(
        ?int $limit = null,
        ?int $offset = null,
    ): array {
        $model = new static();

        return $model
            ->DB()
            ->get($limit, $offset);
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * @throws UndefinedClassException
     */
    public function refresh(): AbstractModel
    {
        return $this->where('id', '=', $this->attributes['id'])->first();
    }


    public function toArray(): array
    {
        $attributes = $this->attributes;
        foreach ($this->hidden as $attribute) {
            unset($attributes[$attribute]);
        }

        return $attributes;
    }

    public function toJson(int $flags = 0): string|false
    {
        return json_encode($this->toArray(), $flags);
    }

    public function update(array $attributes): void
    {
        $this->DB()->where('id' , '=', $this->attributes['id'])->update($this->filterFillable($attributes));
    }

    /**
     * @return DB<TModel>
     */
    public static function where(
        string $column,
        string $operator,
        mixed $value,
    ): DB {
        $model = new static();

        return $model->DB()->where(
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

    protected function getModelId(AbstractModel|int $model): int
    {
        if (!is_int($model)) {
            return $model->id;
        }

        return $model;
    }
}