# Chosen Theme — Phase 2 + Phase 3 As-Built Spec

**Date:** 2026-04-30
**Status:** Code complete, awaiting visual verification on `chosen.local`
**Plan:** `~/.claude/plans/immutable-questing-narwhal.md`
**Replaces:** the missing prior-session spec that was supposed to live at `docs/superpowers/specs/`

This document captures what was actually built in Phase 2 (real header + footer) and Phase 3 (9 custom blocks). Intended as the single source of truth that Phase 4 (content build) and Phase 5 (QA) reference. Pairs with `docs/session-handoff.md` which is the strategic / open-items doc.

---

## Phase 2 — header, footer, base resets

### `parts/header.html`

- Sticky 64px navy bar (`bg-chosen-navy`, `top-0`, `z-40`).
- CHOSEN wordmark on the left: 20px Work Sans Medium with the `chosen-mark.png` medallion replacing the "O" via an inline `<span class="chosen-wordmark__o">` (background-image, `0.84em × 0.84em`, vertical-align -0.18em). Mirrors `design-system/preview/type-wordmark.html` exactly.
- 5 hardcoded nav links — Home, Programme, Speakers, Travel, FAQs. Hidden below `md` breakpoint (mobile hamburger deferred to Phase 4).
- Gold pill "Register" CTA on the right. `bg-chosen-gold` → `hover:bg-chosen-gold-600` + `hover:text-white` over 200ms `ease-out-quart`. Currently `href="#"` until `CHOSEN_REGISTER_URL` is populated.
- `.is-scrolled` class added on scroll by an inline IIFE in `parts/footer.html`. Adds a subtle navy shadow.

### `parts/footer.html`

- Navy background, 3-column grid on desktop / stacked on mobile.
- White logo top-left (180×56 PNG, working copy in `assets/img/chosen-logo-white.png`).
- JY Australia address (140 Westbourne Grove, Northcote VIC 3070).
- Three Lucide-style inline-SVG socials: map-pin → Google Maps, Instagram, Facebook. 24px / 1.75px stroke, white/85 → gold on hover.
- 48px gold rule.
- Scripture pull-quote: gold eyebrow ("Theme · Chosen 2026") → italic light Psalm 34:5 → gold cite. Mirrors `design-system/preview/components-scripture-pull.html`.
- Copyright row + "Built for Chosen 2026 · La Trobe University, Melbourne · 19–22 November 2026".
- Bottom of file: two inline IIFEs — sticky-shadow toggle + shared IntersectionObserver for `.chosen-fade-up` elements (used by vision, expect-tile-grid, cta-banner, quote).

### `src/css/input.css`

`@layer base` additions:
- `html { scroll-behavior: smooth }` (off with reduced motion)
- `body { background: #FAF8F3; color: #14130F }` — warm paper, never pure white; near-black, never pure black
- `a { color: #EDA90C; text-decoration: none } a:hover { color: #C68A06 }`
- `:focus-visible { outline: 3px solid #EDA90C; outline-offset: 2px }`
- Global reduced-motion `!important` reset (defence-in-depth alongside per-component guards)

`@layer components` additions:
- `.chosen-wordmark__o` — medallion replacement
- `.chosen-header.is-scrolled` — sticky shadow
- `.chosen-fade-up` + `.chosen-fade-up.is-visible` — shared scroll-fade primitive
- `.chosen-tile` + `.chosen-tile::before` + hover lift — expect-tile-grid hover
- `.chosen-image-mosaic*` — bento + even grid + hover gold tint + blur-to-sharp loading
- `.chosen-sponsor-strip__logo` — greyscale → colour hover
- `.chosen-marquee*` — viewport, track, separator, hover-pause
- `.chosen-hero__rays` + `.chosen-hero__ray` + `.chosen-hero__photo--kenburns img` + `.chosen-cta-pulse`
- `.screen-reader-text` — visually-hidden recipe restated

