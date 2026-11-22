<?php
// NAVORA — index.php
// Front controller single entry point.
// URL rewriting (e.g. Apache mod_rewrite or IIS URL Rewrite) recommended.
// All routes defined in routes/web.php.

// Load core infrastructure
require __DIR__ . '/core/App.php';
require __DIR__ . '/core/Controller.php';
require __DIR__ . '/core/Session.php';

// Autoloader: map NAVORA namespaces to files (minimal, covers Controllers, Models, Core)
spl_autoload_register(function ($class) {
    $parts = explode('\\', ltrim($class, '\\'));
    if (count($parts) < 2) {
        return;
    }
    $name = array_pop($parts);
    $ns = implode('\\', $parts);
    $paths = [
        'NAVORA\\Controllers' => '/app/controllers',
        'NAVORA\\Models' => '/app/models',
        'NAVORA\\Core' => '/core',
    ];
    foreach ($paths as $namespace => $dir) {
        if ($ns === $namespace) {
            $file = __DIR__ . $dir . '/' . $name . '.php';
            if (is_file($file)) {
                require $file;
                return;
            }
        }
    }
});

session_start();

$app = new NAVORA\Core\App();

// Define routes (this is normally done programmatically, but for this scaffold
// we'll use a simple dispatch approach)
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$uri = str_replace('/index.php/', '/', $uri);

 // Public routes (no session required)
 if ($uri === '/' || $uri === '') {
     // Landing page - check session
     if (isset($_SESSION['user_id'])) {
         // Authenticated user: redirect to dashboard
         header('Location: /dashboard');
         exit;
     }
     // Guest: render landing page
     $controller = new NAVORA\Controllers\LandingController();
     $controller->index();
 } elseif ($uri === '/masuk' && $_SERVER['REQUEST_METHOD'] === 'GET') {
     $controller = new NAVORA\Controllers\AuthController();
     $controller->showLogin();
 } elseif ($uri === '/masuk' && $_SERVER['REQUEST_METHOD'] === 'POST') {
     $controller = new NAVORA\Controllers\AuthController();
     $controller->login();
 } elseif ($uri === '/daftar' && $_SERVER['REQUEST_METHOD'] === 'GET') {
     $controller = new NAVORA\Controllers\AuthController();
     $controller->showRegister();
 } elseif ($uri === '/daftar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
     $controller = new NAVORA\Controllers\AuthController();
     $controller->register();
 } elseif ($uri === '/dashboard') {
     // Protected: require authentication
     if (!isset($_SESSION['user_id'])) {
         header('Location: /');
         exit;
     }
     $controller = new NAVORA\Controllers\DashboardController();
     $controller->index();
 } elseif ($uri === '/logout') {
     // Destroy session
     unset($_SESSION['user_id']);
     session_destroy();
     header('Location: /');
     exit;
 } else {
     // 404 Not Found
     $app->notFound('errors/404.php');
 }