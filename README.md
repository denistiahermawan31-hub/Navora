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
- [Decision Status](#decision-status)
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
7. `docs/04-project-management/DECISIONS.md` — approved decisions and what is still undecided.
8. `docs/05-quality/ACCEPTANCE-CRITERIA.md` — how completion is judged.

---

## Decision Status

**Approved by the owner (2026-11-22; recorded in `DECISIONS.md` §1):** DEC-003 (multi-user accounts, private data), DEC-004 and DEC-025 (deposits, withdrawals, negative balances), DEC-006 (automatic status), DEC-007 (IDR, no decimals), DEC-008 (archive/restore, history retained), DEC-009 (numbered pagination for goals and history), DEC-011 (goal names unique per account), DEC-012 (Hampir Tercapai at 90–99%), DEC-013 (Bahasa Indonesia), DEC-014 (Asia/Jakarta), DEC-015 (email verification and password reset), DEC-016 (productivity modules excluded), DEC-023 (over-saving), DEC-024 (MVP Scope A), DEC-026 (past target dates allowed).

These are product decisions, **not** approval of the requirements package as a whole. The Requirements gate remains pending owner sign-off.

Open implementation parameters include PHP version (`TECH-01`), database engine (`TECH-02`), money column type (`TECH-03`), page size (`C-20`), invalid-page fallback (`C-19`), token lifetime and single-use rules (`OPEN-09`), email provider (`TECH-06`), and hosting/document root (`TECH-09`). Full register: `docs/04-project-management/DECISIONS.md` §4 and §5.

---

## Methodology

Waterfall with formal stage gates is the proposed methodology (`DEC-022` pending): Requirements → Design → Development → Testing → Deployment → Maintenance. No stage starts before the previous one is approved. See `docs/04-project-management/ROADMAP.md`.

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
