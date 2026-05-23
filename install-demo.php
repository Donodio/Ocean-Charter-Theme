<?php
/**
 * Ocean Charter - Full Stitch Demo Installer
 *
 * Installs all demo pages, menus, yachts, settings, and options
 * faithfully matching the "LUXURY YACHT RENTAL - A" Stitch design.
 *
 * USAGE: drop this file into /wp-content/themes/ocean-charter/
 * then visit ?ocean_charter_install_demo=1&nonce=YOUR_NONCE
 * OR run directly via WP-CLI: wp eval-file install-demo.php
 *
 * @package OceanCharter
 */

/* ── Safety check ─────────────────────────────────────────────────────────── */
if ( ! defined( 'ABSPATH' ) ) {
	// Allow running from CLI or direct include.
	require_once dirname( __DIR__, 3 ) . '/wp-load.php';
}

if ( ! current_user_can( 'manage_options' ) && ! defined( 'WP_CLI' ) ) {
	wp_die( 'Unauthorized access.' );
}

$results = array();

/* ════════════════════════════════════════════════════════════════════════════
   1.  DEMO YACHTS
   ════════════════════════════════════════════════════════════════════════════ */
$demo_yachts = array(
	array(
		'title'   => 'The Azure Muse',
		'content' => "The Azure Muse represents the pinnacle of performance and luxury. Designed for those who demand both speed and sophistication, this vessel offers an expansive open-air experience with a retractable carbon fiber roof that invites the Mediterranean sun into the heart of the vessel.\n\nEvery inch of the interior has been crafted by award-winning designers, combining hand-stitched Italian leather with exotic woods and polished stainless steel accents. The result is an environment that feels as exclusive as a five-star hotel suite — yet moves with breathtaking speed across the water.",
		'excerpt' => 'A Predator 74 at its finest. Designed for those who demand both speed and sophistication.',
		'meta'    => array( '_bbc_length' => '74', '_bbc_guests' => '10', '_bbc_cabins' => '4', '_bbc_location' => 'Palma, Mallorca', '_bbc_price_half_day' => '7900', '_bbc_year' => '2020', '_bbc_builder' => 'Sunseeker', '_bbc_max_speed' => '28', '_bbc_crew' => '4', '_bbc_beam' => '5.4', '_boat_captain_name' => 'Capt. Marcus Sterling', '_boat_captain_bio' => 'With over 15 years of experience navigating the French Riviera and Amalfi Coast, Marcus ensures your journey is as safe as it is spectacular.' ),
	),
	array(
		'title'   => 'The Azure Sovereign',
		'content' => "A masterpiece of naval architecture combining raw performance with palatial comfort. The Azure Sovereign dominates every anchorage and marina she enters — a true superyacht presence in a motor yacht format.\n\nThe main saloon features 3-metre floor-to-ceiling panoramic windows, a dedicated cinema room, and an on-deck Jacuzzi overlooking the sea. Accommodation for 12 guests across 5 staterooms, each individually designed.",
		'excerpt' => 'Superyacht presence in a motor yacht format. 92ft of raw performance and palatial comfort.',
		'meta'    => array( '_bbc_length' => '92', '_bbc_guests' => '12', '_bbc_cabins' => '5', '_bbc_location' => 'Monaco, France', '_bbc_price_half_day' => '12500', '_bbc_year' => '2023', '_bbc_builder' => 'Sunseeker', '_bbc_max_speed' => '26', '_bbc_crew' => '6', '_bbc_beam' => '6.2' ),
	),
	array(
		'title'   => 'Midnight Serenity',
		'content' => "With her distinctive obsidian hull and interior curated by a Renzo Piano Studio alumnus, Midnight Serenity is the most photographed vessel in the Côte d'Azur.\n\nThe expansive beach club at stern level converts in minutes to a fully equipped spa and wellness terrace. An unparalleled place to begin or end a day in the Mediterranean.",
		'excerpt' => 'Distinctive obsidian hull, expansive beach club, and an interior by Renzo Piano Studio alumnus.',
		'meta'    => array( '_bbc_length' => '78', '_bbc_guests' => '10', '_bbc_cabins' => '4', '_bbc_location' => 'Cannes, France', '_bbc_price_half_day' => '9800', '_bbc_year' => '2022', '_bbc_builder' => 'Ferretti', '_bbc_max_speed' => '24', '_bbc_crew' => '5', '_bbc_beam' => '5.8' ),
	),
	array(
		'title'   => 'Golden Horizon',
		'content' => "Warm teak decks, rich mahogany interiors, and a sun-kissed Mediterranean soul define the Golden Horizon experience. Perfect for intimate groups who appreciate warmth over ostentation.\n\nThe generous cockpit and fly bridge provide multiple social settings for entertaining, while the forward sun deck creates a private sanctuary away from the world.",
		'excerpt' => 'Warm teak decks and Mediterranean soul. Perfect for intimate groups of up to 8 guests.',
		'meta'    => array( '_bbc_length' => '68', '_bbc_guests' => '8', '_bbc_cabins' => '3', '_bbc_location' => 'Ibiza, Spain', '_bbc_price_half_day' => '6200', '_bbc_year' => '2021', '_bbc_builder' => 'Princess Yachts', '_bbc_max_speed' => '22', '_bbc_crew' => '3', '_bbc_beam' => '5.0' ),
	),
	array(
		'title'   => 'The Obsidian Edge',
		'content' => "Sharp bow, aggressive stance, obsidian hull — The Obsidian Edge is a vessel that demands attention. A Riva superyacht born from the racing pedigree that made the Italian brand legendary.\n\nBelow decks, the interior is a study in contemporary Italian design: Nero Marquina marble, brushed brass, and hand-laid carbon fibre surfaces throughout. This is a yacht for those who refuse to be ordinary.",
		'excerpt' => 'Sharp lines, obsidian hull, and relentless speed. A yacht that turns heads in every marina.',
		'meta'    => array( '_bbc_length' => '82', '_bbc_guests' => '10', '_bbc_cabins' => '4', '_bbc_location' => 'Cannes, France', '_bbc_price_half_day' => '10400', '_bbc_year' => '2023', '_bbc_builder' => 'Riva', '_bbc_max_speed' => '30', '_bbc_crew' => '5', '_bbc_beam' => '5.6' ),
	),
	array(
		'title'   => 'Silver Serenity',
		'content' => "Silver Serenity redefines the art of gentle living at sea. At 92 feet, she carries her guests in an atmosphere of hushed luxury — where white-gloved service, Michelin-starred menus, and curated art collections are simply part of the itinerary.\n\nThe master stateroom spans the full beam of the vessel and opens onto a private balcony. A floating palazzo for those who see the sea as a sanctuary.",
		'excerpt' => 'A superyacht-class experience. Silver Serenity redefines the art of gentle living at sea.',
		'meta'    => array( '_bbc_length' => '92', '_bbc_guests' => '12', '_bbc_cabins' => '5', '_bbc_location' => 'Amalfi Coast, Italy', '_bbc_price_half_day' => '13800', '_bbc_year' => '2022', '_bbc_builder' => 'Benetti', '_bbc_max_speed' => '18', '_bbc_crew' => '6', '_bbc_beam' => '6.8' ),
	),
);

