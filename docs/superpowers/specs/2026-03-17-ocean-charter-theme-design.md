# Ocean Charter WordPress Theme — Full Design Spec

**Date:** 2026-03-17
**Project:** LUXURY YACHT RENTAL - A (Stitch projectId: `15543455046990239069`)
**Theme path:** `wp-content/themes/ocean-charter`
**Status:** Approved — ready for implementation

---

## 1. Overview

Ocean Charter is a world-class luxury yacht rental WordPress theme. It is:

- **Elementor-first** — all page content is editable via Elementor page builder; PHP templates are thin shells
- **BBC plugin-integrated** — Boat Booking Core CPTs (`boat`, `bbc_package`) and Elementor widgets drive bookings
- **Stitch-faithful** — every screen from the LUXURY YACHT RENTAL - A Stitch design is implemented pixel-perfect
- **Mobile-first responsive** — every breakpoint covered; fluid typography via `clamp()`
- **Animation-enriched** — scroll-triggered reveals, hover micro-interactions, cinematic transitions
- **Pexels-powered** — ~25 curated luxury yacht/ocean CDN images embedded in `inc/pexels-images.php`

---

## 2. Design Tokens

All tokens defined in `style.css` as CSS custom properties:

```css
--primary:      #d9b230;   /* Gold — CTAs, accents, highlights */
--primary-dark: #b8941f;   /* Hover state for gold */
--secondary:    #0a101a;   /* Deep navy — page backgrounds */
--surface:      #111a28;   /* Card/section backgrounds */
--surface-2:    #1a2535;   /* Elevated surfaces */
--text:         #f0ece3;   /* Primary body text */
--text-muted:   #8a9bb0;   /* Secondary/caption text */
--border:       rgba(217,178,48,0.15); /* Subtle gold border */

--font-heading: 'Playfair Display', Georgia, serif;
--font-body:    'Inter', system-ui, sans-serif;

--radius:       8px;
--radius-lg:    16px;
--radius-pill:  50px;

--shadow-card:  0 8px 40px rgba(0,0,0,0.4);
--shadow-glow:  0 0 40px rgba(217,178,48,0.15);

--transition:   0.35s cubic-bezier(0.4,0,0.2,1);
--transition-slow: 0.6s cubic-bezier(0.4,0,0.2,1);

--glass-bg:     rgba(17,26,40,0.70);
--glass-border: rgba(255,255,255,0.08);
```

### Typography Scale

```
H1: clamp(2.8rem, 5vw, 5rem)   — Playfair Display, letter-spacing -0.02em
H2: clamp(2rem, 3.5vw, 3.2rem) — Playfair Display
H3: clamp(1.4rem, 2.5vw, 2rem) — Playfair Display
H4: clamp(1.1rem, 2vw, 1.4rem) — Playfair Display
Body: 1rem / 1.7               — Inter 400
Small: 0.875rem                — Inter 500, letter-spacing 0.08em
Caption: 0.75rem uppercase     — Inter 600, letter-spacing 0.12em, --text-muted
```

---

## 2b. Confirmed BBC Plugin API (from source code audit)

**Elementor Widget slugs:**
- `bbc_booking_form` — Booking Form widget (entity_type: boat|package; controls: `bbc_select_boat`, `entity_type`)
- `boat-search` — Boat Search widget (controls: `show_location`, `show_type`, `show_dates`, `layout`, `button_text`, `results_page`)
- `bbc-boat-grid` — Boat Grid widget
- `bbc-boat-listing` — Boat Listing widget

**Boat CPT meta keys** (native post meta, no ACF required):
| Meta key | Type | Purpose |
|---|---|---|
| `_bbc_location` | string | Location name |
| `_bbc_boat_type` | string | Boat type slug |
| `_bbc_max_guests` | int | Max guest capacity |
| `_bbc_length` | float | Vessel length |
| `_bbc_length_unit` | string | `ft` or `m` |
| `_bbc_price_day` | float | Daily rate |
| `_bbc_price_hour` | float | Hourly rate |
| `_bbc_cabins` | int | Number of cabins |
| `_bbc_gallery` | array | Attachment IDs (WP media) |
| `_bbc_captain_included` | string | yes/no/optional |
| `_bbc_fuel_included` | string | yes/no |
| `_bbc_security_deposit` | float | Deposit amount |

**No ACF dependency.** All boat data is stored as native WordPress post meta. Gallery images retrieved via `get_post_meta($id, '_bbc_gallery', true)` (returns array of attachment IDs), then `wp_get_attachment_image_url($id, 'large')`.

