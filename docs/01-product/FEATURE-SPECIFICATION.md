# NAVORA — Feature Specification

| Field | Value |
|---|---|
| Document ID | NAVORA-FS |
| Version | 0.2.0 (Consolidated) |
| Date | 2026-11-22 |
| Status | **Owner-approved product decisions applied; feature-level OPEN items listed** |
| Related | `PRD.md`, `SRS.md`, `BUSINESS-RULES.md`, `COMPONENT-SPECIFICATION.md`, `DECISIONS.md` |

Each feature below lists purpose, actor, preconditions, main flow, alternatives, validation, states, and open questions. Features not listed here are not approved.

---

## Feature Index

| ID | Feature | Priority | Status |
|---|---|---|---|
| FS-01 | Create dream goal | Must | `APPROVED` (concept) / `PROPOSED` (details) |
| FS-02 | Goal list & search | Must (list) / Should (search) | `APPROVED` (list) / `OPEN-05` (search) |
| FS-03 | Goal detail | Must | `APPROVED` |
| FS-04 | Edit dream goal | Must | `APPROVED` |
| FS-05 | Archive dream goal | Must | `APPROVED` (DEC-008) |
| FS-06 | Record savings (deposit or withdrawal) | Must | `APPROVED` (DEC-004) |
| FS-07 | Savings history | Must | `APPROVED` (DEC-009) |
| FS-08 | Edit/delete savings transaction | Should/Could | `OPEN-01`, `OPEN-02` |
| FS-09 | Dashboard summary | Must | `PROPOSED` (widget set `OPEN-06`) |
| FS-10 | Goal status & completion | Must | `APPROVED` (DEC-006) |
| FS-11 | Authentication | Must | `APPROVED` (DEC-003) |
| FS-12 | Email verification | Must | `APPROVED` (DEC-015) |
| FS-13 | Password reset | Must | `APPROVED` (DEC-015) |
| FS-14 | Archive view & restore | Must | `APPROVED` (DEC-008) |
| FS-15 | Hampir Tercapai indicator | Must | `APPROVED` (DEC-012) |

---

## FS-01 — Create Dream Goal

- **Purpose:** Turn a purchase intention into a trackable goal.
- **Actor:** Registered, logged-in user.
- **Entry points:** "Tambah Goal" button in header, dashboard empty state, goals list empty state.
- **Preconditions:** Authenticated session; email verified (enforcement `OPEN-08`).
- **Main flow:**
  1. System presents the goal form: name (required), target price (required), optional target date, optional description.
  2. User enters values and submits.
  3. System validates server-side (including name uniqueness within the account).
  4. System persists the goal with zero balance and derived status `Berlangsung`.
  5. System redirects to the goal detail view with a success message.
- **Alternative flows:**
  - Validation fails → form re-rendered with field errors and preserved values.
  - Duplicate name in the same account → field error explaining that the name is already used.
  - User cancels → returns to previous page; nothing saved.
  - Duplicate submission → single record (redirect-after-POST).
- **Validation:** RULE-001..RULE-009, RULE-044.
- **States:** default, submitting (disabled control + inline progress), error summary.
- **Open questions:** maximum target amount (`TECH-03`), whether description ships (`OPEN-03`), case rules for uniqueness (`OPEN-14`).

---

## FS-02 — Goal List & Search

- **Purpose:** See all active goals and their progress at a glance.
- **Main flow:**
  1. System loads the user's non-archived goals with aggregated balance (deposits minus withdrawals).
  2. System displays cards/rows: name, target, balance, progress, remaining, excess (if any), optional target date, status (`Berlangsung` / `Tercapai`), Hampir Tercapai indicator.
  3. Numbered pagination, 20 goals per page (DEC-009).
  4. Selecting a goal opens FS-03.
- **Alternative flows:**
  - No goals → empty state with "Buat goal pertama".
  - Search returns nothing → "Tidak ada goal yang cocok" with a clear-search action.
  - Invalid page → page 1 or last valid page.
- **Validation:** search ≤ 80 chars; sort/status values allow-listed.
- **States:** loading skeleton, populated, empty, no-results, error.
- **Open questions:** whether search/sort/status filters ship in MVP (`OPEN-05`).

---

## FS-03 — Goal Detail

- **Purpose:** Single place to understand and act on one goal.
- **Content:** name, target, balance (may be negative), remaining, progress bar with accessible value, excess (if any), target date (if set), description (if set), status, Hampir Tercapai indicator, actions (Tambah Tabungan, Edit, Arsipkan), recent transactions, link to full history.
- **Alternative flows:**
  - Goal not found/not owned/archived → 404 for archived goals unless opened from the archive view.
  - No transactions → empty history state with "Tambah Tabungan".
- **Open questions:** whether the detail page paginates transactions inline or links to FS-07.

---

## FS-04 — Edit Dream Goal

