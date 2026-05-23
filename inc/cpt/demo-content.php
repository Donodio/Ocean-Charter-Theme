<?php
/**
 * Ocean Charter — Demo Content Seeder
 * Seeds all OC CPTs with realistic demo data.
 * Run via: wp eval-file inc/cpt/demo-content.php
 * Or include from a setup script.
 */
if ( ! defined( 'ABSPATH' ) ) {
    $wp_load = dirname( __DIR__, 5 ) . '/wp-load.php';
    if ( file_exists( $wp_load ) ) require $wp_load;
    else { echo "wp-load.php not found.\n"; exit(1); }
}

// Prevent double-seeding
if ( get_option( 'oc_demo_seeded' ) === '1' ) {
    echo "Demo content already seeded. Delete option 'oc_demo_seeded' to re-run.\n";
    exit;
}

// ---------------------------------------------------------------------------
// Pexels image URLs (hardcoded — no API calls needed)
// ---------------------------------------------------------------------------
$img_yacht       = 'https://images.pexels.com/photos/1430676/pexels-photo-1430676.jpeg?auto=compress&cs=tinysrgb&w=800';
$img_sailing     = 'https://images.pexels.com/photos/1285624/pexels-photo-1285624.jpeg?auto=compress&cs=tinysrgb&w=800';
$img_mediterranean = 'https://images.pexels.com/photos/1010657/pexels-photo-1010657.jpeg?auto=compress&cs=tinysrgb&w=800';
$img_santorini   = 'https://images.pexels.com/photos/1285625/pexels-photo-1285625.jpeg?auto=compress&cs=tinysrgb&w=800';
$img_islands     = 'https://images.pexels.com/photos/1533721/pexels-photo-1533721.jpeg?auto=compress&cs=tinysrgb&w=800';
$img_luxury      = 'https://images.pexels.com/photos/1705255/pexels-photo-1705255.jpeg?auto=compress&cs=tinysrgb&w=800';
$img_crew        = 'https://images.pexels.com/photos/1181690/pexels-photo-1181690.jpeg?auto=compress&cs=tinysrgb&w=400';
$img_chef        = 'https://images.pexels.com/photos/887827/pexels-photo-887827.jpeg?auto=compress&cs=tinysrgb&w=400';
$img_woman       = 'https://images.pexels.com/photos/1239291/pexels-photo-1239291.jpeg?auto=compress&cs=tinysrgb&w=400';

// ---------------------------------------------------------------------------
// Helper: create or update a post (skip insert if title already exists)
// ---------------------------------------------------------------------------
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

// ---------------------------------------------------------------------------
// Helper: ensure a taxonomy term exists; return term_id
// ---------------------------------------------------------------------------
function oc_ensure_term( string $term, string $taxonomy, ?int $parent = null ): int {
    $existing = get_term_by( 'name', $term, $taxonomy );
    if ( $existing ) return (int) $existing->term_id;
    $args   = $parent ? [ 'parent' => $parent ] : [];
    $result = wp_insert_term( $term, $taxonomy, $args );
    return is_wp_error( $result ) ? 0 : (int) $result['term_id'];
}

echo "\nSeeding Ocean Charter demo content...\n";
echo str_repeat( '-', 50 ) . "\n";

// ===========================================================================
// 1. DESTINATIONS
// ===========================================================================
echo "\n[1/10] Seeding Destinations...\n";

// Taxonomy: oc_destination_region
$regions = [
    'Mediterranean',
    'Caribbean',
    'Indian Ocean',
    'Pacific',
    'Northern Europe',
    'Middle East',
];
$region_ids = [];
foreach ( $regions as $region ) {
    $region_ids[ $region ] = oc_ensure_term( $region, 'oc_destination_region' );
}

$destinations = [
    [
        'title'   => 'Santorini & the Cyclades',
        'excerpt' => 'Volcanic caldera sunsets, whitewashed villages, and crystal Aegean waters.',
        'meta'    => [
            '_oc_vessel_count' => 24,
            '_oc_is_popular'   => 1,
            '_oc_explore_url'  => '/itinerary/',
            '_oc_image_url'    => $img_santorini,
        ],
        'region'  => 'Mediterranean',
    ],
    [
        'title'   => 'Amalfi Coast',
        'excerpt' => 'Cliffside villages, limoncello-scented air, and the bluest water in Italy.',
        'meta'    => [
            '_oc_vessel_count' => 18,
            '_oc_is_popular'   => 1,
            '_oc_image_url'    => $img_mediterranean,
        ],
        'region'  => 'Mediterranean',
    ],
    [
        'title'   => 'BVI & Caribbean',
        'excerpt' => 'Year-round sunshine, rum-punch sundowners, and the legendary Baths.',
        'meta'    => [
            '_oc_vessel_count' => 31,
            '_oc_image_url'    => $img_sailing,
        ],
        'region'  => 'Caribbean',
    ],
    [
        'title'   => 'Maldives',
        'excerpt' => 'Overwater bungalows, bioluminescent bays, and whale shark encounters.',
        'meta'    => [
            '_oc_vessel_count' => 12,
            '_oc_is_popular'   => 1,
            '_oc_image_url'    => $img_islands,
        ],
        'region'  => 'Indian Ocean',
    ],
    [
        'title'   => 'Norwegian Fjords',
        'excerpt' => 'Midnight sun, dramatic cliffs, and the silence of ancient glaciers.',
        'meta'    => [
            '_oc_vessel_count' => 8,
            '_oc_image_url'    => $img_luxury,
        ],
        'region'  => 'Northern Europe',
    ],
    [
        'title'   => 'Dubrovnik Riviera',
        'excerpt' => 'Medieval walled cities, hidden coves, and the pearl of the Adriatic.',
        'meta'    => [
            '_oc_vessel_count' => 15,
            '_oc_image_url'    => $img_yacht,
        ],
        'region'  => 'Mediterranean',
    ],
];

