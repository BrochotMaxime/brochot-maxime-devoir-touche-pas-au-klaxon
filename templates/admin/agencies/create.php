<?php

declare(strict_types=1);

/**
 * @var array<string, string> $errors
 * @var array<string, string> $old
 */
?>

<section class="page-section">
    <h1 class="page-section__title">
        Ajouter une agence
    </h1>

    <?php
    $formAction = '/admin/agencies';
    $submitLabel = 'Créer l’agence';

    require __DIR__ . '/_form.php';
    ?>
</section>