<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Responses;

use OpenApi\Attributes as OA;

#[OA\Response(
    response: "Unauthorized",
    description: "Unauthenticated",
    content: new OA\JsonContent(
        properties: [
            new OA\Property(
                property: "message",
                type: "string"
            )
        ]
    )
)]
class Unauthorized
{
}
