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

                        <?php if ($currentUser !== null): ?>
                            <th
                                class="trip-table__actions"
                                scope="col"
                            >
                                Actions
                            </th>
                        <?php endif; ?>
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

                            <?php if ($currentUser !== null): ?>
                                <td class="trip-table__actions">
                                    <div class="d-flex flex-wrap justify-content-center gap-2">
                                        <button
                                            class="btn btn-sm btn-primary"
                                            type="button"
                                            data-bs-toggle="modal"
                                            data-bs-target="#trip-details-modal"
                                        >
                                            Détails
                                        </button>

                                        <?php if (
                                            isset($currentUser['id'])
                                            && $trip->isOwnedBy((int) $currentUser['id'])
                                        ): ?>
                                            <a
                                                class="btn btn-sm btn-secondary"
                                                href="/trips/<?= escape($trip->getId()) ?>/edit"
                                            >
                                                Modifier
                                            </a>

                                            <form
                                                class="m-0"
                                                action="/trips/<?= escape($trip->getId()) ?>/delete"
                                                method="post"
                                                data-trip-delete-form
                                            >
                                                <button
                                                    class="btn btn-sm btn-danger"
                                                    type="submit"
                                                >
                                                    Supprimer
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

<?php if ($currentUser !== null && $trips !== []): ?>
    <div
        class="modal fade"
        id="trip-details-modal"
        tabindex="-1"
        aria-labelledby="trip-details-modal-title"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2
                        class="modal-title fs-5"
                        id="trip-details-modal-title"
                    >
                        Informations complémentaires
                    </h2>

                    <button
                        class="btn-close"
                        type="button"
                        data-bs-dismiss="modal"
                        aria-label="Fermer"
                    ></button>
                </div>

                <div class="modal-body">
                    <dl class="trip-details">
                        <div class="trip-details__row">
                            <dt>Personne à contacter</dt>
                            <dd data-trip-detail="author"></dd>
                        </div>

                        <div class="trip-details__row">
                            <dt>Téléphone</dt>
                            <dd data-trip-detail="phone"></dd>
                        </div>

                        <div class="trip-details__row">
                            <dt>Adresse email</dt>
                            <dd data-trip-detail="email"></dd>
                        </div>

                        <div class="trip-details__row">
                            <dt>Nombre total de places</dt>
                            <dd data-trip-detail="total-seats"></dd>
                        </div>
                    </dl>
                </div>

                <div class="modal-footer">
                    <button
                        class="btn btn-secondary"
                        type="button"
                        data-bs-dismiss="modal"
                    >
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>