foreach ( $destinations as $d ) {
    $id = oc_seed_post( 'oc_destination', $d['title'], $d['excerpt'], $d['meta'] );
    if ( $id && isset( $region_ids[ $d['region'] ] ) ) {
        wp_set_object_terms( $id, [ $region_ids[ $d['region'] ] ], 'oc_destination_region' );
    }
    echo "  + {$d['title']} (ID: {$id})\n";
}

// ===========================================================================
// 2. SERVICES
// ===========================================================================
echo "\n[2/10] Seeding Services...\n";

$services = [
    [
        'title'   => 'Private Chef',
        'excerpt' => 'Michelin-trained chefs craft bespoke menus from the finest local produce.',
        'meta'    => [
            '_oc_eyebrow'    => 'Culinary',
            '_oc_badge_icon' => 'chef',
            '_oc_link_url'   => '',
            '_oc_features'   => json_encode( [ 'Custom menus', 'Dietary accommodations', 'Wine pairing', 'Local market sourcing' ] ),
            '_oc_image_url'  => $img_chef,
        ],
    ],
    [
        'title'   => 'Water Sports & Toys',
        'excerpt' => 'Jet skis, paddleboards, sea bobs, and full scuba diving equipment.',
        'meta'    => [
            '_oc_eyebrow'    => 'Adventure',
            '_oc_badge_icon' => 'water',
            '_oc_link_url'   => '',
            '_oc_features'   => json_encode( [ 'Jet skis', 'Paddleboards', 'Scuba diving', 'Sea bobs', 'Snorkelling gear' ] ),
            '_oc_image_url'  => $img_sailing,
        ],
    ],
    [
        'title'   => 'Events & Celebrations',
        'excerpt' => 'From intimate anniversaries to landmark corporate gatherings at sea.',
        'meta'    => [
            '_oc_eyebrow'    => 'Events',
            '_oc_badge_icon' => 'events',
            '_oc_link_url'   => '',
            '_oc_features'   => json_encode( [ 'Event planning', 'Floral arrangements', 'Live music coordination', 'Custom theming', 'Photography' ] ),
            '_oc_image_url'  => $img_luxury,
        ],
    ],
    [
        'title'   => 'Concierge Service',
        'excerpt' => '24/7 personal concierge handling every detail from shore excursions to transfers.',
        'meta'    => [
            '_oc_eyebrow'    => 'Lifestyle',
            '_oc_badge_icon' => 'concierge',
            '_oc_link_url'   => '',
            '_oc_features'   => json_encode( [ 'Shore excursions', 'Restaurant reservations', 'Private transfers', 'Helicopter bookings', 'VIP access' ] ),
            '_oc_image_url'  => $img_crew,
        ],
    ],
];

foreach ( $services as $s ) {
    $id = oc_seed_post( 'oc_service', $s['title'], $s['excerpt'], $s['meta'] );
    echo "  + {$s['title']} (ID: {$id})\n";
}

// ===========================================================================
// 3. PACKAGES
// ===========================================================================
echo "\n[3/10] Seeding Packages...\n";

// Taxonomy: oc_package_type
$pkg_types = [ 'Day Charter', 'Weekend', 'Weekly', 'Corporate', 'Celebration', 'Honeymoon' ];
$pkg_type_ids = [];
foreach ( $pkg_types as $type ) {
    $pkg_type_ids[ $type ] = oc_ensure_term( $type, 'oc_package_type' );
}

$packages = [
    [
        'title'   => 'Sunset Day Charter',
        'excerpt' => 'A 6-hour voyage along the coast with sunset cocktails and a private chef dinner.',
        'meta'    => [
            '_oc_tag'        => 'Popular',
            '_oc_price'      => '$2,800',
            '_oc_duration'   => '6 hours',
            '_oc_cta_url'    => '/contact/',
            '_oc_inclusions' => json_encode( [ 'Captain & crew', 'Welcome drinks', '3-course dinner', 'Snorkelling stop', 'Return transfer' ] ),
            '_oc_image_url'  => $img_sailing,
        ],
        'types'   => [ 'Day Charter' ],
    ],
    [
        'title'   => 'Romantic Weekend Escape',
        'excerpt' => 'Two nights aboard a private sailing yacht anchored in secluded bays.',
        'meta'    => [
            '_oc_tag'        => 'Signature',
            '_oc_price'      => '$6,500',
            '_oc_duration'   => '2 nights',
            '_oc_cta_url'    => '/contact/',
            '_oc_inclusions' => json_encode( [ 'Captain & chef', 'All meals & drinks', 'Champagne on arrival', 'Spa treatments', 'Shore excursions' ] ),
            '_oc_image_url'  => $img_luxury,
        ],
        'types'   => [ 'Weekend', 'Honeymoon' ],
    ],
    [
        'title'   => '7-Day Aegean Odyssey',
        'excerpt' => 'A week-long voyage through the Greek islands with an expert crew and private guide.',
        'meta'    => [
            '_oc_tag'        => 'Featured',
            '_oc_price'      => '$18,500',
            '_oc_duration'   => '7 days',
            '_oc_cta_url'    => '/contact/',
            '_oc_inclusions' => json_encode( [ 'Full crew', 'All provisions', 'Fuel & marina fees', 'Private guide at Delos', 'Water sports equipment', '24/7 concierge' ] ),
            '_oc_image_url'  => $img_santorini,
        ],
        'types'   => [ 'Weekly' ],
    ],
    [
        'title'   => 'Corporate Charter',
        'excerpt' => 'Impress clients and reward teams with an exclusive corporate yachting experience.',
        'meta'    => [
            '_oc_tag'        => 'Celebration',
            '_oc_price'      => 'POA',
            '_oc_duration'   => 'Custom',
            '_oc_cta_url'    => '/contact/',
            '_oc_inclusions' => json_encode( [ 'AV equipment', 'Branded catering', 'Team-building activities', 'Photography', 'Transfer coordination' ] ),
            '_oc_image_url'  => $img_yacht,
        ],
        'types'   => [ 'Corporate' ],
    ],
];

