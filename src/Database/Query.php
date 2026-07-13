<?php

namespace App\Database;

use PDO;

readonly class Query
{
    public function __construct(
        protected PDO $db
    ) {
    }

    public function select(string $table, array $attributes = []): array
    {


        $stmt = $this->execute(
            sprintf(), $params);

        return $stmt->fetchAll();
    }

    public function first(string $sql, array $params = []): ?array
    {
        $stmt = $this->execute($sql, $params);

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