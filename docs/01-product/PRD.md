# NAVORA — Product Requirements Document (PRD)

| Field | Value |
|---|---|
| Document ID | NAVORA-PRD |
| Version | 0.2.0 (Consolidated) |
| Date | 2026-11-22 |
| Status | **Owner-approved product decisions applied; technical items remain OPEN** |
| Source basis | `SRC-00` project brief; `SRC-01` repository audit; owner decisions 2026-11-22 (`DECISIONS.md` §1) |
| Related | `BRD.md`, `SRS.md`, `BUSINESS-RULES.md`, `FEATURE-SPECIFICATION.md`, `docs/05-quality/ACCEPTANCE-CRITERIA.md` |

## Status Legend

| Label | Meaning |
|---|---|
| `APPROVED` | Explicitly confirmed by the project owner. Binding. |
| `PROPOSED` | Drafted here; needs owner approval. |
| `OPEN` / `TBD` | Unresolved; blocks the dependent work (see `DECISIONS.md` §4). |
| `SUPERSEDED` | Withdrawn by a later approved decision; kept for traceability. |
| `FUTURE` | Deliberately deferred beyond MVP. |
| `OUT` | Explicitly out of scope. |

> **Source-of-truth note:** the owner-approved decisions in `DECISIONS.md` §1 are the latest source of truth. Where this document previously conflicted with them, the approved decision wins and the change is recorded in `CHANGELOG.md`.
>
> **Technical note:** approval of product behavior does **not** approve technical implementation choices. PHP version, database engine, money column type, status storage, timestamp storage, email delivery, session storage, migration tooling, hosting, and test tooling remain `OPEN` (`DECISIONS.md` §4.2).

---

## 1. Product Overview

NAVORA is a web application for personal savings planning. A registered user defines **dream goals** (things they want to buy), assigns each a **target price**, optionally a **target date**, and records **savings transactions** over time. Each transaction is either a **deposit (Setoran)** or a **withdrawal (Penarikan)**. NAVORA computes the saved balance, progress, and remaining amount, and keeps a reviewable history.

Each user has a private account and dashboard. Data is isolated per user.

**Reference scenario (from the brief):** a user wants a Yamaha Aerox motorcycle. They create a goal "Yamaha Aerox", set a target price of `Rp30.000.000`, optionally a target date, record savings, and watch progress toward the target. The item, brand, and price are illustrative — the product is not motorcycle-specific.

**Approved financial example:** target `Rp30.000.000`; deposits `Rp5.000.000` + `Rp2.500.000`; withdrawal `Rp0` → balance `Rp7.500.000`; remaining `Rp22.500.000`; progress `25,0%`.

---

## 2. Product Goals

| ID | Goal | Status |
|---|---|---|
| PG-01 | Let a user express a purchase intention as a structured goal | `APPROVED` (concept, `SRC-00`) |
| PG-02 | Make savings progress immediately understandable | `APPROVED` (concept, `SRC-00`) |
| PG-03 | Keep an accurate, reviewable savings history | `APPROVED` (concept, `SRC-00`) |
| PG-04 | Keep daily interaction fast: few fields, clear feedback | `PROPOSED` |
| PG-05 | Deliver a calm, trustworthy, blue-themed interface | `PROPOSED` (palette `DEC-010` approved; implementation not verified) |
| PG-06 | Remain usable on mobile and desktop | `PROPOSED` |

---

## 3. Personas

| Persona | Context | Goals | Frustrations | Status |
|---|---|---|---|---|
| **Rafi — Planned Buyer** | Saving for one significant purchase over several months | Know exactly how much is left; stay motivated | Loses track of what was set aside; doubts his own math | `PROPOSED` |
| **Dina — Multi-Goal Saver** | Saving for a laptop and a trip at the same time | See all goals and compare progress | Mixes up which money belongs to which goal | `PROPOSED` |
| **Bima — Record Keeper** | Likes a written trail of contributions | Review history by date; correct mistakes | Paper notes get lost; totals drift | `PROPOSED` |

Personas are illustrative and require validation. No demographic data is asserted.

---

## 4. Functional Requirements

Priority uses MoSCoW. `Must` items form the MVP. A requirement is only buildable when its status is `APPROVED` and no blocking `OPEN` item applies. `Must` is a priority label, **not** owner approval.