foreach ( $demo_yachts as $yacht_data ) {
	// Avoid duplicates.
	$existing = get_page_by_title( $yacht_data['title'], OBJECT, 'boat' );
	if ( $existing ) {
		$results[] = "Yacht already exists: {$yacht_data['title']}";
		continue;
	}
	$post_id = wp_insert_post( array(
		'post_title'   => $yacht_data['title'],
		'post_content' => $yacht_data['content'],
		'post_excerpt' => $yacht_data['excerpt'],
		'post_status'  => 'publish',
		'post_type'    => 'boat',
	) );
	if ( ! is_wp_error( $post_id ) ) {
		foreach ( $yacht_data['meta'] as $key => $val ) {
			update_post_meta( $post_id, $key, $val );
		}
		// Also add amenities meta.
		$amenities = 'Jacuzzi on Sundeck, Fully-Equipped Gym, Salon & Dining Area, Retractable Sun Roof, High-Speed WiFi, Dolby Atmos Sound System, Jet Ski & Water Toys, Beach Club Platform, Air Conditioning Throughout, Professional Chef Available';
		update_post_meta( $post_id, '_bbc_amenities', $amenities );
		$results[] = "Created yacht: {$yacht_data['title']} (ID: $post_id)";
	} else {
		$results[] = "Error creating yacht: {$yacht_data['title']} — " . $post_id->get_error_message();
	}
}

/* ════════════════════════════════════════════════════════════════════════════
   2.  DEMO PAGES
   ════════════════════════════════════════════════════════════════════════════ */
