<?php

namespace App\Database;

use PDO;

class Connection
{
    private static ?PDO $connection = null;

    public function __construct()
    {}

    public static function getConnection(?string $dsn = null): PDO
    {
        if (self::$connection === null) {
            self::$connection = new PDO(
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

        return self::$connection;
    }

    public static function exec($sql): void
    {
        self::$connection->exec($sql);
    }

    public static function reset(): void
    {
        self::$connection = null;
    }
}