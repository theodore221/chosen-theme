# Chosen Theme — Phase 0 Completion + Phase 1 Scaffold

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Complete the local environment setup and scaffold a fully activatable WordPress FSE block theme with all design system tokens wired in, Claude Code tooling in place, and Tailwind + @wordpress/scripts building cleanly — no blocks yet, but the foundation is solid.

**Architecture:** Custom WordPress FSE block theme. Design tokens defined once in `design-system/colors_and_type.css`, derived into `theme.json` (for the block editor) and `tailwind.config.js` (for utility classes). Tailwind CLI and wp-scripts run concurrently via `concurrently`. All compiled assets are committed to git (server won't run npm). Theme linked to LocalWP via symlink so the git repo stays in place.

**Tech Stack:** WordPress 6.9.4, PHP 8.2, Tailwind CSS v3, @wordpress/scripts v30, concurrently v9, LocalWP (nginx + MySQL 8.0)

---

## File Map

| File | Action | Responsibility |
|------|--------|---------------|
| `style.css` | Create | Theme identity header — name, version, text-domain |
| `index.php` | Create | Required empty file — WP won't recognise theme without it |
| `theme.json` | Create | All design tokens: colours, fonts, spacing, layout widths |
| `functions.php` | Create | Enqueue styles/scripts, Google Fonts, theme support, constants, require inc/ |
| `inc/block-registration.php` | Create | register_block_type() for all custom blocks (empty initially) |
| `inc/menus.php` | Create | register_nav_menus() |
| `inc/security.php` | Create | Disable xmlrpc, hide WP version, remove junk head tags |
| `src/css/input.css` | Create | Tailwind directives + design system import |
| `tailwind.config.js` | Create | Token mirror of theme.json + content paths |
| `package.json` | Create | npm scripts: start (watch), build (prod) |
| `templates/index.html` | Create | Required fallback template |
| `templates/front-page.html` | Create | Home page (empty content area, wires header + footer) |
| `templates/page.html` | Create | Default page template |
| `templates/single.html` | Create | Single post template |
| `parts/header.html` | Create | Minimal placeholder (full build in Phase 2) |
| `parts/footer.html` | Create | Minimal placeholder (full build in Phase 2) |
| `assets/css/main.css` | Generated | Compiled Tailwind output — committed to git |
| `assets/img/.gitkeep` | Create | Keeps img/ folder in git before logo arrives |
| `.gitignore` | Create | Ignore node_modules/ only |
| `design-system/` | Rename | Move "Chosen 2025 Design System/" → `design-system/` |
| `.claude/settings.json` | Create | Pre-approved Bash permissions |
| `.claude/commands/new-block.md` | Create | /new-block scaffold command |
| `.claude/commands/chosen-design.md` | Create | /chosen-design brand context command |
| `.claude/agents/marketing-content-creator.md` | Copy | From ~/Downloads/ |
| `.claude/agents/engineering-cms-developer.md` | Copy | From ~/Downloads/ |
| `docs/phases-plan.md` | Create | Copy of approved phases plan |
| `docs/adr/001-block-theme-over-classic.md` | Create | ADR: why FSE block theme |
| `docs/adr/002-tailwind-with-wp-scripts.md` | Create | ADR: build tooling decision |
| `docs/adr/003-deploy-theme-only.md` | Create | ADR: theme-only deployment |

---

## Task 1: Finish Phase 0 — Plugin cleanup and permalink flush

**Files:** None (WP Admin actions)

- [ ] **Step 1: Open WP Admin**

Go to `http://chosen.local/wp-admin` and log in with your live site credentials.

- [ ] **Step 2: Flush permalinks**

WP Admin → Settings → Permalinks → click **Save Changes** (no changes needed — just saving flushes the rewrite rules). This prevents broken URLs after the import.

- [ ] **Step 3: Deactivate and delete unwanted plugins**

WP Admin → Plugins. Deactivate then delete each of these if present:
- Hello Dolly
- Inspiro (or any WPZOOM plugin)
- Any other WPZOOM extensions

Keep: Akismet, LiteSpeed Cache, CookieAdmin Pro, The Icon Block, All-in-One WP Migration, WP Migrate Lite.

- [ ] **Step 4: Install ACF Free**

Plugins → Add New → search "Advanced Custom Fields" by WP Engine → Install Now → Activate.

- [ ] **Step 5: Install Fluent Forms**

Plugins → Add New → search "Fluent Forms" by WPManageNinja → Install Now → Activate.

- [ ] **Step 6: Verify**

Visit `http://chosen.local` — the site should load (even if it looks broken without the right theme, that's fine). No white screen of death = success.

---

## Task 2: Link the theme repo to LocalWP

The git repo lives at `/Users/theodorexavier/Desktop/repo/chosen-theme/`. LocalWP expects themes at `~/Library/Application Support/Local/sites/chosen-local/app/public/wp-content/themes/`. A symlink connects them without moving the repo.

**Files:** Symlink only (no repo files change)

- [ ] **Step 1: Create the symlink**

Open Terminal and run:
```bash
ln -s "/Users/theodorexavier/Desktop/repo/chosen-theme" \
  "$HOME/Library/Application Support/Local/sites/chosen-local/app/public/wp-content/themes/chosen-theme"
```

- [ ] **Step 2: Verify LocalWP sees the theme**

```bash
ls "$HOME/Library/Application Support/Local/sites/chosen-local/app/public/wp-content/themes/"
```

Expected output includes `chosen-theme` alongside any other themes.

- [ ] **Step 3: Confirm git still works**

```bash
cd /Users/theodorexavier/Desktop/repo/chosen-theme
git status
```

Expected: shows untracked files as before — git is unaffected by the symlink.

---

## Task 3: Rename design system folder and move agents

- [ ] **Step 1: Rename the design system folder**

```bash
cd /Users/theodorexavier/Desktop/repo/chosen-theme
mv "Chosen 2025 Design System" design-system
```

- [ ] **Step 2: Copy agent files from Downloads**

```bash
mkdir -p .claude/agents
cp ~/Downloads/marketing-content-creator.md .claude/agents/
cp ~/Downloads/engineering-cms-developer.md .claude/agents/
```

- [ ] **Step 3: Verify**

```bash
ls design-system/
# Expected: README.md  SKILL.md  colors_and_type.css  assets/  preview/  ui_kits/

ls .claude/agents/
# Expected: engineering-cms-developer.md  marketing-content-creator.md
```

- [ ] **Step 4: Commit**

```bash
git add design-system/ .claude/agents/
git commit -m "chore: rename design system folder and add custom agents"
```

---

## Task 4: Create Claude Code tooling

- [ ] **Step 1: Create `.claude/settings.json`**

```bash
mkdir -p .claude/commands
```

Create `.claude/settings.json`:
```json
{
  "permissions": {
    "allow": [
      "Bash(npm run *)",
      "Bash(wp *)",
      "Bash(find . *)",
      "Bash(grep *)"
    ]
  }
}
```

- [ ] **Step 2: Create `.claude/commands/new-block.md`**

Create `.claude/commands/new-block.md`:
```markdown
---
description: Scaffold a new custom Gutenberg block for the chosen-theme
---

Create a new WordPress block with the namespace `chosen/<name>` inside `src/blocks/<name>/`.

Create these three files:

**`src/blocks/<name>/block.json`:**
```json
{
  "$schema": "https://schemas.wp.org/trunk/block.json",
  "apiVersion": 3,
  "name": "chosen/<name>",
  "title": "<Title>",
  "category": "chosen",
  "description": "",
  "supports": { "html": false },
  "attributes": {},
  "editorScript": "file:./index.js",
  "render": "file:./render.php"
}
```

**`src/blocks/<name>/edit.js`:**
```js
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function Edit( { attributes, setAttributes } ) {
  const blockProps = useBlockProps();
  return (
    <>
      <InspectorControls>
        <PanelBody title={ __( 'Settings', 'chosen-theme' ) }>
          {/* Add controls here */}
        </PanelBody>
      </InspectorControls>
      <div { ...blockProps }>
        <p>{ __( 'chosen/<name> — edit view', 'chosen-theme' ) }</p>
      </div>
    </>
  );
}
```

**`src/blocks/<name>/render.php`:**
```php
<?php
/**
 * chosen/<name> block render callback.
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    Inner block content.
 * @param WP_Block $block      Block instance.
 */
?>
<section <?php echo get_block_wrapper_attributes( [ 'class' => 'chosen-<name>' ] ); ?>>
  <!-- render output here -->
</section>
```

Then add a `register_block_type()` call in `inc/block-registration.php`:
```php
register_block_type( __DIR__ . '/../src/blocks/<name>' );
```

Run `npm run build` after scaffolding.
```

- [ ] **Step 3: Create `.claude/commands/chosen-design.md`**

Create `.claude/commands/chosen-design.md`:
```markdown
---
description: Load the Chosen Conference brand context — colours, typography, voice, and design principles
---

## Chosen Conference — Brand Context

Read the following files to get full brand context before any design or content work:

- `design-system/README.md` — complete brand guidelines (colour meanings, voice, typography, animation rules)
- `design-system/colors_and_type.css` — all CSS custom properties (canonical token source)
- `design-system/preview/brand-voice.html` — 4 voice examples (invitational, mission-driven, scripture-anchored, bold & youthful)
- `design-system/preview/type-display.html` — Anton display treatment: "Be Radiant" in red/gold
- `design-system/preview/components-buttons.html` — primary/secondary/ghost button specs

### Key brand rules (memorise these)
- **Navy `#0B0A55`** is the dominant colour for all dark surfaces
- **Gold `#EDA90C`** is the primary accent — CTAs, eyebrow labels, scripture citations, gold rules
- **Red `#F71A1D`** is the passion accent — theme word "Be Radiant", urgency
- **Work Sans** for all body/UI text; **Anton** for display/hero headlines only
- Voice: invitational not promotional; scripture-anchored; Australian English; no emoji on official surfaces
- CHOSEN in all caps; "conference" lowercase; eyebrow labels ALL CAPS with `letter-spacing: 0.18em`
- No glassmorphism; no parallax; no bounce animations; no generic SaaS gradients
```

- [ ] **Step 4: Verify tooling**

```bash
ls .claude/commands/
# Expected: chosen-design.md  new-block.md

ls .claude/
# Expected: agents/  commands/  settings.json
```

- [ ] **Step 5: Commit**

```bash
git add .claude/
git commit -m "chore: add Claude Code tooling — settings, commands, agents"
```

---

## Task 5: Create core WordPress theme identity files

- [ ] **Step 1: Create `style.css`**

```css
/*
Theme Name: Chosen Theme
Theme URI: https://chosenconference.org.au
Author: Theodore Xavier
Description: Custom WordPress block theme for Chosen Conference 2026 — Be Radiant. Built for Jesus Youth Australia.
Version: 1.0.0
Requires at least: 6.4
Tested up to: 6.9
Requires PHP: 8.0
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: chosen-theme
*/
```

- [ ] **Step 2: Create `index.php`**

```php
<?php
// Silence is golden. Required for WordPress to recognise this as a valid theme.
```

- [ ] **Step 3: Create `.gitignore`**

```
node_modules/
.DS_Store
*.log
```

Note: Do NOT ignore `assets/` — compiled CSS and JS must be committed so the live server can serve them without running npm.

- [ ] **Step 4: Create `assets/img/.gitkeep`**

```bash
mkdir -p assets/img assets/js assets/css
touch assets/img/.gitkeep assets/js/.gitkeep
```

- [ ] **Step 5: Verify**

```bash
ls -la
# Must see: style.css  index.php  .gitignore
```

- [ ] **Step 6: Commit**

```bash
git add style.css index.php .gitignore assets/
git commit -m "feat(theme): add core theme identity files"
```

---

## Task 6: Create `theme.json`

This is WordPress's design token file. The block editor reads it to populate the colour palette, font family picker, and spacing controls in the sidebar. Every value is derived from `design-system/colors_and_type.css`.

- [ ] **Step 1: Create `theme.json`**

```json
{
  "$schema": "https://schemas.wp.org/trunk/theme.json",
  "version": 3,
  "settings": {
    "appearanceTools": true,
    "color": {
      "custom": false,
      "customGradient": false,
      "palette": [
        { "slug": "chosen-navy",  "color": "#0B0A55", "name": "Navy" },
        { "slug": "chosen-royal", "color": "#4071AC", "name": "Royal Blue" },
        { "slug": "chosen-gold",  "color": "#EDA90C", "name": "Gold" },
        { "slug": "chosen-red",   "color": "#F71A1D", "name": "Red" },
        { "slug": "chosen-orange","color": "#FE4E0E", "name": "Orange" },
        { "slug": "chosen-yellow","color": "#EBC903", "name": "Yellow" },
        { "slug": "chosen-white", "color": "#FFFFFF",  "name": "White" },
        { "slug": "chosen-paper", "color": "#FAF8F3", "name": "Warm Paper" },
        { "slug": "chosen-black", "color": "#0A0A0A", "name": "Black" }
      ]
    },
    "typography": {
      "customFontSize": false,
      "fontFamilies": [
        {
          "slug": "work-sans",
          "name": "Work Sans",
          "fontFamily": "'Work Sans', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif"
        },
        {
          "slug": "anton",
          "name": "Anton (Display)",
          "fontFamily": "'Anton', 'Bebas Neue', 'Work Sans', sans-serif"
        }
      ],
      "fontSizes": [
        { "slug": "xs",   "size": "0.75rem",  "name": "XS" },
        { "slug": "sm",   "size": "0.875rem", "name": "Small" },
        { "slug": "base", "size": "1rem",     "name": "Base" },
        { "slug": "lg",   "size": "1.125rem", "name": "Large" },
        { "slug": "xl",   "size": "1.375rem", "name": "XL" },
        { "slug": "2xl",  "size": "1.75rem",  "name": "2XL" },
        { "slug": "3xl",  "size": "2.25rem",  "name": "3XL" },
        { "slug": "4xl",  "size": "3rem",     "name": "4XL" },
        { "slug": "5xl",  "size": "4rem",     "name": "5XL" },
        { "slug": "6xl",  "size": "5.5rem",   "name": "6XL" },
        { "slug": "7xl",  "size": "7.5rem",   "name": "7XL" }
      ]
    },
    "spacing": {
      "units": ["px", "rem", "%", "em", "vw", "vh"]
    },
    "layout": {
      "contentSize": "720px",
      "wideSize": "1200px"
    }
  },
  "styles": {
    "color": {
      "background": "#FFFFFF",
      "text": "#14130F"
    },
    "typography": {
      "fontFamily": "var(--wp--preset--font-family--work-sans)",
      "fontSize": "var(--wp--preset--font-size--base)",
      "lineHeight": "1.5"
    }
  }
}
```

- [ ] **Step 2: Verify JSON is valid**

```bash
node -e "JSON.parse(require('fs').readFileSync('theme.json','utf8')); console.log('theme.json valid')"
```

Expected output: `theme.json valid`

- [ ] **Step 3: Commit**

```bash
git add theme.json
git commit -m "feat(theme): add theme.json with design system tokens"
```

---

## Task 7: Create build tooling

- [ ] **Step 1: Create `package.json`**

```json
{
  "name": "chosen-theme",
  "version": "1.0.0",
  "description": "WordPress block theme for Chosen Conference 2026",
  "scripts": {
    "start": "concurrently \"wp-scripts start\" \"tailwindcss -i ./src/css/input.css -o ./assets/css/main.css --watch\"",
    "build": "wp-scripts build && tailwindcss -i ./src/css/input.css -o ./assets/css/main.css --minify",
    "lint:js": "wp-scripts lint-js",
    "lint:css": "wp-scripts lint-style"
  },
  "devDependencies": {
    "@wordpress/scripts": "^30.0.0",
    "tailwindcss": "^3.4.0",
    "concurrently": "^9.0.0"
  }
}
```

- [ ] **Step 2: Create `tailwind.config.js`**

```js
/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './src/blocks/**/*.{js,php}',
    './parts/**/*.html',
    './templates/**/*.html',
    './patterns/**/*.html',
    './inc/**/*.php',
    './*.php',
  ],
  theme: {
    extend: {
      colors: {
        'chosen-navy':   '#0B0A55',
        'chosen-navy-700': '#15146E',
        'chosen-navy-500': '#2A2A8C',
        'chosen-royal':  '#4071AC',
        'chosen-royal-700': '#2F5A91',
        'chosen-royal-300': '#7C9DC7',
        'chosen-gold':   '#EDA90C',
        'chosen-gold-600': '#C68A06',
        'chosen-gold-300': '#F5C964',
        'chosen-red':    '#F71A1D',
        'chosen-red-700': '#C81216',
        'chosen-orange': '#FE4E0E',
        'chosen-yellow': '#EBC903',
        'chosen-paper':  '#FAF8F3',
      },
      fontFamily: {
        sans:    ["'Work Sans'", 'system-ui', '-apple-system', 'sans-serif'],
        display: ["'Anton'", "'Bebas Neue'", 'sans-serif'],
      },
      letterSpacing: {
        eyebrow: '0.18em',
        wide:    '0.06em',
      },
      transitionTimingFunction: {
        'ease-out-quart': 'cubic-bezier(0.22, 0.61, 0.36, 1)',
        'chosen-move':    'cubic-bezier(0.4, 0, 0.6, 1)',
      },
      boxShadow: {
        'chosen-sm':   '0 1px 2px rgba(11,10,85,0.08)',
        'chosen-md':   '0 6px 18px rgba(11,10,85,0.10), 0 2px 4px rgba(11,10,85,0.06)',
        'chosen-lg':   '0 18px 40px rgba(11,10,85,0.18), 0 4px 10px rgba(11,10,85,0.08)',
        'chosen-glow': '0 0 0 4px rgba(237,169,12,0.25)',
      },
      borderRadius: {
        'chosen-pill': '9999px',
      },
    },
  },
  plugins: [],
};
```

- [ ] **Step 3: Create `src/css/input.css`**

```bash
mkdir -p src/css
```

```css
@tailwind base;
@tailwind components;
@tailwind utilities;

