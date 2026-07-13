<?php

namespace App\Database;

use PDO;

readonly class Database
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = new PDO(
            sprintf(
                'mysql:host=%s;dbname=%s;charset=utf8mb4',
                getenv('MYSQL_HOST') ?: 'mysql',
                getenv('MYSQL_DATABASE'),
            ),
            getenv('MYSQL_USER'),
            getenv('MYSQL_PASSWORD'),
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