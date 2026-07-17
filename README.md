# Library Api PHP

## Overview

This project is a personal training exercise designed to explore and reinforce modern PHP development practices by building a RESTful API framework from scratch.

Rather than relying on an existing framework such as Laravel or Symfony, the goal is to gain a deeper understanding of the core components that power web applications, including routing, middleware, authentication, request validation, database interaction, and API documentation.

The project is intended for learning, experimentation, and continuous improvement. As new concepts are explored and best practices are learned, they are incorporated into the codebase to evolve the framework over time.

While the project aims to follow modern PHP standards and conventions, it should not be considered production-ready software. Instead, it serves as a practical environment for experimenting with architecture, testing, and API design.

## Project Structure

The project is organised into a number of directories, each with a clearly defined responsibility. The intention is to keep concerns separated and make the codebase easy to navigate and extend.

```text
.
├── bin/
│   ├── Console commands (e.g. database seeders)
│   └── OpenAPI documentation generator
├── config/
│   └── Application configuration (e.g. roles and permissions)
├── database/
│   ├── migrations/
│   └── seeders/
├── public/
│   ├── index.php          # Application entry point
│   └── openapi.json       # Generated OpenAPI specification
├── routes/
│   └── API route definitions
├── src/
│   ├── Authentication/    # JWT authentication
│   ├── Commands/          # Console command implementations
│   ├── Config/            # Configuration loader
│   ├── Database/          # Database connection and query builder
│   ├── Enums/             # Roles, permissions and other enums
│   ├── Exceptions/        # Framework exceptions
│   ├── Http/
│   │   ├── Controllers/   # API controllers
│   │   ├── Exceptions/    # HTTP-specific exceptions
│   │   ├── Foundation/    # Core request and response classes
│   │   ├── Helpers/       # Response helpers
│   │   ├── Middlewares/   # Authentication, permissions, etc.
│   │   └── Requests/      # Form request validation
│   ├── Models/            # Application models
│   ├── helpers.php        # Global helper functions
│   ├── OpenApi.php        # OpenAPI configuration
│   └── Router.php         # HTTP router
└── tests/
    ├── Unit tests
    └── Feature tests
```

### Directory Overview

| Directory    | Purpose                                                                                                                                                               |
| ------------ | --------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **bin**      | Contains executable scripts such as console commands, database seeders, and the OpenAPI documentation generator.                                                      |
| **config**   | Stores application configuration files. This includes configuration for features such as roles, permissions, and other application settings.                          |
| **database** | Contains database migrations and seeders used to create and populate the application's schema.                                                                        |
| **public**   | The web root of the application. It contains the `index.php` bootstrap file along with generated public assets such as the OpenAPI specification.                     |
| **routes**   | Defines the application's HTTP routes, keeping routing separate from controller logic.                                                                                |
| **src**      | Contains the application's source code, organised into logical components such as authentication, database access, HTTP handling, routing, models, and configuration. |
| **tests**    | PHPUnit unit and feature tests covering the framework and application behaviour.                                                                                      |


I like this structure because it keeps the framework-style infrastructure (`Router`, `Database`, `Http`, `Authentication`) separate from the application layer (`Controllers`, `Models`, `Requests`). As the project grows, it will be straightforward to introduce additional features such as events, queues, caching, or service providers without significantly changing the existing layout.

## Project Setup

This project is designed to be quick to get up and running using Docker and the provided `Makefile`.

### Starting the project

Run the following command:

```bash
make start
```

This command will:

* Build and start all required Docker containers.
* Create and initialise the MySQL database.
* Run all database migrations.
* Start the PHP application.

Once complete, the API will be ready to use.

### Running console commands

The project includes a lightweight console command runner for tasks such as database seeding.

For example:

```bash
make console CMD="seed"
```

Additional commands can be added by creating new command classes and invoking them through the console runner.

### Cleaning up

