# Chosen Theme — Implementation Log

**For full session context, plans, and remaining work: read `docs/session-handoff.md` first.**

---

## Current status

| Phase | Status | Notes |
|-------|--------|-------|
| Phase 0 — Environment | ✅ Complete | LocalWP running at chosen.local |
| Phase 1 — Scaffold | ✅ Complete | All structural files built and committed |
| Task 11 — Activate theme | ✅ Complete | User-confirmed via WP Admin |
| **Phase 2 — Header + footer** | **⏳ Awaiting visual verification** | Code shipped (5 commits); user to verify on chosen.local |
| Phase 3 — 9 custom blocks | ⏳ Pending Phase 2 verification | `.impeccable.md` already shipped (Task 0) |
| Phase 4 — Content | ⏳ Pending assets from Irene | Logo SVG, real signup URL needed |
| Phase 5 — QA + launch | ⏳ Pending content | Lighthouse, WCAG AA, cross-browser |
| Phase 6 — Handoff | ⏳ Pending launch | Editor training doc for Irene |

---

## Commit history

```
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

## Known issues / gotchas

- Template parts (.html) don't execute PHP — all dynamic output via render.php in custom blocks
- ACF field groups built in Phase 3 must be exported as PHP to `inc/` to deploy with theme
- `CHOSEN_REGISTER_URL` is empty until Jento provides real signup URL
- Logo PNG only (no SVG yet) — `assets/img/` has a .gitkeep placeholder
- Anton is a substitute display font — confirm with Irene/Jento before launch