**Fleet archive query parameters** (confirmed from `SearchHandler::modify_archive_query`):
| GET param | Meta key | Compare |
|---|---|---|
| `location` | `_bbc_location` | LIKE |
| `boat_type` | `_bbc_boat_type` | = |
| `guests` | `_bbc_max_guests` | >= (NUMERIC) |
| `min_cabins` | `_bbc_cabins` | >= (NUMERIC) |
| `min_price` | `_bbc_price_day` | >= (NUMERIC) |
| `max_price` | `_bbc_price_day` | <= (NUMERIC) |
| `orderby` | — | date / price / price_desc / title |

**Home page search bar** uses BBC's `boat-search` Elementor widget (`[boat_search]` shortcode equivalent). Fields submitted: `boat_type`, `location`, `date_from`, `date_to`. Redirects to boat CPT archive where `modify_archive_query` reads these GET params automatically.

**Booking form on single-boat:** Use BBC's `bbc_booking_form` Elementor widget in the Elementor Theme Builder single-boat template. No PHP shortcode for booking — BBC booking is Elementor widget only. PHP fallback (non-Elementor): `<a href="/contact/?boat=<?php echo get_the_ID(); ?>" class="btn-primary">Enquire to Book</a>`.

**No shortcode `[bbc_booking_form]` exists.** Do not attempt to call it with `do_shortcode`.

---

## 3. Elementor-First Architecture

### 3.1 PHP Template Shells

Every page template is reduced to a thin shell:

```php
<?php get_header(); ?>
<main id="main" class="oc-page oc-page--[name]">
    <?php while ( have_posts() ) : the_post(); ?>
        <?php the_content(); ?>
    <?php endwhile; ?>
</main>
<?php get_footer(); ?>
```

Exceptions:
- `archive-boat.php` — uses WP_Query with `$_GET` filter support; Elementor cannot replace archive loops
- `single-boat.php` — thin shell only; BBC booking form is placed via Elementor Theme Builder single-boat template using the `bbc_booking_form` Elementor widget. **Do NOT use `do_shortcode` for booking.** PHP fallback (when Elementor TB not set): `<a href="/contact/?boat=<?php echo get_the_ID(); ?>" class="btn-primary">Enquire to Book</a>`

### 3.2 Custom Elementor Widgets

Registered in `inc/elementor-widgets.php`, loaded via `elementor/widgets/register` action.

**Note:** BBC's `boat-search` Elementor widget handles the search form on the home page hero — no custom `OC_Booking_Search_Widget` needed. Theme widgets below are supplemental.

| Widget Class | Slug | Controls (type: default) |
|---|---|---|
**No `OC_Fleet_Grid_Widget` is needed.** Use BBC's `bbc-boat-grid` Elementor widget directly on the home page "Featured Vessels" section and on the fleet archive page. This widget is already built and registered by the BBC plugin.

| Widget Class | Slug | Controls (type: default) |
|---|---|---|
| `OC_Hero_Widget` | `oc-hero` | `heading` TEXT: "Define Your Horizon" · `subheading` TEXTAREA: tagline · `cta_label` TEXT: "Explore Fleet" · `cta_url` URL: /fleet/ · `overlay_opacity` SLIDER 0–1: 0.5 · `bg_image` IMAGE |
| `OC_Stats_Bar_Widget` | `oc-stats-bar` | `stats` REPEATER (max 4 items): each item → `number` TEXT + `suffix` TEXT (e.g. "+") + `label` TEXT |
| `OC_Destination_Card_Widget` | `oc-destination-card` | `image` IMAGE · `region_name` TEXT · `vessel_count` TEXT: "24 yachts" · `link` URL |
| `OC_Package_Card_Widget` | `oc-package-card` | `image` IMAGE · `title` TEXT · `price` TEXT: "From $4,500" · `duration` TEXT: "7 days" · `inclusions` REPEATER: each item `text` TEXT · `cta_url` URL |
| `OC_Testimonial_Widget` | `oc-testimonial` | `quote` TEXTAREA · `author` TEXT · `role` TEXT · `avatar` IMAGE |
| `OC_CTA_Strip_Widget` | `oc-cta-strip` | `heading` TEXT · `subtext` TEXT · `primary_label` TEXT: "Book Now" · `primary_url` URL · `secondary_label` TEXT: "WhatsApp Us" · `secondary_url` URL (auto-populated from `oc_whatsapp_url()`) |
| `OC_Itinerary_Day_Widget` | `oc-itinerary-day` | `day_number` TEXT: "Day 1" · `location` TEXT · `description` WYSIWYG · `activities` REPEATER: `label` TEXT · `images` GALLERY (2 images) |

### 3.3 Global Colors + Fonts in Elementor

