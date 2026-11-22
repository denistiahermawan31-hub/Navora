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

```text
┌───────────────┬──────────────────────────────────────────────────────────────┐
│ NAVORA        │  Dashboard                              [ + Tambah Goal ]   │
│               │  Ringkasan tabungan Anda                                     │
│ ▸ Dashboard   │                                                              │
│ ▸ Goals       │  ┌────────────────┬────────────────┬────────────────┐        │
│ ▸ Arsip       │  │ GOAL AKTIF     │ TOTAL TABUNGAN │ TOTAL SISA     │        │
│               │  │ 3              │ Rp12.500.000   │ Rp27.500.000   │        │
│               │  └────────────────┴────────────────┴────────────────┘        │
│               │                                                              │
│               │  Hampir tercapai                          ▸ Lihat semua goal│
│               │  ┌──────────────────────────────┐  ┌────────────────────────┐│
│               │  │ Yamaha Aerox      Hampir     │  │ Laptop                   ││
│               │  │ Rp12.500.000 / Rp30.000.000  │  │ Rp6.000.000 / Rp15.000.000│
│               │  │ ▓▓▓▓▓▓▓▓░░░░░░░░░░░░  42%    │  │ ▓▓▓▓▓▓░░░░░░░░░░░░░░  40%│
│               │  │ Sisa Rp17.500.000            │  │ Sisa Rp9.000.000         ││
│               │  └──────────────────────────────┘  └────────────────────────┘│
│               │                                                              │
│               │  Tabungan terbaru                         ▸ Lihat riwayat  │
│               │  ┌──────────────────────────────────────────────────────────┐│
│               │  │ 18 Nov 2026 · Yamaha Aerox · Setoran Rp1.500.000         ││
│               │  │ 15 Nov 2026 · Laptop       · Setoran   Rp750.000         ││
│               │  └──────────────────────────────────────────────────────────┘│
└───────────────┴──────────────────────────────────────────────────────────────┘
```

**Notes**
- Three summary figures share one band, separated by hairlines — not three identical cards.
- Cards show only real data. No sparkline, no fake trend arrow.
- Archived goals are excluded from all figures.
- Empty state replaces the card sections with a single centered block (WF-08).

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
- Numbered pagination, 20 goals per page (DEC-009).
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

**Rules:** 20 rows per page; numbered pagination (DEC-009); amounts right-aligned; empty state WF-10.

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

```text
┌─────────────────────────────────┐
│ ☰  NAVORA              [ + ]    │
├─────────────────────────────────┤
│ Dashboard                       │
│ Ringkasan tabungan Anda         │
│                                 │
│ ┌─────────────────────────────┐ │
│ │ GOAL AKTIF                  │ │
│ │ 3                           │ │
│ ├─────────────────────────────┤ │
│ │ TOTAL TABUNGAN              │ │
│ │ Rp12.500.000                │ │
│ ├─────────────────────────────┤ │
│ │ TOTAL SISA                  │ │
│ │ Rp27.500.000                │ │
│ └─────────────────────────────┘ │
│                                 │
│ Hampir tercapai                 │
│ ┌─────────────────────────────┐ │
│ │ Yamaha Aerox                │ │
│ │ Rp12.500.000 / Rp30.000.000 │ │
│ │ ▓▓▓▓▓▓▓░░░░░░░  42%         │ │
│ │ Sisa Rp17.500.000           │ │
│ └─────────────────────────────┘ │
│                                 │
│ Tabungan terbaru                │
│ …                               │
└─────────────────────────────────┘
```

**Rules:** 16px page gutter; cards full width; summary stacks vertically; primary action in the header remains reachable with a 44×44 target.

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

---

## Approval Items

| Item | Reason |
|---|---|
| Summary figures on dashboard | Exact widget set is `OPEN-06` |
| Archive/restore wording | Approved behavior (DEC-008); copy needs review |
| Negative and excess money display | Format details `OPEN-16`; excess approved (DEC-023) |
| Mobile bottom-sheet modals | Interaction preference; dialog is the fallback |
| Search/sort/filter presence | `OPEN-05` |
