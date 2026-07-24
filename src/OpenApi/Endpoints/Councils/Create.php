<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Endpoints\Councils;

use OpenApi\Attributes as OA;

#[OA\Post(
    path: "/api/councils",
    description: "Creates a new council.",
    summary: "Create council",
    security: [
        ["bearerAuth" => []]
    ],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            ref: "#/components/schemas/CreateCouncilRequest"
        )
    ),
    tags: ["Councils"],
    responses: [
        new OA\Response(
            response: 201,
            description: "Council created",
            content: new OA\JsonContent(
                ref: "#/components/schemas/CouncilResponse"
            )
        ),
        new OA\Response(ref: "#/components/responses/Unauthorized", response: 401),
        new OA\Response(ref: "#/components/responses/Validation", response: 422),
    ]
)]
class Create
{
}