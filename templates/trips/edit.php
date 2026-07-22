<?php

declare(strict_types=1);

use App\Model\Agency;
use App\Model\Trip;

/**
 * @var array<string, mixed> $currentUser
 * @var list<Agency> $agencies
 * @var Trip $trip
 * @var array<string, string> $errors
 * @var array<string, string> $old
 */
?>

<section class="page-section">
    <h1 class="page-section__title">
        Modifier le trajet
    </h1>

    <?php
    $formAction = sprintf(
        '/trips/%d/update',
        $trip->getId(),
    );

    $submitLabel = 'Enregistrer les modifications';

    require __DIR__ . '/_form.php';
    ?>
</section>