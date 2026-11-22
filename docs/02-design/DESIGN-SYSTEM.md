# NAVORA — Design System

| Field | Value |
|---|---|
| Document ID | NAVORA-DS |
| Version | 0.2 (DEC-010 palette alignment) |
| Date | 2026-11-22 |
| Status | **Palette approved (DEC-010); typography and icon system approved (DEC-019 Option A)** |
| Related | `UI-UX-GUIDELINES.md`, `COMPONENT-SPECIFICATION.md`, `WIREFRAME-SPECIFICATION.md` |

> Nothing in this document is installed or implemented. No fonts, icon packages, or CSS frameworks have been added to the project.

---

## 1. Brand Direction

NAVORA should feel like a **calm, credible financial companion**: precise enough to trust with money, warm enough to use weekly.

| Attribute | Expression |
|---|---|
| Trustworthy | Deep, saturated blue for primary actions; restrained neutrals |
| Clear | Strong hierarchy, generous whitespace, one primary action per view |
| Personal | Human copy, goal-specific language, no corporate jargon |
| Modern | Flat surfaces, thin borders, subtle elevation, deliberate motion |
| Calm | No glow, no glass, no gradient meshes, no decorative blobs |

**Deliberate rejection of:** purple-blue gradients, glassmorphism, neon glow, oversized rounded cards, emoji icons, generic AI dashboard collages, stat-card repetition.

---

## 2. Color System

### 2.0 Palette provenance and owner decision

| Aspect | BESTCO reference | Approved NAVORA decision | Status |
|---|---|---|---|
| Primary blue scale | `BestCO/resources/css/app.css:11-21` defines `--color-primary-50` through `--color-primary-950`. | NAVORA uses the exact same 11 hex values, mapped to `--nv-blue-50` through `--nv-blue-950`. | `APPROVED` — DEC-010, 2026-11-22 |
| Neutral colors | BESTCO uses Tailwind neutral/slate utilities. | NAVORA cool-gray neutrals remain unchanged. | `PROPOSED` — not changed by DEC-010 |
| Semantic status colors | BESTCO uses emerald/amber/rose/sky utilities. | NAVORA semantic tokens remain unchanged. | `PROPOSED` — not changed by DEC-010 |
| Gradients, glow, glass, dark mode, dynamic themes | Present in parts of BESTCO. | Excluded from NAVORA MVP; no secondary primary blue palette is introduced. | `OUT` / `DEFERRED` |

### 2.1 Brand Blue — approved BESTCO palette (`DEC-010`)

The owner approved the exact 11-step BESTCO primary blue scale on 2026-11-22. NAVORA uses the same hex values; no value may be desaturated, darkened, renamed, or substituted. Neutral and semantic colors remain NAVORA-specific and must not introduce another primary brand palette.

| Token | BESTCO reference value | Approved NAVORA value | Intended use |
|---|---|---|---|
| `--nv-blue-50` | `#eff6ff` | `#eff6ff` | Subtle selected surface, info background |
| `--nv-blue-100` | `#dbeafe` | `#dbeafe` | Progress track, chip background |
| `--nv-blue-200` | `#bfdbfe` | `#bfdbfe` | Hover tint, decorative fill |
| `--nv-blue-300` | `#93c5fd` | `#93c5fd` | Secondary accents only |
| `--nv-blue-400` | `#60a5fa` | `#60a5fa` | Light decorative fill; never white text |
| `--nv-blue-500` | `#3b82f6` | `#3b82f6` | Focus/non-text UI only; fails AA for body text with white |
| `--nv-blue-600` | `#2563eb` | `#2563eb` | **Primary action background**, primary text on white |
| `--nv-blue-700` | `#1d4ed8` | `#1d4ed8` | Hover/active primary, headings |
| `--nv-blue-800` | `#1e40af` | `#1e40af` | Deep headings, pressed surfaces |
| `--nv-blue-900` | `#1e3a8a` | `#1e3a8a` | Near-navy text on light tint |
| `--nv-blue-950` | `#172554` | `#172554` | Darkest brand ink |

### 2.2 Neutrals — cool gray with a blue undertone

| Token | Hex | Intended use |
|---|---|---|
| `--nv-white` | `#FFFFFF` | Card and page base |
| `--nv-gray-25` | `#FBFCFE` | Alternating row tint |
| `--nv-gray-50` | `#F5F8FB` | App background |
| `--nv-gray-100` | `#EDF1F5` | Muted surface, disabled fill |
| `--nv-gray-200` | `#DDE4EB` | Subtle divider |
| `--nv-gray-300` | `#C9D3DC` | Decorative border |
| `--nv-gray-400` | `#93A2AF` | Disabled text, placeholder (not body text) |
| `--nv-gray-500` | `#6B7C8C` | **Control borders**, secondary icons |
| `--nv-gray-600` | `#5A6B7B` | Muted body text (5.49:1 on white) |
| `--nv-gray-700` | `#3D5566` | Secondary headings |
| `--nv-gray-800` | `#1F3A4D` | Body text alternative |
| `--nv-gray-900` | `#0A2540` | **Primary text** |
| `--nv-gray-950` | `#06182A` | Maximum contrast text |

