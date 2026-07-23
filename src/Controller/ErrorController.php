<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\AuthService;
use App\Service\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * Handles application error pages.
 */
final class ErrorController
{
    public function __construct(
        private readonly View $view,
        private readonly AuthService $authService,
    ) {
    }

    /**
     * Returns the HTTP 403 forbidden response.
     */
    public function forbidden(): Response
    {
        return new Response(
            $this->view->render('error/403', [
                'pageTitle' => 'Accès interdit',
                'currentUser' => $this->authService->getUser(),
            ]),
            Response::HTTP_FORBIDDEN,
        );
    }

    /**
     * Returns the application response for an expired or invalid CSRF token.
     *
     * The non-standard HTTP 419 status represents an expired form session.
     */
    public function invalidCsrfToken(): Response
    {
        return new Response(
            $this->view->render('error/419', [
                'pageTitle' => 'Session expirée',
                'currentUser' => $this->authService->getUser(),
            ]),
            419,
        );
    }

    /**
     * Returns the HTTP 404 not-found response.
     */
    public function notFound(): Response
    {
        return new Response(
            $this->view->render('error/404', [
                'pageTitle' => 'Page introuvable',
                'currentUser' => $this->authService->getUser(),
            ]),
            Response::HTTP_NOT_FOUND,
        );
    }
}