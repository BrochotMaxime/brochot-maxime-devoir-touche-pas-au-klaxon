<?php

declare(strict_types=1);

if (!function_exists('escape')) {
    /**
     * Escapes a scalar value for safe HTML output.
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

if (!function_exists('csrfField')) {
    /**
     * Generates an escaped hidden input containing a CSRF token.
     */
    function csrfField(string $token): string
    {
        return sprintf(
            '<input type="hidden" name="_csrf_token" value="%s">',
            escape($token),
        );
    }
}