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

