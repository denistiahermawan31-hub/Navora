# NAVORA — Wireframe Specification

| Field | Value |
|---|---|
| Document ID | NAVORA-WF |
| Version | 0.2.0 (Consolidated) |
| Date | 2026-11-22 |
| Status | **PROPOSED — low-fidelity specification, requires approval** |
| Related | `UI-UX-GUIDELINES.md`, `COMPONENT-SPECIFICATION.md`, `DESIGN-SYSTEM.md` |

Wireframes are structural, not visual. They define regions, hierarchy, and behavior. Colors, type, and spacing come from `DESIGN-SYSTEM.md`. No wireframe is an implementation.

All user-facing labels are Bahasa Indonesia (DEC-013). Money uses `Rp15.000.000` format with no decimals (DEC-007).

Legend: `[ ]` button · `( )` input · `▸` link · `▓` progress fill · `·` spacer · `▤` table

---

## WF-01 — Dashboard (Desktop ≥1280px)

**Status: `APPROVED — Owner approved`** — 2026-11-22, given directly by the Project Owner. Applies to WF-01 only; all other wireframes remain `PROPOSED`.

```text
┌───────────────┬──────────────────────────────────────────────────────────────┐
│ NAVORA        │  Dashboard                              [ + Tambah Goal ]   │
│               │  Ringkasan tabungan Anda                                     │
│ ▸ Dashboard   │                                                              │
│ ▸ Goals       │  ┌────────────────┬────────────────┬────────────────┐        │
│ ▸ Arsip       │  │ [ jumlah ]     │ Rp[…]          │ Rp[…]          │        │
│               │  └────────────────┴────────────────┴────────────────┘        │
│               │                                                              │
│               │  Hampir tercapai                          ▸ Lihat semua goal│
│               │  ┌──────────────────────────────┐  ┌────────────────────────┐│
│               │  │ [ nama goal ]     Hampir     │  │ [ nama goal ]   Hampir ││
│               │  │ Rp[…] / Rp[…]                │  │ Rp[…] / Rp[…]          ││
│               │  │ ▓▓▓▓▓▓▓▓▓▓░░  90,5%         │  │ ▓▓▓▓▓▓▓▓▓░░░ [ n,n% ] ││
│               │  │ Sisa Rp[…]                   │  │ Sisa Rp[…]             ││
│               │  └──────────────────────────────┘  └────────────────────────┘│
│               │  (maks. 3 kartu, urut progres tertinggi)                     │
│               │                                                              │
│               │  Tabungan terbaru                                            │
│               │  ┌──────────────────────────────────────────────────────────┐│
│               │  │ [ tanggal ] · [ nama goal ] · [ jenis ] Rp[…]            ││
│               │  └──────────────────────────────────────────────────────────┘│
│ [ akun ]      │                                                              │
│ [ Keluar ]    │                                                              │
└───────────────┴──────────────────────────────────────────────────────────────┘
```

