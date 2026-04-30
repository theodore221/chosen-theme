# Chosen Theme — Implementation Log

**For full session context, plans, and remaining work: read `docs/session-handoff.md` first.**

---

## Current status

| Phase | Status | Notes |
|-------|--------|-------|
| Phase 0 — Environment | ✅ Complete | LocalWP running at chosen.local |
| Phase 1 — Scaffold | ✅ Complete | All structural files built and committed |
| Task 11 — Activate theme | ✅ Complete | User-confirmed via WP Admin |
| Phase 2 — Header + footer | ✅ Code complete | 6 commits (cc927e2 → 680ac60); awaiting user visual verification on chosen.local |
| **Phase 3 — 9 custom blocks** | **✅ Code complete** | All 9 blocks shipped (5053b1e → 325b5f1); awaiting user verification + Lighthouse + impeccable polish |
| Phase 4 — Content | ⏳ Pending assets from Irene | Logo SVG, real signup URL needed |
| Phase 5 — QA + launch | ⏳ Pending content | Lighthouse, WCAG AA, cross-browser |
| Phase 6 — Handoff | ⏳ Pending launch | Editor training doc for Irene |

---

## Commit history

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
e9d202d docs: add comprehensive session-handoff.md, update implementation log
b291116 docs: add ADRs (001-003), update implementation log — Phase 1 complete
7e3e3b7 feat(theme): add FSE templates and footer/header parts
ac1b711 feat(theme): add functions.php and inc/ (block registration, menus, security)
da283cb feat(build): add Tailwind + wp-scripts build tooling, compiled assets
c9f9470 feat(theme): add theme.json with brand design tokens
031c4b2 feat(theme): add core theme identity files
eda1dc0 chore: rename design-system/, add .claude tooling and agents
28a1652 Initial commit
```

---

## Phase 1 — What was built (2026-04-30)

- `style.css` — theme header (name, version, text-domain, requirements)
- `index.php` — required empty WP recognition file
- `.gitignore` — node_modules/ only (assets/ is committed)
- `theme.json` — 10 brand colours, 3 font families, 11 font sizes, 720/1200px layout widths
- `tailwind.config.js` — mirrors theme.json tokens (colours, fonts, letter-spacing, easing, shadows)
- `package.json` — npm scripts: start (concurrently Tailwind watch + wp-scripts start), build
- `src/css/input.css` — Tailwind directives + prefers-reduced-motion reset
- `src/index.js` — placeholder (prevents wp-scripts build error with no blocks yet)
- `assets/css/main.css` — compiled Tailwind output (committed, ~3KB)
- `functions.php` — setup, Google Fonts enqueue, design-system CSS enqueue, constants, inc/ requires
- `inc/block-registration.php` — empty, ready for register_block_type() calls
- `inc/menus.php` — primary + footer nav menus
- `inc/security.php` — xmlrpc disabled, wp_generator removed
- `templates/index.html` — required WP fallback
- `templates/front-page.html` — home page (full-width main)
- `templates/page.html` — default page (constrained)
- `templates/single.html` — single post (constrained)
- `parts/header.html` — minimal navy placeholder
- `parts/footer.html` — minimal navy placeholder
- `docs/adr/001-003` — architecture decision records
- `.claude/settings.json` — pre-approved bash permissions
- `.claude/commands/new-block.md` — /new-block scaffold command
- `.claude/commands/chosen-design.md` — /chosen-design brand context loader
- `.claude/agents/marketing-content-creator.md` — content agent
- `.claude/agents/engineering-cms-developer.md` — WP engineering agent
- `design-system/` — full design system (renamed from "Chosen 2025 Design System/")
- `docs/session-handoff.md` — comprehensive session resume guide

**Build verified:** `npm run build` — webpack compiled cleanly, Tailwind output generated.

---

## Phase 2 — What was built (2026-04-30)

- `.impeccable.md` — root-level Design Context for impeccable skills
- `assets/img/chosen-logo-white.png` — working copy of white horizontal logo
- `assets/img/chosen-mark.png` — working copy of Tau-circle medallion
- `parts/header.html` — sticky 64px navy header with CHOSEN wordmark (medallion replacing "O"), 5 hardcoded nav links (hidden < md), gold "Register" pill
- `parts/footer.html` — branded navy footer with white logo, JY address, three Lucide-style inline-SVG socials, 48px gold rule, scripture pull-quote (Psalm 34:5), copyright row, inline IO snippet for sticky-shadow
- `src/css/input.css` additions: `.chosen-wordmark__o` medallion replacement, `.chosen-header.is-scrolled` shadow, base resets (warm paper bg, gold focus ring, smooth-scroll with reduced-motion override, default link colour)
- `tailwind.config.js` — fixed `transitionTimingFunction` keys (`out-quart`, `movement` — Tailwind prepends `ease-` itself)

**Build verified:** `npm run build` — `assets/css/main.css` ~9KB minified, includes `.ease-out-quart`, `.tracking-[0.18em]`, `.max-w-wide`, all `.chosen-*` colour utilities. URL `url(../../assets/img/chosen-mark.png)` resolves correctly from `/wp-content/themes/chosen-theme/assets/css/main.css` to `/wp-content/themes/chosen-theme/assets/img/chosen-mark.png`.

**Awaiting user visual verification on `chosen.local`** — see Phase 2 verification gate in `~/.claude/plans/immutable-questing-narwhal.md`.

---

## Phase 3 — What was built (2026-04-30)

All 9 custom blocks shipped under the `chosen/` namespace, grouped under the "Chosen" category in the inserter. Each block: `block.json` + `index.js` (registers via wp-scripts) + `edit.js` (React InspectorControls + RichText) + `render.php` (server-rendered, escaped Tailwind output).

| Block | Commit | Purpose |
|-------|--------|---------|
| `chosen/hero` | `5053b1e` | Full-bleed photo + 24-ray rotating motif + Anton "BE RADIANT" + gold date/venue card + pulsing Register CTA. Ken Burns photo. |
| `chosen/marquee` | `509c7be` | Pure-CSS infinite text scroll on navy. Gold/white items, hover-pause, 3 speed presets. |
| `chosen/vision` | `aeb7bf1` | Eyebrow → Anton headline → 48px gold rule → italic light scripture → gold cite. IO scroll-fade entrance. |
| `chosen/stat-strip` | `2ebbbb3` | 4-stat row with count-up-from-zero numbers (IO + RAF + easeOutQuart). Paper or royal bg. |
| `chosen/expect-tile-grid` | `ef8a31e` | 8 staggered cards (60ms delay each). Hover lift + gold top accent slide-in. |
| `chosen/image-mosaic` | `1656054` | Bento or even photo grid. Hard-edged, full-bleed. Gold tint hover, blur-to-sharp load. |
| `chosen/cta-banner` | `57fcdc5` | Navy banner: Anton headline + gold pulse pill + scripture pull-quote split. |
| `chosen/sponsor-strip` | `8e05649` | ACF-managed logo row on warm paper. Greyscale → colour hover. |
| `chosen/quote` | `325b5f1` | Standalone scripture pull-quote. Navy or paper bg. |

Phase 3 also added:
- `inc/acf-sponsor-fields.php` — ACF Sponsors field group registered as PHP (deploys with theme)
- `assets/img/photos/` — 7 working-copy photos from `design-system/assets/`
- 4 keyframes (`chosenSpin`, `chosenKenBurns`, `chosenRegisterPulse`, `chosenMarquee`) and 1 shared scroll-fade primitive (`.chosen-fade-up`)
- Updated `tailwind.config.js` (fixed `transitionTimingFunction` keys: `out-quart`, `movement`)

Compiled `assets/css/main.css`: ~17.8KB minified. Compiled JS bundles: ~50KB total across 9 blocks (hero largest 11.8KB, sponsor-strip smallest 2.2KB).

**Build verified:** `npm run build` clean, all 9 PHP renders lint clean, all 9 block.json valid.

**As-built spec:** `docs/superpowers/specs/2026-04-30-chosen-theme-phase-2-3.md` — replaces the missing prior-session spec.

**Awaiting user verification:** see Phase 3 verification gate in the plan — visit `chosen.local`, compose `/test-blocks` page using all 9 blocks, run Lighthouse mobile, verify reduced-motion behaviour, run `/impeccable:critique` + `/impeccable:polish` per block.

---

## Known issues / gotchas

- Template parts (.html) don't execute PHP — all dynamic output via render.php in custom blocks
- ACF field groups exported as PHP to `inc/acf-sponsor-fields.php` (deploys with theme)
- `CHOSEN_REGISTER_URL` is empty until Jento provides — hero + cta-banner CTAs render with `aria-disabled="true"` until populated
- Logo PNG only (no SVG yet) — `assets/img/chosen-logo-white.png` and `chosen-mark.png` are working copies
- Anton is a substitute display font — confirm with Irene/Jento before launch
- Hardcoded theme path `/wp-content/themes/chosen-theme/...` in `parts/footer.html` `<img src=...>` — breaks if theme folder is renamed (template parts can't run PHP to use `get_template_directory_uri()`)
- 7 photos at `assets/img/photos/` are 5MB+ originals — Phase 4 should swap for Irene-approved photography with rights confirmed and alt text vetted; WP responsive image markup serves auto-generated thumbnails on the front-end so visitors don't pay full-resolution cost
- Mobile hamburger nav not built — header hides nav links below `md` breakpoint (deferred to Phase 4)
- `wp:navigation` block not used in header — links are hardcoded `<a>` tags. If Irene wants editor-controlled nav, swap in Phase 4
