# NAVORA Documentation Index

**Documentation revision:** 0.1.0 · **Date:** 2026-11-22 · **Status:** draft, awaiting owner approval

This index explains what each document is for, who should read it, and what is still unresolved. It is the entry point for humans and AI agents.

---

## Status Legend

| Label | Meaning |
|---|---|
| `CONFIRMED` | Stated directly in the project brief or verified in the repository |
| `PROPOSED` | Drafted here; requires owner approval |
| `TBD` | Unresolved; blocks dependent work |
| `DRAFT` | Written but not approved |
| `NOT VERIFIED` | No execution evidence exists |

---

## 1. Product (`docs/01-product/`)

| Document | Purpose | Read if you are… | Status |
|---|---|---|---|
| [BRD.md](01-product/BRD.md) | Business background, problem, objectives, users, scope, benefits, constraints, stakeholders, success indicators, risks | Product owner, reviewer | Draft |
| [PRD.md](01-product/PRD.md) | Product goals, personas, 45 functional requirement IDs (`FR-001..FR-045`, one superseded), 15 non-functional requirements (`NFR-###`), user stories, MVP scope, future candidates, out-of-scope list | Everyone | Draft — approved decisions applied; package awaiting sign-off |
| [SRS.md](01-product/SRS.md) | System context, actors, behavior, inputs/outputs, validation, errors, performance, security, integrations | Developer, tester | Draft — approved behavior applied; stack `OPEN` |
| [BUSINESS-RULES.md](01-product/BUSINESS-RULES.md) | 56 numbered rules (`RULE-001..RULE-056`) for goals, transactions, balance, progress, completion, archive, ownership | Developer, tester, owner | Draft — approved financial rules applied; details `OPEN` |
| [FEATURE-SPECIFICATION.md](01-product/FEATURE-SPECIFICATION.md) | Feature-level flows (`FS-01..FS-11`), CRUD matrix, pagination decisions, destructive-action and feedback policy | Developer, designer | Draft |

**Start here:** BRD → PRD → BUSINESS-RULES.

---

## 2. Design (`docs/02-design/`)

| Document | Purpose | Status |
|---|---|---|
| [UI-UX-GUIDELINES.md](02-design/UI-UX-GUIDELINES.md) | Experience principles, information architecture, layout, navigation, page patterns, states, forms, accessibility, motion | Draft |
| [DESIGN-SYSTEM.md](02-design/DESIGN-SYSTEM.md) | Navora Blue palette with measured contrast, neutrals, semantics, typography, spacing, radius, shadows, borders, icons, states, motion tokens | **Proposal** |
| [COMPONENT-SPECIFICATION.md](02-design/COMPONENT-SPECIFICATION.md) | 24 component contracts (`CMP-01..CMP-24`) with data, behavior, responsive and state requirements | Draft |
| [USER-FLOW.md](02-design/USER-FLOW.md) | 12 Mermaid flowcharts (`UF-01..UF-12`) including error and alternative paths | Draft |
| [WIREFRAME-SPECIFICATION.md](02-design/WIREFRAME-SPECIFICATION.md) | Low-fidelity layouts (`WF-01..WF-14`) for desktop and mobile | Draft |

**Start here:** DESIGN-SYSTEM → USER-FLOW → WIREFRAMES.

---

## 3. Technical (`docs/03-technical/`)

| Document | Purpose | Status |
|---|---|---|
| [TECHNICAL-ARCHITECTURE.md](03-technical/TECHNICAL-ARCHITECTURE.md) | Verified facts vs inferred/proposed architecture, request lifecycle, routing, data flow, errors, deployment risks | Draft — stack unconfirmed |
| [DATABASE-DESIGN.md](03-technical/DATABASE-DESIGN.md) | Proposed ERD, tables, DDL, derived queries, indexes, integrity, deletion options | **Proposal — not executed** |
| [API-SPECIFICATION.md](03-technical/API-SPECIFICATION.md) | Proposed HTTP routes, request contracts, status codes, authorization; server-rendered boundary proposed, JSON/REST API `PENDING` (`DEC-017`) | **Proposal — not implemented** |
| [SECURITY-GUIDELINES.md](03-technical/SECURITY-GUIDELINES.md) | Threat model, auth, sessions, authorization, input/output, CSRF, headers, secrets, privacy, deployment checklist | Draft |

**Start here:** TECHNICAL-ARCHITECTURE → DATABASE-DESIGN → SECURITY-GUIDELINES.

---

## 4. Project Management (`docs/04-project-management/`)

