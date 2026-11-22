<?php
// NAVORA — app/controllers/LandingController.php
// Public Landing Page controller
// Guest: renders landing page
// Authenticated user: redirects to /dashboard

namespace NAVORA\Controllers;

use NAVORA\Core\Controller;

class LandingController extends Controller
{
    public function index()
    {
        // Check if user has active session
        if ($this->session->get('user_id')) {
            // Authenticated user: redirect to dashboard
            $this->redirect('/dashboard');
            return;
        }

        // Guest: render landing page
        $this->view('landing.index', [
            'pageTitle' => 'NAVORA - Tabungan untuk Tujuan yang Jelas'
        ]);
    }
}