/* Import design system custom properties */
@import url('../../design-system/colors_and_type.css');

/* Base resets */
@layer base {
  *,
  *::before,
  *::after {
    box-sizing: border-box;
  }

  html {
    font-family: var(--font-sans);
    color: var(--fg);
    -webkit-font-smoothing: antialiased;
  }

  /* Respect reduced motion preference */
  @media (prefers-reduced-motion: reduce) {
    *,
    *::before,
    *::after {
      animation-duration: 0.01ms !important;
      animation-iteration-count: 1 !important;
      transition-duration: 0.01ms !important;
    }
  }
}
```

- [ ] **Step 4: Create `src/index.js` placeholder**

`@wordpress/scripts` needs at least one JS entry point or it errors. Create a minimal placeholder now; it gets replaced by real block entries in Phase 3.

```bash
mkdir -p src/blocks
```

Create `src/index.js`:
```js
// Placeholder entry point for @wordpress/scripts.
// Individual block scripts are declared via block.json editorScript
// and auto-discovered in Phase 3. This file prevents a build error
// when no blocks exist yet.
```

- [ ] **Step 5: Install dependencies**

```bash
cd /Users/theodorexavier/Desktop/repo/chosen-theme
npm install
```

Expected: Creates `node_modules/` and `package-lock.json`. No errors.

- [ ] **Step 6: Run initial build**

```bash
npm run build
```

Expected output includes:
- `assets/css/main.css` created (Tailwind output, minified)
- wp-scripts compiles `src/index.js` → `assets/js/index.js` (tiny file, that's fine)
- Exit code 0

If Tailwind errors on the `@import` of `colors_and_type.css`: remove that line from `input.css` (the CSS variables load via `functions.php` `wp_enqueue_style` — that's the correct WordPress approach anyway).

- [ ] **Step 7: Verify output**

```bash
ls assets/css/
# Expected: main.css

