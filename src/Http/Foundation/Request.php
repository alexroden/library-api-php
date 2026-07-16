<?php

namespace AlexRoden\LibraryApiPhp\Http\Foundation;

use AlexRoden\LibraryApiPhp\Models\User;

class Request
{
    protected array $query;
    protected array $body;
    protected array $files;
    protected array $headers;

    protected ?User $user = null;

    public function __construct(
        ?array $query = null,
        ?array $body = null,
        ?array $headers = null,
        ?array $files = null,
    )
    {
        $this->query = $query ?? $_GET;
        $this->files = $files ?? $_FILES;
        $this->headers = $this->getHeaders($headers);

        if ($body) $this->body = $body;
        else {
            $contentType = $this->headers['Content-Type'] ?? '';
            if (str_contains($contentType, 'application/json')) {
                $this->body = json_decode(file_get_contents('php://input'), true) ?? [];
            } else {
                $this->body = $_POST;
            }
        }
    }

    public function all(): array
    {
        return array_merge($this->query, $this->body);
    }

    public function bearerToken(): string
    {
        $token = $this->headers['Authorization'];
        if ($token === "") {
            $token = $this->headers['authorization'];
        }

        $prefix = 'Bearer ';
        if (str_starts_with($token, $prefix)) {
            $token = substr($token, strlen($prefix));
        }

        return $token ?? '';
    }

    public function except(array $keys): array
    {
        return array_diff_key(
            $this->all(),
            array_flip($keys)
        );
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->all());
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->all()[$key] ?? $default;
    }

    public function only(array $keys): array
    {
        return array_intersect_key(
            $this->all(),
            array_flip($keys)
        );
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }



    private function getHeaders(array $headers = []): array
    {

        if (function_exists('getallheaders')) {
            if (getallheaders()) $headers = array_merge($headers, getallheaders());
        }

        foreach ($_SERVER as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $header = str_replace(
                    '_',
                    '-',
                    ucwords(strtolower(substr($key, 5)), '_')
                );

                $headers[$header] = $value;
            }
        }

        return $headers;
    }
}