foreach ( $packages as $p ) {
    $id = oc_seed_post( 'oc_package', $p['title'], $p['excerpt'], $p['meta'] );
    if ( $id ) {
        $term_ids = array_filter( array_map( fn( $t ) => $pkg_type_ids[ $t ] ?? 0, $p['types'] ) );
        if ( $term_ids ) {
            wp_set_object_terms( $id, array_values( $term_ids ), 'oc_package_type' );
        }
    }
    echo "  + {$p['title']} (ID: {$id})\n";
}

// ===========================================================================
// 4. TESTIMONIALS
// ===========================================================================
echo "\n[4/10] Seeding Testimonials...\n";

$testimonials = [
    [
        'title'  => 'James & Sophie Hartwell',
        'meta'   => [
            '_oc_quote'            => 'The entire team exceeded every expectation. We\'ve sailed with several charter companies before, but nothing compares to this level of personalised care.',
            '_oc_author_role'      => 'Honeymooners',
            '_oc_charter_location' => 'Santorini, Greece',
            '_oc_is_featured'      => 1,
            '_oc_image_url'        => $img_woman,
        ],
    ],
    [
        'title'  => 'Richard Chen',
        'meta'   => [
            '_oc_quote'            => 'Organised our company leadership retreat on a 7-day charter. Every detail was perfect — the crew transformed a business trip into an unforgettable experience.',
            '_oc_author_role'      => 'CEO, Pacific Ventures',
            '_oc_charter_location' => 'Amalfi Coast, Italy',
            '_oc_image_url'        => $img_crew,
        ],
    ],
    [
        'title'  => 'Isabelle Moreau',
        'meta'   => [
            '_oc_quote'            => 'The private chef was extraordinary. Every meal felt like a Michelin-starred restaurant, yet completely tailored to where we were. The sea bream from Paros was simply extraordinary.',
            '_oc_author_role'      => 'Food & Travel Writer',
            '_oc_charter_location' => 'Greek Cyclades',
            '_oc_image_url'        => $img_woman,
        ],
    ],
    [
        'title'  => 'The Anderson Family',
        'meta'   => [
            '_oc_quote'            => 'Five of us, three generations, and everyone was catered to perfectly. The crew made our children feel like royalty and gave us parents the relaxation we desperately needed.',
            '_oc_author_role'      => 'Family Charter',
            '_oc_charter_location' => 'BVI, Caribbean',
            '_oc_is_featured'      => 1,
            '_oc_image_url'        => $img_crew,
        ],
    ],
    [
        'title'  => 'Marco & Elena Rossi',
        'meta'   => [
            '_oc_quote'            => 'Celebrated our 25th anniversary exactly as we dreamed. Woke up to a different cove every morning. The spa session on Day 6 was the best birthday gift imaginable.',
            '_oc_author_role'      => 'Silver Anniversary',
            '_oc_charter_location' => 'Dubrovnik Riviera',
            '_oc_image_url'        => $img_luxury,
        ],
    ],
];

foreach ( $testimonials as $t ) {
    $id = oc_seed_post( 'oc_testimonial', $t['title'], '', $t['meta'] );
    echo "  + {$t['title']} (ID: {$id})\n";
}

// ===========================================================================
// 5. VESSELS
// ===========================================================================
echo "\n[5/10] Seeding Vessels...\n";

// Taxonomy: oc_vessel_type
$vessel_types = [ 'Motor Yacht', 'Sailing Yacht', 'Catamaran', 'Superyacht' ];
$vessel_type_ids = [];
foreach ( $vessel_types as $type ) {
    $vessel_type_ids[ $type ] = oc_ensure_term( $type, 'oc_vessel_type' );
}

