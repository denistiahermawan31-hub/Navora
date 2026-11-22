# NAVORA — Decision & Conflict Register

| Field | Value |
|---|---|
| Document ID | NAVORA-DEC |
| Version | 0.2.2 |
| Date | 2026-11-22 |
| Status | **Owner-approved product and palette decisions recorded; technical items remain OPEN** |
| Owner | Project Owner (`denistiahermawan31-hub`) |

## Status Legend

| Status | Meaning |
|---|---|
| `APPROVED` | Explicitly confirmed by the project owner. Binding. |
| `PROPOSED` | Drafted for approval; not binding yet. |
| `PENDING` | Awaiting owner or technical confirmation; not binding. |
| `OPEN` / `TBD` | Unresolved; blocks the dependent work. |
| `PARTIALLY RESOLVED` | Legacy label: some aspects settled, at least one still unresolved. Retired in 0.2.1; present only for historical traceability. |
| `DEFERRED` | Intentionally postponed beyond the current milestone. |
| `SUPERSEDED` | Replaced by a newer decision; kept for history. |
| `OUT` | Explicitly excluded from NAVORA. |

> The 16 owner-approved product decisions and 1 owner-approved design decision below are the **latest source of truth**. Where they conflict with an earlier draft, the approved decision wins. No AI agent may approve or alter them.

---

## 1. Owner-Approved Product Decisions (2026-11-22)

| ID | Decision | Status | Primary impact |
|---|---|---|---|
| **DEC-003** | NAVORA is **multi-user**. Every user has an account and a personal dashboard. Data must be isolated per user. | `APPROVED` | Auth, schema, all queries, flows |
| **DEC-004** | Users can record **deposits and withdrawals**. | `APPROVED` | Transaction model, balance, UI |
| **DEC-006** | Goal status follows the current balance automatically: at/above target = **Tercapai**; below target = **Berlangsung**. | `APPROVED` | Status, dashboard, flows |
| **DEC-007** | Single currency **IDR**, **no decimals**, thousands separator is a dot, format `Rp15.000.000`. | `APPROVED` | Money type, validation, formatting |
| **DEC-008** | Goal deletion uses **archive (soft delete)**. Transaction history is retained. Archived goals are not active and can be restored. | `APPROVED` | Schema, delete/restore UI, counts |
| **DEC-009** | **Numbered pagination** for both the goal list and transaction history. | `APPROVED` | List/history UI, queries |
| **DEC-011** | Goal names are **unique within one account**; different users may reuse a name. | `APPROVED` | Validation, unique index |
| **DEC-012** | **Hampir Tercapai** indicator appears at progress ≥ 90% and < 100%. | `APPROVED` | Dashboard, cards, status UI |
| **DEC-013** | The entire user interface uses **Bahasa Indonesia**. | `APPROVED` | All UI copy, errors, dates |
| **DEC-014** | Fixed timezone **Asia/Jakarta (WIB)** for all users. | `APPROVED` | Date validation, display, timestamps |
| **DEC-015** | Account security includes **email verification** and **password reset via email link**. | `APPROVED` | Auth flow, schema, email delivery |
| **DEC-016** | Habits, tasks, notes, focus, and planning are **removed from NAVORA scope**. NAVORA focuses only on personal savings and goals. | `APPROVED` | Scope, navigation, routing |
| **DEC-023** | **Over-saving is allowed.** Balance records the actual amount; visual progress caps at 100%; remaining target becomes Rp0 after the target is reached; excess savings are displayed separately. | `APPROVED` | Balance, progress, detail UI |
| **DEC-024** | **MVP Scope A**: focus on core NAVORA features. Notifications, report export, and advanced statistics are deferred. | `APPROVED` | MVP boundary, roadmap |
| **DEC-025** | **Withdrawals may exceed the current balance.** Negative balances are allowed. | `APPROVED` | Validation, balance display |
| **DEC-026** | **Target dates in the past are allowed** when creating or editing a goal. | `APPROVED` | Validation, date policy |

> DEC-023..DEC-026 are derived records created during consolidation for approved items that the owner listed without a decision ID. They do not add new behavior.

