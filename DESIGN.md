# Design

## Color Palette

| Token | Value | Role |
|---|---|---|
| `--espresso` | `#564328` | Primary identity, body text, nav |
| `--mahogany` | `#502515` | Deep contrast, display headings |
| `--gold` | `#C2A66E` | CTAs, accents, highlights, dividers |
| `--soft-white` | `#FAF6F6` | Page background, cards, breathing space |
| `--white` | `#FFFFFF` | Pure white surfaces |
| `--shadow-warm` | `rgba(80, 37, 21, 0.10)` | Warm box shadows |

**Color strategy:** Restrained. Tinted neutrals carry the page; gold and mahogany provide the brand moments. Never use `--gold` for small body text on light backgrounds (contrast too low).

## Typography

| Role | Family | Weights | Notes |
|---|---|---|---|
| Logo | Le Jour Serif | Regular | Brand mark only |
| Display headings | TAN Angleton | Regular | Section heroes, page titles |
| Accent / Script | Breathing | Regular | Pull quotes, taglines, ornamental |
| Body / UI | Poppins | 300, 400, 600, 700 | All body copy, labels, nav |

**Font stack fallback:** `'Poppins', sans-serif` for body; `serif` fallback for display.

**Scale (approximate):**
- Display: 3.5–5rem (clamp)
- H1: 2.5–3.5rem
- H2: 1.75–2.25rem
- H3: 1.25–1.5rem
- Body: 1rem (16px base)
- Small / Label: 0.875rem

**Rules:**
- TAN Angleton for emotional moments only — not utility text
- Breathing script for 1–4 word accents, never paragraphs
- Body line length capped at 65–70ch
- `text-wrap: balance` on display headings

## Spacing Scale

`4px` base unit. Common values: 4, 8, 12, 16, 24, 32, 48, 64, 96, 128px.

## Components

### Navigation
- Sticky, white background, `border-bottom: 1px solid #eee`
- Links: Poppins 700, uppercase, 0.85rem, espresso color
- CTA button: gold fill, uppercase, no border-radius (sharp edge on brand)

### Buttons
- Primary: `background: #C2A66E`, white text, `padding: 12px 24px`, `border-radius: 6px`
- Hover: transitions to `#502515` (mahogany)
- Font: Poppins 600

### Cards (Unit Cards)
- White background, `border-radius: 12px`
- Warm shadow: `0 8px 24px rgba(80,37,21,0.10)`
- Hover: lift `-8px translateY`, deeper shadow
- `border: 1px solid #FAF6F6`

### Hero
- Full-viewport height (`80vh`, min 600px)
- Image carousel with slow cross-fade (1.4s ease-in-out)
- Overlay: dark gradient or subtle flash effect for transition
- Heading: TAN Angleton display size, white, centered

## Motion

- Transitions: `0.3s ease` for interactive elements (buttons, cards)
- Hero slider: `1.4s ease-in-out` transform
- Entrance animations: fade + subtle translateY (16px → 0), `@media (prefers-reduced-motion)` fallback to instant
- No bounce, no elastic easing

## Imagery

- Warm, natural, earthy tones — linen, wood, botanicals, soft natural light
- Square or landscape crops preferred for room shots
- Avoid cold, clinical, or heavily processed photography

## Layout

- Max content width: `1200px`, centered
- Section padding: `80px 50px` desktop, `48px 20px` mobile
- Grid: `repeat(auto-fit, minmax(280px, 1fr))` for room/unit grids
- Flexbox for nav, button groups, inline elements
