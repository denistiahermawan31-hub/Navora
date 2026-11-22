# NAVORA — Testing Plan

| Field | Value |
|---|---|
| Document ID | NAVORA-TP |
| Version | 0.2.0 (Consolidated) |
| Date | 2026-11-22 |
| Status | **PLAN ONLY — no tests executed** |
| Related | `ACCEPTANCE-CRITERIA.md`, `DEFINITION-OF-DONE.md`, `SRS.md` |

> **No test has been run.** The application contains no code. Every status in this document is `NOT RUN` until evidence is recorded in `PROGRESS.md` or a test report.
>
> Test cases reflect the owner-approved decisions of 2026-11-22: DEC-003, DEC-004, DEC-006, DEC-007, DEC-008, DEC-009, DEC-011, DEC-012, DEC-013, DEC-014, DEC-015, DEC-016, DEC-023, DEC-024, DEC-025, DEC-026 (`DECISIONS.md` §7). Cases that depend on remaining `OPEN`/`PROPOSED` items are marked accordingly.

---

## 1. Objectives

1. Verify every `Must` requirement behaves as specified.
2. Prove financial calculations (balance, remaining, progress, excess, status) are exact and consistent.
3. Confirm archive/restore retains history and excludes archived goals from active views.
4. Confirm withdrawals, negative balances, and over-saving behave as approved.
5. Confirm validation and error handling never expose internals or lose user input.
6. Verify authentication, email verification, password reset, and data isolation.
7. Verify responsive layout and keyboard accessibility.
8. Confirm security controls (CSRF, escaping, authorization) work.
9. Ensure no out-of-scope feature is reachable.

---

## 2. Test Levels

| Level | Scope | Tool | Status |
|---|---|---|---|
| Unit | Money formatting, progress/remaining/excess helpers, validators | `TBD` (PHPUnit requires Composer approval) | `NOT RUN` |
| Integration | SQL queries, aggregates, pagination, archive filters, FK behavior | `TBD` | `NOT RUN` |
| Feature/HTTP | Route → controller → view → redirect | `TBD` or manual HTTP client | `NOT RUN` |
| UI/Visual | Layout, states, feedback | Manual browser | `NOT RUN` |
| Responsive | 360, 768, 1024, 1440 px | Browser devtools + real device if available | `NOT RUN` |
| Accessibility | Keyboard, contrast, labels, focus, screen reader | Manual + `TBD` automated checker | `NOT RUN` |
| Security | Injection, XSS, CSRF, IDOR, headers, token reuse | Manual + `TBD` | `NOT RUN` |
| Performance | Server timing, query count, page weight | Browser devtools + DB profiling | `NOT RUN` |
| Regression | Re-run affected cases after changes | Manual/automated | `NOT RUN` |

No test framework is installed and none may be installed during the documentation phase.

---

## 3. Environments

| Environment | Purpose | Status |
|---|---|---|
| Local development | Feature testing | `OPEN` — PHP not installed in audit environment |
| Staging | Pre-release verification | `OPEN` |
| Production | Smoke tests only | `OPEN` |

**Required environment facts:** PHP version (`TECH-01`), DB engine/version (`TECH-02`), web server, timezone (Asia/Jakarta approved), currency (IDR approved), email delivery (`TECH-06`).

---

## 4. Test Data Policy

- Use synthetic data only (e.g., "Test Goal A", amounts like `1000000`).
- Never use real personal financial data.
- Never ship demo data in the UI.
- Each test creates and cleans up its own records where possible.
- Boundary values must be explicit (see §6).
- The reference example (Yamaha Aerox) may be used as illustrative data, clearly labeled as test data.

---

## 5. Test Cases

### 5.1 Authentication & Account