**Notes (owner-approved decisions D-01..D-12 recorded in `DECISIONS.md` §1.2.3)**
- **D-04 — Five blocks.** Goal Aktif, Total Tabungan, Total Sisa, Hampir Tercapai, Tabungan Terbaru. The three summary figures stay in **one band separated by hairlines**, not three identical cards. This resolves `OPEN-06` for WF-01.
- **D-01 — Hampir Tercapai example.** Only `90% <= progress < 100%` may carry the label; the former 42%-style example is removed. Illustrative value: `90,5%`. Source: DEC-012 / RULE-054.
- **D-02 — No demo finance figures.** All amounts are structural placeholders (`Rp[…]`, `[ jumlah ]`). No seed or demo data is created; examples must not be read as valid data.
- **D-03 — No global history.** The "Lihat riwayat" link is removed and no global transaction-history feature is added. History remains per goal; "Lihat semua goal" → `/goals` is retained.
- **D-05 — Navigation and states.** Sidebar destinations are Dashboard, Goals, Arsip. Loading, empty, and error states follow the approved component contracts with no fabricated financial figures; documented accessibility and navigation patterns are retained.
- **D-07 — Percentage.** One decimal with comma, e.g. `90,5%` (`OPEN-15` resolved for WF-01).
- **D-08 — Negative money.** `-Rp2.000.000` (`OPEN-16` resolved for WF-01); DEC-025 negative-balance behavior unchanged.
- **D-09 — Date.** `22 Nov 2026` with Indonesian month names; timezone stays Asia/Jakarta (DEC-014).
- **D-10 — Goal Aktif.** Every non-archived goal is counted, including goals with `Tercapai` status (DEC-008; RULE-032 behavior resolved for WF-01).
- **D-11 — Hampir Tercapai section.** At most 3 cards, ordered by highest progress; the section is hidden when no goal is in range.
- **D-12 — Account area.** Account information and the `Keluar` button are pinned to the bottom of the sidebar; logout follows UF-12.
- Archived goals are excluded from all figures. Cards show only real data — no sparkline, no fake trend arrow.
- **Empty / feedback states.** No goals → one centered empty state with a single "Tambah Goal" CTA and **no zero-valued summary band**; goals exist but no transactions → summary and goal list remain, and "Tabungan Terbaru" shows "Belum ada tabungan tercatat"; no Hampir Tercapai goal → section hidden; loading and error states are informative and never display invented financial figures.
- DEC-010 (exact BESTCO blue) and DEC-019 Option A (Plus Jakarta Sans, JetBrains Mono, local inline SVG) remain the design basis.
- `C-19` (invalid-page fallback) and `C-20` (page size) were not part of this approval and remain `OPEN`. Other wireframes are unchanged.
```
---

## WF-02 — Goals List (Desktop)

```text
┌───────────────┬──────────────────────────────────────────────────────────────┐
│ NAVORA        │  Goals (3)                               [ + Tambah Goal ]   │
│               │  ( Cari goal…              )   Urutkan: [ Terbaru ▾ ]        │
│ ▸ Dashboard   │                                                              │
│ ▸ Goals       │  ┌────────────────────┐ ┌────────────────────┐ ┌──────────┐ │
│ ▸ Arsip       │  │ Yamaha Aerox    ⋯  │ │ Laptop          ⋯  │ │ Trip     │ │
│               │  │ target 20 Des 2026 │ │ tanpa tanggal      │ │ …        │ │
│               │  │ Rp12.500.000       │ │ Rp6.000.000        │ │          │ │
│               │  │ dari Rp30.000.000  │ │ dari Rp15.000.000  │ │          │ │
│               │  │ ▓▓▓▓▓▓░░░░░  42%   │ │ ▓▓▓▓░░░░░░░  40%   │ │          │ │
│               │  │ Sisa Rp17.500.000  │ │ Sisa Rp9.000.000   │ │          │ │
│               │  └────────────────────┘ └────────────────────┘ └──────────┘ │
│               │                                                              │
│               │        ▸ Sebelumnya   1   2   3   …   5   Berikutnya ▸      │
└───────────────┴──────────────────────────────────────────────────────────────┘
```

**Behavior**
- Title is the link to detail; the card itself is not a button.
- `⋯` menu: Edit, Arsipkan.
- Numbered pagination (DEC-009); `20` goals per page is a draft default (`C-20` OPEN).
- Search with no matches → WF-09.
- Sort options: Terbaru, Tanggal target, Progres (`OPEN-05`).

---

## WF-03 — Goal Detail (Desktop)

```text
┌───────────────┬──────────────────────────────────────────────────────────────┐
│ NAVORA        │  ▸ Goals / Yamaha Aerox                                     │
│               │                                                              │
│ ▸ Dashboard   │  Yamaha Aerox           [ Berlangsung ]  [ Edit ] [ ⋯ ]    │
│ ▸ Goals       │  Tanggal target: 20 Desember 2026                            │
│ ▸ Arsip       │                                                              │
│               │  ┌──────────────────────────────────────────────────────────┐│
│               │  │  SALDO                                                   ││
│               │  │  Rp12.500.000                                            ││
│               │  │  dari target Rp30.000.000                 Sisa            ││
│               │  │                                          Rp17.500.000     ││
│               │  │  ▓▓▓▓▓▓▓▓▓▓░░░░░░░░░░░░░░░░░░  42%                       ││
│               │  │                                                          ││
│               │  │  [ + Tambah Tabungan ]                                   ││
│               │  └──────────────────────────────────────────────────────────┘│
│               │                                                              │
│               │  Deskripsi                                                   │
│               │  Menabung untuk DP motor.                                    │
│               │                                                              │
│               │  Tabungan terbaru                          ▸ Lihat semua (12)│
│               │  ┌──────────────────────────────────────────────────────────┐│
│               │  │ Tanggal      Jenis      Nominal        Catatan   Aksi    ││
│               │  │ 18 Nov 26    Setoran    Rp1.500.000    Bonus     ⋯       ││
│               │  │ 12 Nov 26    Penarikan    Rp750.000    —         ⋯       ││
│               │  └──────────────────────────────────────────────────────────┘│
└───────────────┴──────────────────────────────────────────────────────────────┘
```

**Notes**
- Balance is the largest figure on the page and may be negative.
- Progress includes a numeric percentage; it never exceeds 100%.
- Remaining and excess (if any) are always visible without scrolling on desktop.
- `⋯` holds Edit and Arsipkan.

---

## WF-04 — Savings History (Desktop)

```text
┌───────────────┬──────────────────────────────────────────────────────────────┐
│ NAVORA        │  ▸ Goals / Yamaha Aerox / Riwayat Tabungan                   │
│               │  Riwayat Tabungan — Yamaha Aerox        [ + Tambah Tabungan]│
│               │                                                              │
│               │  ┌──────────────────────────────────────────────────────────┐│
│               │  │ Tanggal       Jenis      Nominal       Catatan   Aksi    ││
│               │  ├──────────────────────────────────────────────────────────┤│
│               │  │ 18 Nov 2026   Setoran    Rp1.500.000   Bonus     ⋯       ││
│               │  │ 12 Nov 2026   Penarikan    Rp750.000   —         ⋯       ││
│               │  │ 05 Nov 2026   Setoran    Rp2.000.000   Bulanan   ⋯       ││
│               │  │ … 17 baris lainnya                                       ││
│               │  └──────────────────────────────────────────────────────────┘│
│               │                                                              │
│               │        ▸ Sebelumnya   1   2   3   …   8   Berikutnya ▸      │
└───────────────┴──────────────────────────────────────────────────────────────┘
```

**Rules:** numbered pagination (DEC-009); `20` rows per page is a draft default (`C-20` OPEN); amounts right-aligned; empty state WF-10.

---

## WF-05 — Goal Form (Desktop, create/edit)

```text
┌───────────────┬──────────────────────────────────────────────────────────────┐
│ NAVORA        │  Goal Baru                                                   │
│               │  Tentukan apa yang ingin Anda tabung.                        │
│ ▸ Dashboard   │                                                              │
│ ▸ Goals       │  ┌────────────────────────────────────────────────────────┐  │
│ ▸ Arsip       │  │ Nama *                                                 │  │
│               │  │ ( Yamaha Aerox                                       ) │  │
│               │  │                                                        │  │
│               │  │ Target harga *                                         │  │
│               │  │ ( 30000000                                         ) Rp │  │
│               │  │ Masukkan jumlah penuh yang ingin Anda tabung.          │  │
│               │  │                                                        │  │
│               │  │ Tanggal target (opsional)                              │  │
│               │  │ ( 2026-12-20                                       )   │  │
│               │  │                                                        │  │
│               │  │ Deskripsi (opsional)                                   │  │
│               │  │ ( Menabung untuk DP motor.                          )  │  │
│               │  │                                          0 / 500       │  │
│               │  │                                                        │  │
│               │  │                             [ Batal ]  [ Simpan Goal ]│  │
│               │  └────────────────────────────────────────────────────────┘  │
└───────────────┴──────────────────────────────────────────────────────────────┘
```

**Rules:** labels always visible; required marked; inline help; error summary above the first field on failure; integer-only IDR input; past target dates accepted (DEC-026).

---

## WF-06 — Add Savings (Modal, Desktop)

```text
        ┌──────────────────────────────────────────────┐
        │  Tambah Tabungan — Yamaha Aerox          [×] │
        │                                              │
        │  Jenis *                                     │
        │  (•) Setoran      ( ) Penarikan              │
        │                                              │
        │  Nominal *                                   │
        │  ( 1500000                                )  │
        │  Rp · tanpa desimal                          │
        │                                              │
        │  Tanggal *                                   │
        │  ( 2026-11-22                             )  │
        │                                              │
        │  Catatan (opsional)                          │
        │  ( Bonus                                  )  │
        │                                              │
        │                    [ Batal ]  [ Simpan ]     │
        └──────────────────────────────────────────────┘