When you have finished working on the project, you can completely remove the Docker environment by running:

```bash
make cleanup
```

This command stops and removes all containers, networks and Docker volumes created by the project, leaving your system in a clean state.

## Database Migrations

Database schema changes are managed using raw SQL migration files rather than an ORM or migration builder. Each migration consists of two files:

* **Up migration** – Applies the schema change.
* **Down migration** – Reverts the schema change.

This approach keeps the generated SQL explicit, database-specific, and easy to review.

### Running migrations

Migrations are executed automatically when running:

```bash
make start
```

This ensures the local development database is always created with the latest schema.

### Testing

The test suite automatically executes the migrations before the tests run, ensuring each test is executed against the current database schema.

This provides confidence that:

* All migrations are valid and executable.
* The application code and database schema remain in sync.
* Schema changes cannot accidentally break the test environment.

By using the same migrations in both development and testing, the project guarantees that the schema being tested is identical to the schema used during local development.

## Database Query Builder

The project includes a lightweight database query builder that acts as a thin wrapper around raw SQL. Its goal is to provide a fluent interface for building queries while still generating straightforward SQL under the hood.

Unlike a full ORM, the query builder does not attempt to abstract away SQL. Instead, it focuses on making common queries easier to construct while allowing the resulting SQL to remain predictable.

### Model Mapping

When a model class is supplied, query results are automatically hydrated into instances of that model.

For example, the following query returns an array of `Role` models:

```php
return $this->DB(
    'user_roles',
    Role::class,
)->where(
    'user_id',
    '=',
    $this->id,
)->join(
    'roles',
    'role_id',
    'id',
    ['id', 'name', 'created_at', 'updated_at'],
)->excludeLocalAttributes()->get();
```

### Joining Tables

Joins can optionally specify which columns should be selected from the joined table. This helps reduce the amount of data returned and prevents ambiguous column names.

When querying through a pivot or relationship table, `excludeLocalAttributes()` can be used to remove the columns belonging to the starting table from the final result.

For example, when querying through the `user_roles` table, calling `excludeLocalAttributes()` removes the `user_roles` columns so that only the `roles` attributes are mapped onto the returned `Role` models.

### Retrieving Results

The `get()` method returns all matching records.

```php
public function get(
    ?int $limit = null,
    ?int $offset = null,
    ?bool $excludeModelMapping = false,
): array
```

The optional parameters allow you to:

* Limit the number of returned records.
* Offset the results for pagination.
* Disable automatic model hydration.

### Returning Raw Arrays

Automatic model mapping can be disabled when only a subset of fields is required.

For example, this query returns only the permission names as arrays rather than hydrated model instances:

```php
public function permissions(): array
{
    return array_map(
        fn (array $row) => $row['name'],
        $this->DB('user_roles')
            ->where('user_id', '=', $this->id)
            ->join('roles', 'role_id', 'id')
            ->join('role_permissions', 'id', 'role_id')
            ->join(
                'permissions',
                'role_permissions.permission_id',
                'id',
                ['name'],
            )
            ->excludeLocalAttributes()
            ->get(excludeModelMapping: true)
    );
}
```

### Additional Operations

The query builder also provides convenience methods for common database operations, including:

* `first()` – Returns the first matching record.
* `insert()` – Inserts a new record.
* `update()` – Updates matching records.
* `delete()` – Deletes matching records.

The query builder is intentionally lightweight and is expected to grow over time as additional functionality is required by the project.

## Models

All application models extend the `AbstractModel` class, which provides a lightweight Active Record-style interface built on top of the project's query builder.

The aim of the model layer is to encapsulate model-specific behaviour while delegating all database interactions to the `DB` query builder.

### Defining a Model

Each model defines the following properties:

* **`$table`** – The database table associated with the model.
* **`$fillable`** – The attributes that may be mass assigned during create and update operations.
* **`$hidden`** – Attributes that should be excluded when serialising the model to an array or JSON.

