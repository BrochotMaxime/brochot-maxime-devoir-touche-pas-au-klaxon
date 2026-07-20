<?php

declare(strict_types=1);

use App\Core\Application;

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/config/bootstrap.php';

$application = new Application();

echo $application->getName();