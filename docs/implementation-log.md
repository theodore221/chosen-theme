# Chosen Theme — Implementation Log

**For full session context, plans, and remaining work: read `docs/session-handoff.md` first.**

---

## Current status

| Phase | Status | Notes |
|-------|--------|-------|
| Phase 0 — Environment | ✅ Complete | LocalWP running at chosen.local |
| Phase 1 — Scaffold | ✅ Complete | All structural files built and committed |
| **Task 11 — Activate theme** | **⏳ USER ACTION** | Go to chosen.local/wp-admin → Appearance → Themes → activate Chosen Theme |
| Phase 2 — Header + footer | ⏳ Pending activation | Real nav, wordmark, footer |
| Phase 3 — 9 custom blocks | ⏳ Pending Phase 2 | Run /impeccable:teach-impeccable first |
| Phase 4 — Content | ⏳ Pending assets from Irene | Logo SVG, real signup URL needed |
| Phase 5 — QA + launch | ⏳ Pending content | Lighthouse, WCAG AA, cross-browser |
| Phase 6 — Handoff | ⏳ Pending launch | Editor training doc for Irene |

---

## Commit history

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

## Known issues / gotchas

- Template parts (.html) don't execute PHP — all dynamic output via render.php in custom blocks
- ACF field groups built in Phase 3 must be exported as PHP to `inc/` to deploy with theme
- `CHOSEN_REGISTER_URL` is empty until Jento provides real signup URL
- Logo PNG only (no SVG yet) — `assets/img/` has a .gitkeep placeholder
- Anton is a substitute display font — confirm with Irene/Jento before launch
