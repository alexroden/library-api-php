<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Endpoints\Stocks;

use OpenApi\Attributes as OA;

#[OA\Get(
    path: "/api/stocks/{stock}",
    description: "Returns a given stock record.",
    summary: "Get a given stock record",
    security: [
        ["bearerAuth" => []]
    ],
    tags: ["Stocks"],
    parameters: [
        new OA\Parameter(
            name: "stock",
            in: "path",
            required: true,
            schema: new OA\Schema(type: "integer")
        ),
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: "Stock returned",
            content: new OA\JsonContent(
                ref: "#/components/schemas/StockResponse"
            )
        ),
        new OA\Response(ref: "#/components/responses/Unauthorized", response: 401),
        new OA\Response(response: 404, description: "Stock not found"),
    ]
)]
class Get
{
}
