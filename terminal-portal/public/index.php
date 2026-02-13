<?php

require_once __DIR__ . '/../app/config/auth.php';
require_once __DIR__ . '/../app/helpers/response.php';
require_once __DIR__ . '/../app/middleware/auth_mw.php';
require_once __DIR__ . '/../app/middleware/permission_mw.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/MachineController.php';
require_once __DIR__ . '/../app/models/Machine.php';
require_once __DIR__ . '/../app/config/db.php';

start_session();

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$routes = [
    'GET' => [
        '/login' => function () {
            require __DIR__ . '/../views/pages/login.php';
        },
        '/logout' => function () {
            AuthController::logout();
        },
        '/dashboard' => function () {
            require_login();
            require_permission('dashboard.view');
            require __DIR__ . '/../views/pages/dashboard.php';
        },
        '/machines' => function () {
            require_login();
            require_permission('machine.view');
            require __DIR__ . '/../views/pages/machines_list.php';
        },
        '/machines/add' => function () {
            require_login();
            require_permission('machine.create');
            require __DIR__ . '/../views/pages/machines_form.php';
        },
        '/machines/edit' => function () {
            require_login();
            require_permission('machine.edit');
            require __DIR__ . '/../views/pages/machines_form.php';
        },
    ],
    'POST' => [
        '/login' => function () {
            AuthController::login();
        },
        '/api/auth/login' => function () {
            AuthController::apiLogin();
        },
        '/api/auth/logout' => function () {
            AuthController::apiLogout();
        },
        '/api/machines' => function () {
            require_permission('machine.create', true);
            MachineController::store();
        },
    ],
    'GET_API' => [
        '/api/auth/me' => function () {
            require_permission('auth.login', true);
            AuthController::apiMe();
        },
        '/api/machines' => function () {
            require_permission('machine.view', true);
            MachineController::index();
        },
    ],
    'PUT' => [
        '/api/machines' => function ($id) {
            require_permission('machine.edit', true);
            MachineController::update((int)$id);
        },
    ],
    'DELETE' => [
        '/api/machines' => function ($id) {
            require_permission('machine.delete', true);
            MachineController::destroy((int)$id);
        },
    ],
];

if ($path === '/' || $path === '') {
    header('Location: /dashboard');
    exit;
}

if ($method === 'GET' && isset($routes['GET'][$path])) {
    $routes['GET'][$path]();
    exit;
}

if ($method === 'POST' && isset($routes['POST'][$path])) {
    $routes['POST'][$path]();
    exit;
}

if ($method === 'GET' && isset($routes['GET_API'][$path])) {
    $routes['GET_API'][$path]();
    exit;
}

if ($method === 'GET' && preg_match('#^/api/machines/(\d+)$#', $path, $matches)) {
    require_permission('machine.view', true);
    MachineController::show((int)$matches[1]);
    exit;
}

if ($method === 'PUT' && preg_match('#^/api/machines/(\d+)$#', $path, $matches)) {
    $routes['PUT']['/api/machines']($matches[1]);
    exit;
}

if ($method === 'DELETE' && preg_match('#^/api/machines/(\d+)$#', $path, $matches)) {
    $routes['DELETE']['/api/machines']($matches[1]);
    exit;
}

http_response_code(404);
if (str_starts_with($path, '/api/')) {
    json_error('not_found', 'Endpoint bulunamadı.', null, 404);
}

echo 'Sayfa bulunamadı.';
