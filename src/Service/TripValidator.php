<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\AgencyRepository;
use DateTimeImmutable;

/**
 * Validates trip form data.
 */
final class TripValidator
{
    public function __construct(
        private readonly AgencyRepository $agencyRepository,
    ) {
    }

    /**
     * @param array<string, string> $data
     *
     * @return array<string, string>
     */
    public function validate(array $data): array
    {
        $errors = [];

        $departureAgencyId = $this->validateAgency(
            $data['departure_agency_id'] ?? '',
            'departure_agency_id',
            'L\'agence de départ est obligatoire.',
            $errors,
        );

        $arrivalAgencyId = $this->validateAgency(
            $data['arrival_agency_id'] ?? '',
            'arrival_agency_id',
            'L\'agence d\'arrivée est obligatoire.',
            $errors,
        );

        if (
            $departureAgencyId !== null
            && $arrivalAgencyId !== null
            && $departureAgencyId === $arrivalAgencyId
        ) {
            $errors['arrival_agency_id'] =
                'L\'agence d\'arrivée doit être différente de l\'agence de départ.';
        }

        $departureDatetime = $this->validateDatetime(
            $data['departure_datetime'] ?? '',
            'departure_datetime',
            'La date et l\'heure de départ sont obligatoires.',
            'La date et l\'heure de départ ne sont pas valides.',
            $errors,
        );

        $arrivalDatetime = $this->validateDatetime(
            $data['arrival_datetime'] ?? '',
            'arrival_datetime',
            'La date et l\'heure d\'arrivée sont obligatoires.',
            'La date et l\'heure d\'arrivée ne sont pas valides.',
            $errors,
        );

        $now = new DateTimeImmutable();

        if (
            $departureDatetime !== null
            && $departureDatetime <= $now
        ) {
            $errors['departure_datetime'] =
                'La date et l\'heure de départ doivent être dans le futur.';
        }

        if (
            $departureDatetime !== null
            && $arrivalDatetime !== null
            && $arrivalDatetime <= $departureDatetime
        ) {
            $errors['arrival_datetime'] =
                'La date et l\'heure d\'arrivée doivent être postérieures au départ.';
        }

        $totalSeats = $this->validatePositiveInteger(
            $data['total_seats'] ?? '',
            'total_seats',
            'Le nombre total de places est obligatoire.',
            'Le nombre total de places doit être supérieur à zéro.',
            $errors,
        );

        $availableSeats = $this->validateNonNegativeInteger(
            $data['available_seats'] ?? '',
            'available_seats',
            'Le nombre de places disponibles est obligatoire.',
            'Le nombre de places disponibles doit être positif ou nul.',
            $errors,
        );

        if (
            $totalSeats !== null
            && $availableSeats !== null
            && $availableSeats > $totalSeats
        ) {
            $errors['available_seats'] =
                'Le nombre de places disponibles ne peut pas dépasser le nombre total de places.';
        }

        return $errors;
    }

    /**
     * @param array<string, string> $errors
     */
    private function validateAgency(
        string $value,
        string $field,
        string $requiredMessage,
        array &$errors,
    ): ?int {
        if ($value === '') {
            $errors[$field] = $requiredMessage;

            return null;
        }

        if (filter_var($value, FILTER_VALIDATE_INT) === false) {
            $errors[$field] = 'L\'agence sélectionnée n\'est pas valide.';

            return null;
        }

        $agencyId = (int) $value;

        if ($agencyId <= 0 || $this->agencyRepository->findById($agencyId) === null) {
            $errors[$field] = 'L\'agence sélectionnée n\'existe pas.';

            return null;
        }

        return $agencyId;
    }

    /**
     * @param array<string, string> $errors
     */
    private function validateDatetime(
        string $value,
        string $field,
        string $requiredMessage,
        string $invalidMessage,
        array &$errors,
    ): ?DateTimeImmutable {
        if ($value === '') {
            $errors[$field] = $requiredMessage;

            return null;
        }

        $datetime = DateTimeImmutable::createFromFormat(
            '!Y-m-d\TH:i',
            $value,
        );

        $dateErrors = DateTimeImmutable::getLastErrors();

        if (
            $datetime === false
            || (
                is_array($dateErrors)
                && (
                    $dateErrors['warning_count'] > 0
                    || $dateErrors['error_count'] > 0
                )
            )
        ) {
            $errors[$field] = $invalidMessage;

            return null;
        }

        return $datetime;
    }

    /**
     * @param array<string, string> $errors
     */
    private function validatePositiveInteger(
        string $value,
        string $field,
        string $requiredMessage,
        string $invalidMessage,
        array &$errors,
    ): ?int {
        if ($value === '') {
            $errors[$field] = $requiredMessage;

            return null;
        }

        if (filter_var($value, FILTER_VALIDATE_INT) === false) {
            $errors[$field] = $invalidMessage;

            return null;
        }

        $integer = (int) $value;

        if ($integer <= 0) {
            $errors[$field] = $invalidMessage;

            return null;
        }

        return $integer;
    }

    /**
     * @param array<string, string> $errors
     */
    private function validateNonNegativeInteger(
        string $value,
        string $field,
        string $requiredMessage,
        string $invalidMessage,
        array &$errors,
    ): ?int {
        if ($value === '') {
            $errors[$field] = $requiredMessage;

            return null;
        }

        if (filter_var($value, FILTER_VALIDATE_INT) === false) {
            $errors[$field] = $invalidMessage;

            return null;
        }

        $integer = (int) $value;

        if ($integer < 0) {
            $errors[$field] = $invalidMessage;

            return null;
        }

        return $integer;
    }
}