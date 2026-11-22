# NAVORA — Business Requirements Document (BRD)

| Field | Value |
|---|---|
| Document ID | NAVORA-BRD |
| Version | 0.2.0 (Consolidated) |
| Date | 2026-11-22 |
| Status | **Owner-approved product decisions applied; business approval pending sign-off** |
| Source basis | `SRC-00` project brief; `SRC-01` repository audit; owner decisions 2026-11-22 |
| Related documents | `PRD.md`, `BUSINESS-RULES.md`, `docs/04-project-management/DECISIONS.md` |

> This BRD contains no market research, financial projections, or user statistics. No such data was supplied, and none has been invented. Items marked `PROPOSED` require approval; items marked `OPEN` are unresolved.

---

## 1. Business Background

Individuals often save toward specific purchases without a structured way to connect day-to-day saving to a concrete target. Money is set aside informally — in a separate account, a wallet, or cash — and progress is estimated rather than measured.

NAVORA addresses this by treating each desired purchase as a **dream goal** with an explicit target price, a recorded savings history, and a visible measure of what remains.

**Verified context:** the repository contains an unimplemented PHP MVC scaffold for this product (`SRC-01`). No existing business documentation existed before 2026-11-22.

**Not verified:** market size, competitor landscape, willingness to pay, and target demographics. These are out of scope for this document.

---

## 2. Problem Statement

| Problem | Consequence for the user | NAVORA response |
|---|---|---|
| Savings for a specific purchase are tracked mentally or in ad-hoc notes | User cannot state precisely how much has been saved | A goal holds a target price and a ledger of savings entries |
| Progress is unclear | Motivation fades; the target feels distant | Progress percentage and remaining amount are always visible |
| Records are scattered | History is lost; past contributions cannot be reviewed | Per-goal transaction history with dates, paginated |
| Money sometimes has to be taken back out | Informal notes cannot represent withdrawals | Deposits and withdrawals are both recorded |
| Multiple goals compete for limited income | No prioritization or overview | A dashboard summarizes active goals and their status |
| Informal records are easy to falsify or forget | User distrusts their own numbers | Balance derived from recorded transactions, not typed totals |
| Users abandon goals they no longer pursue | Old goals clutter the list | Archive and restore preserve history without clutter |

---

## 3. Business Objectives

| ID | Objective | Measure (proposed) | Status |
|---|---|---|---|
| BO-01 | Make goal-based saving measurable | A user can determine balance and remaining amount for any goal in one view | `PROPOSED` |
| BO-02 | Reduce reliance on mental arithmetic | Balance, progress, and status are computed by the system | `PROPOSED` |
| BO-03 | Encourage consistent saving | Users can record deposits and withdrawals and see progress change immediately | `PROPOSED` |
| BO-04 | Provide a durable personal record | Savings history persists and is reviewable per goal, including archived goals | `PROPOSED` |
| BO-05 | Deliver a trustworthy, professional experience | No calculation discrepancies; clear feedback on every action | `PROPOSED` |
| BO-06 | Protect personal financial data | Each account sees only its own data | `APPROVED` (DEC-003) |

**No revenue, monetization, or growth objective is defined.** NAVORA is documented as a personal-use application. Any commercial objective is `OPEN`.

---

## 4. Target Users

| Persona | Description | Primary need | Status |
|---|---|---|---|
| PU-01 The Planned Buyer | Saves over weeks/months for a specific item (e.g., a motorcycle) | Know exactly how much remains and how far along they are | `PROPOSED` |
| PU-02 The Multi-Goal Saver | Maintains several purchase goals simultaneously | Compare goals and see the overall picture | `PROPOSED` |
| PU-03 The Record Keeper | Wants a reliable history of what was set aside, added, and withdrawn | Accurate, reviewable transaction history | `PROPOSED` |

**Assumptions (require validation):** users are individuals (not businesses), manage their own data, and have basic smartphone or desktop literacy. No demographic, geographic, or income assumptions are approved. The interface language is Bahasa Indonesia (`APPROVED`, DEC-013).

---

## 5. Business Scope

### 5.1 In Scope (MVP Scope A — DEC-024)

