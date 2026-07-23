<?php

declare(strict_types=1);

namespace App\Service;

/**
 * Generates and validates CSRF tokens stored in the user session.
 */
final class Csrf
{
    private const SESSION_KEY = 'csrf_tokens';

    public function __construct(
        private readonly Session $session,
    ) {
    }

    /**
     * Returns the token associated with a form.
     *
     * A new token is generated when none exists yet.
     */
    public function getToken(string $formName): string
    {
        $tokens = $this->getStoredTokens();

        if (isset($tokens[$formName])) {
            return $tokens[$formName];
        }

        $token = bin2hex(random_bytes(32));

        $tokens[$formName] = $token;

        $this->session->set(
            self::SESSION_KEY,
            $tokens,
        );

        return $token;
    }

    /**
     * Checks whether a submitted token matches the stored form token.
     */
    public function isTokenValid(
        string $formName,
        ?string $submittedToken,
    ): bool {
        if ($submittedToken === null || $submittedToken === '') {
            return false;
        }

        $tokens = $this->getStoredTokens();
        $storedToken = $tokens[$formName] ?? null;

        return $storedToken !== null
            && hash_equals($storedToken, $submittedToken);
    }

    /**
     * Removes a token after a successful write operation.
     */
    public function removeToken(string $formName): void
    {
        $tokens = $this->getStoredTokens();

        unset($tokens[$formName]);

        $this->session->set(
            self::SESSION_KEY,
            $tokens,
        );
    }

    /**
     * @return array<string, string>
     */
    private function getStoredTokens(): array
    {
        $tokens = $this->session->get(
            self::SESSION_KEY,
            [],
        );

        if (!is_array($tokens)) {
            return [];
        }

        return array_filter(
            $tokens,
            static fn (
                mixed $token,
                mixed $formName,
            ): bool => is_string($formName)
                && is_string($token),
            ARRAY_FILTER_USE_BOTH,
        );
    }
}