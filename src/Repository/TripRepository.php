<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Trip;
use App\Model\TripListItem;
use App\Model\AdminTripListItem;

use DateTimeImmutable;
use PDO;

/**
 * Provides trip persistence and application-specific trip queries.
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
     * Returns all trips for the administrator list.
     *
     * @return list<AdminTripListItem>
     */
    public function findAllForAdministration(): array
    {
        $statement = $this->connection->query(
            'SELECT
                trips.id,
                trips.departure_datetime,
                trips.arrival_datetime,
                trips.total_seats,
                trips.available_seats,
                users.first_name AS author_first_name,
                users.last_name AS author_last_name,
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
            ORDER BY trips.departure_datetime DESC'
        );

        $trips = [];

        foreach ($statement->fetchAll() as $row) {
            $trips[] = $this->hydrateAdminListItem($row);
        }

        return $trips;
    }

    /**
     * Returns trips created by a given user.
     *
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
     * Creates a trip and returns its identifier.
     *
     * @param array{
     *     departure_datetime: string,
     *     arrival_datetime: string,
     *     total_seats: int,
     *     available_seats: int,
     *     author_id: int,
     *     departure_agency_id: int,
     *     arrival_agency_id: int
     * } $data
     */
    public function create(array $data): int
    {
        $statement = $this->connection->prepare(
            'INSERT INTO trips (
                departure_datetime,
                arrival_datetime,
                total_seats,
                available_seats,
                author_id,
                departure_agency_id,
                arrival_agency_id
            ) VALUES (
                :departure_datetime,
                :arrival_datetime,
                :total_seats,
                :available_seats,
                :author_id,
                :departure_agency_id,
                :arrival_agency_id
            )'
        );

        $statement->execute([
            'departure_datetime' => $data['departure_datetime'],
            'arrival_datetime' => $data['arrival_datetime'],
            'total_seats' => $data['total_seats'],
            'available_seats' => $data['available_seats'],
            'author_id' => $data['author_id'],
            'departure_agency_id' => $data['departure_agency_id'],
            'arrival_agency_id' => $data['arrival_agency_id'],
        ]);

        return (int) $this->connection->lastInsertId();
    }

    /**
     * Updates the editable data of an existing trip.
     *
     * @param array{
     *     departure_datetime: string,
     *     arrival_datetime: string,
     *     total_seats: int,
     *     available_seats: int,
     *     departure_agency_id: int,
     *     arrival_agency_id: int
     * } $data
     */
    public function update(int $id, array $data): bool
    {
        $statement = $this->connection->prepare(
            'UPDATE trips
            SET
                departure_datetime = :departure_datetime,
                arrival_datetime = :arrival_datetime,
                total_seats = :total_seats,
                available_seats = :available_seats,
                departure_agency_id = :departure_agency_id,
                arrival_agency_id = :arrival_agency_id
            WHERE id = :id'
        );

        return $statement->execute([
            'id' => $id,
            'departure_datetime' => $data['departure_datetime'],
            'arrival_datetime' => $data['arrival_datetime'],
            'total_seats' => $data['total_seats'],
            'available_seats' => $data['available_seats'],
            'departure_agency_id' => $data['departure_agency_id'],
            'arrival_agency_id' => $data['arrival_agency_id'],
        ]);
    }

    public function delete(int $id): bool
    {
        $statement = $this->connection->prepare(
            'DELETE FROM trips
            WHERE id = :id'
        );

        return $statement->execute([
            'id' => $id,
        ]);
    }

    public function countAll(): int
    {
        $statement = $this->connection->query(
            'SELECT COUNT(*) FROM trips'
        );

        return (int) $statement->fetchColumn();
    }

    public function countUpcoming(): int
    {
        $statement = $this->connection->query(
            'SELECT COUNT(*)
            FROM trips
            WHERE departure_datetime >= NOW()'
        );

        return (int) $statement->fetchColumn();
    }

    /**
     * Hydrates a trip entity from a database row.
     *
     * @param array{
     *     id: int|string,
     *     departure_datetime: string,
     *     arrival_datetime: string,
     *     total_seats: int|string,
     *     available_seats: int|string,
     *     author_id: int|string,
     *     departure_agency_id: int|string,
     *     arrival_agency_id: int|string
     * } $row
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
     * Hydrates a public trip list item from a joined database row.
     *
     * @param array{
     *     id: int|string,
     *     departure_datetime: string,
     *     arrival_datetime: string,
     *     total_seats: int|string,
     *     available_seats: int|string,
     *     author_id: int|string,
     *     author_first_name: string,
     *     author_last_name: string,
     *     author_phone: string,
     *     author_email: string,
     *     departure_agency: string,
     *     arrival_agency: string
     * } $row
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

    /**
     * Hydrates an administrator trip list item from a joined database row.
     *
     * @param array{
     *     id: int|string,
     *     departure_datetime: string,
     *     arrival_datetime: string,
     *     total_seats: int|string,
     *     available_seats: int|string,
     *     author_first_name: string,
     *     author_last_name: string,
     *     author_email: string,
     *     departure_agency: string,
     *     arrival_agency: string
     * } $row
     */
    private function hydrateAdminListItem(
        array $row,
    ): AdminTripListItem {
        return new AdminTripListItem(
            id: (int) $row['id'],
            departureDatetime: new DateTimeImmutable(
                (string) $row['departure_datetime']
            ),
            arrivalDatetime: new DateTimeImmutable(
                (string) $row['arrival_datetime']
            ),
            totalSeats: (int) $row['total_seats'],
            availableSeats: (int) $row['available_seats'],
            authorFirstName: (string) $row['author_first_name'],
            authorLastName: (string) $row['author_last_name'],
            authorEmail: (string) $row['author_email'],
            departureAgency: (string) $row['departure_agency'],
            arrivalAgency: (string) $row['arrival_agency'],
        );
    }
}