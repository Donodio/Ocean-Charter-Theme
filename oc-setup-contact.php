<?php
/**
 * OC Setup — Contact Page (ID 57)
 *
 * Builds the Elementor layout for the Contact page, matching the Stitch
 * "Ocean Charter - Contact" design:
 *   1. Banner hero
 *   2. oc-contact-section widget (form LEFT 7/12 + info/map RIGHT 5/12)
 *   3. CTA strip
 *
 * Run via WP-CLI:
 *   wp eval-file wp-content/themes/ocean-charter/oc-setup-contact.php
 * Or via /tmp/run_oc_contact.sh
 */

if ( ! defined( 'ABSPATH' ) ) {
    $wp_load = dirname( __DIR__, 3 ) . '/wp-load.php';
    if ( file_exists( $wp_load ) ) {
        require $wp_load;
    } else {
        echo "wp-load.php not found.\n"; exit( 1 );
    }
}

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
$hero_img = defined( 'OC_IMG_HERO_CONTACT' ) ? OC_IMG_HERO_CONTACT
          : ( defined( 'OC_IMG_HERO_HOME' )  ? OC_IMG_HERO_HOME : '' );

// ── Build Contact JSON ────────────────────────────────────────────────────────
$contact_json = [

    // 1. Banner hero
    oc4_full_container( 'ct-hero', [
        oc4_hero_widget( 'ct-hero-w', 'Get In Touch', 'Contact Our Concierge', $hero_img ),
    ] ),

    // 2. Contact section (form + info + map)
    oc4_full_container( 'ct-body', [
        [
            'id'         => 'ct-contact-w',
            'elType'     => 'widget',
            'widgetType' => 'oc-contact-section',
            'settings'   => [
                'form_heading'    => 'Design Your Voyage',
                'form_subtitle'   => 'Tell us about your dream charter and our concierge team will craft a bespoke itinerary within 24 hours.',
                'info_heading'    => 'Reach Us Directly',
                'address'        => 'Marina Bay, Luxury Yacht Terminal<br>Pier 7, Suite 201',
                'phone'          => '+1 (800) 555-YACHT',
                'email'          => 'concierge@oceancharter.com',
                'whatsapp_number' => '+18005559224',
                'map_label'      => 'Marina Bay Yacht Terminal',
            ],
            'elements' => [],
        ],
    ], [
        'background_background' => 'classic',
        'background_color'      => '#0a0f1a',
        'padding'               => [ 'unit' => 'px', 'top' => '80', 'right' => '0', 'bottom' => '100', 'left' => '0', 'isLinked' => false ],
    ] ),

    // 3. CTA strip
    oc4_full_container( 'ct-cta', [
        [
            'id'         => 'ct-cta-w',
            'elType'     => 'widget',
            'widgetType' => 'oc-cta-strip',
            'settings'   => [
                'heading'         => 'Prefer to Talk Directly?',
                'subtext'         => 'Our concierge team is available 24/7 to help you plan every detail of your maritime escape.',
                'primary_label'   => 'WhatsApp Us Now',
                'primary_url'     => [ 'url' => 'https://wa.me/18005559224' ],
                'secondary_label' => 'Call Us',
            ],
            'elements' => [],
        ],
    ] ),

];

oc4_set_elementor( 57, $contact_json, 'Contact page' );

// ── Clear Elementor file cache ────────────────────────────────────────────────
if ( class_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance->files_manager ) ) {
    \Elementor\Plugin::$instance->files_manager->clear_cache();
    echo "✓ Elementor cache cleared.\n";
}

echo "\nContact setup complete.\n";
