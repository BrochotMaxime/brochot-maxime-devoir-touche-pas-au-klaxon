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
        private int $totalSeats,
        private int $availableSeats,
        private int $authorId,
        private string $authorFirstName,
        private string $authorLastName,
        private string $authorPhone,
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

    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    public function getAuthorFirstName(): string
    {
        return $this->authorFirstName;
    }

    public function getAuthorLastName(): string
    {
        return $this->authorLastName;
    }

    public function getAuthorFullName(): string
    {
        return sprintf(
            '%s %s',
            $this->authorFirstName,
            $this->authorLastName,
        );
    }

    public function getAuthorPhone(): string
    {
        return $this->authorPhone;
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

    public function isOwnedBy(int $userId): bool
    {
        return $this->authorId === $userId;
    }
}