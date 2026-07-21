<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

/**
 * Handles application error pages.
 */
final class ErrorController
{
    public function notFound(): Response
    {
        return new Response(
            '<h1>404</h1><p>La page demandée est introuvable.</p>',
            Response::HTTP_NOT_FOUND,
        );
    }
}