<?php
/**
 * Ocean Charter — Extra Demo Content Seeder
 * Adds additional itineraries, offers, and vessels to reach 4-column grid counts.
 * Run via: wp eval-file inc/cpt/demo-content-extra.php
 * Or include from inc/demo-importer.php after demo-content.php has run.
 *
 * @package OceanCharter
 */
if ( ! defined( 'ABSPATH' ) ) {
    $wp_load = dirname( __DIR__, 5 ) . '/wp-load.php';
    if ( file_exists( $wp_load ) ) require $wp_load;
    else { echo "wp-load.php not found.\n"; exit(1); }
}

if ( ! ( defined( 'WP_CLI' ) && WP_CLI ) && ! current_user_can( 'manage_options' ) ) {
    wp_die( 'Admin access required.' );
}

// Prevent double-seeding. The demo importer deletes this option before
// including the file so re-imports work; direct CLI runs respect it.
if ( get_option( 'oc_demo_extra_seeded' ) === '1' ) {
    echo "Extra demo content already seeded. Delete option 'oc_demo_extra_seeded' to re-run.\n";
    return;
}

// ---------------------------------------------------------------------------
// Helper: create or update a post (skip insert if title already exists)
// ---------------------------------------------------------------------------
if ( ! function_exists( 'oc_seed_post' ) ) {
    function oc_seed_post( string $post_type, string $title, string $excerpt, array $meta, string $status = 'publish' ): int {
        $existing = get_posts( [
            'post_type'      => $post_type,
            'title'          => $title,
            'posts_per_page' => 1,
            'post_status'    => 'any',
            'fields'         => 'ids',
        ] );

        if ( $existing ) {
            $id = $existing[0];
        } else {
            $id = wp_insert_post( [
                'post_type'    => sanitize_key( $post_type ),
                'post_title'   => sanitize_text_field( $title ),
                'post_excerpt' => wp_kses_post( $excerpt ),
                'post_status'  => sanitize_key( $status ),
                'post_content' => '',
            ] );
        }

        if ( is_wp_error( $id ) || ! $id ) {
            echo "  [ERROR] Could not insert post: {$title}\n";
            return 0;
        }

        foreach ( $meta as $k => $v ) {
            update_post_meta( $id, $k, $v );
        }

        return (int) $id;
    }
}

// ---------------------------------------------------------------------------
// Helper: ensure a taxonomy term exists; return term_id
// ---------------------------------------------------------------------------
if ( ! function_exists( 'oc_ensure_term' ) ) {
    function oc_ensure_term( string $term, string $taxonomy, ?int $parent = null ): int {
        $existing = get_term_by( 'name', $term, $taxonomy );
        if ( $existing ) return (int) $existing->term_id;
        $args   = $parent ? [ 'parent' => $parent ] : [];
        $result = wp_insert_term( $term, $taxonomy, $args );
        return is_wp_error( $result ) ? 0 : (int) $result['term_id'];
    }
}

echo "\nSeeding Ocean Charter extra demo content...\n";
echo str_repeat( '-', 50 ) . "\n";

// ===========================================================================
// EXTRA ITINERARIES (adds itinerary 3 & 4 for 4-column grid)
// ===========================================================================
echo "\n[1/3] Seeding Extra Itineraries...\n";

// ---------------------------------------------------------------------------
// Itinerary 3: 10-Day Caribbean Adventure
// ---------------------------------------------------------------------------
$itin3_id = oc_seed_post(
    'oc_itinerary',
    '10-Day Caribbean Adventure',
    'An island-hopping voyage through the Lesser Antilles, from Barbados to Antigua.',
    [
        '_oc_subtitle'     => 'Barbados to Antigua',
        '_oc_duration'     => '10 Days',
        '_oc_region'       => 'Caribbean',
        '_oc_tags'         => 'Caribbean,Snorkelling,Culture',
        '_oc_price'        => '$28,000',
        '_oc_price_period' => 'for 10 days',
        '_oc_inclusions'   => json_encode( [
            'Full crew',
            'All meals & rum cocktails',
            'Snorkelling & diving gear',
            'Island excursions',
            '24/7 concierge',
        ] ),
        '_oc_route_stops'  => json_encode( [
            [ 'name' => 'Barbados',    'lat' => 13.1939, 'lng' => -59.5432 ],
            [ 'name' => 'St Lucia',    'lat' => 13.9094, 'lng' => -60.9789 ],
            [ 'name' => 'Dominica',    'lat' => 15.4150, 'lng' => -61.3710 ],
            [ 'name' => 'Guadeloupe',  'lat' => 16.2650, 'lng' => -61.5510 ],
            [ 'name' => 'Montserrat',  'lat' => 16.7425, 'lng' => -62.1874 ],
            [ 'name' => 'Antigua',     'lat' => 17.0608, 'lng' => -61.7964 ],
        ] ),
        '_oc_image_url'    => 'https://images.pexels.com/photos/1268856/pexels-photo-1268856.jpeg?auto=compress&cs=tinysrgb&w=1920',
    ]
);
echo "  + 10-Day Caribbean Adventure (ID: {$itin3_id})\n";

