<?php

namespace Tests;

use App\Database\Database;
use App\Database\Query;
use PDO;
use PHPUnit\Framework\TestCase;

abstract class AbstractDatabaseTestCase extends TestCase
{
    private PDO $db;
    protected Query $query;

    protected function setUp(): void
    {
        parent::setUp();

        $database = new Database('sqlite::memory:');
        $this->db = $database->getConnection();
        $this->createSchema();

        $this->query = new Query($this->db);
    }private function createSchema(): void
{
    $files = glob(dirname(__DIR__) . '/database/migrations/*.up.sql');

    sort($files);

    foreach ($files as $file) {
        $sql = $file
                |> file_get_contents(...)
                |> (fn($x) => preg_replace('/\s+COLLATE\s+utf8mb4_unicode_ci/i', '', $x))
                |> (fn($x) => preg_replace('/id\s+INT\s+AUTO_INCREMENT\s+PRIMARY\s+KEY/i', 'id INTEGER PRIMARY KEY AUTOINCREMENT', $x))
                |> (fn($x) => preg_replace('/,\s*INDEX\s+[^(]+\([^)]+\)/i', '',  $x))
                |> (fn($x) => preg_replace('/ON UPDATE CURRENT_TIMESTAMP/i', '', $x));

        $this->db->exec($sql);
    }
}


}