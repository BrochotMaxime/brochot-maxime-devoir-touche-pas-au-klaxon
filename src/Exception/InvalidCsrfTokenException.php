<?php

declare(strict_types=1);

namespace App\Exception;

use RuntimeException;

/**
 * Thrown when a submitted CSRF token is missing or invalid.
 */
final class InvalidCsrfTokenException extends RuntimeException
{
}