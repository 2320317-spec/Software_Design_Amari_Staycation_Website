# Product

## Register

brand

## Users

Local Metro Manila residents — couples, friend groups, families — seeking a short luxury staycation in Alabang. They land on the site when they want to escape briefly without traveling far. The emotional job: feel the warmth and comfort of the space before they even arrive. Secondary audience: celebration bookers (birthdays, anniversaries) who want a curated, intimate setting.

## Product Purpose

Amari Staycation is a boutique staycation property in Alabang. The site exists to convert curious visitors into confirmed bookings by making the space feel irresistible before they step inside. Success is a completed booking. The experience should feel like a soft, warm invitation — not a transaction.

## Brand Personality

Warm. Grounded. Quietly luxurious.

- Voice: Calm, intimate, unhurried. Like a host who already knows you.
- Tone: Welcoming without being salesy. Descriptive without being verbose.
- Emotional goal: "I want to be there right now."

## Anti-references

- Cold, high-gloss hotel chains (Marriott, Hilton digital aesthetic) — too corporate, too transactional
- Coastal/beach resort aesthetics (bright white, turquoise, nav-heavy) — wrong geography, wrong mood
- Heavy SaaS-card grid layouts — this is a feeling, not a feature list
- Cream/sand AI-default palettes — the warmth comes from the brand's own browns and gold, not from a tinted near-white background

## Design Principles

1. **The room speaks first.** Imagery and atmosphere do the selling; copy supports, never leads.
2. **Warmth over polish.** Earthy, tactile, slightly imperfect is more inviting than clinical perfection.
3. **One invitation at a time.** Every page or section should have one clear next step. No competing CTAs.
4. **Earned restraint.** Negative space is not emptiness — it's the visual breath between moments.
5. **One deliberate accent per page.** Pinyon Script is used exactly once per page as an intimate whisper. Never repeated as a section pattern.

## Brand Colors

- `#564328` — Espresso (body text, primary identity)
- `#502515` — Mahogany (headings, dark surfaces)
- `#C2A66E` — Gold (decorative fills, dividers, script accents — NOT as text on light backgrounds)
- `#FAF6F6` — Soft white (page backgrounds, breathing space)
- `#FFFFFF` — White (card surfaces)

## Typography

- **Display / headings**: Bodoni Moda (Google Fonts) — italic at hero and display sizes
- **Body / UI**: Poppins (Google Fonts) — 300/400/600/700
- **Script accent**: Pinyon Script (Google Fonts) — one use per page only

## Accessibility & Inclusion

- WCAG AA minimum. Reduced motion respected on all pages via `.js-ready` guard and `@media (prefers-reduced-motion: reduce)`.
- **Gold contrast rule**: `var(--gold)` (#C2A66E) on white/soft-white is ~2.2:1 — fails WCAG AA at all text sizes used on this site. Gold is decorative only. Text on light backgrounds must use `var(--espresso)` (~7.5:1) or `var(--mahogany)` (~9.1:1).
- **`btn-book-nav` known issue**: white text on gold background is ~2.3:1 — needs fix (swap to mahogany background).