$vessels = [
    [
        'title'   => 'Aelia',
        'excerpt' => 'A masterpiece of Italian design and German engineering. 48 metres of pure sailing perfection.',
        'meta'    => [
            '_oc_length'        => '48m',
            '_oc_guests'        => 12,
            '_oc_cabins'        => 6,
            '_oc_speed'         => 12,
            '_oc_year_built'    => 2019,
            '_oc_builder'       => 'Perini Navi',
            '_oc_flag'          => 'Cayman Islands',
            '_oc_home_port'     => 'Athens',
            '_oc_price_per_day' => '$12,000',
            '_oc_price_per_week'=> '$78,000',
            '_oc_cta_url'       => '/contact/',
            '_oc_amenities'     => json_encode( [ 'Jacuzzi', 'Gym', 'Cinema Room', 'Beach Club', 'Water Toys', 'Spa Room' ] ),
            '_oc_specs'         => json_encode( [ 'LOA: 48m', 'Beam: 9.6m', 'Draft: 4.5m', 'Cruising: 12kt', 'Range: 3,000nm' ] ),
            '_oc_gallery_1'     => $img_sailing,
            '_oc_image_url'     => $img_sailing,
        ],
        'type'    => 'Sailing Yacht',
    ],
    [
        'title'   => 'Poseidon',
        'excerpt' => 'A muscular motor yacht built for blue-water crossing with resort-grade interiors.',
        'meta'    => [
            '_oc_length'        => '38m',
            '_oc_guests'        => 10,
            '_oc_cabins'        => 5,
            '_oc_speed'         => 22,
            '_oc_year_built'    => 2021,
            '_oc_builder'       => 'Sunseeker',
            '_oc_flag'          => 'British Virgin Islands',
            '_oc_home_port'     => 'Monaco',
            '_oc_price_per_day' => '$8,500',
            '_oc_price_per_week'=> '$55,000',
            '_oc_cta_url'       => '/contact/',
            '_oc_amenities'     => json_encode( [ 'Pool', 'Bar', 'Jet Skis', 'Paddleboards', 'Scuba Gear' ] ),
            '_oc_specs'         => json_encode( [ 'LOA: 38m', 'Beam: 8.1m', 'Cruising: 22kt', 'Max: 28kt' ] ),
            '_oc_image_url'     => $img_yacht,
        ],
        'type'    => 'Motor Yacht',
    ],
    [
        'title'   => 'Zephyr',
        'excerpt' => 'Spacious catamaran with expansive deck and shallow-draft access to hidden coves.',
        'meta'    => [
            '_oc_length'        => '22m',
            '_oc_guests'        => 8,
            '_oc_cabins'        => 4,
            '_oc_speed'         => 9,
            '_oc_year_built'    => 2020,
            '_oc_builder'       => 'Lagoon',
            '_oc_flag'          => 'France',
            '_oc_home_port'     => 'Marseille',
            '_oc_price_per_day' => '$4,200',
            '_oc_price_per_week'=> '$27,000',
            '_oc_cta_url'       => '/contact/',
            '_oc_amenities'     => json_encode( [ 'Snorkelling Gear', 'Kayaks', 'Sun Deck', 'BBQ', 'Water Slide' ] ),
            '_oc_image_url'     => $img_mediterranean,
        ],
        'type'    => 'Catamaran',
    ],
    [
        'title'   => 'Empress',
        'excerpt' => 'The pinnacle of luxury at sea. A superyacht offering unrivalled privacy and service for discerning guests.',
        'meta'    => [
            '_oc_length'        => '65m',
            '_oc_guests'        => 16,
            '_oc_cabins'        => 8,
            '_oc_speed'         => 16,
            '_oc_year_built'    => 2022,
            '_oc_builder'       => 'Lürssen',
            '_oc_flag'          => 'Malta',
            '_oc_home_port'     => 'Dubai',
            '_oc_price_per_day' => '$35,000',
            '_oc_price_per_week'=> '$225,000',
            '_oc_cta_url'       => '/contact/',
            '_oc_amenities'     => json_encode( [ 'Cinema', 'Gym', 'Jacuzzi', 'Helipad', 'Beach Club', 'Wine Cellar', 'Tender Garage' ] ),
            '_oc_image_url'     => $img_luxury,
        ],
        'type'    => 'Superyacht',
    ],
];

foreach ( $vessels as $v ) {
    $id = oc_seed_post( 'oc_vessel', $v['title'], $v['excerpt'], $v['meta'] );
    if ( $id && isset( $vessel_type_ids[ $v['type'] ] ) ) {
        wp_set_object_terms( $id, [ $vessel_type_ids[ $v['type'] ] ], 'oc_vessel_type' );
    }
    echo "  + {$v['title']} (ID: {$id})\n";
}

// ===========================================================================
// 6. TEAM MEMBERS
// ===========================================================================
echo "\n[6/10] Seeding Team Members...\n";

// Taxonomy: oc_team_role
$team_roles = [ 'Captain', 'Chef', 'Crew', 'Management' ];
$team_role_ids = [];
foreach ( $team_roles as $role ) {
    $team_role_ids[ $role ] = oc_ensure_term( $role, 'oc_team_role' );
}

$team_members = [
    [
        'title'   => 'Captain Alex Stavros',
        'excerpt' => '25 years navigating the world\'s most demanding waters.',
        'meta'    => [
            '_oc_role_title'     => 'Captain & Skipper',
            '_oc_years_exp'      => 25,
            '_oc_bio'            => 'Captain Stavros holds a Master Mariner certificate and has logged over 300,000 nautical miles across the Mediterranean, Caribbean, and Pacific. Born in Thessaloniki, he brings an unmatched knowledge of Greek island anchorages and a relaxed, professional manner that puts guests immediately at ease.',
            '_oc_certifications' => json_encode( [ 'Master Mariner CoC', 'STCW95', 'MCA OOW', 'RYA Yachtmaster Ocean' ] ),
            '_oc_languages'      => json_encode( [ 'English', 'Greek', 'French' ] ),
            '_oc_image_url'      => $img_crew,
        ],
        'role'    => 'Captain',
    ],
    [
        'title'   => 'Chef Maria Konstantinou',
        'excerpt' => 'Michelin-trained, Mediterranean-obsessed, and relentlessly inventive.',
        'meta'    => [
            '_oc_role_title'     => 'Executive Chef',
            '_oc_years_exp'      => 15,
            '_oc_bio'            => 'Maria trained under three Michelin-starred chefs in Athens and Lyon before taking to the sea. She sources ingredients at every port, crafting menus around what\'s freshest that morning. Her philosophy: every meal should be a memory in its own right.',
            '_oc_certifications' => json_encode( [ 'Advanced Food Hygiene', 'ENG1 Medical', 'STCW Basic Safety' ] ),
            '_oc_languages'      => json_encode( [ 'Greek', 'English', 'French', 'Italian' ] ),
            '_oc_image_url'      => $img_chef,
        ],
        'role'    => 'Chef',
    ],
    [
        'title'   => 'First Officer Lena Brandt',
        'excerpt' => 'Navigator, divemaster, and water sports instructor in one.',
        'meta'    => [
            '_oc_role_title'     => 'First Officer',
            '_oc_years_exp'      => 10,
            '_oc_bio'            => 'Lena graduated from the German Maritime Academy before spending a decade on private yachts in the Mediterranean and Norwegian fjords. A PADI Divemaster, she leads the water sports programme and ensures safety standards exceed requirements.',
            '_oc_certifications' => json_encode( [ 'OOW Certificate', 'PADI Divemaster', 'RYA Powerboat L2', 'STCW95' ] ),
            '_oc_languages'      => json_encode( [ 'German', 'English', 'Spanish' ] ),
            '_oc_image_url'      => $img_crew,
        ],
        'role'    => 'Crew',
    ],
    [
        'title'   => 'James Whitfield',
        'excerpt' => 'Dedicated to crafting seamless charter experiences from first enquiry to fond farewell.',
        'meta'    => [
            '_oc_role_title'     => 'Charter Manager',
            '_oc_years_exp'      => 12,
            '_oc_bio'            => 'James brings 12 years of luxury charter management to the team. He coordinates every client detail — from dietary preferences to surprise anniversary arrangements — ensuring no request is too small and no expectation goes unmet.',
            '_oc_certifications' => json_encode( [ 'MYBA Charter Agreement', 'Maritime Tourism Diploma' ] ),
            '_oc_languages'      => json_encode( [ 'English', 'Italian', 'Arabic' ] ),
            '_oc_image_url'      => $img_crew,
        ],
        'role'    => 'Management',
    ],
];

