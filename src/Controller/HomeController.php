<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

/**
 * Handles public home page requests.
 */
final class HomeController
{
    public function index(): Response
    {
        return new Response(
            '<h1>Touche pas au klaxon</h1><p>Liste des trajets à venir.</p>'
        );
    }
}