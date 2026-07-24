<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Endpoints\Councils;

use OpenApi\Attributes as OA;

#[OA\Get(
    path: "/api/councils",
    summary: "List councils",
    security: [
        ["bearerAuth" => []]
    ],
    tags: ["Users"],
    parameters: [
        new OA\Parameter(ref: "#/components/parameters/Limit"),
        new OA\Parameter(ref: "#/components/parameters/Offset"),
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: "Councils returned",
            content: new OA\JsonContent(
                ref: "#/components/schemas/CouncilCollection"
            )
        ),
        new OA\Response(ref: "#/components/responses/Unauthorized", response: 401)
    ]
)]
class ListCouncils
{
}