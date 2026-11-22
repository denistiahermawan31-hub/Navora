<?php
// NAVORA — core/Controller.php
// Base controller class for the custom PHP MVC scaffold.
// Provides view rendering and session helpers.

namespace NAVORA\Core;

use NAVORA\Core\Session;

class Controller
{
    protected $view;
    protected $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    public function view($view, $data = [])
    {
        extract($data);
        require __DIR__ . '/../app/views/' . str_replace('.', '/', $view) . '.php';
    }

    protected function redirect($url)
    {
        header('Location: ' . $url);
        exit;
    }

    protected function authorize($condition, $message = 'Akses ditolak')
    {
        if (!$condition) {
            http_response_code(403);
            die($message);
        }
    }
}