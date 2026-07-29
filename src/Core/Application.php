<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Provides application-wide configuration available throughout the project.
 */
final class Application
{
    /**
     * Returns the public name of the application.
     */
    public function getName(): string
    {
        return 'Touche pas au klaxon';
    }

    /**
     * Indicates whether the application is running in demonstration mode.
     */
    public function isDemo(): bool
    {
        return filter_var(
            $_ENV['APP_DEMO'] ?? false,
            FILTER_VALIDATE_BOOL,
        );
    }
}