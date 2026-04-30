# ADR 001 — Block Theme over Classic Theme

**Date:** 2026-04-30  
**Status:** Accepted

## Context

Chosen Conference needs a custom WordPress theme for a marketing/promo landing page targeting Gen Z Australians. The site is display-only: hype, vision, registration CTA. No membership, payments, or complex interactivity.

Two viable approaches: classic PHP theme or Full Site Editing (FSE) block theme.

## Decision

Use a **WordPress FSE block theme** (block theme with `templates/` HTML files and `theme.json` design tokens).

## Reasons

1. **Future direction of WordPress.** Block themes are the official direction since WP 5.9. Classic themes will eventually require compatibility shims.
2. **theme.json feeds the block editor palette.** Brand colours and typefaces appear automatically in the editor sidebar — content editors (Irene) get guardrails without custom Gutenberg configuration.
3. **No page builder needed.** FSE's Site Editor handles layout composition. Classic themes require ACF Flexible Content, Elementor, or similar to give editors layout control.
4. **Deployment is theme-folder only.** A block theme is a self-contained folder — zip and upload. No full-site export needed.

## Trade-offs

- WP block editor HTML syntax (`<!-- wp:... -->`) is unfamiliar to a developer coming from React/Next.js. Mitigated by the Next.js → WP mental model translation in `CLAUDE.md`.
- Block theme templates can't execute PHP directly — dynamic output requires server-rendered blocks (`render.php`). This is acceptable: all dynamic content on this site goes through custom blocks.
- FSE is still maturing (WP 6.4+). Some edge cases require workarounds. WP 6.9 is the tested-up-to target; the codebase will stay on v3 `theme.json`.
