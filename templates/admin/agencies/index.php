<?php

declare(strict_types=1);

use App\Model\Agency;
use App\Service\Csrf;

/**
 * @var list<Agency> $agencies
 * @var Csrf $csrf
 */
?>

<section class="page-section">
    <div class="admin-list-heading">
        <div>
            <h1 class="page-section__title">
                Agences
            </h1>

            <p class="mb-0">
                Gérez les agences disponibles pour les trajets.
            </p>
        </div>

        <div class="d-flex gap-2">
            <a
                class="btn btn-primary"
                href="/admin/agencies/create"
            >
                Ajouter une agence
            </a>

            <a
                class="btn btn-outline-secondary"
                href="/admin"
            >
                Retour au tableau de bord
            </a>
        </div>
    </div>

    <?php if ($agencies === []): ?>
        <div
            class="alert alert-info"
            role="alert"
        >
            Aucune agence n'est actuellement enregistrée.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table
                class="
                    admin-table
                    table
                    table-striped
                    table-hover
                    align-middle
                "
            >
                <thead>
                    <tr>
                        <th scope="col">
                            Nom
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
                    <?php foreach ($agencies as $agency): ?>
                        <tr>
                            <td>
                                <?= escape($agency->getName()) ?>
                            </td>

                            <td class="admin-table__actions">
                                <div
                                    class="
                                        d-flex
                                        flex-wrap
                                        justify-content-center
                                        gap-2
                                    "
                                >
                                    <a
                                        class="btn btn-sm btn-secondary"
                                        href="/admin/agencies/<?= escape(
                                            $agency->getId()
                                        ) ?>/edit"
                                    >
                                        Modifier
                                    </a>

                                    <form
                                        class="m-0"
                                        action="/admin/agencies/<?= escape(
                                            $agency->getId()
                                        ) ?>/delete"
                                        method="post"
                                        data-agency-delete-form
                                    >
                                        <?= csrfField(
                                            $csrf->getToken(
                                                sprintf(
                                                    'agency_delete_%d',
                                                    $agency->getId(),
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
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>