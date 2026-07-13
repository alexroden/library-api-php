<?php

namespace App\Models;

use App\Database\Query;

abstract class AbstractModel
{
    protected string $table;

    protected array $fillable = [];

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

    protected function filterFillable(array $attributes): array
    {
        return array_intersect_key(
            $attributes,
            array_flip($this->fillable)
        );
    }
}