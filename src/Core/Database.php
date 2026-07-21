<?php

declare(strict_types=1);

namespace App\Core;

use PDO;

/**
 * Creates and provides the application database connection.
 */
final class Database
{
    private ?PDO $connection = null;

    public function __construct(
        private readonly DatabaseConfig $config,
    ) {
    }

    /**
     * Returns a shared PDO connection for the current Database instance.
     */
    public function getConnection(): PDO
    {
        if ($this->connection instanceof PDO) {
            return $this->connection;
        }

        $this->connection = new PDO(
            $this->config->getDsn(),
            $this->config->getUsername(),
            $this->config->getPassword(),
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ],
        );

        return $this->connection;
    }
}