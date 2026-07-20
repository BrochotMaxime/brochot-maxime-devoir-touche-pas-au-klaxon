# Application architecture

## Overview

Touche pas au klaxon follows a Model-View-Controller architecture.

All HTTP requests enter the application through the front controller located at `public/index.php`.

## Main directories

### `config/`

Contains the application bootstrap and configuration files.

### `database/`

Contains the database creation and initial data scripts.

### `public/`

Contains the front controller and publicly accessible assets.

This directory must be used as the web server document root.

### `resources/`

Contains source files that must be compiled, such as Sass stylesheets.

### `src/`

Contains the PHP application source code and follows PSR-4 autoloading through the `App` namespace.

- `Controller/`: receives HTTP requests and coordinates responses.
- `Core/`: contains reusable application infrastructure.
- `Model/`: contains domain models.
- `Repository/`: contains database access logic.
- `Service/`: contains reusable business and application services.

### `templates/`

Contains PHP presentation templates.

Templates must not execute database queries or contain business logic.

### `tests/`

Contains PHPUnit tests.

- `Unit/`: tests isolated classes and validation rules.
- `Integration/`: tests interactions with infrastructure such as the database.

## Request lifecycle

1. The web server directs the request to `public/index.php`.
2. The front controller loads Composer and the application bootstrap.
3. The router matches the requested URL.
4. The corresponding controller is executed.
5. The controller calls repositories or services.
6. The controller renders a template or redirects the user.

## Architectural principles

- Keep controllers focused on HTTP request coordination.
- Keep SQL queries inside repositories.
- Keep business rules outside templates.
- Reuse shared behavior through services and core components.
- Use dependency injection instead of creating dependencies throughout the code.