wc -c assets/css/main.css
# Expected: >1000 bytes (Tailwind generated something)
```

- [ ] **Step 8: Commit**

```bash
git add package.json package-lock.json tailwind.config.js src/ assets/css/main.css
git commit -m "feat(build): add Tailwind + wp-scripts build pipeline"
```

---

## Task 8: Create `functions.php`

- [ ] **Step 1: Create `inc/` folder**

```bash
mkdir -p inc
touch inc/block-registration.php inc/menus.php inc/security.php
```

- [ ] **Step 2: Create `functions.php`**

```php
<?php
/**
 * Chosen Theme — functions.php
 * Bootstrap file: theme support, enqueue, constants, includes.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'CHOSEN_VERSION', wp_get_theme()->get( 'Version' ) );
define( 'CHOSEN_REGISTER_URL', 'https://chosenconference.org.au/register' ); // Replace with real URL when confirmed.

/**
 * Theme setup.
 */
function chosen_setup(): void {
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'editor-styles' );
    add_editor_style( 'assets/css/main.css' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
}
add_action( 'after_setup_theme', 'chosen_setup' );

/**
 * Enqueue front-end styles and scripts.
 */
function chosen_enqueue_assets(): void {
    // Main compiled stylesheet (Tailwind output).
    wp_enqueue_style(
        'chosen-main',
        get_stylesheet_directory_uri() . '/assets/css/main.css',
        [],
        CHOSEN_VERSION
    );

    // Google Fonts: Work Sans + Anton + Bebas Neue.
    wp_enqueue_style(
        'chosen-fonts',
        'https://fonts.googleapis.com/css2?family=Anton&family=Bebas+Neue&family=Work+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400&display=swap',
        [],
        null
    );

    // Design system CSS custom properties.
    wp_enqueue_style(
        'chosen-design-tokens',
        get_stylesheet_directory_uri() . '/design-system/colors_and_type.css',
        [ 'chosen-fonts' ],
        CHOSEN_VERSION
    );
}
add_action( 'wp_enqueue_scripts', 'chosen_enqueue_assets' );

