<?php

declare(strict_types=1);

use App\Controller\AuthController;
use App\Controller\ErrorController;
use App\Controller\HomeController;
use App\Controller\TripController;
use App\Controller\AdminController;
use App\Core\Database;
use App\Core\DatabaseConfig;
use App\Exception\InvalidCsrfTokenException;
use App\Repository\UserRepository;
use App\Repository\TripRepository;
use App\Repository\AgencyRepository;
use App\Service\AuthService;
use App\Service\Session;
use App\Service\View;
use App\Service\AccessGuard;
use App\Service\Flash;
use App\Service\TripValidator;
use App\Service\AgencyValidator;
use App\Service\Csrf;
use App\Service\CsrfGuard;
use Buki\Router\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$router = new Router();

$session = new Session();

$flash = new Flash($session);

$csrf = new Csrf($session);
$csrfGuard = new CsrfGuard($csrf);

$view = new View(
    dirname(__DIR__) . '/templates',
    $flash,
    $csrf,
);

$databaseConfig = DatabaseConfig::fromEnvironment();
$database = new Database($databaseConfig);
$connection = $database->getConnection();

$userRepository = new UserRepository($connection);
$tripRepository = new TripRepository($connection);
$agencyRepository = new AgencyRepository($connection);

$agencyValidator = new AgencyValidator(
    $agencyRepository,
);

$authService = new AuthService(
    $userRepository,
    $session,
);

$adminController = new AdminController(
    $view,
    $authService,
    $userRepository,
    $agencyRepository,
    $tripRepository,
    $agencyValidator,
    $flash,
    $csrf,
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
    $csrf,
);

$errorController = new ErrorController(
    $view,
    $authService,
);

$accessGuard = new AccessGuard(
    $authService,
    $errorController,
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
    $errorController,
    $csrf,
);

/**
 * Executes an action after validating its CSRF token.
 *
 * @param callable(): Response $action
 */
$executeWithCsrf = static function (
    Request $request,
    string $formName,
    callable $action,
) use (
    $csrfGuard,
    $errorController,
): Response {
    try {
        $csrfGuard->validate(
            $request,
            $formName,
        );
    } catch (InvalidCsrfTokenException) {
        return $errorController->invalidCsrfToken();
    }

    return $action();
};

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
        $adminController,
    ): Response {
        $accessResponse = $accessGuard
            ->requireAdministrator();

        if ($accessResponse !== null) {
            return $accessResponse;
        }

        return $adminController->dashboard();
    },
);

$router->get(
    '/admin/users',
    function () use (
        $accessGuard,
        $adminController,
    ): Response {
        $accessResponse = $accessGuard
            ->requireAdministrator();

        if ($accessResponse !== null) {
            return $accessResponse;
        }

        return $adminController->users();
    },
);

$router->get(
    '/admin/agencies',
    function (
        Request $_request,
        Response $_response,
    ) use (
        $accessGuard,
        $adminController,
    ): Response {
        $accessResponse = $accessGuard
            ->requireAdministrator();

        if ($accessResponse !== null) {
            return $accessResponse;
        }

        return $adminController->agencies();
    },
);

$router->get(
    '/admin/agencies/create',
    function () use (
        $accessGuard,
        $adminController,
    ): Response {
        $accessResponse = $accessGuard
            ->requireAdministrator();

        if ($accessResponse !== null) {
            return $accessResponse;
        }

        return $adminController->createAgency();
    },
);

$router->post(
    '/admin/agencies',
    function (
        Request $request,
        Response $_response,
    ) use (
        $accessGuard,
        $adminController,
        $executeWithCsrf,
    ): Response {
        $accessResponse = $accessGuard
            ->requireAdministrator();

        if ($accessResponse !== null) {
            return $accessResponse;
        }

        return $executeWithCsrf(
            $request,
            'agency_create',
            fn (): Response => $adminController
                ->storeAgency($request),
        );
    },
);

$router->get(
    '/admin/agencies/:id/edit',
    function (
        Request $_request,
        Response $_response,
        string $id,
    ) use (
        $accessGuard,
        $adminController,
    ): Response {
        $accessResponse = $accessGuard
            ->requireAdministrator();

        if ($accessResponse !== null) {
            return $accessResponse;
        }

        return $adminController->editAgency((int) $id);
    },
);

