---
description: Load full Chosen Conference brand context before any design or content work
---

Read these files now for complete brand context:

1. `design-system/README.md` — brand guidelines, colour theology, voice, animation rules
2. `design-system/colors_and_type.css` — all CSS custom properties (canonical token source)
3. `design-system/preview/brand-voice.html` — 4 voice examples
4. `design-system/preview/type-display.html` — Anton display treatment
5. `design-system/preview/components-buttons.html` — button specs
6. `design-system/preview/components-scripture-pull.html` — scripture layout pattern
7. `design-system/preview/brand-radiant-rays.html` — radiant rays motif implementation

## Brand rules to apply immediately

**Colours:**
- Navy `#0B0A55` — dominant dark surface colour
- Gold `#EDA90C` — primary accent: CTAs, eyebrow labels, scripture citations, gold rules
- Red `#F71A1D` — passion accent: theme word "Be Radiant", urgency only
- Royal Blue `#4071AC` — secondary section backgrounds
- Warm Paper `#FAF8F3` — light section backgrounds (not pure white)

**Typography:**
- Work Sans — all body text, UI, navigation, labels
- Anton — display/hero headlines ONLY (never body)
- Eyebrow labels: ALL CAPS, `letter-spacing: 0.18em`, gold colour

**Voice:**
- Invitational not promotional ("Come and encounter" not "Don't miss out")
- Scripture-anchored — every major surface has a verse
- First-person plural for community (we/us/our), second-person for reader (you/your)
- Australian English spelling (evangelise, colour, programme)
- CHOSEN in all caps; "conference" lowercase
- No emoji on official surfaces

**What NOT to do:**
- No glassmorphism
- No parallax scrolling
- No bounce/spring animations
- No generic SaaS dark gradients
- No AI-centric design patterns
- No Instrument Serif, Archivo Black, or Inter (prototype fonts — discarded)

**Delight approach:** "Warm reverence + quiet joy" — light dawning, not party popper.
- Ken Burns on hero photos (15–20s, ≤4% scale)
- Radiant rays rotate at 60s/turn
- Scripture fades in with upward translate (200ms ease-out)
- Stats count up on scroll via Intersection Observer
- All animations off with `prefers-reduced-motion`
