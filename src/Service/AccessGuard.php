<?php

declare(strict_types=1);

namespace App\Service;

use App\Controller\ErrorController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controls access to authenticated and administrator areas.
 */
final class AccessGuard
{
    public function __construct(
        private readonly AuthService $authService,
        private readonly ErrorController $errorController,
    ) {
    }

    /**
     * Redirects unauthenticated visitors to the login page.
     */
    public function requireAuthentication(): ?Response
    {
        if ($this->authService->isAuthenticated()) {
            return null;
        }

        return new RedirectResponse('/login');
    }

    /**
     * Protects administrator-only pages.
     */
    public function requireAdministrator(): ?Response
    {
        if (!$this->authService->isAuthenticated()) {
            return new RedirectResponse('/login');
        }

        if (!$this->authService->isAdmin()) {
            return $this->errorController->forbidden();
        }

        return null;
    }
}