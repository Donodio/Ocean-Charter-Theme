<?php
/**
 * OC Setup — Itinerary Page (ID 80)
 *
 * Builds the Elementor layout for the Sample Itinerary page, matching the
 * Stitch "Ocean Charter - Itinerary" design:
 *   1. Banner hero
 *   2. 2-column body: timeline (7 days) LEFT + animated route map + booking sidebar RIGHT
 *   3. CTA strip
 *
 * Run via /tmp/run_oc_itinerary.sh
 */

if ( ! defined( 'ABSPATH' ) ) {
    $wp_load = dirname( __DIR__, 3 ) . '/wp-load.php';
    if ( file_exists( $wp_load ) ) {
        require $wp_load;
    } else {
        echo "wp-load.php not found.\n"; exit( 1 );
    }
}

// ── Helpers ───────────────────────────────────────────────────────────────────
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

// ── Image constants (with Pexels fallbacks matching page-itinerary.php) ──────
$hero_img = defined( 'OC_IMG_HERO_ITINERARY' ) ? OC_IMG_HERO_ITINERARY
          : ( defined( 'OC_IMG_HERO_HOME' )    ? OC_IMG_HERO_HOME
          : 'https://images.pexels.com/photos/1285625/pexels-photo-1285625.jpeg?auto=compress&cs=tinysrgb&w=1920' );

$it_imgs = [
    1 => defined( 'OC_IMG_ITINERARY_1' ) ? OC_IMG_ITINERARY_1 : 'https://images.pexels.com/photos/1285624/pexels-photo-1285624.jpeg?auto=compress&cs=tinysrgb&w=800',
    2 => defined( 'OC_IMG_ITINERARY_2' ) ? OC_IMG_ITINERARY_2 : 'https://images.pexels.com/photos/1430676/pexels-photo-1430676.jpeg?auto=compress&cs=tinysrgb&w=800',
    3 => defined( 'OC_IMG_ITINERARY_3' ) ? OC_IMG_ITINERARY_3 : 'https://images.pexels.com/photos/1533721/pexels-photo-1533721.jpeg?auto=compress&cs=tinysrgb&w=800',
    4 => defined( 'OC_IMG_ITINERARY_4' ) ? OC_IMG_ITINERARY_4 : 'https://images.pexels.com/photos/1268856/pexels-photo-1268856.jpeg?auto=compress&cs=tinysrgb&w=800',
    5 => defined( 'OC_IMG_ITINERARY_5' ) ? OC_IMG_ITINERARY_5 : 'https://images.pexels.com/photos/1705255/pexels-photo-1705255.jpeg?auto=compress&cs=tinysrgb&w=800',
];