foreach ( $team_members as $m ) {
    $id = oc_seed_post( 'oc_team_member', $m['title'], $m['excerpt'], $m['meta'] );
    if ( $id && isset( $team_role_ids[ $m['role'] ] ) ) {
        wp_set_object_terms( $id, [ $team_role_ids[ $m['role'] ] ], 'oc_team_role' );
    }
    echo "  + {$m['title']} (ID: {$id})\n";
}

// ===========================================================================
// 7. FAQs
// ===========================================================================
echo "\n[7/10] Seeding FAQs...\n";

// Taxonomy: oc_faq_category
$faq_cats = [ 'Booking', 'Onboard Experience', 'Pricing', 'General' ];
$faq_cat_ids = [];
foreach ( $faq_cats as $cat ) {
    $faq_cat_ids[ $cat ] = oc_ensure_term( $cat, 'oc_faq_category' );
}

$faqs = [
    [
        'title'    => 'How far in advance should I book?',
        'meta'     => [
            '_oc_answer'     => 'We recommend booking at least 3–6 months in advance for peak summer season (June–September). However, we can often accommodate last-minute bookings subject to vessel availability.',
            '_oc_sort_order' => 1,
        ],
        'category' => 'Booking',
    ],
    [
        'title'    => 'What is included in the charter price?',
        'meta'     => [
            '_oc_answer'     => 'All packages include the vessel, captain, and core crew. Most packages include fuel, marina fees, and provisions. Specific inclusions vary by package — please refer to the individual package details or contact our charter team for a personalised quote.',
            '_oc_sort_order' => 2,
        ],
        'category' => 'Pricing',
    ],
    [
        'title'    => 'Can I customise the itinerary?',
        'meta'     => [
            '_oc_answer'     => 'Absolutely. Every Ocean Charter voyage is tailored to your preferences. Share your desired destinations, activities, and pace with your charter manager and we will craft an itinerary around you.',
            '_oc_sort_order' => 3,
        ],
        'category' => 'Booking',
    ],
    [
        'title'    => 'What experience do I need to sail?',
        'meta'     => [
            '_oc_answer'     => 'None whatsoever. Every charter includes a fully qualified, licensed captain and crew. You simply relax and enjoy — no sailing experience is required or expected.',
            '_oc_sort_order' => 4,
        ],
        'category' => 'General',
    ],
    [
        'title'    => 'Can you accommodate dietary requirements?',
        'meta'     => [
            '_oc_answer'     => 'Yes. Our chefs accommodate all dietary requirements including vegetarian, vegan, gluten-free, and specific allergens. Please inform us at the time of booking so provisions can be sourced accordingly.',
            '_oc_sort_order' => 5,
        ],
        'category' => 'Onboard Experience',
    ],
    [
        'title'    => 'What water sports equipment is available?',
        'meta'     => [
            '_oc_answer'     => 'Availability varies by vessel, but typically includes jet skis, paddleboards, kayaks, snorkelling gear, and a tender dinghy. Some vessels carry scuba equipment and underwater scooters. Your charter manager will confirm what is available on your selected yacht.',
            '_oc_sort_order' => 6,
        ],
        'category' => 'Onboard Experience',
    ],
    [
        'title'    => 'Is travel insurance required?',
        'meta'     => [
            '_oc_answer'     => 'We strongly recommend comprehensive travel insurance including medical evacuation coverage. While the vessel carries full maritime insurance, personal travel insurance is the responsibility of guests.',
            '_oc_sort_order' => 7,
        ],
        'category' => 'General',
    ],
    [
        'title'    => 'How does payment work?',
        'meta'     => [
            '_oc_answer'     => 'A 50% deposit secures your booking, with the balance due 60 days before departure. We accept bank transfer, major credit cards, and cryptocurrency. An APA (Advance Provisioning Allowance) of 30% of the charter fee is typically required to cover provisions, fuel, and marina fees.',
            '_oc_sort_order' => 8,
        ],
        'category' => 'Pricing',
    ],
];

