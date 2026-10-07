<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Endpoints\Libraries;

use OpenApi\Attributes as OA;

#[OA\Get(
    path: "/api/libraries",
    summary: "List councils",
    security: [
        ["bearerAuth" => []]
    ],
    tags: ["Libraries"],
    parameters: [
        new OA\Parameter(ref: "#/components/parameters/Limit"),
        new OA\Parameter(ref: "#/components/parameters/Offset"),
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: "Libraries returned",
            content: new OA\JsonContent(
                ref: "#/components/schemas/LibraryCollection"
            )
        ),
        new OA\Response(ref: "#/components/responses/Unauthorized", response: 401)
    ]
)]
class ListLibraries
{
}
