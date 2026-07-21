<?php

declare(strict_types=1);

namespace App\Model;

use DateTimeImmutable;

/**
 * Represents the information required to display a trip in a list.
 */
final readonly class TripListItem
{
    public function __construct(
        private int $id,
        private DateTimeImmutable $departureDatetime,
        private DateTimeImmutable $arrivalDatetime,
        private int $availableSeats,
        private string $departureAgency,
        private string $arrivalAgency,
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

    public function getAvailableSeats(): int
    {
        return $this->availableSeats;
    }

    public function getDepartureAgency(): string
    {
        return $this->departureAgency;
    }

    public function getArrivalAgency(): string
    {
        return $this->arrivalAgency;
    }
}