foreach ( $faqs as $f ) {
    $id = oc_seed_post( 'oc_faq', $f['title'], '', $f['meta'] );
    if ( $id && isset( $faq_cat_ids[ $f['category'] ] ) ) {
        wp_set_object_terms( $id, [ $faq_cat_ids[ $f['category'] ] ], 'oc_faq_category' );
    }
    echo "  + {$f['title']} (ID: {$id})\n";
}

// ===========================================================================
// 8. OFFERS
// ===========================================================================
echo "\n[8/10] Seeding Offers...\n";

$offers = [
    [
        'title'   => 'Early Summer Special',
        'excerpt' => 'Book any 7-day charter in June and receive a complimentary spa day for two.',
        'meta'    => [
            '_oc_subtitle'      => 'June Departures Only',
            '_oc_discount'      => '20%',
            '_oc_discount_type' => 'percent',
            '_oc_valid_from'    => '2026-01-01',
            '_oc_valid_to'      => '2026-04-30',
            '_oc_badge_text'    => 'Early Bird',
            '_oc_cta_url'       => '',
            '_oc_is_featured'   => 1,
            '_oc_image_url'     => $img_sailing,
        ],
    ],
    [
        'title'   => 'Honeymoon Package Upgrade',
        'excerpt' => 'Newlyweds receive a complimentary champagne breakfast, floral arrangement, and sunset cruise extension.',
        'meta'    => [
            '_oc_subtitle'      => 'For 2026 newlyweds',
            '_oc_discount'      => 'Complimentary Upgrade',
            '_oc_discount_type' => 'upgrade',
            '_oc_valid_from'    => '2026-01-01',
            '_oc_valid_to'      => '2026-12-31',
            '_oc_badge_text'    => 'Honeymoon',
            '_oc_cta_url'       => '',
            '_oc_image_url'     => $img_luxury,
        ],
    ],
    [
        'title'   => 'Last-Minute Mediterranean',
        'excerpt' => 'Departures within 30 days attract a significant discount across select vessels.',
        'meta'    => [
            '_oc_subtitle'      => 'Limited Availability',
            '_oc_discount'      => '$3,000 off',
            '_oc_discount_type' => 'fixed',
            '_oc_valid_from'    => '2026-01-01',
            '_oc_valid_to'      => '2026-09-30',
            '_oc_badge_text'    => 'Last Minute',
            '_oc_cta_url'       => '',
            '_oc_is_featured'   => 1,
            '_oc_image_url'     => $img_mediterranean,
        ],
    ],
];

foreach ( $offers as $o ) {
    $id = oc_seed_post( 'oc_offer', $o['title'], $o['excerpt'], $o['meta'] );
    echo "  + {$o['title']} (ID: {$id})\n";
}

// ===========================================================================
// 9. PRESS
// ===========================================================================
echo "\n[9/10] Seeding Press...\n";

$press_items = [
    [
        'title' => 'Condé Nast Traveller',
        'meta'  => [
            '_oc_publication' => 'Condé Nast Traveller',
            '_oc_pub_date'    => '2025-07-15',
            '_oc_quote'       => 'Ocean Charter has redefined what it means to sail in style. Every detail speaks to an obsessive commitment to the guest experience.',
            '_oc_article_url' => '#',
            '_oc_logo_url'    => '',
            '_oc_is_featured' => 1,
        ],
    ],
    [
        'title' => 'Forbes Travel Guide',
        'meta'  => [
            '_oc_publication' => 'Forbes Travel Guide',
            '_oc_pub_date'    => '2025-05-02',
            '_oc_quote'       => 'Five stars. An operation of extraordinary refinement — the gold standard of private yacht charters.',
            '_oc_article_url' => '#',
            '_oc_logo_url'    => '',
            '_oc_is_featured' => 1,
        ],
    ],
    [
        'title' => 'The Sunday Times Travel',
        'meta'  => [
            '_oc_publication' => 'The Sunday Times Travel',
            '_oc_pub_date'    => '2025-09-20',
            '_oc_quote'       => 'Whether it\'s a Santorini sunset or a Hydra harbour breakfast, Ocean Charter makes every moment feel like it was curated specifically for you.',
            '_oc_article_url' => '#',
            '_oc_logo_url'    => '',
        ],
    ],
    [
        'title' => 'Boat International',
        'meta'  => [
            '_oc_publication' => 'Boat International',
            '_oc_pub_date'    => '2024-11-10',
            '_oc_quote'       => 'A charter company that truly understands the modern luxury traveller. Seamless, thoughtful, and genuinely memorable.',
            '_oc_article_url' => '#',
            '_oc_logo_url'    => '',
        ],
    ],
];

foreach ( $press_items as $press ) {
    $id = oc_seed_post( 'oc_press', $press['title'], '', $press['meta'] );
    echo "  + {$press['title']} (ID: {$id})\n";
}

// ===========================================================================
// 10. ITINERARIES (with child oc_itinerary_day posts)
// ===========================================================================
echo "\n[10/10] Seeding Itineraries...\n";

