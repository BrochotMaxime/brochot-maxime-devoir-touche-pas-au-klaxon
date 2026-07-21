<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * Handles authentication pages.
 */
final class AuthController
{
    public function __construct(
        private readonly View $view,
    ) {
    }

    public function login(): Response
    {
        return new Response(
            $this->view->render('auth/login', [
                'pageTitle' => 'Connexion',
                'currentUser' => null,
            ])
        );
    }
}