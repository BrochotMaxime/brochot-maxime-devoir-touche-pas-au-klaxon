<?php

declare(strict_types=1);

use App\Model\Agency;

/**
 * @var Agency $agency
 * @var array<string, string> $errors
 * @var array<string, string> $old
 */
?>

<section class="page-section">
    <h1 class="page-section__title">
        Modifier l'agence
    </h1>

    <?php
    $formAction = sprintf(
        '/admin/agencies/%d/update',
        $agency->getId(),
    );

    $submitLabel = 'Enregistrer les modifications';

    require __DIR__ . '/_form.php';
    ?>
</section>