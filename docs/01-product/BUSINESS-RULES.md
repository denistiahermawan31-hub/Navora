# NAVORA — Business Rules

| Field | Value |
|---|---|
| Document ID | NAVORA-BR |
| Version | 0.2.0 (Consolidated) |
| Date | 2026-11-22 |
| Status | **Owner-approved financial rules applied; remaining items OPEN** |
| Related | `PRD.md`, `SRS.md`, `DATABASE-DESIGN.md`, `DECISIONS.md` |

## How to read this document

- Every rule has an ID (`RULE-###`), a status, and a source.
- `APPROVED` means the project owner confirmed the rule on 2026-11-22. It is binding.
- `PROPOSED` means it is a reasonable default drafted here and **must be approved**.
- `OPEN` / `TBD` means the rule cannot be finalized without an owner decision. The document describes the options and consequences — it does not choose one.

> **Approved financial rules.** Multi-user accounts, IDR with no decimals, deposits and withdrawals, automatic status, archive/restore, unique goal names, over-saving, negative balances, and past target dates are **decided** (`DECISIONS.md` §1). They are no longer `TBD`.

---

## 1. Terminology

| Term | Definition | Status |
|---|---|---|
| Dream Goal | A desired purchase with a name, target price, and optional target date | `APPROVED` (concept) |
| Target Price | The amount the user intends to accumulate for a goal, in IDR | `APPROVED` |
| Setoran (Deposit) | A recorded amount added toward a goal, with a date | `APPROVED` (DEC-004) |
| Penarikan (Withdrawal) | A recorded amount removed from a goal, with a date | `APPROVED` (DEC-004) |
| Saved Balance | Total deposits minus total withdrawals for a goal | `APPROVED` (RULE-020) |
| Remaining Amount | Target price minus saved balance, floored at zero | `APPROVED` (RULE-021) |
| Excess (Kelebihan) | Saved balance minus target price, floored at zero | `APPROVED` (RULE-023) |
| Progress | Saved balance relative to target price, capped at 100% for display | `APPROVED` (RULE-022) |
| Goal Status | `Berlangsung` (below target) or `Tercapai` (at/above target), derived from balance | `APPROVED` (RULE-029/030) |
| Archived Goal | A goal hidden from active lists but retained with its history | `APPROVED` (DEC-008) |

---

## 2. Dream Goal Creation

| ID | Rule | Status | Source / Decision |
|---|---|---|---|
| RULE-001 | A goal must have a non-empty name. | `PROPOSED` | Standard validation |
| RULE-002 | Goal name length is 1–80 characters after trimming. | `PROPOSED` | UI/layout constraint |
| RULE-003 | A goal must have a target price greater than zero. | `PROPOSED` | Target must be meaningful |
| RULE-004 | Target price is a whole Rupiah amount: digits only, no decimal separator, no fractional part. | `APPROVED` | DEC-007 |
| RULE-005 | Target price upper bound is `OPEN` (proposal: `999.999.999.999` to prevent overflow). | `OPEN` | TECH-03 |
| RULE-006 | Target date is optional. If provided, it must be a valid calendar date. | `PROPOSED` | Brief: "if needed" |
| RULE-007 | Target date may be in the past, both when creating and when editing a goal. No warning or rejection is required. | `APPROVED` | DEC-026 |
| RULE-008 | A description/note is optional, maximum 500 characters. | `OPEN` | OPEN-03 (FR-007) |
| RULE-009 | Goal names are unique within one account. Different accounts may use the same name. Comparison is case-insensitive and whitespace-trimmed (proposal). | `APPROVED` (uniqueness) / `OPEN` (comparison rules) | DEC-011, OPEN-14 |

---

## 3. Savings Transactions

