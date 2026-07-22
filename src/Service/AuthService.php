<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\User;
use App\Repository\UserRepository;

/**
 * Handles user authentication and authenticated session data.
 */
final class AuthService
{
    private const SESSION_KEY = 'authenticated_user';

    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly Session $session,
    ) {
    }

    /**
     * Attempts to authenticate a user.
     */
    public function attempt(string $email, string $password): bool
    {
        $user = $this->userRepository->findByEmail($email);

        if ($user === null) {
            return false;
        }

        if (!password_verify($password, $user->getPassword())) {
            return false;
        }

        $this->login($user);

        return true;
    }

    public function isAuthenticated(): bool
    {
        return $this->session->has(self::SESSION_KEY);
    }

    /**
     * Returns the currently authenticated user data.
     *
     * @return array{
     *     id: int,
     *     firstName: string,
     *     lastName: string,
     *     email: string,
     *     phone: string,
     *     role: string
     * }|null
     */
    public function getUser(): ?array
    {
        $user = $this->session->get(self::SESSION_KEY);

        if (!is_array($user)) {
            return null;
        }

        return $user;
    }

    private function login(User $user): void
    {
        $this->session->regenerateId();

        $this->session->set(self::SESSION_KEY, [
            'id' => $user->getId(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'email' => $user->getEmail(),
            'phone' => $user->getPhone(),
            'role' => $user->getRole(),
        ]);
    }

    public function logout(): void
    {
        $this->session->clear();
        $this->session->regenerateId();
    }

    public function isAdmin(): bool
    {
        $user = $this->getUser();

        return $user !== null
            && $user['role'] === 'ROLE_ADMIN';
    }

    public function getRedirectPath(): string
    {
        return $this->isAdmin()
            ? '/admin'
            : '/';
    }
}