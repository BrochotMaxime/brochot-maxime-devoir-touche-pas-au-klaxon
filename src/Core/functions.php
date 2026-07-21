<?php

declare(strict_types=1);

if (!function_exists('escape')) {
    /**
     * Escapes a value before displaying it in HTML.
     */
    function escape(string|int|float|null $value): string
    {
        return htmlspecialchars(
            (string) $value,
            ENT_QUOTES | ENT_SUBSTITUTE,
            'UTF-8',
        );
    }
}