### 1.1 Approved decision details

#### DEC-003 — Multi-user authentication
- Every user registers an account (name, email, password).
- Each user has a private dashboard and private goal set.
- Every goal and transaction is owned by exactly one user.
- Cross-user access returns "not found" (no data leakage).
- The former single-user alternative is withdrawn.

#### DEC-004 / DEC-025 — Deposits, withdrawals, negative balance
- A transaction has a direction: **Setoran** (deposit) or **Penarikan** (withdrawal).
- Amount is always a positive IDR integer; direction determines the sign.
- A withdrawal may exceed the current balance.
- A negative balance is valid and must be displayed clearly.
- No overdraft fee, interest, or credit calculation is implied.

#### DEC-007 — IDR formatting
- Currency: Indonesian Rupiah only.
- No decimal places: `Rp15.000.000`, not `Rp15.000.000,00`.
- Thousands separator: dot (`.`).
- Symbol: `Rp` directly followed by the number (no space) for the approved example.
- Negative-value formatting is **OPEN** (see §4).

#### DEC-008 — Archive, retain, restore
- "Delete goal" becomes **"Arsipkan goal"**.
- Transactions are never deleted by archiving.
- Archived goals are excluded from active/default lists and active counts.
- Archived goals appear in an archive view and can be restored.
- Hard delete is **not part of MVP**.
- Whether an archived goal can receive new transactions before restore is **OPEN**.

#### DEC-006 / DEC-023 — Automatic status and over-saving
- `status = Tercapai` when `balance >= target`.
- `status = Berlangsung` when `balance < target`.
- `balance = total deposits - total withdrawals`.
- Over-saving is allowed; the balance is not capped.
- Visual progress = clamp(balance / target × 100, 0, 100).
- `remaining = max(0, target - balance)`.
- `excess = max(0, balance - target)`, displayed separately.
- If a withdrawal drops the balance below target, status returns to **Berlangsung** automatically.

#### DEC-011 — Unique goal names
- Unique per account, enforced by validation and a database constraint.
- Different accounts may use the same name.
- Case sensitivity and whitespace normalization are **OPEN** (see §4).

#### DEC-012 — Hampir Tercapai
- Applies when `90 <= progress < 100` and the goal is not archived.
- A goal at 100% is **Tercapai**, not **Hampir Tercapai**.
- A goal below 90% is **Berlangsung** without the indicator.
- Indicator is supplementary; it does not change the stored status.

#### DEC-013 / DEC-014 — Indonesian UI and WIB
- All user-facing copy is Bahasa Indonesia (labels, buttons, errors, empty states, emails).
- Dates are presented in Indonesian format and interpreted in Asia/Jakarta.
- "Today" and "not in the future" checks use WIB.
- Technical storage format (UTC vs local) is **OPEN** (see §4).

#### DEC-015 — Email verification and password reset
- Registration sends a verification email.
- Password reset is initiated by email and completed through a time-limited link.
- Whether login is blocked before verification is **OPEN**.
- Token lifetime, single-use policy, and email provider are **OPEN** (see §4).

### 1.2 Owner-Approved Design Decision

| ID | Decision | Status | Primary impact |
|---|---|---|---|
| **DEC-010** | NAVORA uses the exact 11-step BESTCO primary blue scale for its brand color tokens. No exact value may be desaturated, darkened, renamed, or substituted. | `APPROVED` | Design tokens, UI |

#### DEC-010 — Brand palette

- Approved by the Project Owner on 2026-11-22.
- Reference: `BestCO/resources/css/app.css:11-21`; mirrored in `app/Services/Theme/DynamicThemeService.php:13-24` and `app/Http/Controllers/ThemeController.php:20-30`.
- Approved values: `50 #eff6ff`, `100 #dbeafe`, `200 #bfdbfe`, `300 #93c5fd`, `400 #60a5fa`, `500 #3b82f6`, `600 #2563eb`, `700 #1d4ed8`, `800 #1e40af`, `900 #1e3a8a`, `950 #172554`.
- NAVORA CSS token names remain `--nv-blue-*`; the hex values are identical to BESTCO's `--color-primary-*` values.
- Neutrals and semantic status colors remain NAVORA-specific and must not introduce a second primary blue palette.
- This decision does **not** approve typography/icons (`DEC-019`), gradients, glow, glassmorphism, dark mode, dynamic themes, or application implementation.