### 2.3 Semantic colors

| Token | Hex | Use | Notes |
|---|---|---|---|
| `--nv-success-50` | `#ECF8F3` | Success surface | |
| `--nv-success-100` | `#D2EFE4` | Success chip | |
| `--nv-success-600` | `#0F6B4F` | Success text/icon | 6.49:1 on white |
| `--nv-success-700` | `#0B5740` | Success hover | |
| `--nv-warning-50` | `#FFF8EC` | Warning surface | |
| `--nv-warning-100` | `#FDEBCF` | Warning chip | |
| `--nv-warning-800` | `#8A4B08` | Warning text | 6.44:1 on `warning-50` |
| `--nv-danger-50` | `#FDF1F0` | Error surface | |
| `--nv-danger-100` | `#FADBD7` | Error chip | |
| `--nv-danger-600` | `#C4321F` | Error text, destructive action | 5.49:1 with white |
| `--nv-danger-700` | `#B42318` | Destructive hover | 6.57:1 with white |
| `--nv-info-*` | = blue scale | Informational | Reuse brand blue |
| `--nv-accent-600` | `#0E7C6F` | Secondary "on track" accent | Teal; 5.08:1 with white. Use sparingly |

### 2.4 Verified contrast ratios

Recalculated 2026-11-22 with the WCAG relative-luminance formula for the approved DEC-010 values. Recalculate if any hex changes.

| Foreground | Background | Ratio | Result |
|---|---|---|---|
| `#FFFFFF` | `#2563eb` | 5.17:1 | AA normal text ✓ |
| `#FFFFFF` | `#1d4ed8` | 6.70:1 | AA normal text ✓ |
| `#FFFFFF` | `#1e40af` | 8.72:1 | AAA normal text ✓ |
| `#1d4ed8` | `#eff6ff` | 6.16:1 | AA ✓ |
| `#2563eb` | `#eff6ff` | 4.75:1 | AA ✓ |
| `#1e3a8a` | `#eff6ff` | 9.52:1 | AAA ✓ |
| `#FFFFFF` | `#172554` | 14.69:1 | AAA ✓ |
| `#FFFFFF` | `#3b82f6` | 3.68:1 | Non-text UI only — fails AA normal text |
| `#0A2540` | `#FFFFFF` | 15.54:1 | AAA ✓ |
| `#3D5566` | `#FFFFFF` | 7.81:1 | AAA ✓ |
| `#5A6B7B` | `#FFFFFF` | 5.49:1 | AA ✓ |
| `#5A6B7B` | `#F5F8FB` | 5.15:1 | AA ✓ |
| `#FFFFFF` | `#0F6B4F` | 6.49:1 | AA ✓ |
| `#FFFFFF` | `#C4321F` | 5.49:1 | AA ✓ |
| `#8A4B08` | `#FFF8EC` | 6.44:1 | AA ✓ |
| `#0E7C6F` | `#FFFFFF` | 5.08:1 | AA ✓ |
| `#6B7C8C` | `#FFFFFF` | 4.30:1 | **Fails AA for normal text** — use for borders/icons only |
| `#93A2AF` | `#FFFFFF` | 2.62:1 | Placeholder/disabled only |
| `#DDE4EB` | `#FFFFFF` | ~1.3:1 | Decorative divider only |

**Rule:** body text must use `gray-900`, `gray-700`, or `gray-600`. `gray-500` and lighter are never used for body copy.

### 2.5 Color usage rules

- Exactly **one** primary blue action per screen region.
- Blue is not used to indicate "success"; use semantic green.
- Red is reserved for destructive actions and errors.
- Amber is reserved for warnings and near-deadline states (`TBD`).
- Never place white text on `primary-500`/`--nv-blue-500` or lighter. Use `primary-600`/`--nv-blue-600` or darker for white text.
- No gradients in the MVP; use flat fills only. No glow, glassmorphism, dark mode, or dynamic theme behavior.

---

## 3. Typography

| Role | Approved family | Fallback | Status |
|---|---|---|---|
| UI text | **Plus Jakarta Sans** | Inter, system UI | `APPROVED` — DEC-019 Option A |
| Display headings | **Plus Jakarta Sans** (single family) | Inter, system UI | `APPROVED` — DEC-019 Option A |
| Numeric emphasis | **JetBrains Mono** with `font-variant-numeric: tabular-nums` | system monospace | `APPROVED` — DEC-019 Option A |

