<?php

namespace AlexRoden\LibraryApiPhp\Http;

use AlexRoden\LibraryApiPhp\Models\User;

class Request
{
    protected array $query;
    protected array $body;
    protected array $files;
    protected array $headers;

    protected ?User $user = null;

    public function __construct()
    {
        $this->query = $_GET;
        $this->files = $_FILES;
        $this->headers = getallheaders();

        $contentType = $this->headers['Content-Type'] ?? '';
        if (str_contains($contentType, 'application/json')) {
            $this->body = json_decode(file_get_contents('php://input'), true) ?? [];
        } else {
            $this->body = $_POST;
        }
    }

    public function all(): array
    {
        return array_merge($this->query, $this->body);
    }

    public function bearerToken(): string
    {
        return $this->headers['Authorization'] ?? '';
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

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }
}