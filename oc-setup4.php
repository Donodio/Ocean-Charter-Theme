<?php
/**
 * OC Setup 4 — Rebuild Fleet & Destinations Elementor layouts.
 *
 * Fixes:
 *  - Fleet  (ID 81): banner hero + type-filter bar + luxury BBC boat grid
 *  - Destinations (ID 79): banner hero + 4-col destination cards + CTA strip
 *
 * Run once via WP-CLI:
 *   wp eval-file wp-content/themes/ocean-charter/oc-setup4.php
 */

if ( ! defined( 'ABSPATH' ) ) {
    // Running via WP-CLI eval-file — bootstrap WordPress.
    $wp_load = dirname( __DIR__, 3 ) . '/wp-load.php';
    if ( file_exists( $wp_load ) ) {
        require $wp_load;
    } else {
        echo "wp-load.php not found.\n"; exit(1);
    }
}

// ── Image constants (defined by pexels-images.php via functions.php) ─────────
$fleet_img   = defined('OC_IMG_HERO_FLEET')        ? OC_IMG_HERO_FLEET        : '';
$dest_img    = defined('OC_IMG_HERO_DESTINATIONS') ? OC_IMG_HERO_DESTINATIONS : '';
$med_img     = defined('OC_IMG_DEST_MEDITERRANEAN') ? OC_IMG_DEST_MEDITERRANEAN : '';
$carib_img   = defined('OC_IMG_DEST_CARIBBEAN')     ? OC_IMG_DEST_CARIBBEAN     : '';
$indian_img  = defined('OC_IMG_DEST_INDIAN_OCEAN')  ? OC_IMG_DEST_INDIAN_OCEAN  : '';
$pacific_img = defined('OC_IMG_DEST_PACIFIC')       ? OC_IMG_DEST_PACIFIC       : '';

// ── Helper ────────────────────────────────────────────────────────────────────
function oc4_set_elementor( $post_id, array $data, string $label ) {
    // Store Elementor data (wp_slash survives wp_unslash inside update_post_meta)
    update_post_meta( $post_id, '_elementor_data', wp_slash( json_encode( $data ) ) );
    update_post_meta( $post_id, '_elementor_edit_mode', 'builder' );
    // Clear cached post_content so Elementor's filter can rerender from JSON
    wp_update_post( [ 'ID' => $post_id, 'post_content' => '' ] );
    echo "✓ {$label} (ID {$post_id}) updated.\n";
}

function oc4_hero_widget( string $wid, string $eyebrow, string $heading, string $bg_url ): array {
    return [
        'id'         => $wid,
        'elType'     => 'widget',
        'widgetType' => 'oc-hero',
        'settings'   => [
            'hero_style'      => 'banner',
            'eyebrow'         => $eyebrow,
            'heading'         => $heading,
            'subheading'      => '',
            'cta_label'       => '',
            'secondary_label' => '',
            'show_search'     => 'no',
            'bg_image'        => [ 'url' => $bg_url, 'id' => 0 ],
            'overlay_opacity' => [ 'size' => 0.55, 'unit' => 'px' ],
        ],
        'elements' => [],
    ];
}

function oc4_full_container( string $cid, array $elements, array $extra_settings = [] ): array {
    return [
        'id'       => $cid,
        'elType'   => 'container',
        'settings' => array_merge( [
            'content_width' => 'full',
            'padding'       => [ 'unit' => 'px', 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'isLinked' => false ],
        ], $extra_settings ),
        'elements' => $elements,
    ];
}

function oc4_boxed_container( string $cid, array $elements, array $extra_settings = [] ): array {
    return [
        'id'       => $cid,
        'elType'   => 'container',
        'settings' => array_merge( [
            'content_width'    => 'boxed',
            'padding'          => [ 'unit' => 'px', 'top' => '60', 'right' => '0', 'bottom' => '80', 'left' => '0', 'isLinked' => false ],
        ], $extra_settings ),
        'elements' => $elements,
    ];
}

