<?php
// NAVORA — app/controllers/SavingsController.php
//
// STATUS: blocked on the persistence contract.
//
// The endpoint map is documented (API-SPECIFICATION.md, PROPOSED):
//   EP-17 GET  /goals/{id}/savings  index
//   EP-23 POST /goals/{id}/savings  store
//   EP-24 POST /savings/{id}        update  (OPEN-01)
//   EP-25 POST /savings/{id}/delete destroy (OPEN-02)
//
// Implementations require, and must not invent:
//   • the database engine and money column type (DATABASE-DESIGN.md OPEN)
//   • the query layer on core/Database.php (currently 0 bytes, Owner-owned)
//   • CSRF token generation and the flash-message contract
//   • confirmed edit/delete behavior (OPEN-01, OPEN-02)
//
// Until those exist, the read endpoint renders the real error state so no
// transaction, balance, or progress value is fabricated.

namespace NAVORA\Controllers;

use NAVORA\Core\Controller;

final class SavingsController extends Controller
{
    public function index(): void
    {
        http_response_code(503);

        $this->view('savings.index', [
            'pageTitle' => 'Riwayat Tabungan',
            'goal' => null,
            'rows' => [],
            'pagination' => null,
            'values' => null,
            'errors' => [],
            'loading' => false,
            'error' => 'Riwayat tabungan belum tersedia. Lapisan penyimpanan belum diaktifkan.',
        ]);
    }
}