```

**Behavior:** focus moves to Nominal on open; Escape cancels; on save, modal closes and the underlying page shows the updated balance plus a toast. A withdrawal larger than the balance is accepted after a clear notice.

---

## WF-07 — Confirmation Dialog (Arsipkan Goal)

```text
        ┌──────────────────────────────────────────────┐
        │  Arsipkan “Yamaha Aerox”?                [×] │
        │                                              │
        │  Goal ini akan dipindahkan ke Arsip.         │
        │  12 catatan tabungan tetap disimpan dan      │
        │  goal dapat dipulihkan kapan saja.           │
        │                                              │
        │                    [ Batal ]  [ Arsipkan ]   │
        └──────────────────────────────────────────────┘
```

**Rules:** message must name the record and state the outcome; archiving is reversible and retains history (DEC-008); default focus on Batal; confirm button uses a neutral/danger variant. Hard delete is not offered.

---

## WF-08 — Dashboard Empty State

```text
┌───────────────┬──────────────────────────────────────────────────────────────┐
│ NAVORA        │  Dashboard                                                   │
│               │                                                              │
│ ▸ Dashboard   │              ┌────────────────────────────┐                  │
│ ▸ Goals       │              │         [ikon target]      │                  │
│ ▸ Arsip       │              │  Belum ada goal            │                  │
│               │              │  Buat goal untuk mulai     │                  │
│               │              │  mencatat tabungan.        │                  │
│               │              │      [ + Buat Goal ]       │                  │
│               │              └────────────────────────────┘                  │
└───────────────┴──────────────────────────────────────────────────────────────┘
```

**Rules:** no zero-value summary cards above an empty state; the empty state is the primary content.

---

## WF-09 — No Search Results

```text
│  ( aerox                            )   Urutkan: [ Terbaru ▾ ]      │
│                                                                     │
│                     Tidak ada goal yang cocok dengan “aerox”.       │
│                     [ Hapus pencarian ]                             │
```

---

## WF-10 — Empty Savings History

```text
│  Tabungan terbaru                                                   │
│  ┌───────────────────────────────────────────────────────────────┐  │
│  │  Belum ada tabungan tercatat.                                 │  │
│  │  Tambahkan nominal pertama untuk mulai melihat progres.       │  │
│  │  [ + Tambah Tabungan ]                                        │  │
│  └───────────────────────────────────────────────────────────────┘  │
```

---

## WF-11 — Mobile: Dashboard (360px)

**Status: `APPROVED — Owner approved`** — 2026-11-22, given directly by the Project Owner. Applies to WF-11 only; all other wireframes remain `PROPOSED`.

```text
┌─────────────────────────────────┐
│ ☰  NAVORA              [ + ]    │
├─────────────────────────────────┤
│ Dashboard                       │
│ Ringkasan tabungan Anda         │
│                                 │
│ ┌─────────────────────────────┐ │
│ │ [ jumlah ]                  │ │
│ │ Rp[…]                       │ │
│ │ Sisa Rp[…]                  │ │
│ └─────────────────────────────┘ │
│                                 │
│ Hampir tercapai                 │
│ ┌─────────────────────────────┐ │
│ │ [ nama goal ]               │ │
│ │ Rp[…] / Rp[…]               │ │
│ │ ▓▓▓▓▓▓▓▓▓▓░░  90,5%         │ │
│ │ Sisa Rp[…]                  │ │
│ └─────────────────────────────┘ │
│                                 │
│ (maks. 3 kartu, urut progres tertinggi) │
│                                 │
│ Tabungan terbaru                │
│ ┌─────────────────────────────┐ │
│ │ [ tanggal ] · [ nama goal ] · [ jenis ] Rp[…] ││
│ └─────────────────────────────┘ │
│ [ akun ]                        │
│ [ Keluar ]                      │
└─────────────────────────────────┘
```

**Notes (owner-approved decisions M-01..M-12 recorded in `DECISIONS.md` §1.2.4)**
- **M-01 — No demo data.** All amounts are structural placeholders (`Rp[…]`, `[ jumlah ]`, `[ nama goal ]`). No seed or demo data is created; examples must not be read as valid data.
- **M-02 & M-03 — Hampir Tercapai illustration.** Only `90% <= progress < 100%` may carry the label; illustrative value: `90,5%` one decimal with comma. Source: DEC-012 / RULE-054 / WF-01 D-01.
- **M-04 — Mobile navigation drawer.** Destinations: Dashboard, Goals, Arsip, akun, Keluar. Account and Keluar pinned to bottom of drawer. Behavior follows CMP-03: focus trap, Escape/backdrop close, body scroll lock, focus returns to trigger, `aria-expanded`, `role="dialog"`, `aria-modal="true"`.
- **M-05 — Primary action.** Header uses `+ Tambah` button with visible icon and text; action leads to add goal flow; not an icon-only button.
- **M-06 — Status badge.** Kartu Hampir Tercapai wajib menampilkan badge teks `Hampir`; status tidak boleh disampaikan melalui warna saja. Ikuti CMP-11.
- **M-07 — Hampir Tercapai rules.** Only goals with `90% <= progress < 100%`; maksimal 3 kartu di dashboard; urut berdasarkan progres tertinggi; section disembunyikan jika tidak ada goal yang memenuhi kriteria.
- **M-08 — Tabungan Terbaru.** Maksimal 5 transaksi terbaru; tanggal format Indonesia `22 Nov 2026`; zona Asia/Jakarta; bila belum ada transaksi, tampilkan `Belum ada tabungan tercatat` bukan angka nol; placeholder struktural, bukan data demo.
- **M-09 — UI states.** No goals → one centered empty state with single "Tambah Goal" CTA and **no summary band** with zero-valued figures; goals exist but no transactions → summary and goal list remain, "Tabungan Terbaru" shows "Belum ada tabungan tercatat"; no Hampir Tercapai goal → section hidden; loading → skeleton mengikuti geometri akhir dan `aria-busy="true"`; error → pesan sederhana dalam Bahasa Indonesia beserta aksi pemulihan, tanpa angka finansial palsu atau exception text.
- **M-10 — Responsive and accessibility.** Design basis DEC-010 and DEC-019 Option A; page gutter 16px; tidak ada horizontal scrolling pada viewport 360px; gunakan skip link dan semantic `<main>`; focus indicator mengikuti DESIGN-SYSTEM; progress bar memiliki `role="progressbar"` dan nilai teks yang dapat diakses; status tidak hanya dibedakan melalui warna; toast mobile mengikuti UI-UX-GUIDELINES; hormati `prefers-reduced-motion`.
- **M-11 — Goals navigation.** Tidak perlu tautan `Lihat semua goal` tambahan pada section Hampir Tercapai; navigasi ke seluruh goal cukup melalui item `Goals` pada mobile drawer; jangan menambahkan global transaction history.
- **M-12 — Negative balance.** Saldo negatif tetap didukung sesuai DEC-025; format tampilan negatif mengikuti keputusan WF-01, contoh `-Rp2.000.000`.
- Archived goals are excluded from all figures. Cards show only real data — no sparkline, no fake trend arrow.
- **Empty / feedback states.** No goals → one centered empty state with a single "Tambah Goal" CTA and **no zero-valued summary band**; goals exist but no transactions → summary and goal list remain, and "Tabungan Terbaru" shows "Belum ada tabungan tercatat"; no Hampir Tercapai goal → section hidden; loading and error states are informative and never display invented financial figures.
- DEC-010 (exact BESTCO blue) and DEC-019 Option A (Plus Jakarta Sans, JetBrains Mono, local inline SVG) remain the design basis.
- `C-19` (invalid-page fallback) and `C-20` (page size) were not part of this approval and remain `OPEN`. Other wireframes are unchanged.
```
---

