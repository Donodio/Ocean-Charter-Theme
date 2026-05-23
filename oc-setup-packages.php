<?php
/**
 * OC Setup — Packages Page (ID 84)
 *
 * Builds the Elementor layout for the Packages page, matching the Stitch
 * "Ocean Charter - Packages" design:
 *   1. Banner hero
 *   2. Category filter pills + 3-column package cards grid
 *   3. Bespoke section (text LEFT + image/badge RIGHT)
 *   4. CTA strip
 *
 * Run via /tmp/run_oc_packages.sh
 */

if ( ! defined( 'ABSPATH' ) ) {
    $wp_load = dirname( __DIR__, 3 ) . '/wp-load.php';
    if ( file_exists( $wp_load ) ) {
        require $wp_load;
    } else {
        echo "wp-load.php not found.\n"; exit( 1 );
    }
}

// ── Reuse helpers ─────────────────────────────────────────────────────────────
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

// ── Image constants ───────────────────────────────────────────────────────────
$hero_img      = defined( 'OC_IMG_HERO_PACKAGES' )  ? OC_IMG_HERO_PACKAGES  : ( defined( 'OC_IMG_HERO_HOME' ) ? OC_IMG_HERO_HOME : '' );
$sunset_img    = defined( 'OC_IMG_PKG_SUNSET' )     ? OC_IMG_PKG_SUNSET     : $hero_img;
$corporate_img = defined( 'OC_IMG_PKG_CORPORATE' )  ? OC_IMG_PKG_CORPORATE  : $hero_img;
$birthday_img  = defined( 'OC_IMG_PKG_BIRTHDAY' )   ? OC_IMG_PKG_BIRTHDAY   : $hero_img;
$bespoke_img   = defined( 'OC_IMG_PKG_BESPOKE' )    ? OC_IMG_PKG_BESPOKE    : $hero_img;

