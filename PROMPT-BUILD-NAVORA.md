# PROMPT-BUILD-NAVORA.md

**Purpose:** A ready-to-use prompt for the implementation phase.
**Do not run this prompt until the Requirements stage is approved.** See the preconditions below.

---

## Preconditions (all must be true)

- [ ] Owner has approved `docs/01-product/PRD.md` and `docs/01-product/BUSINESS-RULES.md`.
- [ ] The approved decisions are confirmed as the source of truth: DEC-003 (multi-user auth), DEC-004/DEC-025 (withdrawals and negative balances), DEC-006 (automatic status), DEC-007 (IDR), DEC-008 (archive/restore), DEC-009 (numbered pagination), DEC-011, DEC-012, DEC-013, DEC-014, DEC-015, DEC-016, DEC-023, DEC-024, DEC-026 (`DECISIONS.md` §1).
- [ ] Remaining `OPEN` items that block the first feature are resolved or explicitly excluded (`DECISIONS.md` §4).
- [ ] PHP version and database engine are confirmed (TECH-01, TECH-02).
- [x] Primary palette is approved (`DEC-010`: exact BESTCO blue scale).
- [x] Typography and icon library are approved (`DEC-019` Option A).
- [x] Public Landing Page is approved (`DEC-027`: guest `/`, authenticated `/dashboard`).
- [ ] Development stage has been explicitly authorized.

If any box is unchecked, the correct action is to ask for the missing decision — not to start coding.

---

## Copy-Paste Prompt

> You are implementing **NAVORA**, a personal savings and dream-goal management web application.
>
> **Before writing code, read in this order:**
> 1. `AGENTS.md`
> 2. `docs/README.md`
> 3. `docs/04-project-management/PROGRESS.md`
> 4. `docs/04-project-management/DECISIONS.md`
> 5. `docs/01-product/PRD.md` and `docs/01-product/BUSINESS-RULES.md`
> 6. `docs/01-product/FEATURE-SPECIFICATION.md`
> 7. `docs/02-design/DESIGN-SYSTEM.md`, `COMPONENT-SPECIFICATION.md`, `USER-FLOW.md`, `WIREFRAME-SPECIFICATION.md`
> 8. `docs/03-technical/TECHNICAL-ARCHITECTURE.md`, `DATABASE-DESIGN.md`, `API-SPECIFICATION.md`, `SECURITY-GUIDELINES.md`
> 9. `docs/05-quality/TESTING-PLAN.md`, `ACCEPTANCE-CRITERIA.md`, `DEFINITION-OF-DONE.md`
>
> **Project facts**
> - The application lives in `NAVORA/` and is a custom PHP MVC scaffold. Every file is currently 0 bytes.
> - There is no framework, no Composer, no npm, no test framework, and no database schema.
> - Do not add dependencies, frameworks, fonts, icon packages, or build tools without explicit approval.
> - The app is server-rendered: `index.php` → `routes/web.php` → controller → model → view.
>
> **Scope**
> - In scope: mandatory multi-user accounts (register/login/logout, email verification, password reset), dream goals (create/read/update/**archive**/**restore**), target price, optional target date (past dates allowed), deposits and withdrawals, derived balance/progress/remaining/excess, automatic status and Hampir Tercapai, savings history with numbered pagination, dashboard summary, validation, feedback, responsive accessible UI, Bahasa Indonesia, IDR and WIB, Public Landing Page (`DEC-027`).
> - Out of scope: permanent goal deletion (DEC-008), transaction edit/delete (OPEN-01/OPEN-02), habits, tasks, notes, focus sessions, planner, standalone analytics, rental, inventory, POS, payments, investments, multi-currency. Leave those scaffold files untouched and unreachable (DEC-016).
>
> **Hard rules**
> - Do not invent business rules. If a rule is `TBD` or `PENDING USER APPROVAL`, stop and ask.
> - Money uses exact types; never floats. Balance is always `SUM(transactions)`, never a stored editable field.
> - Validate server-side, escape output, use prepared statements, protect POSTs with CSRF, and scope every query by owner (authentication is mandatory — DEC-003).
> - Return 404 for missing and unauthorized records.
> - Build the documented components; do not duplicate layouts or forms.
> - Follow the approved blue palette in `DESIGN-SYSTEM.md` (`DEC-010`) and the approved typography/icons (`DEC-019` Option A); no gradients/glow/glass/blobs/emoji icons.
> - Every data view needs loading, empty, error, and populated states.
> - Responsive from 360px; keyboard accessible; WCAG 2.1 AA.
> - No fabricated data in the UI.
>
> **Work order**
> 1. Bootstrap: front controller, autoloader, config, error handling, session, CSRF helper.
> 2. Database connection and approved schema.
> 3. Layout, design tokens, base components (button, field, money, progress, modal, toast, empty state).
> 4. Goals: list, create, detail, edit, archive, archive view and restore.
> 5. Savings: deposits/withdrawals, history, numbered pagination; edit/delete only if OPEN-01/OPEN-02 are approved.
> 6. Dashboard aggregation.
> 7. Authentication (mandatory): registration, login/logout, email verification, password reset.
> 8. Responsive, accessibility, and security passes.
>
> **Per task**
> - State the requirement ID you are implementing.
> - List the files you will change.
> - Implement the smallest correct change.
> - Execute the relevant test cases and record actual results.
> - Update `PROGRESS.md`, `CHANGELOG.md`, and `DECISIONS.md` when applicable.
> - Report what was verified and what was not.
>
> **Stop and ask if**
> - A blocking decision is unresolved.
> - A requirement is ambiguous or contradictory.
> - A change would alter the schema, auth model, architecture, or scope.
> - You cannot verify the result.
>
> Do not declare a feature complete without executed evidence. Do not proceed to deployment without approval.

---

## Expected First Deliverable (after approval)

A runnable skeleton that:

1. Boots through `NAVORA/index.php`.
2. Resolves a small route table from `routes/web.php`.
3. Renders the layout with approved tokens.
4. Connects to the approved database.
5. Returns a styled 404 for unknown routes and a safe 500 for failures.
6. Has **no** business features yet.

Then proceed feature by feature, verifying each before moving on.

---

## Review Checklist for the Owner

- [ ] Does the implementation match the approved requirement IDs?
- [ ] Are unresolved rules still unimplemented?
- [ ] Is the balance derived rather than stored?
- [ ] Are all states (loading/empty/error/success) present?
- [ ] Is it usable at 360px?
- [ ] Is it keyboard operable?
- [ ] Are destructive actions confirmed?
- [ ] Did `PROGRESS.md` change honestly?
- [ ] Were any dependencies added without approval?
- [ ] Is any out-of-scope module reachable?

---

## Anti-Patterns to Reject

| Pattern | Why |
|---|---|
| "I assumed multi-currency or decimals" | DEC-007: single IDR, no decimals |
| "I added a `current_saved` column" | Balance must be derived (DEC-005) |
| "I marked tests as passed" without running them | Dishonesty |
| "I wired up the habits page" | Out of scope (DEC-016) |
| "I added Tailwind/GSAP to save time" | Unapproved dependency (GSAP: DEC-020) |
| "I used a manual 'mark complete' button" | DEC-006: status is automatic from balance |
| "I deleted a goal permanently" | DEC-008: archive only, history retained |
| "I deleted the unused modules" | Destructive, unapproved |
| "I used a purple gradient because it looks modern" | Violates design direction |
| "I added sample data so the dashboard looks full" | Fabricated data |
