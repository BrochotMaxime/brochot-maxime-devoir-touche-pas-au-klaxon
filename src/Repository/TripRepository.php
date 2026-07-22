<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Trip;
use App\Model\TripListItem;

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
     * Returns future trips that still have available seats.
     *
     * @return list<TripListItem>
     */
    public function findPublicAvailableTrips(): array
    {
        $statement = $this->connection->query(
            'SELECT
                trips.id,
                trips.departure_datetime,
                trips.arrival_datetime,
                trips.total_seats,
                trips.available_seats,
                trips.author_id,
                users.first_name AS author_first_name,
                users.last_name AS author_last_name,
                users.phone AS author_phone,
                users.email AS author_email,
                departure_agency.name AS departure_agency,
                arrival_agency.name AS arrival_agency
            FROM trips
            INNER JOIN users
                ON users.id = trips.author_id
            INNER JOIN agencies AS departure_agency
                ON departure_agency.id = trips.departure_agency_id
            INNER JOIN agencies AS arrival_agency
                ON arrival_agency.id = trips.arrival_agency_id
            WHERE trips.departure_datetime >= NOW()
              AND trips.available_seats > 0
            ORDER BY trips.departure_datetime ASC'
        );
    
        $trips = [];
    
        foreach ($statement->fetchAll() as $row) {
            $trips[] = $this->hydrateListItem($row);
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

    /**
     * @param array<string, mixed> $row
     */
    private function hydrateListItem(array $row): TripListItem
    {
        return new TripListItem(
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
            authorFirstName: (string) $row['author_first_name'],
            authorLastName: (string) $row['author_last_name'],
            authorPhone: (string) $row['author_phone'],
            authorEmail: (string) $row['author_email'],
            departureAgency: (string) $row['departure_agency'],
            arrivalAgency: (string) $row['arrival_agency'],
        );
    }
}