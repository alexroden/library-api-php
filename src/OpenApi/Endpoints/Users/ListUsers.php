<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Endpoints\Users;

use OpenApi\Attributes as OA;

#[OA\Get(
    path: "/api/users",
    summary: "List users",
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
            description: "Users returned",
            content: new OA\JsonContent(
                ref: "#/components/schemas/UserCollection"
            )
        ),
        new OA\Response(ref: "#/components/responses/Unauthorized", response: 401)
    ]
)]
class ListUsers
{

}
