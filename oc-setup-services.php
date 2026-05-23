<?php
/**
 * OC Setup — Services Page (ID 78)
 *
 * Builds the Elementor layout for the Services page, matching the Stitch
 * "Ocean Charter - Services" design:
 *   1. Banner hero
 *   2. "Tailored To Your Tastes" intro (2-col: text + image)
 *   3. 2×2 staggered service card grid (oc-service-card widgets)
 *   4. CTA strip
 *
 * Run via WP-CLI:
 *   wp eval-file wp-content/themes/ocean-charter/oc-setup-services.php
 * Or via /tmp/run_oc_services.sh
 */

if ( ! defined( 'ABSPATH' ) ) {
    $wp_load = dirname( __DIR__, 3 ) . '/wp-load.php';
    if ( file_exists( $wp_load ) ) {
        require $wp_load;
    } else {
        echo "wp-load.php not found.\n"; exit( 1 );
    }
}

// ── Image constants ───────────────────────────────────────────────────────────
$hero_img      = defined( 'OC_IMG_HERO_SERVICES' )    ? OC_IMG_HERO_SERVICES    : ( defined( 'OC_IMG_HERO_HOME' ) ? OC_IMG_HERO_HOME : '' );
$chef_img      = defined( 'OC_IMG_SVC_CHEF' )         ? OC_IMG_SVC_CHEF         : $hero_img;
$watertoys_img = defined( 'OC_IMG_SVC_WATERTOYS' )    ? OC_IMG_SVC_WATERTOYS    : $hero_img;
$events_img    = defined( 'OC_IMG_SVC_EVENTS' )       ? OC_IMG_SVC_EVENTS       : $hero_img;
$concierge_img = defined( 'OC_IMG_SVC_CONCIERGE' )    ? OC_IMG_SVC_CONCIERGE    : $hero_img;

// ── Reuse helpers from oc-setup4.php if already loaded, else define ───────────
if ( ! function_exists( 'oc4_set_elementor' ) ) :
function oc4_set_elementor( $post_id, array $data, string $label ) {
    update_post_meta( $post_id, '_elementor_data', wp_slash( json_encode( $data ) ) );
    update_post_meta( $post_id, '_elementor_edit_mode', 'builder' );
    wp_update_post( [ 'ID' => $post_id, 'post_content' => '' ] );
    echo "✓ {$label} (ID {$post_id}) updated.\n";
}
function oc4_full_container( string $cid, array $elements, array $extra = [] ): array {
    return [
        'id' => $cid, 'elType' => 'container',
        'settings' => array_merge( [ 'content_width' => 'full', 'padding' => [ 'unit' => 'px', 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'isLinked' => false ] ], $extra ),
        'elements' => $elements,
    ];
}
function oc4_boxed_container( string $cid, array $elements, array $extra = [] ): array {
    return [
        'id' => $cid, 'elType' => 'container',
        'settings' => array_merge( [ 'content_width' => 'boxed', 'padding' => [ 'unit' => 'px', 'top' => '60', 'right' => '0', 'bottom' => '80', 'left' => '0', 'isLinked' => false ] ], $extra ),
        'elements' => $elements,
    ];
}
function oc4_hero_widget( string $wid, string $eyebrow, string $heading, string $bg_url ): array {
    return [
        'id' => $wid, 'elType' => 'widget', 'widgetType' => 'oc-hero',
        'settings' => [
            'hero_style' => 'banner', 'eyebrow' => $eyebrow, 'heading' => $heading,
            'subheading' => '', 'cta_label' => '', 'secondary_label' => '',
            'show_search' => 'no', 'bg_image' => [ 'url' => $bg_url, 'id' => 0 ],
            'overlay_opacity' => [ 'size' => 0.55, 'unit' => 'px' ],
        ],
        'elements' => [],
    ];
}
endif;

// ── Local helpers ─────────────────────────────────────────────────────────────

/**
 * Build an oc-service-card widget node.
 */
function oc_svc_card_widget( string $wid, string $eyebrow, string $icon, string $title, string $desc, array $features, string $img_url ): array {
    return [
        'id'         => $wid,
        'elType'     => 'widget',
        'widgetType' => 'oc-service-card',
        'settings'   => [
            'image'       => [ 'url' => $img_url, 'id' => 0 ],
            'eyebrow'     => $eyebrow,
            'badge_icon'  => $icon,
            'title'       => $title,
            'description' => $desc,
            'features'    => array_map( fn( $f ) => [ 'label' => $f, '__dynamic__' => [] ], $features ),
            'link'        => [ 'url' => '/contact/', 'is_external' => '' ],
        ],
        'elements' => [],
    ];
}

