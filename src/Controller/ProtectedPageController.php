<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\AuthService;
use App\Service\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * Displays temporary protected pages until their features are implemented.
 */
final class ProtectedPageController
{
    public function __construct(
        private readonly View $view,
        private readonly AuthService $authService,
    ) {
    }

    public function adminDashboard(): Response
    {
        return new Response(
            $this->view->render('admin/dashboard', [
                'pageTitle' => 'Administration',
                'currentUser' => $this->authService->getUser(),
            ])
        );
    }
}