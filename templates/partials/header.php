<?php

declare(strict_types=1);

/**
 * @var array<string, mixed>|null $currentUser
 */

$isAuthenticated = isset($currentUser);
$isAdmin = $isAuthenticated
    && ($currentUser['role'] ?? null) === 'ROLE_ADMIN';
?>

<header class="application-header">
    <nav
        class="application-header__nav"
        aria-label="Navigation principale"
    >
        <a
            class="application-header__brand"
            href="<?= $isAdmin ? '/admin' : '/' ?>"
        >
            Touche pas au klaxon
        </a>

        <?php if (!$isAuthenticated): ?>
            <a
                class="btn btn-primary"
                href="/login"
            >
                Connexion
            </a>

        <?php elseif ($isAdmin): ?>
            <a
                class="application-header__link"
                href="/admin/users"
            >
                Utilisateurs
            </a>

            <a
                class="application-header__link"
                href="/admin/agencies"
            >
                Agences
            </a>

            <a
                class="application-header__link"
                href="/admin/trips"
            >
                Trajets
            </a>

            <p class="application-header__user">
                Bonjour
                <?= escape($currentUser['firstName'] ?? '') ?>
                <?= escape($currentUser['lastName'] ?? '') ?>
            </p>

            <form
                class="application-header__logout"
                action="/logout"
                method="post"
            >
                <?= csrfField($csrf->getToken('logout')) ?>

                <button
                    class="btn btn-light"
                    type="submit"
                >
                    Déconnexion
                </button>
            </form>

        <?php else: ?>
            <a
                class="btn btn-primary"
                href="/trips/create"
            >
                Créer un trajet
            </a>

            <p class="application-header__user">
                Bonjour
                <?= escape($currentUser['firstName'] ?? '') ?>
                <?= escape($currentUser['lastName'] ?? '') ?>
            </p>

            <form
                class="application-header__logout"
                action="/logout"
                method="post"
            >
                <?= csrfField($csrf->getToken('logout')) ?>

                <button
                    class="btn btn-light"
                    type="submit"
                >
                    Déconnexion
                </button>
            </form>
        <?php endif; ?>
    </nav>
</header>