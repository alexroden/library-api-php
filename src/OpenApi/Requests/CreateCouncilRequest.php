<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Requests;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "CreateCouncilRequest",
    required: [
        "name",
        "last_name",
    ],
    properties: [
        new OA\Property(
            property: "name",
            type: "string",
        ),
    ]
)]
class CreateCouncilRequest
{

}