**Must requirements still awaiting owner approval**

| ID | Origin / evidence | Why it remains pending |
|---|---|---|
| FR-026 | Dashboard metric definitions in `PRD.md` §4.4, `SRS.md` §4.4, `FS-09`; definitions reference DEC-012/DEC-025/RULE-021 | Core metrics are described, but the exact widget set is `OPEN-06`; no owner approval of the final set is recorded. |
| FR-028 | Persistent navigation to Dashboard, Goals, and Archive; `US-27` | Navigation items are a proposed structure; Settings is deferred (`OPEN-07`) and no owner approval of this exact navigation set is recorded. |
| FR-034 | Loading, empty, and error states; `US-31`; `RULE-044` covers only empty history | General UI-state expectations exist in project rules, but no owner decision approves this requirement as written. |

These three requirements remain visibly `PROPOSED`. The requirements package is therefore **not ready for final sign-off**; their priority does not imply approval.

### 4.1 Account & Access

| ID | Requirement | Priority | Status | Notes |
|---|---|---|---|---|
| FR-001 | A user can register an account with name, email, and password | Must | `APPROVED` | DEC-003 |
| FR-002 | A user can log in and log out | Must | `APPROVED` | DEC-003 |
| FR-003 | Each user can only access their own goals and transactions | Must | `APPROVED` | DEC-003; unauthorized access returns 404 |
| FR-004 | ~~The application is usable without an account (single-user mode)~~ | — | `SUPERSEDED` | Withdrawn by DEC-003 |
| FR-036 | A newly registered user must verify their email address before full access | Must | `APPROVED` (behavior) / `OPEN-08` (enforcement point) | DEC-015 |
| FR-037 | A user can request a password reset link by email and set a new password | Must | `APPROVED` (behavior) / `OPEN-09` (token lifetime) | DEC-015 |

### 4.2 Dream Goals

| ID | Requirement | Priority | Status | Notes |
|---|---|---|---|---|
| FR-005 | Create a dream goal with a required name and required target price | Must | `APPROVED` (concept, `SRC-00`) | |
| FR-006 | Optionally set a target date on a goal | Must | `APPROVED` | Brief: "if needed"; DEC-026 |
| FR-007 | Optionally add a short description/note to a goal | Could | `OPEN-03` | Not stated in brief |
| FR-008 | View a list of all goals with name, target, saved, and progress | Must | `APPROVED` (concept, `SRC-00`) | |
| FR-009 | Open a goal detail view with full information and actions | Must | `APPROVED` | |
| FR-010 | Edit a goal's name, target price, target date, and description | Must | `APPROVED` | Brief: "Update… goals" |
| FR-011 | Archive a goal after explicit confirmation; history is retained | Must | `APPROVED` | DEC-008. "Delete" is replaced by "Arsipkan" |
| FR-012 | Sort goals by recently updated, target date, or progress | Should | `OPEN-05` | |
| FR-013 | Search goals by name | Should | `OPEN-05` | Relevant once a user has many goals |
| FR-014 | Filter **active** goals by status (Berlangsung / Tercapai) | Could | `OPEN-05` | Depends on FR-024; archived goals belong to the separate archive view (FR-038/FR-045), not to a status value (RULE-029) |
| FR-015 | See an empty state with a clear call to action when no goals exist | Must | `APPROVED` | |
| FR-039 | Goal names are unique within one account; different accounts may reuse a name | Must | `APPROVED` (behavior) / `OPEN-14` (case rules) | DEC-011 |
| FR-044 | A target date in the past is accepted when creating or editing a goal | Must | `APPROVED` | DEC-026 |

### 4.3 Savings & Progress