// Modular includes.
require_once __DIR__ . '/inc/block-registration.php';
require_once __DIR__ . '/inc/menus.php';
require_once __DIR__ . '/inc/security.php';
```

- [ ] **Step 3: Create `inc/menus.php`**

```php
<?php
/**
 * Navigation menu registration.
 */

function chosen_register_menus(): void {
    register_nav_menus( [
        'primary' => __( 'Primary Navigation', 'chosen-theme' ),
        'footer'  => __( 'Footer Navigation', 'chosen-theme' ),
    ] );
}
add_action( 'init', 'chosen_register_menus' );
```

- [ ] **Step 4: Create `inc/security.php`**

```php
<?php
/**
 * Security hardening.
 */

// Disable XML-RPC.
add_filter( 'xmlrpc_enabled', '__return_false' );

// Remove WP version from head and RSS.
remove_action( 'wp_head', 'wp_generator' );

// Remove unnecessary head tags.
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
```

- [ ] **Step 5: Create `inc/block-registration.php`**

```php
<?php
/**
 * Custom block registration.
 * Each block is registered from its block.json file.
 * Add a register_block_type() call here for each block as you build them.
 */

function chosen_register_blocks(): void {
    // Blocks will be registered here as they are built in Phase 3.
    // Example (uncomment when src/blocks/hero/ exists):
    // register_block_type( __DIR__ . '/../src/blocks/hero' );
}
add_action( 'init', 'chosen_register_blocks' );
```

- [ ] **Step 6: Commit**

```bash
git add functions.php inc/
git commit -m "feat(theme): add functions.php, menus, security, block registration stubs"
```

---

## Task 9: Create FSE templates and parts

WordPress FSE block themes use HTML files for templates (pages) and parts (header/footer). These are block markup — WordPress parses them and renders the registered blocks. Right now they're minimal wrappers; the real header and footer design happens in Phase 2.

- [ ] **Step 1: Create `templates/index.html`** (required fallback)

```bash
mkdir -p templates parts
```

```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->
<!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} -->
<main class="wp-block-group">
  <!-- wp:post-content {"layout":{"type":"constrained"}} /-->