`inc/elementor-support.php` registers:
- Global Colors: Primary Gold, Deep Navy, Surface, Text, Muted
- Global Fonts: Heading (Playfair Display), Body (Inter)

These appear in Elementor's Global Settings panel so all widgets inherit brand values automatically.

### 3.4 OCDI Demo Import

`ocdi/` directory contains:
- `content.xml` — WP export: 8 pages, nav menus (Primary + Footer), widget areas
- `elementor-templates.json` — Elementor template JSON (generated post-implementation via Elementor → Templates → Export)
- `widgets.wie` — sidebar/footer widget assignments

**Page list for content.xml:**
| Slug | Title | Template |
|---|---|---|
| `/` | Home | `front-page.php` (set as static front page) |
| `/fleet/` | Our Fleet | Default (CPT archive at `/boat/`) |
| `/itinerary/` | Sample Itinerary | `page-itinerary.php` |
| `/packages/` | Packages | `page-packages.php` |
| `/services/` | Services | `page-services.php` |
| `/destinations/` | Destinations | `page-destinations.php` |
| `/contact/` | Contact | Default (Elementor full-width) |

**Primary nav:** Home, Fleet, Destinations, Packages, Services, Itinerary, Contact
**Footer nav:** Privacy Policy, Terms, FAQ, Sitemap

**OCDI files are a post-implementation deliverable** — generated after all page layouts are built. `content.xml` and `widgets.wie` are authored manually; `elementor-templates.json` is exported from Elementor.

---

## 4. Header

**File:** `header.php` (complete rebuild)

### Logo
```html
<a href="<?php echo home_url(); ?>" class="oc-logo">
  <svg class="oc-logo__icon" ...> <!-- sailing yacht SVG icon, 32px, gold stroke --> </svg>
  <span class="oc-logo__text">Ocean <em>Charter</em></span>
</a>
```
- SVG: simplified sailing yacht silhouette, `stroke: var(--primary)`, no fill
- Text: "Ocean" in Inter 600, "Charter" in Playfair Display italic, gold

### Nav Structure
```html
<header class="oc-header" id="oc-header">
  <div class="oc-header__inner">
    [logo]
    <nav class="oc-nav" role="navigation">
      <div class="oc-nav__pill">
        <?php wp_nav_menu(['theme_location' => 'menu-1', 'container' => false]); ?>
      </div>
    </nav>
    <a href="/contact" class="btn-primary oc-header__cta">Book Now</a>
    <button class="oc-hamburger" aria-label="Menu" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>
  </div>
  <div class="oc-mobile-nav" id="oc-mobile-nav" hidden>
    <?php wp_nav_menu(['theme_location' => 'menu-1', 'container' => false, 'menu_class' => 'oc-mobile-nav__list']); ?>
    <a href="/contact" class="btn-primary">Book Now</a>
  </div>
</header>
```

### Nav Pill Styles
- Background: `var(--glass-bg)`, `backdrop-filter: blur(12px)`
- Border: `1px solid var(--glass-border)`
- Border-radius: `var(--radius-pill)`
- Padding: `6px 8px`
- Links: `14px Inter 500`, padding `10px 18px`, `border-radius: 30px`
- Active/hover link: `background: rgba(217,178,48,0.12)`, color `var(--primary)`

### Scroll Behavior
- At `>80px` scroll: header gets `.scrolled` class → `background: rgba(10,16,26,0.95)`, `backdrop-filter: blur(20px)`, nav pill padding tightens
- Transition: `0.3s ease`

### Mobile Menu (≤768px)
- Hamburger 3-bar → animated X (`transform: rotate` on bars)
- `#oc-mobile-nav` slides down from header (`max-height` animation: 0 → 400px, `0.35s ease`)
- Links stack vertically, `18px`, `48px min-height` tap targets
- Closes on: outside click, ESC key, link click
- "Book Now" button full-width at bottom of mobile menu

---

## 5. Footer

**File:** `footer.php` — 4-column layout on desktop, 2-col at 768px, 1-col at 480px.

**Layout:**
```
| Logo + tagline + social icons | Quick Links (nav menu) | Services links | Contact info |
```

**Column 1 — Brand:**
- SVG logo (same as header), tagline "Luxury yacht charters across the world's most coveted waters."
- Social icons: Instagram, Facebook, YouTube (SVG, `color: var(--text-muted)`, hover gold)

**Column 2 — Quick Links:**
- `wp_nav_menu(['theme_location' => 'footer', 'container' => false])` — links from Footer nav menu location

**Column 3 — Services:**
- Hardcoded links: Crewed Charters, Private Events, Water Sports, Corporate Charters, Concierge

