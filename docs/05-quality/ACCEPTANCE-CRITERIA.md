# NAVORA — Acceptance Criteria

| Field | Value |
|---|---|
| Document ID | NAVORA-AC |
| Version | 0.2.0 (Consolidated) |
| Date | 2026-11-22 |
| Status | **DRAFT — none verified (no implementation exists)** |
| Related | `TESTING-PLAN.md`, `PRD.md`, `DEFINITION-OF-DONE.md` |

**Rule:** every criterion is `NOT VERIFIED` until executed with evidence. Criteria depending on unresolved decisions are marked `OPEN` or `BLOCKED`.

> Product decisions approved on 2026-11-22 (DEC-003..DEC-016, DEC-023..DEC-026) are now reflected below. Criteria that were previously blocked by those decisions are unblocked but remain `NOT VERIFIED` because no code exists.

---

## 1. Goal Management

### AC-01 — Create a goal (FR-005)
**Given** a logged-in user on the goals page
**When** they submit a name "Yamaha Aerox" and a target price of `30000000`
**Then** the goal is persisted, they are redirected to its detail page, and it shows balance `Rp0`, remaining equal to the target, and progress `0%`.
**Status:** `NOT VERIFIED`

### AC-02 — Required fields (FR-005, FR-030)
**Given** the create form
**When** it is submitted with an empty name or target
**Then** the form is re-rendered with a field-level error for each missing value, valid input is preserved, and no record is created.
**Status:** `NOT VERIFIED`

### AC-03 — Target must be positive (RULE-003)
**Given** the create form
**When** the target is `0` or negative
**Then** submission is rejected with "Nominal harus lebih dari nol" and no record is created.
**Status:** `NOT VERIFIED`

### AC-04 — Optional target date (FR-006)
**Given** the create form
**When** the target date is left empty
**Then** the goal is created and no date is displayed on the detail page.
**Status:** `NOT VERIFIED`

### AC-05 — Goal list (FR-008)
**Given** three active goals with different balances
**When** the user opens the goals list
**Then** each goal shows its name, balance, target, progress percentage, and remaining amount, and the values match the transaction sums.
**Status:** `NOT VERIFIED`

### AC-06 — Goal detail (FR-009)
**Given** a goal with 12 transactions (deposits and withdrawals)
**When** the detail page is opened
**Then** the balance equals deposits minus withdrawals, progress is `min(100, balance/target)`, remaining is `max(0, target-balance)`, and excess is `max(0, balance-target)`.
**Status:** `NOT VERIFIED`

### AC-07 — Edit goal (FR-010)
**Given** an existing goal
**When** the name and target are changed and saved
**Then** the new values are displayed, transactions are unchanged, and progress/remaining/status are recomputed.
**Status:** `NOT VERIFIED`

### AC-08 — Reduce target below saved (RULE-036)
**Given** balance `Rp10.000.000` and target `Rp10.000.000`
**When** the target is changed to `Rp5.000.000`
**Then** progress displays `100%`, remaining displays `Rp0`, and excess displays `Rp5.000.000`.
**Status:** `NOT VERIFIED`

### AC-09 — Archive requires confirmation (FR-011)
**Given** an existing goal
**When** the user selects "Arsipkan"
**Then** a confirmation dialog names the goal and states that history is retained and the goal can be restored, and no archiving occurs until confirmed.
**Status:** `NOT VERIFIED`

### AC-10 — Archive outcome (FR-011, FR-038)
**Given** a confirmed archive action
**When** the request completes
**Then** the goal is marked archived, its transactions remain, it disappears from active lists/counts/totals, and it appears in the archive view with a success message.
**Status:** `NOT VERIFIED`

### AC-11 — Empty goals state (FR-015)
**Given** a user with no goals
**When** they open the dashboard or goals list
**Then** an empty state explains the situation and offers a "Buat Goal" action; no fabricated data is displayed.
**Status:** `NOT VERIFIED`

