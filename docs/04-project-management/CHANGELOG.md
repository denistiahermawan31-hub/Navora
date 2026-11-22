# NAVORA — Changelog

All notable changes to project documentation and code are recorded here.
Format follows [Keep a Changelog](https://keepachangelog.com/) conventions.
The project does not yet follow semantic versioning; versions are documentation revisions.

---

## [Unreleased]

### Planned
- Owner sign-off of the consolidated requirements package (Requirements gate G1).
- Design approval (DEC-010 palette, DEC-019 typography and icons).
- Technical stack confirmation (TECH-01 PHP version, TECH-02 database engine, TECH-09 document root).
- Resolution of OPEN items that block the first feature, including C-19/C-20 and OPEN-09.
- Owner decision on proposed Must requirements `FR-026`, `FR-028`, and `FR-034`.
- Implementation of the MVP (blocked until Requirements gate approval).

---

## [0.2.1] — 2026-11-22

### Changed — Targeted post-review corrections
- Replaced shorthand approved-decision ranges in `BRD.md`, `ACCEPTANCE-CRITERIA.md`, and `TESTING-PLAN.md` with the explicit owner-approved ID list from `DECISIONS.md` §7.
- Clarified that DEC-005 is derived approved behavior, not an independently owner-approved ID.
- Changed DEC-018 and DEC-021 from draft `APPROVED` labels to `PENDING` because no owner approval record exists; no behavior changed.
- Kept DEC-002 and DEC-017 `PENDING` and corrected documents that presented them as final decisions.
- Changed C-30 to `PROPOSED` and C-03 to `OPEN`; added `PENDING` and legacy `PARTIALLY RESOLVED` definitions to the status legend.
- Marked proposed Must requirements `FR-026`, `FR-028`, and `FR-034` explicitly pending in `PRD.md`; requirements package remains not ready for sign-off.
- Added AC-57 (persistent navigation), AC-58 (persistence across sessions), explicit FR references for FR-018/019/020, and a Must → user story → RULE → AC traceability table.
- Updated AC/test-case counts to 58 criteria (9 open/proposed/blocked) and 107 planned cases; added TC-A-13 and TC-U-15.

### Notes
- No application source file, dependency, migration, or test was added or executed.
- Previous consolidation work was preserved; no revert, reset, commit, or push was performed.

---

## [0.2.0] — 2026-11-22

### Changed — Documentation consolidation
- Applied owner-approved decisions (DEC-003/004/006/007/008/009/011/012/013/014/015/016/023/024/025/026) consistently across governance, product, design, technical, and quality documents.
- Replaced stale "if approved"/`TBD` statements about authentication, currency, withdrawals, completion, archive, and scope exclusions.
- Corrected decision references: GSAP is DEC-020; typography/icons are DEC-019; waterfall is DEC-022.
- Kept page size (`C-20`), invalid-page fallback (`C-19`), token lifetime/single-use (`OPEN-09`), design tokens (`DEC-010`/`DEC-019`), and stack (`TECH-01`..`TECH-12`) explicitly `OPEN` instead of presenting draft defaults as approved.
- Aligned dashboard metric definitions across PRD FR-026, SRS §4.4, and FS-09; exact widget set remains `OPEN-06`.
- Clarified that individual approved decisions do not constitute approval of the requirements package.
- Renumbered `TESTING-PLAN.md` ID note in the docs index to the verified 105 test cases (`TC-A-01` etc.) and 56 acceptance criteria (3 open/blocked). Historical 0.2.0 counts; superseded in 0.2.1 by 107 test cases and 58 criteria (9 open/proposed/blocked).

### Notes
- No application source file, dependency, migration, or test was added or executed.
- `DOCUMENTATION-INVENTORY.md` remains a historical audit snapshot; current status is in `PROGRESS.md` and `docs/README.md`.

---

## [0.1.0] — 2026-11-22

### Added — Project audit
- `docs/04-project-management/DOCUMENTATION-INVENTORY.md`: full repository audit, source registry, scaffold inventory (45 empty files), conflicts, gaps, limitations.

### Added — Product documentation (drafts)
- `docs/01-product/BRD.md`
- `docs/01-product/PRD.md` (FR-001..FR-035, NFR-001..NFR-014)
- `docs/01-product/SRS.md`
- `docs/01-product/BUSINESS-RULES.md` (RULE-001..RULE-050)
- `docs/01-product/FEATURE-SPECIFICATION.md` (FS-01..FS-11, CRUD matrix, pagination decisions)

### Added — Design documentation (proposals)
- `docs/02-design/UI-UX-GUIDELINES.md`
- `docs/02-design/DESIGN-SYSTEM.md` (Navora Blue palette, verified contrast ratios, tokens, motion)
- `docs/02-design/COMPONENT-SPECIFICATION.md` (CMP-01..CMP-24)
- `docs/02-design/USER-FLOW.md` (UF-01..UF-12, Mermaid)
- `docs/02-design/WIREFRAME-SPECIFICATION.md` (WF-01..WF-14, ASCII layouts)

### Added — Technical documentation (proposals)
- `docs/03-technical/TECHNICAL-ARCHITECTURE.md`
- `docs/03-technical/DATABASE-DESIGN.md` (proposed ERD and DDL, not executed)
- `docs/03-technical/API-SPECIFICATION.md` (HTTP routes, not implemented)
- `docs/03-technical/SECURITY-GUIDELINES.md`

### Added — Project management
- `docs/04-project-management/ROADMAP.md` (Waterfall stages and gates)
- `docs/04-project-management/PROGRESS.md` (honest 0% implementation status)
- `docs/04-project-management/DECISIONS.md` (DEC-001..DEC-018, C-01..C-06)
- `docs/04-project-management/CHANGELOG.md` (this file)

### Added — Quality
- `docs/05-quality/TESTING-PLAN.md`
- `docs/05-quality/ACCEPTANCE-CRITERIA.md`
- `docs/05-quality/DEFINITION-OF-DONE.md`

### Added — Entry points
- `README.md`
- `AGENTS.md`
- `PROMPT-BUILD-NAVORA.md`
- `docs/README.md`

### Changed
- Nothing in `NAVORA/` was modified. All 45 source files remain untouched and empty.

### Removed
- Nothing.

### Notes
- No dependency was installed or upgraded.
- No test was executed; no test result is claimed.
- No documentation existed before this entry; nothing was overwritten, merged, or archived.

---

## Revision History Format

```markdown
## [x.y.z] — YYYY-MM-DD
### Added
### Changed
### Deprecated
### Removed
### Fixed
### Security
### Notes
```