| ID | Capability | Basis |
|---|---|---|
| BS-01 | Create a dream goal with a unique name and target price | `SRC-00`; DEC-011 |
| BS-02 | Optionally set a target date, including past dates | `SRC-00` ("if needed"); DEC-026 |
| BS-03 | Record savings deposits and withdrawals against a goal | `SRC-00`; DEC-004 |
| BS-04 | View current balance, progress, remaining amount, and excess | `SRC-00`; DEC-023 |
| BS-05 | View savings history per goal with numbered pagination | `SRC-00`; DEC-009 |
| BS-06 | Edit a goal; archive and restore a goal with history retained | `SRC-00`; DEC-008 |
| BS-07 | Dashboard overview of active goals | `SRC-00` (implied by "monitor progress") |
| BS-08 | Responsive, accessible blue-themed interface in Bahasa Indonesia | `SRC-00`; DEC-013 |
| BS-09 | Multi-user accounts with email verification and password reset | DEC-003, DEC-015 |
| BS-10 | Automatic goal status and Hampir Tercapai indicator | DEC-006, DEC-012 |
| BS-11 | IDR formatting and Asia/Jakarta dates | DEC-007, DEC-014 |

### 5.2 Out of Scope (explicit)

Rental services, camera/projector rental, tailoring services, inventory management, point-of-sale, e-commerce checkout, lending/credit, investment products, tax calculation, bank integrations, payment processing, multi-tenant organizational accounts, administrator/back-office roles, and any feature from the legacy scaffold that is not savings-related (habits, tasks, notes, focus sessions, planner, standalone analytics — DEC-016).

### 5.3 Deferred (future candidates)

Recurring savings schedules, reminders/notifications, report export, advanced statistics/charts, shared goals, multi-currency, goal cover images, transaction editing/deleting, a settings page, and mobile applications. See `PRD.md` §11 and DEC-024.

---

## 6. Expected Benefits

| ID | Benefit | Type | Status |
|---|---|---|---|
| BN-01 | Clarity about financial progress toward a purchase | User | `PROPOSED` |
| BN-02 | Reduced manual calculation and fewer arithmetic errors | User | `PROPOSED` |
| BN-03 | Motivation through visible progress and the Hampir Tercapai indicator | User | `PROPOSED` |
| BN-04 | Reliable personal savings record, including archived goals | User | `PROPOSED` |
| BN-05 | Privacy through per-user data isolation | User | `APPROVED` (DEC-003) |

Benefits are qualitative. No quantified benefit (e.g., "increases savings by X%") is claimed.

---

## 7. Business Constraints

| ID | Constraint | Evidence | Impact |
|---|---|---|---|
| BC-01 | Web-based delivery | `SRC-00` | Browser-first; no native app in MVP |
| BC-02 | Existing PHP MVC scaffold must be respected | `SRC-01` | No framework migration without approval |
| BC-03 | Documentation-first, Waterfall gates | `SRC-00` | No development before approval |
| BC-04 | No dependency installation during documentation | `SRC-00` | Stack choices remain provisional |
| BC-05 | Blue-based visual identity | `SRC-00` | Design system constrained to blue-led palette |
| BC-06 | Scope limited to savings/dream goals | `SRC-00`; DEC-016 | Unrelated domains excluded |
| BC-07 | Single currency IDR, no decimals | DEC-007 | Money fields, validation, and display are fixed |
| BC-08 | Interface language Bahasa Indonesia, timezone WIB | DEC-013, DEC-014 | All copy and date handling are constrained |
| BC-09 | Runtime/tooling not verified in audit environment | PHP/Composer absent locally | PHP version and DB engine must be confirmed (`TECH-01`, `TECH-02`) |
| BC-10 | Team size, budget, and schedule | Not provided | `OPEN` — affects roadmap realism |

---

## 8. Stakeholders

| Role | Responsibility | Identified? |
|---|---|---|
| Project Owner / Product Owner | Approves scope, business rules, design, and releases | Repository owner (GitHub: `denistiahermawan31-hub`) |
| Developer | Implements according to approved documents | `OPEN` |
| End User | Uses the application to manage personal savings goals | `PROPOSED` personas |
| Reviewer / Supervisor | Academic or quality review, if applicable | `OPEN` |
| AI coding agent | Implements and documents; cannot approve decisions | Defined in `AGENTS.md` |

---

## 9. Success Indicators

Proposed, measurable at release. None has been measured; all are targets.

