# NAVORA

**A web-based personal savings and dream-goal management application.**

NAVORA helps people save toward specific purchases. A user creates a dream goal, sets a target price, records savings over time, and sees exactly how much has been saved and how much remains.

> **Project status: documentation foundation only.** The application is **not implemented**. Every file under `NAVORA/` is an empty placeholder (0 bytes). There is no runnable build, no database schema, and no dependency manifest. Do not expect the application to start.

---

## Table of Contents

- [Current State](#current-state)
- [Intended Stack](#intended-stack)
- [Repository Layout](#repository-layout)
- [Documentation](#documentation)
- [Reading Order](#reading-order)
- [Open Decisions](#open-decisions)
- [Methodology](#methodology)
- [Contributing](#contributing)
- [License](#license)

---

## Current State

| Area | Status |
|---|---|
| Documentation | Draft complete — awaiting owner approval |
| Application code | 0% — 45 empty files |
| Database schema | Not created (`database/dayora.sql` is empty) |
| Tests | None |
| Dependencies | None installed |
| Deployment | Not configured |

**Verified on 2026-11-22.** See `docs/04-project-management/DOCUMENTATION-INVENTORY.md` for the full audit and `docs/04-project-management/PROGRESS.md` for honest progress.

---

## Intended Stack

| Layer | Intended | Verified? |
|---|---|---|
| Language | PHP | File extensions only; version `TBD` |
| Architecture | Custom MVC with a front controller | Inferred from folder structure |
| Views | Server-rendered PHP templates | Inferred |
| Database | Relational SQL | Engine `TBD` |
| Front end | Hand-written CSS + vanilla JS | Inferred; files empty |
| Package manager | None | Confirmed absent |

No framework (Laravel, CodeIgniter, Symfony) and no JS framework is present or approved. Technology choices require owner confirmation (`docs/04-project-management/DECISIONS.md`, DEC-002).

---

## Repository Layout

```text
Navora/
├── AGENTS.md                        # Rules for AI coding sessions
├── README.md                        # This file
├── PROMPT-BUILD-NAVORA.md           # Implementation-phase prompt (after approval)
├── docs/                            # Product, design, technical, PM, quality docs
│   ├── 01-product/
│   ├── 02-design/
│   ├── 03-technical/
│   ├── 04-project-management/
│   └── 05-quality/
└── NAVORA/                          # Application scaffold (all files empty)
    ├── index.php                    # Front controller (placeholder)
    ├── core/                        # App, Controller, Database (placeholders)
    ├── app/controllers/             # 10 controllers (placeholders)
    ├── app/models/                  # 9 models (placeholders)
    ├── app/views/                   # 15 views + layouts (placeholders)
    ├── config/database.php          # Placeholder
    ├── routes/web.php               # Placeholder
    ├── database/dayora.sql          # Placeholder (legacy name)
    └── public/css|js/               # Placeholders
```

Documentation lives at the repository root so that GitHub and AI tooling surface it; application code remains in `NAVORA/` (DEC-001).

---

## Documentation

| Folder | Contents |
|---|---|
| `docs/01-product` | BRD, PRD, SRS, business rules, feature specification |
| `docs/02-design` | UI/UX guidelines, design system, components, user flows, wireframes |
| `docs/03-technical` | Architecture, database design, HTTP interface, security |
| `docs/04-project-management` | Inventory, roadmap, progress, decisions, changelog |
| `docs/05-quality` | Testing plan, acceptance criteria, definition of done |

Start at [`docs/README.md`](docs/README.md) for the full index.

---

## Reading Order

1. `docs/01-product/BRD.md` — why the product exists.
2. `docs/01-product/PRD.md` — what will be built, with requirement IDs.
3. `docs/01-product/BUSINESS-RULES.md` — how money and records behave.
4. `docs/02-design/DESIGN-SYSTEM.md` — visual language.
5. `docs/02-design/USER-FLOW.md` — end-to-end behavior.
6. `docs/03-technical/TECHNICAL-ARCHITECTURE.md` — how it is structured.
7. `docs/04-project-management/DECISIONS.md` — what is still undecided.
8. `docs/05-quality/ACCEPTANCE-CRITERIA.md` — how completion is judged.

---

## Open Decisions

Development is **blocked** until these are approved:

| Decision | Question |
|---|---|
| DEC-003 | Are user accounts required? |
| DEC-004 | Are withdrawals allowed? |
| DEC-006 | How does a goal become completed? |
| DEC-007 | Which currency, and how is it formatted? |
| DEC-008 | What happens to transactions when a goal is deleted? |
| DEC-016 | What happens to the out-of-scope scaffold modules? |

Full register: `docs/04-project-management/DECISIONS.md`.

---

## Methodology

Waterfall with formal stage gates: Requirements → Design → Development → Testing → Deployment → Maintenance. No stage starts before the previous one is approved. See `docs/04-project-management/ROADMAP.md`.

---

## Contributing

1. Read `AGENTS.md` and the approved documentation.
2. Do not implement unapproved requirements.
3. Do not add dependencies without approval.
4. Update documentation and `PROGRESS.md` with any change.
5. Provide evidence for any claim that something works.

---

## License

`TBD` — no license file exists. Do not assume an open-source license.

---

## Contact / Ownership

Repository owner: `denistiahermawan31-hub`. Product owner approval is required for scope, financial rules, security, and architecture decisions.