</main>
<!-- /wp:group -->
<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

- [ ] **Step 2: Create `templates/front-page.html`**

```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->
<!-- wp:group {"tagName":"main","layout":{"type":"default"}} -->
<main class="wp-block-group">
  <!-- wp:post-content {"layout":{"type":"default"}} /-->
</main>
<!-- /wp:group -->
<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

- [ ] **Step 3: Create `templates/page.html`**

```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->
<!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} -->
<main class="wp-block-group">
  <!-- wp:post-title {"level":1} /-->
  <!-- wp:post-content {"layout":{"type":"constrained"}} /-->
</main>
<!-- /wp:group -->
<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

- [ ] **Step 4: Create `templates/single.html`**

```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->
<!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} -->
<main class="wp-block-group">
  <!-- wp:post-title {"level":1} /-->
  <!-- wp:post-date /-->
  <!-- wp:post-content {"layout":{"type":"constrained"}} /-->
</main>
<!-- /wp:group -->
<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

- [ ] **Step 5: Create `parts/header.html`** (minimal placeholder)

```html
<!-- wp:group {"tagName":"header","className":"chosen-header","layout":{"type":"flex","justifyContent":"space-between","flexWrap":"nowrap"},"style":{"color":{"background":"#0B0A55"},"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|60","right":"var:preset|spacing|60"}}}} -->
<header class="wp-block-group chosen-header has-background" style="background-color:#0B0A55">
  <!-- wp:site-title {"level":0,"style":{"typography":{"fontStyle":"normal","fontWeight":"700","letterSpacing":"0.16em","textTransform":"uppercase"},"color":{"text":"#FFFFFF"}}} /-->
  <!-- wp:navigation {"layout":{"type":"flex","flexWrap":"nowrap"},"style":{"color":{"text":"#FFFFFF"}}} /-->
