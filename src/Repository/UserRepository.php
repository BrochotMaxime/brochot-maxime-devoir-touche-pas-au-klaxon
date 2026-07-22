<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\User;
use PDO;

/**
 * Provides database access for users.
 */
final class UserRepository
{
    public function __construct(
        private readonly PDO $connection,
    ) {
    }

    public function findById(int $id): ?User
    {
        $statement = $this->connection->prepare(
            'SELECT
                id,
                last_name,
                first_name,
                phone,
                email,
                password,
                role
            FROM users
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

    public function findByEmail(string $email): ?User
    {
        $statement = $this->connection->prepare(
            'SELECT
                id,
                last_name,
                first_name,
                phone,
                email,
                password,
                role
            FROM users
            WHERE email = :email'
        );

        $statement->execute([
            'email' => $email,
        ]);

        $row = $statement->fetch();

        if ($row === false) {
            return null;
        }

        return $this->hydrate($row);
    }

    /**
     * @return list<User>
     */
    public function findAll(): array
    {
        $statement = $this->connection->query(
            'SELECT
                id,
                last_name,
                first_name,
                phone,
                email,
                password,
                role
            FROM users
            ORDER BY last_name ASC, first_name ASC'
        );

        $users = [];

        foreach ($statement->fetchAll() as $row) {
            $users[] = $this->hydrate($row);
        }

        return $users;
    }

    public function countAll(): int
    {
        $statement = $this->connection->query(
            'SELECT COUNT(*) FROM users'
        );

        return (int) $statement->fetchColumn();
    }

    /**
     * @param array<string, mixed> $row
     */
    private function hydrate(array $row): User
    {
        return new User(
            id: (int) $row['id'],
            lastName: (string) $row['last_name'],
            firstName: (string) $row['first_name'],
            phone: (string) $row['phone'],
            email: (string) $row['email'],
            password: (string) $row['password'],
            role: (string) $row['role'],
        );
    }
}