// ── Filter bar HTML (fleet page) — glassmorphism SELECT panel matching Stitch ──
$filter_html = <<<'HTML'
<style>
.oc-fleet-filter-bar{padding:32px 0;background:transparent;}
.oc-fleet-filter-inner{max-width:1200px;margin:0 auto;padding:0 clamp(1rem,4vw,2.5rem);}
.oc-filter-glass{background:rgba(26,37,53,0.7);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,0.08);border-radius:16px;padding:24px 28px;}
.oc-filter-heading{font-size:11px;font-weight:700;letter-spacing:.15em;text-transform:uppercase;color:var(--text-muted);margin-bottom:16px;}
.oc-filter-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;}
.oc-filter-group{display:flex;flex-direction:column;gap:6px;}
.oc-filter-group label{font-size:11px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:var(--text-muted);}
.oc-filter-group select{background:rgba(10,16,26,0.6);border:1px solid rgba(255,255,255,0.1);border-radius:8px;color:var(--text);font-size:13px;padding:10px 14px;appearance:none;-webkit-appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' fill='none'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%238a9bb0' stroke-width='1.5' stroke-linecap='round'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 12px center;cursor:pointer;transition:border-color .2s;}
.oc-filter-group select:focus{outline:none;border-color:rgba(217,178,48,0.5);}
.oc-filter-group select:hover{border-color:rgba(217,178,48,0.3);}
@media(max-width:768px){.oc-filter-grid{grid-template-columns:repeat(2,1fr);}}
@media(max-width:480px){.oc-filter-grid{grid-template-columns:1fr;}}
</style>
<div class="oc-fleet-filter-bar">
  <div class="oc-fleet-filter-inner">
    <div class="oc-filter-glass">
      <div class="oc-filter-heading">Filter Vessels</div>
      <div class="oc-filter-grid">
        <div class="oc-filter-group">
          <label for="oc-filter-type">Vessel Type</label>
          <select id="oc-filter-type" onchange="if(this.value)location.href='/fleet/?type='+this.value;else location.href='/fleet/'">
            <option value="">All Types</option>
            <option value="motor-yacht">Motor Yacht</option>
            <option value="sailing-yacht">Sailing Yacht</option>
            <option value="catamaran">Catamaran</option>
            <option value="gulet">Gulet</option>
          </select>
        </div>
        <div class="oc-filter-group">
          <label for="oc-filter-guests">Guest Capacity</label>
          <select id="oc-filter-guests" onchange="if(this.value)location.href='/fleet/?guests='+this.value;else location.href='/fleet/'">
            <option value="">Any Capacity</option>
            <option value="2-6">2 – 6 Guests</option>
            <option value="7-12">7 – 12 Guests</option>
            <option value="13-20">13 – 20 Guests</option>
            <option value="20+">20+ Guests</option>
          </select>
        </div>
        <div class="oc-filter-group">
          <label for="oc-filter-price">Pricing</label>
          <select id="oc-filter-price" onchange="if(this.value)location.href='/fleet/?price='+this.value;else location.href='/fleet/'">
            <option value="">Any Budget</option>
            <option value="luxury">Luxury (€5k+/day)</option>
            <option value="premium">Premium (€2k–€5k)</option>
            <option value="standard">Standard (under €2k)</option>
          </select>
        </div>
        <div class="oc-filter-group">
          <label for="oc-filter-region">Destination</label>
          <select id="oc-filter-region" onchange="if(this.value)location.href='/fleet/?region='+this.value;else location.href='/fleet/'">
            <option value="">All Regions</option>
            <option value="mediterranean">Mediterranean</option>
            <option value="caribbean">Caribbean</option>
            <option value="indian-ocean">Indian Ocean</option>
            <option value="pacific">Pacific</option>
          </select>
        </div>
      </div>
    </div>
  </div>
</div>
HTML;

