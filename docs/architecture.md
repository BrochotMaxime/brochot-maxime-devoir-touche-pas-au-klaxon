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

## Authentication

Authentication is handled by `App\Service\AuthService`.

User credentials are checked against data retrieved by `UserRepository`.

Only the password hash is stored in the database. Submitted passwords are verified with PHP's password verification functions.

After successful authentication, the session identifier is regenerated and the minimum required user information is stored in the session.

Controllers retrieve the authenticated user through `AuthService` and pass it to the shared layout.

## Authorization

Protected routes use `App\Service\AccessGuard`.

Unauthenticated visitors are redirected to the login page.

Authenticated standard users receive an HTTP 403 response when attempting to access administrator pages.

Authorization is checked server-side for every protected route. Hiding a link or button in a template is not considered sufficient protection.

Logout is handled through a POST request and destroys both server-side session data and the session cookie.

## Flash Messages

Temporary feedback messages are handled by `App\Service\Flash`.

Messages are stored in the PHP session, survive one redirect and are removed after their first display.

The shared layout renders flash messages through `templates/partials/flash.php`.

Supported message types are:

- success
- danger
- warning
- info

Write operations will follow the Post/Redirect/Get pattern and add a success or error message before redirecting to the corresponding list page.

## Styling

The interface uses Bootstrap and Sass.

Bootstrap variables are overridden before importing the framework so that the supplied graphic palette is applied to standard components.

Sass source files are stored in `resources/scss/`.

Compiled CSS is generated in `public/assets/css/main.css`.

Available commands:

- `npm run sass:build`
- `npm run sass:watch`

## Read Models

List pages may use dedicated read models when their data differs from the main domain model.

`TripListItem` represents the joined trip and agency information required by the public home page.

The corresponding SQL query remains inside `TripRepository`.