| ID | Requirement | Priority | Status | Notes |
|---|---|---|---|---|
| FR-016 | Record a savings transaction against a goal with a direction (Setoran / Penarikan), amount, and date | Must | `APPROVED` | DEC-004 |
| FR-017 | Optionally attach a short note to a transaction | Could | `OPEN-04` | Not stated in brief |
| FR-018 | Display current saved balance as total deposits minus total withdrawals | Must | `APPROVED` | DEC-004, DEC-005, DEC-025 |
| FR-019 | Display progress as a percentage of target, capped at 100% for display | Must | `APPROVED` | DEC-023 |
| FR-020 | Display the remaining amount to reach the target, floored at Rp0 | Must | `APPROVED` | DEC-023 |
| FR-021 | Display savings history per goal, newest first, with numbered pagination | Must | `APPROVED` | DEC-009 |
| FR-022 | Edit an existing savings transaction | Could | `OPEN-01` | Deferred unless approved |
| FR-023 | Delete a savings transaction after confirmation | Should | `OPEN-02` | Deferred unless approved |
| FR-024 | Goal status follows the current balance automatically: at/above target = **Tercapai**, below target = **Berlangsung** | Must | `APPROVED` | DEC-006 |
| FR-025 | Allow saving beyond the target price; show the excess separately | Must | `APPROVED` | DEC-023 |
| FR-040 | A negative balance (withdrawals exceed deposits) is allowed and displayed clearly | Must | `APPROVED` (behavior) / `OPEN-16` (format) | DEC-025 |
| FR-041 | Show a **Hampir Tercapai** indicator when `90% <= progress < 100%` | Must | `APPROVED` | DEC-012 |

### 4.4 Dashboard & Navigation

| ID | Requirement | Priority | Status | Notes |
|---|---|---|---|---|
| FR-026 | Dashboard shows active-goal count, total saved, total remaining, nearly-reached goals, and recent activity | Must | `PROPOSED` (widget set) | Core metrics approved in principle; exact set `OPEN-06` |
| FR-027 | Dashboard lists recent savings activity | Should | `PROPOSED` | Part of `OPEN-06` |
| FR-028 | Persistent navigation to Dashboard, Goals, and Archive | Must | `PROPOSED` | Settings is deferred (`OPEN-07`) |
| FR-029 | Global "Tambah Goal" and per-goal "Tambah Tabungan" actions are reachable in one step from their context | Should | `PROPOSED` | |

**Dashboard metric definitions (draft — `OPEN-06`):** active-goal count excludes archived goals (FR-045); total saved is the sum of per-goal balances and may be negative (DEC-025); total remaining is the sum of each active goal's `max(0, target - balance)` (RULE-021); nearly reached is the set of active goals with `90% <= progress < 100%` (DEC-012); recent activity is at most 5 newest transactions. These definitions align PRD FR-026, SRS §4.4, and FS-09; the exact widget set is not approved.

### 4.5 Archive & Restore

| ID | Requirement | Priority | Status | Notes |
|---|---|---|---|---|
| FR-038 | A user can view archived goals and restore them; restored goals return to the active list | Must | `APPROVED` | DEC-008 |
| FR-045 | Archived goals are excluded from active lists, active counts, and dashboard totals | Must | `APPROVED` | DEC-008 |
| FR-046 | Public Landing Page: guest access to `/` without authentication; displays informational content, CTA Masuk/Daftar; authenticated users redirected to `/dashboard` | Must | `APPROVED` | DEC-027 |

### 4.6 System Feedback & Data

| ID | Requirement | Priority | Status | Notes |
|---|---|---|---|---|
| FR-030 | Validate all inputs server-side and show field-level errors | Must | `APPROVED` (policy) | |
| FR-031 | Show success feedback after create/update/archive/restore actions | Must | `APPROVED` (policy) | |
| FR-032 | Show a safe error page/message when an operation fails | Must | `APPROVED` (policy) | No stack traces to users |
| FR-033 | Format money as Indonesian Rupiah: `Rp` + dot thousands separator, no decimals | Must | `APPROVED` | DEC-007 |
| FR-034 | Loading, empty, and error states are visually distinct | Must | `PROPOSED` | No owner decision record; state policy only |
| FR-035 | Data persists across sessions | Must | `APPROVED` (concept) | Database-backed |
| FR-042 | The entire user interface uses Bahasa Indonesia | Must | `APPROVED` | DEC-013 |
| FR-043 | Dates are interpreted and displayed in Asia/Jakarta (WIB) | Must | `APPROVED` (zone) / `OPEN` (display format) | DEC-014 |

### 4.7 Explicitly Out of Scope

