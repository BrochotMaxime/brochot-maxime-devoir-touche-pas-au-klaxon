<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Agency;
use PDO;

/**
 * Provides database access for agencies.
 */
final class AgencyRepository
{
    public function __construct(
        private readonly PDO $connection,
    ) {
    }

    public function findById(int $id): ?Agency
    {
        $statement = $this->connection->prepare(
            'SELECT
                id,
                name
            FROM agencies
            WHERE id = :id'
        );

        $statement->execute([
            'id' => $id,
        ]);

        $row = $statement->fetch();

        if ($row === false) {
            return null;
        }

        return $this->hydrate($row);
    }

    /**
     * @return list<Agency>
     */
    public function findAll(): array
    {
        $statement = $this->connection->query(
            'SELECT
                id,
                name
            FROM agencies
            ORDER BY name ASC'
        );

        $agencies = [];

        foreach ($statement->fetchAll() as $row) {
            $agencies[] = $this->hydrate($row);
        }

        return $agencies;
    }

    /**
     * @param array<string, mixed> $row
     */
    private function hydrate(array $row): Agency
    {
        return new Agency(
            id: (int) $row['id'],
            name: (string) $row['name'],
        );
    }
}