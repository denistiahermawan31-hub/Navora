# NAVORA — User Flows

| Field | Value |
|---|---|
| Document ID | NAVORA-UF |
| Version | 0.2.0 (Consolidated) |
| Date | 2026-11-22 |
| Status | **PROPOSED flows updated with approved decisions; remaining OPEN items marked** |
| Related | `FEATURE-SPECIFICATION.md`, `WIREFRAME-SPECIFICATION.md`, `BUSINESS-RULES.md` |

Each flow starts at a defined entry point and ends at a defined outcome. Error and alternative paths are included. Authentication is mandatory (DEC-003). Where a rule is still unresolved, the flow shows the decision point as `OPEN` instead of inventing behavior.

---

## UF-01 — First Visit

**Entry:** visitor opens application root URL `/` without active session; or registered user opens `/` with active session.
**Outcome:** guest sees Landing Page; authenticated user redirected to `/dashboard`.

```mermaid
flowchart TD
  A["Buka URL aplikasi"] --> B{"Sesi aktif?"}
  B -- "Ya" --> C["Arahkan ke /dashboard (303)"]
  B -- "Tidak" --> D["Tampilkan Landing Page"]
  D --> E["Pengunjung melihat informasi NAVORA"]
  E --> F["Pilih aksi: Masuk atau Daftar"]
  F --> G["Masuk → /masuk"]
  G --> H["Login berhasil → return path atau /dashboard"]
  F --> I["Daftar → /daftar"]
  I --> J["Daftar berhasil → cek email"]
  J --> K["Verifikasi email → /dashboard"]
```

---

## UF-02 — Registration and Email Verification

**Entry:** Landing Page CTA "Daftar" → `/daftar`; or authenticated user navigating to registration.
**Outcome:** verified account with an empty active goal list; after verification, user redirected to `/dashboard`.

```mermaid
flowchart TD
  A["Buka form pendaftaran"] --> B["Isi nama, email, kata sandi, konfirmasi"]
  B --> C["Kirim"]
  C --> D{"Validasi server lolos?"}
  D -- "Tidak" --> E["Tampilkan error per field dan ringkasan"]
  E --> B
  D -- "Ya" --> F{"Email sudah terdaftar?"}
  F -- "Ya" --> G["Pesan generik: email sudah terdaftar"]
  G --> B
  F -- "Tidak" --> H["Buat akun dengan kata sandi ter-hash"]
  H --> I["Kirim email verifikasi"]
  I --> J["Tampilkan halaman: periksa email Anda"]
  J --> K["Pengguna membuka tautan verifikasi"]
  K --> L{"Token valid dan belum kedaluwarsa?"}
  L -- "Tidak" --> M["Tawarkan kirim ulang verifikasi"]
  M --> J
  L -- "Ya" --> N["Tandai email terverifikasi"]
  N --> O["Arahkan ke dashboard dengan empty state"]
```

> Whether login is blocked before verification is `OPEN-08` (proposal: limited access until verified). Token lifetime is `OPEN-09` (proposal: 60 minutes).

---

## UF-03 — Login and Returning User

**Entry:** unauthenticated visit to a protected page.
**Outcome:** requested page or dashboard.

```mermaid
flowchart TD
  A["Minta halaman terproteksi"] --> B{"Sesi valid?"}
  B -- "Ya" --> C["Render halaman yang diminta"]
  B -- "Tidak" --> D["Arahkan ke halaman masuk dengan return path"]
  D --> E["Masukkan email dan kata sandi"]
  E --> F["Kirim"]
  F --> G{"Kredensial valid?"}
  G -- "Tidak" --> H["Pesan generik: email atau kata sandi salah"]
  H --> E
  G -- "Ya" --> I["Regenerasi session ID"]
  I --> J{"Return path aman dan internal?"}
  J -- "Ya" --> K["Arahkan ke halaman yang diminta"]
  J -- "Tidak" --> L["Arahkan ke dashboard"]
```

---

## UF-04 — Create Dream Goal

**Entry:** "Tambah Goal" from header, dashboard empty state, or goals list.
**Outcome:** goal created and its detail page displayed.

