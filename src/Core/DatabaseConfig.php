<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Stores the database connection configuration.
 */
final readonly class DatabaseConfig
{
    public function __construct(
        private string $host,
        private int $port,
        private string $database,
        private string $username,
        private string $password,
        private string $charset = 'utf8mb4',
    ) {
    }

    /**
     * Creates the database configuration from environment variables.
     */
    public static function fromEnvironment(): self
    {
        return new self(
            host: (string) ($_ENV['DB_HOST'] ?? '127.0.0.1'),
            port: (int) ($_ENV['DB_PORT'] ?? 3306),
            database: (string) ($_ENV['DB_NAME'] ?? ''),
            username: (string) ($_ENV['DB_USER'] ?? ''),
            password: (string) ($_ENV['DB_PASSWORD'] ?? ''),
            charset: (string) ($_ENV['DB_CHARSET'] ?? 'utf8mb4'),
        );
    }

    /**
     * Returns the PDO Data Source Name.
     */
    public function getDsn(): string
    {
        return sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            $this->host,
            $this->port,
            $this->database,
            $this->charset,
        );
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getDatabase(): string
    {
        return $this->database;
    }
}