</header>
<!-- /wp:group -->
```

- [ ] **Step 6: Create `parts/footer.html`** (minimal placeholder)

```html
<!-- wp:group {"tagName":"footer","className":"chosen-footer","style":{"color":{"background":"#0B0A55","text":"#FFFFFF"},"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|60","right":"var:preset|spacing|60"}}}} -->
<footer class="wp-block-group chosen-footer has-background has-text-color" style="background-color:#0B0A55;color:#FFFFFF">
  <!-- wp:paragraph {"align":"center","style":{"color":{"text":"rgba(255,255,255,0.7)"},"typography":{"fontSize":"0.875rem"}}} -->
  <p class="has-text-align-center" style="color:rgba(255,255,255,0.7);font-size:0.875rem">© 2026 Jesus Youth Australia. All rights reserved.</p>
  <!-- /wp:paragraph -->
</footer>
<!-- /wp:group -->
```

- [ ] **Step 7: Create `patterns/` placeholder**

```bash
mkdir -p patterns
touch patterns/.gitkeep
```

- [ ] **Step 8: Commit**

```bash
git add templates/ parts/ patterns/
git commit -m "feat(theme): add FSE templates and placeholder header/footer parts"
```

---

## Task 10: Create docs and ADRs

- [ ] **Step 1: Create docs structure**

```bash
mkdir -p docs/adr docs/superpowers/specs docs/superpowers/plans
```

- [ ] **Step 2: Create `docs/adr/001-block-theme-over-classic.md`**

```markdown
# ADR 001: FSE Block Theme over Classic Theme

