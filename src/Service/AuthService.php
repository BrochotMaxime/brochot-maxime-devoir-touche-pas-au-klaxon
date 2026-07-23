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
     * Authenticates valid credentials and initializes the user session.
     *
     * Returns false without revealing whether the email or password was invalid.
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
     * Returns the validated authenticated user data stored in the session.
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

        if (
            !is_array($user)
            || !isset(
                $user['id'],
                $user['firstName'],
                $user['lastName'],
                $user['email'],
                $user['phone'],
                $user['role'],
            )
            || !is_int($user['id'])
            || !is_string($user['firstName'])
            || !is_string($user['lastName'])
            || !is_string($user['email'])
            || !is_string($user['phone'])
            || !is_string($user['role'])
        ) {
            return null;
        }

        return $user;
    }

    private function login(User $user): void
    {
        // Prevent session fixation before storing authenticated user data.
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

    /**
     * Clears authentication data and renews the session identifier.
     */
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

    /**
     * Returns the post-login destination for the authenticated user's role.
     */
    public function getRedirectPath(): string
    {
        return $this->isAdmin()
            ? '/admin'
            : '/';
    }
}