<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\AgencyRepository;

/**
 * Validates agency form data.
 */
final class AgencyValidator
{
    public function __construct(
        private readonly AgencyRepository $agencyRepository,
    ) {
    }

    /**
     * @return array<string, string>
     */
    public function validate(
        string $name,
        ?int $excludedId = null,
    ): array {
        $errors = [];

        if ($name === '') {
            $errors['name'] = 'Le nom de l\'agence est obligatoire.';

            return $errors;
        }

        if (mb_strlen($name) > 100) {
            $errors['name'] =
                'Le nom de l\'agence ne peut pas dépasser 100 caractères.';
        }

        if (
            $errors === []
            && $this->agencyRepository->existsByName(
                $name,
                $excludedId,
            )
        ) {
            $errors['name'] =
                'Une agence possède déjà ce nom.';
        }

        return $errors;
    }
}