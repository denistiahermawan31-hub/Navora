# AGENTS.md — NAVORA AI Development Rules

**Applies to:** every AI coding session in this repository.
**Last updated:** 2026-11-22
**Documentation status:** draft, awaiting owner approval.
**Application status:** empty scaffold — 0 bytes per file, 0% implemented.

> This file is binding on AI agents. When it conflicts with an unapproved draft, this file wins. When it conflicts with an explicit owner instruction, the owner wins — but the conflict must be recorded in `docs/04-project-management/DECISIONS.md`.

---

## 0. Read This First

Before writing a single line of code:

1. Read `docs/README.md` (index) and `docs/04-project-management/PROGRESS.md` (real status).
2. Read `docs/04-project-management/DECISIONS.md` (what is approved vs pending).
3. Read the relevant requirement in `docs/01-product/PRD.md` and the matching rules in `docs/01-product/BUSINESS-RULES.md`.
4. Read `docs/03-technical/TECHNICAL-ARCHITECTURE.md` before touching structure or routing.
5. Inspect the actual code. Do not trust documentation to describe the implementation — verify it.
6. If a blocking decision is still `TBD` or `PENDING USER APPROVAL`, **stop and ask**. Do not invent an answer.

**Never begin implementation without explicit owner approval of the Requirements stage.**

---

## 1. Source of Truth (Priority Order)

1. Explicitly confirmed requirements from the project owner.
2. Approved BRD and PRD.
3. Approved business rules and feature specifications.
4. Approved architecture, database, and security documentation.
5. Existing verified implementation and configuration.
6. Documented technical decisions.

Assumptions are never requirements. If a statement is marked `PROPOSED`, it is **not** approved. If marked `TBD`, it is **unknown**.

---

## 2. Hard Stops

Do not proceed, and report instead, if any of these is true:

| Condition | Why |
|---|---|
| A required decision is `TBD` or `PENDING USER APPROVAL` | Financial/security behavior would be invented |
| The task requires a new dependency | Dependencies need approval |
| The task changes architecture, schema, or auth model | Architectural decisions need approval |
| The task expands scope beyond savings/dream goals | Scope control (DEC-016) |
| Existing code contradicts the documentation | Ambiguity must be resolved, not guessed |
| A destructive operation is requested without confirmation | Data safety |
| You cannot verify a claim (test, build, behavior) | Honesty requirement |
| Credentials/secrets would be committed | Security |

---

## 3. Scope Discipline

**In scope:** dream goals, target prices, optional target dates, savings transactions, derived balance/progress/remaining, savings history, dashboard summary, validation, feedback states, responsive accessible UI, and authentication **only if approved**.

**Out of scope — do not implement, link, or route:**
habits, tasks, notes, focus sessions, planner/schedule, standalone analytics, rental/borrowing, tailoring, inventory, POS, payments, bank integrations, investments, multi-tenancy, native apps.

The files for out-of-scope modules exist in the scaffold. **Leave them untouched.** Do not wire them into navigation. Do not delete them without explicit approval (DEC-016).

---

## 4. Non-Negotiable Engineering Rules

### 4.1 Preserve working functionality
- Inspect before editing.
- Make the smallest change that satisfies the requirement.
- Do not refactor unrelated code.
- Do not rename files, routes, or tables without approval.
- Do not reformat entire files.

### 4.2 No fabricated information
- Never invent requirements, business rules, test results, benchmarks, or completion status.
- Never claim a feature works without executing it.
- Never mark a test passed without evidence.
- Never present a draft as approved.
- If something is unknown, write `TBD`.

### 4.3 Dependencies
- Do not add Composer, npm, CDN, font, or icon dependencies without explicit approval.
- Prefer platform capabilities and existing code.
- GSAP is **not approved** (DEC-012). CSS transitions cover MVP motion.
- No framework migration (DEC-002).

### 4.4 Architecture
- Keep the custom PHP MVC structure: `index.php` → `routes/web.php` → controller → model → view.
- No SQL in controllers. No queries in views. No business calculations in JavaScript.
- Keep one definition of each money calculation (balance, remaining, progress).
- Do not silently change the architecture. Record any proposed change in `DECISIONS.md`.

### 4.5 Naming and structure
- Follow existing conventions; where none exist yet, use the documented ones:
  - Classes: `PascalCase` (`GoalController`, `SavingsTransaction`).
  - Methods/variables: `camelCase`.
  - DB tables/columns: `snake_case` (see `DATABASE-DESIGN.md`).
  - CSS: `nv-` prefix, component-scoped classes.
- One controller per domain area, one model per entity, one partial per reusable component.

### 4.6 Reusable components
- Build the components defined in `docs/02-design/COMPONENT-SPECIFICATION.md`.
- Do not duplicate layout, form fields, money display, or progress bars per page.
- Extend an existing component instead of creating a near-duplicate.

---

## 5. UI and Design Rules

