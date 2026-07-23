<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Provides application-level metadata.
 */
final class Application
{
    public function getName(): string
    {
        return 'Touche pas au klaxon';
    }
}