// ── Day data ──────────────────────────────────────────────────────────────────
$days = [
    [
        'day'  => 'Day 1', 'location' => 'Departure — Athens, Greece',
        'desc' => '<p>Your adventure begins in Athens, where you will board your private yacht at Piraeus Marina. After a welcome briefing and champagne toast with your expert crew, you will set sail toward the crystal-clear waters of the Saronic Gulf, watching the ancient city fade gently into the horizon.</p><p>Anchor in a secluded cove for sunset cocktails before dining under the stars — a breathtaking overture to seven days of maritime luxury.</p>',
        'acts' => [ 'Piraeus Marina boarding', 'Champagne welcome toast', 'Sunset cove anchorage' ],
        'imgs' => [ $hero_img, $it_imgs[1] ],
    ],
    [
        'day'  => 'Day 2', 'location' => 'Santorini — The Caldera',
        'desc' => '<p>Arrive at the world-famous caldera of Santorini at dawn, when the whitewashed clifftop villages glow amber in the early light. Tender ashore to explore Oia\'s cobbled streets before returning for a freshly prepared Aegean lunch.</p><p>Snorkel the volcanic hot springs at Nea Kameni before watching Santorini\'s legendary sunset from the deck — yours exclusively.</p>',
        'acts' => [ 'Caldera arrival at dawn', 'Oia village exploration', 'Volcanic hot springs snorkel', 'Private sunset on deck' ],
        'imgs' => [ $it_imgs[1], $it_imgs[2] ],
    ],
    [
        'day'  => 'Day 3', 'location' => 'Mykonos — Cosmopolitan Energy',
        'desc' => '<p>Sail north to Mykonos, arriving by mid-morning at the iconic windmill peninsula. Stroll the labyrinthine lanes of Mykonos Town, browsing designer boutiques and stopping for a frappe at Little Venice.</p><p>Your concierge will secure a private table at a rooftop beach club for the afternoon.</p>',
        'acts' => [ 'Windmill peninsula arrival', 'Little Venice waterfront', 'Private beach club table' ],
        'imgs' => [ $it_imgs[2], $it_imgs[3] ],
    ],
    [
        'day'  => 'Day 4', 'location' => 'Delos — Sacred Island',
        'desc' => '<p>A short sail from Mykonos brings you to Delos, the mythological birthplace of Apollo and Artemis. Walk among remarkably preserved ruins with your private guide bringing 3,000-year-old stories to life.</p><p>Picnic lunch aboard, anchored in a quiet bay. The afternoon is yours for swimming and paddleboarding.</p>',
        'acts' => [ 'Private guided ruins tour', 'Avenue of Lions walk', 'Anchored bay swimming' ],
        'imgs' => [ $it_imgs[3], $it_imgs[4] ],
    ],
    [
        'day'  => 'Day 5', 'location' => 'Paros — Golden Villages',
        'desc' => '<p>Head south to Paros, anchoring off the golden-hued village of Naoussa. Your chef sources the day\'s ingredients here — locally caught fish, sun-ripened tomatoes, and Parian goat cheese.</p><p>As afternoon rolls in, launch the jet skis and water toys for an exhilarating session in the sheltered bay.</p>',
        'acts' => [ 'Naoussa village wander', 'Local market sourcing', 'Jet ski & water toy session' ],
        'imgs' => [ $it_imgs[4], $it_imgs[1] ],
    ],
    [
        'day'  => 'Day 6', 'location' => 'Hydra — Car-Free Elegance',
        'desc' => '<p>Sail west to Hydra, the car-free island of donkeys and stone mansions beloved by artists and writers. Stroll along the horseshoe harbour and climb to the monastery for panoramic views.</p><p>Afternoon dedicated to a full spa session aboard the yacht. Farewell dinner features the finest mezze and chilled Assyrtiko wine.</p>',
        'acts' => [ 'Horseshoe harbour stroll', 'Monastery panorama climb', 'Full spa session aboard', 'Farewell mezze dinner' ],
        'imgs' => [ $it_imgs[2], $it_imgs[3] ],
    ],
    [
        'day'  => 'Day 7', 'location' => 'Return — Athens, Greece',
        'desc' => '<p>Your final morning at sea. As the yacht cruises back toward Piraeus, savour a leisurely breakfast on deck watching the Greek coastline slide by. The crew will have your luggage packed and transfers arranged.</p><p>Disembark with memories to last a lifetime — and already wondering when you will return.</p>',
        'acts' => [ 'Breakfast on deck', 'Piraeus disembarkation', 'Concierge transfer service' ],
        'imgs' => [ $hero_img, $it_imgs[1] ],
    ],
];

// ── Helper: itinerary day widget node ─────────────────────────────────────────
function oc_itin_day_widget( string $wid, string $day, string $location, string $desc, array $acts, array $imgs ): array {
    return [
        'id'         => $wid,
        'elType'     => 'widget',
        'widgetType' => 'oc-itinerary-day',
        'settings'   => [
            'day_number'  => $day,
            'location'    => $location,
            'description' => $desc,
            'activities'  => array_map( fn( $a ) => [ 'label' => $a, '__dynamic__' => [] ], $acts ),
            'image_a'     => [ 'url' => $imgs[0] ?? '', 'id' => 0 ],
            'image_b'     => [ 'url' => $imgs[1] ?? '', 'id' => 0 ],
        ],
        'elements' => [],
    ];
}

// ── Intro HTML ────────────────────────────────────────────────────────────────
$intro_html = <<<'HTML'
<p style="color:rgba(148,163,184,1);font-size:17px;line-height:1.8;max-width:600px;margin:0 0 40px;">
  Follow your private yacht through seven of the most spectacular islands in the world. Every detail curated, every moment extraordinary.
</p>
HTML;

// ── 2-column body layout ──────────────────────────────────────────────────────
$timeline_col = [
    'id'     => 'it-body-l',
    'elType' => 'container',
    'settings' => [
        'content_width'  => 'full',
        'flex_direction' => 'column',
        'width'          => [ 'size' => 65, 'unit' => '%' ],
        'padding'        => [ 'unit' => 'px', 'top' => '0', 'right' => '24', 'bottom' => '0', 'left' => '0', 'isLinked' => false ],
    ],
    'elements' => array_merge(
        [
            [
                'id' => 'it-intro', 'elType' => 'widget', 'widgetType' => 'html',
                'settings' => [ 'html' => $intro_html ],
                'elements' => [],
            ],
        ],
        array_map(
            fn( $d, $i ) => oc_itin_day_widget( 'it-d' . ( $i + 1 ), $d['day'], $d['location'], $d['desc'], $d['acts'], $d['imgs'] ),
            $days,
            array_keys( $days )
        )
    ),
];

