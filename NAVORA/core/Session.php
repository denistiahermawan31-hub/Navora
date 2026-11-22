<?php
// NAVORA — core/Session.php
// Simple session handler for the NAVORA PHP MVC scaffold.

namespace NAVORA\Core;

class Session
{
    public function start()
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }
        session_start();
    }

    public function get($key, $default = null)
    {
        $this->start();
        return $_SESSION[$key] ?? $default;
    }

    public function set($key, $value)
    {
        $this->start();
        $_SESSION[$key] = $value;
    }

    public function has($key)
    {
        $this->start();
        return isset($_SESSION[$key]);
    }

    public function remove($key)
    {
        $this->start();
        unset($_SESSION[$key]);
    }

    public function destroy()
    {
        $this->start();
        session_destroy();
    }
}