// ── Category filter pills HTML ────────────────────────────────────────────────
$filter_html = <<<'HTML'
<style>
.pk-filter-pills{display:flex;flex-wrap:wrap;gap:12px;justify-content:center;margin-bottom:60px;}
.pk-filter-pills__btn{padding:10px 24px;border-radius:9999px;font-size:0.875rem;font-weight:600;letter-spacing:0.05em;cursor:pointer;border:1px solid rgba(255,255,255,0.12);background:rgba(255,255,255,0.05);color:rgba(148,163,184,1);transition:all .25s ease;}
.pk-filter-pills__btn:hover,.pk-filter-pills__btn--active{background:#d9b230;border-color:#d9b230;color:#0a0f1a;}
</style>
<div class="pk-filter-pills" role="group" aria-label="Filter packages by category">
  <button class="pk-filter-pills__btn pk-filter-pills__btn--active" data-filter="all">All Packages</button>
  <button class="pk-filter-pills__btn" data-filter="day-charters">Day Charters</button>
  <button class="pk-filter-pills__btn" data-filter="celebrations">Celebrations</button>
  <button class="pk-filter-pills__btn" data-filter="corporate">Corporate</button>
</div>
<script>
(function(){
  var btns = document.querySelectorAll('.pk-filter-pills__btn');
  btns.forEach(function(btn){
    btn.addEventListener('click',function(){
      btns.forEach(function(b){b.classList.remove('pk-filter-pills__btn--active');});
      btn.classList.add('pk-filter-pills__btn--active');
    });
  });
})();
</script>
HTML;

// ── 3-col package card container helper ──────────────────────────────────────
function oc_pkg_card_widget( string $wid, string $tag, string $title, string $price, string $duration, array $inclusions, string $img_url, string $cta_url = '/contact/' ): array {
    return [
        'id'         => $wid,
        'elType'     => 'widget',
        'widgetType' => 'oc-package-card',
        'settings'   => [
            'image'      => [ 'url' => $img_url, 'id' => 0 ],
            'tag'        => $tag,
            'title'      => $title,
            'price'      => $price,
            'duration'   => $duration,
            'inclusions' => array_map( fn( $t ) => [ 'text' => $t, '__dynamic__' => [] ], $inclusions ),
            'cta_url'    => [ 'url' => $cta_url, 'is_external' => '' ],
        ],
        'elements' => [],
    ];
}

function oc_three_col_container( string $cid, array $children ): array {
    return [
        'id' => $cid, 'elType' => 'container',
        'settings' => [
            'content_width'  => 'full',
            'flex_direction' => 'row',
            'align_items'    => 'stretch',
            'gap'            => [ 'unit' => 'px', 'size' => 32, 'column' => '32', 'row' => '32' ],
            'padding'        => [ 'unit' => 'px', 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'isLinked' => false ],
        ],
        'elements' => array_map( function( $child, $i ) use ( $cid ) {
            return [
                'id'     => $cid . '-c' . $i,
                'elType' => 'container',
                'settings' => [
                    'content_width'  => 'full',
                    'flex_direction' => 'column',
                    'width'          => [ 'size' => 33.333, 'unit' => '%' ],
                    'padding'        => [ 'unit' => 'px', 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'isLinked' => false ],
                ],
                'elements' => [ $child ],
            ];
        }, $children, array_keys( $children ) ),
    ];
}

// ── Build Packages JSON ───────────────────────────────────────────────────────
$packages_json = [

    // 1. Banner hero
    oc4_full_container( 'pk-hero', [
        oc4_hero_widget( 'pk-hero-w', 'Charter Packages', 'Exclusive Charter Packages', $hero_img ),
    ] ),

    // 2. Filter pills + 3 cards grid
    oc4_boxed_container( 'pk-cards', [

        // Category filter pills
        [
            'id'         => 'pk-filter-w',
            'elType'     => 'widget',
            'widgetType' => 'html',
            'settings'   => [ 'html' => $filter_html ],
            'elements'   => [],
        ],

        // 3-column card grid
        oc_three_col_container( 'pk-grid', [
            oc_pkg_card_widget(
                'pk-c1', 'Popular',
                'Sunset Cruise',
                'From $1,200', '3 Hours',
                [ '3-Hour Coastal Cruise', 'Premium Champagne Selection', 'Gourmet Hors d\'oeuvres', 'Professional Captain & Crew' ],
                $sunset_img
            ),
            oc_pkg_card_widget(
                'pk-c2', 'Signature',
                'Corporate Events',
                'From $4,800', 'Full Day',
                [ 'Full-Day Charter (8 Hours)', 'State-of-the-Art AV Facilities', 'Tailored Catering Menu', 'Up to 30 Guests', 'Dedicated Event Manager' ],
                $corporate_img
            ),
            oc_pkg_card_widget(
                'pk-c3', 'Celebration',
                'Birthday Parties',
                'From $3,200', '5 Hours',
                [ '5-Hour Charter', 'Professional DJ', 'Custom Party Décor', 'Bespoke Beverage Packages', 'Photographer Available' ],
                $birthday_img
            ),
        ] ),

    ], [
        'background_background' => 'classic',
        'background_color'      => '#0a0f1a',
        'padding'               => [ 'unit' => 'px', 'top' => '80', 'right' => '0', 'bottom' => '100', 'left' => '0', 'isLinked' => false ],
    ] ),

    // 3. Bespoke section
    oc4_full_container( 'pk-bespoke', [
        [
            'id'         => 'pk-bespoke-w',
            'elType'     => 'widget',
            'widgetType' => 'oc-bespoke-section',
            'settings'   => [
                'eyebrow'    => 'Fully Custom',
                'heading'    => 'Bespoke Voyages',
                'body_text'  => "None of our packages match your vision? Perfect. Our most discerning clients work directly with our charter architects to design a voyage that exists nowhere else in the world.\n\nTell us your dream. We'll build the journey around it.",
                'cta_label'  => 'Speak to a Charter Architect',
                'cta_url'    => [ 'url' => '/contact/', 'is_external' => '' ],
                'image'      => [ 'url' => $bespoke_img, 'id' => 0 ],
                'badge_text' => '100% Satisfaction',
            ],
            'elements' => [],
        ],
    ] ),

    // 4. CTA strip
    oc4_full_container( 'pk-cta', [
        [
            'id'         => 'pk-cta-w',
            'elType'     => 'widget',
            'widgetType' => 'oc-cta-strip',
            'settings'   => [
                'heading'         => 'Ready to Book Your Charter?',
                'subtext'         => 'Select a package above or let us craft something entirely unique for you.',
                'primary_label'   => 'View All Packages',
                'primary_url'     => [ 'url' => '/contact/' ],
                'secondary_label' => 'Custom Enquiry',
            ],
            'elements' => [],
        ],
    ] ),

];

oc4_set_elementor( 84, $packages_json, 'Packages page' );

// ── Clear Elementor file cache ────────────────────────────────────────────────
if ( class_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance->files_manager ) ) {
    \Elementor\Plugin::$instance->files_manager->clear_cache();
    echo "✓ Elementor cache cleared.\n";
}

echo "\nPackages setup complete.\n";