### AC-12 — Search (FR-013)
**Given** goals "Yamaha Aerox" and "Laptop"
**When** the user searches "aerox"
**Then** only the matching goal is listed; a query with no matches shows a no-results state with a clear action.
**Status:** `OPEN-05` (search not confirmed for MVP)

### AC-41 — Unique goal name per account (FR-039)
**Given** user A already has a goal named "Laptop"
**When** user A creates or renames another goal to "laptop"
**Then** the request is rejected with "Nama goal sudah digunakan"; user B may still create a goal named "Laptop".
**Status:** `NOT VERIFIED`

### AC-42 — Past target date accepted (FR-044)
**Given** the create or edit form
**When** the target date is set to a date before today (WIB)
**Then** the goal is saved without error or warning.
**Status:** `NOT VERIFIED`

---

## 2. Savings

### AC-13 — Record a deposit (FR-016)
**Given** a goal with balance `Rp5.000.000`
**When** the user records a deposit of `Rp1.500.000` dated today
**Then** exactly one transaction is created, the balance becomes `Rp6.500.000`, and a success message is shown.
**Status:** `NOT VERIFIED`

### AC-14 — Reject invalid amounts (RULE-011)
**Given** the add-savings form
**When** the amount is `0`, negative, non-numeric, or contains decimals
**Then** the form shows an error, no transaction is created, and the balance is unchanged.
**Status:** `NOT VERIFIED`

### AC-15 — Reject future dates (RULE-013)
**Given** today is 2026-11-22 (WIB)
**When** the transaction date is 2026-11-23
**Then** the submission is rejected with a clear message and no transaction is created.
**Status:** `NOT VERIFIED` (rule is `PROPOSED`)

### AC-16 — History ordering and pagination (FR-021)
**Given** 25 transactions
**When** the history page is opened
**Then** 20 rows are shown newest-first, numbered pagination offers page 2, and page 2 shows the remaining 5.
**Status:** `NOT VERIFIED`

### AC-17 — Invalid page (RULE-043)
**Given** 25 transactions
**When** the URL requests page `999`, `0`, or `abc`
**Then** the system returns a valid page (page 1 or last valid page) without an error page or blank list.
**Status:** `NOT VERIFIED`

### AC-18 — Empty history (FR-034)
**Given** a goal with no transactions
**When** the detail or history page is opened
**Then** an empty state with a "Tambah Tabungan" action is shown instead of an empty table.
**Status:** `NOT VERIFIED`

### AC-19 — Delete transaction (FR-023)
**Given** a transaction of `Rp1.500.000` in a goal
**When** the user confirms deletion
**Then** the transaction is removed, the balance changes by exactly that amount, and status is recomputed.
**Status:** `OPEN-02` (not in MVP)

### AC-43 — Record a withdrawal (FR-016)
**Given** a goal with balance `Rp5.000.000`
**When** the user records a withdrawal of `Rp2.000.000` dated today
**Then** exactly one transaction is created, the balance becomes `Rp3.000.000`, and a success message is shown.
**Status:** `NOT VERIFIED`

### AC-44 — Withdrawal may exceed balance (FR-040)
**Given** a goal with balance `Rp2.000.000`
**When** the user records a withdrawal of `Rp5.000.000`
**Then** the transaction is accepted, the balance becomes `-Rp3.000.000`, and the negative balance is displayed clearly.
**Status:** `NOT VERIFIED`

### AC-45 — Over-saving (FR-025)
**Given** a target of `Rp30.000.000` and balance `Rp35.000.000`
**Then** the balance is not capped, progress shows `100%`, remaining shows `Rp0`, and excess shows `Rp5.000.000` separately.
**Status:** `NOT VERIFIED`

### AC-46 — Automatic completion (FR-024)
**Given** a target of `Rp30.000.000` and balance `Rp29.000.000`
**When** a deposit of `Rp1.000.000` is recorded
**Then** the status becomes `Tercapai` without any manual action.
**Status:** `NOT VERIFIED`