## WF-12 — Mobile: Goal Detail

```text
┌─────────────────────────────────┐
│ ☰  NAVORA              [ + ]    │
├─────────────────────────────────┤
│ ▸ Goals / Yamaha Aerox          │
│ Yamaha Aerox      [ Berlangsung]│
│ target 20 Des 2026              │
│                                 │
│ SALDO                           │
│ Rp12.500.000                    │
│ dari target Rp30.000.000        │
│ ▓▓▓▓▓▓▓░░░░░░░  42%             │
│ Sisa Rp17.500.000               │
│                                 │
│ [        + Tambah Tabungan   ]  │
│                                 │
│ [ Edit ]            [ Arsipkan ]│
│                                 │
│ Tabungan terbaru                │
│ ┌─────────────────────────────┐ │
│ │ 18 Nov 2026                 │ │
│ │ Setoran Rp1.500.000 · Bonus │ │
│ └─────────────────────────────┘ │
└─────────────────────────────────┘
```

---

## WF-13 — Mobile: Savings History (Stacked Rows)

```text
│ Riwayat Tabungan — Yamaha Aerox │
│ [        + Tambah Tabungan   ]  │
│                                 │
│ ┌─────────────────────────────┐ │
│ │ 18 Nov 2026          ⋯      │ │
│ │ Setoran Rp1.500.000         │ │
│ │ Bonus                       │ │
│ └─────────────────────────────┘ │
│ ┌─────────────────────────────┐ │
│ │ 12 Nov 2026          ⋯      │ │
│ │ Penarikan Rp750.000         │ │
│ │ —                           │ │
│ └─────────────────────────────┘ │
│                                 │
│ [ Sebelumnya ]    [ Berikutnya ]│
```