For example:

```php
class User extends AbstractModel
{
    protected string $table = 'users';

    protected array $fillable = [
        'email',
        'password',
        'first_name',
        'last_name',
    ];

    protected array $hidden = [
        'password',
    ];
}
```

### Querying Models

The abstract model exposes convenience methods that internally utilise the query builder.

Finding a model by its primary key:

```php
$user = User::find(1);
```

Building a query:

```php
$user = (new User())
    ->where('email', '=', 'admin@example.com')
    ->first();
```

Because the underlying query builder is returned, all of its functionality—such as joins, pagination, and model mapping—is immediately available.

### Creating Records

Models can be created directly without interacting with the query builder.

```php
$user = (new User())->create([
    'email' => 'admin@example.com',
    'password' => $hashedPassword,
    'first_name' => 'Admin',
    'last_name' => 'User',
]);
```

Only attributes listed in the model's `$fillable` property will be persisted.

### Updating Records

Existing models can be updated using the `update()` method.

```php
$user->update([
    'first_name' => 'Alex',
]);
```

As with creation, only fillable attributes are included in the generated SQL.

### Model Attributes

Database columns are stored internally within the model's attribute collection and are exposed through PHP's magic property accessors.

```php
echo $user->email;

$user->first_name = 'Alex';
```

This provides a clean and familiar interface without requiring explicit getter and setter methods.

### Serialisation

Models implement PHP's `JsonSerializable` interface, making them straightforward to return in API responses.

The following methods are available:

* `toArray()` – Returns the model as an array.
* `toJson()` – Returns the model as a JSON string.
* `jsonSerialize()` – Enables automatic serialisation when passed to `json_encode()`.

Any attributes defined in the model's `$hidden` property are automatically excluded from all serialised output.

### Extending Models

The `AbstractModel` is intentionally lightweight and provides only the common functionality required by every model.

Individual models are encouraged to define their own domain-specific methods and relationships. For example, the `User` model exposes methods for authentication, retrieving assigned roles, and resolving permissions, while still relying on the shared functionality provided by the base model.

## Roles and Permissions

The project includes a simple role-based access control (RBAC) system to manage API authorisation.

Roles and permissions are defined using PHP constants, providing type safety, IDE auto-completion, and avoiding the use of hard-coded strings throughout the codebase.

### Roles

The following roles are available:

| Role          | Description                                            |
| ------------- | ------------------------------------------------------ |
| `SUPER_ADMIN` | Has every permission available within the application. |
| `ADMIN`       | Full access to user management.                        |
| `EDITOR`      | Can create, view, list and update users.               |
| `STAFF`       | Read-only access to user information.                  |
| `USER`        | Basic access to user information.                      |

### Permissions

The relationship between roles and permissions is configured in `config/role-permissions.php`.

| Permission     | Super Admin | Admin | Editor | Staff | User |
| -------------- | :---------: | :---: | :----: | :---: | :--: |
| `users.create` |      ✓      |   ✓   |    ✓   |       |      |
| `users.get`    |      ✓      |   ✓   |    ✓   |   ✓   |   ✓  |
| `users.list`   |      ✓      |   ✓   |    ✓   |   ✓   |      |
| `users.update` |      ✓      |   ✓   |    ✓   |       |      |
| `users.delete` |      ✓      |   ✓   |        |       |      |

The `SUPER_ADMIN` role is configured using `Permissions::getConstants()`, meaning it automatically receives every permission defined by the application. As new permissions are added, they become available to the `SUPER_ADMIN` role without requiring any changes to the configuration.

### Route Protection

Routes can be protected using the built-in authentication and permission middleware.

For example:

```php
$router->post(
    '/users',
    [UserController::class, 'create'],
    ['permission:users.create']
);
```

When a request reaches a protected route, the permission middleware verifies that the authenticated user has the required permission before allowing the request to continue.
