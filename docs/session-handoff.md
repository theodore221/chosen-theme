# Chosen Theme — Session Handoff

**Read this file at the start of every new Claude session.**
This is the single source of truth for resuming work. It reflects the state as of 2026-04-30.

---

## Quick orientation

| Item | Value |
|------|-------|
| Project | Custom WordPress FSE block theme — chosenconference.org.au |
| Conference | Chosen 2026 — "Be Radiant" (Psalm 34:5) |
| Organiser | Jesus Youth Australia |
| Venue | La Trobe University, Melbourne · 19–22 November 2026 |
| Repo | `/Users/theodorexavier/Desktop/repo/chosen-theme/` |
| Local site | `http://chosen.local` (LocalWP, PHP 8.2, MySQL 8.0, nginx) |
| WP version | 6.9.4 |
| Branch | `main` |
| Theme name | Chosen Theme (text-domain: `chosen-theme`) |
| Build | `npm run build` (Tailwind v3 + @wordpress/scripts v30) |

## People

| Person | Role |
|--------|------|
| **Theodore Xavier** | Lead developer — software engineering background, first WP custom theme |
| **Irene** | Content lead — populates pages, provides copy |
| **Jento** | Site admin (JY Australia) — approves plugin changes, holds live credentials |

---

## Phase status

| Phase | Description | Status |
|-------|-------------|--------|
| Phase 0 | Environment setup (LocalWP clone of live site) | ✅ Complete |
| Phase 1 | Theme scaffold — all structural files, build tooling | ✅ Complete |
| **Phase 2** | **Real header + footer** | **⏳ Next** |
| Phase 3 | 9 custom blocks | ⏳ Pending |
| Phase 4 | Content build with Irene | ⏳ Pending |
| Phase 5 | QA + launch | ⏳ Pending |
| Phase 6 | Handoff to Irene + Jento | ⏳ Pending |

### Task 11 — Activate theme (USER ACTION REQUIRED)

Theodore must go to `chosen.local/wp-admin` → Appearance → Themes → activate **Chosen Theme**.

Verify after activation:
- `chosen.local` loads without PHP errors
- Block editor palette shows 10 brand colours (navy/royal/gold/red/orange/yellow/teal/white/paper/black)
- Work Sans and Anton appear in editor font selector
- No 404 on `assets/css/main.css`

---

## What Phase 1 built (all committed to git)

```
chosen-theme/
├── style.css                    Theme header (name, version, text-domain)
├── index.php                    Required empty WP recognition file
├── .gitignore                   node_modules/ only
├── theme.json                   10 brand colours, 3 font families, 11 font sizes, 720/1200px layout
├── tailwind.config.js           Mirrors theme.json tokens exactly
├── package.json                 npm scripts: start (watch), build (production)
├── functions.php                setup, enqueue, constants, require inc/
├── inc/
│   ├── block-registration.php   Empty — add register_block_type() per block
│   ├── menus.php                primary + footer nav menus registered
│   └── security.php             xmlrpc disabled, wp_generator removed
├── src/
│   ├── css/input.css            @tailwind base/components/utilities + prefers-reduced-motion
│   └── index.js                 Placeholder so wp-scripts doesn't error on empty src/
├── assets/
│   ├── css/main.css             Compiled Tailwind (COMMITTED — server never runs npm)
│   ├── js/                      Compiled block JS (empty until Phase 3)
│   └── img/                     Empty — awaiting logo SVG from Irene/Jento
├── templates/
│   ├── index.html               REQUIRED WP fallback
│   ├── front-page.html          Home (wraps post-content in full-width main)
│   ├── page.html                Default page (constrained)
│   └── single.html              Single post (constrained)
├── parts/
│   ├── header.html              Minimal navy placeholder — Phase 2 will replace
│   └── footer.html              Minimal navy placeholder — Phase 2 will replace
├── patterns/                    Empty — populated in Phase 3
├── design-system/               Full design system (renamed from "Chosen 2025 Design System/")
│   ├── README.md                Brand guidelines (canonical — read this)
│   ├── colors_and_type.css      All CSS custom properties (canonical token source)
│   ├── assets/                  Logo PNGs + conference photography
│   ├── preview/                 25 HTML component examples (visual reference for every block)
│   └── uploads/                 Hi-res photography + source brand files
├── .claude/
│   ├── settings.json            Pre-approved bash permissions
│   ├── commands/
│   │   ├── new-block.md         /new-block <name> scaffold command
│   │   └── chosen-design.md     /chosen-design brand context loader
│   └── agents/
│       ├── marketing-content-creator.md
│       └── engineering-cms-developer.md
└── docs/
    ├── session-handoff.md       THIS FILE
    ├── implementation-log.md    Phase-by-phase status log
    ├── adr/
    │   ├── 001-block-theme-over-classic.md
    │   ├── 002-tailwind-with-wp-scripts.md
    │   └── 003-deploy-theme-only.md
    └── superpowers/
        └── plans/
            └── 2026-04-30-chosen-theme-phase-1-scaffold.md
```

