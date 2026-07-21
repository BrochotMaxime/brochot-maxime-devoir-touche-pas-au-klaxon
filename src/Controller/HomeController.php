<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * Handles public home page requests.
 */
final class HomeController
{
    public function __construct(
        private readonly View $view,
    ) {
    }

    public function index(): Response
    {
        return new Response(
            $this->view->render('home/index', [
                'pageTitle' => 'Accueil',
                'currentUser' => null,
            ])
        );
    }
}