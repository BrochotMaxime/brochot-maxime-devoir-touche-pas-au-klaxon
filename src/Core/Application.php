<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Represents the main application.
 */
final class Application
{
    /**
     * Returns the application name.
     */
    public function getName(): string
    {
        return 'Touche pas au klaxon';
    }
}