// Day children for Itinerary 3
$caribbean_days = [
    [
        'title'      => 'Barbados — Bridgetown Departure',
        'day'        => 1,
        'location'   => 'Bridgetown Harbour',
        'desc'       => 'Set sail from the pink-sand shores of Barbados. Welcome rum punch aboard, crew briefing, and an afternoon swim at a secluded cove.',
        'activities' => json_encode( [
            'Bridgetown harbour boarding',
            'Welcome rum cocktails',
            'Secluded cove swim',
        ] ),
    ],
    [
        'title'      => 'St Lucia — The Pitons',
        'day'        => 5,
        'location'   => 'St Lucia',
        'desc'       => 'Sail into the shadow of the iconic twin Piton peaks. Anchor in the dramatic volcanic bay for snorkelling, a rainforest hike, and a sunset Piton beer aboard.',
        'activities' => json_encode( [
            'Piton bay anchorage',
            'Rainforest guided hike',
            'Volcanic hot springs',
        ] ),
    ],
    [
        'title'      => 'Antigua — English Harbour',
        'day'        => 10,
        'location'   => 'English Harbour Antigua',
        'desc'       => 'Your final day in one of the Caribbean\'s most celebrated sailing harbours. Explore Nelson\'s Dockyard before a farewell lobster dinner aboard.',
        'activities' => json_encode( [
            'Nelson\'s Dockyard tour',
            'Farewell lobster dinner',
            'Antigua transfer',
        ] ),
    ],
];

foreach ( $caribbean_days as $day ) {
    $day_id = oc_seed_post(
        'oc_itinerary_day',
        $day['title'],
        '',
        [
            '_oc_parent_itinerary' => $itin3_id,
            '_oc_day_number'       => $day['day'],
            '_oc_location'         => $day['location'],
            '_oc_description'      => $day['desc'],
            '_oc_activities'       => $day['activities'],
        ]
    );
    if ( $day_id ) {
        wp_update_post( [ 'ID' => $day_id, 'post_parent' => $itin3_id ] );
    }
    echo "    - Day {$day['day']}: {$day['title']} (ID: {$day_id})\n";
}

// ---------------------------------------------------------------------------
// Itinerary 4: 7-Day Norwegian Fjords
// ---------------------------------------------------------------------------
$itin4_id = oc_seed_post(
    'oc_itinerary',
    '7-Day Norwegian Fjords',
    'A dramatic voyage through Norway\'s deepest fjords, from the colourful wharves of Bergen to the mountain village of Flam.',
    [
        '_oc_subtitle'     => 'Bergen to Flam',
        '_oc_duration'     => '7 Days',
        '_oc_region'       => 'Northern Europe',
        '_oc_tags'         => 'Fjords,Wilderness,Adventure',
        '_oc_price'        => '$22,000',
        '_oc_price_period' => 'for 7 days',
        '_oc_inclusions'   => json_encode( [
            'Expert arctic crew',
            'All provisions',
            'Kayaks & paddleboards',
            'Northern Lights watch',
            'Shore excursions',
        ] ),
        '_oc_route_stops'  => json_encode( [
            [ 'name' => 'Bergen',         'lat' => 60.3913, 'lng' => 5.3221  ],
            [ 'name' => 'Hardangerfjord', 'lat' => 60.2300, 'lng' => 6.2300  ],
            [ 'name' => 'Sognefjord',     'lat' => 61.1700, 'lng' => 6.7500  ],
            [ 'name' => 'Naeroyfjord',    'lat' => 60.8900, 'lng' => 6.8500  ],
            [ 'name' => 'Flam',           'lat' => 60.8627, 'lng' => 7.1137  ],
        ] ),
        '_oc_image_url'    => 'https://images.pexels.com/photos/1533721/pexels-photo-1533721.jpeg?auto=compress&cs=tinysrgb&w=1920',
    ]
);
echo "  + 7-Day Norwegian Fjords (ID: {$itin4_id})\n";

