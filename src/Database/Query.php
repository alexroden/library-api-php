<?php

namespace App\Database;

use PDO;
use PDOStatement;

readonly class Query
{
    public function __construct(
        protected PDO $db
    ) {
    }

//    public function select(string $table, array $attributes = []): array
//    {
//
//
//        $stmt = $this->execute(
//            sprintf(), $params);
//
//        return $stmt->fetchAll();
//    }

    public function first(string $table, array $attrs, array $where = []): ?array
    {
        array_unshift($attrs, 'id');
        $attrs = array_merge($attrs, ['created_at', 'updated_at']);

        $column = implode(', ', $attrs);
        $sql = "SELECT {$column} FROM {$table}";

        $values = [];
        if (count($where) > 0) {
            $sql .= ' WHERE ';
            foreach ($where as $key => $value) {
                $sql .= "{$key} = :{$key}";
                $values[":{$key}"] = $value;
            }
        }

        $stmt = $this->execute($sql, $values);

        $result = $stmt->fetch();

        return $result ?: null;
    }

    public function insert(string $table, array $attributes = []): int
    {
        $columns = implode(', ', array_keys($attributes));

        $placeholders = implode(
            ', ',
            array_fill(0, count($attributes), '?')
        );

        $stmt = $this->db->prepare(
            sprintf(
                'INSERT INTO %s (%s) VALUES (%s)',
                $table,
                $columns,
                $placeholders
            )
        );

        $stmt->execute(array_values($attributes));

        return (int) $this->db->lastInsertId();
    }

    public function update(string $sql, array $params = []): bool
    {
        return $this->execute($sql, $params)->rowCount() > 0;
    }

    public function delete(string $sql, array $params = []): bool
    {
        return $this->execute($sql, $params)->rowCount() > 0;
    }

    private function execute(string $sql, array $params = []): PDOStatement
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt;
    }
}