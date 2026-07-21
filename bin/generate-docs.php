<?php

require __DIR__ . '/../vendor/autoload.php';

use OpenApi\Generator;

$openapi = (new Generator())->generate([
    __DIR__ . '/../src/OpenApi',
]);

file_put_contents(
    __DIR__ . '/../public/openapi.json',
    $openapi->toJson(JSON_PRETTY_PRINT)
);