<?php

declare(strict_types=1);

namespace App\Model;

use DateTimeImmutable;

/**
 * Represents a trip proposed by an employee.
 */
final readonly class Trip
{
    public function __construct(
        private int $id,
        private DateTimeImmutable $departureDatetime,
        private DateTimeImmutable $arrivalDatetime,
        private int $totalSeats,
        private int $availableSeats,
        private int $authorId,
        private int $departureAgencyId,
        private int $arrivalAgencyId,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDepartureDatetime(): DateTimeImmutable
    {
        return $this->departureDatetime;
    }

    public function getArrivalDatetime(): DateTimeImmutable
    {
        return $this->arrivalDatetime;
    }

    public function getTotalSeats(): int
    {
        return $this->totalSeats;
    }

    public function getAvailableSeats(): int
    {
        return $this->availableSeats;
    }

    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    public function getDepartureAgencyId(): int
    {
        return $this->departureAgencyId;
    }

    public function getArrivalAgencyId(): int
    {
        return $this->arrivalAgencyId;
    }

    public function hasAvailableSeats(): bool
    {
        return $this->availableSeats > 0;
    }

    public function isOwnedBy(int $userId): bool
    {
        return $this->authorId === $userId;
    }
}