<?php

namespace AlexRoden\LibraryApiPhp\Http\Controllers;

use AlexRoden\LibraryApiPhp\Authentication\Jwt;
use AlexRoden\LibraryApiPhp\Exceptions\UndefinedClassException;
use AlexRoden\LibraryApiPhp\Http\Exceptions\NotFoundException;
use AlexRoden\LibraryApiPhp\Http\Exceptions\UnauthorizedException;
use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Http\Helpers\JsonResponse;
use AlexRoden\LibraryApiPhp\Http\Requests\AuthRequest;
use AlexRoden\LibraryApiPhp\Models\User;
use OpenApi\Attributes as OA;

class UserController
{
    protected Jwt $jwt;

    public function __construct()
    {
        $this->jwt = new Jwt(env('JWT_SECRET'));
    }

    #[OA\Post(
        path: "/api/auth",
        summary: "Authenticate user",
        description: "Authenticates a user and returns a JWT token.",
        tags: ["Users"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: [
                    "email",
                    "password"
                ],
                properties: [
                    new OA\Property(
                        property: "email",
                        type: "string",
                        example: "alex@example.com"
                    ),
                    new OA\Property(
                        property: "password",
                        type: "string",
                        example: "password"
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Authenticated user returned",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                new OA\Property(
                                    property: "id",
                                    type: "integer",
                                    example: 1
                                ),
                                new OA\Property(
                                    property: "email",
                                    type: "string",
                                    example: "alex@example.com"
                                ),
                                new OA\Property(
                                    property: "first_name",
                                    type: "string",
                                    example: "Alex"
                                ),
                                new OA\Property(
                                    property: "last_name",
                                    type: "string",
                                    example: "Roden"
                                ),
                                new OA\Property(
                                    property: "created_at",
                                    type: "string",
                                    example: "2026-07-16 11:00:27"
                                ),
                                new OA\Property(
                                    property: "updated_at",
                                    type: "string",
                                    example: "2026-07-16 11:00:27"
                                ),
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(
                            property: "message",
                            type: "string",
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: "Validation failed",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(
                            property: "message",
                            type: "string",
                        )
                    ]
                )
            )
        ]
    )]
    /**
     * @throws NotFoundException
     * @throws UndefinedClassException
     * @throws UnauthorizedException|\JsonException
     */
    public function auth(AuthRequest $request): JsonResponse
    {
        $user = new User()->where('email', '=', $request->input('email'))->first();
        if (!$user) {
            throw new NotFoundException('User not found');
        }

        if (!$user->authenticate($request->input('password'))) {
            throw new UnauthorizedException('Invalid credentials');
        }

        return new JsonResponse(
            ['data' => $user->toArray()],
            200,
            ['Authorization' => $this->jwt->encode([
                'sub' => $user->id,
                'email' => $user->email,
                'permissions' => $user->permissions(),
            ])],
        );
    }

    #[OA\Post(
        path: "/api/users",
        summary: "Create user",
        description: "Creates a new user account.",
        tags: ["Users"],
        security: [
            ["bearerAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: [
                    "email",
                    "password"
                ],
                properties: [
                    new OA\Property(
                        property: "email",
                        type: "string",
                        example: "newuser@example.com"
                    ),
                    new OA\Property(
                        property: "password",
                        type: "string",
                        example: "password"
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Authenticated user returned",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                new OA\Property(
                                    property: "id",
                                    type: "integer",
                                    example: 1
                                ),
                                new OA\Property(
                                    property: "email",
                                    type: "string",
                                    example: "alex@example.com"
                                ),
                                new OA\Property(
                                    property: "first_name",
                                    type: "string",
                                    example: "Alex"
                                ),
                                new OA\Property(
                                    property: "last_name",
                                    type: "string",
                                    example: "Roden"
                                ),
                                new OA\Property(
                                    property: "created_at",
                                    type: "string",
                                    example: "2026-07-16 11:00:27"
                                ),
                                new OA\Property(
                                    property: "updated_at",
                                    type: "string",
                                    example: "2026-07-16 11:00:27"
                                ),
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(
                            property: "message",
                            type: "string",
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: "Validation failed",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(
                            property: "message",
                            type: "string",
                        )
                    ]
                )
            )
        ]
    )]
    public function create(Request $request): JsonResponse
    {
        dd($request);
    }

    #[OA\Get(
        path: "/api/user",
        summary: "Get authenticated user",
        description: "Returns the currently authenticated user.",
        tags: ["Users"],
        security: [
            ["bearerAuth" => []]
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Authenticated user returned",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                new OA\Property(
                                    property: "id",
                                    type: "integer",
                                    example: 1
                                ),
                                new OA\Property(
                                    property: "email",
                                    type: "string",
                                    example: "alex@example.com"
                                ),
                                new OA\Property(
                                    property: "first_name",
                                    type: "string",
                                    example: "Alex"
                                ),
                                new OA\Property(
                                    property: "last_name",
                                    type: "string",
                                    example: "Roden"
                                ),
                                new OA\Property(
                                    property: "created_at",
                                    type: "string",
                                    example: "2026-07-16 11:00:27"
                                ),
                                new OA\Property(
                                    property: "updated_at",
                                    type: "string",
                                    example: "2026-07-16 11:00:27"
                                ),
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(
                            property: "message",
                            type: "string",
                        )
                    ]
                )
            )
        ]
    )]
    public function getAuthenticatedUser(Request $request): JsonResponse
    {
        return new JsonResponse([
            'data' => $request->getUser(),
        ]);
    }
}