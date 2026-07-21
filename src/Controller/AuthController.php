<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\AuthService;
use App\Service\View;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Handles user authentication.
 */
final class AuthController
{
    public function __construct(
        private readonly View $view,
        private readonly AuthService $authService,
    ) {
    }

    public function showLogin(): Response
    {
        if ($this->authService->isAuthenticated()) {
            return new RedirectResponse('/');
        }

        return new Response(
            $this->view->render('auth/login', [
                'pageTitle' => 'Connexion',
                'currentUser' => null,
                'errors' => [],
                'old' => [],
            ])
        );
    }

    public function authenticate(Request $request): Response
    {
        if ($this->authService->isAuthenticated()) {
            return new RedirectResponse('/');
        }

        $email = trim((string) $request->request->get('email', ''));
        $password = (string) $request->request->get('password', '');

        $errors = [];

        if ($email === '') {
            $errors['email'] = 'L\'adresse email est obligatoire.';
        } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = 'L\'adresse email n\'est pas valide.';
        }

        if ($password === '') {
            $errors['password'] = 'Le mot de passe est obligatoire.';
        }

        if ($errors === [] && !$this->authService->attempt($email, $password)) {
            $errors['credentials'] = 'Les identifiants renseignés sont incorrects.';
        }

        if ($errors !== []) {
            return new Response(
                $this->view->render('auth/login', [
                    'pageTitle' => 'Connexion',
                    'currentUser' => null,
                    'errors' => $errors,
                    'old' => [
                        'email' => $email,
                    ],
                ]),
                Response::HTTP_UNPROCESSABLE_ENTITY,
            );
        }

        return new RedirectResponse('/');
    }

    public function logout(): Response
    {
        $this->authService->logout();

        return new RedirectResponse('/');
    }
}