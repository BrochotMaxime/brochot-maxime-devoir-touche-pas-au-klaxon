<?php

declare(strict_types=1);

/**
 * @var array<string, string> $errors
 * @var array<string, string> $old
 */
?>

<section class="page-section">
    <h1 class="page-section__title">
        Connexion
    </h1>

    <?php if (isset($errors['credentials'])): ?>
        <div
            class="alert alert-danger"
            role="alert"
        >
            <?= escape($errors['credentials']) ?>
        </div>
    <?php endif; ?>

    <form
        action="/login"
        method="post"
        novalidate
    >
        <div class="mb-3">
            <label
                class="form-label"
                for="email"
            >
                Adresse email
            </label>

            <input
                class="form-control<?= isset($errors['email'])
                    ? ' is-invalid'
                    : '' ?>"
                type="email"
                id="email"
                name="email"
                value="<?= escape($old['email'] ?? '') ?>"
                autocomplete="email"
                required
            >

            <?php if (isset($errors['email'])): ?>
                <div class="invalid-feedback">
                    <?= escape($errors['email']) ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="mb-4">
            <label
                class="form-label"
                for="password"
            >
                Mot de passe
            </label>

            <input
                class="form-control<?= isset($errors['password'])
                    ? ' is-invalid'
                    : '' ?>"
                type="password"
                id="password"
                name="password"
                autocomplete="current-password"
                required
            >

            <?php if (isset($errors['password'])): ?>
                <div class="invalid-feedback">
                    <?= escape($errors['password']) ?>
                </div>
            <?php endif; ?>
        </div>

        <button
            class="btn btn-primary"
            type="submit"
        >
            Se connecter
        </button>
    </form>
</section>