<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\AuthService;
use App\Service\View;
use App\Service\Flash;
use App\Service\Csrf;
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
        private readonly Flash $flash,
        private readonly Csrf $csrf,
    ) {
    }

    /**
     * Displays the login form or redirects an already authenticated user.
     */
    public function showLogin(): Response
    {
        if ($this->authService->isAuthenticated()) {
            return new RedirectResponse(
                $this->authService->getRedirectPath()
            );
        }

        return new Response(
            $this->view->render('auth/login', [
                'pageTitle' => 'Connexion',
                'currentUser' => null,
                'errors' => [],
                'old' => [],
                'csrfToken' => $this->csrf->getToken('login'),
            ])
        );
    }

    /**
     * Validates submitted credentials and authenticates the user.
     *
     * Invalid form data or credentials return an HTTP 422 response without distinguishing whether the email or password was incorrect.
     */
    public function authenticate(Request $request): Response
    {
        if ($this->authService->isAuthenticated()) {
            return new RedirectResponse(
                $this->authService->getRedirectPath()
            );
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
                    'csrfToken' => $this->csrf->getToken('login'),
                ]),
                Response::HTTP_UNPROCESSABLE_ENTITY,
            );
        }

        $this->flash->success(
            'Vous êtes maintenant connecté.'
        );

        return new RedirectResponse(
            $this->authService->getRedirectPath()
        );
    }

    /**
     * Logs out the current user and redirects to the home page.
     */
    public function logout(): Response
    {
        $this->authService->logout();

        $this->flash->success(
            'Vous avez été déconnecté avec succès.'
        );

        return new RedirectResponse('/');
    }
}