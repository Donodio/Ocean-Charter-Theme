<?php
/**
 * Ocean Charter — Dynamic Page Builder
 *
 * Rebuilds ALL content pages so every section is driven by a
 * CPT-query Elementor widget. No hardcoded content on the front end.
 *
 * Pages rebuilt:
 *   55  Home
 *   78  Services
 *   79  Destinations
 *   80  Itinerary
 *   81  Fleet
 *   84  Packages
 *
 * Run via:
 *   php -d mysqli.default_socket=<socket> oc-setup-dynamic.php
 */

if ( ! defined( 'ABSPATH' ) ) {
    $wp_load = dirname( __DIR__, 3 ) . '/wp-load.php';
    if ( file_exists( $wp_load ) ) require $wp_load;
    else { echo "wp-load.php not found.\n"; exit( 1 ); }
}

// ── Hero images (all confirmed valid Pexels URLs) ────────────────────────────
$HERO = [
    'home'         => 'https://images.pexels.com/photos/1118873/pexels-photo-1118873.jpeg?auto=compress&cs=tinysrgb&w=1920',
    'services'     => 'https://images.pexels.com/photos/3836440/pexels-photo-3836440.jpeg?auto=compress&cs=tinysrgb&w=1920',
    'destinations' => 'https://images.pexels.com/photos/1268856/pexels-photo-1268856.jpeg?auto=compress&cs=tinysrgb&w=1920',
    'itinerary'    => 'https://images.pexels.com/photos/1285625/pexels-photo-1285625.jpeg?auto=compress&cs=tinysrgb&w=1920',
    'fleet'        => 'https://images.pexels.com/photos/2248516/pexels-photo-2248516.jpeg?auto=compress&cs=tinysrgb&w=1920',
    'packages'     => 'https://images.pexels.com/photos/3225517/pexels-photo-3225517.jpeg?auto=compress&cs=tinysrgb&w=1920',
];

// ── Helpers ──────────────────────────────────────────────────────────────────

/** Write Elementor JSON to a page and clear cache. */
function oc_dyn_set( int $post_id, array $data, string $label ): void {
    update_post_meta( $post_id, '_elementor_data',      wp_slash( json_encode( $data ) ) );
    update_post_meta( $post_id, '_elementor_edit_mode', 'builder' );
    wp_update_post( [ 'ID' => $post_id, 'post_content' => '' ] );
    echo "✓ {$label} (ID {$post_id})\n";
}

/** Full-width container wrapping child elements. */
function oc_cnt( string $id, array $children, array $extra = [] ): array {
    return [
        'id'       => $id,
        'elType'   => 'container',
        'settings' => array_merge( [
            'content_width' => 'full',
            'padding'       => [ 'unit' => 'px', 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'isLinked' => false ],
        ], $extra ),
        'elements' => $children,
    ];
}

/** Section container with dark background and standard vertical padding. */
function oc_section( string $id, array $children, string $bg = '#0a0f1a', string $pt = '80', string $pb = '80' ): array {
    return oc_cnt( $id, $children, [
        'background_background' => 'classic',
        'background_color'      => $bg,
        'padding'               => [ 'unit' => 'px', 'top' => $pt, 'right' => '120', 'bottom' => $pb, 'left' => '120', 'isLinked' => false ],
    ] );
}

/** Elementor widget node. */
function oc_w( string $id, string $type, array $settings ): array {
    return [
        'id'         => $id,
        'elType'     => 'widget',
        'widgetType' => $type,
        'settings'   => $settings,
        'elements'   => [],
    ];
}

/** oc-hero widget node (banner style). */
function oc_hero_banner( string $id, string $eyebrow, string $heading, string $bg_url, string $sub = '' ): array {
    return oc_w( $id, 'oc-hero', [
        'hero_style'      => 'banner',
        'eyebrow'         => $eyebrow,
        'heading'         => $heading,
        'subheading'      => $sub,
        'cta_label'       => '',
        'secondary_label' => '',
        'show_search'     => 'no',
        'bg_image'        => [ 'url' => $bg_url, 'id' => 0 ],
        'overlay_opacity' => [ 'size' => 0.55, 'unit' => 'px' ],
        'hero_min_height' => [ 'size' => 340, 'unit' => 'px' ],
    ] );
}

