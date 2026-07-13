<?php

namespace Database\Seeders;

use PDO;

abstract class AbstractSeeder
{
    public function __construct(
        protected readonly PDO $db
    ) {
    }
}