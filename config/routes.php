<?php

declare(strict_types=1);

use App\Controller\AuthController;
use App\Controller\ErrorController;
use App\Controller\HomeController;
use App\Service\View;
use Buki\Router\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$router = new Router();

$view = new View(
    dirname(__DIR__) . '/templates'
);

$homeController = new HomeController($view);
$authController = new AuthController($view);
$errorController = new ErrorController($view);

$router->get(
    '/',
    fn (): Response => $homeController->index(),
);

$router->get(
    '/login',
    fn (): Response => $authController->login(),
);

$router->notFound(
    function (
        Request $_request,
        Response $_response,
    ) use ($errorController): Response {
        return $errorController->notFound();
    },
);

return $router;