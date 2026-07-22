<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\AgencyRepository;
use App\Repository\TripRepository;
use App\Service\AuthService;
use App\Service\Flash;
use App\Service\TripValidator;
use App\Service\View;
use App\Service\Csrf;
use DateTimeImmutable;
use LogicException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Handles trip creation and management.
 */
final class TripController
{
    public function __construct(
        private readonly View $view,
        private readonly AuthService $authService,
        private readonly AgencyRepository $agencyRepository,
        private readonly TripRepository $tripRepository,
        private readonly TripValidator $tripValidator,
        private readonly Flash $flash,
        private readonly ErrorController $errorController,
        private readonly Csrf $csrf,
    ) {
    }

    public function create(): Response
    {
        return new Response(
            $this->view->render('trips/create', [
                'pageTitle' => 'Créer un trajet',
                'currentUser' => $this->authService->getUser(),
                'agencies' => $this->agencyRepository->findAll(),
                'errors' => [],
                'old' => $this->getDefaultFormData(),
                'csrfToken' => $this->csrf->getToken(
                    'trip_create'
                ),
            ])
        );
    }

    public function edit(int $id): Response
    {
        $currentUser = $this->authService->getUser();

        if ($currentUser === null) {
            throw new LogicException(
                'An authenticated user is required to edit a trip.'
            );
        }

        $trip = $this->tripRepository->findById($id);

        if ($trip === null) {
            $this->flash->error(
                'Le trajet demandé est introuvable.'
            );

            return new RedirectResponse('/');
        }

        if (!$trip->isOwnedBy((int) $currentUser['id'])) {
            return $this->errorController->forbidden();
        }

        return new Response(
            $this->view->render('trips/edit', [
                'pageTitle' => 'Modifier un trajet',
                'currentUser' => $currentUser,
                'agencies' => $this->agencyRepository->findAll(),
                'trip' => $trip,
                'errors' => [],
                'old' => [
                    'departure_agency_id' =>
                        (string) $trip->getDepartureAgencyId(),
                    'arrival_agency_id' =>
                        (string) $trip->getArrivalAgencyId(),
                    'departure_datetime' =>
                        $trip->getDepartureDatetime()->format('Y-m-d\TH:i'),
                    'arrival_datetime' =>
                        $trip->getArrivalDatetime()->format('Y-m-d\TH:i'),
                    'total_seats' =>
                        (string) $trip->getTotalSeats(),
                    'available_seats' =>
                        (string) $trip->getAvailableSeats(),
                ],
                'csrfToken' => $this->csrf->getToken(
                    sprintf('trip_update_%d', $id)
                ),
            ])
        );
    }

    public function update(
        Request $request,
        int $id,
    ): Response {
        $currentUser = $this->authService->getUser();

        if ($currentUser === null) {
            throw new LogicException(
                'An authenticated user is required to update a trip.'
            );
        }

        $trip = $this->tripRepository->findById($id);

        if ($trip === null) {
            $this->flash->error(
                'Le trajet demandé est introuvable.'
            );

            return new RedirectResponse('/');
        }

        if (!$trip->isOwnedBy((int) $currentUser['id'])) {
            return $this->errorController->forbidden();
        }

        $data = [
            'departure_agency_id' => trim(
                (string) $request->request->get(
                    'departure_agency_id',
                    '',
                )
            ),
            'arrival_agency_id' => trim(
                (string) $request->request->get(
                    'arrival_agency_id',
                    '',
                )
            ),
            'departure_datetime' => trim(
                (string) $request->request->get(
                    'departure_datetime',
                    '',
                )
            ),
            'arrival_datetime' => trim(
                (string) $request->request->get(
                    'arrival_datetime',
                    '',
                )
            ),
            'total_seats' => trim(
                (string) $request->request->get(
                    'total_seats',
                    '',
                )
            ),
            'available_seats' => trim(
                (string) $request->request->get(
                    'available_seats',
                    '',
                )
            ),
        ];

        $errors = $this->tripValidator->validate($data);

        if ($errors !== []) {
            return new Response(
                $this->view->render('trips/edit', [
                    'pageTitle' => 'Modifier un trajet',
                    'currentUser' => $currentUser,
                    'agencies' => $this->agencyRepository->findAll(),
                    'trip' => $trip,
                    'errors' => $errors,
                    'old' => $data,
                    'csrfToken' => $this->csrf->getToken(
                        sprintf('trip_update_%d', $id)
                    ),
                ]),
                Response::HTTP_UNPROCESSABLE_ENTITY,
            );
        }

        $departureDatetime = new DateTimeImmutable(
            $data['departure_datetime']
        );

        $arrivalDatetime = new DateTimeImmutable(
            $data['arrival_datetime']
        );

        $this->tripRepository->update($id, [
            'departure_datetime' => $departureDatetime->format(
                'Y-m-d H:i:s'
            ),
            'arrival_datetime' => $arrivalDatetime->format(
                'Y-m-d H:i:s'
            ),
            'total_seats' => (int) $data['total_seats'],
            'available_seats' => (int) $data['available_seats'],
            'departure_agency_id' =>
                (int) $data['departure_agency_id'],
            'arrival_agency_id' =>
                (int) $data['arrival_agency_id'],
        ]);

        $this->flash->success(
            'Le trajet a été modifié avec succès.'
        );

        return new RedirectResponse('/');
    }

