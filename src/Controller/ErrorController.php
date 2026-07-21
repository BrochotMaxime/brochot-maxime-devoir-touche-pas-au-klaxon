<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * Handles application error pages.
 */
final class ErrorController
{
    public function __construct(
        private readonly View $view,
    ) {
    }

    public function notFound(): Response
    {
        return new Response(
            $this->view->render('error/404', [
                'pageTitle' => 'Page introuvable',
                'currentUser' => null,
            ]),
            Response::HTTP_NOT_FOUND,
        );
    }
}