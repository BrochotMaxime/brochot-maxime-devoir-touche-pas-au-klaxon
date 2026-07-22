<?php

declare(strict_types=1);

/**
 * @var array<string, string> $errors
 * @var array<string, string> $old
 * @var string $formAction
 * @var string $submitLabel
 */
?>

<form
    action="<?= escape($formAction) ?>"
    method="post"
    novalidate
>
    <div class="mb-4">
        <label
            class="form-label"
            for="name"
        >
            Nom de l'agence
        </label>

        <input
            class="form-control<?= isset($errors['name'])
                ? ' is-invalid'
                : '' ?>"
            type="text"
            id="name"
            name="name"
            value="<?= escape($old['name'] ?? '') ?>"
            maxlength="100"
            autocomplete="organization"
            required
        >

        <?php if (isset($errors['name'])): ?>
            <div class="invalid-feedback">
                <?= escape($errors['name']) ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="d-flex gap-3">
        <button
            class="btn btn-primary"
            type="submit"
        >
            <?= escape($submitLabel) ?>
        </button>

        <a
            class="btn btn-outline-secondary"
            href="/admin/agencies"
        >
            Annuler
        </a>
    </div>
</form>