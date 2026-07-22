<?php

declare(strict_types=1);

namespace App\Model;

use DateTimeImmutable;

/**
 * Represents the information required by the administrator trip list.
 */
final readonly class AdminTripListItem
{
    public function __construct(
        private int $id,
        private DateTimeImmutable $departureDatetime,
        private DateTimeImmutable $arrivalDatetime,
        private int $totalSeats,
        private int $availableSeats,
        private string $authorFirstName,
        private string $authorLastName,
        private string $authorEmail,
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

    public function getTotalSeats(): int
    {
        return $this->totalSeats;
    }

    public function getAvailableSeats(): int
    {
        return $this->availableSeats;
    }

    public function getAuthorFullName(): string
    {
        return sprintf(
            '%s %s',
            $this->authorFirstName,
            $this->authorLastName,
        );
    }

    public function getAuthorEmail(): string
    {
        return $this->authorEmail;
    }

    public function getDepartureAgency(): string
    {
        return $this->departureAgency;
    }

    public function getArrivalAgency(): string
    {
        return $this->arrivalAgency;
    }

    public function isPast(): bool
    {
        return $this->departureDatetime < new DateTimeImmutable();
    }

    public function isFull(): bool
    {
        return $this->availableSeats === 0;
    }
}