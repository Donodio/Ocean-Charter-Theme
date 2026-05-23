# Ocean Charter — Stitch Design Reference

This is the authoritative design reference for the Ocean Charter WordPress theme.
Always match the HTML in this directory when implementing or updating any template, widget, or CSS.

---

## Design Tokens

| Token | Value |
|---|---|
| Primary / Gold | `#d9b230` |
| Background Dark | `#0a0f1a` / `#0a0c10` / `#201d12` (page-specific deep darks) |
| Navy Muted | `#1a2233` |
| Body font | Inter (300–900) |
| Heading / Serif font | Playfair Display (italic, 400–900) |
| Border radius default | `0.25rem` |
| Border radius pill | `9999px` |

## Glass Effect (used throughout)

```css
background: rgba(26, 34, 51, 0.7);
backdrop-filter: blur(12px);
-webkit-backdrop-filter: blur(12px);
border: 1px solid rgba(217, 178, 48, 0.1);
```

Listings page glass variant (slightly different):
```css
background: rgba(26, 42, 58, 0.6);
border: 1px solid rgba(255, 255, 255, 0.1);
```

---

## Navigation

- **Fixed** top of viewport, full width (`w-full`)
- Inner `<nav>` is a **rounded-full pill** with glass effect: `mx-auto flex max-w-7xl items-center justify-between rounded-full glass px-6 py-3`
- Logo: sailing icon (Material Symbols) + "Ocean Charter" in Playfair Display italic
- Nav links: `text-sm font-medium text-slate-300 hover:text-primary`
- CTA: `bg-primary text-navy-deep px-6 py-2 rounded-full text-sm font-bold`

---

## Fleet Listings Page

### Filter Bar
- Glassmorphism panel: `glass-effect rounded-2xl p-4 mb-12`
- 4 selects in a flex row: **Type**, **Capacity**, **Pricing**, **Location**
- Each select: `w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm`
- Label above each: `text-[10px] uppercase tracking-widest text-primary font-bold`
- "More Filters" button on the right: `bg-primary/20 border border-primary/30 text-primary rounded-xl`

### Boat Cards
- Background: `bg-white/5` (not `bg-navy-muted`)
- Border: `border border-white/10 hover:border-primary/50`
- Image: **`aspect-[4/3]`** (landscape)
- **Star rating** overlaid at **bottom** of image: gold star icon + score + "(N reviews)"
- Title: `text-xl font-bold group-hover:text-primary`
- Price: `text-xl font-black text-white` + `$X,XXX` + `/hr`
- Specs row: guest count + location icon
- **Two buttons**: `Details` (secondary: `bg-white/10 text-white flex-1`) + `Quick Book` (primary: `bg-primary text-background-dark flex-[2] uppercase tracking-widest`)

---

## Destinations Page

### Region Filter Pills (above grid)
- `All Regions` (active: `bg-primary text-background-dark`), Europe, Americas, Asia Pacific
- Inactive: `bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-slate-300 rounded-full`

### Destination Cards — PORTRAIT layout
- Card structure: **image on top**, **content below** (NOT an overlay card)
- Image: **`aspect-[4/5]`** (portrait, taller than wide) with `overflow-hidden`
- Gradient overlay on image: `from-background-dark/80 via-transparent to-transparent opacity-60`
- "Popular" badge: `absolute top-4 left-4` on image
- Content below image: `p-6 flex flex-col flex-grow`
  - `<h3>` title
  - `<p>` description text (1–2 lines)
  - Footer row (border-t): vessel count (left) + "Explore →" link (right)

### World Map Callout (below grid)
- **2-column** layout: **LEFT = map visual**, **RIGHT = text + bullets**
- Map side: dark bg with globe icon, mock UI with zoom buttons and "Live Location" card
- Text side: heading + paragraph + 3 bullet points (verified checkmark icon) + CTA button
- Section has `bg-slate-100 dark:bg-white/5 border-y` styling

---

## Homepage Key Elements

### Hero Booking Widget
- 4-column glassmorphism widget: **Destination** (text input), **Dates** (text input), **Guests** (select), **Search** button
- Glass panel: `glass rounded-2xl p-2 shadow-2xl` with `divide-x divide-white/10`
- Search button fills last column: `bg-primary hover:bg-primary/90 text-navy-deep font-bold rounded-xl`

### Featured Yacht Cards
- `aspect-[4/5]` portrait image
- "Top Rated" badge: absolute top-right, `bg-primary text-navy-deep rounded-full`
- Title (Playfair Display) + price `/day`
- Specs: length, guests, speed
- Single button: `border border-primary/30 text-primary hover:bg-primary hover:text-navy-deep`

---

## Page Files
- `homepage.html` — Hero + booking widget + featured yachts + why us + testimonial + gallery + CTA
- `fleet-listings.html` — Filter panel + 6-card grid + BBC integration status
- `itinerary.html` — Timeline layout + sticky map + booking card
- `services.html` — Cinematic hero + 4 service cards (2×2 staggered)
- `contact.html` — Hero + contact form + map + WhatsApp CTA
- `packages.html` — Category pills + 3 package cards + bespoke section
- `yacht-details.html` — Gallery hero + specs + amenities + captain + booking widget + reviews
- `destinations.html` — Hero + region filter + 6 portrait cards + world map callout + newsletter CTA