**Commits (most recent first):**
```
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

## Phase 2 — Real header + footer

**Before starting:** Confirm Task 11 (theme activated) is done. Then:

### Header (`parts/header.html`)

The header is the most brand-critical surface. Spec:

- **Sticky** nav bar, navy `#0B0A55` background, 64px tall, full-width
- **Logo left:** CHOSEN wordmark where the "O" is replaced inline by `chosen-mark.png` (the Tau-circle medallion). See `design-system/preview/type-wordmark.html` for the exact technique — a `<span>` with `background-image: url('chosen-mark.png')`, matching cap-height dimensions, sits in place of the "O" glyph.
- **Nav links centre/right:** Work Sans Medium, white, no underline, hover gold
- **CTA far right:** "Register" pill button — gold bg `#EDA90C`, navy text, Work Sans 700, `letter-spacing: 0.08em`, `text-transform: uppercase`, `padding: 12px 22px`, `border-radius: 999px`. Links to `CHOSEN_REGISTER_URL` constant.
- Logo file: `design-system/assets/chosen-logo-white.png` (white version for dark header)
- Reference: `design-system/preview/components-topnav.html`

### Footer (`parts/footer.html`)

- Navy `#0B0A55` background, full-width
- Logo (white version) top-left
- Address: 140 Westbourne Grove, Northcote VIC 3070
- Social icons: Lucide (map-pin, instagram, facebook) — 24px, 1.75px stroke, `currentColor` white
- Scripture pull-quote at bottom (see scripture pull spec in Phase 3)
- Copyright: © 2026 Jesus Youth Australia. All rights reserved.

---

## Phase 3 — Custom blocks (build order)

**BEFORE STARTING PHASE 3:** Run `/impeccable:teach-impeccable` pointing at `design-system/README.md` and `design-system/colors_and_type.css`. This creates the impeccable design context file for this project. Then use `/impeccable:critique` and `/impeccable:polish` on each block.

**Block development pattern per block:**
1. `/new-block <name>` — scaffold (see `.claude/commands/new-block.md`)
2. Define attributes in `block.json`
3. Build `edit.js` with `useBlockProps()` + `InspectorControls`
4. Build `render.php` with Tailwind classes + escaped output
5. Add `register_block_type( __DIR__ . '/../src/blocks/<name>' );` to `inc/block-registration.php`
6. `npm run build` → test in editor and front-end
7. `/impeccable:critique` → `/impeccable:polish`
8. Commit: `feat(blocks): add <name> block`

### Block 1 — Hero (`chosen/hero`)

Preview refs: `components-date-venue.html`, `brand-radiant-rays.html`, `brand-logos.html`

- Full-bleed photo background: `design-system/assets/photo-arms-raised-worship.jpg`
- 60% navy scrim over photo for text legibility
- **Radiant rays** rotating behind: 24 rays in vanilla JS, cycling 6 brand colours (`#F71A1D`, `#FE4E0E`, `#EDA90C`, `#EBC903`, `#4071AC`, `#37BCB1`). `clipPath: polygon(0 40%, 100% 0, 100% 100%, 0 60%)`. Container: `animation: spin 60s linear infinite`. Off with `prefers-reduced-motion`.
- Photo: slow Ken Burns — `animation: kenBurns 20s ease-in-out infinite` (≤4% scale). Off with `prefers-reduced-motion`.
- **Anton headline:** "BE RADIANT" — huge, display weight, white, centred
- **Red accent:** "Be Radiant" theme word/theme text in `#F71A1D`
- **Date/venue block:** Gold box, Anton display — "19–22" at 56px + "NOV '26" at 18px. Work Sans venue name + city.
- **Primary CTA:** gold pill "Register" → `CHOSEN_REGISTER_URL`. Subtle `box-shadow` pulse every 4s (gold glow).
- Wordmark treatment: CHOSEN with "O" replaced by chosen-mark.png medallion (same as header but hero scale)

### Block 2 — Marquee (`chosen/marquee`)

- Infinite CSS scroll strip, navy background
- Gold and white alternating text items
- Smooth with `cubic-bezier(0.22, 0.61, 0.36, 1)` (ease-out-quart)
- Pauses on hover (`animation-play-state: paused`)
- Items editable via block attributes (repeatable text array)

