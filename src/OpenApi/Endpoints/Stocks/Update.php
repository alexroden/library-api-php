<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Endpoints\Stocks;

use OpenApi\Attributes as OA;

#[OA\Put(
    path: "/api/stocks/{stock}",
    description: "Replaces the quantity of a given stock record. The library and book the record belongs to cannot be changed.",
    summary: "Update stock",
    security: [
        ["bearerAuth" => []]
    ],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            ref: "#/components/schemas/UpdateStockRequest"
        )
    ),
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
            description: "Stock updated",
            content: new OA\JsonContent(
                ref: "#/components/schemas/StockResponse"
            )
        ),
        new OA\Response(
            response: 401,
            description: "Missing or invalid token, or the token lacks the stocks.update permission",
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "message", type: "string"),
                ]
            )
        ),
        new OA\Response(response: 404, description: "Stock not found"),
        new OA\Response(ref: "#/components/responses/Validation", response: 422),
    ]
)]
class Update
{
}
