<?php

namespace AlexRoden\LibraryApiPhp\Database;

use AlexRoden\LibraryApiPhp\Exceptions\DatabaseException;
use AlexRoden\LibraryApiPhp\Exceptions\UndefinedClassException;
use AlexRoden\LibraryApiPhp\Models\AbstractModel;
use InvalidArgumentException;
use PDO;

/**
 * @template TModel of AbstractModel
 */
class DB
{
    private array $conditions = [];
    private array $joins = [];

    private bool $excludeLocalAttributes = false;

    /**
     * @param class-string<TModel>|null $class
     */
    public function __construct(
        private string $table,
        private ?string $class = null,
        private array $attributes = ['*']
    ) {
    }

    public function delete(): void
    {
        $pdo = Connection::getConnection();

        $sql = "DELETE FROM {$this->table}";
        $bindings = $this->applyConditions($sql);

        $stmt = $pdo->prepare($sql);
        if (!$stmt->execute($bindings)) {
            $error = $stmt->errorInfo();

            throw new DatabaseException(
                sprintf(
                    'Database query failed [%s]: %s',
                    $error[0],
                    $error[2]
                )
            );
        }
    }

    public function excludeLocalAttributes(bool $excludeLocalAttributes = true): self
    {
        $this->excludeLocalAttributes = $excludeLocalAttributes;

        return $this;
    }

    /**
     * @return TModel|null
     *
     * @throws UndefinedClassException
     */
    public function first(): ?AbstractModel
    {
        return $this->get(1)[0] ?? null;
    }

    /**
     * @return array<TModel>
     *
     * @throws UndefinedClassException
     */
    public function get(
        ?int $limit = null,
        ?int $offset = null,
        ?bool $excludeModelMapping = false,
    ): array {
        if (!$this->class) {
            throw new UndefinedClassException($this->table);
        }

        $pdo = Connection::getConnection();
        $attributes = $this->attributes;
        if (count($attributes) > 0 && $attributes[0] !== '*') {
            $prefix = '';
            if (count($this->joins) > 0) {
                if ($this->excludeLocalAttributes) $attributes = [];

                foreach ($this->joins as $join) {
                    if ($join[3] === null || count($join[3]) === 0) {
                        continue;
                    }

                    foreach ($join[3] as $joinValue) {
                        $prefix = '';
                        if (!str_contains($joinValue, '.')) {
                            $prefix = $join[0].'.';
                        }

                        $attributes[] = $prefix.$joinValue;
                    }
                }

                if (!$this->excludeLocalAttributes) {
                    $prefix = $this->table.'.';
                    $attributes = array_map(function (string $attribute) use ($prefix) {
                        return $prefix.$attribute;
                    }, $attributes);
                }
            }

            if (!$this->excludeLocalAttributes) {
                array_unshift($attributes, $prefix.'id');
                $attributes = array_merge($attributes, [$prefix.'created_at', $prefix.'updated_at']);
            }
        }

        $columns = implode(', ', $attributes);
        $sql = "SELECT {$columns} FROM {$this->table}";

        $this->applyJoins($sql);
        $bindings = $this->applyConditions($sql);

        if ($limit) {
            $sql .= " LIMIT ?";
            $bindings[] = $limit;
        }

        if ($offset) {
            $sql .= " OFFSET ?";
            $bindings[] = $offset;
        }

        $stmt = $pdo->prepare($sql);
        if (!$stmt->execute($bindings)) {
            $error = $stmt->errorInfo();

            throw new DatabaseException(
                sprintf(
                    'Database query failed [%s]: %s',
                    $error[0],
                    $error[2]
                )
            );
        }

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($excludeModelMapping) {
            return $rows;
        }

        return array_map(function (array $row) {
            /** @var class-string<TModel> $class */
            $class = $this->class;

            $model = new $class(
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

    public function insert(array $attributes = []): int
    {
        if ($attributes === []) {
            throw new InvalidArgumentException('No attributes provided for insert.');
        }

        $pdo = Connection::getConnection();
        $columns = implode(', ', array_keys($attributes));

        $placeholders = implode(
            ', ',
            array_fill(0, count($attributes), '?')
        );

        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $this->table,
            $columns,
            $placeholders
        );

        $stmt = $pdo->prepare($sql);
        if (!$stmt->execute(array_values($attributes))) {
            $error = $stmt->errorInfo();

            throw new DatabaseException(
                sprintf(
                    'Database query failed [%s]: %s',
                    $error[0],
                    $error[2]
                )
            );
        }

        return (int) $pdo->lastInsertId();
    }

    public function join(
        string $table,
        string $localKey,
        string $foreignKey,
        ?array $attributes = null,
    ): static {
        $this->joins[] = [$table, $localKey, $foreignKey, $attributes];
        return $this;
    }

    public function where(
        string $column,
        string $operator,
        mixed $value
    ): static {
        $this->conditions[] = [
            $column,
            $operator,
            $value,
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
            $value,
            'OR'
        ];

        return $this;
    }

    public function update(array $attributes = []): void
    {
        $pdo = Connection::getConnection();
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


    private function applyConditions(string &$query): array
    {
        $bindings = [];
        if (count($this->conditions)) {
            $query .= ' WHERE ';

            foreach ($this->conditions as $index => $condition) {
                if ($index > 0) {
                    $query .= " {$condition[3]} ";
                }

                $prefix = '';
                if (count($this->joins) > 0) {
                    $prefix = $this->table.'.';
                }

                $query .= "{$prefix}{$condition[0]} {$condition[1]} ?";

                $bindings[] = $condition[2];
            }
        }

        return $bindings;
    }

    private function applyJoins(string &$query): void
    {
        if (count($this->joins) > 0) {
            foreach ($this->joins as $join) {
                $inner =  $join[1];
                if (!str_contains($inner, '.')) {
                    $inner =  $this->table.'.'.$join[1];
                }

                $query .= " JOIN {$join[0]} ON {$inner} = {$join[0]}.{$join[2]} ";
            }
        }
    }

}