    public function delete(int $id): Response
    {
        $currentUser = $this->authService->getUser();

        if ($currentUser === null) {
            throw new LogicException(
                'An authenticated user is required to delete a trip.'
            );
        }

        $trip = $this->tripRepository->findById($id);

        if ($trip === null) {
            $this->flash->error(
                'Le trajet demandé est introuvable.'
            );

            return new RedirectResponse('/');
        }

        if (!$trip->isOwnedBy((int) $currentUser['id'])) {
            return $this->errorController->forbidden();
        }

        $this->tripRepository->delete($id);

        $this->flash->success(
            'Le trajet a été supprimé avec succès.'
        );

        return new RedirectResponse('/');
    }

    public function store(Request $request): Response
    {
        $currentUser = $this->authService->getUser();

        if ($currentUser === null) {
            throw new LogicException(
                'An authenticated user is required to create a trip.'
            );
        }

        $data = [
            'departure_agency_id' => trim(
                (string) $request->request->get(
                    'departure_agency_id',
                    '',
                )
            ),
            'arrival_agency_id' => trim(
                (string) $request->request->get(
                    'arrival_agency_id',
                    '',
                )
            ),
            'departure_datetime' => trim(
                (string) $request->request->get(
                    'departure_datetime',
                    '',
                )
            ),
            'arrival_datetime' => trim(
                (string) $request->request->get(
                    'arrival_datetime',
                    '',
                )
            ),
            'total_seats' => trim(
                (string) $request->request->get(
                    'total_seats',
                    '',
                )
            ),
            'available_seats' => trim(
                (string) $request->request->get(
                    'available_seats',
                    '',
                )
            ),
        ];

        $errors = $this->tripValidator->validate($data);

        if ($errors !== []) {
            return new Response(
                $this->view->render('trips/create', [
                    'pageTitle' => 'Créer un trajet',
                    'currentUser' => $currentUser,
                    'agencies' => $this->agencyRepository->findAll(),
                    'errors' => $errors,
                    'old' => $data,
                    'csrfToken' => $this->csrf->getToken(
                        'trip_create'
                    ),
                ]),
                Response::HTTP_UNPROCESSABLE_ENTITY,
            );
        }

        $departureDatetime = new DateTimeImmutable(
            $data['departure_datetime']
        );

        $arrivalDatetime = new DateTimeImmutable(
            $data['arrival_datetime']
        );

        $this->tripRepository->create([
            'departure_datetime' => $departureDatetime->format(
                'Y-m-d H:i:s'
            ),
            'arrival_datetime' => $arrivalDatetime->format(
                'Y-m-d H:i:s'
            ),
            'total_seats' => (int) $data['total_seats'],
            'available_seats' => (int) $data['available_seats'],
            'author_id' => (int) $currentUser['id'],
            'departure_agency_id' =>
                (int) $data['departure_agency_id'],
            'arrival_agency_id' =>
                (int) $data['arrival_agency_id'],
        ]);

        $this->flash->success(
            'Le trajet a été créé avec succès.'
        );

        return new RedirectResponse('/');
    }

    /**
     * @return array<string, string>
     */
    private function getDefaultFormData(): array
    {
        $departureDatetime = new DateTimeImmutable('+1 day');
        $arrivalDatetime = $departureDatetime->modify('+1 hour');

        return [
            'departure_agency_id' => '',
            'arrival_agency_id' => '',
            'departure_datetime' => $departureDatetime->format(
                'Y-m-d\TH:i'
            ),
            'arrival_datetime' => $arrivalDatetime->format(
                'Y-m-d\TH:i'
            ),
            'total_seats' => '',
            'available_seats' => '',
        ];
    }
}