---

## 2. Renumbering & Supersession Map

The owner's approved product decisions reused IDs that earlier drafts had assigned to pending technical/design topics. To avoid ambiguity, the earlier entries were renumbered. Their content is preserved under the new IDs.

| Former ID | Former topic | New ID | Status |
|---|---|---|---|
| DEC-011 | Typography and icon library | **DEC-019** | `PENDING` |
| DEC-012 | GSAP adoption | **DEC-020** | `DEFERRED` |
| DEC-013 | TailAdmin as inspiration only | **DEC-021** | `PENDING` (no owner approval record; reference not inspected) |
| DEC-014 | Waterfall methodology | **DEC-022** | `PENDING` |
| DEC-015 | Single-user vs multi-user data model | merged into **DEC-003** | `SUPERSEDED` |
| DEC-016 | Legacy modules | **DEC-016** | `APPROVED` (same topic) |

> No approved product behavior was lost in this renumbering. Only draft technical/design entries moved.

---

## 3. Other Decisions (Not Owner-Approved as Product Behavior)

| ID | Topic | Status | Note |
|---|---|---|---|
| DEC-001 | Documentation at repository root, code in `NAVORA/` | `PENDING` | Low risk; confirm later |
| DEC-002 | Keep the custom PHP MVC scaffold; no framework migration | `PENDING` | Technical baseline |
| DEC-005 | Balance derived from transactions, not manually editable | `APPROVED (derived behavior) / OPEN (implementation)` | Behavior follows from DEC-004/006/023; not an independently owner-approved ID. Storage/query detail open. |
| DEC-017 | No JSON API in MVP; server-rendered HTTP | `PENDING` | Technical; server-rendered is a proposed baseline, not an approved exclusion |
| DEC-018 | Non-brief requirements are `PROPOSED` unless approved | `PENDING` | Documentation policy; formerly labelled `APPROVED` in draft but absent from §7 |
| DEC-019 | Typography (Inter/Sora) and icons (Lucide) | `PENDING` | Renumbered from former DEC-011 |
| DEC-020 | GSAP optional | `DEFERRED` | Renumbered from former DEC-012 |
| DEC-021 | TailAdmin as inspiration only, not copied | `PENDING` | Renumbered from former DEC-013; formerly labelled `APPROVED` (principle) but absent from §7; `SRC-03` not inspected |
| DEC-022 | Waterfall with approval gates | `PENDING` | Renumbered from former DEC-014 |

### 3.1 Approval-status discrepancies resolved in 0.2.1

- **DEC-005:** the rule that balance is derived from transactions is approved **as a consequence** of DEC-004/DEC-006/DEC-023 (`RULE-025`, `PRD FR-018`). It is not an independently owner-approved decision ID and is therefore not listed in §7. Implementation details remain `OPEN`.
- **DEC-018 and DEC-021:** earlier drafts marked these `APPROVED`, but §7 contains no owner-approval record. Their status is now `PENDING`. No behavior changed; only the status claim was corrected.
- **C-30:** `200` re-render is a documented technical convention in `API-SPECIFICATION.md`, but no owner approval is recorded. Its conflict status is now `PROPOSED`, not `RESOLVED`.
- **DEC-002 and DEC-017:** both remain `PENDING`. Other documents must not present them as final decisions; wording has been corrected accordingly.
- **DEC-010:** owner-approved as the exact BESTCO primary blue scale and recorded in §1.2 and §7. It is no longer in the `PENDING` set.
- **All other `PENDING` items:** excluded from approved lists. Cross-document references use the explicit owner-approved ID list from §7 instead of a numeric range.

No approval evidence has been invented; where approval cannot be verified, the status is `PENDING` or `PROPOSED`.

---

## 4. OPEN / TBD Register

These items are **not** decided by the approved product decisions. They must not be treated as final.

### 4.1 Product behavior still open

