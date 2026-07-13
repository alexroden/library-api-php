<?php

namespace Tests;

use App\Database\Database;
use PDO;
use PHPUnit\Framework\TestCase;

abstract class AbstractDatabaseTestCase extends TestCase
{
    protected PDO $db;

    protected function setUp(): void
    {
        parent::setUp();

        $database = new Database('sqlite::memory:');

        $this->db = $database->getConnection();

        $this->createSchema();
    }

    private function createSchema(): void
    {
        $this->db->exec("
            CREATE TABLE users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                first_name TEXT NOT NULL,
                last_name TEXT,
                email TEXT,
                password TEXT
            );
        ");
    }
}