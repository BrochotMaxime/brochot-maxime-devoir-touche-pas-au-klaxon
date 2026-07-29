<?php

declare(strict_types=1);

use App\Core\Application;

/** @var Application $application */
?>

<footer class="application-footer">
    <p class="application-footer__text">
        <?= escape($application->getName()) ?>
        &copy; <?= escape(date('Y')) ?>
    </p>
</footer>