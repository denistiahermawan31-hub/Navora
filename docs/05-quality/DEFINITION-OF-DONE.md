# NAVORA — Definition of Done (DoD)

| Field | Value |
|---|---|
| Document ID | NAVORA-DOD |
| Version | 0.1 (Draft) |
| Date | 2026-11-22 |
| Status | **PROPOSED — applies once development begins** |
| Related | `TESTING-PLAN.md`, `ACCEPTANCE-CRITERIA.md`, `AGENTS.md` |

A feature is **not** done because code exists. It is done when every applicable item below is satisfied **and evidence is recorded**.

---

## 1. Universal DoD (every change)

- [ ] Behaviour matches an approved requirement (`PRD.md`) and business rule (`BUSINESS-RULES.md`).
- [ ] No out-of-scope feature was added (DEC-016).
- [ ] Existing working functionality is preserved; no unrelated file was modified.
- [ ] Code follows the existing structure and naming conventions.
- [ ] Inputs are validated server-side; output is escaped.
- [ ] Loading, empty, success, and error states are handled where data is shown.
- [ ] Responsive behavior verified at 360px and 1280px.
- [ ] Keyboard operation verified for the affected flow.
- [ ] No console errors; no unhandled exceptions in the server log.
- [ ] Relevant documentation updated (`PROGRESS.md`, `DECISIONS.md` if a decision was made).
- [ ] Evidence recorded (test result, screenshot, or log).
- [ ] No secrets, debug output, or temporary code committed.

---

## 2. Feature DoD

A feature is done when:

- [ ] Its acceptance criteria (`ACCEPTANCE-CRITERIA.md`) are executed and pass.
- [ ] Its test cases (`TESTING-PLAN.md`) are executed, with failures resolved or explicitly deferred by the owner.
- [ ] Boundary values are tested (min, max, invalid).
- [ ] Money calculations are verified against independent arithmetic.
- [ ] Destructive actions require confirmation and state their consequence.
- [ ] Success and failure feedback is visible and accessible.
- [ ] Documentation reflects actual behavior, not intended behavior.
- [ ] `PROGRESS.md` status is updated honestly.

---

## 3. UI Component DoD

- [ ] Matches `COMPONENT-SPECIFICATION.md`.
- [ ] Uses design tokens; no hard-coded colors outside the token set.
- [ ] Defines default, hover, active, focus-visible, disabled, loading, and error states where applicable.
- [ ] Defines loading, empty, and error states for data components.
- [ ] Responsive behavior defined at 360px and 1280px.
- [ ] Contrast verified (≥4.5:1 text, ≥3:1 UI boundaries).
- [ ] Accessible name/role/state provided; decorative icons hidden from AT.
- [ ] No forbidden visual patterns (glow, glass, blobs, emoji icons, oversized rounding).
- [ ] No duplicated markup where an existing component applies.

---

## 4. Database Change DoD

- [ ] Change is reflected in `DATABASE-DESIGN.md` first.
- [ ] Migration/schema script is reviewed and reversible (or rollback documented).
- [ ] Constraints enforce documented rules where the engine supports them.
- [ ] Indexes map to real queries.
- [ ] Money uses exact types (no float).
- [ ] No destructive change without backup and owner approval.
- [ ] No data fabricated for demo purposes.

---

## 5. Documentation DoD

- [ ] Information is traceable to a source or explicitly marked `PROPOSED` / `TBD`.
- [ ] No invented requirement, test result, or status.
- [ ] Conflicts are recorded in `DECISIONS.md`, not silently resolved.
- [ ] Requirement IDs are unique and referenced consistently.
- [ ] Mermaid diagrams render without syntax errors.
- [ ] `CHANGELOG.md` updated for notable changes.

---

## 6. Release DoD (Gate 4 → 5)

- [ ] All `Must` requirements implemented and accepted.
- [ ] All `Must` acceptance criteria pass with evidence.
- [ ] No open Critical/High defects.
- [ ] Security checklist completed (`SECURITY-GUIDELINES.md` §12).
- [ ] Responsive and accessibility checks completed.
- [ ] Performance targets measured and recorded.
- [ ] Deployment checklist completed.
- [ ] Rollback plan documented.
- [ ] Owner approval recorded.

---

## 7. What "Done" Never Means

- "The page loads" without state handling.
- "It works on my machine" without recorded evidence.
- "Tests would pass" without execution.
- "Documented" without traceability.
- "Temporary" code left in place without a tracked decision.
- A feature marked done while a blocking `TBD` remains open.

---

## 8. Evidence Format

| Change type | Minimum evidence |
|---|---|
| Feature | Executed acceptance criteria + screenshots for key states |
| Bug fix | Before/after reproduction result |
| UI component | Screenshots at 360px and 1280px + keyboard check |
| Database | Query plan or result sample + migration applied in a test environment |
| Security | Test payload result (e.g., escaped XSS output) |
| Documentation | Review note in `CHANGELOG.md` |

Evidence is stored in the project's chosen location (`TBD`); no evidence location has been created during the documentation phase.
