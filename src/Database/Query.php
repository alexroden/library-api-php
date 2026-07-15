<?php

namespace App\Database;

use App\Models\AbstractModel;
use PDO;

class Query
{
    private array $conditions = [];

    public function __construct(
        private string $table,
        private string $class,
        private array $attributes = ['*']
    ) {
    }

    public function where(
        string $column,
        string $operator,
        mixed $value
    ): static {
        $this->conditions[] = [
            $column,
            $operator,
            $this->formatValue($value),
            'AND'
        ];

        return $this;
    }

    public function orWhere(
        string $column,
        string $operator,
        mixed $value
    ): static {
        $this->conditions[] = [
            $column,
            $operator,
            $this->formatValue($value),
            'OR'
        ];

        return $this;
    }

    public function get(int $limit = 10, int $offset = 0): array
    {
        $pdo = Database::getConnection();
        $attributes = $this->attributes;
        if (count($attributes) > 0 && $attributes[0] !== '*') {
            array_unshift($attributes, 'id');
            $attributes = array_merge($attributes, ['created_at', 'updated_at']);
        }

        $columns = implode(', ', $attributes);
        $query = "SELECT {$columns} FROM {$this->table}";

        $bindings = $this->applyConditions($query);

        $query .= " LIMIT {$limit} OFFSET {$offset}";

        $stmt = $pdo->prepare($query);
        $stmt->execute($bindings);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function (array $row) {
            $model = new $this->class(
                new self(
                    $this->table,
                    $this->class,
                    $this->attributes,
                ),
            );

            $model->fill($row);

            return $model;
        }, $rows);
    }

    public function first(): ?AbstractModel
    {
        return $this->get(1)[0] ?? null;
    }

    public function insert(array $attributes = []): int
    {
        $pdo = Database::getConnection();
        $columns = implode(', ', array_keys($attributes));

        $placeholders = implode(
            ', ',
            array_fill(0, count($attributes), '?')
        );

        $stmt = $pdo->prepare(
            sprintf(
                'INSERT INTO %s (%s) VALUES (%s)',
                $this->table,
                $columns,
                $placeholders
            )
        );

        $stmt->execute(array_values($attributes));

        return (int) $pdo->lastInsertId();
    }

    public function update(array $attributes = []): void
    {
        $pdo = Database::getConnection();
        $columns = implode(
            ', ',
            array_map(
                fn ($column) => "{$column} = ?",
                array_keys($attributes)
            )
        );

        $query = 'UPDATE %s SET %s';
        $bindings = array_merge(array_values($attributes), $this->applyConditions($query));

        $foo = sprintf(
            $query,
            $this->table,
            $columns,
        );

        $stmt = $pdo->prepare(
            $foo,
        );

        $stmt->execute($bindings);
    }

    private function formatValue(mixed $value): string
    {
        return match (true) {
            is_string($value) => "'{$value}'",
            is_bool($value)   => $value ? 'TRUE' : 'FALSE',
            is_null($value)   => 'NULL',
            default           => $value,
        };
    }

    private function applyConditions(string &$query): array
    {
        $bindings = [];
        if ($this->conditions !== []) {
            $query .= ' WHERE ';

            foreach ($this->conditions as $index => $condition) {
                if ($index > 0) {
                    $query .= " {$condition[3]} ";
                }

                $query .= "{$condition[0]} {$condition[1]} ?";

                $bindings[] = $condition[2];
            }
        }

        return $bindings;
    }

    public function getConnection(): PDO
    {
        return Database::getConnection();
    }
}
