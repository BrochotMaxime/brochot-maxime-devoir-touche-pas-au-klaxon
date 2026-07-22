<?php

declare(strict_types=1);

use App\Model\User;

/**
 * @var list<User> $users
 */
?>

<section class="page-section">
    <div class="admin-list-heading">
        <div>
            <h1 class="page-section__title">
                Utilisateurs
            </h1>

            <p class="mb-0">
                Consultez les employés autorisés à utiliser l'application.
            </p>
        </div>

        <a
            class="btn btn-outline-secondary"
            href="/admin"
        >
            Retour au tableau de bord
        </a>
    </div>

    <?php if ($users === []): ?>
        <div
            class="alert alert-info"
            role="alert"
        >
            Aucun utilisateur n'est actuellement enregistré.
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

                        <th scope="col">
                            Prénom
                        </th>

                        <th scope="col">
                            Téléphone
                        </th>

                        <th scope="col">
                            Adresse email
                        </th>

                        <th scope="col">
                            Rôle
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td>
                                <?= escape($user->getLastName()) ?>
                            </td>

                            <td>
                                <?= escape($user->getFirstName()) ?>
                            </td>

                            <td>
                                <a href="tel:<?= escape(
                                    $user->getPhone()
                                ) ?>">
                                    <?= escape($user->getPhone()) ?>
                                </a>
                            </td>

                            <td>
                                <a href="mailto:<?= escape(
                                    $user->getEmail()
                                ) ?>">
                                    <?= escape($user->getEmail()) ?>
                                </a>
                            </td>

                            <td>
                                <?php if ($user->isAdmin()): ?>
                                    <span class="badge text-bg-primary">
                                        Administrateur
                                    </span>
                                <?php else: ?>
                                    <span class="badge text-bg-secondary">
                                        Utilisateur
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>