- **Purpose:** Keep goal details accurate.
- **Main flow:** open prefilled form → modify → submit → validate (including uniqueness) → update → redirect with success.
- **Rules:** changing the target does not alter transactions (RULE-035); reducing the target below the balance shows 100% progress, zero remaining, and an excess (RULE-036); status is recomputed automatically (RULE-029).
- **Alternative flows:** validation errors; duplicate name; cancel discards changes; not found → 404.
- **Open questions:** none blocking. Editing a `Tercapai` goal is allowed (proposal).

---

## FS-05 — Archive Dream Goal

- **Purpose:** Remove a goal from active view without losing history.
- **Main flow:** choose "Arsipkan" → confirmation dialog naming the goal and stating that history is retained and the goal can be restored → confirm → mark archived → redirect to goals list with success message.
- **Alternative flows:** cancel → no change; not found → 404; already archived → no-op message.
- **Rules:** RULE-037, RULE-038, RULE-039, RULE-040.
- **Behavior:** archived goals are excluded from active lists, active counts, and dashboard totals; transactions remain intact; the goal is visible in the archive view.
- **Open question:** whether transactions can be added while archived (`OPEN-12`, proposal: no).

---

## FS-06 — Record Savings (Deposit or Withdrawal)

- **Purpose:** Add or remove an amount from a goal's balance.
- **Entry points:** goal detail primary action; dashboard recent-goal action.
- **Main flow:**
  1. System presents direction (`Setoran` / `Penarikan`), amount (required, whole rupiah), date (default today, WIB), note (optional).
  2. User submits.
  3. System validates (amount > 0, date valid, not future).
  4. System inserts the transaction.
  5. System recomputes balance and status.
  6. System redirects to the goal with updated balance/progress and a success message.
- **Alternative flows:** invalid amount/date → inline errors; goal not found/archived → 404; withdrawal exceeding balance → accepted and balance becomes negative; deposit above target → accepted and excess shown.
- **Rules:** RULE-011..RULE-015, RULE-020..RULE-024, RULE-051..RULE-053.
- **Open questions:** whether future transaction dates are rejected (proposal: yes); whether a quick-add amount preset exists.

---

## FS-07 — Savings History

- **Purpose:** Review and verify deposits and withdrawals.
- **Main flow:** open history for a goal → rows sorted by date desc, then id desc → numbered pagination (20/page) → each row shows direction, amount, date, note, running effect (if approved).
- **Alternative flows:** empty state; page out of range → clamp; goal not found → 404.
- **Rules:** RULE-019, RULE-042..RULE-044.
- **Open questions:** whether totals per page/month are shown; whether row-level edit/delete actions appear (`OPEN-01`, `OPEN-02`).

---

## FS-08 — Edit / Delete Savings Transaction

- **Purpose:** Correct mistakes.
- **Status:** `OPEN-01` (edit) and `OPEN-02` (delete). Not in MVP unless the owner approves.
- **Main flow (if approved):** choose action → prefilled form or confirmation → validate → persist → recompute balance and status → redirect with success.
- **Risks:** retroactive balance changes may flip status between `Berlangsung` and `Tercapai` (RULE-031).
- **Recommendation:** defer both to post-MVP; if delete is approved, keep archive semantics for goals unchanged.

---

## FS-09 — Dashboard Summary

- **Purpose:** Answer "bagaimana keseluruhan tabungan saya?" in one screen.
- **Content (bounded, proposed):**
  - Active goal count (excludes archived).
  - Total saved across active goals (deposits minus withdrawals).
  - Total remaining across active goals (floored at zero per goal).
  - Goals in the Hampir Tercapai range (90%–99%).
  - Up to 5 recent savings entries.
- **Rules:** totals derived from the same queries as goal pages; no stored aggregate; archived goals excluded (RULE-033).
- **Alternative flows:** no goals → onboarding; no transactions → "Belum ada tabungan tercatat".
- **Open questions:** exact widget set (`OPEN-06`); whether negative balances are included in "total saved" (proposal: yes, as a negative contribution).

---

## FS-10 — Goal Status & Completion

- **Purpose:** Reflect whether the target has been reached.
- **Status:** `APPROVED` (DEC-006).
- **Behavior:**
  - `Tercapai` when `balance >= target`.
  - `Berlangsung` when `balance < target`.
  - Status is recomputed after every transaction (deposit or withdrawal) and after a target edit.
  - No manual "mark complete" action exists.
  - A goal at 100% is `Tercapai`, not Hampir Tercapai.
- **UI impact:** status badge on list and detail; dashboard counts; no celebration animation in MVP.
- **Open question:** whether status is stored or derived at query time (`TECH-04`, proposal: derived).

---

## FS-11 — Authentication

- **Purpose:** Protect personal financial data.
- **Status:** `APPROVED` (DEC-003).
- **Scope:** register (name, email, password), login, logout, session protection, ownership checks, generic errors, 404 for unauthorized records.
- **Open questions:** session lifetime (`OPEN-11`), password policy (`OPEN-10`), rate limiting (`OPEN-19`), account deletion (not in scope).

