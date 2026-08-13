<?php

namespace AlexRoden\LibraryApiPhp\Database\Seeders;

use AlexRoden\LibraryApiPhp\Database\Connection;

abstract class AbstractSeeder
{
    public function __construct() {
        Connection::getConnection();
    }
}
