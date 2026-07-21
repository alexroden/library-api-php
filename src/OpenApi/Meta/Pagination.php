<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Meta;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Pagination",
    properties: [
        new OA\Property(property: "total", type: "integer"),
        new OA\Property(property: "limit", type: "integer"),
        new OA\Property(property: "offset", type: "integer"),
        new OA\Property(property: "count", type: "integer"),
        new OA\Property(property: "has_more", type: "boolean"),
    ]
)]
class Pagination
{

}