| ID | Rule | Status | Source / Decision |
|---|---|---|---|
| RULE-010 | A savings transaction belongs to exactly one goal. | `PROPOSED` | Core model |
| RULE-011 | Every transaction has a direction (`Setoran` or `Penarikan`) and an amount greater than zero. The amount is always positive; direction determines the sign. | `APPROVED` | DEC-004 |
| RULE-012 | Transaction amounts are whole Rupiah: digits only, no decimals. | `APPROVED` | DEC-007 |
| RULE-013 | Transaction date is required and must be a valid date. A transaction date in the future is rejected (proposal; WIB is the reference zone). | `PROPOSED` | Needs confirmation |
| RULE-014 | Transaction note is optional, maximum 255 characters. | `OPEN` | OPEN-04 (FR-017) |
| RULE-015 | Withdrawals are supported. A withdrawal reduces the goal balance. | `APPROVED` | DEC-004 |
| RULE-016 | Editing a transaction recalculates the goal balance and status immediately. | `OPEN` (feature) / `APPROVED` (recalculation principle) | OPEN-01 |
| RULE-017 | Deleting a transaction recalculates the goal balance and status immediately. | `OPEN` (feature) / `APPROVED` (recalculation principle) | OPEN-02 |
| RULE-018 | A transaction may only be edited or deleted while its goal exists and is not archived (proposal). | `PROPOSED` | Referential integrity |
| RULE-019 | Transactions are ordered by transaction date descending, then by id descending for same-day entries. | `PROPOSED` | Stable ordering |
| RULE-051 | A withdrawal may exceed the current balance. The transaction is still recorded. | `APPROVED` | DEC-025 |
| RULE-052 | A transaction may be recorded against a goal that is already `Tercapai`; this increases the excess. | `APPROVED` | DEC-023 |

---

## 4. Balance and Progress

| ID | Rule | Status |
|---|---|---|
| RULE-020 | `saved_balance = SUM(deposits) - SUM(withdrawals)` over all transactions of the goal, or `0` when none exist. | `APPROVED` |
| RULE-021 | `remaining = MAX(0, target_price - saved_balance)`. | `APPROVED` |
| RULE-022 | `progress_percent = MIN(100, saved_balance / target_price * 100)`. Display precision is `OPEN` (proposal: one decimal, comma separator). | `APPROVED` (formula) / `OPEN` (precision) |
| RULE-023 | `excess = MAX(0, saved_balance - target_price)` and is displayed separately from progress. | `APPROVED` |
| RULE-024 | Over-saving is permitted: the balance may exceed the target, and the excess is displayed rather than rejected or discarded. | `APPROVED` |
| RULE-025 | Balance is never manually editable; it is always derived from transactions. | `APPROVED` |
| RULE-026 | Monetary values are stored as exact whole-rupiah values (integer type proposed), never floating point. | `APPROVED` (exactness) / `OPEN` (column type) |
| RULE-027 | No monetary rounding occurs in calculations because IDR has no minor unit. Any percentage rounding is display-only. | `APPROVED` |
| RULE-028 | A goal with no transactions shows `Rp0` saved, `0%` progress, and remaining equal to the target. | `APPROVED` |
| RULE-053 | A negative balance is valid and must be displayed with a minus sign (format `OPEN-16`, proposal `-Rp2.000.000`). | `APPROVED` (validity) / `OPEN` (format) |

**Worked example 1 (approved currency):** target `Rp30.000.000`; deposits `Rp5.000.000` and `Rp2.500.000`; no withdrawals → balance `Rp7.500.000`; remaining `Rp22.500.000`; progress `25,0%`; excess `Rp0`.

**Worked example 2 (withdrawal):** target `Rp30.000.000`; deposit `Rp10.000.000`; withdrawal `Rp4.000.000` → balance `Rp6.000.000`; remaining `Rp24.000.000`; progress `20,0%`.

**Worked example 3 (over-saving):** target `Rp30.000.000`; balance `Rp35.000.000` → progress `100%`; remaining `Rp0`; excess `Rp5.000.000`; status `Tercapai`.

