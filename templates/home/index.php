<?php

declare(strict_types=1);

use App\Model\TripListItem;

/**
 * @var list<TripListItem> $trips
 * @var array<string, mixed>|null $currentUser
 */
?>

<section class="page-section">
    <h1 class="page-section__title">
        Trajets disponibles
    </h1>

    <?php if ($trips === []): ?>
        <p class="mb-0">
            Aucun trajet avec des places disponibles n'est actuellement planifié.
        </p>
    <?php else: ?>

    <?php if ($currentUser === null): ?>
        <p>Connectez-vous pour consulter les coordonnées de la personne proposant un trajet.</p>
    <?php endif; ?>

        <div class="table-responsive">
            <table class="trip-table table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th scope="col">
                            Départ
                        </th>

                        <th scope="col">
                            Date de départ
                        </th>

                        <th scope="col">
                            Heure de départ
                        </th>

                        <th scope="col">
                            Destination
                        </th>

                        <th scope="col">
                            Date d'arrivée
                        </th>

                        <th scope="col">
                            Heure d'arrivée
                        </th>

                        <th scope="col">
                            Places disponibles
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($trips as $trip): ?>
                        <tr>
                            <td>
                                <?= escape(
                                    $trip->getDepartureAgency()
                                ) ?>
                            </td>

                            <td>
                                <?= escape(
                                    $trip
                                        ->getDepartureDatetime()
                                        ->format('d/m/Y')
                                ) ?>
                            </td>

                            <td>
                                <?= escape(
                                    $trip
                                        ->getDepartureDatetime()
                                        ->format('H:i')
                                ) ?>
                            </td>

                            <td>
                                <?= escape(
                                    $trip->getArrivalAgency()
                                ) ?>
                            </td>

                            <td>
                                <?= escape(
                                    $trip
                                        ->getArrivalDatetime()
                                        ->format('d/m/Y')
                                ) ?>
                            </td>

                            <td>
                                <?= escape(
                                    $trip
                                        ->getArrivalDatetime()
                                        ->format('H:i')
                                ) ?>
                            </td>

                            <td>
                                <?= escape(
                                    $trip->getAvailableSeats()
                                ) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>