Font family: Plus Jakarta Sans for UI/text; JetBrains Mono for financial figures.
Font delivery: Self-hosted fonts preferred. If fonts are not yet dependency-approved, use system UI + system monospace fallback. No Google Fonts CDN; no external font package installed in this documentation.

### 3.1 Type scale

| Token | Size / line-height | Weight | Use |
|---|---|---|---|
| `display` | 40 / 48 | 600 | Marketing hero only; Plus Jakarta Sans |
| `h1` | 30 / 38 | 600 | Page title; Plus Jakarta Sans |
| `h2` | 24 / 32 | 600 | Section title; Plus Jakarta Sans |
| `h3` | 20 / 28 | 600 | Card/panel title; Plus Jakarta Sans |
| `body-lg` | 18 / 28 | 400 | Intro text; Plus Jakarta Sans |
| `body` | 16 / 24 | 400 | Default; Plus Jakarta Sans |
| `body-sm` | 14 / 20 | 400 | Secondary text, table cells; Plus Jakarta Sans |
| `caption` | 12 / 16 | 500 | Labels, metadata; Plus Jakarta Sans |
| `amount-xl` | 32 / 40 | 600 | Primary monetary figure; JetBrains Mono with `font-variant-numeric: tabular-nums` |
| `amount` | 20 / 28 | 600 | Card amounts; JetBrains Mono with `font-variant-numeric: tabular-nums` |

**Rules:** maximum two families; headings use `text-wrap: balance` where supported; no letter-spacing on body text; monetary figures use tabular numerals and right alignment in tables.

---

## 4. Spacing

Base unit: **4 px**. Scale: `4, 8, 12, 16, 20, 24, 32, 40, 48, 64, 80, 96`.

| Token | Value | Typical use |
|---|---|---|
| `space-1` | 4 | Icon/text gap |
| `space-2` | 8 | Tight stacks, chip padding |
| `space-3` | 12 | Input padding (vertical) |
| `space-4` | 16 | Component padding |
| `space-5` | 20 | Card padding (compact) |
| `space-6` | 24 | Card padding, section gap |
| `space-8` | 32 | Section separation |
| `space-10` | 40 | Page section rhythm |
| `space-12` | 48 | Large section |
| `space-16` | 64 | Hero/vertical rhythm |
| `space-20` | 80 | Page top spacing |
| `space-24` | 96 | Maximum section gap |

Layout container: `max-width: 1200px`, gutter `24px` desktop / `16px` mobile.

---

## 5. Radius, Borders, Elevation

### Radius

| Token | Value | Use |
|---|---|---|
| `radius-xs` | 4px | Chips, tags |
| `radius-sm` | 6px | Inputs, small buttons |
| `radius-md` | 8px | Buttons, table containers |
| `radius-lg` | 12px | Cards, panels |
| `radius-xl` | 16px | Modals, large surfaces (maximum) |
| `radius-full` | 999px | Avatars, progress bar ends, pills |

Cards use `12px`, not 24px+. Avoid "bubble" UI.

### Borders

| Token | Value | Use |
|---|---|---|
| `border-subtle` | `1px solid #DDE4EB` | Dividers, card outlines |
| `border-default` | `1px solid #C9D3DC` | Table and panel boundaries |
| `border-control` | `1px solid #6B7C8C` | Inputs, selects, checkboxes (≥3:1) |
| `border-focus` | `2px solid #2563eb` | Focus ring (with 2px offset) |
| `border-danger` | `1px solid #C4321F` | Invalid fields |

### Shadows (subtle, no glow)

| Token | Value | Use |
|---|---|---|
| `shadow-xs` | `0 1px 2px rgba(10,37,64,.06)` | Inputs, sticky headers |
| `shadow-sm` | `0 1px 3px rgba(10,37,64,.08), 0 1px 2px rgba(10,37,64,.04)` | Cards |
| `shadow-md` | `0 4px 12px rgba(10,37,64,.10)` | Dropdowns, popovers |
| `shadow-lg` | `0 12px 32px rgba(10,37,64,.14)` | Modals only |

Never stack multiple colored shadows. No `box-shadow` used to simulate borders.

---

## 6. Iconography

| Decision | Value | Status |
|---|---|---|
| Library | **Local inline SVG set** (Lucide-compatible outline geometry) | `APPROVED` — DEC-019 Option A |
| Stroke width | 1.75px at 24px, 1.5px at 20px | `APPROVED` — DEC-019 Option A |
| Sizes | 16 (inline), 20 (buttons/nav), 24 (section), 32 (empty states) | `APPROVED` — DEC-019 Option A |
| Color | `currentColor`; decorative icons `aria-hidden="true"` | Required |
| Meaning | One icon = one concept; always paired with a text label for primary actions | Required |