| ID | Excluded capability | Basis |
|---|---|---|
| OUT-01 | Rental, borrowing, tailoring, inventory, or POS features | `SRC-00` |
| OUT-02 | Bank/payment integration, automated transfers | `SRC-00` |
| OUT-03 | Investment, interest, or credit calculation | `SRC-00` |
| OUT-04 | Shared/household goals and social features | Not in brief |
| OUT-05 | Habit tracking, tasks, notes, focus timers, planner | DEC-016 |
| OUT-06 | Standalone analytics/reporting suite | DEC-016, DEC-024 |
| OUT-07 | Native mobile applications | Not in brief |
| OUT-08 | Multi-currency wallets and exchange rates | DEC-007 |
| OUT-09 | Notifications/reminders, report export, advanced statistics | DEC-024 (deferred) |
| OUT-10 | Administrator role / back-office | No scope approved |
| OUT-11 | Goal cover images | DEC-024 (deferred) |
| OUT-12 | Full dashboard or goal CRUD on Public Landing Page | DEC-027: landing page is informational only |

---

## 5. Non-Functional Requirements

| ID | Category | Requirement | Target | Status |
|---|---|---|---|---|
| NFR-001 | Performance | Server-rendered page response for a personal dataset | < 500 ms server time; < 2.5 s LCP on a mid-range phone over 4G | `PROPOSED` |
| NFR-002 | Performance | Transaction list query uses an index on `(goal_id, occurred_on, id)` | Query plan verified | `PROPOSED` |
| NFR-003 | Security | Passwords stored with `password_hash()` (Argon2id preferred, bcrypt acceptable) | No plaintext or reversible storage | `PROPOSED` |
| NFR-004 | Security | All SQL uses prepared statements | Zero string-concatenated queries | `PROPOSED` |
| NFR-005 | Security | Output escaped by default in views | No unescaped user content | `PROPOSED` |
| NFR-006 | Security | State-changing requests protected by CSRF tokens | All POST requests | `PROPOSED` |
| NFR-007 | Accessibility | WCAG 2.1 AA for core flows | Keyboard, contrast, labels, focus | `PROPOSED` |
| NFR-008 | Responsiveness | Usable from 360 px to 1920 px | No horizontal scroll; all actions reachable | `PROPOSED` |
| NFR-009 | Compatibility | Current versions of Chrome, Edge, Firefox, Safari | Manual smoke test | `PROPOSED` |
| NFR-010 | Maintainability | Shared layout, components, and design tokens | No duplicated page shells | `PROPOSED` |
| NFR-011 | Privacy | No third-party analytics or tracking by default | No external trackers | `PROPOSED` |
| NFR-012 | Motion | Honor `prefers-reduced-motion`; no layout-shifting animation | Verified | `PROPOSED` |
| NFR-013 | Reliability | Balance always equals deposits minus withdrawals | 100% in tests | `PROPOSED` |
| NFR-014 | Localization | Interface copy in Bahasa Indonesia; money and dates use Indonesian conventions | DEC-013, DEC-014 | `APPROVED` (language) / `PROPOSED` (format details) |
| NFR-015 | Security | Verification and reset links are delivered by email and are time-limited (DEC-015); token generation, exact lifetime, and single-use enforcement are `OPEN-09` | Lifetime and single-use `OPEN-09` | `APPROVED` (delivery/time-limited behavior) / `OPEN` (token rules) |

---

## 6. User Stories (MVP)

