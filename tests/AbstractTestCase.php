<?php

namespace AlexRoden\LibraryApiPhp\Tests;

use AlexRoden\LibraryApiPhp\Database\Connection;
use AlexRoden\LibraryApiPhp\Database\DB;
use AlexRoden\LibraryApiPhp\Models\User;
use PHPUnit\Framework\TestCase;

abstract class AbstractTestCase extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        Connection::reset();
        Connection::getConnection('sqlite::memory:');

        $this->createSchema();
    }

    private function createSchema(): void
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


            Connection::exec($sql);
        }
    }
}