No emoji as interface icons. No mixing icon families. An allowlisted local SVG component provides the icon set. No external icon package or CDN is included.

---

## 7. Component States

| State | Visual treatment |
|---|---|
| Default | `blue-600` background, white text |
| Hover | `blue-700` background |
| Active/pressed | `blue-800`, 1px translateY(1px) max |
| Focus-visible | 2px `blue-600` ring, 2px offset (never removed) |
| Disabled | `gray-100` background, `gray-400` text, `not-allowed` cursor, no shadow |
| Loading | Label replaced by spinner + "Saving…", width preserved, `aria-busy="true"` |
| Invalid | `danger-600` border + message with icon |
| Selected | `blue-50` surface + `blue-600` left border (2px) |

---

## 8. Motion Tokens

| Token | Duration | Easing | Use |
|---|---|---|---|
| `motion-instant` | 80ms | `ease-out` | Color/opacity micro-feedback |
| `motion-fast` | 140ms | `cubic-bezier(.2,0,.2,1)` | Hover, focus |
| `motion-base` | 220ms | `cubic-bezier(.2,0,.2,1)` | Expand/collapse, toasts |
| `motion-slow` | 320ms | `cubic-bezier(.16,1,.3,1)` | Modal/dialog entrance |
| `motion-reveal` | 500ms | `cubic-bezier(.16,1,.3,1)` | One-time scroll reveal |

**Rules:**
- Animate only `transform` and `opacity` (plus `height` only for small disclosures).
- No animation may move content the user is reading.
- `@media (prefers-reduced-motion: reduce)` sets durations to `0.01ms` and disables transforms.
- Progress bar animates once on load, then only on value change.
- No looping/decorative animation.
- GSAP is **not** approved (DEC-020); CSS transitions cover all MVP needs.

---

## 9. Elevation & Z-Index Scale

| Layer | Z-index |
|---|---|
| Base | 0 |
| Sticky header | 100 |
| Dropdown | 200 |
| Sidebar (mobile overlay) | 300 |
| Modal backdrop | 400 |
| Modal | 410 |
| Toast | 500 |

---

## 10. CSS Token Skeleton (for implementation phase, not yet created)

```css
:root {
  /* Brand */
  --nv-blue-50:#eff6ff;  --nv-blue-100:#dbeafe; --nv-blue-200:#bfdbfe;
  --nv-blue-300:#93c5fd; --nv-blue-400:#60a5fa; --nv-blue-500:#3b82f6;
  --nv-blue-600:#2563eb; --nv-blue-700:#1d4ed8; --nv-blue-800:#1e40af;
  --nv-blue-900:#1e3a8a; --nv-blue-950:#172554;

  /* Neutrals */
  --nv-white:#FFFFFF;    --nv-gray-25:#FBFCFE;  --nv-gray-50:#F5F8FB;
  --nv-gray-100:#EDF1F5; --nv-gray-200:#DDE4EB; --nv-gray-300:#C9D3DC;
  --nv-gray-400:#93A2AF; --nv-gray-500:#6B7C8C; --nv-gray-600:#5A6B7B;
  --nv-gray-700:#3D5566; --nv-gray-800:#1F3A4D; --nv-gray-900:#0A2540;

  /* Semantic */
  --nv-success:#0F6B4F;  --nv-warning:#8A4B08;  --nv-danger:#C4321F;
  --nv-focus:#2563eb;

  /* Text */
  --nv-text:#0A2540; --nv-text-muted:#5A6B7B; --nv-text-inverse:#FFFFFF;

  /* Surfaces */
  --nv-bg:#F5F8FB; --nv-surface:#FFFFFF; --nv-surface-muted:#eff6ff;

  /* Radius */
  --nv-radius-sm:6px; --nv-radius-md:8px; --nv-radius-lg:12px; --nv-radius-xl:16px;

  /* Motion */
  --nv-ease:cubic-bezier(.2,0,.2,1);
  --nv-dur-fast:140ms; --nv-dur-base:220ms; --nv-dur-slow:320ms;
}
```

This block is documentation only; `NAVORA/public/css/style.css` remains empty until the design stage is approved.

---

## 11. Design Governance

1. The brand palette is **owner-approved** (`DEC-010`). Typography and icon system are **owner-approved** (`DEC-019` Option A).
2. Any new color must be added as a token with a documented purpose and verified contrast.
3. Any new component must follow `COMPONENT-SPECIFICATION.md`.
4. Accessibility checks are mandatory before a component is considered done.
5. AI agents may propose token changes but may not approve them.
2. Any new color must be added as a token with a documented purpose and verified contrast.
3. Any new component must follow `COMPONENT-SPECIFICATION.md`.
4. Accessibility checks are mandatory before a component is considered done.
5. AI agents may propose token changes but may not approve them.
