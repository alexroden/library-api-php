<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Endpoints\Councils;

use OpenApi\Attributes as OA;

#[OA\Put(
    path: "/api/councils/{council}",
    description: "Update a given council.",
    summary: "Update council",
    security: [
        ["bearerAuth" => []]
    ],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            ref: "#/components/schemas/CouncilRequest"
        )
    ),
    tags: ["Councils"],
    responses: [
        new OA\Response(
            response: 200,
            description: "Council returned",
            content: new OA\JsonContent(
                ref: "#/components/schemas/CouncilResponse"
            )
        ),
        new OA\Response(ref: "#/components/responses/Unauthorized", response: 401),
        new OA\Response(ref: "#/components/responses/Validation", response: 422),
    ]
)]
class Update
{
}
