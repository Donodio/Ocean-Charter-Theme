<?php
/**
 * Ocean Charter — Image Seeder
 *
 * Downloads high-quality Pexels photos and assigns them as featured images
 * (and gallery meta) to all seeded Ocean Charter CPT posts.
 *
 * Run via: php -d mysqli.default_socket=... inc/cpt/seed-images.php
 * Safe to re-run — skips posts that already have a thumbnail.
 */
if ( ! defined( 'ABSPATH' ) ) {
    $wp_load = dirname( __DIR__, 5 ) . '/wp-load.php';
    if ( file_exists( $wp_load ) ) require $wp_load;
    else { echo "wp-load.php not found.\n"; exit( 1 ); }
}

// Required for media_sideload_image()
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

// ── Confirmed valid Pexels photo library ─────────────────────────────────────
// All IDs verified via direct HTTP request. w=1920 for heroes, w=800 for cards.
$P = function ( int $id, int $w = 800 ): string {
    return "https://images.pexels.com/photos/{$id}/pexels-photo-{$id}.jpeg?auto=compress&cs=tinysrgb&w={$w}";
};

$imgs = [
    // Yachts & boats
    'motor_yacht'    => $P( 163236,  1920 ),   // white luxury speedboat/motor yacht
    'sailing_large'  => $P( 1118873, 1920 ),   // tall sailing yacht at sea
    'sailing_blue'   => $P( 1430676, 1920 ),   // blue & white sailing yacht
    'sailing_action' => $P( 1001682, 1920 ),   // sailing yacht under full sail
    'catamaran'      => $P( 3699535, 1920 ),   // multi-hull sailing
    'superyacht'     => $P( 2248516, 1920 ),   // large white superyacht
    'yacht_deck'     => $P( 3836440,  800 ),   // yacht deck detail
    'marina'         => $P( 3493777,  800 ),   // yacht in marina
    'yacht_sunset'   => $P( 3225517,  800 ),   // yacht at sunset
    'yacht_luxury'   => $P( 4440206,  800 ),   // luxury yacht on water
    'harbor_aerial'  => $P( 1592461,  800 ),   // harbor/marina aerial

    // Destinations
    'aegean'         => $P( 1285625, 1920 ),   // crystal Aegean waters
    'mediterranean'  => $P( 1010657, 1920 ),   // Mediterranean coast
    'tropical'       => $P( 1705255, 1920 ),   // tropical luxury
    'islands'        => $P( 1268856, 1920 ),   // island archipelago
    'coastal'        => $P( 1533721, 1920 ),   // dramatic coastline
    'sailing_sea'    => $P( 1285624, 1920 ),   // open sea sailing

    // People
    'captain'        => $P( 1181690,  400 ),   // professional portrait (male)
    'chef'           => $P( 887827,   400 ),   // chef at work
    'crew_female'    => $P( 1239291,  400 ),   // professional portrait (female)
];

// ── Helper: sideload + set featured image ────────────────────────────────────
function oc_set_thumbnail( int $post_id, string $url, string $desc ): bool {
    if ( has_post_thumbnail( $post_id ) ) {
        echo "    (already has thumbnail — skipping)\n";
        return false;
    }
    $attach_id = media_sideload_image( $url, $post_id, $desc, 'id' );
    if ( is_wp_error( $attach_id ) ) {
        echo "    ✗ Failed: " . $attach_id->get_error_message() . "\n";
        return false;
    }
    set_post_thumbnail( $post_id, $attach_id );
    return true;
}

// ── Helper: get post ID by title + type ──────────────────────────────────────
function oc_get_id( string $title, string $post_type ): int {
    $posts = get_posts( [ 'post_type' => $post_type, 'title' => $title, 'posts_per_page' => 1, 'post_status' => 'any', 'fields' => 'ids' ] );
    return $posts ? (int) $posts[0] : 0;
}

echo "Ocean Charter — Image Seeder\n";
echo str_repeat( '-', 50 ) . "\n\n";

// ── 1. VESSELS ────────────────────────────────────────────────────────────────
echo "[1/6] Vessels\n";

$vessels = [
    'Aelia'    => [ 'hero' => $imgs['sailing_large'],  'gallery' => [ $imgs['sailing_action'], $imgs['catamaran'],    $imgs['yacht_deck'],   $imgs['marina']       ] ],
    'Poseidon' => [ 'hero' => $imgs['motor_yacht'],    'gallery' => [ $imgs['yacht_luxury'],   $imgs['harbor_aerial'], $imgs['yacht_sunset'], $imgs['marina']       ] ],
    'Zephyr'   => [ 'hero' => $imgs['sailing_blue'],   'gallery' => [ $imgs['catamaran'],      $imgs['sailing_sea'],  $imgs['yacht_deck'],   $imgs['yacht_sunset'] ] ],
    'Empress'  => [ 'hero' => $imgs['superyacht'],     'gallery' => [ $imgs['motor_yacht'],    $imgs['yacht_luxury'], $imgs['harbor_aerial'], $imgs['yacht_deck']  ] ],
];

foreach ( $vessels as $title => $data ) {
    $id = oc_get_id( $title, 'oc_vessel' );
    if ( ! $id ) { echo "  ✗ Not found: {$title}\n"; continue; }
    echo "  {$title} (ID {$id})\n";
    oc_set_thumbnail( $id, $data['hero'], $title . ' — hero' );
    // Gallery meta fields
    foreach ( $data['gallery'] as $i => $url ) {
        $key = '_oc_gallery_' . ( $i + 1 );
        update_post_meta( $id, $key, $url );
    }
    echo "    ✓ Gallery updated\n";
}