// ---------------------------------------------------------------------------
// Itinerary 1: 7-Day Aegean Odyssey
// ---------------------------------------------------------------------------
$itin1_id = oc_seed_post(
    'oc_itinerary',
    '7-Day Aegean Odyssey',
    'A week-long voyage through the Greek islands with an expert crew and private guide.',
    [
        '_oc_subtitle'      => 'Athens to Athens via the Cyclades',
        '_oc_duration'      => '7 Days',
        '_oc_region'        => 'Greek Islands',
        '_oc_tags'          => 'Sailing,Culture,Gastronomy',
        '_oc_price'         => '$18,500',
        '_oc_price_period'  => 'for 7 days (yacht only)',
        '_oc_price_note'    => 'Price varies by vessel. Includes crew, fuel, and onboard provisions.',
        '_oc_card_title'    => 'Reserve Your Suite',
        '_oc_cta_url'       => '/contact/',
        '_oc_whatsapp'      => '+15551234567',
        '_oc_inclusions'    => json_encode( [
            'Dedicated crew',
            'All meals & beverages',
            'Fuel & marina fees',
            'Water sports equipment',
            'Private guide at Delos',
            '24/7 concierge',
        ] ),
        '_oc_route_stops'   => json_encode( [
            [ 'name' => 'Athens',    'lat' => 37.9838, 'lng' => 23.7275 ],
            [ 'name' => 'Hydra',     'lat' => 37.3489, 'lng' => 23.4620 ],
            [ 'name' => 'Paros',     'lat' => 37.0853, 'lng' => 25.1520 ],
            [ 'name' => 'Delos',     'lat' => 37.3966, 'lng' => 25.2685 ],
            [ 'name' => 'Mykonos',   'lat' => 37.4467, 'lng' => 25.3289 ],
            [ 'name' => 'Santorini', 'lat' => 36.3932, 'lng' => 25.4615 ],
            [ 'name' => 'Athens',    'lat' => 37.9838, 'lng' => 23.7275 ],
        ] ),
        '_oc_image_url'     => $img_santorini,
    ]
);
echo "  + 7-Day Aegean Odyssey (ID: {$itin1_id})\n";

// Day children for Itinerary 1
$aegean_days = [
    [
        'title'    => 'Athens — Piraeus Departure',
        'day'      => 1,
        'location' => 'Athens, Greece',
        'desc'     => 'Your adventure begins in Athens where you board at Piraeus Marina. Champagne welcome, crew briefing, then set sail into the Saronic Gulf. Anchor in a secluded cove for sunset cocktails and dinner under the stars.',
        'activities' => json_encode( [ 'Piraeus Marina boarding', 'Champagne welcome toast', 'Saronic Gulf sailing', 'Sunset cove dinner' ] ),
    ],
    [
        'title'    => 'Santorini — The Caldera',
        'day'      => 2,
        'location' => 'Santorini, Greece',
        'desc'     => 'Arrive at dawn to the world-famous caldera. Tender ashore to Oia for cobbled-lane exploration before a freshly prepared Aegean lunch. Snorkel the volcanic hot springs and watch Santorini\'s legendary sunset from the deck.',
        'activities' => json_encode( [ 'Caldera arrival at dawn', 'Oia village exploration', 'Volcanic hot springs snorkel', 'Private sunset on deck' ] ),
    ],
    [
        'title'    => 'Mykonos — Cosmopolitan Energy',
        'day'      => 3,
        'location' => 'Mykonos, Greece',
        'desc'     => 'Sail north to the iconic windmill peninsula. Stroll Mykonos Town lanes, browse boutiques, and stop for a frappe at Little Venice. Concierge secures a private table at a rooftop beach club.',
        'activities' => json_encode( [ 'Windmill peninsula arrival', 'Little Venice waterfront', 'Private beach club table' ] ),
    ],
    [
        'title'    => 'Delos — Sacred Island',
        'day'      => 4,
        'location' => 'Delos, Greece',
        'desc'     => 'A short sail to the mythological birthplace of Apollo and Artemis. Walk ruins with a private guide. Picnic lunch aboard, then a lazy afternoon of swimming and paddleboarding.',
        'activities' => json_encode( [ 'Private guided ruins tour', 'Avenue of Lions walk', 'Anchored bay swimming' ] ),
    ],
    [
        'title'    => 'Paros — Golden Villages',
        'day'      => 5,
        'location' => 'Paros, Greece',
        'desc'     => 'Anchor off Naoussa village. Your chef sources the day\'s ingredients at the market. Launch jet skis and water toys in the sheltered bay. Dinner al fresco as stars multiply.',
        'activities' => json_encode( [ 'Naoussa village wander', 'Local market sourcing', 'Jet ski & water toy session' ] ),
    ],
    [
        'title'    => 'Hydra — Car-Free Elegance',
        'day'      => 6,
        'location' => 'Hydra, Greece',
        'desc'     => 'Car-free Hydra awaits. Stroll the horseshoe harbour and climb to the monastery. Afternoon spa session aboard. Farewell mezze dinner with chilled Assyrtiko wine.',
        'activities' => json_encode( [ 'Horseshoe harbour stroll', 'Monastery panorama climb', 'Full spa session aboard', 'Farewell mezze dinner' ] ),
    ],
    [
        'title'    => 'Return to Athens',
        'day'      => 7,
        'location' => 'Athens, Greece',
        'desc'     => 'Final morning at sea. Leisurely breakfast on deck watching the Greek coastline. Crew prepares luggage and concierge arranges transfers. Disembark at Piraeus.',
        'activities' => json_encode( [ 'Breakfast on deck', 'Piraeus disembarkation', 'Concierge transfer service' ] ),
    ],
];

foreach ( $aegean_days as $day ) {
    $day_id = oc_seed_post(
        'oc_itinerary_day',
        $day['title'],
        '',
        [
            '_oc_parent_itinerary' => $itin1_id,
            '_oc_day_number'       => $day['day'],
            '_oc_location'         => $day['location'],
            '_oc_description'      => $day['desc'],
            '_oc_activities'       => $day['activities'],
        ]
    );
    // Set post_parent so hierarchy is explicit
    if ( $day_id ) {
        wp_update_post( [ 'ID' => $day_id, 'post_parent' => $itin1_id ] );
    }
    echo "    - Day {$day['day']}: {$day['title']} (ID: {$day_id})\n";
}

