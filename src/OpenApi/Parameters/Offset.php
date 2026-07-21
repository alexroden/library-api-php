<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Parameters;

use OpenApi\Attributes as OA;

#[OA\Parameter(
    parameter: "Offset",
    name: "offset",
    in: "query",
    schema: new OA\Schema(
        type: "integer",
        default: 0
    )
)]
class Offset
{

}