```mermaid
flowchart TD
  A["Klik Tambah Goal"] --> B["Form: nama, target harga, tanggal opsional, deskripsi opsional"]
  B --> C["Pengguna mengirim"]
  C --> D{"Nama valid dan unik dalam akun?"}
  D -- "Tidak" --> E["Error: nama wajib / sudah digunakan"]
  D -- "Ya" --> F{"Target harga bilangan bulat lebih dari nol?"}
  F -- "Tidak" --> G["Error: masukkan nominal yang valid"]
  F -- "Ya" --> H{"Tanggal valid, jika diisi?"}
  H -- "Tidak" --> I["Error: masukkan tanggal yang valid"]
  H -- "Ya" --> J["Simpan goal dengan saldo nol dan status Berlangsung"]
  E --> K["Render ulang form dengan nilai dipertahankan"]
  G --> K
  I --> K
  K --> C
  J --> L["Arahkan ke detail goal dengan pesan sukses"]
  L --> M["Tampilkan Rp0, progres 0%, sisa sama dengan target"]
```

**Alternative:** user cancels → return to previous page, nothing saved. A past target date is accepted (DEC-026).

---

## UF-05 — View Goal Detail

**Entry:** select a goal from the list, dashboard, or a direct link.
**Outcome:** goal information displayed with available actions.

```mermaid
flowchart TD
  A["Pilih goal"] --> B{"Goal ada dan milik pengguna?"}
  B -- "Tidak" --> C["Tampilkan 404"]
  B -- "Ya" --> D{"Goal diarsipkan?"}
  D -- "Ya" --> E["404 dari tampilan aktif; akses lewat halaman Arsip"]
  D -- "Tidak" --> F["Muat goal dan hitung saldo"]
  F --> G["Tampilkan saldo, target, sisa, progres, kelebihan, status, tanggal"]
  G --> H{"Ada transaksi?"}
  H -- "Ya" --> I["Tampilkan 5 transaksi terbaru dan tautan Riwayat"]
  H -- "Tidak" --> J["Empty state dengan aksi Tambah Tabungan"]
  I --> K["Pengguna memilih aksi"]
  J --> K
  K --> L["Tambah Tabungan: UF-06"]
  K --> M["Edit goal: UF-07"]
  K --> N["Arsipkan goal: UF-08"]
  K --> O["Lihat riwayat: UF-09"]
```

---

## UF-06 — Record Savings (Deposit or Withdrawal)

**Entry:** "Tambah Tabungan" on goal detail or dashboard activity.
**Outcome:** transaction saved, balance and status updated.

```mermaid
flowchart TD
  A["Klik Tambah Tabungan"] --> B["Form: jenis Setoran/Penarikan, nominal, tanggal default hari ini WIB, catatan opsional"]
  B --> C["Pengguna mengirim"]
  C --> D{"Nominal bilangan bulat lebih dari nol?"}
  D -- "Tidak" --> E["Error: masukkan nominal lebih dari nol"]
  D -- "Ya" --> F{"Tanggal valid dan tidak di masa depan?"}
  F -- "Tidak" --> G["Error: pilih tanggal yang valid"]
  F -- "Ya" --> H{"Goal diarsipkan?"}
  H -- "Ya" --> I["404 atau pesan: pulihkan goal terlebih dahulu"]
  H -- "Tidak" --> J["Simpan transaksi"]
  J --> K["Hitung ulang saldo dan status"]
  K --> L["Arahkan ke detail goal dengan pesan sukses"]
  L --> M["Tampilkan saldo, sisa, progres, dan kelebihan terbaru"]
  E --> N["Render ulang form dengan nilai dipertahankan"]
  G --> N
  N --> C
```

**Edge cases:**
- Withdrawal larger than the balance → accepted; balance becomes negative (DEC-025).
- Deposit that pushes balance above target → accepted; progress caps at 100% and excess is shown (DEC-023).
- Goal archived in another tab → 404 on submit, no orphan record.
- Double submit → single transaction (redirect-after-POST + request guard).

---

## UF-07 — Edit Dream Goal

**Entry:** goal detail → Edit.
**Outcome:** updated goal with recomputed balance, progress, and status.