$router->post(
    '/admin/agencies/:id/update',
    function (
        Request $request,
        Response $_response,
        string $id,
    ) use (
        $accessGuard,
        $adminController,
        $executeWithCsrf,
    ): Response {
        $accessResponse = $accessGuard
            ->requireAdministrator();

        if ($accessResponse !== null) {
            return $accessResponse;
        }

        return $executeWithCsrf(
            $request,
            sprintf('agency_update_%d', (int) $id),
            fn (): Response => $adminController->updateAgency(
                $request,
                (int) $id,
            ),
        );
    },
);

$router->post(
    '/admin/agencies/:id/delete',
    function (
        Request $request,
        Response $_response,
        string $id,
    ) use (
        $accessGuard,
        $adminController,
        $executeWithCsrf,
    ): Response {
        $accessResponse = $accessGuard
            ->requireAdministrator();

        if ($accessResponse !== null) {
            return $accessResponse;
        }

        return $executeWithCsrf(
            $request,
            sprintf('agency_delete_%d', (int) $id),
            fn (): Response => $adminController
                ->deleteAgency((int) $id),
        );
    },
);

$router->get(
    '/admin/trips',
    function () use (
        $accessGuard,
        $adminController,
    ): Response {
        $accessResponse = $accessGuard
            ->requireAdministrator();

        if ($accessResponse !== null) {
            return $accessResponse;
        }

        return $adminController->trips();
    },
);

$router->post(
    '/admin/trips/:id/delete',
    function (
        Request $request,
        Response $_response,
        string $id,
    ) use (
        $accessGuard,
        $adminController,
        $executeWithCsrf,
    ): Response {
        $accessResponse = $accessGuard
            ->requireAdministrator();

        if ($accessResponse !== null) {
            return $accessResponse;
        }

        return $executeWithCsrf(
            $request,
            sprintf('admin_trip_delete_%d', (int) $id),
            fn (): Response => $adminController->deleteTrip(
                (int) $id,
            ),
        );
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

$router->get(
    '/trips/:id/edit',
    function (
        Request $_request,
        Response $_response,
        string $id,
    ) use (
        $accessGuard,
        $tripController,
    ): Response {
        $accessResponse = $accessGuard
            ->requireAuthentication();

        if ($accessResponse !== null) {
            return $accessResponse;
        }

        return $tripController->edit((int) $id);
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
        $executeWithCsrf,
    ): Response {
        $accessResponse = $accessGuard
            ->requireAuthentication();

        if ($accessResponse !== null) {
            return $accessResponse;
        }

        return $executeWithCsrf(
            $request,
            'trip_create',
            fn (): Response => $tripController->store($request),
        );
    },
);

$router->post(
    '/trips/:id/update',
    function (
        Request $request,
        Response $_response,
        string $id,
    ) use (
        $accessGuard,
        $tripController,
        $executeWithCsrf,
    ): Response {
        $accessResponse = $accessGuard
            ->requireAuthentication();

        if ($accessResponse !== null) {
            return $accessResponse;
        }

        return $executeWithCsrf(
            $request,
            sprintf(
                'trip_update_%d',
                (int) $id,
            ),
            fn (): Response => $tripController->update(
                $request,
                (int) $id,
            ),
        );
    },
);

$router->post(
    '/trips/:id/delete',
    function (
        Request $request,
        Response $_response,
        string $id,
    ) use (
        $accessGuard,
        $tripController,
        $executeWithCsrf,
    ): Response {
        $accessResponse = $accessGuard
            ->requireAuthentication();

        if ($accessResponse !== null) {
            return $accessResponse;
        }

        return $executeWithCsrf(
            $request,
            sprintf('trip_delete_%d', (int) $id),
            fn (): Response => $tripController->delete((int) $id),
        );
    },
);

$router->post(
    '/login',
    function (
        Request $request,
        Response $_response,
    ) use (
        $authController,
        $executeWithCsrf,
    ): Response {
        return $executeWithCsrf(
            $request,
            'login',
            fn (): Response => $authController
                ->authenticate($request),
        );
    },
);

$router->post(
    '/logout',
    function (
        Request $request,
        Response $_response,
    ) use (
        $authController,
        $executeWithCsrf,
    ): Response {
        return $executeWithCsrf(
            $request,
            'logout',
            fn (): Response => $authController->logout(),
        );
    },
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