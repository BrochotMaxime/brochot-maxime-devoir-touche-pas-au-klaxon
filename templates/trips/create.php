<?php

declare(strict_types=1);

use App\Model\Agency;

/**
 * @var array<string, mixed> $currentUser
 * @var list<Agency> $agencies
 * @var array<string, string> $errors
 * @var array<string, string> $old
 */
?>

<section class="page-section">
    <h1 class="page-section__title">
        Créer un trajet
    </h1>

    <form
        action="/trips"
        method="post"
        novalidate
    >
        <fieldset class="mb-4">
            <legend class="fs-5">
                Personne à contacter
            </legend>

            <div class="row g-3">
                <div class="col-md-6">
                    <label
                        class="form-label"
                        for="first_name"
                    >
                        Prénom
                    </label>

                    <input
                        class="form-control"
                        type="text"
                        id="first_name"
                        value="<?= escape(
                            $currentUser['firstName'] ?? ''
                        ) ?>"
                        readonly
                    >
                </div>

                <div class="col-md-6">
                    <label
                        class="form-label"
                        for="last_name"
                    >
                        Nom
                    </label>

                    <input
                        class="form-control"
                        type="text"
                        id="last_name"
                        value="<?= escape(
                            $currentUser['lastName'] ?? ''
                        ) ?>"
                        readonly
                    >
                </div>

                <div class="col-md-6">
                    <label
                        class="form-label"
                        for="email"
                    >
                        Adresse email
                    </label>

                    <input
                        class="form-control"
                        type="email"
                        id="email"
                        value="<?= escape(
                            $currentUser['email'] ?? ''
                        ) ?>"
                        readonly
                    >
                </div>

                <div class="col-md-6">
                    <label
                        class="form-label"
                        for="phone"
                    >
                        Téléphone
                    </label>

                    <input
                        class="form-control"
                        type="text"
                        id="phone"
                        value="<?= escape(
                            $currentUser['phone'] ?? ''
                        ) ?>"
                        readonly
                    >
                </div>
            </div>
        </fieldset>

        <fieldset class="mb-4">
            <legend class="fs-5">
                Itinéraire
            </legend>

            <div class="row g-3">
                <div class="col-md-6">
                    <label
                        class="form-label"
                        for="departure_agency_id"
                    >
                        Agence de départ
                    </label>

                    <select
                        class="form-select<?= isset(
                            $errors['departure_agency_id']
                        ) ? ' is-invalid' : '' ?>"
                        id="departure_agency_id"
                        name="departure_agency_id"
                        required
                    >
                        <option value="">
                            Sélectionner une agence
                        </option>

                        <?php foreach ($agencies as $agency): ?>
                            <option
                                value="<?= escape($agency->getId()) ?>"
                                <?= (string) $agency->getId()
                                    === (
                                        $old['departure_agency_id']
                                        ?? ''
                                    )
                                        ? 'selected'
                                        : '' ?>
                            >
                                <?= escape($agency->getName()) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <?php if (
                        isset($errors['departure_agency_id'])
                    ): ?>
                        <div class="invalid-feedback">
                            <?= escape(
                                $errors['departure_agency_id']
                            ) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label
                        class="form-label"
                        for="arrival_agency_id"
                    >
                        Agence d’arrivée
                    </label>

                    <select
                        class="form-select<?= isset(
                            $errors['arrival_agency_id']
                        ) ? ' is-invalid' : '' ?>"
                        id="arrival_agency_id"
                        name="arrival_agency_id"
                        required
                    >
                        <option value="">
                            Sélectionner une agence
                        </option>

                        <?php foreach ($agencies as $agency): ?>
                            <option
                                value="<?= escape($agency->getId()) ?>"
                                <?= (string) $agency->getId()
                                    === (
                                        $old['arrival_agency_id']
                                        ?? ''
                                    )
                                        ? 'selected'
                                        : '' ?>
                            >
                                <?= escape($agency->getName()) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <?php if (
                        isset($errors['arrival_agency_id'])
                    ): ?>
                        <div class="invalid-feedback">
                            <?= escape(
                                $errors['arrival_agency_id']
                            ) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label
                        class="form-label"
                        for="departure_datetime"
                    >
                        Date et heure de départ
                    </label>

                    <input
                        class="form-control<?= isset(
                            $errors['departure_datetime']
                        ) ? ' is-invalid' : '' ?>"
                        type="datetime-local"
                        id="departure_datetime"
                        name="departure_datetime"
                        value="<?= escape(
                            $old['departure_datetime'] ?? ''
                        ) ?>"
                        required
                    >

                    <?php if (
                        isset($errors['departure_datetime'])
                    ): ?>
                        <div class="invalid-feedback">
                            <?= escape(
                                $errors['departure_datetime']
                            ) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label
                        class="form-label"
                        for="arrival_datetime"
                    >
                        Date et heure d’arrivée
                    </label>

                    <input
                        class="form-control<?= isset(
                            $errors['arrival_datetime']
                        ) ? ' is-invalid' : '' ?>"
                        type="datetime-local"
                        id="arrival_datetime"
                        name="arrival_datetime"
                        value="<?= escape(
                            $old['arrival_datetime'] ?? ''
                        ) ?>"
                        required
                    >

                    <?php if (
                        isset($errors['arrival_datetime'])
                    ): ?>
                        <div class="invalid-feedback">
                            <?= escape(
                                $errors['arrival_datetime']
                            ) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </fieldset>

        <fieldset class="mb-4">
            <legend class="fs-5">
                Places
            </legend>

            <div class="row g-3">
                <div class="col-md-6">
                    <label
                        class="form-label"
                        for="total_seats"
                    >
                        Nombre total de places
                    </label>

                    <input
                        class="form-control<?= isset(
                            $errors['total_seats']
                        ) ? ' is-invalid' : '' ?>"
                        type="number"
                        id="total_seats"
                        name="total_seats"
                        value="<?= escape(
                            $old['total_seats'] ?? ''
                        ) ?>"
                        min="1"
                        step="1"
                        required
                    >

                    <?php if (isset($errors['total_seats'])): ?>
                        <div class="invalid-feedback">
                            <?= escape($errors['total_seats']) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label
                        class="form-label"
                        for="available_seats"
                    >
                        Nombre de places disponibles
                    </label>

                    <input
                        class="form-control<?= isset(
                            $errors['available_seats']
                        ) ? ' is-invalid' : '' ?>"
                        type="number"
                        id="available_seats"
                        name="available_seats"
                        value="<?= escape(
                            $old['available_seats'] ?? ''
                        ) ?>"
                        min="0"
                        step="1"
                        required
                    >

                    <?php if (
                        isset($errors['available_seats'])
                    ): ?>
                        <div class="invalid-feedback">
                            <?= escape(
                                $errors['available_seats']
                            ) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </fieldset>

        <div class="d-flex gap-3">
            <button
                class="btn btn-primary"
                type="submit"
            >
                Créer le trajet
            </button>

            <a
                class="btn btn-outline-secondary"
                href="/"
            >
                Annuler
            </a>
        </div>
    </form>
</section>