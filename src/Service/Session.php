<?php

declare(strict_types=1);

namespace App\Service;

/**
 * Provides controlled access to the PHP session.
 */
final class Session
{
    public function has(string $key): bool
    {
        return array_key_exists($key, $_SESSION);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public function regenerateId(): void
    {
        session_regenerate_id(true);
    }

    public function clear(): void
    {
        $_SESSION = [];
    }
}