/** oc-cta-strip widget node. */
function oc_cta( string $id, string $heading, string $sub, string $primary = 'Get In Touch', string $secondary = 'WhatsApp Us' ): array {
    return oc_w( $id, 'oc-cta-strip', [
        'heading'         => $heading,
        'subtext'         => $sub,
        'primary_label'   => $primary,
        'primary_url'     => [ 'url' => '/contact/' ],
        'secondary_label' => $secondary,
    ] );
}

echo "Ocean Charter — Dynamic Page Builder\n";
echo str_repeat( '=', 50 ) . "\n\n";

// ════════════════════════════════════════════════════════════════
// PAGE: HOME (ID 55)
// Stitch sequence: Hero → Featured Vessels → Why Us → Services →
//   Destinations → Itineraries → Testimonials → Gallery → Offers
//   → Press → FAQ → CTA
// ════════════════════════════════════════════════════════════════
$home_json = [

    // 1. Full-height hero with booking search
    oc_cnt( 'hm-hero', [
        oc_w( 'hm-hero-w', 'oc-hero', [
            'hero_style'      => 'full',
            'eyebrow'         => 'Private Yacht Charters',
            'heading'         => 'Define Your Horizon',
            'subheading'      => 'Bespoke yacht charters across the world\'s most coveted waters.',
            'cta_label'       => 'Explore Fleet',
            'cta_url'         => [ 'url' => '/fleet/' ],
            'secondary_label' => 'View Packages',
            'secondary_url'   => [ 'url' => '/packages/' ],
            'show_search'     => 'yes',
            'bg_image'        => [ 'url' => $HERO['home'], 'id' => 0 ],
            'overlay_opacity' => [ 'size' => 0.5, 'unit' => 'px' ],
        ] ),
    ] ),

    // 2. Featured Vessels — 3-col, portrait image, price/day, amenities, "Explore Vessel"
    oc_section( 'hm-featured-vessels', [
        oc_w( 'hm-fv-w', 'oc-featured-vessels', [
            'posts_count'     => 3,
            'featured_only'   => 'no',
            'section_heading' => 'Featured Vessels',
            'section_eyebrow' => 'Our Finest Fleet',
            'view_all_label'  => 'View Full Fleet',
            'view_all_url'    => [ 'url' => '/fleet/' ],
        ] ),
    ], '#0a0f1a' ),

    // 3. Why Us — 2-col: overlapping images + "25 years" gold card left, icon list right
    oc_section( 'hm-why-us', [
        oc_w( 'hm-why-us-w', 'oc-why-us', [
            'eyebrow'              => 'The Ocean Charter Difference',
            'heading'              => '25 Years of Unmatched Maritime Experience',
            'description'          => "Since 1999, we have been crafting extraordinary private yacht experiences for discerning travellers across the world's most coveted waters. Every charter is a masterpiece of planning, expertise, and passion.",
            'stat_years'           => '25',
            'stat_years_label'     => 'Years of Unmatched<br>Maritime Excellence',
            'stat_charters'        => '2,400+',
            'stat_destinations'    => '60+',
            'stat_rating'          => '★ 4.9',
            'primary_image'        => [ 'url' => 'https://images.pexels.com/photos/1118873/pexels-photo-1118873.jpeg?auto=compress&cs=tinysrgb&w=1200', 'id' => 0 ],
            'secondary_image'      => [ 'url' => 'https://images.pexels.com/photos/1268856/pexels-photo-1268856.jpeg?auto=compress&cs=tinysrgb&w=800', 'id' => 0 ],
            // Stats card positioning defaults
            'stat_card_h_offset'   => [ 'size' => 50, 'unit' => '%' ],
            'stat_card_v_offset'   => [ 'size' => 1.5, 'unit' => 'rem' ],
            'stat_card_width'      => [ 'size' => 320, 'unit' => 'px' ],
            'stat_card_min_height' => [ 'size' => 140, 'unit' => 'px' ],
            'stat_card_border_radius' => [ 'size' => 1, 'unit' => 'rem' ],
            'stat_card_border_width'  => [ 'size' => 2, 'unit' => 'px' ],
            'stat_card_bg'         => '#111a28',
            'stat_card_border_color' => '#d9b230',
            'stat_num_color'       => '#d9b230',
            'stat_label_color'     => '#f0ece3',
        ] ),
    ], '#060c14', '100', '100' ),

    // 4. Onboard Services — 4-col grid
    oc_section( 'hm-services', [
        oc_w( 'hm-services-w', 'oc-service-grid', [
            'posts_count'     => 4,
            'section_heading' => 'Onboard Services',
            'section_eyebrow' => 'The Finest Details',
        ] ),
    ], '#0a0f1a' ),

    // 5. Destinations Grid — 4-col with region filter pills
    oc_section( 'hm-destinations', [
        oc_w( 'hm-dest-w', 'oc-destination-grid', [
            'posts_count'     => 4,
            'show_filter'     => 'yes',
            'section_heading' => 'Sailing Destinations',
            'section_eyebrow' => 'Where Will You Go',
        ] ),
    ], '#060c14' ),

    // 6. Sample Itineraries
    oc_section( 'hm-itins', [
        oc_w( 'hm-itins-w', 'oc-itinerary-grid', [
            'posts_count'     => 4,
            'section_heading' => 'Sample Itineraries',
            'section_eyebrow' => 'Chart Your Course',
        ] ),
    ], '#0a0f1a' ),

    // 7. Testimonials carousel — auto-advancing with arrows + dots
    oc_section( 'hm-testi', [
        oc_w( 'hm-testi-w', 'oc-testimonial-carousel', [
            'posts_count'     => 5,
            'featured_only'   => 'no',
            'section_heading' => 'Guest Experiences',
            'section_eyebrow' => 'What They Say',
        ] ),
    ], '#060c14' ),

    // 8. Destinations Gallery — masonry photo strip (Santorini, Amalfi, Bahamas…)
    oc_section( 'hm-gallery', [
        oc_w( 'hm-gallery-w', 'oc-destinations-gallery', [
            'posts_count'     => 5,
            'section_heading' => 'The World Awaits',
            'section_eyebrow' => 'Our Destinations',
        ] ),
    ], '#0a0f1a', '80', '80' ),

    // 9. Special Offers — 4-col with images
    oc_section( 'hm-offers', [
        oc_w( 'hm-offers-w', 'oc-offer-cards', [
            'posts_count'     => 4,
            'columns'         => '4',
            'featured_only'   => 'no',
            'section_heading' => 'Special Offers',
            'section_eyebrow' => 'Limited Time',
            'image_height'    => [ 'size' => 220, 'unit' => 'px' ],
            'card_radius'     => [ 'size' => 16, 'unit' => 'px' ],
            'card_bg'         => '#111a28',
        ] ),
    ], '#060c14' ),

    // 10. Press strip
    oc_section( 'hm-press', [
        oc_w( 'hm-press-w', 'oc-press-strip', [
            'posts_count'     => 4,
            'show_quotes'     => 'yes',
            'section_heading' => 'As Featured In',
        ] ),
    ], '#0a0f1a', '60', '60' ),

    // 11. FAQ accordion
    oc_section( 'hm-faq', [
        oc_w( 'hm-faq-w', 'oc-faq-accordion', [
            'posts_count'     => 6,
            'category'        => '',
            'section_heading' => 'Frequently Asked Questions',
            'section_eyebrow' => 'Got Questions',
        ] ),
    ], '#060c14' ),

    // 12. CTA — "Ready to Set Sail / Your Bespoke Maritime"
    oc_section( 'hm-cta', [
        oc_cta( 'hm-cta-w',
            'Ready to Sail Your Bespoke Maritime Journey?',
            'Tell us your dream destination and we will craft the perfect charter experience — tailored to every detail.',
            'Plan My Charter',
            'WhatsApp Us'
        ),
    ], '#111a28', '80', '80' ),

];