| ID | Case | Steps | Expected | Covers | Status |
|---|---|---|---|---|---|
| TC-A-01 | Register valid account | Submit name, unique email, matching passwords | Account created; verification email sent | FR-001, FR-036 | `NOT RUN` |
| TC-A-02 | Duplicate email | Register with an existing email | Rejected with generic message; no enumeration | FR-001 | `NOT RUN` |
| TC-A-03 | Weak password | Submit below minimum length | Rejected | FR-001 (`OPEN-10`) | `NOT RUN` |
| TC-A-04 | Verify email | Open valid verification link | Email marked verified; single-use enforcement pending `OPEN-09` | FR-036 | `NOT RUN` |
| TC-A-05 | Expired verification token | Open an expired link | Rejected; resend offered | FR-036 | `NOT RUN` |
| TC-A-06 | Login valid | Correct email and password | Session created; ID regenerated | FR-002 | `NOT RUN` |
| TC-A-07 | Login invalid | Wrong password | Generic error; no enumeration | FR-002 | `NOT RUN` |
| TC-A-08 | Logout | POST logout with CSRF | Session invalidated; back button shows no protected content | FR-002 | `NOT RUN` |
| TC-A-09 | Password reset request | Submit a registered email | Generic confirmation; email sent | FR-037 | `NOT RUN` |
| TC-A-10 | Password reset unknown email | Submit unknown email | Same generic confirmation; no email | FR-037 | `NOT RUN` |
| TC-A-11 | Password reset completion | Open valid link; set new password | Password updated; sessions invalidated; single-use/lifetime pending `OPEN-09` | FR-037 | `NOT RUN` |
| TC-A-12 | Data isolation | User A requests user B's goal | 404; no data disclosed | FR-003 | `NOT RUN` |
| TC-A-13 | Persistence across sessions | Create a goal and transaction, log out, log in again in a new session | Records, balance, and status are unchanged | FR-035 | `NOT RUN` |

### 5.2 Goals

| ID | Case | Steps | Expected | Covers | Status |
|---|---|---|---|---|---|
| TC-G-01 | Create valid goal | Submit name + target | 303 redirect; goal visible; balance 0 | FR-005 | `NOT RUN` |
| TC-G-02 | Missing name | Submit empty name | Error on name; values preserved | FR-030 | `NOT RUN` |
| TC-G-03 | Name at 80 chars | Submit 80-char name | Accepted | RULE-002 | `NOT RUN` |
| TC-G-04 | Name at 81 chars | Submit 81-char name | Rejected with message | RULE-002 | `NOT RUN` |
| TC-G-05 | Target zero | Submit `0` | Rejected | RULE-003 | `NOT RUN` |
| TC-G-06 | Target negative | Submit `-500` | Rejected | RULE-003 | `NOT RUN` |
| TC-G-07 | Target non-numeric | Submit `abc` | Rejected | VR-03 | `NOT RUN` |
| TC-G-08 | Target with decimals | Submit `1000.50` | Rejected (IDR has no decimals) | RULE-004 | `NOT RUN` |
| TC-G-09 | Optional date empty | Omit date | Accepted; detail shows no date | FR-006 | `NOT RUN` |
| TC-G-10 | Invalid date | Submit `2026-02-30` | Rejected | RULE-006 | `NOT RUN` |
| TC-G-11 | Past target date | Submit yesterday's date | Accepted without warning | FR-044 | `NOT RUN` |
| TC-G-12 | Duplicate name same account | Create "Laptop" twice | Second rejected | FR-039 | `NOT RUN` |
| TC-G-13 | Same name different accounts | User A and B both create "Laptop" | Both accepted | FR-039 | `NOT RUN` |
| TC-G-14 | Edit goal | Change name and target | Persisted; progress recomputed | FR-010 | `NOT RUN` |
| TC-G-15 | Reduce target below saved | Balance 10, target 10 → target 5 | 100%, remaining 0, excess 5 | RULE-036 | `NOT RUN` |
| TC-G-16 | Archive without confirm | Trigger archive, cancel | Nothing archived | FR-011 | `NOT RUN` |
| TC-G-17 | Archive with confirm | Confirm | Archived; history retained; removed from active list | FR-011, FR-045 | `NOT RUN` |
| TC-G-18 | Restore goal | Restore from archive | Goal returns to active list with history | FR-038 | `NOT RUN` |
| TC-G-19 | Goal list aggregates | Two goals with transactions | Correct per-goal and total values | FR-008 | `NOT RUN` |
| TC-G-20 | Empty goals | No records | Empty state with CTA | FR-015 | `NOT RUN` |
| TC-G-21 | Search match / no match | Query existing and missing name | Filtered results / no-results state | FR-013 (`OPEN-05`) | `NOT RUN` |
| TC-G-22 | Sort options | Change sort | Order changes; invalid value falls back | FR-012 (`OPEN-05`) | `NOT RUN` |