**Rules:** table converts to stacked blocks; each value keeps its label context; no horizontal scrolling required.

---

## WF-14 — Page-Level Error (500)

```text
┌──────────────────────────────────────────────┐
│ NAVORA                                       │
│                                              │
│              Terjadi kesalahan               │
│   Kami tidak dapat menyelesaikan tindakan    │
│   tersebut. Data Anda tidak berubah.         │
│                                              │
│              [ Kembali ke dashboard ]        │
│                                              │
│   Referensi: ERR-2026-11-22-001              │
└──────────────────────────────────────────────┘
```

**Rules:** no stack traces, file paths, SQL, or exception class names shown to users; the reference ID is logged server-side.

---

## WF-15 — Archive View (Desktop)

```text
┌───────────────┬──────────────────────────────────────────────────────────────┐
│ NAVORA        │  Arsip                                                       │
│               │  Goal yang diarsipkan tetap menyimpan riwayat.               │
│ ▸ Dashboard   │                                                              │
│ ▸ Goals       │  ┌──────────────────────────────────────────────────────────┐│
│ ▸ Arsip       │  │ Nama              Diarsipkan    Saldo terakhir   Aksi    ││
│               │  ├──────────────────────────────────────────────────────────┤│
│               │  │ Trip Bali         10 Nov 2026   Rp2.000.000     [Pulihkan]││
│               │  │ Kamera Lama       02 Okt 2026   -Rp250.000      [Pulihkan]││
│               │  └──────────────────────────────────────────────────────────┘│
│               │                                                              │
│               │        ▸ Sebelumnya   1   2   Berikutnya ▸                  │
└───────────────┴──────────────────────────────────────────────────────────────┘
```

