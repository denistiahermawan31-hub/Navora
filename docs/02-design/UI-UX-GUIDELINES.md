# NAVORA — UI/UX Guidelines

| Field | Value |
|---|---|
| Document ID | NAVORA-UX |
| Version | 0.2.0 (Consolidated) |
| Date | 2026-11-22 |
| Status | **PROPOSED — design requires owner approval; approved product rules applied** |
| Related | `DESIGN-SYSTEM.md`, `COMPONENT-SPECIFICATION.md`, `WIREFRAME-SPECIFICATION.md`, `USER-FLOW.md` |

---

## 1. Experience Principles

| Principle | Practical rule |
|---|---|
| **Progress is the hero** | Every goal view answers "how much is saved, how much remains" without scrolling |
| **One primary action** | Each screen has one visually dominant action (e.g., "Add savings" on a goal) |
| **Numbers must be unambiguous** | Currency symbol, grouped digits, tabular alignment, explicit labels |
| **No surprise consequences** | Destructive actions name the record and state the outcome before confirming |
| **Fast to record** | Adding savings should take one short form: amount, date (prefilled today), optional note |
| **Honest states** | Never show fabricated placeholder totals; show real zeros and empty states |
| **Quiet confidence** | Restrained color, no hype, no gamified confetti in MVP |

---

## 2. Information Architecture

```text
Dashboard (ringkasan)
├── Goals (daftar, cari, urutkan)
│   ├── Goal detail
│   │   ├── Tambah Tabungan (modal/halaman)
│   │   ├── Edit goal
│   │   ├── Arsipkan goal (konfirmasi)
│   │   └── Riwayat tabungan (numbered pagination)
│   └── Goal baru
└── Arsip (goal diarsipkan + pulihkan)
```

| Level | Purpose | Primary action |
|---|---|---|
| Dashboard | Overall status across goals | Tambah Goal / Tambah Tabungan |
| Goals | Browse and find a goal | Goal baru |
| Goal detail | Understand and act on one goal | Tambah Tabungan |
| History | Audit deposits and withdrawals | Kembali ke goal |
| Arsip | Review and restore archived goals | Pulihkan |

**Not in navigation:** habits, tasks, notes, focus, planner, analytics (DEC-016). Settings is deferred (`OPEN-07`).

---

## 3. Layout & Grid

| Breakpoint | Width | Layout |
|---|---|---|
| `sm` | < 640px | Single column; bottom or drawer navigation; stacked cards |
| `md` | 640–1023px | Single column with 2-column card grid where useful |
| `lg` | 1024–1279px | Persistent sidebar (240px) + content |
| `xl` | ≥ 1280px | Sidebar + content, max content width 1200px |

- Content gutters: 16px mobile, 24px tablet, 32px desktop.
- Vertical rhythm: 24px between cards, 32–40px between sections.
- Do not stretch text lines beyond ~72 characters.
- Tables scroll horizontally only when unavoidable, with a visible affordance.

---

## 4. Navigation

### Desktop sidebar
- Width 240px, white surface, 1px right border.
- Brand mark at top, primary destinations below, account action pinned to bottom.
- Active item: `blue-50` background, `blue-600` text, 2px left indicator.
- Collapsible to icon rail only if needed later; not in MVP.

### Mobile
- Top app bar with brand and menu button.
- Drawer opens from the left; focus trapped; closes on Escape/backdrop.
- Primary "Add savings" remains reachable within the goal context.

### Rules
- Navigation labels are nouns: Dashboard, Goals. Not "Home" and "My Stuff".
- Current location is always indicated.
- No hidden primary destinations in dropdowns.

---

## 5. Page Patterns

### 5.1 Dashboard
1. Page header: greeting/title + primary action.
2. Summary band: 3 figures (active goals, total saved, total remaining) — **one row, not a wall of cards**.
3. "Hampir tercapai" list (90%–99%) with compact progress bars.
4. Recent activity list (max 5).
5. Empty state when no goals.

### 5.2 Goals list
- Header with count and "Tambah Goal".
- Search field + sort control (and status filter if approved, `OPEN-05`).
- Card grid (1/2/3 columns responsive).
- Each card: name, balance/target, progress bar, remaining, excess (if any), optional target date, overflow menu (Edit/Arsipkan).
- Numbered pagination, 20 per page (DEC-009).

### 5.3 Goal detail
- Breadcrumb/back link.
- Title + status badge + overflow actions.
- Hero figure: balance, large; target and remaining secondary; excess when above target.
- Progress bar with percentage and accessible text.
- Primary "Tambah Tabungan" button.
- Description and target date metadata.
- Recent transactions (5) + "Lihat semua".

### 5.4 Savings history
- Goal name context at top.
- Table: Tanggal | Jenis | Nominal | Catatan | Aksi.
- Numbered pagination, 20 per page.
- Empty state with "Tambah Tabungan".

### 5.5 Forms
- Single column, max 560px.
- Labels above inputs, always visible (no placeholder-only labels).
- Required marker `*` with a legend.
- Inline help text for formatting rules (e.g., "Nominal harus lebih dari nol").
- Primary submit on the right/bottom; secondary Batal.
- Error summary at top for screen readers; field errors beneath inputs.

---

## 6. Feedback & State Design