**Worked example 4 (negative balance):** target `Rp30.000.000`; deposit `Rp2.000.000`; withdrawal `Rp5.000.000` → balance `-Rp3.000.000`; remaining `Rp30.000.000`; progress `0%`; status `Berlangsung`.

---

## 5. Goal Status & Completion

| ID | Rule | Status |
|---|---|---|
| RULE-029 | Status is derived from the balance: `Tercapai` when `saved_balance >= target_price`, otherwise `Berlangsung`. Archiving is a separate flag, not a status value. | `APPROVED` (DEC-006) / `OPEN` (storage strategy, TECH-04) |
| RULE-030 | Completion is automatic. No manual "mark complete" action exists. | `APPROVED` |
| RULE-031 | If a withdrawal (or, if approved later, an edit/delete) drops the balance below the target, the status returns to `Berlangsung` automatically. | `APPROVED` |
| RULE-032 | `Tercapai` goals remain visible in the active list and are included in active counts unless filtered. | `PROPOSED` |
| RULE-033 | An archived goal is excluded from active lists, active counts, and dashboard totals, but retains its history and can be restored. | `APPROVED` (DEC-008) |
| RULE-034 | A `Tercapai` goal may still receive deposits and withdrawals (over-saving is allowed). | `APPROVED` (DEC-023) |
| RULE-054 | The **Hampir Tercapai** indicator is shown when `90% <= progress < 100%` and the goal is not archived. At `100%` the goal is `Tercapai`, not `Hampir Tercapai`. | `APPROVED` (DEC-012) |

---

## 6. Editing, Archiving, and Restoring

| ID | Rule | Status |
|---|---|---|
| RULE-035 | Editing a goal's target price does not modify its transactions; balance, progress, and status are recomputed. | `APPROVED` |
| RULE-036 | Reducing the target below the saved balance results in 100% display progress, zero remaining, and an excess amount. | `APPROVED` |
| RULE-037 | Archiving a goal requires explicit confirmation naming the goal. | `APPROVED` (DEC-008) |
| RULE-038 | Archiving never deletes transactions. The full history is retained and visible in the archive view. | `APPROVED` (DEC-008) |
| RULE-039 | Archiving is reversible: an archived goal can be restored to the active list. | `APPROVED` (DEC-008) |
| RULE-040 | Hard deletion of goals is **not** part of the MVP. No UI path may permanently remove a goal. | `APPROVED` (DEC-008) |
| RULE-041 | No bulk destructive action exists in the MVP. | `PROPOSED` |
| RULE-055 | While a goal is archived, new transactions cannot be added (proposal: restore first). | `OPEN` (OPEN-12) |

---

## 7. History and Presentation

| ID | Rule | Status |
|---|---|---|
| RULE-042 | Transaction history is scoped to a single goal and uses **numbered pagination**, 20 rows per page. | `APPROVED` (DEC-009) |
| RULE-043 | Pagination preserves the goal context and any active search/sort/filter state. An invalid page number falls back to page 1; an out-of-range page clamps to the last valid page. | `PROPOSED` |
| RULE-044 | Empty history shows an empty state with a "Tambah Tabungan" action, not a blank table. | `PROPOSED` |
| RULE-045 | Dates are interpreted in Asia/Jakarta (WIB). Display format is `OPEN` (proposal: `22/11/2026`). Storage format is `YYYY-MM-DD`. | `APPROVED` (zone) / `OPEN` (display) |
| RULE-046 | Amounts are displayed as Indonesian Rupiah: `Rp` directly followed by digits, dot as the thousands separator, no decimals. Example: `Rp15.000.000`. | `APPROVED` (DEC-007) |
| RULE-056 | All user-facing labels, messages, and empty states use Bahasa Indonesia. | `APPROVED` (DEC-013) |

---

## 8. Access & Ownership