**Rules:** archived goals are read-only except restore; numbered pagination; empty state WF-16.

---

## WF-16 — Archive Empty State

```text
│  ┌───────────────────────────────────────────────────────────────┐  │
│  │  Belum ada goal yang diarsipkan.                              │  │
│  │  Goal yang Anda arsipkan akan muncul di sini.                 │  │
│  └───────────────────────────────────────────────────────────────┘  │
```

---

## WF-17 — Login / Register (Desktop)

```text
        ┌──────────────────────────────────────────────┐
        │  NAVORA                                      │
        │  Masuk ke akun Anda                          │
        │                                              │
        │  Email *                                     │
        │  ( nama@contoh.com                        )  │
        │                                              │
        │  Kata sandi *                                │
        │  ( ••••••••                               )  │
        │                                              │
        │                    [ Masuk ]                 │
        │                                              │
        │  ▸ Lupa kata sandi?                          │
        │  Belum punya akun? ▸ Daftar                  │
        └──────────────────────────────────────────────┘
```

**Rules:** no marketing panel; one primary action; errors appear above the form; password fields are never repopulated.

---

## WF-18 — Public Landing Page (Desktop)

```text
┌────────────────────────────────────────────────────────────────────────────────┐
│ ◎ NAVORA      Tentang    Fitur    Cara Kerja              [ Masuk ] [ Daftar ] │ ← 64px
├────────────────────────────────────────────────────────────────────────────────┤
│                                                                                │
│  ┌──────────────────────────────────┐   ┌──────────────────────────────────┐  │
│  │                                  │   │                                  │  │
│  │  Menabung jadi lebih terarah.    │   │   ┌──────────────────────────┐   │  │
│  │                                  │   │   │   [ placeholder visual ] │   │  │
│  │  Buat tujuan tabungan, catat     │   │   │   target · progres ·     │   │  │
│  │  setoran dan penarikan, lalu     │   │   │   koin (SVG garis)       │   │  │
│  │  pantau progres sampai tercapai. │   │   │   tanpa angka/data       │   │  │
│  │                                  │   │   └──────────────────────────┘   │  │
│  │  [ Daftar ]   [ Masuk ]          │   │                                  │  │
│  │                                  │   │                                  │  │
│  └──────────────────────────────────┘   └──────────────────────────────────┘  │
│              HERO — 2 kolom (1:1), konten penuh, latar --nv-white             │
├────────────────────────────────────────────────────────────────────────────────┤
│                             Tentang NAVORA                                     │
│              NAVORA membantu mencatat tabungan untuk satu tujuan yang jelas:  │
│              target harga, catatan setoran, dan progres yang mudah dibaca.     │
├────────────────────────────────────────────────────────────────────────────────┤
│                                Fitur Utama                                     │
│  ┌───────────┐  ┌───────────┐  ┌───────────┐                                  │
│  │  (ikon)   │  │  (ikon)   │  │  (ikon)   │                                  │
│  │  Target   │  │ Setoran & │  │  Progres  │                                  │
│  │  harga &  │  │ penarikan │  │  saldo    │                                  │
│  │  tanggal  │  │           │  │           │                                  │
│  └───────────┘  └───────────┘  └───────────┘                                  │
│  ┌───────────┐  ┌───────────┐  ┌───────────┐                                  │
│  │  (ikon)   │  │  (ikon)   │  │  (ikon)   │                                  │
│  │  Riwayat  │  │  Arsip &  │  │   Akun    │                                  │
│  │  tabungan │  │ pulihkan  │  │  pribadi  │                                  │
│  └───────────┘  └───────────┘  └───────────┘                                  │
│                     GRID 3 kolom × 2 baris, card 12px radius                   │
├────────────────────────────────────────────────────────────────────────────────┤
│                                Cara Kerja                                      │
│   ┌─────────┐      ┌───────────┐     ┌────────────┐    ┌───────────┐          │
│   │1 Daftar │ ───▶ │2 Cek Email│ ──▶ │3 Verifikasi│ ─▶ │4 Dashboard│          │
│   └─────────┘      └───────────┘     └────────────┘    └───────────┘          │
│              langkah horizontal, nomor urut, bukan angka statistik              │
├────────────────────────────────────────────────────────────────────────────────┤
│  ┌──────────────────────────────────────────────────────────────────────────┐  │
│  │            Mulai atur tujuan tabunganmu hari ini.                        │  │
│  │                            [ Daftar ]                                    │  │
│  │                  surface --nv-blue-50, satu primary action               │  │
│  └──────────────────────────────────────────────────────────────────────────┘  │
├────────────────────────────────────────────────────────────────────────────────┤
│  ◎ NAVORA                                              © 2026 NAVORA          │ ← footer
└────────────────────────────────────────────────────────────────────────────────┘
```

