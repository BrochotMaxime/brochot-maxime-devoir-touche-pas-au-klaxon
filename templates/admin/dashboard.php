<?php

declare(strict_types=1);

/**
 * @var array{
 *     users: int,
 *     agencies: int,
 *     trips: int,
 *     upcomingTrips: int
 * } $statistics
 */
?>

<section class="page-section">
    <div class="admin-dashboard__heading">
        <div>
            <h1 class="page-section__title">
                Tableau de bord administrateur
            </h1>

            <p>Gérez les utilisateurs, les agences et les trajets de l'application.</p>
        </div>
    </div>

    <div class="admin-dashboard__statistics">
        <article class="admin-stat-card">
            <p class="admin-stat-card__value">
                <?= escape($statistics['users']) ?>
            </p>

            <h2 class="admin-stat-card__title">
                Utilisateurs
            </h2>
        </article>

        <article class="admin-stat-card">
            <p class="admin-stat-card__value">
                <?= escape($statistics['agencies']) ?>
            </p>

            <h2 class="admin-stat-card__title">
                Agences
            </h2>
        </article>

        <article class="admin-stat-card">
            <p class="admin-stat-card__value">
                <?= escape($statistics['trips']) ?>
            </p>

            <h2 class="admin-stat-card__title">
                Trajets enregistrés
            </h2>
        </article>

        <article class="admin-stat-card">
            <p class="admin-stat-card__value">
                <?= escape($statistics['upcomingTrips']) ?>
            </p>

            <h2 class="admin-stat-card__title">
                Trajets à venir
            </h2>
        </article>
    </div>

    <div class="admin-dashboard__sections">
        <article class="admin-section-card">
            <h2>
                Utilisateurs
            </h2>

            <p>Consultez la liste des employés autorisés à utiliser l'application.</p>

            <a
                class="btn btn-primary"
                href="/admin/users"
            >
                Voir les utilisateurs
            </a>
        </article>

        <article class="admin-section-card">
            <h2>
                Agences
            </h2>

            <p>Ajoutez, modifiez ou supprimez les agences de l'entreprise.</p>

            <a
                class="btn btn-primary"
                href="/admin/agencies"
            >
                Gérer les agences
            </a>
        </article>

        <article class="admin-section-card">
            <h2>
                Trajets
            </h2>

            <p>Consultez tous les trajets et supprimez ceux qui doivent être retirés.</p>

            <a
                class="btn btn-primary"
                href="/admin/trips"
            >
                Gérer les trajets
            </a>
        </article>
    </div>
</section>