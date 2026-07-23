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

    public function findByName(string $name): ?Agency
    {
        $statement = $this->connection->prepare(
            'SELECT
                id,
                name
            FROM agencies
            WHERE name = :name'
        );

        $statement->execute([
            'name' => $name,
        ]);

        $row = $statement->fetch();

        if ($row === false) {
            return null;
        }

        return $this->hydrate($row);
    }

    /**
     * Checks whether an agency name is already used.
     *
     * @param int|null $excludedId Agency identifier ignored during an update.
     */
    public function existsByName(
        string $name,
        ?int $excludedId = null,
    ): bool {
        $sql = 'SELECT COUNT(*)
            FROM agencies
            WHERE name = :name';

        $parameters = [
            'name' => $name,
        ];

        if ($excludedId !== null) {
            $sql .= ' AND id <> :excluded_id';
            $parameters['excluded_id'] = $excludedId;
        }

        $statement = $this->connection->prepare($sql);
        $statement->execute($parameters);

        return (int) $statement->fetchColumn() > 0;
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

    public function countAll(): int
    {
        $statement = $this->connection->query(
            'SELECT COUNT(*) FROM agencies'
        );

        return (int) $statement->fetchColumn();
    }

    public function create(string $name): int
    {
        $statement = $this->connection->prepare(
            'INSERT INTO agencies (name)
            VALUES (:name)'
        );

        $statement->execute([
            'name' => $name,
        ]);

        return (int) $this->connection->lastInsertId();
    }

    public function update(int $id, string $name): bool
    {
        $statement = $this->connection->prepare(
            'UPDATE agencies
            SET name = :name
            WHERE id = :id'
        );

        return $statement->execute([
            'id' => $id,
            'name' => $name,
        ]);
    }

    /**
     * Checks whether an agency is used as a departure or arrival agency.
     */
    public function isUsedByTrips(int $id): bool
    {
        $statement = $this->connection->prepare(
            'SELECT COUNT(*)
            FROM trips
            WHERE departure_agency_id = :departure_agency_id
            OR arrival_agency_id = :arrival_agency_id'
        );

        $statement->execute([
            'departure_agency_id' => $id,
            'arrival_agency_id' => $id,
        ]);

        return (int) $statement->fetchColumn() > 0;
    }

    public function delete(int $id): bool
    {
        $statement = $this->connection->prepare(
            'DELETE FROM agencies
            WHERE id = :id'
        );

        return $statement->execute([
            'id' => $id,
        ]);
    }

    /**
     * Hydrates an agency from a database row.
     *
     * @param array{
     *     id: int|string,
     *     name: string
     * } $row
     */
    private function hydrate(array $row): Agency
    {
        return new Agency(
            id: (int) $row['id'],
            name: (string) $row['name'],
        );
    }
}