### AC-47 — Status returns to Berlangsung (FR-024)
**Given** a `Tercapai` goal at exactly the target
**When** a withdrawal drops the balance below the target
**Then** the status becomes `Berlangsung` automatically.
**Status:** `NOT VERIFIED`

### AC-48 — Hampir Tercapai shown (FR-041)
**Given** a goal at 90% or 99% progress and not archived
**Then** the "Hampir Tercapai" indicator is visible.
**Status:** `NOT VERIFIED`

### AC-49 — Hampir Tercapai not shown at 100% (FR-041)
**Given** a goal at exactly 100% progress
**Then** the status is `Tercapai` and the Hampir Tercapai indicator is not shown.
**Status:** `NOT VERIFIED`

---

## 3. Calculations

### AC-20 — Progress formula (RULE-022)
**Given** balance `Rp7.500.000` and target `Rp30.000.000`
**Then** progress is `25,0%` and remaining is `Rp22.500.000`.
**Status:** `NOT VERIFIED`

### AC-21 — Over-target display (RULE-023)
**Given** balance `Rp35.000.000` and target `Rp30.000.000`
**Then** the progress bar is full, the percentage displays `100%`, remaining displays `Rp0`, and excess displays `Rp5.000.000`.
**Status:** `NOT VERIFIED`

### AC-22 — Integer exactness (RULE-026)
**Given** amounts `Rp1`, `Rp2`, and `Rp3`
**Then** the balance is exactly `Rp6`, stored and displayed without floating-point artefacts.
**Status:** `NOT VERIFIED`

### AC-23 — No N+1 aggregation (NFR-002)
**Given** 50 goals with transactions
**When** the list loads
**Then** balances are obtained without one query per goal (query count bounded and logged during review).
**Status:** `NOT VERIFIED`

### AC-50 — Balance with withdrawals (RULE-020)
**Given** deposits `Rp10.000.000` and withdrawals `Rp4.000.000`
**Then** the balance is exactly `Rp6.000.000`, and any independently calculated value matches.
**Status:** `NOT VERIFIED`

---

## 4. Dashboard

### AC-24 — Summary accuracy (FR-026)
**Given** three active goals with known balances
**When** the dashboard loads
**Then** active-goal count, total saved, and total remaining match independently calculated values.
**Status:** `NOT VERIFIED`

### AC-25 — Zero state (FR-015)
**Given** no goals
**Then** no zero-filled statistic cards are shown above the empty state.
**Status:** `NOT VERIFIED`

### AC-51 — Archived goals excluded (FR-045)
**Given** one active goal and one archived goal
**When** the dashboard loads
**Then** counts and totals include only the active goal.
**Status:** `NOT VERIFIED`

---

## 5. Feedback & Errors

### AC-26 — Success feedback (FR-031)
**Given** any successful create/update/archive/restore
**Then** a visible success message appears and the page reflects the change after redirect.
**Status:** `NOT VERIFIED`

### AC-27 — CSRF rejection (NFR-006)
**Given** a POST without a valid token
**Then** the server returns 419 and no state changes.
**Status:** `NOT VERIFIED`

### AC-28 — Not found (FR-032)
**Given** a nonexistent goal ID
**Then** a 404 page is shown with no internal details and a link back to the dashboard.
**Status:** `NOT VERIFIED`

### AC-29 — Server error safety (FR-032)
**Given** a forced database failure
**Then** the user sees a generic error with a reference ID; no stack trace, SQL, or file path is exposed.
**Status:** `NOT VERIFIED`

---

## 6. Responsive & Accessibility

### AC-30 — Mobile usability (NFR-008)
**Given** a 360px-wide viewport
**When** viewing dashboard, goals, detail, archive, and history
**Then** there is no horizontal scrolling and every action is reachable.
**Status:** `NOT VERIFIED`

### AC-31 — Keyboard operation (NFR-007)
**Given** keyboard-only input
**When** registering, creating a goal, and adding savings
**Then** every control is reachable, focus is visible, and the flow completes without a mouse.
**Status:** `NOT VERIFIED`

