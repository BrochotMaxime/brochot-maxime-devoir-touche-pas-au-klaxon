# Application Architecture

## Overview

Touche pas au klaxon is a PHP application built with a Model-View-Controller architecture.

The project is designed to keep responsibilities separated and make the code reusable, maintainable and easy to understand.

All HTTP requests enter through `public/index.php`.

## Main Directories

### `config/`

Contains application configuration files:

- `bootstrap.php`
- `routes.php`

### `database/`

Contains the SQL scripts used to create and populate the database.

### `public/`

Contains the front controller and public assets.

The web server document root must point to this directory.

### `resources/`

Contains source files such as Sass stylesheets.

### `src/`

Contains the PHP source code and follows PSR-4 autoloading through the `App` namespace.

- `Controller/`: handles HTTP requests and responses
- `Core/`: contains shared infrastructure
- `Model/`: contains immutable domain objects representing users, agencies and trips
- `Repository/`: contains PDO queries and converts database rows into domain models
- `Service/`: contains reusable application logic

### `templates/`

Contains PHP views.

Templates must not execute SQL queries or contain business logic.

### `tests/`

Contains PHPUnit unit and integration tests.

## Request Lifecycle

1. The browser sends a request.
2. `router.php` forwards it to `public/index.php`.
3. Composer and the application bootstrap are loaded.
4. The router matches the requested URL.
5. A controller is executed.
6. The controller calls services or repositories.
7. A response, template or redirect is returned.

## Routing

Routes are declared in `config/routes.php`.

Unknown URLs return a custom HTTP 404 response.

## Environment Configuration

Local configuration is stored in `.env`.

The file is excluded from Git.

The `.env.example` file documents the required variables.

## Database Connection

The application uses PDO.

`App\Core\DatabaseConfig` reads the database configuration.

`App\Core\Database` provides the PDO connection.

Repositories receive this connection and do not create their own.

## Architectural Principles

- one public entry point
- separation of responsibilities
- SQL isolated in repositories
- thin controllers
- simple templates
- dependency injection
- secure configuration
- testable components

## View Rendering

The `App\Service\View` service renders PHP templates inside the shared application layout.

Controllers provide template data and receive the generated HTML.

Shared interface elements are stored in `templates/partials/`, while the main document structure is stored in `templates/layouts/base php`.

Dynamic values must be escaped with the global `escape()` helper before being displayed.