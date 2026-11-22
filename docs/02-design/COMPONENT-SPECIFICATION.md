# NAVORA — Component Specification

| Field | Value |
|---|---|
| Document ID | NAVORA-CS |
| Version | 0.1 (Draft) |
| Date | 2026-11-22 |
| Status | **PROPOSED — requires owner approval** |
| Related | `DESIGN-SYSTEM.md`, `UI-UX-GUIDELINES.md`, `WIREFRAME-SPECIFICATION.md` |

Components are described independent of implementation. In the PHP MVC scaffold these will likely be PHP partials with view variables ("data contract" below), plus CSS classes and minimal JS. **No component exists yet.**

Naming: `NV` prefix for CSS classes (e.g., `nv-goal-card`) to avoid collisions.

---

## 1. Component Inventory

| ID | Component | Priority | Used by |
|---|---|---|---|
| CMP-01 | App shell / layout | Must | All pages |
| CMP-02 | Sidebar navigation | Must | Desktop |
| CMP-03 | Mobile top bar + drawer | Must | Mobile |
| CMP-04 | Page header | Must | All pages |
| CMP-05 | Button | Must | Everywhere |
| CMP-06 | Form field (label/input/help/error) | Must | All forms |
| CMP-07 | Money display | Must | Cards, tables, detail |
| CMP-08 | Progress bar | Must | Cards, detail |
| CMP-09 | Goal card | Must | Goals list, dashboard |
| CMP-10 | Summary figure band | Must | Dashboard |
| CMP-11 | Status badge | Should | Cards, detail |
| CMP-12 | Goal form | Must | Create/edit |
| CMP-13 | Transaction form | Must | Add savings |
| CMP-14 | Transaction table | Must | History, detail |
| CMP-15 | Pagination | Must | History |
| CMP-16 | Search & sort bar | Should | Goals list |
| CMP-17 | Modal / dialog | Must | Forms, confirmations |
| CMP-18 | Confirmation dialog | Must | Destructive actions |
| CMP-19 | Toast | Must | Feedback |
| CMP-20 | Empty state | Must | Lists, dashboard |
| CMP-21 | Skeleton / loading | Should | Lists |
| CMP-22 | Error summary | Must | Forms |
| CMP-23 | Footer | Could | App shell |
| CMP-24 | Inline alert | Should | Page-level messages |

**Not specified** (out of scope): habit tracker, task list, note editor, focus timer, planner calendar, analytics charts, rental/inventory/POS widgets.

---

## CMP-01 — App Shell / Layout

- **Purpose:** Consistent frame for every page: skip link, header, sidebar, main content, footer, toast region.
- **Structure:** `<a class="skip-link">` → header → sidebar (desktop) → `<main id="main">` → footer → toast live region.
- **Data:** page title, active nav key, optional flash message.
- **Behavior:** skip link becomes visible on focus; main content is the first landmark after navigation.
- **Responsive:** sidebar becomes drawer < 1024px; header compresses to brand + menu.
- **States:** default; drawer open (mobile); no layout shift when a toast appears.

## CMP-02 — Sidebar Navigation

- **Purpose:** Persistent access to Dashboard and Goals.
- **Structure:** brand mark, nav list, bottom account area.
- **Data:** items `{label, href, icon, active}`.
- **Behavior:** active item indicated by background + 2px left border + `aria-current="page"`.
- **Responsive:** hidden below 1024px, rendered as CMP-03 drawer.
- **States:** default, hover, active, focus-visible.

## CMP-03 — Mobile Top Bar + Drawer

- **Purpose:** Navigation on small screens.
- **Structure:** menu button (44×44), brand, optional primary action; drawer with same nav items.
- **Behavior:** open/close, Escape closes, backdrop click closes, focus trapped, body scroll locked, focus returns to trigger.
- **Accessibility:** `aria-expanded`, `aria-controls`, `role="dialog"` + `aria-modal="true"` on drawer.

## CMP-04 — Page Header

- **Purpose:** Establish page identity and primary action.
- **Data:** `title` (required), `description` (optional), `actions` (0–2), `breadcrumb` (optional).
- **Structure:** `h1`, supporting text, action group aligned right on desktop / stacked on mobile.
- **Rules:** exactly one `h1`; at most one primary button.

## CMP-05 — Button

- **Variants:** primary, secondary (outline), ghost, danger, link.
- **Sizes:** sm (32px), md (40px), lg (48px); min touch target 44px on mobile.
- **Data:** `label`, `type` (`button|submit`), `variant`, `size`, `disabled`, `loading`, `iconStart/iconEnd`, `href` (renders link).
- **States:** default, hover, active, focus-visible, disabled, loading, full-width.
- **Loading:** preserves width, shows spinner, sets `aria-busy="true"`, ignores repeated clicks.
- **Rules:** icon-only buttons require `aria-label` and a tooltip; never use a button for navigation when a link is correct.