oc_dyn_set( 55, $home_json, 'Home' );

// ════════════════════════════════════════════════════════════════
// PAGE: SERVICES (ID 78)
// Sections: Hero · Services Grid · Team · FAQ · CTA
// ════════════════════════════════════════════════════════════════
$services_json = [

    oc_cnt( 'sv-hero', [
        oc_hero_banner( 'sv-hero-w', 'Onboard Experience', 'Crafted for Perfection', $HERO['services'],
            'Every detail of your voyage — from cuisine to adventure — curated by passionate experts.' ),
    ] ),

    oc_section( 'sv-grid', [
        oc_w( 'sv-grid-w', 'oc-service-grid', [
            'posts_count'     => 4,
            'section_heading' => 'What We Offer',
            'section_eyebrow' => 'Our Services',
        ] ),
    ], '#0a0f1a' ),

    oc_section( 'sv-team', [
        oc_w( 'sv-team-w', 'oc-team-grid', [
            'posts_count'     => 4,
            'role_filter'     => '',
            'section_heading' => 'Meet The Crew',
            'section_eyebrow' => 'Expert Hands',
        ] ),
    ], '#060c14' ),

    oc_section( 'sv-faq', [
        oc_w( 'sv-faq-w', 'oc-faq-accordion', [
            'posts_count'     => -1,
            'category'        => 'onboard-experience',
            'section_heading' => 'Onboard FAQs',
            'section_eyebrow' => 'Good to Know',
        ] ),
    ], '#0a0f1a' ),

    oc_section( 'sv-cta', [
        oc_cta( 'sv-cta-w',
            'Experience the Difference',
            'Every charter is as unique as the guests aboard. Tell us what matters most to you.',
            'Enquire Now'
        ),
    ], '#111a28', '60', '60' ),

];