**Column 4 — Contact:**
- WhatsApp number (pulled from Customizer: `oc_whatsapp_number`)
- Email address (Customizer: `oc_contact_email`, default `info@oceancharter.com`)
- Address (Customizer: `oc_contact_address`)

**Bottom bar:** Copyright `© <?php echo date('Y'); ?> Ocean Charter. All rights reserved.` — centered, `var(--text-muted)`, `border-top: 1px solid var(--border)`.

**Background:** `var(--secondary)`, top border `var(--border)`.

**WhatsApp floating button:**
- Fixed `bottom: 28px; right: 28px; z-index: 9999`
- 56px circle, `background: #25D366`, white SVG WhatsApp icon
- `href="https://wa.me/<?php echo esc_attr(get_theme_mod('oc_whatsapp_number','15551234567')); ?>"`
- Entrance: `animation: bounceIn 0.5s 2s both`
- Tooltip on hover: "Chat with us"
- Elementor Theme Builder can override footer via `elementor-location/footer` hook

---

## 6. Pages

### 6.1 Home (`front-page.php`)

**Sections (top to bottom):**

1. **Hero** — full viewport, Pexels luxury yacht image, overlay `linear-gradient(to bottom, rgba(10,16,26,0.3), rgba(10,16,26,0.7))`, `OC_Hero_Widget`:
   - H1: "Define Your Horizon" (Stitch copy)
   - Sub: "Bespoke yacht charters across the world's most coveted waters"
   - Booking Search Bar (horizontal, below headline): Destination select + Date input + Guests counter + "Search Fleet" gold button → redirects to `/fleet/` with `$_GET` params
   - Animation: H1 fadeInUp 0.6s, sub fadeInUp 0.8s, search bar fadeInUp 1s

2. **Stats Bar** — `OC_Stats_Bar_Widget`: 150+ Vessels / 25 Destinations / 12 Years / 4.9★ Rating — dark bg strip with gold numbers, counter-up animation on scroll

3. **Featured Vessels** — BBC's `bbc-boat-grid` Elementor widget (3 posts, configured in Elementor), "Explore Fleet" link below

4. **Why Us** — 4 icon pillars (Expert Crew, White-Glove Service, Bespoke Itineraries, 24/7 Support) + featured testimonial pull-quote. Staggered fadeInUp on scroll.

5. **Destinations** — 4-card masonry-style grid, each with Pexels region image, overlay name + vessel count. Hover: scale 1.04, gold border glow.

6. **Services** — 4-card grid (Crewed Charters, Private Events, Water Sports, Concierge). Cards: dark bg, icon, title, 2-line description.

7. **Packages Teaser** — 3 package cards from `bbc_package` CPT (or fallback). Price badge, duration, "View Package" CTA.

8. **CTA Strip** — `OC_CTA_Strip_Widget`: "Ready to Set Sail?" headline, WhatsApp CTA + Book Now CTA.

### 6.2 Fleet / Yacht Listing (`archive-boat.php`)

- Hero: short 40vh hero, "Our Fleet" heading, Pexels ocean image
- **Filter Bar**: Guests (select), Size (select), Type (select), Sort (select) — submits via GET, PHP reads and builds `WP_Query` meta_query. Pre-populated from `$_GET` (from home page booking search)
- 3-col responsive grid (2-col at 1024px, 1-col at 600px)
- **Vessel Card**: aspect-ratio 4/3 Pexels/featured image, hover overlay with quick-specs (guests, length, cabins), gold "View Yacht" CTA, price badge top-right
- Pagination: `paginate_links()`
- Animation: cards stagger fadeInUp 0.1s delay increments

### 6.3 Yacht Detail (`single-boat.php`)

- **Split Gallery Hero**: CSS Grid — left 60% large portrait image, right 40% two stacked images. Images from `oc_get_boat_gallery($id)` helper which reads `_bbc_gallery` post meta (array of WP attachment IDs via `wp_get_attachment_image_url()`), falling back to featured image, then to Pexels constants. "View All Photos" button triggers `:target` CSS lightbox (see Section 8).
- **Quick Specs Bar**: Guests / Length / Cabins / Builder / Year — horizontal strip, gold icon + number
- **Main Content Area** (2-col at desktop):
  - Left (65%): Description, Amenities grid (icon + label, 4-col), Captain bio card, Testimonials carousel
  - Right (35%, sticky): Booking sidebar
    - Price display: "From $X,XXX / day"
    - **BBC Widget**: `bbc_booking_form` Elementor widget placed in Elementor Theme Builder single-boat template. **No `do_shortcode` call.** PHP fallback in `single-boat.php` shell: `<a href="/contact/?boat=<?php echo get_the_ID(); ?>" class="btn-primary">Enquire to Book</a>`
    - WhatsApp quick enquiry link
