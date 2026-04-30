# ADR 003 — Deploy Theme Folder Only (Not Full .wpress Export)

**Date:** 2026-04-30  
**Status:** Accepted

## Context

The live site was cloned from production using All-in-One WP Migration (`.wpress`, 169 MB). Initial plan was to develop locally and re-export the full `.wpress` for deployment.

## Decision

**Deploy theme code only** — zip the `chosen-theme/` folder and upload via WP Admin → Appearance → Themes → Upload. Never re-export a full `.wpress` for deployment.

## Reasons

1. **Site content stays untouched.** Re-exporting `.wpress` would overwrite the live database, wiping real content (pages, media library, ACF field values, form entries, user accounts).
2. **Deployment footprint is tiny.** The theme folder is a few MB of PHP, CSS, JS, and compiled assets — fast to transfer and verify.
3. **No database drift risk.** Local dev can freely create test content; none of it contaminates the live CMS.
4. **Reversible.** Activating the old Inspiro theme is a one-click rollback. A `.wpress` re-import is irreversible without a backup.

## What is deployed

- `chosen-theme/` folder contents (all committed files in this repo)
- Method: zip upload via WP Admin, OR `git push` + live server pulls from GitHub

## What is never deployed this way

- Database rows (posts, meta, users, options)
- Uploaded media (`wp-content/uploads/`)
- Plugin state

## Trade-offs

- ACF field group definitions are stored in the database by default. If field groups change during development, they must be exported as PHP (`acf_add_local_field_group()`) or JSON and committed — not left only in the local DB. This will be handled in Phase 3 when ACF repeater fields are built.
