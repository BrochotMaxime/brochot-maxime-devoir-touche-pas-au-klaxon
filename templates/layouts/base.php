<?php

declare(strict_types=1);

/**
 * @var string $content
 * @var string|null $pageTitle
 * @var array<string, mixed>|null $currentUser
 */

$applicationName = 'Touche pas au klaxon';
$documentTitle = isset($pageTitle)
    ? sprintf('%s | %s', $pageTitle, $applicationName)
    : $applicationName;
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">

        <meta
            name="viewport"
            content="width=device-width, initial-scale=1"
        >

        <title><?= escape($documentTitle) ?></title>

        <link
            rel="stylesheet"
            href="/assets/css/main.css"
        >
    </head>

    <body>
        <?php require dirname(__DIR__) . '/partials/header.php'; ?>

        <?php require dirname(__DIR__) . '/partials/flash.php'; ?>

        <main class="application-main">
            <?= $content ?>
        </main>

        <?php require dirname(__DIR__) . '/partials/footer.php'; ?>

        <script src="/assets/js/bootstrap.bundle.min.js"></script>
        <script src="/assets/js/trip-details-modal.js"></script>
        <script src="/assets/js/trip-delete-confirmation.js"></script>
        <script src="/assets/js/agency-delete-confirmation.js"></script>
    </body>
</html>