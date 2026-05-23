# Ocean Charter — Stitch Design System Reference

> Authoritative design reference for the Ocean Charter WordPress theme.
> All UI work MUST match these specifications.

---

## Design Tokens

### Colors

| Token | Value | Usage |
|-------|-------|-------|
| `primary` | `#d9b230` | Gold accent — buttons, labels, borders, icons |
| `background-dark` | `#05070a` | Deepest background (detail pages, footer) |
| `navy-deep` | `#0a0f1a` | Homepage dark bg, surface-container-low |
| `navy-muted` | `#1a2233` | Card backgrounds, surface-container |
| `background-light` | `#f8f7f6` | Light mode background |
| `surface-container-high` | `#242c3d` | Elevated cards, form backgrounds |
| `surface-container-highest` | `#2f384d` | Tags, badges |
| `on-surface` | `#f8f7f6` | Primary text on dark |
| `on-surface-variant` | `#cbd5e1` | Secondary text (slate-300/400) |
| `on-primary` | `#0a0f1a` | Text on gold buttons |
| `outline-variant` | `rgba(217, 178, 48, 0.1)` | Subtle gold borders |

### Glass Effect (Shared)

```css
.glass {
    background: rgba(26, 34, 51, 0.7);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(217, 178, 48, 0.1);
}
```

Variants:
- **Homepage nav:** `rgba(26, 34, 51, 0.7)` with `blur(12px)`
- **Fleet filter:** `rgba(26, 42, 58, 0.6)` with `blur(12px)`, `border: 1px solid rgba(255,255,255,0.1)`
- **Detail pages:** `rgba(26, 34, 51, 0.4)` with `blur(12px)`, `border: 1px solid rgba(217,178,48,0.1)`

### Typography

| Role | Font Family | Usage |
|------|-------------|-------|
| Body / Labels | **Inter** (300–900) | All body text, navigation, buttons, labels |
| Serif (Homepage) | **Playfair Display** (400–900, italic) | Homepage headings, yacht names |
| Serif (Detail pages) | **Newsreader** (200–800, italic) | Detail page headings, prices, editorial text |

**Patterns:**
- Section label: `text-primary font-bold tracking-[0.2em] text-xs uppercase`
- Section heading: `font-serif text-4xl` (below label)
- Price: `font-serif text-4xl italic` + `/day` or `/hr` suffix in small text
- Tiny labels: `text-[10px] uppercase tracking-widest text-primary font-bold`

### Icons

- **Material Symbols Outlined** — used throughout
- Weight: 300–400, Fill: 0 (outline) or 1 (filled for stars)
- Key icons: `sailing`, `location_on`, `calendar_month`, `group`, `search`, `star`, `arrow_forward`, `verified`, `restaurant`, `surfing`, `celebration`, `support_agent`, `chat_bubble`

### Border Radius

| Token | Value |
|-------|-------|
| DEFAULT | `0.25rem` (homepage) / `0.125rem` (detail pages) |
| lg | `0.5rem` / `0.25rem` |
| xl | `0.75rem` / `0.5rem` |
| full | `9999px` / `0.75rem` |

---

## Page Layouts

### 1. Homepage

**Hero:**
- Full-screen `h-screen` with background image + gradient overlay
- Centered text: serif heading with italic gold accent word
- **Booking widget:** Glass panel, `max-w-5xl`, 4-col grid (Destination input, Dates input, Guests select, Search button)
- Search button: `bg-primary text-navy-deep font-bold rounded-xl`

**Featured Vessels:**
- Section label + heading pattern
- 3-col grid of yacht cards
- Card: `aspect-[4/5]` portrait image, rounded-xl, border, hover scale-110 on image
- Badge: `absolute top-4 right-4 bg-primary text-navy-deep text-[10px] font-black px-3 py-1 rounded-full uppercase`
- Card body: yacht name (serif text-2xl), price (primary font-bold), specs row (length/guests/speed with icons), "Explore Vessel" button (border border-primary/30, hover fill)

**Why Choose Us:**
- `bg-navy-deep` full-width section
- 2-col: left = aspect-square image with overlapping stat box (`25+ Years`), right = 3 feature rows (icon box + text)

**Testimonials:**
- Centered, max-w-4xl
- Large decorative quote mark (`text-[120px] font-serif text-primary/10`)
- Serif italic quote, avatar with gold border, name + title

**Destination Gallery:**
- `grid-cols-4 grid-rows-2 h-[600px]`
- Large left (col-span-2 row-span-2), 2 small right, 1 wide bottom (col-span-2)
- Each: image with gradient-to-t overlay, destination name at bottom

**CTA Section:**
- Background image with `bg-navy-deep/90` overlay
- Two buttons: primary solid + glass outline

**Footer:**
- `bg-background-dark`, 4-col grid (logo+desc+social, Fleet links, Experience links, Contact info)
- Bottom bar: copyright + privacy/terms links, `text-[10px] uppercase tracking-[0.2em]`

