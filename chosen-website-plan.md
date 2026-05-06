# Chosen Conference Website — Implementation Plan v2

**Project:** Front-end uplift of `chosenconference.org.au`
**Owner:** Theodore (lead dev)
**Content lead:** Irene  •  **Site admin:** Jento (JY Australia)
**Tech approach:** Custom WP block theme, Tailwind, no page builder
**Scope:** Marketing/promo only (v1) — speakers showcase added later when content ready
**Timeline:** ~2 weeks to launch

> v2 supersedes the original plan. Scope tightened: no schedule, no registration logic (external app handles it), speakers deferred to a later content drop.

---

## 1. What we're building (and what we're not)

**Building (v1):**
- Marketing landing page that communicates the Chosen vision and builds hype
- Static informational pages (About, What to Expect, Get Involved, Contact, Safety, Privacy)
- Strong aesthetic (Gen Z / Y2K-zine + sacred art language, Ignite Conference as primary reference)
- All "Register" CTAs link out to the external signup app

**Deferred (post-v1, drop-in when ready):**
- Speaker showcase (CPT scaffolded in v1, populated in v1.1)
- FAQ accordion (can ship as plain page in v1, upgrade to accordion later)

**Not building:**
- Registration flows
- Payment
- User accounts / auth
- Schedule complexity (a simple "schedule announced soon" line is enough for v1)

---

## 2. Stakeholders

| Person | Role | Owns |
|---|---|---|
| **Theodore** | Lead dev / design lead | Architecture, theme code, deployment |
| **Irene** | Content/project contact | Copy sign-off, brand direction, scope |
| **Jento** | Site admin (JY Australia) | Hosting access, plugin permissions, go-live |
| **Domain manager** (TBC) | DNS | DNS changes when staging needs setup |

---

## 3. Status check (what's done, what's pending)

✅ **Done**
- Admin access to WP confirmed
- Hosting in place (LiteSpeed-based, paid)
- Domain registered, site live but pre-public

🟡 **In progress**
- Domain manager contact — Theodore reaching out (need: registrar, DNS host, contact for changes)
- Brand assets — Chosen has logo + brand colours; need files

⏳ **Pending decisions**
- External signup app URL (for `CHOSEN_REGISTER_URL`)
- Visual direction sign-off from Irene (locked in Phase 1)

---

## 4. Phased plan

### Phase 0 — Discovery (in progress)

Quick remaining items:

- [ ] Get logo files from Irene (SVG ideal, plus PNG fallbacks; light + dark variants)
- [ ] Get brand colour hex codes
- [ ] Get brand fonts (or font names if licensed)
- [ ] Get past-conference photography (for hero, mosaic, atmosphere)
- [ ] Get external signup app URL (or "TBC, will provide" with a target date)
- [ ] Get vision/tagline copy direction (or signal we're proposing)
- [ ] Confirm with Jento: OK to remove Inspiro and WPZOOM plugins on launch

### Phase 1 — Design prototype (THIS IS NEXT)

The cheap-iteration phase. Lock the aesthetic before any WP theme work begins.

**Deliverables:**
- Moodboard (visual references — Ignite, Catholic visual identity, Y2K-zine, contemporary youth brands)
- Working HTML/Tailwind prototype of the landing page (no WP yet)
- Includes: hero, marquee, vision, stat strip, expect tile mosaic, image mosaic, CTA banner, sponsor strip, footer
- Stakeholder review with Irene → iterate → explicit sign-off

**Done when:** Irene has signed off the prototype aesthetic in writing.

### Phase 2 — Theme scaffold + foundation

Theodore drives via Claude Code using the `.claude/` setup.

- Project scaffold (style.css, theme.json, functions.php, package.json, Tailwind, templates, parts)
- Apply real brand tokens (logo, colours, typography) to `theme.json` and `tailwind.config.js`
- Build header part with logo + nav + register CTA
- Build footer part with contact, social, sponsor, attribution
- Verify theme activates cleanly in LocalWP

**Done when:** Branded but content-empty theme renders without errors on LocalWP.

### Phase 3 — Custom blocks (v1 set)

Slim block list focused on marketing/promo. In priority order:

| # | Block | Purpose |
|---|---|---|
| 1 | `chosen/hero` | Landing hero — video/image background, headline, sub, dates, register CTA |
| 2 | `chosen/marquee` | Scrolling text strip with glyph separators |
| 3 | `chosen/vision` | Vision/why statement — heading, body, optional image |
| 4 | `chosen/stat-strip` | "By the numbers" row — 3–4 stats |
| 5 | `chosen/expect-tile-grid` | Ignite-style mosaic of experience pillars (rallies, worship, sacraments, etc.) |
| 6 | `chosen/image-mosaic` | Photo collage section |
| 7 | `chosen/cta-banner` | Bold full-width CTA — register prompt |
| 8 | `chosen/sponsor-strip` | Partner logos |
| 9 | `chosen/quote` | Pull-quote / scripture / testimonial |

**Deferred to v1.1 when content lands:**
- `chosen/speaker-grid` + `chosen/speaker-card` (Speaker CPT scaffolded but unpopulated)

**Done when:** All 9 blocks built, render correctly, editor UX intuitive.

### Phase 4 — Content build

Pages composed from blocks. Most copy is evergreen (vision, who runs Chosen, what to expect) so doesn't depend on speakers/schedule.

- **Home** — hero, marquee, vision, stat strip, expect tile mosaic, image mosaic, CTA banner, sponsors
- **About** — vision, history (Chosen's been running since at least 2018), Jesus Youth Australia attribution
- **What to Expect** — deeper explainer for each pillar from the home tile mosaic
- **Get Involved** — volunteer, sponsor, bring a parish group
- **Contact** — form + email + social + phone
- **Safety** — child-safe commitment (mandatory for Catholic ministry compliance)
- **Privacy** — required by Australian Privacy Act

Plus: "Speakers and schedule announced soon" placeholder section on home — primes anticipation, gives reason to revisit.

**Done when:** All pages live with real or near-final copy on LocalWP.

### Phase 5 — QA + launch

- Lighthouse: a11y ≥95, perf ≥90
- Cross-browser: Chrome, Safari, Firefox, mobile Safari, Chrome Android
- Responsive: 375 / 768 / 1024 / 1440
- Contact form end-to-end test
- All `Register` CTAs land on real signup app URL
- Settings → Reading → uncheck "Discourage search engines"
- Backup prod, deploy theme, activate, verify
- Submit sitemap to Google Search Console

**Done when:** Site is live, indexable, all CTAs route correctly.

### Phase 6 — Light handoff

- 30-min walkthrough with Irene — how to edit a page, how to add a speaker (when content ready)
- Short `EDITOR-GUIDE.md` with screenshots of common tasks
- Theodore stays informally on call for content updates

---

## 5. Plugin changes on launch

**Keep:** Akismet (activate), LiteSpeed Cache, CookieAdmin Pro, The Icon Block

**Remove:** Hello Dolly, Inspiro Starter Sites, WPZOOM Portfolio Lite, Image Slider Block, Video Popup Block by WPZOOM, CookieAdmin (free duplicate of Pro)

**Add:** ACF Free, Fluent Forms (replacement for WPZOOM Forms — much more flexible), WP Migrate Lite (for prod ↔ local content sync if needed)

**Theme:** Replace Inspiro with `chosen-theme`. Keep Inspiro installed as fallback during initial transition.

---

## 6. Content scaffold

### Sitemap

```
Home (/)
├── About (/about)
├── What to Expect (/what-to-expect)
├── Get Involved (/get-involved)
├── Contact (/contact)
├── Safety (/safety)
└── Privacy (/privacy)

Deferred to v1.1:
├── Speakers (/speakers)
└── (no schedule page — info goes in app)

External:
└── Register → external signup app URL
```

### Home page section order

1. **Hero** — autoplaying muted video of past conference, bold headline, dates + venue, primary "Register" CTA
2. **Marquee strip** — `CHOSEN ⟡ CHOSEN ⟡ CHOSEN`
3. **Vision** — short paragraph + scripture reference (John 15:16 — "you did not choose me but I chose you" — source of the conference name)
4. **Stat strip** — past attendance, speakers, days, dioceses
5. **Expect tile mosaic** — rallies, workshops, worship, sacraments, vocation expo, soul cafe (music), live theatre, eucharistic celebration
6. **Image mosaic** — past-conference faces and energy
7. **"Speakers + schedule" anticipation block** — "Full lineup announced soon — register early to lock your spot"
8. **CTA banner** — bold register prompt with date urgency
9. **Sponsor strip**
10. **Footer** — contact, social, safety link, JY Australia attribution

---

## 7. Risks (top 3)

| Risk | Mitigation |
|---|---|
| Content (copy + photos) not ready by launch | Get Irene committed to a delivery date; we draft filler copy; lean on past-conference photos for atmosphere |
| External signup app URL not ready by launch | Use `CHOSEN_REGISTER_URL` constant — change in one place when URL lands; until then, link to a "Register — coming soon" page |
| Brand colours/fonts low-fidelity or contested | Lock in Phase 1 with Irene; don't move to theme work until tokens are signed off |

---

## 8. Open questions for Irene

1. Logo file (SVG preferred), brand colour hex codes, brand fonts (or font names)?
2. Past-conference photo library — what do we have, who has consent rights?
3. Is the external signup app URL ready, or pending?
4. Tagline / theme for this year's Chosen?
5. Any organising team members beyond you and Jento who need to sign off design before launch?
6. Anyone else in JY Australia leadership we should loop in before go-live?

## 9. Open questions for Jento

1. OK to remove Inspiro and the WPZOOM plugins on launch?
2. Hosting provider name (determines staging path closer to launch)?
3. Anything currently on the live WP install we need to preserve (forms, pages, settings)?

---

## 10. Immediate next actions

1. **Send Irene** the Phase 0 questions (section 8) — get logo, colours, fonts, photos, signup URL
2. **Confirm with Jento** the plugin removals (section 9)
3. **Start the moodboard** — pull references from Ignite, Catholic visual identity, Y2K/zine design
4. **Once moodboard is roughed out** → begin the HTML/Tailwind landing page prototype

The prototype is the next deliverable. Theme work doesn't begin until aesthetic is locked.