| ID | Story | Covers |
|---|---|---|
| US-01 | Sebagai penabung, saya ingin membuat goal dengan nama dan target harga agar saya tahu apa yang sedang saya tabung. | FR-005 |
| US-02 | Sebagai penabung, saya ingin mencatat setoran agar progres saya sesuai kenyataan. | FR-016 |
| US-03 | Sebagai penabung, saya ingin mencatat penarikan agar saldo mencerminkan uang yang benar-benar keluar. | FR-016, FR-040 |
| US-04 | Sebagai penabung, saya ingin melihat berapa yang sudah terkumpul dan berapa sisa target agar saya tahu posisi saya. | FR-018, FR-020 |
| US-05 | Sebagai penabung, saya ingin meninjau riwayat tabungan agar saya bisa memverifikasi catatan saya. | FR-021 |
| US-06 | Sebagai penabung, saya ingin memperbaiki detail goal agar catatan tetap akurat. | FR-010 |
| US-07 | Sebagai penabung, saya ingin mengarsipkan goal yang tidak lagi saya kejar, dengan konfirmasi, agar saya tidak menghapusnya tanpa sengaja. | FR-011 |
| US-08 | Sebagai penabung, saya ingin melihat dan memulihkan goal yang diarsipkan agar riwayatnya tidak hilang. | FR-038, FR-045 |
| US-09 | Sebagai penabung dengan banyak goal, saya ingin ringkasan semua goal agar bisa membandingkan progres. | FR-008, FR-026 |
| US-10 | Sebagai pengguna baru, saya ingin titik awal yang jelas ketika belum punya goal. | FR-015 |
| US-11 | Sebagai pengguna, saya ingin pesan kesalahan yang jelas saat data tidak valid agar bisa memperbaikinya. | FR-030 |
| US-12 | Sebagai pengguna ponsel, saya ingin mencatat tabungan dengan nyaman di ponsel. | NFR-008 |
| US-13 | Sebagai pengguna baru, saya ingin mendaftar akun agar data tabungan saya pribadi. | FR-001 |
| US-14 | Sebagai pengguna, saya ingin memverifikasi email agar akun saya aman. | FR-036 |
| US-15 | Sebagai pengguna, saya ingin mengatur ulang kata sandi lewat email agar tidak kehilangan akses. | FR-037 |
| US-16 | Sebagai pengguna, saya ingin nama goal unik dalam akun saya agar tidak tertukar. | FR-039 |
| US-17 | Sebagai penabung, saya ingin melihat kelebihan tabungan di atas target agar saldo sebenarnya tetap terlihat. | FR-025 |
| US-18 | Sebagai penabung, saya ingin penanda "Hampir Tercapai" agar termotivasi menyelesaikan target. | FR-041 |
| US-19 | Sebagai pengguna Indonesia, saya ingin seluruh antarmuka berbahasa Indonesia agar mudah dipahami. | FR-042 |
| US-20 | Sebagai pengguna, saya ingin tanggal mengikuti WIB agar pencatatan konsisten. | FR-043 |
| US-21 | Sebagai pengguna, saya ingin masuk dan keluar akun agar data saya tetap pribadi. | FR-002 |
| US-22 | Sebagai pengguna, saya ingin hanya melihat goal dan transaksi saya sendiri agar data orang lain tidak terbaca. | FR-003 |
| US-23 | Sebagai penabung, saya ingin boleh menentukan tanggal target agar rencana saya lebih jelas. | FR-006 |
| US-24 | Sebagai penabung, saya ingin membuka detail goal untuk melihat saldo, progres, sisa, dan aksi yang tersedia. | FR-009 |
| US-25 | Sebagai penabung, saya ingin melihat persentase progres yang dibatasi maksimal 100% agar kelebihan tabungan tidak menyesatkan. | FR-019 |
| US-26 | Sebagai penabung, saya ingin status berubah otomatis saat saldo mencapai atau turun di bawah target. | FR-024 |
| US-27 | Sebagai pengguna, saya ingin navigasi tetap ke Dashboard, Goals, dan Arsip agar saya tidak tersesat. | FR-028 |
| US-28 | Sebagai pengguna, saya ingin aksi utama mudah dijangkau dari konteksnya agar pencatatan cepat. | FR-029 |
| US-29 | Sebagai pengguna, saya ingin umpan balik sukses dan pesan kesalahan yang aman agar saya tahu hasil aksi saya. | FR-031, FR-032 |
| US-30 | Sebagai pengguna Indonesia, saya ingin nominal ditampilkan sebagai Rupiah tanpa desimal agar mudah dibaca. | FR-033 |
| US-31 | Sebagai pengguna, saya ingin kondisi memuat, kosong, dan gagal dibedakan secara visual agar saya tahu apa yang terjadi. | FR-034 |
| US-32 | Sebagai pengguna, saya ingin data tabungan tersimpan antar-sesi agar catatan saya tidak hilang. | FR-035 |
| US-33 | Sebagai penabung, saya ingin tetap bisa memasukkan tanggal target yang sudah lalu agar rencana lama dapat dicatat. | FR-044 |
| US-34 | Sebagai penabung, saya ingin melihat aktivitas tabungan terbaru di ringkasan agar perubahan terakhir terlihat. | FR-027 |