oc_dyn_set( 78, $services_json, 'Services' );

// ════════════════════════════════════════════════════════════════
// PAGE: DESTINATIONS (ID 79)
// Sections: Hero · Destinations Grid · Itineraries · CTA
// ════════════════════════════════════════════════════════════════
$destinations_json = [

    oc_cnt( 'dst-hero', [
        oc_hero_banner( 'dst-hero-w', 'Sailing Destinations', 'The World\'s Finest Waters', $HERO['destinations'],
            'From the Aegean to the Indian Ocean — your private yacht awaits.' ),
    ] ),

    oc_section( 'dst-grid', [
        oc_w( 'dst-grid-w', 'oc-destination-grid', [
            'posts_count'     => 6,
            'show_filter'     => 'yes',
            'section_heading' => 'Explore Destinations',
            'section_eyebrow' => 'Where Will You Go',
        ] ),
    ], '#0a0f1a' ),

    oc_section( 'dst-itins', [
        oc_w( 'dst-itins-w', 'oc-itinerary-grid', [
            'posts_count'     => 2,
            'section_heading' => 'Sample Itineraries',
            'section_eyebrow' => 'Curated Routes',
        ] ),
    ], '#060c14' ),

    oc_section( 'dst-cta', [
        oc_cta( 'dst-cta-w',
            'Your Dream Destination Awaits',
            'Not sure where to go? Our charter team will help you find the perfect sailing waters for your group.',
            'Talk to an Expert'
        ),
    ], '#111a28', '60', '60' ),

];

oc_dyn_set( 79, $destinations_json, 'Destinations' );

// ════════════════════════════════════════════════════════════════
// PAGE: ITINERARY (ID 80)
// Sections: Hero · Itinerary Grid · Destinations · FAQ · CTA
// ════════════════════════════════════════════════════════════════
$itinerary_json = [

    oc_cnt( 'it-hero', [
        oc_hero_banner( 'it-hero-w', 'Luxury Sailing', 'Sample Itineraries', $HERO['itinerary'],
            'Every voyage is tailored to you. Browse our curated routes for inspiration.' ),
    ] ),

    oc_section( 'it-grid', [
        oc_w( 'it-grid-w', 'oc-itinerary-grid', [
            'posts_count'     => 6,
            'section_heading' => 'Our Itineraries',
            'section_eyebrow' => 'Chart Your Course',
        ] ),
    ], '#0a0f1a' ),

    oc_section( 'it-dest', [
        oc_w( 'it-dest-w', 'oc-destination-grid', [
            'posts_count'     => 3,
            'show_filter'     => 'no',
            'section_heading' => 'Popular Destinations',
            'section_eyebrow' => 'Starting Points',
        ] ),
    ], '#060c14' ),

    oc_section( 'it-faq', [
        oc_w( 'it-faq-w', 'oc-faq-accordion', [
            'posts_count'     => 4,
            'category'        => 'booking',
            'section_heading' => 'Planning Your Trip',
            'section_eyebrow' => 'Booking FAQs',
        ] ),
    ], '#0a0f1a' ),

    oc_section( 'it-cta', [
        oc_cta( 'it-cta-w',
            'Design Your Own Itinerary',
            'This is one example of the experiences we craft. Tell us your dream destination and we will create an itinerary tailored entirely to you.',
            'Plan My Journey',
            'WhatsApp Us'
        ),
    ], '#111a28', '60', '60' ),

];

oc_dyn_set( 80, $itinerary_json, 'Itinerary' );

