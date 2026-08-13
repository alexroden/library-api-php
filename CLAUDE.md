# CLAUDE.md

Guidance for Claude Code working in this repository.

## What this is

A from-scratch PHP 8.5 REST API framework (no Laravel/Symfony framework) built as a learning
exercise, plus a `library` domain (users, councils, libraries, authors, categories, books) on top of
it. `README.md` documents the framework's public surface in depth — query builder, models, RBAC,
importers. This file covers what the README does not: commands, wiring, and gotchas.

**PHP 8.5 is required**, not just supported. The code uses `const string` typed constants, the pipe
operator (`|>` in `tests/AbstractTestCase.php`), and `new Foo()->method()` without parentheses.

## Commands

```bash
composer test                      # PHPUnit — no Docker or MySQL needed (see Testing)
./vendor/bin/phpunit --filter Book # single test / group
composer docs                      # regenerate public/openapi.json from src/OpenApi attributes

make start                         # mysql + golang-migrate + api (:8080) + mailpit (:8025)
make console CMD="seed"            # run a console command in the api container
make trigger-runner                # create the SQS queue and run the book import runner once
make trigger-worker WORKERS=3      # start N book import workers; ctrl-c stops them
make cleanup                       # tear down containers, volumes and local images
```

`.env` is gitignored and there is **no tracked `.env.example`** — the file must already exist
locally. `docker-compose.yml` and `config/*.php` both read it. `config/queue.php` and
`config/soap.php` read `$_ENV[...]` directly with no default, so a missing key is a fatal error, not
a fallback.

CI (`.github/workflows/php.yml`) runs `composer validate --strict` then `composer test` on PHP 8.5.

## Request lifecycle

`public/index.php` → `bootstrap/router.php` → `bootstrap/app.php::createApplication()` builds the
`Container`, registers `CommandBus`/`EventBus` singletons and the three service providers →
`routes/api.php` registers routes → `Router::dispatch()`.

`index.php` is the only error boundary: `AbstractHttpException`/`ValidationException` become their
own status codes, anything else is a 500 whose message is only exposed when `APP_DEBUG` is truthy.

`Router::dispatch()` does the framework magic by reflection on the controller method:

- **Route model binding** — a parameter typed as an `AbstractModel` subclass whose name matches a
  route placeholder (`/{book}` → `Book $book`) is loaded by id, 404 if missing.
- **Request injection** — a `Request`/`FormRequest`-typed parameter is built via
  `$class::fromRequest($request)`. `FormRequest` validates in its constructor, so validation
  failures throw before the controller body runs.
- Anything else class-typed is resolved from the container.

`Container::resolve()` reflects over constructor parameters and calls `make()` on each type name —
it cannot resolve scalar or union-typed constructor params. Services needing scalars must be bound
explicitly with `bind()`/`singleton()` in a provider.

## Command/event bus

Controllers do not touch models for writes. They dispatch a readonly command and return a
`JsonResponse`:

```php
$book = $this->commandBus->dispatch(new CreateBookCommand(...$request->validated()));
```

`validated()` camel-cases request keys so they spread into named constructor arguments — a command's
property names must match the request field names in camelCase.

Handlers (`src/Bus/Handlers/`) do the persistence and dispatch an event via `$this->events`.
Wiring is config, not convention:

- `config/commands.php` — command class ⇒ handler class
- `config/events.php` — event class ⇒ array of listener classes

A command with no entry in `config/commands.php` throws at dispatch time. Events with no listeners
are still listed with an empty array — keep that habit so the full event surface is visible.

`Config::get('name')` loads `config/name.php` and **only returns arrays**; dot-notation
(`Config::get('queue.books')`) walks into nested keys and throws `RuntimeException` if absent.

## Adding a resource

An end-to-end resource touches all of these — grep an existing one (`Book`, `Council`) as the
template:

1. `database/migrations/NNNN_create_x_table.{up,down}.sql` (sequential prefix, MySQL syntax)
2. `src/Models/X.php` — `$table`, `$fillable`, `$hidden`, relationship methods
3. `src/Http/Requests/{Create,Update}XRequest.php` — `rules()`
4. `src/Bus/Commands/`, `src/Bus/Handlers/`, `src/Bus/Events/` for create/update/delete
5. Register in `config/commands.php` and `config/events.php`
6. `src/Enums/Permissions.php` constants + `config/role-permissions.php` grants
   (`SUPER_ADMIN` uses `Permissions::getConstants()` and picks up new ones automatically)
7. `src/Http/Controllers/XController.php` extending `AbstractController`
8. `routes/api.php` inside the `auth` middleware group
9. `src/OpenApi/{Endpoints,Requests,Responses,Schemas}/` attribute-only classes, then `composer docs`
10. `tests/Factories/XFactory.php` and `tests/Features/X/{Create,Get,List,Update,Delete}Test.php`

**Route middleware is the third argument to the router method, not a third element of the handler
array.** `routes/api.php` currently gets this wrong for libraries, authors, categories and books:

```php
// wrong — the permission array is silently ignored, so only `auth` applies
$router->post('/', [BookController::class, 'create', ['permission:'.Permissions::BOOKS_CREATE]]);
// right — as used by the users and councils groups
$router->post('/', [BookController::class, 'create'], ['permission:'.Permissions::BOOKS_CREATE]);
```

`Router::dispatch()` destructures only `[$controller, $action]`, so the extra element is dropped and
those routes are authenticated but not permission-checked. Follow the users/councils form for new
routes; don't copy the books/authors/categories/libraries form.

## Testing

Tests never touch MySQL. `AbstractTestCase::setUp()` resets the static `Connection` singleton, opens
`sqlite::memory:`, then builds the schema by reading every `database/migrations/*.up.sql` and
**regex-rewriting the MySQL DDL into SQLite** (dropping `COLLATE`, `INDEX` clauses and
`ON UPDATE CURRENT_TIMESTAMP`, converting `INT AUTO_INCREMENT PRIMARY KEY`).

This means **a new migration using MySQL syntax outside those handled patterns breaks the entire
suite**, not just one test. If tests start failing wholesale after a migration, look there first.

`AbstractFeaturesTestCase` boots the real container and routes, then swaps `Mailer` for `FakeMailer`
before registering `EventServiceProvider` — which is why `createApplication(true)` skips event
registration. Drive requests through `$this->handle(Request::create(...))` and call
`$this->asAuthorizedUser()` to get a `SUPER_ADMIN` bearer token. Because feature tests authorise as
super admin, they will not catch a missing permission middleware.

`Connection` is a process-wide static singleton with a `reset()` escape hatch; any test that opens a
connection outside `AbstractTestCase` must reset it.

## Importers

Separate autoload root (`AlexRoden\Importers\` → `importers/src`) with its own entry points.
`importers/bootstrap.php` loads the autoloader and optionally `.env` (in Docker the values arrive as
real env vars). Both processes build their dependencies by hand in `main.php` rather than resolving
them from the container.

`importers/runner/main.php` wires `BookClient` (SOAP, against `public/soap.php`) + `SqsPublisher`
into `BookImportRunner`, which fetches book summaries and publishes batches of ids to ElasticMQ. It
never touches the database, so it does not load `bootstrap/app.php`.

`importers/worker/main.php` wires `BookClient` + `SqsConsumer` into `BookImportWorker`, which
long-polls, calls `getBook` per id, and writes the records. **The worker is the one importer that
does load `bootstrap/app.php`** — it needs a `CommandBus` with the handlers registered, so writes go
through `CreateAuthorCommand` / `CreateCategoryCommand` / `CreateBookCommand` exactly like the api.
Because of that, `Dockerfile.importers` (shared by both services) also copies `bootstrap/`.

Worker invariants worth preserving:

- Every lookup is first-or-create (`Author` by first+last name, `Category` by name, `Book` by
  title), which is what makes the import re-runnable against the unique constraints.
- `assignAuthor`/`assignCategory` are called even for a book that already existed, and both ignore a
  link that is already there — that repairs a partial earlier import.
- A batch is deleted from the queue only if every id in it succeeded; otherwise it is left to become
  visible again and be retried in full.
- An empty receive is not a stop condition — the worker keeps long-polling. It only returns from
  `run()` when `stop()` is called, which `worker/main.php` wires to `SIGTERM`/`SIGINT` via
  `pcntl_async_signals` (hence `pcntl` in `Dockerfile.importers`), and it finishes the batch it is
  holding first. Unit tests wrap the consumer in a decorator that calls `stop()` on the first empty
  receive, so a test that drives its own consumer must arrange its own stop or it will hang.
- `AuthorName::fromString()` splits on the first word only, so multi-word last names survive.

Both processes only echo progress; `main.php` is where fatal errors go to STDERR and exit non-zero.

This is the active work on `feature/importers` and most of it is still uncommitted.

## Conventions

- Namespaces: `AlexRoden\LibraryApiPhp\` → `src/`, `AlexRoden\Importers\` → `importers/src`,
  `AlexRoden\LibraryApiPhp\Tests\` → `tests/`.
- Controller methods are named `create`, `get`, `list`, `update`, `delete` and return `JsonResponse`.
- Writes wrap dispatch in try/catch, mapping `PDOException` → `DatabaseException` and other
  `Exception` → `InternalServiceException`.
- Responses are `{"data": ...}`, list endpoints add a `meta` block with
  `total`/`limit`/`offset`/`count`/`has_more`; 201 on create, 204 on delete.
- **Always import dependencies as a `use` statement at the top of the file** — including global
  classes like `Exception`, `PDOException`, `JsonException`, `SoapFault`, `ReflectionClass` and
  `Throwable`. Never reference a class by an inline fully-qualified name (`\Exception`) and never
  rely on the global namespace fallback inside a namespaced file.
- Commands and value objects are `readonly class` with promoted public properties.
- Permissions and roles are `const string` on `Permissions`/`Roles`, never inline strings.
- OpenAPI classes are empty bodies carrying `OA\*` attributes; `public/openapi.json` is generated —
  edit the attributes, then run `composer docs`.
- Branch from `develop` (PRs target `develop`); commit messages are commonly `#<issue>: summary`.