/**
 * Two-column flex-row container.
 * $pad_right_top: top padding on the right column for the stagger offset.
 */
function oc_two_col_container( string $cid, array $col_l, array $col_r, int $pad_right_top = 80 ): array {
    return [
        'id'     => $cid,
        'elType' => 'container',
        'settings' => [
            'content_width'  => 'full',
            'flex_direction' => 'row',
            'align_items'    => 'flex-start',
            'gap'            => [ 'unit' => 'px', 'size' => 32, 'column' => '32', 'row' => '32' ],
            'padding'        => [ 'unit' => 'px', 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'isLinked' => false ],
        ],
        'elements' => [
            // Left column
            [
                'id'     => $cid . '-l',
                'elType' => 'container',
                'settings' => [
                    'content_width'  => 'full',
                    'flex_direction' => 'column',
                    'width'          => [ 'size' => 50, 'unit' => '%' ],
                    'padding'        => [ 'unit' => 'px', 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'isLinked' => false ],
                ],
                'elements' => $col_l,
            ],
            // Right column (staggered down)
            [
                'id'     => $cid . '-r',
                'elType' => 'container',
                'settings' => [
                    'content_width'  => 'full',
                    'flex_direction' => 'column',
                    'width'          => [ 'size' => 50, 'unit' => '%' ],
                    'padding'        => [ 'unit' => 'px', 'top' => (string) $pad_right_top, 'right' => '0', 'bottom' => '0', 'left' => '0', 'isLinked' => false ],
                ],
                'elements' => $col_r,
            ],
        ],
    ];
}