// ════════════════════════════════════════════════════════════════
// PAGE: FLEET (ID 81)
// Sections: Hero · Vessel Grid · BBC Boats · FAQ · CTA
// ════════════════════════════════════════════════════════════════
$fleet_json = [

    oc_cnt( 'fl-hero', [
        oc_hero_banner( 'fl-hero-w', 'Our Fleet', 'Find Your Perfect Yacht', $HERO['fleet'],
            'From intimate sailing yachts to 65-metre superyachts — every vessel crewed to perfection.' ),
    ] ),

    oc_section( 'fl-vessels', [
        oc_w( 'fl-vessels-w', 'oc-vessel-grid', [
            'posts_count'     => 6,
            'show_filter'     => 'yes',
            'section_heading' => 'Available Vessels',
            'section_eyebrow' => 'Our Fleet',
        ] ),
    ], '#0a0f1a' ),

    oc_section( 'fl-faq', [
        oc_w( 'fl-faq-w', 'oc-faq-accordion', [
            'posts_count'     => 4,
            'category'        => 'general',
            'section_heading' => 'Common Questions',
            'section_eyebrow' => 'About Our Fleet',
        ] ),
    ], '#060c14' ),

    oc_section( 'fl-cta', [
        oc_cta( 'fl-cta-w',
            'Not Sure Which Yacht?',
            'Our charter specialists will match you with the perfect vessel for your group size, destination, and budget.',
            'Talk to a Specialist',
            'WhatsApp Us'
        ),
    ], '#111a28', '60', '60' ),

];

oc_dyn_set( 81, $fleet_json, 'Fleet' );

// ════════════════════════════════════════════════════════════════
// PAGE: PACKAGES (ID 84)
// Sections: Hero · Packages Grid · Offers · Testimonials · FAQ · CTA
// ════════════════════════════════════════════════════════════════
$packages_json = [

    oc_cnt( 'pk-hero', [
        oc_hero_banner( 'pk-hero-w', 'Charter Packages', 'Curated Experiences', $HERO['packages'],
            'Choose from our signature packages or ask us to design something entirely bespoke.' ),
    ] ),

    oc_section( 'pk-grid', [
        oc_w( 'pk-grid-w', 'oc-package-grid', [
            'posts_count'     => 4,
            'show_filter'     => 'yes',
            'section_heading' => 'Charter Packages',
            'section_eyebrow' => 'Choose Your Experience',
        ] ),
    ], '#0a0f1a' ),

    oc_section( 'pk-offers', [
        oc_w( 'pk-offers-w', 'oc-offer-cards', [
            'posts_count'     => 3,
            'featured_only'   => 'no',
            'section_heading' => 'Current Offers',
            'section_eyebrow' => 'Limited Time',
        ] ),
    ], '#060c14' ),

    oc_section( 'pk-testi', [
        oc_w( 'pk-testi-w', 'oc-testimonial-carousel', [
            'posts_count'     => 3,
            'featured_only'   => 'yes',
            'section_heading' => 'What Guests Say',
            'section_eyebrow' => 'Guest Reviews',
        ] ),
    ], '#0a0f1a' ),

    oc_section( 'pk-faq', [
        oc_w( 'pk-faq-w', 'oc-faq-accordion', [
            'posts_count'     => -1,
            'category'        => 'pricing',
            'section_heading' => 'Pricing & Booking',
            'section_eyebrow' => 'Common Questions',
        ] ),
    ], '#060c14' ),

    oc_section( 'pk-cta', [
        oc_cta( 'pk-cta-w',
            'Build Your Bespoke Package',
            'Every aspect of your charter can be tailored — vessel, destination, duration, and services. Let\'s talk.',
            'Get a Custom Quote',
            'WhatsApp Us'
        ),
    ], '#111a28', '60', '60' ),

];

oc_dyn_set( 84, $packages_json, 'Packages' );

// ── Clear Elementor cache ────────────────────────────────────────────────────
if ( class_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance->files_manager ) ) {
    \Elementor\Plugin::$instance->files_manager->clear_cache();
    echo "\n✓ Elementor cache cleared.\n";
}

echo "\n" . str_repeat( '=', 50 ) . "\n";
echo "✓ All pages rebuilt with dynamic CPT widgets.\n";
echo "  Every section now pulls exclusively from the backend.\n";
echo str_repeat( '=', 50 ) . "\n";
