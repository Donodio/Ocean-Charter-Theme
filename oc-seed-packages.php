<?php
/**
 * OC Seed — BBC Package Demo Data
 *
 * Visit this in browser: /wp-content/themes/ocean-charter/oc-seed-packages.php
 * Creates 3 demo BBC packages with full metadata so the package details page
 * and homepage cards have real content to display.
 *
 * Safe to run multiple times — skips if packages already exist.
 */

$wp_load = dirname( __DIR__, 3 ) . '/wp-load.php';
if ( file_exists( $wp_load ) ) {
    require $wp_load;
} else {
    echo "wp-load.php not found.\n"; exit( 1 );
}

if ( ! current_user_can( 'manage_options' ) ) {
    wp_die( 'Admin access required.' );
}

echo '<pre style="font-family:monospace;background:#111;color:#0f0;padding:20px;">';

// Check if packages already exist
$existing = get_posts( [ 'post_type' => 'bbc_package', 'posts_per_page' => 1, 'post_status' => 'any' ] );
if ( ! empty( $existing ) ) {
    echo "BBC packages already exist. Updating metadata on existing packages...\n\n";
    $packages_to_update = get_posts( [ 'post_type' => 'bbc_package', 'posts_per_page' => -1, 'post_status' => 'any' ] );
    foreach ( $packages_to_update as $pkg ) {
        $pid = $pkg->ID;
        // Only fill in missing meta, don't overwrite existing
        if ( ! get_post_meta( $pid, '_bbc_pkg_durations', true ) ) {
            update_post_meta( $pid, '_bbc_pkg_durations', [
                [ 'label' => 'Half Day', 'hours' => 4, 'price' => 1200 ],
                [ 'label' => 'Full Day', 'hours' => 8, 'price' => 2200 ],
            ] );
            echo "  + Added durations to '{$pkg->post_title}'\n";
        }
        if ( ! get_post_meta( $pid, '_bbc_pkg_location', true ) ) {
            update_post_meta( $pid, '_bbc_pkg_location', 'Marina Bay' );
            echo "  + Added location to '{$pkg->post_title}'\n";
        }
        if ( ! get_post_meta( $pid, '_bbc_pkg_max_guests', true ) ) {
            update_post_meta( $pid, '_bbc_pkg_max_guests', 12 );
            update_post_meta( $pid, '_bbc_pkg_min_guests', 2 );
            echo "  + Added guest capacity to '{$pkg->post_title}'\n";
        }
        if ( ! get_post_meta( $pid, '_bbc_pkg_features', true ) ) {
            update_post_meta( $pid, '_bbc_pkg_features', [
                'Professional captain & crew',
                'Fuel & safety equipment',
                'Welcome drinks',
                'Towels & amenities',
            ] );
            echo "  + Added features to '{$pkg->post_title}'\n";
        }
        if ( ! get_post_meta( $pid, '_bbc_pkg_amenities', true ) ) {
            update_post_meta( $pid, '_bbc_pkg_amenities', [ 'catering', 'sound_system', 'snorkeling', 'sun_beds', 'crew_service', 'life_jackets', 'wifi' ] );
            echo "  + Added amenities to '{$pkg->post_title}'\n";
        }
    }
    echo "\nDone updating existing packages.\n";
} else {
    echo "No BBC packages found. Creating 3 demo packages...\n\n";

    $demo_packages = [
        [
            'title'   => 'Sunset Cruise',
            'excerpt' => 'Experience the magic of the golden hour aboard a luxury yacht. Sail along the stunning coastline with premium champagne, gourmet hors d\'oeuvres, and a world-class crew dedicated to making your evening unforgettable.',
            'content' => '<h3>The Ultimate Sunset Experience</h3><p>As the sun dips below the horizon, painting the sky in hues of gold and amber, you\'ll be aboard one of our finest vessels with a glass of premium champagne in hand. Our experienced crew navigates the most scenic coastal routes, ensuring you catch the perfect sunset angle.</p><p>This package is ideal for romantic celebrations, anniversaries, or simply treating yourself to an extraordinary evening on the water.</p>',
            'meta'    => [
                '_bbc_pkg_price'          => 1200,
                '_bbc_pkg_discount'       => 0,
                '_bbc_pkg_label'          => 'Popular',
                '_bbc_pkg_location'       => 'Amalfi Coast, Italy',
                '_bbc_pkg_min_guests'     => 2,
                '_bbc_pkg_max_guests'     => 12,
                '_bbc_pkg_difficulty'     => 'easy',
                '_bbc_pkg_cancellation'   => 'flexible',
                '_bbc_pkg_valid_from'     => '2026-04-01',
                '_bbc_pkg_valid_to'       => '2026-10-31',
                '_bbc_pkg_whats_included' => "Professional captain and crew\nFuel and docking fees\nPremium champagne selection (Veuve Clicquot)\nGourmet hors d'oeuvres by our onboard chef\nSafety equipment & life jackets\nBluetooth sound system\nSoft towels and sunscreen",
                '_bbc_pkg_itinerary'      => "5:30 PM — Board at Marina Bay, welcome champagne\n6:00 PM — Depart along the coastline\n6:30 PM — Pass iconic landmarks, photo opportunities\n7:00 PM — Anchor at sunset point, hors d'oeuvres served\n7:45 PM — Sunset viewing, toast\n8:30 PM — Return to marina",
                '_bbc_pkg_durations'      => [
                    [ 'label' => 'Classic Sunset', 'hours' => 3, 'price' => 1200 ],
                    [ 'label' => 'Extended Evening', 'hours' => 5, 'price' => 1800 ],
                ],
                '_bbc_pkg_features'       => [
                    '3-Hour Coastal Cruise',
                    'Premium Champagne Selection',
                    'Gourmet Hors d\'oeuvres',
                    'Professional Captain & Crew',
                    'Safety Equipment & Insurance',
                    'Bluetooth Sound System',
                ],
                '_bbc_pkg_amenities'      => [ 'champagne', 'catering', 'sound_system', 'bluetooth', 'sun_beds', 'crew_service', 'towels', 'life_jackets', 'gps' ],
            ],
        ],
        [
            'title'   => 'Corporate Events',
            'excerpt' => 'Elevate your brand with a luxury yacht meeting or team-building event. Fully equipped with AV facilities, tailored catering for up to 30 guests, and a dedicated event manager to ensure everything runs smoothly.',
            'content' => '<h3>Corporate Excellence on the Water</h3><p>Transform your next corporate event into an unforgettable experience. Our corporate charter package combines the prestige of a luxury yacht with all the facilities you need for productive meetings, impressive client entertainment, or memorable team celebrations.</p><p>State-of-the-art AV equipment, high-speed Wi-Fi, and flexible deck spaces ensure your event flows seamlessly from presentations to networking over gourmet cuisine.</p>',
            'meta'    => [
                '_bbc_pkg_price'          => 4800,
                '_bbc_pkg_discount'       => 10,
                '_bbc_pkg_label'          => 'Signature',
                '_bbc_pkg_location'       => 'Monaco Harbor',
                '_bbc_pkg_min_guests'     => 8,
                '_bbc_pkg_max_guests'     => 30,
                '_bbc_pkg_difficulty'     => 'easy',
                '_bbc_pkg_cancellation'   => 'moderate',
                '_bbc_pkg_valid_from'     => '2026-03-01',
                '_bbc_pkg_valid_to'       => '2026-12-31',
                '_bbc_pkg_whats_included' => "Full-day yacht charter (8 hours)\nDedicated event manager\nState-of-the-art AV facilities (projector, screen, sound)\nHigh-speed satellite Wi-Fi\nTailored catering menu by executive chef\nPremium beverage packages\nProfessional photography\nCustom branding & signage available",
                '_bbc_pkg_itinerary'      => "9:00 AM — Guests arrive, welcome reception\n9:30 AM — Depart marina, morning session\n12:00 PM — Gourmet lunch served on deck\n1:30 PM — Afternoon activities / presentations\n3:00 PM — Water activities break (optional)\n4:30 PM — Networking reception with canapes\n5:00 PM — Return to marina",
                '_bbc_pkg_durations'      => [
                    [ 'label' => 'Half Day Conference', 'hours' => 4, 'price' => 2800 ],
                    [ 'label' => 'Full Day Event', 'hours' => 8, 'price' => 4800 ],
                    [ 'label' => 'Overnight Retreat', 'hours' => 24, 'price' => 8500 ],
                ],
                '_bbc_pkg_features'       => [
                    'Full-Day Charter (8 Hours)',
                    'State-of-the-Art AV Facilities',
                    'Tailored Catering Menu',
                    'Up to 30 Guests',
                    'Dedicated Event Manager',
                    'Professional Photography',
                    'High-Speed Wi-Fi',
                ],
                '_bbc_pkg_amenities'      => [ 'catering', 'open_bar', 'meals_included', 'sound_system', 'projector', 'wifi', 'ac', 'crew_service', 'life_jackets', 'first_aid' ],
            ],
        ],
        [
            'title'   => 'Birthday Celebration',
            'excerpt' => 'Celebrate your special day on the water with a curated party atmosphere. Professional DJ, custom party décor, bespoke beverage packages, and an unforgettable setting that will have your guests talking for years.',
            'content' => '<h3>Your Birthday, Elevated</h3><p>Forget ordinary venues — celebrate your milestone birthday aboard a luxury yacht. Our birthday celebration package includes everything needed for an extraordinary party: professional DJ, stunning décor tailored to your theme, premium open bar, and gourmet catering.</p><p>Whether it\'s an intimate gathering of close friends or a grand celebration, our experienced event team ensures every detail is perfect. Optional add-ons include fireworks, a professional photographer, and custom cake service.</p>',
            'meta'    => [
                '_bbc_pkg_price'          => 3200,
                '_bbc_pkg_discount'       => 0,
                '_bbc_pkg_label'          => 'Celebration',
                '_bbc_pkg_location'       => 'Ibiza, Spain',
                '_bbc_pkg_min_guests'     => 4,
                '_bbc_pkg_max_guests'     => 20,
                '_bbc_pkg_difficulty'     => 'easy',
                '_bbc_pkg_cancellation'   => 'flexible',
                '_bbc_pkg_valid_from'     => '2026-04-01',
                '_bbc_pkg_valid_to'       => '2026-11-30',
                '_bbc_pkg_whats_included' => "5-hour luxury yacht charter\nProfessional DJ with premium sound system\nCustom party decorations (theme of your choice)\nBespoke beverage packages (cocktails, champagne, soft drinks)\nGourmet party catering\nLED lighting & ambiance\nComplimentary birthday cake\nParty props & accessories",
                '_bbc_pkg_itinerary'      => "4:00 PM — Guests arrive, welcome cocktails\n4:30 PM — Depart marina, party begins\n5:30 PM — Water toys & swimming break\n6:30 PM — Dinner service on deck\n7:30 PM — Birthday cake & champagne toast\n8:00 PM — DJ set & dancing under the stars\n9:00 PM — Return to marina",
                '_bbc_pkg_durations'      => [
                    [ 'label' => 'Afternoon Party', 'hours' => 4, 'price' => 2400 ],
                    [ 'label' => 'Sunset Celebration', 'hours' => 5, 'price' => 3200 ],
                    [ 'label' => 'All-Night Party', 'hours' => 8, 'price' => 5000 ],
                ],
                '_bbc_pkg_features'       => [
                    '5-Hour Charter',
                    'Professional DJ',
                    'Custom Party Décor',
                    'Bespoke Beverage Packages',
                    'Photographer Available',
                    'Complimentary Birthday Cake',
                ],
                '_bbc_pkg_amenities'      => [ 'catering', 'open_bar', 'champagne', 'dj', 'sound_system', 'bluetooth', 'snorkeling', 'paddleboard', 'sun_beds', 'crew_service', 'towels', 'life_jackets' ],
            ],
        ],
    ];

    foreach ( $demo_packages as $pkg ) {
        $post_id = wp_insert_post( [
            'post_type'    => 'bbc_package',
            'post_title'   => $pkg['title'],
            'post_excerpt' => $pkg['excerpt'],
            'post_content' => $pkg['content'],
            'post_status'  => 'publish',
        ] );

        if ( is_wp_error( $post_id ) ) {
            echo "✗ Failed to create '{$pkg['title']}': {$post_id->get_error_message()}\n";
            continue;
        }

        foreach ( $pkg['meta'] as $key => $value ) {
            update_post_meta( $post_id, $key, $value );
        }

        echo "✓ Created '{$pkg['title']}' (ID {$post_id}) with full metadata\n";
    }

    echo "\nDone! 3 demo packages created.\n";
}

// Flush rewrite rules so bbc_package URLs work
flush_rewrite_rules();
echo "\n✓ Rewrite rules flushed.\n";

echo "\nVisit the homepage to see package cards, or click a package to see the details page.\n";
echo '</pre>';
