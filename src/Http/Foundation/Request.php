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
        protected ?string $method = 'GET',
        protected ?string $uri = '/',
        ?array $query = [],
        ?array $body = null,
        ?array $headers = [],
        ?array $files = [],
    ) {
        $this->method = strtoupper($method ?? 'GET');
        $this->uri = $uri ?? '/';

        $this->query = $query ?? [];
        $this->files = $files ?? [];
        $this->headers = $this->getHeaders($headers ?? []);

        if ($body !== null) {
            $this->body = $body;
        } else {
            $contentType = $this->headers['Content-Type'] ?? '';

            if (str_contains($contentType, 'application/json')) {
                $this->body = json_decode(file_get_contents('php://input'), true) ?? [];
            } else {
                $this->body = [];
            }
        }
    }

    public function all(): array
    {
        return array_merge($this->query, $this->body);
    }

    public function bearerToken(): string
    {
        $token = $this->headers['Authorization']
            ?? $this->headers['authorization']
            ?? '';

        $prefix = 'Bearer ';

        if (str_starts_with($token, $prefix)) {
            return substr($token, strlen($prefix));
        }

        return $token;
    }

    public function body(): array
    {
        return $this->body;
    }

    public static function create(
        string $method,
        string $uri,
        array $query = [],
        array $body = [],
        array $headers = [],
        array $files = [],
    ): self {
        return new self(
            method: strtoupper($method),
            uri: $uri,
            query: $query,
            body: $body,
            headers: $headers,
            files: $files,
        );
    }

    public static function capture(): self
    {
        return new self(
            method: $_SERVER['REQUEST_METHOD'] ?? 'GET',
            uri: parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH),
            query: $_GET,
            body: null,
            headers: [],
            files: $_FILES,
        );
    }

    public function except(array $keys): array
    {
        return array_diff_key(
            $this->all(),
            array_flip($keys)
        );
    }

    public function files(): array
    {
        return $this->files;
    }

    public static function fromRequest(Request $request): static
    {
        return new static(
            method: $request->method(),
            uri: $request->uri(),
            query: $request->query(),
            body: $request->body(),
            headers: $request->headers(),
            files: $request->files(),
        );
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->all());
    }

    public function headers(): array
    {
        return $this->headers;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->all()[$key] ?? $default;
    }

    public function method(): string
    {
        return $this->method;
    }

    public function only(array $keys): array
    {
        return array_intersect_key(
            $this->all(),
            array_flip($keys)
        );
    }

    public function setHeader(string $key, mixed $value): void
    {
        $this->headers[$key] = $value;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function query(): array
    {
        return $this->query;
    }

    public function uri(): string
    {
        return $this->uri;
    }

    private function getHeaders(array $headers = []): array
    {
        if (function_exists('getallheaders')) {
            if (getallheaders()) $headers = array_merge($headers, getallheaders());
        }

        foreach ($_SERVER as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $header = substr($key, 5)
                        |> strtolower(...)
                        |> (fn($x) => ucwords($x, '_'))
                        |> (fn($x) => str_replace('_', '-', $x));

                $headers[$header] = $value;
            }
        }

        return $headers;
    }
}