### 5.3 Savings Transactions

| ID | Case | Steps | Expected | Covers | Status |
|---|---|---|---|---|---|
| TC-T-01 | Add deposit | Amount + date | Balance increases exactly; toast shown | FR-016/018 | `NOT RUN` |
| TC-T-02 | Add withdrawal | Amount + date | Balance decreases exactly; toast shown | FR-016/018 | `NOT RUN` |
| TC-T-03 | Zero amount | Submit `0` | Rejected | RULE-011 | `NOT RUN` |
| TC-T-04 | Negative amount | Submit `-100` | Rejected; direction handles sign | RULE-011 | `NOT RUN` |
| TC-T-05 | Decimal amount | Submit `1000.50` | Rejected | RULE-012 | `NOT RUN` |
| TC-T-06 | Future date | Tomorrow's date | Rejected | RULE-013 | `NOT RUN` |
| TC-T-07 | Today's date | Today (WIB) | Accepted | RULE-013 | `NOT RUN` |
| TC-T-08 | Note at limit | 255 chars | Accepted | RULE-014 (`OPEN-04`) | `NOT RUN` |
| TC-T-09 | Note over limit | 256 chars | Rejected | RULE-014 (`OPEN-04`) | `NOT RUN` |
| TC-T-10 | Multiple transactions | Add 3 deposits | Sum matches arithmetic | RULE-020 | `NOT RUN` |
| TC-T-11 | Mixed transactions | Deposit 10, withdraw 4 | Balance 6 | RULE-020 | `NOT RUN` |
| TC-T-12 | Withdrawal exceeds balance | Balance 2, withdraw 5 | Accepted; balance -3 | FR-040 | `NOT RUN` |
| TC-T-13 | Deposit over target | Target 30, balance 35 | Accepted; progress 100%; excess 5 | FR-025 | `NOT RUN` |
| TC-T-14 | Pagination page 1 | 21 transactions (draft size) | Numbered pagination (DEC-009); 20 rows under draft `C-20` | FR-021 | `NOT RUN` |
| TC-T-15 | Pagination last page | Go to last | Correct remaining rows | FR-021 | `NOT RUN` |
| TC-T-16 | Invalid page | `?page=999` / `?page=0` / `?page=abc` | Clamped, no error | RULE-043 | `NOT RUN` |
| TC-T-17 | Empty history | Goal with no transactions | Empty state | RULE-044 | `NOT RUN` |
| TC-T-18 | Ordering | Same-day entries | Newest first, stable by id | RULE-019 | `NOT RUN` |
| TC-T-19 | Double submit | Submit twice rapidly | One record | SRS §7 | `NOT RUN` |
| TC-T-20 | Archived goal transaction | Add transaction to archived goal | Blocked with guidance | RULE-055 (`OPEN-12`) | `NOT RUN` |

### 5.4 Calculations & Status