### 2. Fleet Listings

**Navigation:** Sticky top bar (not pill), has search input, "List Your Boat" CTA

**Header:** Bold `text-4xl lg:text-5xl font-extrabold` title + subtitle

**Filter Panel:**
- `glass-effect rounded-2xl p-4`, flex-wrap
- 4 dropdowns: Type, Capacity, Pricing, Location
- Each: label (`text-[10px] uppercase tracking-widest text-primary font-bold`), select with `bg-white/5 border border-white/10 rounded-xl`
- "More Filters" button at end

**Yacht Cards (3-col grid):**
- `rounded-2xl overflow-hidden border border-white/10 hover:border-primary/50`
- Image: `aspect-[4/3]` with background-image, hover scale-110
- Badge: absolute top-right (e.g., "New Listing")
- **Star rating overlay:** Bottom of image with gradient, `star` icon (fill-1) + rating + review count
- Body: yacht name (text-xl font-bold), price (text-xl font-black), specs (guests + location with icons)
- **Two buttons side-by-side:** "Details" (`flex-1 bg-white/10`) + "Quick Book" (`flex-[2] bg-primary uppercase tracking-widest`)

**BBC Integration Banner:**
- `rounded-3xl border border-primary/20 bg-primary/5`
- Icon + title + description + live status indicator (green dot with ping animation)

### 3. Destinations

**Hero:** 70vh, gradient overlay, centered text with label + heading + subtitle, two buttons (solid + outline)

**Region Filters:** Horizontal pills — active: `bg-primary text-background-dark`, inactive: `bg-white/5`

**Destination Cards (3-col grid):**
- `aspect-[4/5]` PORTRAIT image (NOT landscape)
- Gradient overlay on image (60% opacity, increases on hover)
- Optional badge: absolute top-left (e.g., "Popular")
- Content BELOW image (NOT overlay): title, description, footer row
- Footer: `border-t border-white/10 pt-4` with vessel count (gold text) + "Explore ->" link

**World Map Callout:**
- 2-col: left = map visual (dark bg, radial gradient, zoom buttons, live tracking pill), right = heading + description + bullet list with `verified` icons + link
- Map has mock "Live Location" overlay card

**Newsletter CTA:**
- `bg-primary/5 rounded-3xl` with blur orb decorations
- Email input + subscribe button

### 4. Services

**Hero:** 70vh with cinematic gradient (`linear-gradient(180deg, transparent 0%, rgba(32,29,18,0.8) 100%)`)

**Section Intro:** Italic heading + description, decorative icons (star, diamond, anchor)

**Service Cards (2-col, staggered):**
- `aspect-[4/5]` images with hover scale-110
- Pill badge on image: `bg-background-dark/60 backdrop-blur-md px-4 py-2 rounded-full border border-primary/20`
- Title with arrow_outward icon (appears on hover)
- Description + bullet list in gold text

**Stagger:** Second column has `md:mt-24` offset

### 5. Contact

**Hero:** 40vh min-h-[400px], image with dark gradient overlay, centered text

**Content Grid (pulled up with -mt-20):**
- Left (col-span-7): Glass panel form with Full Name, Email, Interest select, Message textarea, Send button
- Right (col-span-5): HQ info card (border-l-4 border-l-primary), map placeholder, WhatsApp CTA (green gradient)

**Form Labels:** `text-xs font-bold uppercase tracking-widest text-primary`
**Form Inputs:** `bg-background-dark/50 border border-primary/20 rounded-lg`

### 6. Packages

**Hero Banner:** 400px rounded-xl with cinematic gradient, badge + heading + subtitle

**Category Filters:** Horizontal pill buttons with icons (All, Sunset, Corporate, Private Events, Dining)

**Package Cards (3-col):**
- Image (h-64) with optional badge
- Body: title + price/duration, description, 2 feature checkmarks (`done_all` icon), "Quick Booking" button with bolt icon
- Card: `hover:border-primary/50 hover:shadow-2xl hover:shadow-primary/5`

**Bespoke Voyages:** 2-col (text left with bullet list, image right with "100%" stat overlay box)

### 7. Yacht Detail Pages

Two primary variants exist:

**Variant A (Predator 74 / Azure Muse):**
- Hero: `grid-cols-12 h-[70vh]` — main image (col-span-8) + 2 stacked thumbnails (col-span-4)
- Quick specs: 4-col grid (Length, Guests, Crew, Built) with serif italic values
- Description: Serif italic heading + light body text
- Amenities: 3x2 grid with Material icons
- Captain: Flex row with circular avatar (border-2 border-primary/20) + bio
- **Booking Widget (sticky):** Duration toggles (4H/8H/Full Day), price display, date picker, guests/time selects, "Book Experience" button
- Social proof: avatar stack + "12 others looking"
- Reviews: 3-col cards with star ratings, initials avatar

