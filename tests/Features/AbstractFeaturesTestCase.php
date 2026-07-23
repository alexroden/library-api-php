<?php

namespace AlexRoden\LibraryApiPhp\Tests\Features;

use AlexRoden\LibraryApiPhp\Authentication\Jwt;
use AlexRoden\LibraryApiPhp\Config\Config;
use AlexRoden\LibraryApiPhp\Enums\Roles;
use AlexRoden\LibraryApiPhp\Foundation\Container;
use AlexRoden\LibraryApiPhp\Foundation\Providers\EventServiceProvider;
use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Http\Helpers\JsonResponse;
use AlexRoden\LibraryApiPhp\Mail\Mailer;
use AlexRoden\LibraryApiPhp\Models\User;
use AlexRoden\LibraryApiPhp\Router;
use AlexRoden\LibraryApiPhp\Tests\AbstractTestCase;
use AlexRoden\LibraryApiPhp\Tests\Factories\PermissionFactory;
use AlexRoden\LibraryApiPhp\Tests\Factories\RoleFactory;
use AlexRoden\LibraryApiPhp\Tests\Factories\UserFactory;
use AlexRoden\LibraryApiPhp\Tests\Fakes\FakeMailer;

class AbstractFeaturesTestCase extends AbstractTestCase
{
    protected Router $router;
    protected User $currentUser;
    protected $headers = [];

    protected function setUp(): void
    {
        parent::setUp();

        require_once __DIR__ . '/../../bootstrap/app.php';

        $container = createApplication(true);

        $container->singleton(
            Mailer::class,
            new FakeMailer()
        );

        EventServiceProvider::register($container);

        $this->router = new Router($container);

        $router = $this->router;

        require __DIR__ . '/../../routes/api.php';
        require __DIR__ . '/../../routes/docs.php';
    }

    public function asAuthorizedUser(): void
    {
        $this->currentUser = UserFactory::create();

        foreach (Config::get('role-permissions') as $role => $permissions) {
            $role = RoleFactory::create(['name' => $role]);
            foreach ($permissions as $permission) {
                PermissionFactory::create(['name' => $permission]);
                $role->assignPermission($permission);
            }
        }

        $this->currentUser->assignRole(Roles::SUPER_ADMIN);

        $jwt = new Jwt(env('JWT_SECRET'));
        $token = $jwt->encode([
            'sub' => $this->currentUser->id,
            'email' => $this->currentUser->email,
            'permissions' => $permissions,
        ]);

        $this->headers = array_merge($this->headers, ['Authorization' => 'Bearer ' . $token]);
    }

    public function handle(Request $request): JsonResponse
    {
        if (count($this->headers) > 0) {
            foreach ($this->headers as $header => $value) {
                $request->setHeader($header, $value);
            }
        }

        return $this->router->dispatch($request);
    }
}