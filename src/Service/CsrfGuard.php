<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\InvalidCsrfTokenException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Validates CSRF tokens submitted through protected forms.
 */
final class CsrfGuard
{
    public function __construct(
        private readonly Csrf $csrf,
    ) {
    }

    /**
     * Validates the CSRF token submitted for a protected form.
     *
     * @throws InvalidCsrfTokenException When the token is missing or invalid.
     */
    public function validate(
        Request $request,
        string $formName,
    ): void {
        $submittedToken = $request->request->get(
            '_csrf_token'
        );

        if (
            !$this->csrf->isTokenValid(
                $formName,
                is_string($submittedToken)
                    ? $submittedToken
                    : null,
            )
        ) {
            throw new InvalidCsrfTokenException(
                'The submitted CSRF token is invalid.'
            );
        }
    }
}