| ID | Question | Blocks | Proposed default (not approved) |
|---|---|---|---|
| OPEN-01 | Can a savings transaction be edited? | FR-022 | Defer to post-MVP |
| OPEN-02 | Can a savings transaction be deleted? | FR-023 | Defer to post-MVP; if added, recompute status |
| OPEN-03 | Is goal description in MVP? | FR-007 | Optional field |
| OPEN-04 | Is transaction note in MVP? | FR-017 | Optional field |
| OPEN-05 | Are search, sort, and status filters in MVP? | FR-012..FR-014 | Search/sort `Should`; filters `Could` |
| OPEN-06 | Exact dashboard widgets beyond approved metrics | FR-026 | Active goals, total saved, total remaining, nearly reached, recent activity |
| OPEN-07 | Is a Settings page needed in MVP? | Former FR-028 | Defer; no settings scope approved |
| OPEN-08 | Is login blocked until email is verified? | DEC-015 | Require verification before full access |
| OPEN-09 | Password reset token lifetime and single-use rules | DEC-015 | Single-use, 60 minutes |
| OPEN-10 | Password minimum length and policy | DEC-015 | Minimum 8 characters |
| OPEN-11 | Session lifetime and "remember me" | DEC-003 | Session cookie; remember-me deferred |
| OPEN-12 | Can an archived goal receive transactions before restore? | DEC-008 | Block; restore first |
| OPEN-13 | Is there an "overdue" indicator for past target dates? | DEC-026 | Display date only; no overdue badge in MVP |
| OPEN-14 | Case sensitivity of unique goal names | DEC-011 | Case-insensitive, trimmed |
| OPEN-15 | Percentage display precision | DEC-012 | Integer percent for display |
| OPEN-16 | Negative money display format | DEC-007 | `-Rp2.000.000` |
| OPEN-17 | Landing page for visitors | Auth flow | Resolved by DEC-027: Public Landing Page approved; guest sees `/`, authenticated sees `/dashboard` |
| OPEN-18 | Data retention/purge policy for archived goals | DEC-008 | Retain indefinitely in MVP |
| OPEN-19 | Rate limiting for login/reset | Security | Defer; document as risk |
| OPEN-20 | Privacy notice and consent | Legal | TBD |

### 4.2 Technical decisions still open

| ID | Question | Blocks | Status |
|---|---|---|---|
| TECH-01 | PHP version | Implementation | `OPEN` |
| TECH-02 | Database engine and version | Schema/DDL | `OPEN` |
| TECH-03 | Money column type (`BIGINT` vs `DECIMAL`) and maximum amount | Schema | `OPEN` — integer IDR proposed |
| TECH-04 | Status storage: derived in query vs cached column | Schema/query | `OPEN` — derived proposed |
| TECH-05 | Timestamp storage (UTC) vs WIB | Schema/display | `OPEN` — UTC store + WIB display proposed |
| TECH-06 | Email delivery provider/SMTP | Verification/reset | `OPEN` |
| TECH-07 | Session storage and cookie configuration | Auth | `OPEN` |
| TECH-08 | Database migration tooling | Deployment | `OPEN` |
| TECH-09 | Hosting/document root strategy | Deployment | `OPEN` |
| TECH-10 | Automated test framework | Quality | `OPEN` |
| TECH-11 | Whether a JSON API is needed later | Architecture | `OPEN` (MVP server-rendered) |
| TECH-12 | Icon/font delivery (self-hosted vs package) | Design implementation | `OPEN` |

---

## 5. Conflict Register