**Rules:** no user-specific financial data; no goal cards, no dashboard summary; responsive 360–1920px; DEC-010 palette (`--nv-blue-50` surface, `--nv-blue-600` primary), DEC-019 typography (Plus Jakarta Sans, JetBrains Mono tabular-nums only for monetary placeholders which are absent); CTA Masuk/Daftar target 44×44px fokus fokusable; langganan newsletter tidak dalam MVP; header desktop: wordmark kiri, nav tengah, CTA kanan 64px; hero dua kolom 1:1; feature grid 3×2 card radius 12px; cara kerja 4 langkah horizontal dengan konnector 1px; footer minimal: wordmark + © 2026 NAVORA, tanpa link kebijakan/syarat/tambahan.

---

## WF-19 — Public Landing Page (Mobile 360px)

```text
│ ☰ NAVORA              Daftar                          │ ← 56px: hamburger + wordmark + 1 CTA
├─────────────────────────────────────────────────────────┤
│ DRAWER (saat ☰ ditekan):                              │
│   Tentang                                               │
│   Fitur                                                 │
│   Cara Kerja                                            │
│   ─────────────                                         │
│   Masuk                                                 │
│   Daftar                                                │
├─────────────────────────────────────────────────────────┤
│ Menabung jadi lebih terarah.                          │ ← h1 display 30/38, weight 600
│                                 │
│ Buat tujuan tabungan, catat                           │
│ setoran dan penarikan, lalu                           │
│ pantau progres sampai tercapai.                       │
│                                 │
│ [           Daftar           ]                        │ ← primary, full-width
│ [           Masuk           ]                         │ ← secondary, full-width
│                                 │
│ ┌────────────────────────────┐                        │
│ │  [ placeholder visual ]    │                        │ ← stack di bawah copy
│ │  target · progres · koin   │                        │
│ └────────────────────────────┘                        │
├─────────────────────────────────────────────────────────┤
│ Tentang NAVORA                                          │
│ NAVORA membantu mencatat tabungan untuk satu tujuan   │
│ yang jelas: target harga, catatan setoran, dan      │
│ progres yang mudah dibaca.                            │
├─────────────────────────────────────────────────────────┤
│ Fitur Utama                                             │
│ ┌────────────────────────────┐                        │
│ │ (ikon) Target harga &      │                        │ 1 kolom penuh
│ │        tanggal opsional    │                        │
│ └────────────────────────────┘                        │
│ ┌────────────────────────────┐                        │
│ │ (ikon) Setoran & penarikan │                        │
│ └────────────────────────────┘                        │
│ ┌────────────────────────────┐                        │
│ │ (ikon) Progres saldo       │                        │
│ └────────────────────────────┘                        │
│ ... 3 card lainnya                                    │
├─────────────────────────────────────────────────────────┤
│ Cara Kerja                                              │
│   ① Daftar                                              │
│          │                                              │
│   ② Cek Email                                             │
│          │                                              │
│   ③ Verifikasi                                              │
│          │                                              │
│   ④ Dashboard                                               │ ← vertikal, connector 1px
├─────────────────────────────────────────────────────────┤
│ Mulai atur tujuan tabunganmu.                         │
│ [           Daftar           ]                        │
│ [           Masuk           ]                         │
├─────────────────────────────────────────────────────────┤
│ ◎ NAVORA                                                │
│ © 2026 NAVORA                                           │
└─────────────────────────────────────────────────────────┘
```