// ── 2. DESTINATIONS ───────────────────────────────────────────────────────────
echo "\n[2/6] Destinations\n";

$destinations = [
    'Santorini & the Cyclades' => $imgs['aegean'],
    'Amalfi Coast'             => $imgs['mediterranean'],
    'BVI & Caribbean'          => $imgs['tropical'],
    'Maldives'                 => $imgs['islands'],
    'Norwegian Fjords'         => $imgs['coastal'],
    'Dubrovnik Riviera'        => $imgs['sailing_sea'],
];

foreach ( $destinations as $title => $url ) {
    $id = oc_get_id( $title, 'oc_destination' );
    if ( ! $id ) { echo "  ✗ Not found: {$title}\n"; continue; }
    echo "  {$title} (ID {$id})\n";
    oc_set_thumbnail( $id, $url, $title );
    update_post_meta( $id, '_oc_image_url', $url );
}

// ── 3. SERVICES ───────────────────────────────────────────────────────────────
echo "\n[3/6] Services\n";

$services = [
    'Private Chef'           => $imgs['chef'],
    'Water Sports & Toys'    => $imgs['sailing_blue'],
    'Events & Celebrations'  => $imgs['yacht_sunset'],
    'Concierge Service'      => $imgs['harbor_aerial'],
];

foreach ( $services as $title => $url ) {
    $id = oc_get_id( $title, 'oc_service' );
    if ( ! $id ) { echo "  ✗ Not found: {$title}\n"; continue; }
    echo "  {$title} (ID {$id})\n";
    oc_set_thumbnail( $id, $url, $title );
}

// ── 4. PACKAGES ───────────────────────────────────────────────────────────────
echo "\n[4/6] Packages\n";

$packages = [
    'Sunset Day Charter'      => $imgs['yacht_sunset'],
    'Romantic Weekend Escape' => $imgs['sailing_blue'],
    '7-Day Aegean Odyssey'    => $imgs['aegean'],
    'Corporate Charter'       => $imgs['superyacht'],
];

foreach ( $packages as $title => $url ) {
    $id = oc_get_id( $title, 'oc_package' );
    if ( ! $id ) { echo "  ✗ Not found: {$title}\n"; continue; }
    echo "  {$title} (ID {$id})\n";
    oc_set_thumbnail( $id, $url, $title );
}

// ── 5. TEAM MEMBERS ───────────────────────────────────────────────────────────
echo "\n[5/6] Team Members\n";

$team = [
    'Captain Alex Stavros'     => $imgs['captain'],
    'Chef Maria Konstantinou'  => $imgs['chef'],
    'First Officer Lena Brandt'=> $imgs['crew_female'],
    'James Whitfield'          => $imgs['captain'],
];

foreach ( $team as $title => $url ) {
    $id = oc_get_id( $title, 'oc_team_member' );
    if ( ! $id ) { echo "  ✗ Not found: {$title}\n"; continue; }
    echo "  {$title} (ID {$id})\n";
    oc_set_thumbnail( $id, $url, $title );
}

// ── 6. ITINERARIES ────────────────────────────────────────────────────────────
echo "\n[6/6] Itineraries\n";

$itineraries = [
    '7-Day Aegean Odyssey' => $imgs['aegean'],
    '5-Day Amalfi Coast'   => $imgs['mediterranean'],
];

foreach ( $itineraries as $title => $url ) {
    $id = oc_get_id( $title, 'oc_itinerary' );
    if ( ! $id ) { echo "  ✗ Not found: {$title}\n"; continue; }
    echo "  {$title} (ID {$id})\n";
    oc_set_thumbnail( $id, $url, $title );
}

// ── Itinerary day images ──────────────────────────────────────────────────────
$day_imgs = [
    'Athens — Piraeus Departure'   => [ $imgs['marina'],        $imgs['aegean']       ],
    'Santorini — The Caldera'      => [ $imgs['aegean'],        $imgs['sailing_blue'] ],
    'Mykonos — Cosmopolitan Energy'=> [ $imgs['sailing_action'],$imgs['yacht_sunset'] ],
    'Delos — Sacred Island'        => [ $imgs['mediterranean'], $imgs['sailing_sea']  ],
    'Paros — Golden Villages'      => [ $imgs['islands'],       $imgs['catamaran']    ],
    'Hydra — Car-Free Elegance'    => [ $imgs['coastal'],       $imgs['sailing_large']],
    'Return to Athens'             => [ $imgs['aegean'],        $imgs['motor_yacht']  ],
    'Naples Bay & Vesuvius Views'  => [ $imgs['mediterranean'], $imgs['harbor_aerial']],
    'Capri — La Dolce Vita'        => [ $imgs['aegean'],        $imgs['sailing_blue'] ],
    'Positano — Pastel Perfection' => [ $imgs['mediterranean'], $imgs['yacht_sunset'] ],
    'Ravello & Amalfi'             => [ $imgs['coastal'],       $imgs['sailing_sea']  ],
    'Return to Naples'             => [ $imgs['harbor_aerial'], $imgs['motor_yacht']  ],
];

foreach ( $day_imgs as $title => $urls ) {
    $id = oc_get_id( $title, 'oc_itinerary_day' );
    if ( ! $id ) continue;
    echo "  {$title} (ID {$id})\n";
    oc_set_thumbnail( $id, $urls[0], $title );
    update_post_meta( $id, '_oc_image_a', $urls[0] );
    update_post_meta( $id, '_oc_image_b', $urls[1] );
}

// ── Done ─────────────────────────────────────────────────────────────────────
echo "\n" . str_repeat( '=', 50 ) . "\n";
echo "✓ Image seeding complete.\n";
echo str_repeat( '=', 50 ) . "\n";
