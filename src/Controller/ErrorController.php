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