// ---------------------------------------------------------------------------
// Itinerary 2: 5-Day Amalfi Coast
// ---------------------------------------------------------------------------
$itin2_id = oc_seed_post(
    'oc_itinerary',
    '5-Day Amalfi Coast',
    'A sun-drenched voyage along Italy\'s most celebrated coastline, from Naples to Positano.',
    [
        '_oc_subtitle'     => 'Naples to Positano',
        '_oc_duration'     => '5 Days',
        '_oc_region'       => 'Amalfi Coast',
        '_oc_tags'         => 'Italy,History,Cuisine',
        '_oc_price'        => '$12,800',
        '_oc_price_period' => 'for 5 days (yacht only)',
        '_oc_cta_url'      => '/contact/',
        '_oc_inclusions'   => json_encode( [
            'Full crew',
            'All meals',
            'Fuel & marina fees',
            'Vespa tour Positano',
            'Private beach access',
        ] ),
        '_oc_route_stops'  => json_encode( [
            [ 'name' => 'Naples',   'lat' => 40.8518, 'lng' => 14.2681 ],
            [ 'name' => 'Capri',    'lat' => 40.5531, 'lng' => 14.2222 ],
            [ 'name' => 'Positano', 'lat' => 40.6281, 'lng' => 14.4850 ],
            [ 'name' => 'Ravello',  'lat' => 40.6491, 'lng' => 14.6119 ],
            [ 'name' => 'Amalfi',   'lat' => 40.6340, 'lng' => 14.6027 ],
        ] ),
        '_oc_image_url'    => $img_mediterranean,
    ]
);
echo "  + 5-Day Amalfi Coast (ID: {$itin2_id})\n";

// Day children for Itinerary 2
$amalfi_days = [
    [
        'title'    => 'Naples Bay & Vesuvius Views',
        'day'      => 1,
        'location' => 'Naples Bay',
        'desc'     => 'Depart Naples at noon, sailing past the volcanic silhouette of Vesuvius. Anchor off Procida island for aperitivo. Your chef prepares a Neapolitan feast aboard.',
        'activities' => json_encode( [ 'Naples departure', 'Procida aperitivo stop', 'Neapolitan feast' ] ),
    ],
    [
        'title'    => 'Capri — La Dolce Vita',
        'day'      => 2,
        'location' => 'Capri',
        'desc'     => 'Morning arrival at the legendary island of Capri. Tender to the Blue Grotto before the crowds arrive. Chairlift to Anacapri for panoramic views. Limoncello tasting at a clifftop terrace.',
        'activities' => json_encode( [ 'Blue Grotto at dawn', 'Anacapri chairlift', 'Limoncello tasting', 'Marina Grande lunch' ] ),
    ],
    [
        'title'    => 'Positano — Pastel Perfection',
        'day'      => 3,
        'location' => 'Positano',
        'desc'     => 'The jewel of the Amalfi Coast. Anchor in the bay and tender ashore to the pastel cliffside village. Shopping, sea-salt gelato, and a private beach reservation.',
        'activities' => json_encode( [ 'Positano tender landing', 'Clifftop village exploration', 'Private beach access', 'Sunset aperitivo' ] ),
    ],
    [
        'title'    => 'Ravello & Amalfi',
        'day'      => 4,
        'location' => 'Amalfi',
        'desc'     => 'A scenic coastal morning sail to the ancient maritime republic of Amalfi. Explore the cathedral and Arab-Norman cloisters. Afternoon drive (arranged by concierge) to Ravello\'s clifftop gardens.',
        'activities' => json_encode( [ 'Amalfi Cathedral visit', 'Arab-Norman Cloister', 'Ravello Villa Rufolo', 'Garden concert' ] ),
    ],
    [
        'title'    => 'Return to Naples',
        'day'      => 5,
        'location' => 'Naples',
        'desc'     => 'Final sunrise over the Tyrrhenian. A slow morning sail back to Naples with a final chef\'s breakfast of sfogliatelle and espresso. Concierge arranges private transfers to hotel or airport.',
        'activities' => json_encode( [ 'Final sunrise sail', 'Neapolitan breakfast', 'Naples transfer coordination' ] ),
    ],
];

foreach ( $amalfi_days as $day ) {
    $day_id = oc_seed_post(
        'oc_itinerary_day',
        $day['title'],
        '',
        [
            '_oc_parent_itinerary' => $itin2_id,
            '_oc_day_number'       => $day['day'],
            '_oc_location'         => $day['location'],
            '_oc_description'      => $day['desc'],
            '_oc_activities'       => $day['activities'],
        ]
    );
    if ( $day_id ) {
        wp_update_post( [ 'ID' => $day_id, 'post_parent' => $itin2_id ] );
    }
    echo "    - Day {$day['day']}: {$day['title']} (ID: {$day_id})\n";
}

// ===========================================================================
// Mark seeded & summary
// ===========================================================================
update_option( 'oc_demo_seeded', '1' );

echo "\n" . str_repeat( '=', 50 ) . "\n";
echo "✓ Ocean Charter demo content seeded successfully.\n";
echo "  Destinations: 6\n";
echo "  Services: 4\n";
echo "  Packages: 4\n";
echo "  Testimonials: 5\n";
echo "  Vessels: 4\n";
echo "  Team Members: 4\n";
echo "  FAQs: 8\n";
echo "  Offers: 3\n";
echo "  Press: 4\n";
echo "  Itineraries: 2 (with 12 days)\n";
echo str_repeat( '=', 50 ) . "\n\n";