## CMP-06 — Form Field

- **Purpose:** Consistent label, control, help, and error presentation.
- **Structure:** label → control → help text → error text; optional required marker.
- **Data:** `id`, `name`, `label`, `type`, `value`, `required`, `help`, `error`, `autocomplete`, `inputmode`, `prefix/suffix`.
- **Behavior:** label is programmatically associated; error linked via `aria-describedby`; invalid control sets `aria-invalid="true"`; error announced.
- **Variants:** text, email, password, integer amount (IDR), date, textarea, select.
- **Responsive:** full width; 16px font on mobile to prevent zoom.
- **States:** default, focus, filled, disabled, read-only, invalid, success (optional).

## CMP-07 — Money Display

- **Purpose:** Render monetary values unambiguously and consistently.
- **Data:** `amount` (exact whole-rupiah integer), `size` (`sm|md|lg|xl`), `emphasis`, `showSign`.
- **Rules:** format as Indonesian Rupiah (`Rp15.000.000`): `Rp` prefix, dot thousands separator, no decimals (DEC-007); tabular numerals; no client-side float math; negative values use a leading minus.
- **Accessibility:** include the currency in the accessible text (not only a visual symbol); negative values are announced as negative.
- **States:** zero, positive, negative, over-target excess.
- **Open:** percentage display precision (`OPEN-15`); negative format (`OPEN-16`).

## CMP-08 — Progress Bar

- **Purpose:** Visualize savings progress toward a target.
- **Data:** `value` (0–100 display), `label`, `showValue`, `size` (`sm|md`), `tone` (`brand|success`).
- **Structure:** track (`blue-100`) + fill (`blue-600`/`success-600`), optional label row.
- **Behavior:** animates width once on mount; on update, transitions 500ms; reduced motion disables.
- **Accessibility:** `role="progressbar"`, `aria-valuemin="0"`, `aria-valuemax="100"`, `aria-valuenow`, `aria-label` or adjacent text.
- **Edge cases:** 0% shows an empty track with visible 0%; 100% shows a full track and "Target tercapai" text; never exceeds 100% visually; excess is displayed separately (DEC-023).

## CMP-09 — Goal Card

- **Purpose:** Scannable summary of one goal.
- **Structure:** name (link) → optional target date → saved/target line → progress bar → remaining → overflow menu.
- **Data:** `id`, `name`, `target`, `saved`, `progress`, `remaining`, `excess?`, `targetDate?`, `status`, `nearlyReached`.
- **Behavior:** whole card is not a button; the title is the link; overflow menu contains Edit/Arsipkan.
- **Responsive:** full width on mobile; fixed min-height to align grids.
- **States:** default, hover (border tint), Tercapai, Hampir Tercapai, negative balance, loading skeleton.
- **Rules:** no fabricated values; no decorative imagery required; archived goals are not shown here.

## CMP-10 — Summary Figure Band

- **Purpose:** Three headline figures on the dashboard.
- **Data:** `activeGoals`, `totalSaved`, `totalRemaining`.
- **Structure:** one horizontal band with dividers, not three floating cards.
- **Rules:** label above, value below; values derived from the same aggregation as lists; zero is shown as a real zero; archived goals excluded; negative totals displayed as negative.
- **Responsive:** 3 columns → 2 → stacked.
- **States:** loading skeletons; zero-state wording.

## CMP-11 — Status Badge

- **Purpose:** Communicate goal state.
- **Variants:** Berlangsung (blue tint), Tercapai (green tint), Diarsipkan (gray), Hampir Tercapai (amber, DEC-012).
- **Rules:** text + color, never color alone; sentence case; no emoji.

## CMP-12 — Goal Form

- **Purpose:** Create or edit a goal.
- **Fields:** name (required), target price (required), target date (optional), description (optional).
- **Data:** `mode` (`create|edit`), `values`, `errors`, `action`, `cancelHref`.
- **Behavior:** server validation; error summary on failure; submit disabled only while in flight.
- **Responsive:** single column, max 560px.
- **States:** default, submitting, invalid, server error.
- **Open:** image field (not in MVP).

## CMP-13 — Transaction Form

- **Purpose:** Record a deposit or withdrawal against a goal.
- **Fields:** direction (`Setoran` / `Penarikan`, required), amount (required, whole IDR), date (required, default today WIB), note (optional).
- **Data:** `goalId`, `goalName`, `today`, `values`, `errors`, `action`.
- **Behavior:** amount input uses `inputmode="numeric"` and accepts digits only; blocks future dates in UI **and** server; withdrawals may exceed the balance (no client-side block).
- **Responsive:** modal on desktop, bottom sheet on mobile.
- **States:** default, submitting, invalid, negative-balance confirmation, over-target notice.