- **Related Vessels**: 3-col grid, same CPT, excluding current post
- Animation: gallery images stagger in, specs bar counter-up, sticky sidebar appears on scroll past hero

### 6.4 Packages (`page-packages.php`)

- Hero: full-bleed Pexels image (yacht at sunset), "Curated Packages" heading
- **Category Filter Tabs**: All / Day Charter / Weekend Getaway / Blue Water Voyage — JS tab filter, no page reload. Active tab: gold underline + background tint.
- Package cards from `bbc_package` CPT (3-col, 2-col at 768px). Card: image (4/3), tag badge, title, price, duration, 3-line inclusions preview, "View Details" CTA.
- Fallback: 3 hardcoded demo packages if CPT has no entries.
- **Bespoke Voyages** section: editorial layout, large Pexels image left, text right, WhatsApp/Contact CTAs.
- Testimonials: 3-col, gold quote marks, author + role.
- Animation: package cards stagger in, tab switches with crossfade.

### 6.5 Services (`page-services.php`)

- Hero: Pexels luxury interior image, "White-Glove Services" heading
- Intro: centered text block, italicised subheading
- **4 Service Detail Sections** (alternating image/text layout):
  1. Private Chef & Cuisine — Pexels chef/food image
  2. Water Toys & Adventures — Pexels water sports image
  3. Corporate & Private Events — Pexels event/deck image
  4. Concierge & Shore Excursions — Pexels marina/port image
- Each section: image (45% width) + text (55%) with heading, 3-bullet features, CTA link. Alternating: image-left / image-right.
- CTA Strip at bottom.
- Animation: each service section reveals on scroll with alternating slide-in directions.

### 6.6 Itinerary (`page-itinerary.php`) — NEW FILE

- **Hero**: "Sample Itinerary" heading, subtext "A 7-Day Aegean Journey", Pexels Greece island image
- **Layout** (2-col desktop, 1-col mobile):
  - Left (65%): Day-by-day timeline
    - Each day: vertical line connector, numbered circle (gold border), day label, location name, 2-paragraph description, 2 activity images (side by side)
    - Days: Departure → Santorini → Mykonos → Delos → Paros → Hydra → Athens Return
  - Right (35%, sticky from 100px scroll):
    - 7-day summary list (day + destination)
    - "Book This Itinerary" gold CTA (→ contact page)
    - Price estimate card: "From $XX,XXX for 7 days"
    - WhatsApp link
- CTA Strip at bottom: "Design Your Itinerary"
- Animation: timeline items reveal sequentially on scroll (0.15s stagger), sticky sidebar smooth appearance.

### 6.7 Destinations (`page-destinations.php`)

- Hero: world map or aerial ocean Pexels image, "Our Destinations" heading
- **Region Filter Tabs**: All / Mediterranean / Caribbean / Indian Ocean / Pacific — JS filter
- **8 Destination Cards** (4-col → 2-col → 1-col): each with Pexels region image, overlay region name + country list + vessel count badge. Hover: scale + gold border glow.
- Each card links to `/boat/?location=X` (using the confirmed `location` GET parameter that maps to `_bbc_location` LIKE query) to pre-filter the fleet archive.
- Editorial "Why Charter With Us" section between cards and footer.
- Animation: cards stagger in, filter transitions with fade.

### 6.8 Contact (`page-contact.php`)

- Hero: Pexels marina/harbour image, "Get In Touch" heading
- **2-col layout**:
  - Left (55%): Contact form — Name, Email, Phone, Message, Departure Date (date input), Number of Guests, Vessel Preference (select). **Implementation: custom `oc_handle_contact_form()` handler** — POST to `admin-post.php` with action `oc_contact`, nonce `oc_contact_nonce`, honeypot `oc_hp` field. On success: `wp_mail()` to `oc_contact_email` Customizer setting. PHP validates all fields, redirects with `?sent=1` query var for success banner.
  - Right (45%): Static map image (Pexels marina aerial fallback — no Google Maps iframe to avoid GDPR consent requirement), WhatsApp CTA card, office address, phone, email (all from Customizer).
- **Success state:** After `oc_handle_contact_form()` verifies nonce and sends mail, it sets `set_transient('oc_contact_' . md5($user_ip), 1, 60)` then redirects to `/contact/?sent=1`. On page load, success banner renders only if `get_transient('oc_contact_' . md5($user_ip))` is truthy AND `$_GET['sent'] === '1'`; the transient is then deleted. This prevents direct URL access from showing the banner.
- Animation: form fields fade in staggered, contact info slides in from right.

