<?php

declare(strict_types=1);

use App\Controller\AuthController;
use App\Controller\ErrorController;
use App\Controller\HomeController;
use App\Controller\ProtectedPageController;
use App\Controller\TripController;
use App\Core\Database;
use App\Core\DatabaseConfig;
use App\Repository\UserRepository;
use App\Repository\TripRepository;
use App\Repository\AgencyRepository;
use App\Service\AuthService;
use App\Service\Session;
use App\Service\View;
use App\Service\AccessGuard;
use App\Service\Flash;
use App\Service\TripValidator;
use Buki\Router\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$router = new Router();

$session = new Session();
$flash = new Flash($session);

$view = new View(
    dirname(__DIR__) . '/templates',
    $flash,
);

$databaseConfig = DatabaseConfig::fromEnvironment();
$database = new Database($databaseConfig);
$connection = $database->getConnection();

$userRepository = new UserRepository($connection);
$tripRepository = new TripRepository($connection);
$agencyRepository = new AgencyRepository($connection);

$authService = new AuthService(
    $userRepository,
    $session,
);

$homeController = new HomeController(
    $view,
    $authService,
    $tripRepository,
);

$authController = new AuthController(
    $view,
    $authService,
    $flash,
);

$errorController = new ErrorController(
    $view,
    $authService,
);

$accessGuard = new AccessGuard(
    $authService,
    $errorController,
);

$protectedPageController = new ProtectedPageController(
    $view,
    $authService,
);

$tripValidator = new TripValidator(
    $agencyRepository,
);

$tripController = new TripController(
    $view,
    $authService,
    $agencyRepository,
    $tripRepository,
    $tripValidator,
    $flash,
);

$router->get(
    '/',
    fn (): Response => $homeController->index(),
);

$router->get(
    '/login',
    fn (): Response => $authController->showLogin(),
);

$router->get(
    '/admin',
    function () use (
        $accessGuard,
        $protectedPageController,
    ): Response {
        $accessResponse = $accessGuard->requireAdministrator();

        if ($accessResponse !== null) {
            return $accessResponse;
        }

        return $protectedPageController->adminDashboard();
    },
);

$router->get(
    '/trips/create',
    function () use (
        $accessGuard,
        $tripController,
    ): Response {
        $accessResponse = $accessGuard->requireAuthentication();

        if ($accessResponse !== null) {
            return $accessResponse;
        }

        return $tripController->create();
    },
);

$router->post(
    '/trips',
    function (
        Request $request,
        Response $_response,
    ) use (
        $accessGuard,
        $tripController,
    ): Response {
        $accessResponse = $accessGuard->requireAuthentication();

        if ($accessResponse !== null) {
            return $accessResponse;
        }

        return $tripController->store($request);
    },
);

$router->post(
    '/login',
    function (
        Request $request,
        Response $_response,
    ) use ($authController): Response {
        return $authController->authenticate($request);
    },
);

$router->post(
    '/logout',
    fn (): Response => $authController->logout(),
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