**Date:** 2026-04-30
**Status:** Accepted

## Context
The project requires a custom WordPress theme for a marketing landing page. Two options considered: classic PHP theme (page.php, header.php, footer.php pattern) or Full Site Editing (FSE) block theme.

## Decision
FSE block theme.

## Consequences
- Non-technical editors (Irene) can edit header, footer, and template layouts in the visual Site Editor without touching code
- Custom blocks replace template partials — each block is a self-contained unit with editor controls
- Requires WordPress 6.4+ (confirmed: running 6.9.4)
- `theme.json` replaces scattered CSS variables for design tokens
- Templates are HTML block markup, not PHP — simpler to read and reason about
```

- [ ] **Step 3: Create `docs/adr/002-tailwind-with-wp-scripts.md`**

```markdown
# ADR 002: Tailwind CSS v3 alongside @wordpress/scripts

**Date:** 2026-04-30
**Status:** Accepted

## Context
Need a CSS approach and a JS build tool for custom blocks. Options: plain CSS, Tailwind, CSS-in-JS; and wp-scripts vs custom webpack vs Vite.

## Decision
Tailwind CSS v3 (CLI, not CDN) + @wordpress/scripts, run concurrently.

## Consequences
- `npm run start` runs both watchers from one terminal via `concurrently`
- Tailwind generates `assets/css/main.css` (committed to git — server won't run npm)
- wp-scripts compiles block JS to `assets/js/`
- `tailwind.config.js` mirrors `theme.json` tokens — must be kept in sync manually
- Tailwind v3 chosen over v4 for stability and @wordpress/scripts compatibility
```

- [ ] **Step 4: Create `docs/adr/003-deploy-theme-only.md`**

```markdown
# ADR 003: Theme-only deployment (not full site re-export)

**Date:** 2026-04-30
**Status:** Accepted

## Context
Local environment is a full clone of the live site. At deployment time, two options: re-export the full site as a .wpress and import to live, OR deploy only the theme folder.

## Decision
Deploy theme folder only.

## Consequences
- Deployment is a zip upload via WP Admin → Appearance → Themes → Upload, or a git push
- Live site content (pages, media, users, settings) is never overwritten
- Irene can add content to the live site at any time during development — it won't be lost
- .wpress export was a one-time operation to set up the local dev environment
- File size at deployment: ~5MB regardless of design complexity
```

- [ ] **Step 5: Commit**

```bash
git add docs/
git commit -m "docs: add phases plan, ADRs, and plan structure"
```

---

## Task 11: Activate theme and verify

- [ ] **Step 1: Check WP Admin can see the theme**

Open `http://chosen.local/wp-admin` → Appearance → Themes. You should see "Chosen Theme" listed. If it's not there, check:
```bash
ls "$HOME/Library/Application Support/Local/sites/chosen-local/app/public/wp-content/themes/"
# Must show: chosen-theme
```

If the symlink is missing, re-run Task 2 Step 1.

- [ ] **Step 2: Activate the theme**

Click **Activate** on "Chosen Theme".

- [ ] **Step 3: Check for PHP errors**

Visit `http://chosen.local`. If you see a white screen:
- Go to LocalWP → Site Shell and run: `wp --info` to confirm WP CLI works
- Then: `wp eval 'echo "OK";'` — if this fails, there's a PHP syntax error
- Check: `php -l functions.php` and `php -l inc/*.php` to find the error

If the front end loads (even if it looks plain/unstyled), the theme is working.

- [ ] **Step 4: Verify design tokens in editor**

WP Admin → Posts → Add New → open the block inserter → click a paragraph block → check the right sidebar "Colour" section. You should see the 9 Chosen brand colours (Navy, Royal Blue, Gold, Red, etc.) listed.

If you see generic colours instead of brand colours, `theme.json` is not being picked up — confirm the file is in the theme root and valid JSON.

- [ ] **Step 5: Run a watch build to confirm live reloading**

```bash
cd /Users/theodorexavier/Desktop/repo/chosen-theme
npm run start
```

Expected: Two processes start — wp-scripts watcher and Tailwind watcher. No errors. Edit `src/css/input.css` (add a comment, save) and confirm `assets/css/main.css` updates.

Stop with Ctrl+C when confirmed.

- [ ] **Step 6: Final commit**

```bash
git status
# Should show clean working tree or only generated files
git add -A
git commit -m "feat(theme): Phase 1 complete — theme scaffold activatable in LocalWP"
```

---

## Verification Checklist

Before moving to Phase 2, confirm all of these:

- [ ] `chosen.local` loads without PHP errors or white screen
- [ ] WP Admin → Appearance → Themes shows "Chosen Theme" as active
- [ ] Block editor colour palette shows 9 brand colours (Navy, Gold, Red, etc.)
- [ ] Block editor font family picker shows Work Sans and Anton
- [ ] `npm run build` exits cleanly with code 0
- [ ] `assets/css/main.css` exists and is non-empty
- [ ] `design-system/` folder exists with README.md, colors_and_type.css, assets/, preview/
- [ ] `.claude/commands/new-block.md` and `chosen-design.md` exist
- [ ] `.claude/agents/` contains both agent files
- [ ] `git log --oneline` shows 7+ commits for this phase

---

## Next Phase

Phase 2 plan (core layout — header, footer, templates) will be written when Phase 1 is verified complete. The Phase 2 plan will reference `design-system/preview/components-topnav.html` for the exact header implementation.