### Block 3 — Vision (`chosen/vision`)

Preview refs: `components-scripture-pull.html`, `type-body-scripture.html`

- Navy background
- Structure (top to bottom):
  1. **Eyebrow label** — ALL CAPS, `letter-spacing: 0.18em`, gold `#EDA90C`, Work Sans, `font-size: 11px`
  2. **Mission headline** (h2) — Anton display, white, large
  3. **Gold rule** — 48px wide, 3px tall, `#EDA90C`
  4. **Scripture text** — Work Sans Light 300, italic, `font-size: 22px`, white
  5. **Scripture cite** — `font-size: 11px`, `letter-spacing: 0.18em`, gold
- Scroll entrance: scripture fades in with upward `translateY(16px → 0)`, 200ms ease-out. Off with `prefers-reduced-motion`.

### Block 4 — Stat Strip (`chosen/stat-strip`)

Preview ref: `colors-semantic.html`

- Warm paper `#FAF8F3` or royal blue `#4071AC` background
- 4 stats (editable numbers + labels via block attributes)
- Numbers count up from 0 to value when section enters viewport (Intersection Observer)
- Counter uses vanilla JS or CSS custom counter
- Layout: 4-column grid on desktop, 2-column on mobile

### Block 5 — Expect Tile Grid (`chosen/expect-tile-grid`)

Preview refs: `components-cards.html`, `components-badges.html`

- 8 tiles (editable icon + heading + description per tile)
- Card style: white bg, 1px navy border, 8px radius
- Hover state: 4px gold top accent bar slides in + `translateY(-4px)` lift + navy shadow deepens
- Scroll entrance: staggered — each tile delays 50ms more than previous. Off with `prefers-reduced-motion`.
- Layout: 4-col desktop, 2-col tablet, 1-col mobile

### Block 6 — Image Mosaic (`chosen/image-mosaic`)

- CSS grid photo layout using all 7 photos from `design-system/assets/`:
  - `photo-arms-raised-worship.jpg`
  - `photo-adoration-monstrance.jpg`
  - `photo-orange-prayer-tent.jpg`
  - `photo-painting-candlelit.jpg`
  - `photo-sister-laughing.jpg`
  - `photo-worship-keyboard.jpg`
  - `photo-eucalyptus-bush.jpg`
- Hover: warm gold tint overlay (6% opacity)
- Images load with fade-in blur-to-sharp effect
- Photos are hard-edged (radius 0) full-bleed

### Block 7 — CTA Banner (`chosen/cta-banner`)

Preview refs: `components-buttons.html`, `components-scripture-pull.html`

- Navy background
- Anton headline (editable)
- Primary gold pill button → `CHOSEN_REGISTER_URL`. Gold glow pulse every 4s.
- Scripture pull-quote beneath button (eyebrow → verse → rule → cite)
- Button spec: `background: #EDA90C`, `color: #0B0A55`, `font-weight: 700`, `letter-spacing: 0.08em`, `text-transform: uppercase`, `padding: 12px 22px`, `border-radius: 999px`. Hover darkens to `#C8890A`. Active: `translateY(1px)` + overlay.

### Block 8 — Sponsor Strip (`chosen/sponsor-strip`)

- Warm paper `#FAF8F3` background
- Logo row — editable via ACF repeater (image + optional link per sponsor)
- Logos greyscale at rest, colour on hover (transition 200ms)
- Register ACF field group as PHP in `inc/` (not just DB — so it deploys with theme)

### Block 9 — Quote (`chosen/quote`)

Preview refs: `components-scripture-pull.html`, `type-body-scripture.html`

- Standalone scripture block, used between sections
- Same structure as Vision block scripture section:
  - Eyebrow → italic light verse → 48px gold rule → cite
- Editable eyebrow, verse text, citation via block attributes
- Background: navy or warm paper (toggle attribute)

---

## Design system rules — non-negotiable

**Colours:**
- Navy `#0B0A55` — dominant dark surface colour
- Gold `#EDA90C` — primary accent (CTAs, eyebrow labels, scripture cites, gold rules)
- Red `#F71A1D` — theme word "Be Radiant", urgency only — not links
- Royal Blue `#4071AC` — secondary section backgrounds
- Warm Paper `#FAF8F3` — light section backgrounds (never pure white)

**Typography:**
- Work Sans — all body, UI, navigation, labels (300/400/500/600/700/800/900)
- Anton — display/hero headlines ONLY. Never body copy.
- Eyebrow labels: ALL CAPS, `letter-spacing: 0.18em`, gold colour, 11px