---

## 7. Pexels Image Registry (`inc/pexels-images.php`)

Stable CDN URLs — no runtime API calls. ~25 images fetched during implementation:

| Constant | Subject |
|---|---|
| `OC_IMG_HERO_HOME` | Luxury yacht, open ocean, golden hour |
| `OC_IMG_HERO_FLEET` | Aerial fleet shot |
| `OC_IMG_HERO_PACKAGES` | Yacht at sunset |
| `OC_IMG_HERO_SERVICES` | Superyacht interior |
| `OC_IMG_HERO_ITINERARY` | Greek island coastline |
| `OC_IMG_HERO_DESTINATIONS` | Aerial ocean/islands |
| `OC_IMG_HERO_CONTACT` | Marina harbour at dusk |
| `OC_IMG_DEST_MEDITERRANEAN` | Santorini cliffs |
| `OC_IMG_DEST_CARIBBEAN` | Caribbean turquoise water |
| `OC_IMG_DEST_INDIAN_OCEAN` | Maldives overwater |
| `OC_IMG_DEST_PACIFIC` | Pacific horizon |
| `OC_IMG_SVC_CHEF` | Fine dining on deck |
| `OC_IMG_SVC_WATERTOYS` | Jet ski / water sports |
| `OC_IMG_SVC_EVENTS` | Deck party / corporate |
| `OC_IMG_SVC_CONCIERGE` | Port/marina concierge |
| `OC_IMG_VESSEL_1..6` | 6 distinct yacht exterior shots |
| `OC_IMG_ITINERARY_1..5` | Destination location shots |

---

## 8. Animation System

### CSS Keyframes (in `style.css`)
```css
@keyframes fadeInUp    { from { opacity:0; transform: translateY(40px); } to { opacity:1; transform: translateY(0); } }
@keyframes fadeInLeft  { from { opacity:0; transform: translateX(-40px); } to { opacity:1; transform: translateX(0); } }
@keyframes fadeInRight { from { opacity:0; transform: translateX(40px); } to { opacity:1; transform: translateX(0); } }
@keyframes fadeIn      { from { opacity:0; } to { opacity:1; } }
@keyframes shimmer     { 0%,100% { opacity:0.6; } 50% { opacity:1; } }
@keyframes float       { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-8px); } }
@keyframes bounceIn    { 0% { opacity:0; transform: scale(0.3); } 50% { transform: scale(1.05); } 70% { transform: scale(0.9); } 100% { opacity:1; transform: scale(1); } }
```

**Note:** There is no `@keyframes counterUp` — the CSS entrance animation for stats elements uses `fadeInUp`. The number counter is JS-only (see below).

### Scroll-Triggered Reveal (IntersectionObserver in `assets/js/main.js`)
- All elements with `data-animate` attribute start hidden: `opacity:0; transform: translateY(40px)` (set via JS on `DOMContentLoaded`, not inline style — prevents FOUC)
- `IntersectionObserver` with `threshold: 0.15` adds `.is-visible` class
- `.is-visible` CSS: `opacity:1; transform: none; transition: var(--transition-slow)`
- Stagger: `data-delay="0.1"` → `0.2"` etc. JS reads `el.dataset.delay` and applies `transition-delay`

### JS Number Counter (stats bar)
- Triggered when stats bar enters viewport (same IntersectionObserver)
- HTML: `<span class="oc-stat__number" data-target="150" data-suffix="+">0</span>`
- JS animates from 0 to `data-target` over 1.5s using `requestAnimationFrame` and `easeOutQuart` easing
- Suffix string appended after counter reaches target: "150+" / "25" / "12" / "4.9★" (data-suffix="★" for rating)
- Runs once only (observer disconnects after trigger)

### Hover Micro-interactions
- Vessel/destination cards: `transform: translateY(-6px)`, `box-shadow: var(--shadow-glow)`, `transition: var(--transition)`
- Buttons: `filter: brightness(1.08)` on hover; gold bg stays gold
- Nav links: background pill fade in (`rgba(217,178,48,0.12)`), color to `var(--primary)`
- Gallery images: `transform: scale(1.03)` on hover, `overflow: hidden` on wrapper

