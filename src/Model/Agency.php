<?php

declare(strict_types=1);

namespace App\Model;

/**
 * Represents a company agency.
 */
final readonly class Agency
{
    public function __construct(
        private int $id,
        private string $name,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}