| ID | Conflict | Resolution | Status |
|---|---|---|---|
| C-01 | Legacy modules (habits/tasks/notes/focus/planner/analytics) vs savings-only scope | DEC-016: excluded from scope; files preserved, not routed | `RESOLVED` |
| C-02 | Legacy `DAYORA` naming (`database/dayora.sql`) | No rename yet; cleanup requires approval | `OPEN` (low) |
| C-03 | `Goal` vs `DreamSavings` PHP model naming | Product term is "Dream Goal"; table `dream_goals` is a draft schema choice; PHP class choice remains `OPEN`. No implementation file has content; owner or technical approval is required to select one class. | `OPEN` |
| C-04 | Auth present in scaffold vs "if approved" | DEC-003: multi-user approved | `RESOLVED` |
| C-05 | Analytics module vs scope | DEC-016 / DEC-024: out of scope; advanced statistics deferred | `RESOLVED` |
| C-06 | Documentation root placement | DEC-001 pending | `OPEN` (low) |
| C-07 | Decision ID collisions between drafts and owner decisions | Renumbered per §2 | `RESOLVED` |
| C-08 | Dashboard metric definitions differed across PRD/SRS/FS | Consolidated in PRD FR-026 / SRS §4.4 / FS-09 (exact widget set remains `OPEN-06`) | `RESOLVED` |
| C-09 | Goal pagination: numbered vs "Load more" | DEC-009: numbered pagination for goals and history | `RESOLVED` |
| C-10 | Transaction CRUD status differed across documents | Edit/delete remain OPEN-01/OPEN-02; creation and reading are in scope | `OPEN` |
| C-11 | Archived vs soft delete vs hard delete | DEC-008: archive only in MVP; hard delete out | `RESOLVED` |
| C-12 | Over-saving "permitted pending" vs TBD | DEC-023: approved with excess display | `RESOLVED` |
| C-13 | Currency/multi-currency ambiguity | DEC-007: single IDR, no decimals; multi-currency out | `RESOLVED` |
| C-14 | Goal images open question vs deferred | Images remain deferred/out of MVP (DEC-024); no upload | `RESOLVED` |
| C-15 | Auth field mismatch (password confirmation) | Confirmation retained as UI/validation detail; not a product conflict | `RESOLVED` |
| C-16 | Settings navigation without a settings feature | Settings deferred (OPEN-07) | `RESOLVED` (removed from MVP) |
| C-17 | 403 vs 404 for unauthorized access | 404 for missing and unauthorized; no admin role | `RESOLVED` |
| C-18 | SRS diagram "Apache" vs server TBD | Server remains `OPEN`; diagram corrected to "web server (TBD)" | `RESOLVED` |
| C-19 | Invalid page fallback (page 1 vs last page) | No owner-approved value exists. Draft policy kept for reference: non-numeric/`0` → page 1; numeric out-of-range → last valid page. Must stay `OPEN` until approved. | `OPEN` |
| C-20 | Page size 20 vs 20–24 | `DEC-009` approves **numbered pagination only**. No page size was approved. `20` is a documented draft default used in examples/tests, not a binding value. | `OPEN` |
| C-21 | Search/sort state lost across pagination | Query state preserved in pagination links | `RESOLVED` |
| C-22 | Timezone absent | DEC-014: Asia/Jakarta | `RESOLVED` |
| C-23 | English UI assumption | DEC-013: Bahasa Indonesia | `RESOLVED` |
| C-24 | Account recovery missing | DEC-015: verification + reset | `RESOLVED` (details OPEN) |
| C-25 | Landing page mentioned but undefined | Resolved by DEC-027: Public Landing Page approved as part of MVP | `RESOLVED` |
| C-26 | BRD approval list incomplete | Updated to include all approved decisions | `RESOLVED` |
| C-27 | Target date past policy / Q-07 | DEC-026: past dates allowed; optional retained | `RESOLVED` |
| C-28 | Goal name uniqueness | DEC-011: unique per account | `RESOLVED` |
| C-29 | "Nearing completion" threshold | DEC-012: 90% ≤ progress < 100% | `RESOLVED` |
| C-30 | 422 vs 200 on validation failure | Draft technical convention: re-render the form with errors using `200` for HTML form submissions; no 422 JSON responses in the proposed MVP. No owner approval record exists. | `PROPOSED` |
| C-31 | Automated tests promised without tooling | NFR-013 retained as target; test tool `OPEN` | `OPEN` (technical) |
| C-32 | Administrator actor `TBD` | Out of scope; no admin role | `RESOLVED` |
| C-33 | User story coverage gaps | Added stories for archive/restore, withdrawals, auth, indicator | `RESOLVED` |
| C-34 | Feature-spec open questions outside PRD Q list | Moved to OPEN register above | `RESOLVED` |
| C-35 | Dashboard totals across currencies | Single IDR; no mixing | `RESOLVED` |
| C-36 | Archive/restore UI missing | Added FR-036 and archive view | `RESOLVED` |
| C-37 | Concurrency handling | `OPEN` technical; no optimistic locking in MVP | `OPEN` |
| C-38 | Rate limiting | `OPEN` security | `OPEN` |
| C-39 | Privacy/retention policy | `OPEN` | `OPEN` |

