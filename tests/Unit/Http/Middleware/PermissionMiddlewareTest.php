<?php

namespace AlexRoden\LibraryApiPhp\Tests\Unit\Http\Middleware;

use AlexRoden\LibraryApiPhp\Authentication\Jwt;
use AlexRoden\LibraryApiPhp\Config\Config;
use AlexRoden\LibraryApiPhp\Http\Exceptions\PermissionException;
use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Http\Helpers\JsonResponse;
use AlexRoden\LibraryApiPhp\Http\Middlewares\PermissionMiddleware;
use AlexRoden\LibraryApiPhp\Models\User;
use AlexRoden\LibraryApiPhp\Tests\AbstractTestCase;
use AlexRoden\LibraryApiPhp\Tests\Factories\PermissionFactory;
use AlexRoden\LibraryApiPhp\Tests\Factories\RoleFactory;
use AlexRoden\LibraryApiPhp\Tests\Factories\UserFactory;

class PermissionMiddlewareTest extends AbstractTestCase
{
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::create();
    }

    public function testPermissionMiddleware(): void
    {
        $rolePermissions = Config::get('role-permissions');

        foreach ($rolePermissions as $role => $permissions) {
            $role = RoleFactory::create(['name' => $role]);
            foreach ($permissions as $permission) {
                PermissionFactory::create(['name' => $permission]);
                $role->assignPermission($permission);
            }

            $jwt = new Jwt(env('JWT_SECRET'));
            $token = $jwt->encode([
                'sub' => $this->user->id,
                'email' => $this->user->email,
                'permissions' => $permissions,
            ]);

            $request = new Request(
                headers: [
                    'Authorization' => 'Bearer ' . $token,
                ]
            );
            $request->setUser($this->user);

            $middleware = new PermissionMiddleware();

            $called = false;
            $response = $middleware->handle(
                $request,
                function (Request $request) use (&$called) {
                    $called = true;

                    return new JsonResponse([
                        'success' => true,
                    ]);
                },
                ...$permissions,
            );

            $this->assertTrue($called);
            $this->assertInstanceOf(JsonResponse::class, $response);

            $jwt = new Jwt(env('JWT_SECRET'));
            $token = $jwt->encode([
                'sub' => $this->user->id,
                'email' => $this->user->email,
                'permissions' => [],
            ]);

            $request = new Request(
                headers: [
                    'Authorization' => 'Bearer ' . $token,
                ]
            );
            $request->setUser($this->user);

            $this->expectException(PermissionException::class);

            $called = false;
            $middleware->handle(
                $request,
                function (Request $request) use (&$called) {
                    $called = true;

                    return new JsonResponse([
                        'success' => true,
                    ]);
                },
                ...$permissions,
            );
        }
    }
}
