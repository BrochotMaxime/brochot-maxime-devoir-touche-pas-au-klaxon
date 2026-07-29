<?php

declare(strict_types=1);

namespace App\Service;

use App\Core\Application;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Blocks destructive actions while the application runs in demo mode.
 *
 * @param string $redirectTo Route to redirect the user to.
 *
 * @return Response|null A redirect response when the action is blocked,
 *                       or null when it may continue.
 */
final class DemoGuard
{
    private const DEMO_MESSAGE = 'Cette action n\'est pas disponible dans l\'environnement de démonstration.';

    public function __construct(
        private readonly Application $application,
        private readonly Flash $flash,
    ) {
    }

    /**
     * Blocks destructive actions while the application runs in demo mode.
     *
     * @param string $redirectTo Route to redirect the user to.
     *
     * @return Response|null Null when the action is allowed.
     */
    public function requireDestructiveActionAllowed(
        string $redirectTo,
    ): ?Response {
        if (!$this->application->isDemo()) {
            return null;
        }

        $this->flash->warning(self::DEMO_MESSAGE);

        return new RedirectResponse($redirectTo);
    }
}