$demo_pages = array(
	array(
		'title'    => 'Home',
		'slug'     => 'home',
		'template' => '',  // front-page.php is used automatically
		'content'  => '<!-- Front page content managed by front-page.php template -->',
	),
	array(
		'title'    => 'Our Fleet',
		'slug'     => 'fleet',
		'template' => '',   // archive-boat.php handles this
		'content'  => '<!-- Fleet content managed by archive-boat.php template -->',
	),
	array(
		'title'    => 'Services',
		'slug'     => 'services',
		'template' => 'page-services.php',
		'content'  => '<!-- Content managed by page-services.php template -->',
	),
	array(
		'title'    => 'Destinations',
		'slug'     => 'destinations',
		'template' => 'page-destinations.php',
		'content'  => '<!-- Content managed by page-destinations.php template -->',
	),
	array(
		'title'    => 'Packages',
		'slug'     => 'packages',
		'template' => 'page-packages.php',
		'content'  => '<!-- Content managed by page-packages.php template -->',
	),
	array(
		'title'    => 'Contact',
		'slug'     => 'contact',
		'template' => 'page-contact.php',
		'content'  => '<!-- Content managed by page-contact.php template -->',
	),
);

$page_ids = array();
foreach ( $demo_pages as $page_data ) {
	$existing = get_page_by_path( $page_data['slug'] );
	if ( $existing ) {
		$page_ids[ $page_data['slug'] ] = $existing->ID;
		// Update the page template if needed.
		if ( ! empty( $page_data['template'] ) ) {
			update_post_meta( $existing->ID, '_wp_page_template', $page_data['template'] );
		}
		$results[] = "Page already exists (updated template): {$page_data['title']} (ID: {$existing->ID})";
		continue;
	}
	$post_id = wp_insert_post( array(
		'post_title'   => $page_data['title'],
		'post_name'    => $page_data['slug'],
		'post_content' => $page_data['content'],
		'post_status'  => 'publish',
		'post_type'    => 'page',
	) );
	if ( ! is_wp_error( $post_id ) ) {
		if ( ! empty( $page_data['template'] ) ) {
			update_post_meta( $post_id, '_wp_page_template', $page_data['template'] );
		}
		$page_ids[ $page_data['slug'] ] = $post_id;
		$results[] = "Created page: {$page_data['title']} (ID: $post_id)";
	} else {
		$results[] = "Error creating page: {$page_data['title']} — " . $post_id->get_error_message();
	}
}

/* ════════════════════════════════════════════════════════════════════════════
   3.  READING SETTINGS — Set front page and posts page
   ════════════════════════════════════════════════════════════════════════════ */
if ( isset( $page_ids['home'] ) ) {
	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $page_ids['home'] );
	$results[] = "Set front page to Home (ID: {$page_ids['home']})";
}

/* ════════════════════════════════════════════════════════════════════════════
   4.  NAVIGATION MENUS
   ════════════════════════════════════════════════════════════════════════════ */

// Primary navigation menu
$primary_menu_name = 'Primary Navigation';
$primary_menu_id   = wp_get_nav_menu_object( $primary_menu_name );
if ( ! $primary_menu_id ) {
	$primary_menu_id = wp_create_nav_menu( $primary_menu_name );
}
if ( ! is_wp_error( $primary_menu_id ) ) {
	$menu_id = is_object( $primary_menu_id ) ? $primary_menu_id->term_id : $primary_menu_id;

	// Clear existing items first.
	$existing_items = wp_get_nav_menu_items( $menu_id );
	if ( $existing_items ) {
		foreach ( $existing_items as $item ) {
			wp_delete_post( $item->ID, true );
		}
	}

	// Add menu items.
	$menu_items = array(
		array( 'title' => 'Home',         'url' => home_url( '/' ),           'order' => 1 ),
		array( 'title' => 'The Fleet',    'url' => get_post_type_archive_link( 'boat' ) ?: home_url( '/fleet' ), 'order' => 2 ),
		array( 'title' => 'Services',     'url' => isset( $page_ids['services'] ) ? get_permalink( $page_ids['services'] ) : home_url( '/services' ), 'order' => 3 ),
		array( 'title' => 'Destinations', 'url' => isset( $page_ids['destinations'] ) ? get_permalink( $page_ids['destinations'] ) : home_url( '/destinations' ), 'order' => 4 ),
		array( 'title' => 'Packages',     'url' => isset( $page_ids['packages'] ) ? get_permalink( $page_ids['packages'] ) : home_url( '/packages' ), 'order' => 5 ),
		array( 'title' => 'Contact',      'url' => isset( $page_ids['contact'] ) ? get_permalink( $page_ids['contact'] ) : home_url( '/contact' ), 'order' => 6 ),
	);

	foreach ( $menu_items as $item ) {
		wp_update_nav_menu_item( $menu_id, 0, array(
			'menu-item-title'  => $item['title'],
			'menu-item-url'    => $item['url'],
			'menu-item-status' => 'publish',
			'menu-item-position' => $item['order'],
		) );
	}

	// Assign menu to location.
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	$locations['primary'] = $menu_id;
	set_theme_mod( 'nav_menu_locations', $locations );

	$results[] = "Primary menu created/updated (ID: {$menu_id})";
}