| ID | Indicator | Target | Status |
|---|---|---|---|
| SI-01 | A new user can register, verify email, create a goal, and record a transaction without assistance | ≥ 90% task completion in usability review | `PROPOSED` |
| SI-02 | Displayed balance always equals deposits minus withdrawals | 100% consistency in tests | `PROPOSED` |
| SI-03 | Progress, remaining, excess, and status are correct for all tested cases | 100% | `PROPOSED` |
| SI-04 | Core pages usable at 360px width | No horizontal scrolling; all actions reachable | `PROPOSED` |
| SI-05 | WCAG 2.1 AA conformance for core flows | No critical/serious automated findings; manual keyboard check passed | `PROPOSED` |
| SI-06 | No unhandled errors on invalid input | All validation errors presented in the UI | `PROPOSED` |
| SI-07 | No cross-account data access | 100% of isolation tests pass | `PROPOSED` |

---

## 10. Business Risks

| ID | Risk | Likelihood | Impact | Mitigation |
|---|---|---|---|---|
| BR-01 | Scope creep from legacy modules (habits/tasks/notes) | Medium | High | DEC-016; explicit exclusion in PRD |
| BR-02 | Ambiguity in remaining `OPEN` rules causes rework | Medium | Medium | Resolve `OPEN` items before their dependent feature |
| BR-03 | Users expect bank sync or automation | Medium | Medium | State manual-entry scope clearly in UI and docs |
| BR-04 | Manual data entry is tedious; users abandon tracking | Medium | High | Minimal-friction forms; immediate feedback |
| BR-05 | Email delivery failures block registration and reset | Medium | High | Choose a reliable provider; provide resend; log failures (`TECH-06`) |
| BR-06 | Negative balances confuse users | Medium | Medium | Clear labels and explanatory copy |
| BR-07 | Single-developer capacity vs Waterfall documentation overhead | Medium | Medium | Phase gates with small, reviewable deliverables |
| BR-08 | Placeholder codebase mistaken for a working app | Medium | High | Honest status in README and PROGRESS |
| BR-09 | Privacy incident from cross-account leakage | Low | Critical | Ownership-scoped queries; 404 for unauthorized access; security tests |

---

## 11. Approvals Required

| Item | Approver | Status |
|---|---|---|
| Business objectives and scope | Project Owner | `PENDING` (document sign-off) |
| Target users / personas | Project Owner | `PENDING` |
| Success indicators | Project Owner | `PENDING` |
| Multi-user authentication (DEC-003) | Project Owner | `APPROVED` 2026-11-22 |
| Currency and financial rules (DEC-004/006/007/008/023/025) | Project Owner | `APPROVED` 2026-11-22 |
| Archive policy (DEC-008) | Project Owner | `APPROVED` 2026-11-22 |
| Pagination (DEC-009) | Project Owner | `APPROVED` 2026-11-22 |
| Unique goal names (DEC-011) | Project Owner | `APPROVED` 2026-11-22 |
| Hampir Tercapai threshold (DEC-012) | Project Owner | `APPROVED` 2026-11-22 |
| Language and timezone (DEC-013/014) | Project Owner | `APPROVED` 2026-11-22 |
| Account security (DEC-015) | Project Owner | `APPROVED` 2026-11-22 |
| Legacy modules excluded (DEC-016) | Project Owner | `APPROVED` 2026-11-22 |
| MVP Scope A (DEC-024) | Project Owner | `APPROVED` 2026-11-22 |
| Past target dates (DEC-026) | Project Owner | `APPROVED` 2026-11-22 |
| Design palette, typography, icons | Project Owner | `PENDING` (DEC-010, DEC-019) |

---

## 12. Traceability

| BRD item | Traced to |
|---|---|
| Product concept, scope exclusions | `SRC-00` |
| Scaffold constraints, technical baseline | `SRC-01` |
| Multi-user, withdrawals, status, currency, archive, pagination, uniqueness, indicator, language, timezone, account security, legacy exclusion, over-saving, MVP scope, past dates | `DECISIONS.md` §7: DEC-003, DEC-004, DEC-006, DEC-007, DEC-008, DEC-009, DEC-011, DEC-012, DEC-013, DEC-014, DEC-015, DEC-016, DEC-023, DEC-024, DEC-025, DEC-026 |
| Financial rule details | `BUSINESS-RULES.md` |
| Remaining open items | `DECISIONS.md` §4 |

---

## 13. Change History (0.2.0)

- Scope updated to include withdrawals, archive/restore, automatic status, Hampir Tercapai, email verification, and password reset.
- Out-of-scope list extended with admin/back-office and legacy modules.
- Deferred list aligned with DEC-024 (notifications, export, advanced statistics).
- Constraints added for IDR, Bahasa Indonesia, and WIB.
- Risks updated; resolved decisions removed from the blocker list.
- Approvals table now records the 2026-11-22 owner decisions.
