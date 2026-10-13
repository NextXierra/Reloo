# Design System Contract: Reloo Marketplace & Visual CMS

> **Genre**: Editorial / Premium Utilitarian Minimalism  
> **Standards**: Minimalist-UI Protocol + Hallmark Anti-Slop Gates  
> **Brand**: Reloo (Official Signature: Violet `#6828FF` & Hot Pink `#FA26A0`)

---

## 1. Palette & CSS Design Tokens

```css
:root {
    /* Brand Identity (Reloo Official Signature Colors) */
    --color-brand-violet: #6828FF; /* Left dot / first 'O' in Reloo mark */
    --color-brand-pink: #FA26A0;   /* Right dot / second 'O' in Reloo mark */
    --color-brand-dark: #13091B;   /* Deep brand container background */

    /* Canvas & Surfaces */
    --color-paper: #FBFBFA;        /* Warm bone off-white canvas */
    --color-paper-card: #FFFFFF;   /* Primary surface for cards / containers */
    --color-paper-subtle: #F7F6F3; /* Secondary surface / table headers / kbd */
    --color-paper-dark: #121214;   /* Studio / builder dark shell */

    /* Ink & Typography */
    --color-ink: #111111;          /* Charcoal off-black (never pure #000) */
    --color-ink-secondary: #52525B;/* Readable secondary text (contrast >= 4.5:1) */
    --color-ink-muted: #71717A;    /* Muted captions & metadata (contrast >= 4.5:1) */
    --color-ink-inverted: #FFFFFF; /* High-contrast white text on dark */

    /* Structural Borders & Rules */
    --color-rule: #EAEAEA;         /* Ultra-light 1px structural hairline */
    --color-rule-dark: #27272A;    /* Hairline border for dark studio / builder */
    --color-focus: #111111;        /* Instant 2px outline for keyboard focus */

    /* Spot Pastel Status Badges (Strictly desaturated, high-contrast text) */
    --badge-green-bg: #EDF3EC;
    --badge-green-text: #26542A;
    --badge-green-border: #D1E2CF;

    --badge-blue-bg: #E1F3FE;
    --badge-blue-text: #17547D;
    --badge-blue-border: #C2E4FC;

    --badge-yellow-bg: #FBF3DB;
    --badge-yellow-text: #785100;
    --badge-yellow-border: #F5E5B5;

    --badge-red-bg: #FDEBEC;
    --badge-red-text: #8C2220;
    --badge-red-border: #F8C8CA;
}
```

### Brand Signature & Accent Footprint Rule
- **Primary Wordmark**: "REL" in heavy bold sans (`font-extrabold tracking-tight text-ink text-xl`), followed immediately by two solid circular discs: Dot 1 in violet `#6828FF` and Dot 2 in hot pink `#FA26A0` (`w-2.5 h-2.5 rounded-full inline-block`).
- **Official Brand Assets**:
  - `public/images/brand/reloo-horizontal.png` (Horizontal full brandmark for invoices, mastheads)
  - `public/images/brand/reloo-icon.png` (Stylized 'R' with violet and pink discs for app mark, dark studio sidebar, favicon)
- **Accent Footprint Rule (Hallmark Gate 23 & Minimalist-UI)**: Violet and hot pink are strictly brand anchors, occupying $\le 5\%$ of any single viewport. The base remains warm bone, off-black, and 1px hairline rules—never full-bleed purple gradient hero blobs.

---

## 2. Typographic Hierarchy

- **Editorial Serif (Headings & Display)**: `'Instrument Serif', 'Newsreader', Georgia, serif`.
  - Roman only (`font-style: normal`), tight tracking (`letter-spacing: -0.02em` to `-0.03em`), tight line-height (`1.1` to `1.15`).
  - **Hallmark Rule**: No italic headers or single italic emphasis words in headlines.
- **Primary Sans (UI, Body, Controls)**: `'Instrument Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif`.
  - Body copy line-height `1.6` at `14px` (`text-sm`) or `15px`.
- **Monospace (Code, Order IDs, Resi, Currency/Pricing)**: `'Geist Mono', 'SF Mono', Menlo, Consolas, monospace`.
  - Tabular numerals (`font-variant-numeric: tabular-nums`).

---

## 3. Structural Archetypes (Hallmark & Minimalist-UI)

- **Navigation (Hallmark N1b - SaaS Three-Section Nav)**:
  - Section 1 (Left): Official Reloo Wordmark + monospaced "TERKURASI" tag.
  - Section 2 (Center): Refined search bar flanked by category text links.
  - Section 3 (Right): Cart indicator with numeric badge + Account / Login action.
- **Footer (Hallmark Ft1 Mast-headed + Ft4 Dense Colophon)**:
  - Large roman typographic masthead: "RELOO · PASAR BARANG TERKURASI".
  - Physical curation policy statement (10-titik verifikasi).
  - Dense typographic columns for Kategorisasi and Layanan Pelanggan.
  - Monospace partner disclosures (QRIS, Midtrans, BCA, Mandiri, JNE, SiCepat, J&T).
- **Hero & Content Sections**:
  - Asymmetric Diptych / Bento (Hallmark F1): Editorial headline on left, framed photography on right.
  - Curated Assurance Strip (Hallmark T4): 4 hairline columns highlighting tangible guarantees.
  - Product Card Grid (Hallmark F6): 1px borders, 8px radius, pastel condition pills, monospace pricing.
  - Spec Sheet (Hallmark F3): Dense 2-3 column specification tables for authenticity & preloved grade.
  - Accordion (Minimalist FAQ): No container cards; pure 1px hairline bottom borders with `+` and `−`.

---

## 4. Absolute Anti-Slop Banned Patterns

1. BANNED: Purple/indigo gradients (`bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900`).
2. BANNED: Gradient headline text (`background-clip: text text-transparent`).
3. BANNED: Heavy drop shadows (`shadow-md`, `shadow-lg`, `shadow-xl`, `shadow-indigo-600/30`).
4. BANNED: Generic 3-equal-column icon-above-heading card grids.
5. BANNED: Card-in-card nesting without semantic hierarchy.
6. BANNED: `rounded-full` pills for cards or primary buttons (pill shape allowed only on `text-xs` status badges).
7. BANNED: Centred-everything hero sections.
8. BANNED: Emojis anywhere in markup or copy (e.g. 🛒, 📦, ✨, 🚀).
9. BANNED: Cliché copywriting ("Elevate", "Seamless", "Unleash", "Next-Gen", "Game-changer", "Delve").
10. BANNED: Re-drawn UI chrome (fake macOS traffic light dots / fake browser address pills).
11. BANNED: Wrap-to-two-lines clickable buttons (enforce `white-space: nowrap`).
12. BANNED: `transition-all` or bouncy overshoot easings.

---

## 5. Mobile Responsive Non-Negotiables

- **Gate 34**: `html, body { overflow-x: clip; }` prevents horizontal scrollbar on all viewports.
- **Gate 49**: No interactive button or nav label wraps to two lines on 375px mobile viewports.
- **Gate 50**: Grid tracks on image containers use `minmax(0, 1fr)` to prevent blowouts.
- **Touch Targets**: Minimum 44px vertical height on all mobile primary buttons and tap targets.