**Variant B (Azure Seraph / Aurelius III):**
- Hero: Bento gallery — `grid-cols-4 grid-rows-2` (main image col-span-2 row-span-2, 2 small, 1 wide)
- "VIEW ALL X PHOTOS" hover overlay on bottom-right panel
- Quick specs: Grid with centered icons above labels
- Amenities: glass-card or left-border-primary cards
- Captain: Large section with rounded-full avatar, credentials tags
- Reviews: Individual cards with avatar initials
- Related vessels: Horizontal scroll or 3-col grid
- **Booking Widget (sticky):** Price/day, availability badge, date/guests/time selects, "Request Charter" + "WhatsApp" buttons, estimate calculation, trust badges (shield, refresh icons)
- Mobile: Fixed bottom bar with price + Reserve button

### 8. Detail Pages (Itinerary / Package / Destination / Service / Offer)

**Shared Layout:** `grid-cols-12` — content (col-span-7 or 8) + sticky sidebar (col-span-4 or 5)

**Itinerary Detail:**
- Route map component with glass overlay card
- Timeline: Vertical line with numbered day circles (absolute positioned)
- Each day: label + serif heading + description + tag pills + aspect-video image
- Alternating image position (left/right)
- Yacht specs bar at bottom

**Package Detail:**
- Editorial storytelling with label-line pattern (`h-px w-12 bg-primary` + label)
- Asymmetric imagery mosaic (2-col with different aspect ratios)
- Must-visit enclaves: 2x2 bento grid with numbered items

**Destination Detail (Offer):**
- Benefits: 2x2 bento cards with Material icons + left/top borders
- Terms section with gold bullet points

**Service Detail:**
- Masonry gallery (2-col, varied aspect ratios)
- Highlights: 2x2 grid with icon + divider line + heading + text
- Large testimonial block with oversized quote icon

**Sidebar (All Detail Pages):**
- Glass/surface-container card with gold top border (border-t-2 or border-t-4)
- Price display (serif text-4xl)
- Form: name, email/dates, guests, submit button
- WhatsApp CTA below form
- Optional: testimonial mini-card with left gold border
- Optional: trust indicators (secure inquiry, privacy)

---

## Shared Components

### Navigation Variants

1. **Homepage:** Fixed, `rounded-full glass px-6 py-3` pill containing logo + links + CTA
2. **Listing Pages:** Sticky top, `glass-effect border-b border-white/10`, full-width bar with search input
3. **Detail Pages:** Sticky top, `bg-background-dark/80 backdrop-blur-md border-b border-primary/10`, uppercase tracking-widest links

### Footer Variants

1. **Homepage:** 4-col (logo+social, Fleet, Experience, Contact), social icons in glass circles
2. **Listing Pages:** 3-section (logo+desc+social, Fleet/Destinations/Newsletter cols), email subscribe
3. **Detail Pages:** Simplified 2-row (logo+desc left, link cols right) or minimal centered bar

### Buttons

| Type | Classes |
|------|---------|
| Primary solid | `bg-primary text-navy-deep/on-primary font-bold rounded-xl/lg/full` |
| Primary outline | `border border-primary/30 text-primary font-bold hover:bg-primary hover:text-navy-deep` |
| Glass/secondary | `glass/bg-white/10 text-white font-bold rounded-xl hover:bg-white/10` |
| CTA large | `px-10 py-4 font-black rounded-xl hover:scale-105` |
| Quick Book | `flex-[2] bg-primary uppercase tracking-widest` |
| Details | `flex-1 bg-white/10 text-white` |

### Section Header Pattern

```html
<span class="text-primary font-bold tracking-[0.2em] text-xs uppercase">Label</span>
<h3 class="font-serif text-4xl mt-2">Heading</h3>
```

Detail page variant:
```html
<div class="flex items-center gap-4">
    <div class="h-px w-12 bg-primary"></div>
    <span class="font-label uppercase tracking-[0.2em] text-xs text-primary">Label</span>
</div>
```

---

## Responsive Notes

- All grids collapse to single column on mobile
- Navigation links hidden on mobile (hamburger implied)
- Sticky booking widgets become non-sticky on mobile
- Yacht Detail (Variant B) has fixed bottom booking bar on mobile: `md:hidden fixed bottom-0 w-full`
- Filter pills scroll horizontally on mobile with `overflow-x-auto`
- Hero heights reduce on mobile (text sizes scale down with md: prefixes)

---

## WordPress Integration Notes

- Fleet cards powered by **BBC Boat Booking Core** plugin
- Booking widgets integrate with BBC booking system
- Destination/Package/Service/Itinerary/Offer pages use custom post types
- WhatsApp CTAs link to `https://wa.me/` with phone number
- Newsletter forms need backend integration
- Image sources use placeholder URLs — replace with WordPress media library / featured images
