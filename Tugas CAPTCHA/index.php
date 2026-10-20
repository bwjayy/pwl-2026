<?php

session_start();

if (PHP_SAPI === 'cli-server') {
    $requestedFile = __DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (is_file($requestedFile)) {
        return false;
    }
}

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/core/Loader.php';
require_once __DIR__ . '/core/Auth.php';

// Calculate BASE_URL dynamically for both CLI server and Apache subdirectories
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$base = rtrim($scriptDir, '/');
define('BASE_URL', ($base === '/' || $base === '') ? '' : $base);

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = trim(substr($uri, strlen(BASE_URL)), '/');
$segments = $path === '' ? [] : explode('/', $path);

$page = $segments[0] ?? '';

// Root URL redirection
if ($page === '') {
    if (Auth::check()) {
        header('Location: ' . BASE_URL . '/accounts');
    } else {
        header('Location: ' . BASE_URL . '/login');
    }
    exit;
}

// Authentication Routes
if ($page === 'login') {
    require_once __DIR__ . '/controllers/AuthController.php';
    $auth = new AuthController();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $auth->authenticate();
    } else {
        $auth->login();
    }
    exit;
}

if ($page === 'captcha' || $page === 'captcha.php') {
    require_once __DIR__ . '/controllers/AuthController.php';
    $auth = new AuthController();
    $auth->captcha();
    exit;
}

if ($page === 'logout') {
    require_once __DIR__ . '/controllers/AuthController.php';
    $auth = new AuthController();
    $auth->logout();
    exit;
}

// Authentication Guard: require login for all resource pages
if (!Auth::check()) {
    header('Location: ' . BASE_URL . '/login');
    exit;
}

// Resource Routing
$noIdActions = ['create', 'store'];

if (!isset($segments[1]) || $segments[1] === '') {
    $id = null;
    $action = 'index';
} elseif (in_array($segments[1], $noIdActions, true)) {
    $id = null;
    $action = $segments[1];
} else {
    $id = $segments[1];
    $action = $segments[2] ?? 'detail';
}

$method = $_SERVER['REQUEST_METHOD'];
$postOnlyActions = ['store', 'update', 'delete'];
$expectsPost = in_array($action, $postOnlyActions, true);

$controllerMap = [
    'accounts' => 'Accounts',
    'account-type' => 'AccountType',
    'actions' => 'Actions',
];

$controllerName = $controllerMap[$page] ?? str_replace('-', '', ucwords($page, '-'));
$controllerFile = __DIR__ . '/controllers/' . $controllerName . '.php';

if (!file_exists($controllerFile)) {
    http_response_code(404);
    echo '<!DOCTYPE html><html><head><title>404 Not Found</title></head><body style="font-family:sans-serif;text-align:center;padding:50px;"><h2>404 - Halaman Tidak Ditemukan</h2><p><a href="' . BASE_URL . '/accounts">Kembali ke Beranda</a></p></body></html>';
    exit;
}

require_once $controllerFile;

if (!class_exists($controllerName)) {
    http_response_code(404);
    echo '404 - Controller tidak ditemukan';
    exit;
}

if (($expectsPost && $method !== 'POST') || (!$expectsPost && $method !== 'GET')) {
    http_response_code(405);
    echo '405 - Metode Request tidak diizinkan';
    exit;
}

$controller = new $controllerName();

if (!method_exists($controller, $action)) {
    http_response_code(404);
    echo '404 - Aksi tidak ditemukan';
    exit;
}

if ($id === null) {
    $controller->$action();
} else {
    $controller->$action($id);
}
