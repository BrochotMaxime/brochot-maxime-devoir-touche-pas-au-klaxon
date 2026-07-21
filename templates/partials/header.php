<?php

declare(strict_types=1);

/**
 * @var array<string, mixed>|null $currentUser
 */

$isAuthenticated = isset($currentUser);
$isAdmin = $isAuthenticated
    && ($currentUser['role'] ?? null) === 'ROLE_ADMIN';
?>

<header>
    <nav aria-label="Navigation principale">
        <a href="<?= $isAdmin ? '/admin' : '/' ?>">
            Touche pas au klaxon
        </a>

        <?php if (!$isAuthenticated): ?>
            <a href="/login">
                Connexion
            </a>

        <?php elseif ($isAdmin): ?>
            <a href="/admin/users">
                Utilisateurs
            </a>

            <a href="/admin/agencies">
                Agences
            </a>

            <a href="/admin/trips">
                Trajets
            </a>

            <span>
                Bonjour
                <?= escape($currentUser['firstName'] ?? '') ?>
                <?= escape($currentUser['lastName'] ?? '') ?>
            </span>

            <form action="/logout" method="post">
                <button type="submit">
                    Déconnexion
                </button>
            </form>

        <?php else: ?>
            <a href="/trips/create">
                Créer un trajet
            </a>

            <span>
                Bonjour
                <?= escape($currentUser['firstName'] ?? '') ?>
                <?= escape($currentUser['lastName'] ?? '') ?>
            </span>

            <form action="/logout" method="post">
                <button type="submit">
                    Déconnexion
                </button>
            </form>
        <?php endif; ?>
    </nav>
</header>