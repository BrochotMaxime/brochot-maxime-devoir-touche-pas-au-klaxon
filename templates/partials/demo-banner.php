<?php

declare(strict_types=1);

use App\Core\Application;

/** @var Application $application */

if (!$application->isDemo()) {
    return;
}
?>

<aside
class="demo-banner"
aria-label="Informations sur l’environnement de démonstration"
>
<p class="demo-banner__text">
    <strong>Environnement de démonstration</strong> 
    - Les données sont fictives et peuvent être réinitialisées périodiquement.
</p>
</aside>