| ID | Case | Input | Expected | Status |
|---|---|---|---|---|
| TC-C-01 | Zero progress | balance 0, target 30,000,000 | 0%, remaining 30,000,000 | `NOT RUN` |
| TC-C-02 | Partial progress | balance 7,500,000, target 30,000,000 | 25,0%, remaining 22,500,000 | `NOT RUN` |
| TC-C-03 | Exactly complete | balance 30,000,000 | 100%, remaining 0, status Tercapai | `NOT RUN` |
| TC-C-04 | Over target | balance 35,000,000 | Bar 100%; remaining 0; excess 5,000,000; Tercapai | `NOT RUN` |
| TC-C-05 | Integer exactness | 1 + 2 + 3 | 6 exactly, no float artefacts | `NOT RUN` |
| TC-C-06 | Large amount | Maximum allowed (`TECH-03`) | Stored and displayed exactly | `NOT RUN` |
| TC-C-07 | Percentage rounding | 1/3 of target | Display precision per `OPEN-15` | `NOT RUN` |
| TC-C-08 | Negative balance | deposits 2,000,000; withdrawal 5,000,000 | -3,000,000; remaining = target; progress 0% | `NOT RUN` |
| TC-C-09 | Status flips down | Tercapai, then withdrawal below target | Status returns to Berlangsung | `NOT RUN` |
| TC-C-10 | Hampir Tercapai | progress 90% and 99% | Indicator visible | `NOT RUN` |
| TC-C-11 | No indicator at 100% | progress 100% | Tercapai; no Hampir Tercapai | `NOT RUN` |
| TC-C-12 | IDR formatting | 15000000 | `Rp15.000.000` | `NOT RUN` |
| TC-C-13 | Negative IDR formatting | -2000000 | Format per `OPEN-16` (proposal `-Rp2.000.000`) | `NOT RUN` |

### 5.5 Validation & Errors

| ID | Case | Expected | Status |
|---|---|---|---|
| TC-E-01 | Invalid route | 404 page, no internals | `NOT RUN` |
| TC-E-02 | Nonexistent goal ID | 404 | `NOT RUN` |
| TC-E-03 | Another user's goal | 404, not 403 | `NOT RUN` |
| TC-E-04 | Missing CSRF token | 419, no change | `NOT RUN` |
| TC-E-05 | Expired session on submit | Redirect to login or 419, no data loss beyond session | `NOT RUN` |
| TC-E-06 | DB unavailable | Generic 500 with reference ID | `NOT RUN` |
| TC-E-07 | Oversized input | Rejected gracefully | `NOT RUN` |

### 5.6 UI, Responsive, Accessibility

| ID | Case | Expected | Status |
|---|---|---|---|
| TC-U-01 | Dashboard at 360px | No horizontal scroll; actions reachable | `NOT RUN` |
| TC-U-02 | Goals grid at 768/1280px | 2/3 columns, aligned | `NOT RUN` |
| TC-U-03 | Table at 360px | Stacked rows, no clipping | `NOT RUN` |
| TC-U-04 | Modal focus trap | Tab cycles inside; Escape closes; focus restored | `NOT RUN` |
| TC-U-05 | Keyboard-only goal creation | Fully operable | `NOT RUN` |
| TC-U-06 | Visible focus | All focusable elements show a ring | `NOT RUN` |
| TC-U-07 | Form error announcement | Error summary receives focus | `NOT RUN` |
| TC-U-08 | Progress bar semantics | `role="progressbar"` with correct values | `NOT RUN` |
| TC-U-09 | Reduced motion | No transforms/animations | `NOT RUN` |
| TC-U-10 | Contrast | All text pairs meet 4.5:1 | `NOT RUN` |
| TC-U-11 | Zoom 200% | No content loss | `NOT RUN` |
| TC-U-12 | Toast announcement | Live region announces message | `NOT RUN` |
| TC-U-13 | Indonesian copy | No English UI strings | `NOT RUN` |
| TC-U-14 | Hampir Tercapai not color-only | Label/icon present | `NOT RUN` |
| TC-U-15 | Persistent navigation | From dashboard, list, detail, history, and archive views | Dashboard/Goals/Arsip links visible and reachable; `FR-028` is `PROPOSED` | `NOT RUN` |

### 5.7 Security

