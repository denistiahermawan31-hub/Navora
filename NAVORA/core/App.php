<?php
// NAVORA — core/App.php
// Front controller base class for the custom PHP MVC scaffold.
// Provides minimal routing infrastructure.

namespace NAVORA\Core;

class App
{
    protected $routes = [];
    protected $middleware = [];

    public function get($path, $target, $middleware = [])
    {
        $this->routes['GET'][$path] = ['target' => $target, 'middleware' => $middleware];
    }

    public function post($path, $target, $middleware = [])
    {
        $this->routes['POST'][$path] = ['target' => $target, 'middleware' => $middleware];
    }

    public function notFound($view)
    {
        http_response_code(404);
        require __DIR__ . '/../errors/404.php';
        exit;
    }
}