### CSS-Only Gallery Lightbox (single-boat.php)
`:target` approach:
```html
<a href="#photo-1" class="oc-gallery__thumb"><img src="..."></a>
<div class="oc-lightbox" id="photo-1">
  <a href="#" class="oc-lightbox__close">✕</a>
  <img src="..." alt="...">
</div>
```
- `.oc-lightbox` default: `display:none` via `opacity:0; pointer-events:none; position:fixed; inset:0; z-index:10000; background:rgba(0,0,0,0.9)`
- `.oc-lightbox:target`: `opacity:1; pointer-events:all`
- Close: `href="#"` removes `:target`. ESC key handled by JS fallback.
- WCAG: `<a>` triggers are keyboard-focusable; close button has `aria-label="Close photo"`

### Reduced Motion
```css
@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after { animation-duration: 0.01ms !important; transition-duration: 0.01ms !important; }
}
```
JS counter: checks `window.matchMedia('(prefers-reduced-motion: reduce)').matches` and skips animation, jumping directly to final value.

---

## 9. Responsive Breakpoints

| Breakpoint | Layout Changes |
|---|---|
| `≥1200px` | Full 3-col grids, split hero gallery, 2-col page layouts, full pill nav |
| `1024px` | 2-col fleet/destinations grids; single-boat sidebar moves below main content |
| `768px` | 1-col everywhere; hamburger nav replaces pill nav; hero search bar fields stack vertically; footer 2-col |
| `600px` | Fleet grid switches to 1-col (overrides 2-col from 1024px breakpoint) |
| `480px` | Footer 1-col; hero H1 font scales to minimum `clamp()` bound; all buttons full-width; vessel specs wrap to 2-col; padding reduced to `1rem` |

**Component-specific rules at 480px:**
- **Hero**: `min-height: 100svh`; search bar becomes stacked form; H1 `font-size: clamp(2.2rem,8vw,3rem)`
- **Vessel cards**: image `aspect-ratio: 16/9` (less tall); specs bar shows 2 items per row
- **Nav**: hamburger tap target `44px × 44px`; mobile menu `padding: 1.5rem`
- **Stats bar**: 2×2 grid (from 4-col)

All font sizes use `clamp()`. All grid gaps use `clamp(1rem, 3vw, 2.5rem)`. Images use `width:100%; height:auto; object-fit:cover`. No horizontal scroll at any breakpoint.

---

## 10. File Structure

```
themes/ocean-charter/
├── style.css                      # Design tokens + global styles (enhanced)
├── functions.php                  # Requires all inc/ files
├── header.php                     # REBUILT — sailing icon logo, pill nav, hamburger
├── footer.php                     # Enhanced — WhatsApp floating button
├── front-page.php                 # REBUILT as thin shell (Elementor)
├── archive-boat.php               # Enhanced — filter reads $_GET, Pexels fallbacks
├── single-boat.php                # Enhanced — split gallery hero, BBC widget
├── page-packages.php              # Enhanced — category filter tabs, Pexels
├── page-services.php              # Enhanced — Pexels service images
├── page-itinerary.php             # NEW — day-by-day timeline, sticky sidebar
├── page-destinations.php          # Enhanced — region tabs, Pexels cards
├── page-contact.php               # Enhanced — map, WhatsApp, proper form
├── inc/
│   ├── setup.php                  # Nav menus, theme supports, package_type taxonomy
│   ├── enqueue.php                # Scripts + styles
│   ├── customizer.php             # NEW — Customizer settings (WhatsApp, email, address)
│   ├── elementor-support.php      # Global colors/fonts, TB support
│   ├── elementor-widgets.php      # NEW — 7 custom widget classes
│   ├── pexels-images.php          # NEW — ~25 CDN image URL constants
│   ├── template-tags.php          # Helper functions (oc_whatsapp_url, oc_get_boat_gallery, oc_boat_meta)
│   └── ocdi-support.php           # Demo import hooks
├── assets/
│   ├── js/main.js                 # IntersectionObserver, counter-up, hamburger, filters
│   └── css/ (none — all in style.css)
└── ocdi/
    ├── content.xml
    ├── elementor-templates.json
    └── widgets.wie
```

---

## 11. BBC Plugin Integration Points

