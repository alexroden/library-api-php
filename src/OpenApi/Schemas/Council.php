<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Council",
    properties: [
        new OA\Property(property: "id", type: "integer"),
        new OA\Property(property: "name", type: "string"),
        new OA\Property(property: "created_at", type: "string"),
        new OA\Property(property: "updated_at", type: "string"),
    ]
)]
class Council
{
}
