<?php

declare(strict_types=1);

namespace App\Model;

/**
 * Represents a company employee.
 */
final readonly class User
{
    public function __construct(
        private int $id,
        private string $lastName,
        private string $firstName,
        private string $phone,
        private string $email,
        private string $password,
        private string $role,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getFullName(): string
    {
        return sprintf('%s %s', $this->firstName, $this->lastName);
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function isAdmin(): bool
    {
        return $this->role === 'ROLE_ADMIN';
    }
}