| Location | Integration |
|---|---|
| `single-boat.php` | `bbc_booking_form` Elementor widget in TB template; PHP shell has enquiry link fallback only |
| `archive-boat.php` | Direct `WP_Query` loop with `meta_query` built from `$_GET` params (handled by BBC's `SearchHandler::modify_archive_query`) |
| `page-packages.php` | `WP_Query` on `bbc_package` CPT |
| `inc/elementor-widgets.php` | Registers 7 custom OC widgets (OC_Hero, OC_Stats_Bar, OC_Destination_Card, OC_Package_Card, OC_Testimonial, OC_CTA_Strip, OC_Itinerary_Day) |

---

## 12. Stitch Screen → Template Mapping

All 8 screens from Stitch project `15543455046990239069`:

| Screen ID | Screen Name | PHP Template |
|---|---|---|
| `10537b9645cd4207831fe4b8bcf4d213` | Homepage | `front-page.php` |
| `14476268c148448489033ffd49b34e2c` | Yacht Listing | `archive-boat.php` (CPT archive) |
| `f007aec2ad5e4e9cbfdd467d46671b4b` | Yacht Detail | `single-boat.php` (CPT single) |
| `2132f4f325f3403397db3e144d7fd511` | Itinerary | `page-itinerary.php` (new) |
| `c86e06d7dc2d444f8b1de2fe9730b0ff` | Packages | `page-packages.php` |
| `22afdb6a3c5c448e8e04f365ac4011b4` | Services | `page-services.php` |
| `f8ee058026024acaadbcbe520f072fbb` | Destinations | `page-destinations.php` |
| `4e5c129f78bf4285ab18f100bbc0b05c` | Contact | `page-contact.php` |

---

## 13. Theme Configuration & Helpers

### WordPress Customizer Settings (`inc/customizer.php`)

```php
// WhatsApp number (E.164 format, no + prefix)
oc_whatsapp_number  string  default: '15551234567'

// Contact details
oc_contact_email    string  default: 'info@oceancharter.com'
oc_contact_phone    string  default: '+1 (555) 123-4567'
oc_contact_address  string  default: '123 Marina Drive, Miami, FL 33101'
```

### Helper Functions (`inc/template-tags.php`)

```php
// Returns wa.me URL with configured number
function oc_whatsapp_url( $message = '' ): string {
    $number = get_theme_mod( 'oc_whatsapp_number', '15551234567' );
    $url = 'https://wa.me/' . $number;
    if ( $message ) $url .= '?text=' . rawurlencode( $message );
    return esc_url( $url );
}

// Returns boat gallery images (BBC meta, falls back to featured image, then Pexels constant)
function oc_get_boat_gallery( int $boat_id ): array {...}

// Returns boat meta value with fallback
function oc_boat_meta( int $boat_id, string $key, string $fallback = '' ): string {...}
```

### `functions.php` Requirements
```php
define( 'OC_VERSION', '1.0.0' );
define( 'OC_THEME_DIR', get_template_directory() );
define( 'OC_THEME_URL', get_template_directory_uri() );

load_theme_textdomain( 'ocean-charter', OC_THEME_DIR . '/languages' );

// Required files
require_once OC_THEME_DIR . '/inc/setup.php';
require_once OC_THEME_DIR . '/inc/enqueue.php';
require_once OC_THEME_DIR . '/inc/customizer.php';
require_once OC_THEME_DIR . '/inc/template-tags.php';
require_once OC_THEME_DIR . '/inc/pexels-images.php';
require_once OC_THEME_DIR . '/inc/elementor-support.php';
if ( defined( 'ELEMENTOR_VERSION' ) ) {
    require_once OC_THEME_DIR . '/inc/elementor-widgets.php';
}
require_once OC_THEME_DIR . '/inc/ocdi-support.php';
```

### Package Category Taxonomy

**Confirmed from BBC source:** `bbc_package` CPT registers NO taxonomy (`'supports' => ['title', 'editor', 'thumbnail']` only). The theme must register a custom taxonomy.

`inc/setup.php` registers:
```php
register_taxonomy( 'package_type', 'bbc_package', [
    'label'        => 'Package Types',
    'hierarchical' => false,
    'public'       => true,
    'rewrite'      => ['slug' => 'package-type'],
    'show_in_rest' => true,
] );
```
Default terms (created on theme activation via `after_switch_theme` hook): `day-charter` ("Day Charter"), `weekend-getaway` ("Weekend Getaway"), `blue-water-voyage` ("Blue Water Voyage").

**Tab filter:** JS-powered. All package cards rendered on load with `data-type="[term_slug]"` attribute. JS show/hide on tab click. No AJAX. Fallback: if a term has no packages, the tab renders as disabled (muted color, not clickable).

---

## 14. Success Criteria

- [ ] All 8 Stitch screens faithfully reproduced
- [ ] Every page editable in Elementor (Edit with Elementor opens correctly)
- [ ] BBC booking form renders on single-boat page
- [ ] Mobile nav works at 375px, 414px, 768px
- [ ] Animations trigger correctly on scroll, respect `prefers-reduced-motion`
- [ ] Pexels images load via CDN (no 404s)
- [ ] OCDI demo import creates all 8 pages correctly
- [ ] Footer WhatsApp floating button visible on all pages
- [ ] Hero booking search redirects to fleet with correct `$_GET` params
- [ ] `clamp()` typography scales correctly at all viewports