// Day children for Itinerary 4
$fjords_days = [
    [
        'title'      => 'Bergen — Gateway to the Fjords',
        'day'        => 1,
        'location'   => 'Bergen Norway',
        'desc'       => 'Arrive in the colourful wooden-house city of Bergen. Board at the historic Bryggen wharf and sail into the mirror-still waters of the Hardangerfjord.',
        'activities' => json_encode( [
            'Bryggen wharf boarding',
            'Hardangerfjord entry',
            'Midnight sun cocktails',
        ] ),
    ],
    [
        'title'      => 'Sognefjord — Deepest in the World',
        'day'        => 4,
        'location'   => 'Sognefjord Norway',
        'desc'       => 'Navigate the deepest fjord on earth, flanked by 1,700-metre vertical cliff walls. Kayak into side arms inaccessible to larger vessels.',
        'activities' => json_encode( [
            'Kayak into side fjords',
            'Waterfall swim',
            'Norwegian farmhouse lunch',
        ] ),
    ],
    [
        'title'      => 'Flam — Mountain Railway',
        'day'        => 7,
        'location'   => 'Flam Norway',
        'desc'       => 'Sail into the charming village of Flam at the end of the Naeroyfjord. Optional ride on the famous mountain railway before your final dinner of smoked salmon and cloudberries.',
        'activities' => json_encode( [
            'Naeroyfjord sunrise',
            'Flamsbana mountain railway',
            'Farewell Nordic dinner',
        ] ),
    ],
];

foreach ( $fjords_days as $day ) {
    $day_id = oc_seed_post(
        'oc_itinerary_day',
        $day['title'],
        '',
        [
            '_oc_parent_itinerary' => $itin4_id,
            '_oc_day_number'       => $day['day'],
            '_oc_location'         => $day['location'],
            '_oc_description'      => $day['desc'],
            '_oc_activities'       => $day['activities'],
        ]
    );
    if ( $day_id ) {
        wp_update_post( [ 'ID' => $day_id, 'post_parent' => $itin4_id ] );
    }
    echo "    - Day {$day['day']}: {$day['title']} (ID: {$day_id})\n";
}

// ===========================================================================
// EXTRA OFFER (adds offer 4 for 4-column grid)
// ===========================================================================
echo "\n[2/3] Seeding Extra Offer...\n";

$offer4_id = oc_seed_post(
    'oc_offer',
    'Winter Maldives Special',
    'Escape the winter with a discounted Maldives charter. Bioluminescent bays and whale sharks await.',
    [
        '_oc_subtitle'      => 'November to February only',
        '_oc_discount'      => '15%',
        '_oc_discount_type' => 'percent',
        '_oc_valid_from'    => '2025-11-01',
        '_oc_valid_to'      => '2026-02-28',
        '_oc_badge_text'    => 'Winter Escape',
        '_oc_cta_url'       => '',
        '_oc_is_featured'   => 1,
        '_oc_image_url'     => 'https://images.pexels.com/photos/1705255/pexels-photo-1705255.jpeg?auto=compress&cs=tinysrgb&w=800',
    ]
);
echo "  + Winter Maldives Special (ID: {$offer4_id})\n";

// ===========================================================================
// EXTRA VESSELS (adds vessels 5 & 6 for variety)
// ===========================================================================
echo "\n[3/3] Seeding Extra Vessels...\n";

// Ensure vessel type terms exist
$extra_vessel_types = [ 'Sailing Yacht', 'Catamaran' ];
$extra_vessel_type_ids = [];
foreach ( $extra_vessel_types as $type ) {
    $extra_vessel_type_ids[ $type ] = oc_ensure_term( $type, 'oc_vessel_type' );
}