## CMP-14 — Transaction Table

- **Purpose:** Review savings history.
- **Structure:** caption (goal name) → header row (Tanggal, Jenis, Nominal, Catatan, Aksi) → rows → footer/pagination.
- **Data:** `rows[] {id, direction, date, amount, note}`, `page`, `totalPages`, `canEdit`, `canDelete`.
- **Behavior:** rows sorted newest first; actions in an overflow menu or icon buttons with labels.
- **Responsive:** below 640px, rows become stacked blocks with labeled values; no clipped content.
- **States:** default, empty (CMP-20), loading, error, single row, many rows.

## CMP-15 — Pagination

- **Purpose:** Navigate paginated history.
- **Data:** `current`, `total`, `baseUrl`.
- **Structure:** Previous, numbered pages (windowed with ellipsis), Next.
- **Behavior:** links (not JS-only), preserve query state; invalid page clamps.
- **Accessibility:** `<nav aria-label="Navigasi halaman">`, `aria-current="page"`, disabled states not focusable.
- **Rules:** 20 items per page; used for goals, archive, and savings history (DEC-009); hide when a single page.

## CMP-16 — Search & Sort Bar

- **Purpose:** Find and order goals.
- **Data:** `query`, `sort`, `sortOptions`, `resultCount`.
- **Behavior:** submit on Enter or explicit button; clear action; no results state.
- **Accessibility:** labeled search input; sort select labeled; result count announced politely.
- **Responsive:** stacked on mobile; inline on desktop.

## CMP-17 — Modal / Dialog

- **Purpose:** Focused tasks without leaving context.
- **Structure:** backdrop → dialog (title, body, actions, close button).
- **Behavior:** focus trap, Escape closes, restore focus, scroll lock, click outside closes only for non-destructive dialogs.
- **Accessibility:** `role="dialog"`, `aria-modal="true"`, `aria-labelledby`, `aria-describedby` where needed.
- **Responsive:** centered (max 480px) desktop; bottom sheet mobile.
- **States:** opening, open, closing, submitting.

## CMP-18 — Confirmation Dialog

- **Purpose:** Prevent accidental or unintended actions.
- **Data:** `title`, `message` (must name the record), `confirmLabel` (verb), `variant` (`danger` or `neutral`), `action`.
- **Rules:** confirm button is not autofocused; default focus on Cancel; message states consequences; no "Are you sure?" without specifics. Archiving states that history is retained and the goal can be restored (DEC-008).
- **States:** default, submitting, error.

## CMP-19 — Toast

- **Purpose:** Non-blocking success/error feedback.
- **Data:** `type` (`success|error|info`), `message`, `duration` (default 4000ms).
- **Behavior:** auto-dismiss with pause on hover/focus; manual close; max 3 stacked; critical errors also rendered inline.
- **Accessibility:** `role="status"` for success/info, `role="alert"` for errors; not focus-stealing.

## CMP-20 — Empty State

- **Purpose:** Explain absence and offer the next step.
- **Data:** `icon`, `title`, `description`, `action` (label + href).
- **Variants:** no goals, no transactions, no search results, no archived goals, no recent activity.
- **Rules:** truthful copy; no fake sample data; one action.

## CMP-21 — Skeleton / Loading

- **Purpose:** Preserve layout while data loads.
- **Rules:** match final geometry; no shimmer loop for reduced motion; `aria-busy="true"` on the region; announce loading text for screen readers.

## CMP-22 — Error Summary

- **Purpose:** Summarize form errors for keyboard and screen-reader users.
- **Structure:** heading + list of links to invalid fields.
- **Behavior:** receives focus after failed submit; each link moves focus to the field.

## CMP-23 — Footer

- **Purpose:** Minimal closure: product name, version/build note (`TBD`), privacy note if applicable.
- **Rules:** no fake links, no social icons, no marketing copy.

## CMP-24 — Inline Alert

- **Purpose:** Page-level information (e.g., "Goal ini sudah tercapai").
- **Variants:** info, success, warning, danger.
- **Rules:** icon + title + text; dismissible only when non-critical.

---

## 2. Cross-Component Rules

| Rule | Detail |
|---|---|
| Data integrity | Components never compute money with floating point; values arrive preformatted or as exact units |
| Accessibility | Every component must pass keyboard and contrast checks before "done" |
| States | Every data component defines loading, empty, error, and populated states |
| Responsiveness | Every component defines behavior at 360px and 1280px |
| Reuse | No page-specific duplicate of an existing component |
| Naming | `nv-<component>` BEM-ish classes; one responsibility per partial |
| Content | No emoji icons, no placeholder lorem ipsum in shipped UI |