| Document | Purpose | Status |
|---|---|---|
| [DOCUMENTATION-INVENTORY.md](04-project-management/DOCUMENTATION-INVENTORY.md) | **Historical audit snapshot** (initial state: 0 documents, 45 empty source files); current inventory is 23 files under `docs/` plus 3 root documents | Complete — historical |
| [ROADMAP.md](04-project-management/ROADMAP.md) | Waterfall stages, activities, deliverables, exit criteria, milestones | Draft — awaiting Requirements gate |
| [PROGRESS.md](04-project-management/PROGRESS.md) | Honest phase and feature status, verification log, blockers | Living |
| [DECISIONS.md](04-project-management/DECISIONS.md) | 26 recorded decision IDs (`DEC-001..DEC-026`; 16 owner-approved), 39 conflicts (`C-01..C-39`: 28 resolved, 10 OPEN, 1 proposed), 20 product and 12 technical OPEN items | Draft — approved decisions binding; pending/proposed items not binding |
| [CHANGELOG.md](04-project-management/CHANGELOG.md) | Documentation revision history | Living |

**Start here:** DOCUMENTATION-INVENTORY → DECISIONS → PROGRESS.

---

## 5. Quality (`docs/05-quality/`)

| Document | Purpose | Status |
|---|---|---|
| [TESTING-PLAN.md](05-quality/TESTING-PLAN.md) | Levels, environments, 107 test cases (`TC-###`), boundaries, defects, entry/exit criteria | Plan only — nothing executed |
| [ACCEPTANCE-CRITERIA.md](05-quality/ACCEPTANCE-CRITERIA.md) | 58 Given/When/Then criteria (`AC-01..AC-58`); 9 open/proposed/blocked (`AC-12`, `AC-15`, `AC-17`, `AC-19`, `AC-24`, `AC-35`, `AC-52`, `AC-53`, `AC-57`); Must traceability in §11 | Draft — 0 verified |
| [DEFINITION-OF-DONE.md](05-quality/DEFINITION-OF-DONE.md) | Universal, feature, component, database, documentation, and release DoD | Draft |

---

## 6. Repository-Level Documents

| Document | Purpose |
|---|---|
| [`../README.md`](../README.md) | Project overview and honest status |
| [`../AGENTS.md`](../AGENTS.md) | Persistent rules for AI coding sessions |
| [`../PROMPT-BUILD-NAVORA.md`](../PROMPT-BUILD-NAVORA.md) | Ready-to-use implementation prompt, gated on approval |

---

## 7. Traceability Model

```text
SRC-00 brief ──► BRD ──► PRD (FR/NFR) ──► SRS ──► BUSINESS-RULES (RULE)
                                   │
                                   ├──► DESIGN-SYSTEM / COMPONENTS / FLOWS / WIREFRAMES
                                   ├──► ARCHITECTURE / DATABASE / API / SECURITY
                                   └──► TESTING-PLAN (TC) / ACCEPTANCE (AC) / DoD
```

Every requirement ID is referenced by at least one design, technical, or quality document. Where a requirement depends on an unresolved decision, the dependency is named explicitly rather than guessed.

---

## 8. What Is Still Missing

| Gap | Blocking? |
|---|---|
| Owner approval/sign-off of the complete requirements package | Yes (Requirements gate) |
| Owner approval of proposed Must requirements `FR-026`, `FR-028`, `FR-034` | Yes — package not ready until resolved |
| PHP version and database engine (`TECH-01`, `TECH-02`) | Yes (implementation) |
| Money column type and maximum amount (`TECH-03`) | Yes (schema) |
| Design token approval (`DEC-010`, `DEC-019`) | Yes (design gate) |
| Transaction edit/delete policy (`OPEN-01`, `OPEN-02`) | No — deferred outside MVP |
| Page size and invalid-page fallback (`C-20`, `C-19`) | No — draft defaults must not be presented as approved |
| Email provider and token parameters (`TECH-06`, `OPEN-09`) | Before implementation of account security |
| Document root/deployment target (`TECH-09`) | Before deployment |
| High-fidelity visual mockups | No (not requested) |
| TailAdmin inspection | No (reference only, not accessed) |

Approved product decisions (`DECISIONS.md` §1) are **not** gaps. Items in `DECISIONS.md` §4/§5 remain OPEN and must not be silently resolved.

---

## 9. Maintenance Rules

- Update the relevant document **before** changing behavior.
- Record every new decision in `DECISIONS.md` with a status.
- Never mark an acceptance criterion or test as passed without evidence.
- Keep `PROGRESS.md` truthful; a draft document is not an approved one.
- Do not create duplicate documents; extend the existing one.
