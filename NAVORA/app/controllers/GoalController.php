<?php
// NAVORA — app/controllers/GoalController.php
//
// STATUS: blocked on the persistence contract.
//
// The endpoint map is documented (API-SPECIFICATION.md, PROPOSED):
//   EP-13 GET  /goals              index
//   EP-14 GET  /goals/create       create
//   EP-15 GET  /goals/{id}         show
//   EP-16 GET  /goals/{id}/edit    edit
//   EP-18 GET  /arsip              archived
//   EP-19 POST /goals              store
//   EP-20 POST /goals/{id}         update
//   EP-21 POST /goals/{id}/arsipkan archive
//   EP-22 POST /goals/{id}/pulihkan restore
//
// Implementations require, and must not invent:
//   • the database engine and money column type (DATABASE-DESIGN.md OPEN)
//   • the query layer on core/Database.php (currently 0 bytes, Owner-owned)
//   • CSRF token generation and the flash-message contract
//
// Until those exist, the read endpoint renders the real error state so the
// application never presents invented goals or fabricated balances.

namespace NAVORA\Controllers;

use NAVORA\Core\Controller;

final class GoalController extends Controller
{
    public function index(): void
    {
        $search = isset($_GET['q']) ? trim((string) $_GET['q']) : '';
        $sort = isset($_GET['sort']) ? (string) $_GET['sort'] : 'recent';
        if (!in_array($sort, ['recent', 'target_date', 'progress'], true)) {
            $sort = 'recent';
        }

        http_response_code(503);

        $this->view('goals.index', [
            'pageTitle' => 'Goals',
            'goals' => [],
            'pagination' => null,
            'searchTerm' => $search,
            'sort' => $sort,
            'total' => 0,
            'loading' => false,
            'error' => 'Layanan goal belum tersedia. Lapisan penyimpanan belum diaktifkan.',
        ]);
    }
}