All `Must` requirements have at least one user story. `Should`/`Could` requirements that remain `OPEN` (`OPEN-01..OPEN-05`) deliberately have no MVP story until approved. Acceptance criteria for each story: `docs/05-quality/ACCEPTANCE-CRITERIA.md`.

---

## 7. MVP Scope

### Included (MVP Scope A — DEC-024)
- Multi-user accounts: register, login, logout, email verification, password reset.
- Goal create, list, detail, edit, archive, restore.
- Savings transactions: deposits and withdrawals, listing with numbered pagination.
- Derived balance, progress percentage (capped), remaining amount, excess amount, negative balance.
- Automatic status (Berlangsung / Tercapai) and the Hampir Tercapai indicator.
- Dashboard summary with a bounded set of widgets.
- Unique goal names per account.
- IDR formatting and Bahasa Indonesia UI with WIB dates.
- Validation, feedback, empty/error states.
- Responsive blue design system.
- Public Landing Page (`FR-046`) — guest access without auth; CTA Masuk/Daftar; authenticated redirect to `/dashboard`.

### Excluded from MVP
Everything in §4.7, plus: reminders, recurring deposits, charts beyond a progress indicator, exports, goal images, sharing, multi-currency, dark mode (`FUTURE`), transaction editing/deleting (OPEN-01/OPEN-02), settings page (OPEN-07).

### MVP exit condition
All `Must` requirements implemented and verified against acceptance criteria, with no open `OPEN` item that affects financial correctness. `OPEN-01`/`OPEN-02` (transaction edit/delete) do not block exit because they are outside MVP scope.

---

## 8. Feature Priority Matrix

| Priority | Features |
|---|---|
| **Must** | FR-001, FR-002, FR-003, FR-005, FR-006, FR-008, FR-009, FR-010, FR-011, FR-015, FR-016, FR-018, FR-019, FR-020, FR-021, FR-024, FR-025, FR-026, FR-028, FR-030, FR-031, FR-032, FR-033, FR-034, FR-035, FR-036, FR-037, FR-038, FR-039, FR-040, FR-041, FR-042, FR-043, FR-044, FR-045 |
| **Should** | FR-012, FR-013, FR-023, FR-027, FR-029 |
| **Could** | FR-007, FR-014, FR-017, FR-022 |
| **Won't (MVP)** | OUT-01 … OUT-11, all `FUTURE` items |

---

## 9. Assumptions

| ID | Assumption | Validation |
|---|---|---|
| A-01 | One user manages only their own goals; data is isolated per account | `APPROVED` (DEC-003) |
| A-02 | Savings are entered manually | `SRC-00` implies manual entry |
| A-03 | A goal has exactly one target price and the single currency IDR | `APPROVED` (DEC-007) |
| A-04 | Transaction amounts are positive; direction determines the sign | `APPROVED` (DEC-004) |
| A-05 | Dataset size per user is small (tens of goals, hundreds of transactions) | `PROPOSED`; affects pagination |
| A-06 | Interface copy is Bahasa Indonesia | `APPROVED` (DEC-013) |

---

## 10. Dependencies

| ID | Dependency | Status |
|---|---|---|
| D-01 | Confirmed technology stack (PHP version, DB engine) | `OPEN` (TECH-01, TECH-02) |
| D-02 | Approved design tokens | `OPEN` (DEC-019 only; DEC-010 palette approved) |
| D-03 | Email delivery mechanism for verification/reset | `OPEN` (TECH-06) |
| D-04 | Money column type and maximum amount | `OPEN` (TECH-03) |
| D-05 | Hosting target and document root | `OPEN` (TECH-09) |

---

## 11. Future Feature Candidates

These are **not** approved and must not be built opportunistically.