| State | Pattern |
|---|---|
| Loading | Skeleton matching final layout for lists; spinner inside buttons for submits |
| Empty | Icon + one-sentence explanation + primary action; no fake data |
| Error | Plain-language message + recovery action; never raw exception text |
| Success | Toast (3–5s, dismissible) or inline confirmation; focus not stolen |
| Partial failure | Explain what succeeded and what did not |
| Offline | Browser-default behavior; no offline mode claimed |

Toasts appear bottom-right on desktop, top-center on mobile; `role="status"`; pause on hover/focus.

---

## 7. Forms & Validation UX

- Validate on submit; re-validate a field on blur after first error.
- Never clear user input on error (except password fields).
- Monetary input: digits only for IDR (no decimal separator); strip spaces and grouping dots; show `Rp` as a prefix/hint, not inside the value.
- Date input: native date control with a text fallback; display format follows Indonesian locale, storage stays ISO; interpret in WIB.
- Disable submit only during an in-flight request, never preemptively.
- Reversible actions use an explicit verb ("Arsipkan goal"); irreversible actions (if ever added) name the record and consequence.

---

## 8. Data Display

- Right-align monetary columns; left-align text; center only short statuses.
- Use tabular numerals for amounts.
- Negative balances use a minus sign and a text label (not color alone); excess savings are shown separately.
- Progress bars always include a numeric percentage; color is not the only signal.
- Show at most one decimal place for percentages (`OPEN-15`).
- Tables: sticky header only when rows exceed one viewport; zebra striping is optional and subtle (`gray-25`).

---

## 9. Content & Tone

| Do | Don't |
|---|---|
| "Anda telah menabung 25% dari target." | "Awesome! You're crushing it! 🎉" |
| "Sisa: Rp22.500.000" | "Tinggal sedikit lagi!" |
| "Tambah Tabungan" | "Deposit funds" (banking jargon) |
| "Arsipkan goal" | "Hapus permanen" (tidak tersedia) |
| "Belum ada goal. Buat satu untuk mulai menabung." | "Nothing to see here." |

- Second person, present tense, short sentences, Bahasa Indonesia (DEC-013).
- No financial advice, promises, or projections.
- No emoji in UI copy for core actions.

---

## 10. Accessibility Requirements

| ID | Requirement |
|---|---|
| AX-01 | All interactive elements reachable and operable by keyboard |
| AX-02 | Visible focus indicator on every focusable element |
| AX-03 | Form inputs have programmatic labels; errors linked with `aria-describedby` |
| AX-04 | Color is never the sole carrier of meaning |
| AX-05 | Text contrast ≥ 4.5:1; large text/UI components ≥ 3:1 |
| AX-06 | Modals trap focus, close on Escape, and restore focus to the trigger |
| AX-07 | Progress bars expose `role="progressbar"` with `aria-valuenow/min/max` and a text alternative |
| AX-08 | Toasts announced via live region; not the only feedback for critical actions |
| AX-09 | Page has one `h1`; heading levels are sequential |
| AX-10 | Touch targets ≥ 44×44px |
| AX-11 | `prefers-reduced-motion` respected |
| AX-12 | Zoom to 200% without loss of content or function |

Target: **WCAG 2.1 AA**. Not yet tested — no implementation exists.

---

## 11. Responsive Behavior Rules

| Component | Mobile | Desktop |
|---|---|---|
| Sidebar | Drawer, hidden by default | Persistent 240px |
| Goal cards | 1 column | 2–3 columns |
| Summary band | Stacked or 2-column | 3 columns |
| Tables | Convert to stacked definition rows or allow horizontal scroll with hint | Full table |
| Modals | Full-width sheet from bottom | Centered dialog, max 480px |
| Forms | Full width, 16px input font to prevent iOS zoom | Max 560px |

---

## 12. Motion Guidelines

| Moment | Motion | Constraint |
|---|---|---|
| Page/section entrance | 8–12px rise + fade, 320ms, once | No parallax on data screens |
| Card entrance | Stagger 40ms, max 6 items | Skip if reduced motion |
| Progress bar | Width transition 500ms on value change | No looping shimmer |
| Modal | Backdrop fade 180ms + scale 0.98→1 | No bounce |
| Toast | Slide 12px + fade | Auto-dismiss, pause on hover |
| Button press | 1px translate | No ripple |
| Scroll reveal | Only on long informational sections, once | Never on tables/forms |

**Do not:** animate numbers counting up on every visit, spin decorative elements, use scroll-jacking, or animate layout-affecting properties on lists.

GSAP is optional and unapproved (DEC-020). CSS transitions are sufficient for MVP.

---

## 13. Design Review Checklist

- [ ] One clear primary action per view
- [ ] No fabricated data or placeholder totals
- [ ] All amounts formatted consistently with the approved currency
- [ ] Empty, loading, error, and success states designed
- [ ] Contrast verified for all text pairs
- [ ] Keyboard path tested for every flow
- [ ] Mobile layout verified at 360px and 768px
- [ ] Destructive actions confirmed with explicit wording
- [ ] No forbidden visual patterns (glow, glass, blobs, emoji icons)
- [ ] Motion respects reduced-motion and causes no layout shift
- [ ] Components match `COMPONENT-SPECIFICATION.md`
