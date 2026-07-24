<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Endpoints\Councils;

use OpenApi\Attributes as OA;

#[OA\Get(
    path: "/api/councils/{council}",
    description: "Returns a given council.",
    summary: "Get a given council",
    security: [
        ["bearerAuth" => []]
    ],
    tags: ["Users"],
    responses: [
        new OA\Response(
            response: 200,
            description: "Authenticated council returned",
            content: new OA\JsonContent(
                ref: "#/components/schemas/CouncilResponse"
            )
        ),
        new OA\Response(ref: "#/components/responses/Unauthorized", response: 401)
    ]
)]
class Get
{
}