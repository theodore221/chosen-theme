# ADR 002 — Tailwind CSS with @wordpress/scripts

**Date:** 2026-04-30  
**Status:** Accepted

## Context

Styling needs to be consistent, maintainable, and token-driven. Options considered:

- **Plain CSS** — no build step, but no design tokens, verbose, hard to audit
- **Tailwind CSS (CDN)** — zero build, but full utility set served to every visitor; no purging; not acceptable for production
- **Tailwind CSS (CLI build)** — purged, minified, fast; requires a build step
- **CSS-in-JS / Styled Components** — incompatible with WordPress block server rendering

## Decision

Use **Tailwind CSS v3** (CLI, not CDN) compiled with `npm run build`. Run concurrently alongside `@wordpress/scripts` for block JS bundling.

## Reasons

1. **Token mirroring.** `tailwind.config.js` mirrors `theme.json` tokens exactly — one source of truth (`design-system/colors_and_type.css`), expressed in two formats WP and Tailwind each need.
2. **Utility-first reduces custom CSS.** Almost all styling on custom blocks can be expressed as Tailwind utilities in `render.php` class attributes, minimising the surface area of custom CSS.
3. **Compiled assets committed.** `assets/css/main.css` is committed to git — the live server never runs npm. This is the correct pattern for WP theme deployment.
4. **`concurrently` runs both watchers.** `npm run start` spins up `wp-scripts start` (block JS HMR) and `tailwindcss --watch` in a single terminal.

## Trade-offs

- Build step required before testing on live. Mitigated by committing built assets.
- `tailwind.config.js` must stay in sync with `theme.json` manually. Accepted — both files are small and there is a clear convention (navy `#0B0A55` in one = `chosen-navy: '#0B0A55'` in the other).
- Tailwind utility classes are verbose in PHP template strings. Acceptable for this scale.