// ── Destination cards HTML — matches Stitch design exactly:
//    portrait aspect-[4/5] cards, content below image, world map callout 2-col ─
$destinations_html = <<<HTML
<style>
/* Region filter pills (Stitch: All Regions active=bg-primary, others bg-white/5) */
.oc-dest-filters{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:48px;}
.oc-dest-filter-pill{font-size:13px;font-weight:500;padding:8px 20px;border-radius:9999px;text-decoration:none;transition:all .2s;white-space:nowrap;color:rgba(148,163,184,1);background:rgba(255,255,255,0.05);}
.oc-dest-filter-pill:hover{background:rgba(255,255,255,0.1);color:#fff;}
.oc-dest-filter-pill.active{background:#D9B230;color:#0a0c10;font-weight:700;}

/* 3-col destination grid — portrait cards with content below (Stitch pattern) */
.oc-dest-grid-static{display:grid;grid-template-columns:repeat(3,1fr);gap:32px;width:100%;}

.oc-dest-card-s{display:flex;flex-direction:column;overflow:hidden;border-radius:12px;text-decoration:none;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);transition:border-color .3s ease,transform .3s ease,box-shadow .3s ease;}
.oc-dest-card-s:hover{border-color:rgba(217,178,48,0.5);transform:translateY(-4px);box-shadow:0 20px 60px rgba(0,0,0,0.4);}

/* Portrait image block — aspect-[4/5] matches Stitch exactly */
.oc-dest-card-s__img{position:relative;aspect-ratio:4/5;overflow:hidden;}
.oc-dest-card-s__img::after{content:'';position:absolute;inset:0;background:linear-gradient(to top,rgba(10,16,26,.8) 0%,transparent 60%);opacity:.6;transition:opacity .3s;pointer-events:none;}
.oc-dest-card-s:hover .oc-dest-card-s__img::after{opacity:.8;}
.oc-dest-card-s__img img{width:100%;height:100%;object-fit:cover;transition:transform .7s ease;}
.oc-dest-card-s:hover .oc-dest-card-s__img img{transform:scale(1.1);}
.oc-dest-card-s__badge{position:absolute;top:16px;left:16px;z-index:1;background:rgba(217,178,48,.9);color:#0a0c10;font-size:10px;font-weight:800;letter-spacing:.1em;text-transform:uppercase;padding:4px 12px;border-radius:9999px;}

/* Content below image (NOT overlay — matches Stitch card pattern) */
.oc-dest-card-s__body{padding:24px;display:flex;flex-direction:column;flex:1;}
.oc-dest-card-s__body h3{font-family:var(--font-heading);font-size:1.25rem;font-weight:700;color:#f8fafc;margin:0 0 8px;}
.oc-dest-card-s__body p{font-size:.875rem;color:rgba(148,163,184,1);line-height:1.6;margin:0;flex:1;}
.oc-dest-card-s__footer{display:flex;align-items:center;justify-content:space-between;margin-top:16px;padding-top:16px;border-top:1px solid rgba(255,255,255,0.1);}
.oc-dest-card-s__count{font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#D9B230;}
.oc-dest-card-s__explore{font-size:14px;font-weight:700;color:#f8fafc;display:flex;align-items:center;gap:4px;transition:color .2s;}
.oc-dest-card-s:hover .oc-dest-card-s__explore{color:#D9B230;}

/* World Map Callout — 2-col layout: LEFT=map visual, RIGHT=text (matches Stitch) */
.oc-dest-map-section{margin-top:80px;background:rgba(255,255,255,0.05);border-top:1px solid rgba(255,255,255,0.1);border-bottom:1px solid rgba(255,255,255,0.1);padding:80px 0;}
.oc-dest-map-inner{display:grid;grid-template-columns:1fr 1fr;gap:64px;align-items:center;}
.oc-dest-map-visual{position:relative;border-radius:16px;overflow:hidden;aspect-ratio:16/10;background:#0a0f1a;border:1px solid rgba(255,255,255,0.1);}
.oc-dest-map-visual__glow{position:absolute;inset:0;background:radial-gradient(circle at center,rgba(217,178,48,.3) 0%,transparent 70%);pointer-events:none;}
.oc-dest-map-visual__icon{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;opacity:.1;}
.oc-dest-map-visual__icon svg{width:160px;height:160px;}
.oc-dest-map-ui{position:absolute;inset:0;padding:24px;display:flex;flex-direction:column;justify-content:space-between;}
.oc-dest-map-zoom{display:flex;justify-content:flex-end;gap:8px;}
.oc-dest-map-zoom button{width:40px;height:40px;background:rgba(255,255,255,0.1);backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,0.15);border-radius:8px;color:#fff;font-size:18px;font-weight:300;cursor:pointer;display:flex;align-items:center;justify-content:center;}
.oc-dest-map-live{display:inline-flex;align-items:flex-start;gap:12px;background:rgba(255,255,255,0.1);backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,0.1);border-radius:12px;padding:14px 18px;}
.oc-dest-map-live__dot{width:8px;height:8px;border-radius:50%;background:#22c55e;margin-top:4px;flex-shrink:0;animation:pulse-dot 2s infinite;}
@keyframes pulse-dot{0%,100%{opacity:1;}50%{opacity:.4;}}
.oc-dest-map-live__label{font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#fff;margin-bottom:4px;}
.oc-dest-map-live__vessel{font-size:13px;font-style:italic;color:#fff;font-weight:600;}
.oc-dest-map-live__loc{font-size:11px;color:rgba(148,163,184,1);}

.oc-dest-map-text h3{font-family:var(--font-heading);font-size:2rem;color:#f8fafc;margin:0 0 16px;}
.oc-dest-map-text p{font-size:1rem;color:rgba(148,163,184,1);line-height:1.7;margin:0 0 32px;}
.oc-dest-map-bullets{list-style:none;padding:0;margin:0 0 40px;display:flex;flex-direction:column;gap:16px;}
.oc-dest-map-bullets li{display:flex;align-items:flex-start;gap:12px;font-size:.9375rem;color:rgba(226,232,240,1);}
.oc-dest-map-bullets li::before{content:'';width:20px;height:20px;flex-shrink:0;background:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none'%3E%3Cpath d='M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' stroke='%23D9B230' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") center/contain no-repeat;margin-top:2px;}
.oc-dest-map-cta{font-size:14px;font-weight:700;color:#D9B230;border-bottom:2px solid #D9B230;padding-bottom:2px;text-decoration:none;display:inline-block;transition:color .2s;}
.oc-dest-map-cta:hover{color:#fff;border-color:#fff;}

@media(max-width:1024px){.oc-dest-grid-static{grid-template-columns:repeat(2,1fr);}.oc-dest-map-inner{grid-template-columns:1fr;}}
@media(max-width:640px){.oc-dest-grid-static{grid-template-columns:1fr;}.oc-dest-map-section{padding:48px 0;}}
</style>

<!-- Region filter pills -->
<div class="oc-dest-filters">
  <a href="/destinations/" class="oc-dest-filter-pill active">All Regions</a>
  <a href="/destinations/?region=europe" class="oc-dest-filter-pill">Europe</a>
  <a href="/destinations/?region=americas" class="oc-dest-filter-pill">Americas</a>
  <a href="/destinations/?region=asia-pacific" class="oc-dest-filter-pill">Asia Pacific</a>
</div>

<!-- 3 × 2 portrait card grid (Stitch: content below image, NOT overlay) -->
<div class="oc-dest-grid-static">

  <a href="/fleet/?region=mediterranean" class="oc-dest-card-s">
    <div class="oc-dest-card-s__img">
      <span class="oc-dest-card-s__badge">Popular</span>
      <img src="{MED}" alt="Mediterranean" loading="lazy">
    </div>
    <div class="oc-dest-card-s__body">
      <h3>The Mediterranean</h3>
      <p>From the glamorous French Riviera to the ancient charm of the Amalfi Coast and Greek Isles.</p>
      <div class="oc-dest-card-s__footer">
        <span class="oc-dest-card-s__count">42 Vessels Available</span>
        <span class="oc-dest-card-s__explore">Explore <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span>
      </div>
    </div>
  </a>

  <a href="/fleet/?region=caribbean" class="oc-dest-card-s">
    <div class="oc-dest-card-s__img">
      <img src="{CARIB}" alt="Caribbean &amp; Bahamas" loading="lazy">
    </div>
    <div class="oc-dest-card-s__body">
      <h3>Caribbean &amp; Bahamas</h3>
      <p>Unwind in the Exumas, sail the British Virgin Islands, or explore the lush Grenadines.</p>
      <div class="oc-dest-card-s__footer">
        <span class="oc-dest-card-s__count">28 Vessels Available</span>
        <span class="oc-dest-card-s__explore">Explore <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span>
      </div>
    </div>
  </a>

  <a href="/fleet/?region=pacific" class="oc-dest-card-s">
    <div class="oc-dest-card-s__img">
      <img src="{PACIFIC}" alt="South Pacific" loading="lazy">
    </div>
    <div class="oc-dest-card-s__body">
      <h3>South Pacific</h3>
      <p>Discover the untouched beauty of French Polynesia, Fiji, and the Whitsunday Islands.</p>
      <div class="oc-dest-card-s__footer">
        <span class="oc-dest-card-s__count">15 Vessels Available</span>
        <span class="oc-dest-card-s__explore">Explore <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span>
      </div>
    </div>
  </a>

  <a href="/fleet/?region=indian-ocean" class="oc-dest-card-s">
    <div class="oc-dest-card-s__img">
      <img src="{INDIAN}" alt="Indian Ocean" loading="lazy">
    </div>
    <div class="oc-dest-card-s__body">
      <h3>Indian Ocean</h3>
      <p>Paradise redefined in the Maldives, the granitic beauty of Seychelles, and Mauritius.</p>
      <div class="oc-dest-card-s__footer">
        <span class="oc-dest-card-s__count">12 Vessels Available</span>
        <span class="oc-dest-card-s__explore">Explore <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span>
      </div>
    </div>
  </a>

  <a href="/fleet/?region=southeast-asia" class="oc-dest-card-s">
    <div class="oc-dest-card-s__img">
      <img src="{INDIAN}" alt="South East Asia" loading="lazy">
    </div>
    <div class="oc-dest-card-s__body">
      <h3>South East Asia</h3>
      <p>The limestone karsts of Phuket, Indonesia's Raja Ampat, and Palawan's hidden lagoons.</p>
      <div class="oc-dest-card-s__footer">
        <span class="oc-dest-card-s__count">19 Vessels Available</span>
        <span class="oc-dest-card-s__explore">Explore <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span>
      </div>
    </div>
  </a>

  <a href="/fleet/?region=northern-europe" class="oc-dest-card-s">
    <div class="oc-dest-card-s__img">
      <img src="{MED}" alt="Northern Europe" loading="lazy">
    </div>
    <div class="oc-dest-card-s__body">
      <h3>Northern Europe</h3>
      <p>Venture into the majestic Norwegian Fjords or the serene Baltic Sea for an Arctic adventure.</p>
      <div class="oc-dest-card-s__footer">
        <span class="oc-dest-card-s__count">8 Vessels Available</span>
        <span class="oc-dest-card-s__explore">Explore <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span>
      </div>
    </div>
  </a>

</div>

<!-- World Map Callout — 2-col: LEFT=map visual, RIGHT=text+bullets (matches Stitch) -->
<div class="oc-dest-map-section">
  <div class="oc-dest-map-inner">

    <!-- Left: interactive map visual -->
    <div class="oc-dest-map-visual">
      <div class="oc-dest-map-visual__glow"></div>
      <div class="oc-dest-map-visual__icon">
        <svg viewBox="0 0 160 160" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="80" cy="80" r="72" stroke="#D9B230" stroke-width="2"/>
          <ellipse cx="80" cy="80" rx="36" ry="72" stroke="#D9B230" stroke-width="1.5"/>
          <line x1="8" y1="80" x2="152" y2="80" stroke="#D9B230" stroke-width="1.5"/>
          <line x1="80" y1="8" x2="80" y2="152" stroke="#D9B230" stroke-width="1.5"/>
          <ellipse cx="80" cy="80" rx="72" ry="28" stroke="#D9B230" stroke-width="1.5"/>
        </svg>
      </div>
      <div class="oc-dest-map-ui">
        <div class="oc-dest-map-zoom">
          <button>+</button>
          <button>−</button>
        </div>
        <div class="oc-dest-map-live">
          <div class="oc-dest-map-live__dot"></div>
          <div>
            <div class="oc-dest-map-live__label">Live Location</div>
            <div class="oc-dest-map-live__vessel">Motor Yacht 'Aurelia'</div>
            <div class="oc-dest-map-live__loc">Off the coast of Mykonos</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Right: text content -->
    <div class="oc-dest-map-text">
      <h3>Explore by Nautical Chart</h3>
      <p>Our interactive voyage map allows you to track current fleet positions, view seasonal weather patterns, and discover bespoke routes recommended by our veteran captains.</p>
      <ul class="oc-dest-map-bullets">
        <li>Seasonal availability heatmaps</li>
        <li>Real-time port congestion data</li>
        <li>Exclusive hidden anchorage locations</li>
      </ul>
      <a href="/contact/" class="oc-dest-map-cta">Open Full Navigation Console</a>
    </div>

  </div>
</div>
HTML;

// Substitute real URLs into the destination cards HTML
$destinations_html = str_replace(
    [ '{MED}',    '{CARIB}',    '{INDIAN}',    '{PACIFIC}' ],
    [ $med_img,   $carib_img,   $indian_img,   $pacific_img ],
    $destinations_html
);

// ── FLEET PAGE (ID 81) ────────────────────────────────────────────────────────
$fleet_json = [
    // 1. Banner hero
    oc4_full_container( 'fl-hero', [
        oc4_hero_widget( 'fl-hero-w', 'Our Fleet', 'Discover Your Perfect Vessel', $fleet_img ),
    ] ),

    // 2. Type-filter bar
    oc4_full_container( 'fl-filter', [
        [
            'id'         => 'fl-filter-w',
            'elType'     => 'widget',
            'widgetType' => 'html',
            'settings'   => [ 'html' => $filter_html ],
            'elements'   => [],
        ],
    ] ),

    // 3. BBC Boat Grid
    oc4_boxed_container( 'fl-grid', [
        [
            'id'         => 'fl-grid-w',
            'elType'     => 'widget',
            'widgetType' => 'bbc_boat_grid',
            'settings'   => [
                'section_title'    => '',
                'boats_per_page'   => 12,
                'grid_columns'     => '3',
                'show_boat_price'  => 'yes',
                'show_quick_specs' => 'yes',
                'order_by'         => 'date',
                'sort_order'       => 'DESC',
            ],
            'elements' => [],
        ],
    ], [
        'background_background' => 'classic',
        'background_color'      => '#0d1520',
    ] ),
];

oc4_set_elementor( 81, $fleet_json, 'Fleet page' );

// ── DESTINATIONS PAGE (ID 79) ─────────────────────────────────────────────────
$destinations_json = [
    // 1. Banner hero
    oc4_full_container( 'dt-hero', [
        oc4_hero_widget( 'dt-hero-w', 'Destinations', 'Explore the World\'s Finest Waters', $dest_img ),
    ] ),

    // 2. Destination cards (4-column static HTML grid)
    oc4_boxed_container( 'dt-cards', [
        [
            'id'         => 'dt-cards-w',
            'elType'     => 'widget',
            'widgetType' => 'html',
            'settings'   => [ 'html' => $destinations_html ],
            'elements'   => [],
        ],
    ], [
        'background_background' => 'classic',
        'background_color'      => '#0d1520',
        'padding'               => [ 'unit' => 'px', 'top' => '72', 'right' => '0', 'bottom' => '80', 'left' => '0', 'isLinked' => false ],
    ] ),

    // 3. CTA Strip
    oc4_full_container( 'dt-cta', [
        [
            'id'         => 'dt-cta-w',
            'elType'     => 'widget',
            'widgetType' => 'oc-cta-strip',
            'settings'   => [
                'heading'         => 'Ready to Set Sail?',
                'subtext'         => 'Your bespoke charter experience awaits. Let\'s plan your perfect voyage.',
                'primary_label'   => 'Browse the Fleet',
                'primary_url'     => [ 'url' => '/fleet/' ],
                'secondary_label' => 'Contact Us',
            ],
            'elements' => [],
        ],
    ] ),
];

oc4_set_elementor( 79, $destinations_json, 'Destinations page' );

// ── Clear Elementor file cache ────────────────────────────────────────────────
if ( class_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance->files_manager ) ) {
    \Elementor\Plugin::$instance->files_manager->clear_cache();
    echo "✓ Elementor cache cleared.\n";
}

echo "\nSetup 4 complete.\n";