```mermaid
flowchart TD
  A["Klik Edit"] --> B["Muat form dengan nilai saat ini"]
  B --> C["Pengguna mengubah field dan mengirim"]
  C --> D{"Validasi lolos, termasuk nama unik?"}
  D -- "Tidak" --> E["Tampilkan error, nilai dipertahankan"]
  E --> C
  D -- "Ya" --> F["Perbarui field goal saja"]
  F --> G["Jangan ubah transaksi"]
  G --> H["Hitung ulang progres, sisa, kelebihan, dan status"]
  H --> I{"Target baru di bawah saldo?"}
  I -- "Ya" --> J["Tampilkan 100%, sisa Rp0, dan kelebihan"]
  I -- "Tidak" --> K["Tampilkan progres terbaru"]
  J --> L["Arahkan dengan pesan sukses"]
  K --> L
```

---

## UF-08 — Archive Dream Goal

**Entry:** goal detail → "Arsipkan".
**Outcome:** goal archived with history retained, or action cancelled.

```mermaid
flowchart TD
  A["Klik Arsipkan"] --> B["Dialog konfirmasi menyebut nama goal"]
  B --> C{"Pengguna mengonfirmasi?"}
  C -- "Tidak" --> D["Tutup dialog, tidak ada perubahan, fokus kembali ke tombol"]
  C -- "Ya" --> E["Tandai goal sebagai diarsipkan"]
  E --> F["Pertahankan seluruh transaksi"]
  F --> G["Keluarkan dari daftar aktif, hitungan, dan total dashboard"]
  G --> H["Arahkan ke daftar goal dengan pesan sukses"]
  H --> I["Goal tersedia di halaman Arsip dan dapat dipulihkan"]
```

> Hard delete is not available (DEC-008).

---

## UF-09 — View Savings History & Paginate

**Entry:** goal detail → "Riwayat" or "Lihat semua".
**Outcome:** user reviews transactions across pages.

```mermaid
flowchart TD
  A["Buka riwayat tabungan goal"] --> B{"Goal ada, milik pengguna, dan tidak diarsipkan?"}
  B -- "Tidak" --> C["Tampilkan 404"]
  B -- "Ya" --> D{"Parameter halaman valid?"}
  D -- "Tidak" --> E["Fallback ke halaman 1"]
  D -- "Ya" --> F["Muat halaman transaksi (draf 20 baris, C-20 OPEN), urut tanggal menurun lalu id menurun"]
  E --> F
  F --> G{"Ada transaksi?"}
  G -- "Tidak" --> H["Empty state dengan aksi Tambah Tabungan"]
  G -- "Ya" --> I["Tampilkan tabel: jenis, nominal, tanggal, catatan"]
  I --> J{"Ada halaman lain?"}
  J -- "Ya" --> K["Tampilkan numbered pagination"]
  J -- "Tidak" --> L["Sembunyikan pagination"]
  K --> M["Pengguna memilih halaman"]
  M --> F
```

> Row-level edit/delete actions appear only if `OPEN-01`/`OPEN-02` are approved.

---

## UF-10 — Edit or Delete a Transaction (OPEN)

**Entry:** history row action (only if `OPEN-01`/`OPEN-02` are approved).
**Outcome:** corrected history with consistent balance.

```mermaid
flowchart TD
  A["Pilih aksi transaksi"] --> B{"Fitur disetujui?"}
  B -- "Tidak" --> C["Aksi tidak ditampilkan"]
  B -- "Ya" --> D{"Edit atau hapus?"}
  D -- "Edit" --> E["Form terisi, validasi, simpan"]
  D -- "Hapus" --> F["Dialog konfirmasi dengan jenis, nominal, dan tanggal"]
  F --> G{"Dikonfirmasi?"}
  G -- "Tidak" --> H["Batal, tidak ada perubahan"]
  G -- "Ya" --> I["Hapus transaksi"]
  E --> J["Hitung ulang saldo dan status"]
  I --> J
  J --> K{"Saldo sekarang di bawah target?"}
  K -- "Ya" --> L["Status kembali ke Berlangsung"]
  K -- "Tidak" --> M["Status tetap"]
  L --> N["Tampilkan riwayat dan saldo terbaru"]
  M --> N
```

---

## UF-11 — Automatic Status & Hampir Tercapai

**Entry:** a transaction or target edit changes the balance.
**Outcome:** status and indicator reflect the new balance.