---

## FS-12 — Email Verification

- **Purpose:** Confirm that the user controls the registered email address.
- **Status:** `APPROVED` (DEC-015).
- **Main flow:** registration → verification email with a time-limited, single-use link → user opens link → email marked verified → access granted.
- **Alternative flows:** expired/invalid token → resend option; already verified → informational page.
- **Open questions:** whether login is blocked before verification (`OPEN-08`, proposal: limited access until verified); token lifetime (`OPEN-09`, proposal: 60 minutes); email provider (`TECH-06`).

---

## FS-13 — Password Reset

- **Purpose:** Restore access when a password is forgotten.
- **Status:** `APPROVED` (DEC-015).
- **Main flow:** "Lupa kata sandi" → enter email → generic confirmation message → email with time-limited, single-use link → set new password → all sessions invalidated → login.
- **Alternative flows:** unknown email → same generic message (no enumeration); expired token → request again.
- **Open questions:** token lifetime and single-use enforcement (`OPEN-09`); email delivery (`TECH-06`); rate limiting (`OPEN-19`).

---

## FS-14 — Archive View & Restore

- **Purpose:** Let the user review and restore archived goals.
- **Status:** `APPROVED` (DEC-008).
- **Main flow:** open "Arsip" from navigation → list archived goals with archived date and last balance → choose "Pulihkan" → confirmation → goal returns to the active list with its full history.
- **Alternative flows:** no archived goals → empty state; not found → 404.
- **Rules:** RULE-033, RULE-038, RULE-039.
- **Open questions:** whether restore requires confirmation (proposal: yes, lightweight); whether archived goals show in search (`OPEN-05`).

---

## FS-15 — Hampir Tercapai Indicator

- **Purpose:** Motivate the user when a goal is close to completion.
- **Status:** `APPROVED` (DEC-012).
- **Behavior:** shown when `90% <= progress < 100%` and the goal is not archived. At `100%` the goal is `Tercapai`. Below `90%` no indicator.
- **Presentation:** text label plus icon; not color alone (accessibility). The indicator does not change the stored/derived status.
- **Open questions:** exact wording and icon (design stage); whether the dashboard shows a count or a list (`OPEN-06`).

---

## CRUD Matrix

| Entity | Create | Read | Update | Archive / Restore | Hard delete |
|---|---|---|---|---|---|
| Dream Goal | Yes (FS-01) | Yes (FS-02/03/14) | Yes (FS-04) | Yes (FS-05/FS-14) | **No** (DEC-008) |
| Savings Transaction | Yes (FS-06) | Yes (FS-07) | `OPEN-01` (FS-08) | Not applicable | `OPEN-02` (FS-08) |
| User | Yes (FS-11) | Yes | `OPEN` (profile) | Not applicable | Not in scope |
| Habit/Task/Note/Focus/Schedule | No | No | No | No | No (DEC-016) |

---

## Pagination & Data Navigation Decisions

| Page | Expected volume | Method | Rationale |
|---|---|---|---|
| Goals list | 0–100+ | **Numbered pagination**, 20 per page | Approved (DEC-009); stable URLs and predictable back-navigation |
| Goal detail (recent transactions) | 0–10 shown | Fixed "recent" slice + link to history | Keeps the page focused |
| Savings history | 0–1000+ | **Numbered pagination**, 20 per page | Approved (DEC-009); financial records need stable, linkable pages |
| Archive list | 0–100+ | **Numbered pagination**, 20 per page | Consistent with the goals list |
| Dashboard activity | 5 | No pagination | Bounded by design |
| Search results | Variable | Same as goals list | Consistent behavior |

**Status:** `APPROVED` (DEC-009). The previous "Load more" proposal for goals is withdrawn.

---

## Destructive / Reversible Action Policy

| Action | Confirmation | Message requirement |
|---|---|---|
| Archive goal | Required, modal | Names the goal; states that history is retained and the goal can be restored |
| Restore goal | Optional lightweight confirmation | States that the goal returns to the active list |
| Delete transaction (if approved) | Required, modal | States direction, amount, and date; states balance impact |
| Logout | Not required | — |
| Cancel form with changes | Optional prompt | Only if unsaved changes exist |

No destructive or irreversible action may be triggered by a single unconfirmed click. Archiving is reversible, but still requires confirmation.

---

## Feedback Policy

| Event | Feedback |
|---|---|
| Create success | Toast/inline: "Goal berhasil dibuat" + redirect to detail |
| Update success | Toast: "Perubahan disimpan" |
| Archive success | Toast: "Goal diarsipkan" |
| Restore success | Toast: "Goal dipulihkan" |
| Savings added | Toast: "Tabungan tercatat" + updated balance visible |
| Validation error | Field messages + error summary at form top |
| Server error | Generic error page with retry path |
| Empty state | Explanatory text + primary action |

All copy is Bahasa Indonesia (DEC-013).