### AC-32 — Dialog accessibility (NFR-007)
**Given** a confirmation dialog is open
**Then** focus is trapped inside, Escape closes it, and focus returns to the trigger.
**Status:** `NOT VERIFIED`

### AC-33 — Progress semantics (NFR-007)
**Given** a progress bar at 42%
**Then** assistive technology announces a progressbar with value 42 of 100 and an accessible label.
**Status:** `NOT VERIFIED`

### AC-34 — Reduced motion (NFR-012)
**Given** `prefers-reduced-motion: reduce`
**Then** entrance animations and transitions are effectively disabled and no content shifts.
**Status:** `NOT VERIFIED`

### AC-35 — Contrast (NFR-007)
**Given** the approved palette
**Then** all body text meets 4.5:1 and UI boundaries meet 3:1, verified with measured ratios.
**Status:** `BLOCKED — DEC-010` (palette not approved)

---

## 7. Scope Control

### AC-36 — No out-of-scope features (DEC-016)
**Given** the application navigation and routes
**Then** habits, tasks, notes, focus sessions, planner, and analytics are not reachable and not linked.
**Status:** `NOT VERIFIED`

### AC-37 — No fabricated data
**Given** any page with no records
**Then** no sample goals, invented totals, or placeholder financial values are displayed.
**Status:** `NOT VERIFIED`

---

## 8. Authentication & Account Security

### AC-38 — Registration (FR-001)
**Given** the registration form
**When** a visitor submits a valid unique email and matching passwords
**Then** an account is created with a hashed password and a verification email is sent.
**Status:** `NOT VERIFIED`

### AC-39 — Login/logout (FR-002)
**Given** a registered user
**When** they log in and later log out
**Then** the session is created with a regenerated ID and invalidated on logout; the back button does not restore authenticated content.
**Status:** `NOT VERIFIED`

### AC-40 — Data isolation (FR-003)
**Given** two users
**When** user A requests user B's goal
**Then** a 404 is returned and no data is disclosed.
**Status:** `NOT VERIFIED`

### AC-52 — Email verification (FR-036)
**Given** a newly registered user
**When** they open the verification link before it expires
**Then** the email is marked verified and the token cannot be reused.
**Status:** `NOT VERIFIED` (enforcement point `OPEN-08`)

### AC-53 — Password reset (FR-037)
**Given** a registered email
**When** the user requests a reset, opens the link, and sets a new password
**Then** the password is updated, the token is single-use, and existing sessions are invalidated.
**Status:** `NOT VERIFIED` (token lifetime `OPEN-09`)

### AC-54 — Unauthorized returns 404, not 403
**Given** any record owned by another user
**Then** the response is 404 and does not reveal whether the record exists.
**Status:** `NOT VERIFIED`

### AC-55 — Indonesian UI and IDR formatting (FR-033, FR-042)
**Given** any page displaying money or copy
**Then** labels are in Bahasa Indonesia and amounts use `Rp15.000.000` format with no decimals.
**Status:** `NOT VERIFIED`

### AC-56 — WIB date handling (FR-043)
**Given** a user in any timezone
**When** they record a transaction "today"
**Then** the date is interpreted in Asia/Jakarta and the future-date check uses WIB.
**Status:** `NOT VERIFIED`

---

## 9. Summary

| Category | Criteria | Verified | Open/Blocked |
|---|---|---|---|
| Goals | 14 | 0 | 1 (AC-12) |
| Savings | 14 | 0 | 1 (AC-19) |
| Calculations | 5 | 0 | 0 |
| Dashboard | 3 | 0 | 0 |
| Feedback/errors | 4 | 0 | 0 |
| Responsive/a11y | 6 | 0 | 1 (AC-35) |
| Scope | 2 | 0 | 0 |
| Authentication | 8 | 0 | 0 |
| **Total** | **56** | **0** | **3** |

No criterion has been verified. This table must be updated with evidence as testing proceeds.
