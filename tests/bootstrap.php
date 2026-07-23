<?php

declare(strict_types=1);

use Dotenv\Dotenv;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$rootPath = dirname(__DIR__);

$dotenv = Dotenv::createMutable(
    $rootPath,
    '.env.test',
);

$dotenv->load();

date_default_timezone_set(
    $_ENV['APP_TIMEZONE'] ?? 'Europe/Paris'
);

$databaseName = $_ENV['DB_NAME'] ?? '';

if ($databaseName !== 'touche_pas_au_klaxon_test') {
    throw new RuntimeException(
        sprintf(
            'Tests must use the dedicated database. Current database: "%s".',
            $databaseName,
        )
    );
}