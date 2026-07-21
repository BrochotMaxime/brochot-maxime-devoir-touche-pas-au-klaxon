<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Trip;
use DateTimeImmutable;
use PDO;

/**
 * Provides database access for trips.
 */
final class TripRepository
{
    public function __construct(
        private readonly PDO $connection,
    ) {
    }

    public function findById(int $id): ?Trip
    {
        $statement = $this->connection->prepare(
            'SELECT
                id,
                departure_datetime,
                arrival_datetime,
                total_seats,
                available_seats,
                author_id,
                departure_agency_id,
                arrival_agency_id
            FROM trips
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
     * @return list<Trip>
     */
    public function findAll(): array
    {
        $statement = $this->connection->query(
            'SELECT
                id,
                departure_datetime,
                arrival_datetime,
                total_seats,
                available_seats,
                author_id,
                departure_agency_id,
                arrival_agency_id
            FROM trips
            ORDER BY departure_datetime ASC'
        );

        $trips = [];

        foreach ($statement->fetchAll() as $row) {
            $trips[] = $this->hydrate($row);
        }

        return $trips;
    }

    /**
     * @return list<Trip>
     */
    public function findByAuthorId(int $authorId): array
    {
        $statement = $this->connection->prepare(
            'SELECT
                id,
                departure_datetime,
                arrival_datetime,
                total_seats,
                available_seats,
                author_id,
                departure_agency_id,
                arrival_agency_id
            FROM trips
            WHERE author_id = :author_id
            ORDER BY departure_datetime ASC'
        );

        $statement->execute([
            'author_id' => $authorId,
        ]);

        $trips = [];

        foreach ($statement->fetchAll() as $row) {
            $trips[] = $this->hydrate($row);
        }

        return $trips;
    }

    /**
     * @param array<string, mixed> $row
     */
    private function hydrate(array $row): Trip
    {
        return new Trip(
            id: (int) $row['id'],
            departureDatetime: new DateTimeImmutable(
                (string) $row['departure_datetime']
            ),
            arrivalDatetime: new DateTimeImmutable(
                (string) $row['arrival_datetime']
            ),
            totalSeats: (int) $row['total_seats'],
            availableSeats: (int) $row['available_seats'],
            authorId: (int) $row['author_id'],
            departureAgencyId: (int) $row['departure_agency_id'],
            arrivalAgencyId: (int) $row['arrival_agency_id'],
        );
    }
}