Top-level keyframes (outside `@layer` so Tailwind doesn't purge):
- `chosenSpin` — 360° rotation (rays)
- `chosenKenBurns` — 1 → 1.04 scale + ±1.5% translate (hero photo)
- `chosenRegisterPulse` — gold glow ripple (hero + cta-banner CTAs)
- `chosenMarquee` — translateX(0 → -50%) seamless loop

### `tailwind.config.js`

Fix: `transitionTimingFunction` keys dropped redundant `ease-` prefix. Keys are now `out-quart` and `movement` so Tailwind generates `.ease-out-quart` and `.ease-movement` (it auto-prepends `ease-`).

### `inc/block-registration.php`

Adds the `chosen` block category at the top of the inserter, plus an `init` action that loops a `$blocks` array of 9 slugs and registers each from `__DIR__ . '/../build/blocks/' . $slug`. Each block self-guards on `file_exists($path . '/block.json')`.

### `functions.php`

Added `require_once get_template_directory() . '/inc/acf-sponsor-fields.php';` for the Sponsors field group (required for `chosen/sponsor-strip`).

### `inc/acf-sponsor-fields.php` (new)

Registers a "Chosen Sponsors" ACF field group via `acf_add_local_field_group()`. Repeater field with logo (image, required), name (text, required, used as alt), URL (url, optional). Scoped via location rule to the `chosen/sponsor-strip` block. Self-guards on `function_exists('acf_add_local_field_group')`.

---

## Phase 3 — 9 blocks

All blocks share: `chosen` namespace, `category: chosen`, `apiVersion: 3`, `supports.html: false`, server-rendered via `render.php`, `save: () => null` in JS, escape-on-output via `esc_html` / `esc_url` / `esc_attr`. Each block has `block.json` + `index.js` + `edit.js` + `render.php`.

### `chosen/hero` — full-bleed photo, radiant rays, pulse CTA

| Attribute | Type | Default |
|-----------|------|---------|
| eyebrow | string | "Chosen 2026 · National Catholic Youth Conference" |
| headlinePart1 | string | "Be" (gold) |
| headlinePart2 | string | "Radiant" (red) |
| subhead | string | "Look to him, and be radiant." |
| dateRange | string | "19–22" |
| dateMonth | string | "NOV '26" |
| venueName | string | "La Trobe University" |
| venueCity | string | "Bundoora · Melbourne" |
| ctaLabel | string | "Register" |
| backgroundImage | object | `{ id, url, alt }` (MediaUpload + alt-text required) |
| enableRays | boolean | true |
| enableKenBurns | boolean | true |

Render: `<section>` with a full-bleed background `<img>` + 60% navy scrim + (optional) `.chosen-hero__rays` injected by inline IIFE (24 trapezoid rays cycling 6 brand colours, rotating 60s/turn). Anton headline at `clamp(4.5rem, 14vw, 9rem)`, gold + red. Gold date card (Anton 56px) + venue card (Work Sans). Gold pill CTA reads `CHOSEN_REGISTER_URL` via `defined()` guard, falls back to `#` + `aria-disabled="true"`. Pulse animation 4s ease-in-out infinite. All animations gated on `prefers-reduced-motion: no-preference`.

Editor: MediaUpload + alt-text TextControl with required-field warning Notice, RichText for all text fields, ToggleControls for rays + Ken Burns, TextControl for venue.

### `chosen/marquee` — pure CSS infinite scroll

| Attribute | Type | Default |
|-----------|------|---------|
| items | array of `{ text, color: 'gold' | 'white' }` | 5-item default set ("LOOK TO HIM", "BE RADIANT", "CHOSEN 2026", "COME AND ENCOUNTER", "JESUS YOUTH AUSTRALIA") |
| speed | enum: slow / medium / fast | medium (40s) |

Items duplicated inline so `translateX(-50%)` loops seamlessly. Pure CSS — no JS bundles. Pause on hover via `animation-play-state`. Duration via `--chosen-marquee-duration` custom property set inline by render. Anton 3xl–5xl. Duplicates `aria-hidden="true"` so AT only announces the original sequence once. `aria-label="Theme phrases"` on section.

### `chosen/vision` — scripture-anchored mission section

| Attribute | Type | Default |
|-----------|------|---------|
| eyebrow | string | "Theme · Chosen 2026" |
| headline | string | "A conference beyond the ordinary." |
| body | string | "" (optional supporting paragraph) |
| scripture | string | "Look to him, and be radiant; so your faces shall never be ashamed." |
| cite | string | "Psalm 34:5" |

Navy bg, `max-w-content` (720px). Anton headline at `clamp(2.5rem, 6vw, 4.5rem)`. 48px gold rule. Italic light Work Sans 22px scripture. Wrapping `.chosen-fade-up` triggers the shared scroll-fade-up entrance via the IO observer in `parts/footer.html`.

### `chosen/stat-strip` — count-up numbers

| Attribute | Type | Default |
|-----------|------|---------|
| stats | array of `{ value, prefix?, suffix?, label }` | 4 entries: 1500+ Young people gathered, 22 Speakers, 4 Days of encounter, 30+ Workshops & talks |
| background | enum: paper / royal | paper |

Numbers count up from 0 to value via per-instance inline IIFE + IntersectionObserver + RAF loop with easeOutQuart, ~1500ms. Reduced-motion shows finals immediately. Cells reset to 0+suffix before observation so the first off-screen value isn't the end value. Anton numbers `clamp(3rem, 8vw, 5.5rem)`. Gold eyebrow labels.

### `chosen/expect-tile-grid` — staggered cards

| Attribute | Type | Default |
|-----------|------|---------|
| eyebrow | string | "What to expect" |
| headline | string | "Four days of encounter." |
| tiles | array of `{ title, description }` | 8 entries: Adoration, Bishops & guest speakers, Workshops, Live worship, Theatre, Confession, Community, Vocation |

Warm paper bg. 1-col mobile / 2-col sm / 4-col lg. Each tile is white card, navy 12% border, `rounded-md`. Hover: `translateY(-4px)` lift + 4px gold top accent slides in via `::before` `transform: scaleY(0 → 1)` + deeper navy shadow. Reuses `.chosen-fade-up` for entrance + `--i` custom property for 60ms stagger delay per tile.

### `chosen/image-mosaic` — bento photo collage

| Attribute | Type | Default |
|-----------|------|---------|
| images | array of `{ id, url, alt }` | 6 default photos from `assets/img/photos/` |
| layout | enum: bento / even | bento |

Bento: 4-col grid, tiles 0 + 3 span 2x2 (the heroes), others 1x1. Falls back to 2-col on mobile. Even: 3-col desktop / 2-col mobile, uniform. Hard-edged photos (radius 0). Hover 6%-opacity gold tint via `::after`, 200ms ease-out-quart. Blur-to-sharp on load: image starts at `blur(8px) opacity 0`, snaps to clear via inline `onload="this.setAttribute('data-loaded','1')"` + matching CSS rule. Reduced-motion shows sharp immediately. `loading="lazy"` + `decoding="async"`. Section gets `aria-label="Photo gallery from Chosen"`.

### `chosen/cta-banner` — register prompt with scripture

| Attribute | Type | Default |
|-----------|------|---------|
| headline | string | "Be one of the chosen." |
| subhead | string | "Spaces are limited. Bring a friend." |
| ctaLabel | string | "Register" |
| eyebrow | string | "Chosen 2026" |
| scripture | string | "You did not choose me but I chose you; and I appointed you to go and bear fruit that will last." |
| cite | string | "John 15:16" |

Full-width navy. 2-col desktop: left = Anton headline + italic subhead + gold pulse CTA; right = scripture pull (eyebrow → italic verse → 48px gold rule → cite) separated by white/10 border. Stacks on mobile. Reuses `chosenRegisterPulse` keyframe + `defined()` URL guard from hero. Reuses `.chosen-fade-up`.

### `chosen/sponsor-strip` — ACF-managed partner logos

| Attribute | Type | Default |
|-----------|------|---------|
| eyebrow | string | "Brought to you by" |

Sponsors come from the ACF Repeater field `sponsors` (registered in `inc/acf-sponsor-fields.php`). Each: `{ logo, name, url? }`. Layout 2 / 3 / 4 / 6 cols at mobile / sm / md / lg. Logos clamp to `max-height: 56px`, render greyscale + 65% opacity at rest, snap to colour + full opacity on hover (250ms filter transition). Logos with URL wrap in `<a target="_blank" rel="noopener noreferrer">` with screen-reader-only "Visit {name}" text. Render guards: empty/missing sponsors render an editor-only placeholder for users with `edit_posts` capability; visitors see nothing.

### `chosen/quote` — standalone scripture pull-quote

| Attribute | Type | Default |
|-----------|------|---------|
| eyebrow | string | "Theme · Chosen 2026" |
| verse | string | "Look to him, and be radiant; so your faces shall never be ashamed." |
| cite | string | "Psalm 34:5" |
| background | enum: navy / paper | navy |

Standalone scripture pull. Same visual recipe as the scripture half of `chosen/vision` and `chosen/cta-banner`: gold eyebrow → italic light Work Sans verse (`clamp 1.5–2rem`) → 48px gold rule → gold cite. Reuses `.chosen-fade-up`. Renders nothing if verse is empty. Background toggle swaps text colour but gold accents stay constant.

---

## Architecture decisions made (and reaffirmed by the build)

| # | Decision | Outcome |
|---|----------|---------|
| A1 | Wordmark = inline `<span>` with `chosen-mark.png` background | ✅ Works as designed; matches the design-system reference |
| A2 | Header nav = hardcoded `<a>` tags | ✅ Survives reactivation; trade-off: no editor UI for nav until Phase 4 swap |
| A3 | Logo files copied to `assets/img/` | ✅ Stable theme-folder paths in render.php and parts/*.html |
| A4 | Footer socials = inline SVG paths | ✅ No CDN, no JS; ~600 bytes per icon |
| A5 | All 9 blocks use `render.php` (not `save.js`) | ✅ Output consistent between editor preview and front-end |
| A6 | Block category `chosen` registered in inserter | ✅ All 9 blocks group under one heading for Irene |
| A7 | ACF field group registered as PHP | ✅ Schema deploys with theme; not DB-only |
| A8 | Per-instance inline IIFEs for stat counter and hero rays, scoped via `wp_unique_id()` | ✅ Multiple block instances on one page work independently |
| A9 | All `prefers-reduced-motion` queries in `src/css/input.css` (not per-block) | ✅ Single source of truth + global `!important` defence-in-depth |
| A10 | `CHOSEN_REGISTER_URL` read via `defined()` guard, fallback `#` + `aria-disabled` | ✅ Hero and CTA banner work today; will start firing the moment Jento populates the constant |

### Deviation from the plan

- **Mobile hamburger nav** — listed as deferred to Phase 4 in the plan. Not built. Header gracefully hides nav links below `md` breakpoint.
- **`/impeccable:critique` and `/impeccable:polish` per block** — listed as steps 7–8 in the standard block process. These are interactive skill invocations and were skipped during the auto-mode build. They're recommended for the user to run when verifying each block on `chosen.local`.

---

## Build artefacts (committed)

```
build/blocks/cta-banner/{block.json,index.js,index.asset.php,render.php}
build/blocks/expect-tile-grid/{...}
build/blocks/hero/{...}
build/blocks/image-mosaic/{...}
build/blocks/marquee/{...}
build/blocks/quote/{...}
build/blocks/sponsor-strip/{...}
build/blocks/stat-strip/{...}
build/blocks/vision/{...}
assets/css/main.css            ~17.8KB minified
assets/img/chosen-logo-white.png
assets/img/chosen-mark.png
assets/img/photos/photo-*.jpg  (7 photos, ~25MB total)
```

Total compiled JS across blocks: ~50KB minified (hero is the largest at 11.8KB; sponsor-strip the smallest at 2.2KB).

---

## What's left (Phase 4 / Phase 5)

- [ ] User to visually verify Phase 2 + Phase 3 on `chosen.local` (header, footer, all 9 blocks via inserter)
- [ ] Compose `/test-blocks` page using all 9 blocks; run Lighthouse mobile preset (target ≥90 on all 4 axes)
- [ ] Run `/impeccable:audit` post-Phase-3, save to `docs/superpowers/specs/2026-04-30-impeccable-audit-post-phase-3.md`
- [ ] Run `/impeccable:critique` + `/impeccable:polish` on each block
- [ ] WCAG AA contrast audit (Stark or axe), resolve every flagged issue
- [ ] Reduced-motion verification: DevTools → Rendering → Emulate → reload `/test-blocks`, confirm all rays/fades/pulses/counters disabled
- [ ] Replace `assets/img/photos/*.jpg` with Irene-approved photography (rights-confirmed, alt-text-vetted)
- [ ] Replace `assets/img/chosen-logo-white.png` and `chosen-mark.png` with SVG when Irene/Jento provide
- [ ] Populate `CHOSEN_REGISTER_URL` constant in `functions.php` once Jento provides
- [ ] Confirm Anton substitute typeface OR swap for the original poster face if supplied
- [ ] Mobile hamburger nav (deferred from this phase)
- [ ] Optional: swap hardcoded `parts/header.html` nav for `wp:navigation` linked to `primary` menu if Irene wants editor-controlled nav

---

## Commit history (Phase 2 → Phase 3)

```
325b5f1 feat(blocks): add chosen/quote — standalone scripture pull-quote
8e05649 feat(blocks): add chosen/sponsor-strip — ACF-managed partner logo row
57fcdc5 feat(blocks): add chosen/cta-banner — register prompt with scripture pull
1656054 feat(blocks): add chosen/image-mosaic — bento photo collage with gold tint
ef8a31e feat(blocks): add chosen/expect-tile-grid — staggered cards on warm paper
2ebbbb3 feat(blocks): add chosen/stat-strip — count-up numbers on viewport entry
aeb7bf1 feat(blocks): add chosen/vision — scripture-anchored mission section
509c7be feat(blocks): add chosen/marquee — pure CSS infinite scroll
5053b1e feat(blocks): add chosen/hero — full-bleed photo, radiant rays, pulse CTA
11a2a29 feat(blocks): register 'chosen' inserter category and blocks loop
680ac60 docs: log Phase 2 implementation, mark code complete pending visual verify
eac3cf1 feat(theme): base resets — warm paper bg, gold focus ring, ease-out-quart fix
61ddca2 feat(theme): real footer with logo, socials, scripture pull-quote
d732dbd feat(theme): real header with wordmark medallion and gold CTA
737dfb8 feat(theme): copy logo working files to assets/img
cc927e2 docs: add .impeccable.md with canonical design context
```
