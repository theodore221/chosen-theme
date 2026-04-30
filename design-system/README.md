# Chosen 2026 — Design System

National Catholic Youth Conference, hosted by **Jesus Youth Australia**.
**La Trobe University, Melbourne · 19–22 November 2026**.
2026 theme: **"Be Radiant"** — *"Look to him, and be radiant; so your faces shall never be ashamed."* (Psalm 34:5)

---

## What is Chosen?

Chosen is the **annual national Catholic youth conference** of Jesus Youth Australia. It gathers young Catholics from across the country for four days of:

- Adoration & sacramental prayer
- Inspiring talks from bishops, priests, and lay missionaries
- Workshops on faith, vocation, mission and community
- Live worship led by the **Masterplan** band
- Theatre, testimonies, and social time

It exists to help young people *encounter Jesus in a life-changing way* and to *become a sign of hope to the world around them*.

## About Jesus Youth Australia

Jesus Youth is an **International Private Association of the Faithful** within the Catholic Church (recognised by the Pontifical Council for the Laity / Dicastery for Laity, Family and Life on 20 May 2016). Founded in Kerala, India, in the late 1970s, it now operates in 30+ countries. Active in Australia since 2004, with chapters in Melbourne, Sydney, Brisbane, Adelaide, Perth, Canberra, Darwin and Alice Springs.

- Web: chosenconference.org.au · jesusyouth.org.au
- Socials: Jesus Youth Australia (FB), JY Australia (X, IG)

---

## Source materials provided

| File | Use |
|---|---|
| `uploads/JY-Chosen-Conf_LogoV3_Web-01 (1).png` | Primary horizontal logo (CHOSEN with mark) |
| `uploads/JYConf0_18_LogoV3_White-01.png` | Logo on dark backgrounds (mark + white wordmark cropped) |
| `uploads/logo only-02.png` | Logo mark in isolation |
| `uploads/Chosen-flyer.pdf` | 4-page flyer from a prior edition (2018). Source of voice, copy patterns, info architecture |
| `uploads/Chosen-flyer.indd` / `.idml` | InDesign source for the flyer (not parsed) |
| `uploads/WhatsApp Image 2026-04-26 at 23.13.00.jpeg` | **Be Radiant** 2026 poster |
| `uploads/WhatsApp Image 2026-04-26 at 23.12.44.jpeg` | Brand guidelines page from the participant booklet (logo rationale + Work Sans spec) |
| `uploads/BeRadiant ripples.eps` | Listed in brief; **not present in this project** — please re-upload as a vector |
| `uploads/JYConf0_18_LogoV3_Web.ai` | Listed in brief; **not present in this project** — please re-upload |

Working copies live in `assets/` so HTML output can reference them with relative paths.

---

## Index

```
README.md                  ← you are here
SKILL.md                   ← skill manifest (Agent SDK compatible)
colors_and_type.css        ← CSS variables for colour, type, spacing, radii, shadows
fonts/                     ← (Google Fonts CDN; no local files needed at this time)
assets/
  chosen-logo-full.png     ← horizontal logo, dark on light
  chosen-logo-white.png    ← logo for use on dark/photo backgrounds
  chosen-mark.png          ← mark only (the Tau-circle medallion)
  poster-be-radiant.jpeg   ← reference poster, Chosen 2026
  brand-guidelines-page.jpeg
  Chosen-flyer.pdf
preview/                   ← cards rendered in the Design System tab
ui_kits/
  social/                  ← Instagram square + story templates
  email/                   ← HTML email templates
  print/                   ← flyer / poster recreations
```

---

## CONTENT FUNDAMENTALS — voice, copy, tone

The Chosen brand voice is **passionate, youthful, mission-driven**, and rooted in scripture. It speaks *to* young Catholics in their own register, but never sacrifices reverence.

### Voice attributes

- **Invitational, not promotional.** "Come and get equipped" not "Don't miss out".
- **Scripture-anchored.** Every major surface opens or closes with a Psalm/Gospel verse. The verse drives the theme: *Be Radiant* comes from Psalm 34:5.
- **First-person plural and second-person singular.** Uses *we / us / our* for the community, *you / your* when calling the reader. Rarely uses *I*.
- **Action-oriented imperatives.** "Encounter", "Come", "Register", "Look to him". Verbs in the imperative mood, no soft hedging.
- **Reverent capitalisation.** Pronouns referring to God are capitalised: *Him, His, Christ, the Shepherd*. The conference name *CHOSEN* is set in all caps in display contexts.
- **Australian English.** *evangelis**e**, organis**e**, colour, programme*. Spell-check accordingly.

### Casing rules

- Headlines & poster theme words: **Title Case** with display font (e.g. *Be Radiant*).
- Logo lockup: **CHOSEN** in all caps, *conference* in lowercase below.
- Eyebrow / category labels: ALL CAPS, letter-spaced (`PSALM 34:5`, `JY SPEAKERS`, `BISHOPS`).
- Body copy: sentence case, full punctuation.