// Vessel 5: Artemis
$vessel5_id = oc_seed_post(
    'oc_vessel',
    'Artemis',
    'A classic wooden sailing ketch blending timeless beauty with modern performance.',
    [
        '_oc_length'         => '28m',
        '_oc_guests'         => 8,
        '_oc_cabins'         => 4,
        '_oc_speed'          => 10,
        '_oc_year_built'     => 2015,
        '_oc_builder'        => 'Turkish Gulet',
        '_oc_flag'           => 'Turkey',
        '_oc_home_port'      => 'Bodrum',
        '_oc_price_per_day'  => '$3,200',
        '_oc_price_per_week' => '$20,000',
        '_oc_cta_url'        => '/contact/',
        '_oc_amenities'      => json_encode( [
            'Traditional Gulet Deck',
            'Sun Cushions',
            'Snorkelling Gear',
            'Kayaks',
            'BBQ',
        ] ),
        '_oc_specs'          => json_encode( [
            'LOA: 28m',
            'Beam: 7.5m',
            'Draught: 2.8m',
            'Cruising: 10kt',
        ] ),
        '_oc_image_url'      => 'https://images.pexels.com/photos/1001682/pexels-photo-1001682.jpeg?auto=compress&cs=tinysrgb&w=1920',
    ]
);
if ( $vessel5_id && isset( $extra_vessel_type_ids['Sailing Yacht'] ) ) {
    wp_set_object_terms( $vessel5_id, [ $extra_vessel_type_ids['Sailing Yacht'] ], 'oc_vessel_type' );
}
echo "  + Artemis (ID: {$vessel5_id})\n";

// Vessel 6: Horizon
$vessel6_id = oc_seed_post(
    'oc_vessel',
    'Horizon',
    'A sleek performance catamaran built for speed, stability, and extraordinary ocean crossings.',
    [
        '_oc_length'         => '18m',
        '_oc_guests'         => 6,
        '_oc_cabins'         => 3,
        '_oc_speed'          => 14,
        '_oc_year_built'     => 2023,
        '_oc_builder'        => 'HH Catamarans',
        '_oc_flag'           => 'USA',
        '_oc_home_port'      => 'Miami',
        '_oc_price_per_day'  => '$3,800',
        '_oc_price_per_week' => '$24,500',
        '_oc_cta_url'        => '/contact/',
        '_oc_amenities'      => json_encode( [
            'Carbon Fibre Construction',
            'Trampoline Nets',
            'Electric Tender',
            'Solar Panels',
            'Water Maker',
        ] ),
        '_oc_specs'          => json_encode( [
            'LOA: 18m',
            'Beam: 9m',
            'Max Speed: 20kt',
            'Cruising: 14kt',
        ] ),
        '_oc_image_url'      => 'https://images.pexels.com/photos/3699535/pexels-photo-3699535.jpeg?auto=compress&cs=tinysrgb&w=1920',
    ]
);
if ( $vessel6_id && isset( $extra_vessel_type_ids['Catamaran'] ) ) {
    wp_set_object_terms( $vessel6_id, [ $extra_vessel_type_ids['Catamaran'] ], 'oc_vessel_type' );
}
echo "  + Horizon (ID: {$vessel6_id})\n";

// ===========================================================================
// Mark seeded & summary
// ===========================================================================
update_option( 'oc_demo_extra_seeded', '1' );

echo "\n" . str_repeat( '=', 50 ) . "\n";
echo "✓ Ocean Charter extra demo content seeded successfully.\n";
echo "  Extra Itineraries: 2 (10-Day Caribbean Adventure, 7-Day Norwegian Fjords)\n";
echo "    - Caribbean days: 3\n";
echo "    - Norwegian Fjords days: 3\n";
echo "  Extra Offers: 1 (Winter Maldives Special)\n";
echo "  Extra Vessels: 2 (Artemis, Horizon)\n";
echo "\n  Running totals (combined with demo-content.php):\n";
echo "    Itineraries: 4\n";
echo "    Offers: 4\n";
echo "    Vessels: 6\n";
echo str_repeat( '=', 50 ) . "\n\n";
