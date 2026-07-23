<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

final class EnvironmentTest extends TestCase
{
    public function testTestEnvironmentIsLoaded(): void
    {
        self::assertSame(
            'test',
            $_ENV['APP_ENV'] ?? null,
        );

        self::assertSame(
            'touche_pas_au_klaxon_test',
            $_ENV['DB_NAME'] ?? null,
        );
    }
}