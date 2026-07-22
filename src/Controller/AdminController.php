<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\AgencyRepository;
use App\Repository\TripRepository;
use App\Repository\UserRepository;
use App\Service\AuthService;
use App\Service\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * Handles the administrator dashboard.
 */
final class AdminController
{
    public function __construct(
        private readonly View $view,
        private readonly AuthService $authService,
        private readonly UserRepository $userRepository,
        private readonly AgencyRepository $agencyRepository,
        private readonly TripRepository $tripRepository,
    ) {
    }

    public function dashboard(): Response
    {
        return new Response(
            $this->view->render('admin/dashboard', [
                'pageTitle' => 'Administration',
                'currentUser' => $this->authService->getUser(),
                'statistics' => [
                    'users' => $this->userRepository->countAll(),
                    'agencies' => $this->agencyRepository->countAll(),
                    'trips' => $this->tripRepository->countAll(),
                    'upcomingTrips' =>
                        $this->tripRepository->countUpcoming(),
                ],
            ])
        );
    }

    public function users(): Response
    {
        return new Response(
            $this->view->render('admin/users/index', [
                'pageTitle' => 'Utilisateurs',
                'currentUser' => $this->authService->getUser(),
                'users' => $this->userRepository->findAll(),
            ])
        );
    }
}