- Follow the approved blue design system (`docs/02-design/DESIGN-SYSTEM.md`).
- Use design tokens; no hard-coded hex values outside the token file.
- The palette is **proposed** until approved (DEC-010). Do not treat it as final.
- Maintain clear hierarchy: one primary action per view.
- Support 360px–1920px. No horizontal scrolling at 360px.
- Every data view must implement loading, empty, error, and populated states.
- Destructive actions require a confirmation dialog that names the record and states the consequence.
- Use one icon library (proposed: Lucide) with meaningful icons; never emoji as UI icons.
- No purple-blue gradients, glow, glassmorphism, decorative blobs, oversized rounded cards, or generic stat-card walls.
- Respect `prefers-reduced-motion`; no layout-shifting animation; no looping decoration.

### Accessibility (WCAG 2.1 AA)
- Keyboard operable; visible focus; logical order.
- Labels for every input; errors linked and announced.
- Contrast ≥ 4.5:1 for text, ≥ 3:1 for UI boundaries.
- Modals trap focus, close on Escape, restore focus.
- Progress bars expose `role="progressbar"` with values and text.
- Do not use color alone to convey meaning.

---

## 6. Validation, Errors, and Data Integrity

- Validate every write server-side; client validation is only a convenience.
- Preserve user input on validation failure (except passwords).
- Escape all output; use prepared statements for all SQL.
- CSRF token on every POST; reject with 419.
- Scope every query by owner when authentication is enabled.
- Return 404 for missing **and** unauthorized records.
- Money: exact types (`DECIMAL`/integer minor units), never floats.
- Balance is derived from transactions; never a manually editable field (DEC-005).
- Never expose stack traces, SQL, file paths, or secrets to users.

Unresolved: currency/precision (DEC-007), withdrawals (DEC-004), completion (DEC-006), deletion (DEC-008). Do not implement these behaviors until decided.

---

## 7. Workflow for a Task

1. **Locate** the requirement ID and acceptance criteria.
2. **Confirm** no blocking `TBD` applies.
3. **Inspect** the affected files and their dependencies.
4. **Plan** the smallest change; list files to touch.
5. **Implement** following existing patterns.
6. **Verify** by executing the relevant flow/tests; capture evidence.
7. **Update** documentation: `PROGRESS.md`, `CHANGELOG.md`, and `DECISIONS.md` if a decision was made.
8. **Report** what changed, what was verified, and what remains.

Do not skip step 6. "It should work" is not verification.

---

## 8. Testing Expectations

- Run the relevant cases from `docs/05-quality/TESTING-PLAN.md`.
- Check boundary values, not just the happy path.
- Verify money math independently (e.g., 7,500,000 / 30,000,000 = 25.0%).
- Test at 360px and 1280px.
- Test keyboard-only operation for the changed flow.
- Re-run affected regression cases.
- Report actual results, including failures and skipped tests.

---

## 9. Documentation Duties

After implementation:

- [ ] `PROGRESS.md` reflects real status (not aspirational).
- [ ] `CHANGELOG.md` records the change.
- [ ] `DECISIONS.md` records any new technical/product decision.
- [ ] Requirement docs updated if behavior legitimately changed (with approval).
- [ ] Acceptance criteria marked with evidence only when executed.
- [ ] No stale "TBD" left where an answer now exists.
- [ ] No approved-looking language for unapproved items.

---

## 10. Decision Authority

AI agents **cannot approve**:

- Product scope or requirement changes.
- Financial rules (currency, withdrawals, completion, rounding, deletion).
- Security model or authentication approach.
- Architecture or database engine changes.
- Destructive data operations.
- Dependency adoption.
- Release/deployment.

Agents **may**:

- Draft proposals with rationale and alternatives.
- Implement approved requirements.
- Record decisions as `PENDING USER APPROVAL`.
- Flag conflicts and risks.
- Refuse to guess when a rule is unclear.

When uncertain: ask a precise question, state the options and consequences, and stop.

---

## 11. Communication Format

When reporting work, use:

```markdown
## Summary
<what was done, one paragraph>

## Files changed
- path — reason

## Verification
- <command/test> → <actual result>
- <what was NOT verified and why>

## Open items
- <decision needed / TBD / risk>

## Documentation updated
- <files>
```

Never omit the "what was NOT verified" section. Never claim completion without evidence.

---

## 12. Repository Facts Agents Must Know

| Fact | Detail |
|---|---|
| Application root | `NAVORA/` |
| Docs root | `docs/` (repository root) |
| Entry point | `NAVORA/index.php` (empty) |
| Core | `NAVORA/core/App.php`, `Controller.php`, `Database.php` (empty) |
| Routes | `NAVORA/routes/web.php` (empty) |
| DB schema file | `NAVORA/database/dayora.sql` (empty, legacy name) |
| Assets | `NAVORA/public/css/style.css`, `js/script.js` (empty) |
| Framework | None; custom PHP MVC |
| Package manager | None |
| Tests | None |
| PHP/Composer | Not installed in the audit environment |

If any of these facts changes, update this table and the audit inventory.

---

## 13. Definition of Done (summary)

A change is done only when: it matches an approved requirement, handles all states, passes executed acceptance criteria, is responsive and accessible, has no unrelated changes, and has updated documentation with evidence. See `docs/05-quality/DEFINITION-OF-DONE.md`.

**If you cannot meet this, report the gap instead of declaring success.**
