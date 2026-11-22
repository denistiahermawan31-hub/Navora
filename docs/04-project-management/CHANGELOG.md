# NAVORA — Changelog

All notable changes to project documentation and code are recorded here.
Format follows [Keep a Changelog](https://keepachangelog.com/) conventions.
The project does not yet follow semantic versioning; versions are documentation revisions.

---

## [Unreleased]

### Planned
- Resolution of open decisions DEC-003, DEC-004, DEC-006, DEC-007, DEC-008, DEC-016.
- Design approval (palette, typography, icons).
- Technical stack confirmation (PHP version, database engine, document root).
- Implementation of the MVP (blocked until Requirements gate approval).

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
