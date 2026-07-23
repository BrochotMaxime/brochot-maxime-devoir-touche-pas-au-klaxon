<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\AgencyRepository;
use App\Repository\TripRepository;
use App\Repository\UserRepository;
use App\Service\AuthService;
use App\Service\View;
use App\Service\AgencyValidator;
use App\Service\Flash;
use App\Service\Csrf;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Handles administrator dashboard and management actions.
 */
final class AdminController
{
    public function __construct(
        private readonly View $view,
        private readonly AuthService $authService,
        private readonly UserRepository $userRepository,
        private readonly AgencyRepository $agencyRepository,
        private readonly TripRepository $tripRepository,
        private readonly AgencyValidator $agencyValidator,
        private readonly Flash $flash,
        private readonly Csrf $csrf,
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

    public function agencies(): Response
    {
        return new Response(
            $this->view->render('admin/agencies/index', [
                'pageTitle' => 'Agences',
                'currentUser' => $this->authService->getUser(),
                'agencies' => $this->agencyRepository->findAll(),
            ])
        );
    }

    public function createAgency(): Response
    {
        return new Response(
            $this->view->render('admin/agencies/create', [
                'pageTitle' => 'Ajouter une agence',
                'currentUser' => $this->authService->getUser(),
                'errors' => [],
                'old' => [
                    'name' => '',
                ],
                'csrfToken' => $this->csrf->getToken(
                    'agency_create'
                ),
            ])
        );
    }

    public function storeAgency(Request $request): Response
    {
        $name = trim(
            (string) $request->request->get('name', '')
        );

        $errors = $this->agencyValidator->validate($name);

        if ($errors !== []) {
            return new Response(
                $this->view->render('admin/agencies/create', [
                    'pageTitle' => 'Ajouter une agence',
                    'currentUser' => $this->authService->getUser(),
                    'errors' => $errors,
                    'old' => [
                        'name' => $name,
                    ],
                    'csrfToken' => $this->csrf->getToken(
                        'agency_create'
                    ),
                ]),
                Response::HTTP_UNPROCESSABLE_ENTITY,
            );
        }

        $this->agencyRepository->create($name);

        $this->flash->success(
            'L’agence a été créée avec succès.'
        );

        return new RedirectResponse('/admin/agencies');
    }

    public function editAgency(int $id): Response
    {
        $agency = $this->agencyRepository->findById($id);

        if ($agency === null) {
            $this->flash->error(
                'L\'agence demandée est introuvable.'
            );

            return new RedirectResponse('/admin/agencies');
        }

        return new Response(
            $this->view->render('admin/agencies/edit', [
                'pageTitle' => 'Modifier une agence',
                'currentUser' => $this->authService->getUser(),
                'agency' => $agency,
                'errors' => [],
                'old' => [
                    'name' => $agency->getName(),
                ],
                'csrfToken' => $this->csrf->getToken(
                    sprintf('agency_update_%d', $id)
                ),
            ])
        );
    }

    public function updateAgency(
        Request $request,
        int $id,
    ): Response {
        $agency = $this->agencyRepository->findById($id);

        if ($agency === null) {
            $this->flash->error(
                'L\'agence demandée est introuvable.'
            );

            return new RedirectResponse('/admin/agencies');
        }

        $name = trim(
            (string) $request->request->get('name', '')
        );

        $errors = $this->agencyValidator->validate(
            $name,
            $id,
        );

        if ($errors !== []) {
            return new Response(
                $this->view->render('admin/agencies/edit', [
                    'pageTitle' => 'Modifier une agence',
                    'currentUser' => $this->authService->getUser(),
                    'agency' => $agency,
                    'errors' => $errors,
                    'old' => [
                        'name' => $name,
                    ],
                    'csrfToken' => $this->csrf->getToken(
                        sprintf('agency_update_%d', $id)
                    ),
                ]),
                Response::HTTP_UNPROCESSABLE_ENTITY,
            );
        }

        $this->agencyRepository->update($id, $name);

        $this->flash->success(
            'L’agence a été modifiée avec succès.'
        );

        return new RedirectResponse('/admin/agencies');
    }

    /**
     * Deletes an agency only when it is not referenced by any trip.
     */
    public function deleteAgency(int $id): Response
    {
        $agency = $this->agencyRepository->findById($id);

        if ($agency === null) {
            $this->flash->error(
                'L\'agence demandée est introuvable.'
            );

            return new RedirectResponse('/admin/agencies');
        }

        if ($this->agencyRepository->isUsedByTrips($id)) {
            $this->flash->error(
                'Impossible de supprimer cette agence : elle est utilisée par un ou plusieurs trajets.'
            );

            return new RedirectResponse('/admin/agencies');
        }

        $this->agencyRepository->delete($id);

        $this->flash->success(
            'L\'agence a été supprimée avec succès.'
        );

        return new RedirectResponse('/admin/agencies');
    }

    public function trips(): Response
    {
        return new Response(
            $this->view->render('admin/trips/index', [
                'pageTitle' => 'Trajets',
                'currentUser' => $this->authService->getUser(),
                'trips' => $this->tripRepository
                    ->findAllForAdministration(),
            ])
        );
    }

    public function deleteTrip(int $id): Response
    {
        $trip = $this->tripRepository->findById($id);

        if ($trip === null) {
            $this->flash->error(
                'Le trajet demandé est introuvable.'
            );

            return new RedirectResponse('/admin/trips');
        }

        $this->tripRepository->delete($id);

        $this->flash->success(
            'Le trajet a été supprimé avec succès.'
        );

        return new RedirectResponse('/admin/trips');
    }
}