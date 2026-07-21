<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

/**
 * Handles authentication pages.
 */
final class AuthController
{
    public function login(): Response
    {
        return new Response(
            '<h1>Connexion</h1><p>Le formulaire sera ajouté ultérieurement.</p>'
        );
    }
}