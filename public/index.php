<?php

declare(strict_types=1);

use Buki\Router\Router;

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/config/bootstrap.php';

/** @var Router $router */
$router = require dirname(__DIR__) . '/config/routes.php';

$router->run();