> **Note:** C-19 (invalid-page fallback) and C-20 (page size) concern implementation parameters. `DEC-009` approves numbered pagination for goals and history but fixes neither value, so both remain `OPEN`. Draft defaults (20 per page; page 1 for non-numeric input, last page for out-of-range numeric input) are documented for design/test consistency only and must not be reported as approved.
>
> **0.2.1 conflict status summary:** 28 `RESOLVED`, 10 `OPEN` (including C-03), 1 `PROPOSED` (C-30), 0 `PARTIALLY RESOLVED`. C-30 is consistent with the draft `API-SPECIFICATION.md` but is not owner-approved.

---

## 6. Decision Log Format

```markdown
### DEC-XXX — <short title>
- Date: YYYY-MM-DD
- Status: APPROVED | PROPOSED | PENDING | OPEN | DEFERRED | SUPERSEDED | OUT | PARTIALLY RESOLVED (legacy)
- Context: <evidence, links>
- Decision: <what was decided>
- Alternatives: <...>
- Impact: <schema, features, flows>
- Approved by: <owner/date, or PENDING>
```

AI agents may draft entries but may not set `APPROVED` for product, financial, security, or architectural decisions.

---

## 7. Approval Record

| Item | Approved by | Date |
|---|---|---|
| DEC-003, DEC-004, DEC-006, DEC-007, DEC-008, DEC-009, DEC-011, DEC-012, DEC-013, DEC-014, DEC-015, DEC-016, DEC-023, DEC-024, DEC-025, DEC-026 | Project Owner | 2026-11-22 |
| DEC-010 — exact BESTCO primary blue palette | Project Owner | 2026-11-22 |
| DEC-027 — Public Landing Page | Project Owner | 2026-11-22 |
| Public Landing Page — 6 design decisions approved | Project Owner | 2026-11-22 |
| Documentation consolidation into BRD/PRD/SRS/rules/features/quality docs | Project Owner instruction (this task) | 2026-11-22 |
| Typography and icons (`DEC-019`), Waterfall (`DEC-022`), API approach (`DEC-017`) | Not yet approved | — |

> The expanded product list above is the same owner-approved set recorded in 0.2.0; only the shorthand ranges were expanded. `DEC-010` is a separately owner-approved design decision recorded in §1.2, not a product-behavior decision. `DEC-005` is derived behavior, not an independently approved ID. `DEC-018`, `DEC-021`, and C-30 have no owner-approval record and are not included above.

## 1.2.2 Public Landing Page — 6 Design Decisions (DEC-027 Implementation)

| Item | Decision | Basis |
|---|---|---|
| Hero headline | "Menabung jadi lebih terarah." | DEC-027; FR-046; BS-12 |
| Hero visual | SVG abstrak target/progres/koin; tanpa angka/data pengguna | DEC-010; DEC-019; no financial data on landing |
| Ikon feature card | Hanya ikon dari allowlist DEC-019; tidak menambah ikon baru | DEC-019 Option A; CMP-01/CMP-01b |
| Footer | Wordmark `◎ NAVORA` + © 2026 NAVORA; tanpa link kebijakan/syarat | DEC-013; DEC-027; minimal design |
| Mobile drawer | Hamburger `aria-label="Buka menu"`; drawer: Tentang, Fitur, Cara Kerja, Masuk, Daftar; tutup Escape/backdrop; fokus dikembalikan ke hamburger; `[ ← ]` dihapus | DEC-003; DEC-013; accessibility |
| CTA | `Daftar` primary, `Masuk` secondary | DEC-027; FR-046; satu primary action per region |