| ID | Rule | Status |
|---|---|---|
| RULE-047 | Every goal and transaction is owned by exactly one user. | `APPROVED` (DEC-003) |
| RULE-048 | A user may only read or mutate their own records; requests for other users' records return "not found" (404), never 403. | `APPROVED` (DEC-003) |
| RULE-049 | Registration requires a unique email address and a verified email before full access (enforcement point `OPEN-08`). | `APPROVED` (behavior) / `OPEN` (detail) |
| RULE-050 | Session lifetime, "remember me", and password-reset token lifetime are `OPEN`. | `OPEN` (OPEN-09, OPEN-11) |

---

## 9. Validation Summary

| Field | Required | Type | Constraints | Status |
|---|---|---|---|---|
| User name | Yes | Text | 1–80 chars, trimmed | `PROPOSED` |
| Email | Yes | Email | Valid, unique, verified | `APPROVED` (behavior) |
| Password | Yes | Secret | Minimum length `OPEN-10` (proposal: 8) | `OPEN` |
| Goal name | Yes | Text | 1–80 chars, trimmed, unique per account | `APPROVED` (uniqueness) / `PROPOSED` (length) |
| Target price | Yes | Integer IDR | > 0, ≤ max (`OPEN`) | `APPROVED` (integer) / `OPEN` (max) |
| Target date | No | Date | Valid date; past allowed; WIB | `APPROVED` |
| Goal description | No | Text | ≤ 500 chars | `OPEN` (OPEN-03) |
| Transaction direction | Yes | Enum | `Setoran` or `Penarikan` | `APPROVED` |
| Transaction amount | Yes | Integer IDR | > 0, ≤ max (`OPEN`) | `APPROVED` (integer) / `OPEN` (max) |
| Transaction date | Yes | Date | Valid; not in the future (proposal); WIB | `PROPOSED` |
| Transaction note | No | Text | ≤ 255 chars | `OPEN` (OPEN-04) |

---

## 10. Rules Still Requiring Owner or Technical Decision

| Rule | Question | Decision record |
|---|---|---|
| RULE-005 | Maximum target/transaction amount | TECH-03 |
| RULE-008 / RULE-014 | Goal description and transaction note in MVP | OPEN-03, OPEN-04 |
| RULE-009 | Case sensitivity and whitespace normalization for unique names | OPEN-14 |
| RULE-013 | Reject future transaction dates? | Needs confirmation |
| RULE-016 / RULE-017 | Transaction edit/delete in MVP | OPEN-01, OPEN-02 |
| RULE-022 | Percentage display precision | OPEN-15 |
| RULE-026 | Money column type | TECH-03 |
| RULE-029 | Status stored or derived | TECH-04 |
| RULE-045 | Date display format | Needs confirmation |
| RULE-050 | Session and token lifetimes | OPEN-09, OPEN-11 |
| RULE-053 | Negative money display format | OPEN-16 |
| RULE-055 | Transactions on archived goals | OPEN-12 |

**Development may begin only on rules marked `APPROVED`.** Rules marked `OPEN` must either be resolved or explicitly excluded from the MVP before their dependent feature is built.

---

## 11. Change History (0.2.0)

- Withdrawals and negative balances approved (DEC-004, DEC-025); RULE-015 rewritten, RULE-051/053 added.
- Over-saving approved with separate excess display (DEC-023); RULE-023/024 updated.
- Automatic status approved (DEC-006); RULE-029..031 rewritten.
- Archive/restore approved (DEC-008); RULE-037..040 rewritten, RULE-055 added.
- IDR with no decimals approved (DEC-007); RULE-004/012/026/027/046 updated.
- Unique goal names approved (DEC-011); RULE-009 updated.
- Past target dates approved (DEC-026); RULE-007 updated.
- Hampir Tercapai approved (DEC-012); RULE-054 added.
- Bahasa Indonesia and WIB approved (DEC-013, DEC-014); RULE-045/056 updated.
- Multi-user ownership approved (DEC-003); RULE-047..049 updated.