### Emoji & ornaments

- **Emoji: avoid.** The brand does not use emoji on official surfaces; the visual richness comes from photography, the logo mark, and colour. (Social posts may use them sparingly to match platform tone, but never on event collateral.)
- Use the **logo mark** as a visual ornament/punctuation in place of bullets or icons.
- Acceptable ornaments: tall coloured ray graphics ("BeRadiant ripples"), gold rule lines, the Tau-circle medallion.

### Example copy (verbatim from supplied materials)

> "You did not choose me but I chose you; and I appointed you to go and bear fruit that will last." — John 15:16

> "Look to him, and be radiant; so your faces shall never be ashamed." — Psalm 34:5

> A national annual gathering of young people brought to you by Jesus Youth Australia.

> Come and get equipped to be a leader with practical life-changing steps that help you boldly live a life of purpose. Learn to share the joy of the gospel and change lives around you.

> A conference beyond the ordinary.

### Information architecture (events)

When announcing the conference, the order is consistently: **Theme → Scripture → Dates → Venue → Tagline → Hosts → CTA → Speakers → Programme → Pricing → About JY**.

---

## VISUAL FOUNDATIONS

### Colour

The Chosen palette is **theological, not decorative**. Each colour was chosen for what it signifies (per the official brand guideline page):

| Token | Hex | Theological meaning |
|---|---|---|
| `--chosen-navy` | `#0B0A55` | The faithful — *royalty / specialty*; God's chosen children |
| `--chosen-royal` | `#4071AC` | Sky — *God coming down from heaven* |
| `--chosen-gold` | `#EDA90C` | The Tau cross — *the light of truth* the youth follow |
| `--chosen-red` | `#F71A1D` | The youth figures — *beckoning for a richer experience with Christ*; candles of enlightenment |
| `--chosen-yellow` | `#EBC903` | Inner sun ring — radiance |
| `--chosen-orange` | `#FE4E0E` | Outer sun ring — radiance |
| `--chosen-black` | `#0A0A0A` | The CHOSEN wordmark itself |
| `--chosen-white` | `#FFFFFF` | Breathing space, divine purity |

**Use rules**

- **Navy is the dominant brand colour for digital surfaces.** Pair with white space and one warm accent (gold or red).
- **Gold is the primary accent.** Use sparingly: CTAs, eyebrow labels, scripture citations, ornament rules.
- **Red is the urgency/passion accent.** Use for the "Be Radiant" / theme-word treatment, not for body links.
- **Royal blue is a secondary surface colour** — banner backgrounds, alternate sections.
- Never tint or screen the brand colours arbitrarily; use the provided 300/500/700 shades.
- **High contrast required.** Logo always sits on a single solid background (white, navy, royal blue, or a darkened photo).

### Typography

- **Primary typeface: Work Sans** (per the brand guideline page: *CHOSEN — Work Sans Medium 61pt; conference — Work Sans Regular 21pt*).
- **Display / poster typeface: Anton** (a tall, condensed, single-weight sans). The "Be Radiant" treatment on the 2026 poster uses a tall condensed display sans; **Anton (Google Fonts) is our chosen substitute** until we obtain the original face. **⚠ Substitution — please confirm or supply the original poster face.**
- **No serif** in the system. The reverence comes from spacing and colour, not classical type.
- **Eyebrow style** for scripture cites and category labels: ALL CAPS, `letter-spacing: 0.18em`, gold or red.

### Spacing & layout

- **4 px base grid** (`--space-1 … --space-9`).
- **Generous vertical rhythm.** Heroes give the headline 1.5× the space of any neighbouring block.
- Layouts favour **strong horizontal bands** (alternating navy / paper / royal blue) over multi-column complexity.
- Posters use a **central radial composition** — rays fanning from a focal sacred image (the monstrance on Be Radiant). When recreating, anchor key elements to the centre, not corners.

### Backgrounds

- **Full-bleed photography** is the default for hero/poster surfaces. Photos are warm-graded, high-energy, real young people in worship — never stock.
- **Solid navy** is the default for digital cards and email backgrounds.
- **Radiant ray pattern**: alternating tall coloured rays (red, orange, yellow, teal, blue) fanning from a focal point — used for the 2026 *Be Radiant* theme. Source vector: `BeRadiant ripples.eps` (please re-upload).
- **Warm paper** (`--neutral-50`, `#FAF8F3`) is the default for long-form documents.
- **Gradients are minimal.** Use only as photo treatments (warm-to-deep, never blue-purple).

### Imagery — colour vibe

- Warm-graded, gold-and-red dominant.
- High contrast, deeply saturated. Never desaturated, never B&W as a primary treatment.
- Subjects are **real Australian Catholic young people** in worship, prayer, or community — joyful, expressive, diverse.
- Sacramental imagery (monstrance, host, hands) is shot reverently and centrally framed.