```mermaid
flowchart TD
  A["Transaksi disimpan atau target diubah"] --> B["Hitung saldo terbaru"]
  B --> C{"Saldo >= target?"}
  C -- "Ya" --> D["Status Tercapai"]
  C -- "Tidak" --> E["Status Berlangsung"]
  D --> F["Progres 100%, sisa Rp0, kelebihan ditampilkan"]
  E --> G{"Progres >= 90%?"}
  G -- "Ya" --> H["Tampilkan indikator Hampir Tercapai"]
  G -- "Tidak" --> I["Tanpa indikator"]
  F --> J["Perbarui tampilan dan dashboard"]
  H --> J
  I --> J
```

> No manual completion action exists (DEC-006). A withdrawal below target returns the status to Berlangsung automatically.

---

## UF-12 — Logout

```mermaid
flowchart TD
  A["Klik Keluar"] --> B["Validasi CSRF"]
  B --> C["Hapus sesi server-side dan regenerasi ID"]
  C --> D["Arahkan ke halaman masuk"]
  D --> E["Tombol back tidak menampilkan konten terautentikasi"]
```

---

## UF-13 — Archive View & Restore

**Entry:** navigation → "Arsip".
**Outcome:** archived goal restored or reviewed.

```mermaid
flowchart TD
  A["Buka halaman Arsip"] --> B{"Ada goal diarsipkan?"}
  B -- "Tidak" --> C["Empty state: belum ada goal diarsipkan"]
  B -- "Ya" --> D["Tampilkan daftar goal diarsipkan dengan tanggal arsip dan saldo terakhir"]
  D --> E["Pengguna memilih Pulihkan"]
  E --> F["Konfirmasi ringan"]
  F --> G{"Dikonfirmasi?"}
  G -- "Tidak" --> D
  G -- "Ya" --> H["Hapus status arsip"]
  H --> I["Goal kembali ke daftar aktif dengan seluruh riwayat"]
  I --> J["Arahkan dengan pesan sukses"]
```

---

## UF-14 — Password Reset

**Entry:** login screen → "Lupa kata sandi".
**Outcome:** new password set and all sessions invalidated.

```mermaid
flowchart TD
  A["Buka form lupa kata sandi"] --> B["Masukkan email"]
  B --> C["Kirim"]
  C --> D["Selalu tampilkan pesan generik: jika email terdaftar, tautan dikirim"]
  D --> E["Pengguna membuka tautan dari email"]
  E --> F{"Token valid, belum kedaluwarsa, belum dipakai?"}
  F -- "Tidak" --> G["Tampilkan pesan token tidak valid dan opsi minta ulang"]
  F -- "Ya" --> H["Form kata sandi baru dan konfirmasi"]
  H --> I{"Validasi lolos?"}
  I -- "Tidak" --> H
  I -- "Ya" --> J["Perbarui hash kata sandi"]
  J --> K["Tandai token terpakai"]
  K --> L["Batalkan seluruh sesi lama"]
  L --> M["Arahkan ke halaman masuk dengan pesan sukses"]
```

---

## Flow-to-Requirement Traceability

| Flow | Requirements |
|---|---|
| UF-01 | FR-002, FR-015, FR-026, FR-028 |
| UF-02 | FR-001, FR-036 |
| UF-03 | FR-002, FR-003 |
| UF-04 | FR-005, FR-006, FR-030, FR-039, FR-044 |
| UF-05 | FR-009, FR-021, FR-038 |
| UF-06 | FR-016, FR-018..FR-025, FR-040, FR-041 |
| UF-07 | FR-010, FR-030 |
| UF-08 | FR-011, FR-045 |
| UF-09 | FR-021, FR-023 |
| UF-10 | FR-022, FR-023 |
| UF-11 | FR-024, FR-025, FR-041 |
| UF-12 | FR-002 |
| UF-13 | FR-038, FR-045 |
| UF-14 | FR-037 |

---

## Change History (0.2.0)

- Single-user branch removed; authentication is mandatory (DEC-003).
- Registration flow extended with email verification (DEC-015); password reset flow added.
- Delete goal flow replaced with archive and restore flows (DEC-008).
- Savings flow now includes deposit/withdrawal direction and negative balances (DEC-004, DEC-025).
- Completion flow is automatic and includes Hampir Tercapai (DEC-006, DEC-012).
- Currency references updated to IDR; dates use WIB.
