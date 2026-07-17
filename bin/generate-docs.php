<?php

require __DIR__ . '/../vendor/autoload.php';

use OpenApi\Generator;

$generator = new Generator();

$openapi = $generator->generate([
    __DIR__ . '/../src',
]);

file_put_contents(
    __DIR__ . '/../public/openapi.json',
    $openapi->toJson()
);