### Borders, corners, shadows

- **Corner radii**: rectangles use `--radius-md` (8px) for cards, `--radius-pill` for tag chips and the primary CTA. Photographs are typically hard-edged (radius 0) when used full-bleed.
- **The mark is always circular** — never crop the medallion into a rounded-rect.
- **Borders**: 1px navy at 12 % opacity for default; 2px gold for emphasis (e.g. selected card).
- **Shadows are soft and navy-tinted** (`--shadow-md`/`-lg`). Never grey or black neutral. No inner shadows except in form inputs.

### Animation

- **Subtle, reverent.** Fades and gentle ease-out translates (200–400 ms). No bounces, no spring overshoots, no parallax shenanigans.
- Easing: `cubic-bezier(0.22, 0.61, 0.36, 1)` (ease-out-quart) for entrances; `cubic-bezier(0.4, 0, 0.6, 1)` for movement.
- Hero photography may have a **slow Ken Burns** (15–20 s, ≤ 4 % scale).
- **Hover**: `--hover-overlay` (6 % navy wash) on light surfaces; +6 % brightness on dark photo cards.
- **Press**: `--press-overlay` (12 % navy) and a 1 px nudge down (no scale shrink).
- The radiant ray pattern can rotate slowly (60 s/turn) on hero web surfaces — never on email.

### Transparency & blur

- **No glassmorphism** as a primary motif. Surfaces are solid.
- Acceptable: a 60 % black scrim under photo headlines for legibility.
- Avoid blurred hero backgrounds; the photography itself is the surface.

### Cards

- White or paper background, 1 px navy/12 % border, `--shadow-md`, 8 px radius.
- A 4 px **gold top accent bar** marks an emphasised/featured card.
- Photo cards: hard 0 radius, no border, scrim only when overlaying text.

### Layout fixed elements

- Sticky top bar on web: navy, full-width, 64 px tall, white logo at left, primary CTA (gold) at right.
- Footer: navy, with logo, address (140 Westbourne Grove, Northcote VIC 3070), socials, scripture pull-quote.

---

## ICONOGRAPHY

The Chosen brand is **iconography-light**. The visual language relies on:

1. **The logo mark** (Tau-circle medallion) — used as the brand's universal symbol/ornament.
2. **Photography** — the primary "icon" of any given moment is a real photo of young people.
3. **Hand-set type** with strong colour + scale contrast.
4. **The radiant ray pattern** — used as a graphic motif rather than a literal icon set.

When functional icons are needed (e.g. social-media UI, contact info, navigation), we use **[Lucide](https://lucide.dev) via CDN** for its honest, even-stroke geometry that pairs well with Work Sans. Pick stroke icons at 1.75 px weight and 24 px size by default. Colour them `currentColor` so they inherit from text.

```html
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
<i data-lucide="map-pin"></i>
<script>lucide.createIcons();</script>
```

**⚠ Substitution flag:** Lucide is not part of any Chosen brand guideline I was given. It's an editorial choice for digital interfaces only. Print collateral should continue to use the logo mark or no icons at all. Please confirm or supply an in-house icon set.

**Emoji**: not used on official Chosen surfaces. (See *Content fundamentals*.)

**Unicode ornaments**: *not* used. Use the gold rule line or the logo mark instead.

---

## Caveats & open questions

- **Missing source files:** `BeRadiant ripples.eps` and `JYConf0_18_LogoV3_Web.ai` were referenced in the brief but did not arrive in the upload. The radiant rays are recreated as a CSS/SVG pattern in `assets/`. Please re-upload the EPS for pixel-perfect placement on print collateral.
- **Display typeface:** the "Be Radiant" poster word uses a tall condensed display sans the original designer didn't name. Anton (Google Fonts) is the closest free match in widespread use; please confirm or share the original.
- **The 2018 flyer** is a useful voice/IA reference but its visual treatment is dated; current visual direction is the 2026 *Be Radiant* poster.

See the **Design System tab** for rendered swatches, type specimens, components, and brand cards.

---

## UI Kits in this project

- **`ui_kits/social/index.html`** — Instagram 1080×1080 squares (theme reveal, speaker reveal, save-the-date) and 1080×1920 stories (scripture, CTA, programme).
- **`ui_kits/email/index.html`** — 600 px HTML emails: promo (registrations open) and welcome (post-signup checklist).
- **`ui_kits/print/index.html`** — A4 *Be Radiant* poster recreation (radiant rays + monstrance focal + verse) and an A5 mono-flyer for parish noticeboards.

These are *recreations and remixes of the supplied 2026 poster's visual language*, not production-final files. Bring in your own photography by replacing the `.photo` background-image / monstrance focal placeholder.
