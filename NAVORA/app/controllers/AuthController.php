<?php
// NAVORA — app/controllers/AuthController.php
// Minimal auth controller for landing page routes
// Tidak mengimplementasi backend autentikasi lengkap (DEC-015/Open-08 status saat ini)

namespace NAVORA\Controllers;

use NAVORA\Core\Controller;
use NAVORA\Core\Session;

class AuthController extends Controller
{
    public function showLogin()
    {
        // Jika sudah login, arahkan ke dashboard
        if ($this->session->get('user_id')) {
            $this->redirect('/dashboard');
            return;
        }
        // Render halaman login sederhana
        $this->view('auth.login');
    }

    public function login()
    {
        // Placeholder: tidak melakukan validasi otentikasi sepihak
        // sesuai DEC-015 dan OPEN-08 status saat ini
        $this->session->set('login_attempt', true);
        $this->redirect('/');
        exit;
    }

    public function showRegister()
    {
        // Jika sudah login, arahkan ke dashboard
        if ($this->session->get('user_id')) {
            $this->redirect('/dashboard');
            return;
        }
        // Render halaman register sederhana
        $this->view('auth.register');
    }

    public function register()
    {
        // Placeholder: tidak melakukan registrasi sepihak
        // sesuai DEC-015 dan OPEN-08 status saat ini
        $this->session->set('register_attempt', true);
        $this->redirect('/');
        exit;
    }

    public function logout()
    {
        // Hapus session user
        $this->session->remove('user_id');
        $this->session->destroy();
        $this->redirect('/');
        exit;
    }
}