| ID | Case | Expected | Status |
|---|---|---|---|
| TC-S-01 | SQLi in name/search | Stored literally; no error | `NOT RUN` |
| TC-S-02 | XSS in name/note | Escaped in all views | `NOT RUN` |
| TC-S-03 | CSRF on create/archive | Rejected without valid token | `NOT RUN` |
| TC-S-04 | IDOR on goal/transaction | 404 | `NOT RUN` |
| TC-S-05 | Password storage | Hash only | `NOT RUN` |
| TC-S-06 | Session fixation | New ID after login | `NOT RUN` |
| TC-S-07 | Logout back-button | No authenticated content | `NOT RUN` |
| TC-S-08 | Response headers | Security headers present | `NOT RUN` |
| TC-S-09 | Error pages | No stack traces/paths | `NOT RUN` |
| TC-S-10 | Source exposure | `/.env`, `/database/*.sql`, `/config/*` not accessible | `NOT RUN` |
| TC-S-11 | Verification token reuse | Second use rejected | `NOT RUN` |
| TC-S-12 | Reset token reuse | Second use rejected | `NOT RUN` |

### 5.8 Performance

| ID | Case | Target | Status |
|---|---|---|---|
| TC-P-01 | Goal list, 100 goals | < 500 ms server time | `NOT RUN` |
| TC-P-02 | History page, draft 20 rows (`C-20` OPEN) | < 300 ms | `NOT RUN` |
| TC-P-03 | Query count for list | Constant, no N+1 | `NOT RUN` |
| TC-P-04 | CSS+JS weight | ≤ 250 KB uncompressed | `NOT RUN` |
| TC-P-05 | No external requests | Zero third-party calls | `NOT RUN` |

---

## 6. Boundary Values

| Field | Min | Max | Invalid samples |
|---|---|---|---|
| Goal name | 1 char | 80 chars | `""`, 81 chars, duplicate in account |
| Target price | 1 | `OPEN` (`TECH-03`) | `0`, `-1`, `abc`, `1000.50` |
| Description | 0 | 500 chars | 501 chars |
| Transaction amount | 1 | `OPEN` (`TECH-03`) | `0`, `-1`, `NaN`, `1000.50` |
| Transaction note | 0 | 255 chars | 256 chars |
| Page | 1 | last page | `0`, `-1`, `999`, `abc` |
| Search query | 0 | 80 chars | 81 chars, SQL metacharacters |
| Password | `OPEN-10` | 255 bytes | too short |
| Auth token | 1 | lifetime `OPEN-09` | expired, reused, malformed |

---

## 7. Defect Management

| Severity | Definition | Action |
|---|---|---|
| Critical | Data loss, incorrect balance, security breach, app unusable | Block release |
| High | Major feature broken, no workaround | Block release |
| Medium | Feature impaired with workaround | Fix before release if feasible |
| Low | Cosmetic or minor | May defer with owner approval |

Defect record: ID, severity, steps, expected vs actual, environment, evidence, status, fix reference.

---

## 8. Entry & Exit Criteria

**Entry to testing**
- [ ] Requirements approved.
- [ ] Code implemented for the feature under test.
- [ ] Test environment configured with known PHP/DB versions.
- [ ] Email delivery available for verification/reset tests.
- [ ] Test data prepared.

**Exit from testing (G4)**
- [ ] All `Must` acceptance criteria executed and passing.
- [ ] No open Critical/High defects.
- [ ] Security checks completed, including token reuse and IDOR.
- [ ] Responsive and accessibility checks completed.
- [ ] Results recorded with evidence; unexecuted tests explicitly listed.

---

## 9. Reporting

A test report must include:
- Build/commit reference.
- Environment details.
- Cases executed vs total.
- Pass/fail/skipped with reasons.
- Defects by severity.
- Known limitations.
- Sign-off.

**Rule:** a test may only be marked `PASS` with recorded evidence (screenshot, log, or automated output). "Looks fine" is not evidence.
