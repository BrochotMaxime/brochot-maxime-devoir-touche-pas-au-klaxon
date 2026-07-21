<?php

declare(strict_types=1);

use App\Controller\AuthController;
// use App\Controller\ErrorController;
use App\Controller\HomeController;
use Buki\Router\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$router = new Router();

$router->get('/', function (): Response {
    return (new HomeController())->index();
});

$router->get('/login', function (): Response {
    return (new AuthController())->login();
});

$router->notFound(function (
    Request $request,
    Response $response,
): Response {
    $response->setStatusCode(Response::HTTP_NOT_FOUND);
    $response->setContent(
        '<h1>404</h1><p>La page demandée est introuvable.</p>'
    );

    return $response;
});

return $router;