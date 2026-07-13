<?php

namespace App\Database;

use PDO;

readonly class Database
{
    private PDO $connection;

    public function __construct(?string $dsn = null)
    {
        $this->connection = new PDO(
            $dsn ?? sprintf(
                'mysql:host=%s;dbname=%s;charset=utf8mb4',
                getenv('MYSQL_HOST') ?: 'mysql',
                getenv('MYSQL_DATABASE'),
            ),
            getenv('MYSQL_USER') ?? null,
            getenv('MYSQL_PASSWORD') ?? null,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}