| ID | Candidate | Value | Prerequisite |
|---|---|---|---|
| FR-F01 | Transaction editing | Correct mistakes | OPEN-01 |
| FR-F02 | Transaction deletion | Correct mistakes | OPEN-02 |
| FR-F03 | Recurring savings schedule | Encourages consistency | Rules for missed periods |
| FR-F04 | Reminders/notifications | Sustains engagement | DEC-024 (deferred) |
| FR-F05 | Savings chart over time | Motivation and insight | DEC-024 (advanced statistics deferred) |
| FR-F06 | Data export (CSV/PDF) | Portability and backup | DEC-024 (deferred) |
| FR-F07 | Goal cover images | Personalization | Upload security review |
| FR-F08 | Shared goals | Couples/families | New ownership model |
| FR-F09 | Multi-currency | Travel/international goals | Reversal of DEC-007 |
| FR-F10 | Dark mode | Comfort | Token architecture |
| FR-F11 | Settings page | Preferences | OPEN-07 |
| FR-F12 | Overdue indicator for past target dates | Awareness | OPEN-13 |

---

## 12. Open Questions (post-consolidation)

The original questions Q-01..Q-08 are **resolved** and kept here for traceability. Remaining open items live in `DECISIONS.md` §4.

| ID | Question | Resolution | Status |
|---|---|---|---|
| Q-01 | Is authentication required? | DEC-003: multi-user | `RESOLVED` |
| Q-02 | Which currency, and is it fixed or per goal? | DEC-007: single IDR, no decimals | `RESOLVED` |
| Q-03 | Are withdrawals allowed? | DEC-004 / DEC-025: yes, may exceed balance | `RESOLVED` |
| Q-04 | How is a goal completed? | DEC-006: automatic from balance | `RESOLVED` |
| Q-05 | What happens to transactions when a goal is deleted? | DEC-008: archive, history retained | `RESOLVED` |
| Q-06 | Are goal images in scope? | No — deferred (DEC-024) | `RESOLVED` |
| Q-07 | Is a target date required or optional? | Optional; past dates allowed (DEC-026) | `RESOLVED` |
| Q-08 | Should goals be name-unique per user? | DEC-011: unique per account | `RESOLVED` |

Remaining `OPEN` items: OPEN-01 … OPEN-20 (`DECISIONS.md` §4.1) and TECH-01 … TECH-12 (`DECISIONS.md` §4.2).

---

## 13. Traceability

| Requirement group | Brief / decision | Repository evidence |
|---|---|---|
| FR-001..FR-003, FR-036, FR-037 | DEC-003, DEC-015 | `AuthController.php`, `User.php`, auth views |
| FR-005..FR-015, FR-039, FR-044 | `SRC-00`; DEC-011, DEC-026 | `GoalController.php`, `views/goals/index.php` |
| FR-016..FR-025, FR-040, FR-041 | `SRC-00`; DEC-004, DEC-006, DEC-023, DEC-025 | `SavingsController.php`, `SavingsTransaction.php` |
| FR-026..FR-029 | `SRC-00` "monitor progress" | `DashboardController.php`, `views/dashboard/index.php` |
| FR-038, FR-045 | DEC-008 | Archive view (to be created) |
| FR-042, FR-043 | DEC-013, DEC-014 | All views |
| Out-of-scope modules | Not in brief; DEC-016 | Habits/tasks/notes/focus/planner/analytics files (untouched) |

---

## 14. Revision Notes (0.2.0)

- Applied DEC-003 (multi-user), DEC-004/DEC-025 (deposits, withdrawals, negative balance), DEC-006 (automatic status), DEC-007 (IDR), DEC-008 (archive/restore), DEC-009 (numbered pagination), DEC-011 (unique names), DEC-012 (Hampir Tercapai), DEC-013 (Bahasa Indonesia), DEC-014 (WIB), DEC-015 (email verification/reset), DEC-016 (legacy modules out), DEC-023 (over-saving), DEC-024 (MVP Scope A), DEC-026 (past target dates).
- FR-004 marked `SUPERSEDED`; single-user mode withdrawn.
- Added FR-036..FR-045 for account security, archive/restore, unique names, negative balance, nearly-reached indicator, Indonesian UI, WIB, and past target dates.
- Replaced "delete goal" with "archive goal" throughout.
- Removed Settings from MVP navigation (OPEN-07).
- Consolidation follow-up: FR-014 status filter limited to active goals; FR-026 metric definitions aligned with SRS §4.4 and FS-09; NFR-015 distinguishes approved time-limited email links from OPEN-09 token parameters; added US-21..US-34 to close Must-requirement story gaps.
- Page size and invalid-page fallback remain implementation parameters (`C-20`, `C-19` OPEN); `DEC-009` approves numbered pagination only.
