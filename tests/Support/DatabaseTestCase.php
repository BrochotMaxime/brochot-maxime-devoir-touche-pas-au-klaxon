<?php

declare(strict_types=1);

namespace Tests\Support;

use App\Core\Database;
use App\Core\DatabaseConfig;
use PDO;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * Base class for integration tests using the dedicated test database.
 */
abstract class DatabaseTestCase extends TestCase
{
    protected PDO $connection;

    protected function setUp(): void
    {
        parent::setUp();

        $config = DatabaseConfig::fromEnvironment();

        if ($config->getDatabase() !== 'touche_pas_au_klaxon_test') {
            throw new RuntimeException(
                sprintf(
                    'Integration tests must use the test database. Current database: "%s".',
                    $config->getDatabase(),
                )
            );
        }

        $database = new Database($config);

        $this->connection = $database->getConnection();
        $this->connection->beginTransaction();
    }

    protected function tearDown(): void
    {
        if (
            isset($this->connection)
            && $this->connection->inTransaction()
        ) {
            $this->connection->rollBack();
        }

        parent::tearDown();
    }

    protected function getUserIdByEmail(string $email): int
    {
        $statement = $this->connection->prepare(
            'SELECT id
            FROM users
            WHERE email = :email'
        );

        $statement->execute([
            'email' => $email,
        ]);

        $id = $statement->fetchColumn();

        if ($id === false) {
            throw new RuntimeException(
                sprintf(
                    'Test user with email "%s" was not found.',
                    $email,
                )
            );
        }

        return (int) $id;
    }

    protected function getAgencyIdByName(string $name): int
    {
        $statement = $this->connection->prepare(
            'SELECT id
            FROM agencies
            WHERE name = :name'
        );

        $statement->execute([
            'name' => $name,
        ]);

        $id = $statement->fetchColumn();

        if ($id === false) {
            throw new RuntimeException(
                sprintf(
                    'Test agency named "%s" was not found.',
                    $name,
                )
            );
        }

        return (int) $id;
    }

    /**
     * @param array<string, bool|float|int|string|null> $parameters
     */
    protected function countRows(
        string $table,
        string $condition = '1 = 1',
        array $parameters = [],
    ): int {
        $allowedTables = [
            'users',
            'agencies',
            'trips',
        ];

        if (!in_array($table, $allowedTables, true)) {
            throw new RuntimeException(
                sprintf(
                    'Table "%s" is not allowed in test helpers.',
                    $table,
                )
            );
        }

        $statement = $this->connection->prepare(
            sprintf(
                'SELECT COUNT(*)
                FROM %s
                WHERE %s',
                $table,
                $condition,
            )
        );

        $statement->execute($parameters);

        return (int) $statement->fetchColumn();
    }
}