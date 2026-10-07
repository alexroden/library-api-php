<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Requests;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "CouncilRequest",
    required: [
        "name",
    ],
    properties: [
        new OA\Property(
            property: "name",
            type: "string",
        ),
    ]
)]
class CouncilRequest
{

}
