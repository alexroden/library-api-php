<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Endpoints\Users;

use OpenApi\Attributes as OA;

#[OA\Get(
    path: "/api/users",
    summary: "List users",
    tags: ["Users"],
    security: [
        ["bearerAuth" => []]
    ],
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
        new OA\Response(response: 401, ref: "#/components/responses/Unauthorized")
    ]
)]
class ListUsers
{

}