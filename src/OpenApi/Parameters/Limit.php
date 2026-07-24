<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Parameters;

use OpenApi\Attributes as OA;

#[OA\Parameter(
    parameter: "Limit",
    name: "limit",
    in: "query",
    schema: new OA\Schema(
        type: "integer",
        default: 10
    )
)]
class Limit
{

}