$sidebar_col = [
    'id'     => 'it-body-r',
    'elType' => 'container',
    'settings' => [
        'content_width'  => 'full',
        'flex_direction' => 'column',
        'width'          => [ 'size' => 35, 'unit' => '%' ],
        'padding'        => [ 'unit' => 'px', 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'isLinked' => false ],
    ],
    'elements' => [
        [
            'id'         => 'it-sidebar-w',
            'elType'     => 'widget',
            'widgetType' => 'oc-itinerary-sidebar',
            'settings'   => [
                'map_title'    => 'Route Map — Aegean',
                'map_stops'    => [
                    [ 'stop_name' => 'Athens',    'stop_x' => 75,  'stop_y' => 38,  '__dynamic__' => [] ],
                    [ 'stop_name' => 'Santorini', 'stop_x' => 92,  'stop_y' => 208, '__dynamic__' => [] ],
                    [ 'stop_name' => 'Mykonos',   'stop_x' => 205, 'stop_y' => 82,  '__dynamic__' => [] ],
                    [ 'stop_name' => 'Delos',     'stop_x' => 196, 'stop_y' => 108, '__dynamic__' => [] ],
                    [ 'stop_name' => 'Paros',     'stop_x' => 152, 'stop_y' => 158, '__dynamic__' => [] ],
                    [ 'stop_name' => 'Hydra',     'stop_x' => 58,  'stop_y' => 190, '__dynamic__' => [] ],
                    [ 'stop_name' => 'Athens',    'stop_x' => 75,  'stop_y' => 38,  '__dynamic__' => [] ],
                ],
                'card_title'   => 'Reserve Your Suite',
                'price'        => '$18,500',
                'price_period' => 'for 7 days (yacht only)',
                'price_note'   => 'Price varies by vessel selection and season. Includes crew, fuel, and all onboard provisions.',
                'cta_label'    => 'Book This Itinerary',
                'cta_url'      => [ 'url' => '/contact/', 'is_external' => '' ],
                'whatsapp'     => '+15551234567',
                'inclusions'   => array_map(
                    fn( $i ) => [ 'item' => $i, '__dynamic__' => [] ],
                    [
                        'Dedicated crew (captain + chef)',
                        'All meals & beverages',
                        'Fuel & marina fees',
                        'Water sports equipment',
                        'Private guide at Delos',
                        '24/7 concierge service',
                    ]
                ),
            ],
            'elements' => [],
        ],
    ],
];

$body_row = [
    'id'     => 'it-body-row',
    'elType' => 'container',
    'settings' => [
        'content_width'  => 'full',
        'flex_direction' => 'row',
        'align_items'    => 'flex-start',
        'gap'            => [ 'unit' => 'px', 'size' => 0, 'column' => '0', 'row' => '0' ],
        'padding'        => [ 'unit' => 'px', 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'isLinked' => false ],
    ],
    'elements' => [ $timeline_col, $sidebar_col ],
];

// ── Build Itinerary JSON ──────────────────────────────────────────────────────
$itinerary_json = [

    // 1. Banner hero
    oc4_full_container( 'it-hero', [
        oc4_hero_widget( 'it-hero-w', 'Luxury Sailing', 'Sample Itinerary', $hero_img ),
    ] ),

    // 2. Body: timeline + sidebar
    oc4_full_container( 'it-body', [ $body_row ], [
        'background_background' => 'classic',
        'background_color'      => '#0a0f1a',
        'padding'               => [ 'unit' => 'px', 'top' => '80', 'right' => '60', 'bottom' => '100', 'left' => '60', 'isLinked' => false ],
    ] ),

    // 3. CTA strip
    oc4_full_container( 'it-cta', [
        [
            'id'         => 'it-cta-w',
            'elType'     => 'widget',
            'widgetType' => 'oc-cta-strip',
            'settings'   => [
                'heading'         => 'Design Your Own Itinerary',
                'subtext'         => 'This is one example of the experiences we craft. Tell us your dream destination and we will create an itinerary tailored entirely to you.',
                'primary_label'   => 'Plan My Journey',
                'primary_url'     => [ 'url' => '/contact/' ],
                'secondary_label' => 'WhatsApp Us',
            ],
            'elements' => [],
        ],
    ] ),

];

oc4_set_elementor( 80, $itinerary_json, 'Itinerary page' );

// ── Clear Elementor file cache ────────────────────────────────────────────────
if ( class_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance->files_manager ) ) {
    \Elementor\Plugin::$instance->files_manager->clear_cache();
    echo "✓ Elementor cache cleared.\n";
}

echo "\nItinerary setup complete.\n";