// Book Now CTA menu (header button).
$cta_menu_name = 'Header CTA';
$cta_menu_id   = wp_get_nav_menu_object( $cta_menu_name );
if ( ! $cta_menu_id ) {
	$cta_menu_id = wp_create_nav_menu( $cta_menu_name );
}
if ( ! is_wp_error( $cta_menu_id ) ) {
	$cta_id = is_object( $cta_menu_id ) ? $cta_menu_id->term_id : $cta_menu_id;
	wp_update_nav_menu_item( $cta_id, 0, array(
		'menu-item-title'    => 'Book a Charter',
		'menu-item-url'      => isset( $page_ids['contact'] ) ? get_permalink( $page_ids['contact'] ) : home_url( '/contact' ),
		'menu-item-status'   => 'publish',
		'menu-item-position' => 1,
	) );
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	$locations['header_cta'] = $cta_id;
	set_theme_mod( 'nav_menu_locations', $locations );
	$results[] = "Header CTA menu created/updated (ID: {$cta_id})";
}

/* ════════════════════════════════════════════════════════════════════════════
   5.  THEME OPTIONS / CUSTOMIZER SETTINGS
   ════════════════════════════════════════════════════════════════════════════ */
set_theme_mod( 'ocean_charter_company_name',  'Ocean Charter' );
set_theme_mod( 'ocean_charter_tagline',       'Define Your Horizon' );
set_theme_mod( 'ocean_charter_phone',         '+377 99 99 00 00' );
set_theme_mod( 'ocean_charter_email',         'concierge@oceancharter.com' );
set_theme_mod( 'ocean_charter_address',       '7 Quai Antoine 1er, 98000 Monaco' );
set_theme_mod( 'ocean_charter_whatsapp',      'https://wa.me/377000000' );
set_theme_mod( 'ocean_charter_instagram',     'https://instagram.com/' );
set_theme_mod( 'ocean_charter_facebook',      'https://facebook.com/' );

// Update blog description.
update_option( 'blogdescription', 'Define Your Horizon — Luxury Yacht Charter' );

$results[] = 'Theme options set.';

/* ════════════════════════════════════════════════════════════════════════════
   6.  FLUSH REWRITE RULES
   ════════════════════════════════════════════════════════════════════════════ */
flush_rewrite_rules();
$results[] = 'Rewrite rules flushed.';

/* ════════════════════════════════════════════════════════════════════════════
   7.  OUTPUT REPORT
   ════════════════════════════════════════════════════════════════════════════ */
if ( defined( 'WP_CLI' ) && WP_CLI ) {
	foreach ( $results as $msg ) {
		WP_CLI::log( $msg );
	}
	WP_CLI::success( 'Ocean Charter demo installed successfully!' );
} else {
	echo '<!DOCTYPE html><html><body style="font-family:monospace;padding:40px;background:#0a1628;color:#aaa;">';
	echo '<h1 style="color:#d9b230;font-family:Georgia,serif;font-weight:400;">Ocean Charter Demo Installer</h1>';
	echo '<ul style="line-height:2;">';
	foreach ( $results as $msg ) {
		$icon = strpos( $msg, 'Error' ) !== false ? '❌' : '✓';
		$clr  = strpos( $msg, 'Error' ) !== false ? '#e55' : '#6fba6f';
		echo "<li style='color:{$clr};'>{$icon} " . esc_html( $msg ) . '</li>';
	}
	echo '</ul>';
	echo '<p style="margin-top:30px;"><a href="' . esc_url( home_url( '/' ) ) . '" style="color:#d9b230;font-size:16px;">← View Your Site</a></p>';
	echo '</body></html>';
}
