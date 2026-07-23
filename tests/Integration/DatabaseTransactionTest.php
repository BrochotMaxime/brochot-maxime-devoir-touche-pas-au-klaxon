<?php

declare(strict_types=1);

namespace Tests\Integration;

use Tests\Support\DatabaseTestCase;

final class DatabaseTransactionTest extends DatabaseTestCase
{
    public function testDatabaseConnectionUsesTestDatabase(): void
    {
        $statement = $this->connection->query(
            'SELECT DATABASE()'
        );

        self::assertSame(
            'touche_pas_au_klaxon_test',
            $statement->fetchColumn(),
        );
    }

    public function testSeedDataIsAvailable(): void
    {
        self::assertSame(
            3,
            $this->countRows('users'),
        );

        self::assertSame(
            4,
            $this->countRows('agencies'),
        );

        self::assertSame(
            1,
            $this->countRows('trips'),
        );
    }

    public function testWriteOperationCanBeExecutedInsideTransaction(): void
    {
        $initialCount = $this->countRows('agencies');

        $statement = $this->connection->prepare(
            'INSERT INTO agencies (name)
            VALUES (:name)'
        );

        $statement->execute([
            'name' => 'Temporary Test Agency',
        ]);

        self::assertSame(
            $initialCount + 1,
            $this->countRows('agencies'),
        );
    }

    public function testPreviousTestDataWasRolledBack(): void
    {
        self::assertSame(
            0,
            $this->countRows(
                'agencies',
                'name = :name',
                [
                    'name' => 'Temporary Test Agency',
                ],
            ),
        );

        self::assertSame(
            4,
            $this->countRows('agencies'),
        );
    }
}