**Voice:**
- Invitational not promotional ("Come and encounter" not "Don't miss out")
- Scripture-anchored — every major surface has a verse
- We/us/our for community; you/your for the reader
- Australian English (evangelise, colour, programme)
- CHOSEN in all caps; "conference" lowercase
- No emoji on official surfaces

**NEVER:**
- Glassmorphism
- Parallax scrolling
- Bounce/spring animations
- Generic SaaS dark gradients
- Instrument Serif / Archivo Black / Inter (prototype fonts — discarded)

**Delight approach: "Warm reverence + quiet joy"**
- Ken Burns on hero photos (15–20s, ≤4% scale)
- Radiant rays rotate at 60s/turn
- Scripture fades in with upward translate (200ms ease-out)
- Stats count up on scroll (Intersection Observer)
- All animations off with `prefers-reduced-motion: reduce`

---

## Critical technical notes (learned this session)

1. **`assets/` is committed to git.** `assets/css/main.css` and `build/` are committed — the live server never runs npm. Run `npm run build` before every commit that changes CSS or block JS.

2. **Template part HTML files don't execute PHP.** `parts/header.html`, `parts/footer.html`, and `templates/*.html` are pure HTML with block markup. PHP code in them will render as literal text. All dynamic output goes through custom block `render.php` files.

3. **`src/index.js` must exist.** `@wordpress/scripts` errors if `src/` has no entry point. The placeholder file keeps the build happy until real block JS is added.

4. **Symlink path.** The repo is symlinked to LocalWP: `/Users/theodorexavier/Desktop/repo/chosen-theme` → `~/Local Sites/chosen-local/app/public/wp-content/themes/chosen-theme`. Note: the path is `~/Local Sites/` (with space), NOT `~/Library/Application Support/Local/sites/`.

5. **ACF field groups must be registered as PHP.** ACF field group definitions live in the database by default. Any field groups built in Phase 3 must be exported as PHP (`acf_add_local_field_group()`) and saved to `inc/` so they deploy with the theme folder.

6. **Deployment is theme-folder only — never re-export .wpress.** Zip `chosen-theme/` and upload via WP Admin, or `git push` + live server pulls. Re-exporting .wpress would wipe the live database.

7. **`CHOSEN_REGISTER_URL` is an empty constant.** Defined in `functions.php`. Use it everywhere a registration link appears. Populate it when Jento provides the real signup URL.

8. **Block namespace:** All custom blocks use `chosen/<kebab-case>`. PHP functions use `chosen_<snake_case>`. Constants use `CHOSEN_<UPPER_SNAKE>`.

---

## Open items (pending from stakeholders)

- [ ] Logo SVG hand-off (Irene/Jento) — replace `assets/img/chosen-logo-white.png` and `assets/img/chosen-mark.png` with `chosen-logo-white.svg` + `chosen-mark.svg` when supplied. Keep PNGs as fallback for email/poster ui_kits.
- [ ] `BeRadiant ripples.eps` — radiant ray vector (currently recreating in CSS/JS)
- [ ] `CHOSEN_REGISTER_URL` — real external signup app URL
- [ ] Display typeface confirmation (Anton is substitute — confirm or supply original)
- [ ] Plugin change approval from Jento (Hello Dolly, Inspiro, WPZOOM deactivated; ACF + Fluent Forms installed)
- [ ] Annual tagline confirmation ("Be Radiant" — confirm with Irene)
- [ ] **All on-page body copy is currently DRAFT — pending Irene approval.** The Content Creator subagent drafted hero subhead, vision headline + body, expect-tile descriptions, CTA banner headline + subhead, and stat labels using the voice rules in `.impeccable.md` and the approved samples in `design-system/preview/brand-voice.html` — but the exact words are not from any approved source. **Verbatim canonical sources** used as-is on the page are: Psalm 34:5 (theme verse), Isaiah 60:1 (quote block), John 15:16 (CTA banner scripture), and the eyebrow `Theme · Chosen 2026` from `components-scripture-pull.html`. Everything else needs Irene to either approve or rewrite. See `templates/front-page.html` for what's currently live; the source JSON the agent produced is in commit `b7e9909`.

---

## Key commands

```bash
# Start dev watch (Tailwind + block JS)
npm run start

# Production build (run before every commit touching CSS/JS)
npm run build

# Scaffold a new block
/new-block <name>

# Load brand context into any session
/chosen-design
```

---

## Files to read at session start (in order)

1. `docs/session-handoff.md` — this file (orientation + what's next)
2. `CLAUDE.md` — project instructions, naming conventions, principles
3. `design-system/README.md` — full brand guidelines
4. `design-system/colors_and_type.css` — all CSS custom properties
5. `design-system/preview/<relevant-component>.html` — before building each block
