<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Endpoints\Stocks;

use OpenApi\Attributes as OA;

#[OA\Post(
    path: "/api/stocks",
    description: "Creates a stock record holding the quantity of a book at a library. A library holds one record per book, so an existing record has its quantity replaced by the submitted value.",
    summary: "Create stock",
    security: [
        ["bearerAuth" => []]
    ],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            ref: "#/components/schemas/CreateStockRequest"
        )
    ),
    tags: ["Stocks"],
    responses: [
        new OA\Response(
            response: 201,
            description: "Stock created or updated",
            content: new OA\JsonContent(
                ref: "#/components/schemas/StockResponse"
            )
        ),
        new OA\Response(ref: "#/components/responses/Unauthorized", response: 401),
        new OA\Response(ref: "#/components/responses/Validation", response: 422),
    ]
)]
class Create
{
}
