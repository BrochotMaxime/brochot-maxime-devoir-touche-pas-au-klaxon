<?php

declare(strict_types=1);

use Dotenv\Dotenv;

$rootPath = dirname(__DIR__);

/**
 * Initializes environment configuration, timezone and secure session settings.
 */
$dotenv = Dotenv::createImmutable($rootPath);
$dotenv->safeLoad();

date_default_timezone_set(
    $_ENV['APP_TIMEZONE'] ?? 'Europe/Paris'
);

/**
 * Loads environment configuration and initializes the application session.
 *
 * Session cookies are restricted from JavaScript, protected with SameSite and marked as secure when the application runs in production.
 */
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_name('touche_pas_au_klaxon_session');

    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => ($_ENV['APP_ENV'] ?? 'development') === 'production',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    session_start();
}