// ── Intro section HTML ────────────────────────────────────────────────────────
$intro_html = <<<HTML
<style>
.sv-intro-el{padding:100px clamp(1rem,4vw,2.5rem);}
.sv-intro-el__inner{display:grid;grid-template-columns:1fr 1fr;gap:80px;align-items:center;max-width:1140px;margin:0 auto;}
.sv-intro-el__eyebrow{display:block;font-size:11px;font-weight:700;letter-spacing:.15em;text-transform:uppercase;color:#d9b230;margin-bottom:16px;}
.sv-intro-el__title{font-family:var(--font-heading);font-size:clamp(32px,4vw,48px);color:#f8fafc;margin:0 0 24px;font-weight:400;line-height:1.2;}
.sv-intro-el__title em{font-style:italic;color:#d9b230;}
.sv-intro-el__text p{color:rgba(148,163,184,1);line-height:1.8;margin-bottom:40px;font-size:17px;}
.sv-intro-el__img{position:relative;border-radius:16px;overflow:hidden;aspect-ratio:4/5;}
.sv-intro-el__img img{width:100%;height:100%;object-fit:cover;transition:transform .7s ease;}
.sv-intro-el__img:hover img{transform:scale(1.03);}
@media(max-width:1024px){.sv-intro-el__inner{grid-template-columns:1fr;gap:48px;}}
</style>
<section class="sv-intro-el">
  <div class="sv-intro-el__inner">
    <div class="sv-intro-el__text">
      <span class="sv-intro-el__eyebrow">Our Philosophy</span>
      <h2 class="sv-intro-el__title">Tailored To Your <em>Tastes</em></h2>
      <p>Our team of experts curate every detail of your voyage, ensuring an atmosphere of absolute luxury and effortless relaxation. Whether you desire a private chef conjuring culinary masterpieces, exhilarating water sports, or a serene spa day at sea — we deliver it flawlessly.</p>
      <a href="/contact/" class="btn-primary">Design Your Journey</a>
    </div>
    <div class="sv-intro-el__img">
      <img src="{CHEF_IMG}" alt="Luxury culinary service onboard" loading="lazy">
    </div>
  </div>
</section>
HTML;
$intro_html = str_replace( '{CHEF_IMG}', $chef_img, $intro_html );

// ── Section heading HTML (above the card grid) ────────────────────────────────
$grid_heading_html = <<<'HTML'
<style>
.sv-grid-heading{text-align:center;margin-bottom:60px;}
.sv-grid-heading .oc-hero__eyebrow{display:block;margin-bottom:16px;}
.sv-grid-heading h2{font-family:var(--font-heading);font-size:clamp(28px,4vw,48px);font-weight:400;color:#f8fafc;margin:0;}
.sv-grid-heading h2 em{font-style:italic;color:#d9b230;}
</style>
<div class="sv-grid-heading">
  <span class="oc-hero__eyebrow">Premium Services</span>
  <h2>Everything Your <em>Voyage Deserves</em></h2>
</div>
HTML;

// ── Build Services JSON ───────────────────────────────────────────────────────
$services_json = [

    // 1. Banner hero
    oc4_full_container( 'sv-hero', [
        oc4_hero_widget( 'sv-hero-w', 'Premium Services', 'Bespoke Maritime Experiences', $hero_img ),
    ] ),

    // 2. Intro section (full-width, dark bg)
    oc4_full_container( 'sv-intro', [
        [
            'id'         => 'sv-intro-w',
            'elType'     => 'widget',
            'widgetType' => 'html',
            'settings'   => [ 'html' => $intro_html ],
            'elements'   => [],
        ],
    ], [
        'background_background' => 'classic',
        'background_color'      => '#0a0f1a',
    ] ),

    // 3. Service cards grid (boxed, dark bg)
    oc4_boxed_container( 'sv-cards', [

        // Section heading
        [
            'id'         => 'sv-grid-h',
            'elType'     => 'widget',
            'widgetType' => 'html',
            'settings'   => [ 'html' => $grid_heading_html ],
            'elements'   => [],
        ],

        // Staggered 2×2 grid
        oc_two_col_container(
            'sv-grid',
            // Left column: cards 1 (Chef) + 3 (Events)
            [
                oc_svc_card_widget(
                    'sv-c1', 'Culinary Excellence', 'chef',
                    'Private Michelin Chefs',
                    'Savor gourmet menus tailored to your palate by world-renowned chefs. From intimate candlelit dinners to grand seafood galas under the stars.',
                    [ 'Custom Menus', 'Wine Pairing', 'Local Sourcing' ],
                    $chef_img
                ),
                oc_svc_card_widget(
                    'sv-c3', 'Events at Sea', 'events',
                    'Event Curation',
                    'Unforgettable maritime celebrations designed by our elite planning team. From corporate retreats to bespoke weddings and private galas.',
                    [ 'Full Production', 'Live Music', 'Floral Design' ],
                    $events_img
                ),
            ],
            // Right column: cards 2 (Water Toys) + 4 (Concierge) — offset 80px down
            [
                oc_svc_card_widget(
                    'sv-c2', 'Aquatic Adventures', 'water',
                    'Luxury Water Toys',
                    'High-speed aquatic adventures with our premium fleet of Yamaha & Sea-Doo jet skis, Seabobs, and luxury inflatable beach clubs.',
                    [ 'Latest Models', 'Safety Training', 'Tender Support' ],
                    $watertoys_img
                ),
                oc_svc_card_widget(
                    'sv-c4', 'Always Available', 'concierge',
                    '24/7 Concierge',
                    'Your personal assistant on the water. Seamless logistics, local expertise, and VIP access to the world\'s most exclusive shore-side clubs.',
                    [ 'VIP Access', 'Port Logistics', 'Travel Transfers' ],
                    $concierge_img
                ),
            ],
            80 // stagger offset in px
        ),

    ], [
        'background_background' => 'classic',
        'background_color'      => '#0a0f1a',
        'padding'               => [ 'unit' => 'px', 'top' => '80', 'right' => '0', 'bottom' => '100', 'left' => '0', 'isLinked' => false ],
    ] ),

    // 4. CTA strip
    oc4_full_container( 'sv-cta', [
        [
            'id'         => 'sv-cta-w',
            'elType'     => 'widget',
            'widgetType' => 'oc-cta-strip',
            'settings'   => [
                'heading'         => 'Ready to Design Your Journey?',
                'subtext'         => 'Contact our bespoke services team today to start tailoring your next maritime adventure.',
                'primary_label'   => 'Contact Our Concierge',
                'primary_url'     => [ 'url' => '/contact/' ],
                'secondary_label' => 'WhatsApp Us',
            ],
            'elements' => [],
        ],
    ] ),

];

oc4_set_elementor( 78, $services_json, 'Services page' );

// ── Clear Elementor file cache ────────────────────────────────────────────────
if ( class_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance->files_manager ) ) {
    \Elementor\Plugin::$instance->files_manager->clear_cache();
    echo "✓ Elementor cache cleared.\n";
}

echo "\nServices setup complete.\n";
