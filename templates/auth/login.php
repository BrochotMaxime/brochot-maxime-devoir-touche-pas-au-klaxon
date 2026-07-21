<?php

declare(strict_types=1);

/**
 * @var array<string, string> $errors
 * @var array<string, string> $old
 */
?>

<section>
    <h1>Connexion</h1>

    <?php if (isset($errors['credentials'])): ?>
        <p role="alert">
            <?= escape($errors['credentials']) ?>
        </p>
    <?php endif; ?>

    <form action="/login" method="post" novalidate>
        <div>
            <label for="email">
                Adresse email
            </label>

            <input
                type="email"
                id="email"
                name="email"
                value="<?= escape($old['email'] ?? '') ?>"
                autocomplete="email"
                required
            >

            <?php if (isset($errors['email'])): ?>
                <p role="alert">
                    <?= escape($errors['email']) ?>
                </p>
            <?php endif; ?>
        </div>

        <div>
            <label for="password">
                Mot de passe
            </label>

            <input
                type="password"
                id="password"
                name="password"
                autocomplete="current-password"
                required
            >

            <?php if (isset($errors['password'])): ?>
                <p role="alert">
                    <?= escape($errors['password']) ?>
                </p>
            <?php endif; ?>
        </div>

        <button type="submit">
            Se connecter
        </button>
    </form>
</section>