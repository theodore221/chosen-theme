# Chosen Theme — Claude Code Instructions

Custom WordPress block theme for `chosenconference.org.au`.

For full project plan, scope, phases, and open questions: see `chosen-website-plan.md` in the Obsidian vault. This file is operational ("how we work on this codebase"); the plan doc is strategic ("what we're building and why").

## Snapshot

- **Type:** Marketing/promo site (build hype, communicate vision, link out to external signup app)
- **Not building:** registration, payments, auth, schedule complexity, transactional anything
- **Maybe building later:** speaker showcase (CPT scaffolded, populated when content ready)
- **Owner:** Theodore  •  **Content lead:** Irene  •  **Site admin:** Jento (JY Australia)

## Tech stack

- WordPress 6.4+ block theme (FSE)
- PHP 8.0+
- Tailwind CSS v3 (compiled, not CDN)
- `@wordpress/scripts` for block bundling
- LocalWP for local dev
- **No page builders.** Ever.

## Architecture

```
chosen-theme/
├── style.css            theme metadata
├── theme.json           design tokens
├── functions.php        setup, enqueue, block registration
├── inc/                 modular includes (cpts, menus, security)
├── src/
│   ├── css/input.css    Tailwind source → assets/css/main.css
│   └── blocks/<n>/      block.json + render.php + edit.js
├── templates/           FSE templates (HTML w/ block markup)
├── parts/               header, footer
└── patterns/            reusable block compositions
```

## Mental model translation (Next.js → WP block theme)

| Next.js                         | This codebase                         |
|---------------------------------|---------------------------------------|
| `tailwind.config.js` + tokens   | `theme.json`                          |
| `pages/`                        | `templates/`                          |
| Layout components               | `parts/`                              |
| React components                | Custom blocks (`src/blocks/<n>/`)  |
| Component props                 | Block attributes (`block.json`)       |
| `next.config.js`, `lib/setup`   | `functions.php`, `inc/*`              |

## Naming

- Blocks: `chosen/<kebab-case>` — `chosen/hero`, `chosen/marquee`
- CPTs: `chosen_<snake_case>` — `chosen_speaker`
- PHP functions: `chosen_<snake_case>`
- Constants: `CHOSEN_<UPPER_SNAKE>` — e.g., `CHOSEN_REGISTER_URL`
- Templates: lowercase hyphenated — `front-page.html`, `single-chosen_speaker.html`

## Build commands

```bash
npm run start    # watch mode (Tailwind + blocks)
npm run build    # production build
```

## Principles

1. **Editor UX matters as much as front-end output.** Non-tech editors will use this. Block fields need clear labels, sensible defaults, placeholder text.
2. **Tailwind first.** Custom CSS only when utilities aren't enough.
3. **Server-rendered for dynamic content.** Use `render.php`, not `save.js`, for anything reading from CPTs/DB.
4. **Accessibility is non-negotiable.** Semantic HTML, alt text required as block attributes, keyboard nav tested, WCAG AA contrast minimum.
5. **Mobile-first.** Most Gen Z visitors arrive on phones.
6. **Lean dependencies.** Justify every new package.

## What NOT to do

- ❌ Install Elementor / Bricks / Divi / WPBakery / Visual Composer
- ❌ Modify Inspiro theme files (we're moving off it)
- ❌ Hardcode the registration URL — use `CHOSEN_REGISTER_URL`
- ❌ Use `save.js` for blocks pulling dynamic data — use `render.php`
- ❌ Inline `<style>` blocks — Tailwind utilities or `style.css`
- ❌ jQuery — vanilla JS or React via `@wordpress/element`
- ❌ Touch WP core files

## Block development pattern

For each new custom block:

1. `/new-block <n>` (see `.claude/commands/new-block.md`)
2. Define attributes in `block.json` — start minimal
3. `edit.js` — `useBlockProps()`, settings in `<InspectorControls>`, content inline
4. `render.php` — Tailwind classes, semantic HTML, escape all output
5. `npm run build`, test in editor and front-end
6. Commit: `feat(blocks): add <n> block`

## TODO — brand tokens

Replace placeholders once Irene/Jento provide assets:

- [ ] Logo SVG → `assets/img/logo.svg`
- [ ] Brand palette → `theme.json` `color.palette` + `tailwind.config.js` `colors`
- [ ] Brand typography → `theme.json` `typography.fontFamilies` + `tailwind.config.js` `fontFamily`
- [ ] `CHOSEN_REGISTER_URL` → real signup app URL

## Slash commands

- `/new-block <n>` — scaffold a custom block trio
- `/new-cpt <n>` — register a custom post type