**Rules:** single-column layout, full-width; hamburger `aria-label="Buka menu"`, drawer trap fokus, tutup Escape/backdrop; fokus dikembalikan ke tombol menu; tombol panah kembali `[ ← ]` dihapus — tidak ada return path pada halaman publik; 16px page gutter antar section; CTA `Daftar`/`Masuk` minimum 44px vertikal di header/footer; DEC-010 palette (`--nv-blue-50` surface, `--nv-blue-600` primary), DEC-019 typography (Plus Jakarta Sans, `display` 30/38 desktop, `h1` 30/38 mobile, weight 600); visual placeholder SVG abstrak (target + progres + koin) tanpa angka/data pengguna; footer minimal: wordmark + © 2026 NAVORA, tanpa link tambahan; tidak ada horizontal scroll.

---

## Wireframe-to-Component Map

| Wireframe | Components |
|---|---|
| WF-01, WF-11 | CMP-01..04, CMP-07..10, CMP-19, CMP-20 |
| WF-02 | CMP-01..05, CMP-09, CMP-11, CMP-15, CMP-16, CMP-20 |
| WF-03, WF-12 | CMP-01..05, CMP-07..09, CMP-11, CMP-14, CMP-20 |
| WF-04, WF-13 | CMP-01..05, CMP-14, CMP-15, CMP-20 |
| WF-05 | CMP-01, CMP-04..06, CMP-12, CMP-22 |
| WF-06 | CMP-06, CMP-13, CMP-17, CMP-19 |
| WF-07 | CMP-17, CMP-18 |
| WF-08..10, WF-16 | CMP-20 |
| WF-14 | CMP-24 |
| WF-15 | CMP-01..05, CMP-11, CMP-15, CMP-20 |
| WF-17 | CMP-01..06, CMP-22 |
| WF-18 | CMP-01, CMP-04, CMP-19, CMP-20 |
| WF-19 | CMP-01, CMP-04, CMP-19, CMP-20 |

---

## Approval Items

| Item | Reason |
|---|---|
| Summary figures on dashboard | Resolved for WF-01: 5 blocks in one band (D-04); `OPEN-06` scoped to WF-01 only |
| Archive/restore wording | Approved behavior (DEC-008); copy needs review |
| Negative and excess money display | `OPEN-16` resolved for WF-01 only: `-Rp2.000.000` format; DEC-025 unchanged |
| Mobile bottom-sheet modals | Interaction preference; dialog is the fallback |
| Search/sort/filter presence | `OPEN-05` |
| Dashboard widget set | D-04 resolves 5 blocks for WF-01 only; other wireframes remain `OPEN-06` |
| Mobile dashboard widget set | M-01..M-12 resolves all mobile dashboard behaviors: no demo data, Hampir illustration, drawer nav, primary action, status badge, Hampir rules, transaction list, UI states, responsive/a11y, goals navigation, negative balance |
