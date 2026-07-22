<?php

declare(strict_types=1);

use App\Model\AdminTripListItem;
use App\Service\Csrf;

/**
 * @var list<AdminTripListItem> $trips
 * @var Csrf $csrf
 */
?>

<section class="page-section">
    <div class="admin-list-heading">
        <div>
            <h1 class="page-section__title">
                Trajets
            </h1>

            <p class="mb-0">
                Consultez et supprimez les trajets enregistrés dans l'application.
            </p>
        </div>

        <a
            class="btn btn-outline-secondary"
            href="/admin"
        >
            Retour au tableau de bord
        </a>
    </div>

    <?php if ($trips === []): ?>
        <div
            class="alert alert-info"
            role="alert"
        >
            Aucun trajet n'est actuellement enregistré.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table
                class="
                    admin-table
                    admin-trip-table
                    table
                    table-striped
                    table-hover
                    align-middle
                "
            >
                <thead>
                    <tr>
                        <th scope="col">
                            Départ
                        </th>

                        <th scope="col">
                            Destination
                        </th>

                        <th scope="col">
                            Date et heure
                        </th>

                        <th scope="col">
                            Auteur
                        </th>

                        <th scope="col">
                            Places
                        </th>

                        <th scope="col">
                            Statut
                        </th>

                        <th
                            class="admin-table__actions"
                            scope="col"
                        >
                            Actions
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
                                    $trip->getArrivalAgency()
                                ) ?>
                            </td>

                            <td>
                                <div>
                                    <strong>Départ :</strong>
                                    <?= escape(
                                        $trip
                                            ->getDepartureDatetime()
                                            ->format('d/m/Y H:i')
                                    ) ?>
                                </div>

                                <div>
                                    <strong>Arrivée :</strong>
                                    <?= escape(
                                        $trip
                                            ->getArrivalDatetime()
                                            ->format('d/m/Y H:i')
                                    ) ?>
                                </div>
                            </td>

                            <td>
                                <div>
                                    <?= escape(
                                        $trip->getAuthorFullName()
                                    ) ?>
                                </div>

                                <a href="mailto:<?= escape(
                                    $trip->getAuthorEmail()
                                ) ?>">
                                    <?= escape(
                                        $trip->getAuthorEmail()
                                    ) ?>
                                </a>
                            </td>

                            <td>
                                <?= escape(
                                    $trip->getAvailableSeats()
                                ) ?>
                                /
                                <?= escape(
                                    $trip->getTotalSeats()
                                ) ?>
                            </td>

                            <td>
                                <?php if ($trip->isPast()): ?>
                                    <span class="badge text-bg-secondary">
                                        Passé
                                    </span>

                                <?php elseif ($trip->isFull()): ?>
                                    <span class="badge text-bg-danger">
                                        Complet
                                    </span>

                                <?php else: ?>
                                    <span class="badge text-bg-success">
                                        Disponible
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td class="admin-table__actions">
                                <form
                                    class="m-0"
                                    action="/admin/trips/<?= escape(
                                        $trip->getId()
                                    ) ?>/delete"
                                    method="post"
                                    data-admin-trip-delete-form
                                >
                                    <?= csrfField(
                                        $csrf->getToken(
                                            sprintf(
                                                'admin_trip_delete_%d',
                                                $trip->getId(),
                                            )
                                        )
                                    ) ?>

                                    <button
                                        class="btn btn-sm btn-danger"
                                        type="submit"
                                    >
                                        Supprimer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>