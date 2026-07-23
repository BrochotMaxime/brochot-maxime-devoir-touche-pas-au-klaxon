# Application Architecture

## Overview

Touche pas au klaxon is a PHP application built with a Model-View-Controller architecture.

The project separates HTTP handling, application logic, database access and view rendering in order to keep the code reusable, maintainable and testable.

All HTTP requests enter through `public/index.php`.

## Project Structure

### `config/`

Contains application configuration files.

- `bootstrap.php` loads environment variables and initializes the session.
- `routes.php` creates application dependencies and declares the routes.

### `database/`

Contains the SQL scripts used to create and populate the database.

### `public/`

Contains the application front controller and public assets.

The web server document root must point to this directory.

### `resources/`

Contains source files such as Sass stylesheets.

### `src/`

Contains the PHP source code and follows PSR-4 autoloading through the
`App` namespace.

- `Controller/`: handles HTTP requests and coordinates application actions
- `Core/`: contains shared infrastructure and configuration
- `Exception/`: contains application-specific exceptions
- `Model/`: contains immutable domain objects and read models
- `Repository/`: contains database queries and persistence logic
- `Service/`: contains reusable application behaviour

### `templates/`

Contains PHP views, layouts and reusable partials.

Templates must not execute SQL queries or contain application workflow logic.

### `tests/`

Contains PHPUnit unit and integration tests.

## MVC Conventions

### Controllers

Controllers receive HTTP input and coordinate the application workflow.

They may:

- call validators, services and repositories;
- select templates;
- create responses and redirects;
- choose HTTP status codes;
- add flash messages.

Controllers must not contain SQL queries.

### Models

Models represent application data through typed immutable objects.

They may expose small domain behaviours, such as checking whether a trip belongs to a given user.

Models do not access the database or render templates.

### Repositories

Repositories contain PDO queries and database persistence logic.

They:

- prepare and execute SQL statements;
- convert database rows into typed models or read models;
- expose application-oriented query methods.

Repositories do not create HTTP responses or render views.

### Services

Services contain reusable behaviour that does not belong directly to a controller or repository.

They handle concerns such as:

- authentication;
- session access;
- authorization;
- CSRF protection;
- flash messages;
- validation;
- view rendering.

### Views

Views receive prepared data from controllers and render HTML.

They must:

- escape dynamic values;
- avoid SQL queries;
- avoid persistence and application workflow logic.

Reusable interface fragments are stored in `templates/partials/`, while the shared document structure is stored in `templates/layouts/base.php`.

## Request Lifecycle

1. The browser sends an HTTP request.
2. `router.php` forwards the request to `public/index.php`.
3. Composer and the application bootstrap are loaded.
4. The configured router matches the HTTP method and requested URL.
5. Access-control and CSRF checks are executed when required.
6. A controller coordinates services, validators and repositories.
7. The controller returns a Symfony `Response` or `RedirectResponse`.
8. The router sends the response to the browser.

## Routing

Routes are declared in `config/routes.php` with Buki Router.

Each route associates:

- an HTTP method;
- a URL path;
- an anonymous route handler;
- optional access-control and CSRF checks;
- a controller action returning a Symfony response.

GET routes display pages and forms.

POST routes handle authentication and state-changing operations such as creation, update, deletion and logout.

Dynamic route parameters use the `:id` syntax. The router provides these parameters as strings, and route handlers convert database identifiers to integers before passing them to controllers.

Protected routes call `AccessGuard` before executing their controller. When access is denied, the guard returns either a login redirect or an HTTP 403 response.

State-changing routes validate their CSRF token before the controller action is executed. Invalid or missing tokens return the dedicated HTTP 419 response and prevent the operation.

Unknown URLs return the application's custom HTTP 404 response.

## Dependency Injection

The application uses explicit constructor dependency injection without a dependency injection container.

`config/routes.php` acts as the composition root. It creates shared infrastructure, repositories, services, validators and controllers, then injects their dependencies through constructors.

Classes must not create their own database connections, repositories or shared services when those dependencies can be injected.

## Environment and Database

Local configuration is stored in `.env`, which is excluded from Git.

The `.env.example` file documents the required environment variables.

`App\Core\DatabaseConfig` reads the database configuration, while
`App\Core\Database` creates the PDO connection.

Repositories receive the shared PDO connection and do not create their own.

The database schema and data rules are documented separately in `docs/database.md`.

## View Rendering

`App\Service\View` renders PHP templates inside the shared application layout.

Controllers provide template data and receive the generated HTML.

Dynamic values must be escaped with the global `escape()` helper before being displayed.

Flash messages and the CSRF service are made available to templates through the view-rendering service.

## Security

### Authentication

Authentication is handled by `App\Service\AuthService`.

User credentials are retrieved through `UserRepository`, and submitted passwords are verified against their stored hash.

After successful authentication, the session identifier is regenerated to prevent session fixation.

Only the minimum required user information is stored in the session.

### Authorization

Protected routes use `App\Service\AccessGuard`.

Unauthenticated visitors are redirected to the login page.

Authenticated standard users receive an HTTP 403 response when attempting to access administrator routes.

Authorization is always checked server-side. Hiding a link or button in a template is not considered sufficient protection.

Logout is handled through a POST request and clears the authenticated session.

### CSRF Protection

All state-changing forms include a CSRF token generated by `App\Service\Csrf`.

Tokens are stored in the PHP session and submitted through the hidden `_csrf_token` form field.

Protected POST routes validate tokens through `CsrfGuard` before executing controller actions.

Resource-specific tokens include the target identifier in their form name, preventing a token generated for one record from being reused for another.

### Output Escaping

Dynamic values rendered in templates must be escaped with `escape()`.

JavaScript inserts dynamic text with `textContent` when database values must be displayed without being interpreted as HTML.

## Application Conventions

### Validation

Form rules are handled by dedicated validator services before repository write operations are attempted.

The database remains responsible for final integrity constraints such as unique values and foreign-key relationships.

Validation depending on the current user or current date is handled by the PHP application.

### Post/Redirect/Get

Successful write operations follow the Post/Redirect/Get pattern.

Controllers add a flash message and redirect to the corresponding list page after creation, update or deletion.

Flash messages survive one redirect and are removed after their first display.

### Read Models

List pages may use dedicated immutable read models when their required data differs from the main domain model.

Database joins remain inside repositories, which return typed objects to controllers.

### Ownership

The authenticated user is used as the author of a newly created trip and is never accepted from submitted form data.

Trip edit and delete operations verify ownership server-side before performing any action.

Administrators may perform administration-specific actions only after their role has been verified.

## Architectural Principles

The project follows these principles:

- one public entry point;
- separation of responsibilities;
- SQL isolated in repositories;
- thin controllers;
- immutable typed models;
- simple templates;
- explicit dependency injection;
- server-side authorization;
- reusable security services;
- testable components.