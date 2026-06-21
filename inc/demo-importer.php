<?php
/**
 * Ocean Charter — Self-contained Demo Content Importer
 *
 * Replaces the previous One Click Demo Import (OCDI) / WXR flow, which shipped a
 * stale export with dead `*.local` image URLs and half-deleted media. This builds
 * the demo programmatically — no external plugin, no WXR, no broken links.
 *
 * Images are sideloaded best-effort from the stable Pexels CDN registry
 * (inc/pexels-images.php). If a download fails (e.g. no internet during import),
 * the front-end templates already fall back to the same OC_IMG_* constants, so
 * the demo never looks empty.
 *
 * Entry point: Appearance → Import Demo.
 * CLI: `wp eval-file install-demo.php` also routes through oc_run_demo_import().
 *
 * @package OceanCharter
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/* ── Admin page (Appearance → Import Demo) ──────────────────────────────────── */

add_action( 'admin_menu', function () {
    add_theme_page(
        __( 'Import Demo Content', 'ocean-charter' ),
        __( 'Import Demo', 'ocean-charter' ),
        'manage_options',
        'oc-demo-import',
        'oc_demo_import_render_page'
    );
} );

function oc_demo_import_render_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $results = get_transient( 'oc_demo_import_results' );
    if ( $results ) {
        delete_transient( 'oc_demo_import_results' );
    }

    $plugin_ready = post_type_exists( 'boat' ) && post_type_exists( 'bbc_package' );
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Import Ocean Charter Demo Content', 'ocean-charter' ); ?></h1>

        <?php if ( ! $plugin_ready ) : ?>
            <div class="notice notice-warning">
                <p><strong><?php esc_html_e( 'The Boat Booking Core plugin is not active.', 'ocean-charter' ); ?></strong>
                <?php esc_html_e( 'Yachts and packages need it. Activate it first, then run the import — pages, menus and settings will still import without it.', 'ocean-charter' ); ?></p>
            </div>
        <?php endif; ?>

        <p style="max-width:640px;">
            <?php esc_html_e( 'This creates the full demo: yachts, pages, packages, navigation menus, the front page, and theme settings. Demo images are downloaded from a stable image CDN, so this needs an internet connection and may take a minute. Running it again is safe — existing items are skipped.', 'ocean-charter' ); ?>
        </p>

        <?php if ( is_array( $results ) ) : ?>
            <h2><?php esc_html_e( 'Import results', 'ocean-charter' ); ?></h2>
            <ul style="line-height:1.8;">
                <?php foreach ( $results as $line ) :
                    $is_err = stripos( $line, 'error' ) !== false;
                    ?>
                    <li style="color:<?php echo $is_err ? '#b32d2e' : '#1a7f37'; ?>;">
                        <?php echo $is_err ? '&#10007;' : '&#10003;'; ?> <?php echo esc_html( $line ); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <p><a class="button" href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank"><?php esc_html_e( 'View site', 'ocean-charter' ); ?></a></p>
        <?php endif; ?>

        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="margin-top:24px;">
            <input type="hidden" name="action" value="oc_demo_import">
            <?php wp_nonce_field( 'oc_demo_import' ); ?>
            <?php submit_button( __( 'Import Demo Content', 'ocean-charter' ), 'primary large' ); ?>
        </form>
    </div>
    <?php
}

/* ── POST handler ───────────────────────────────────────────────────────────── */

add_action( 'admin_post_oc_demo_import', function () {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'You do not have permission to import demo content.', 'ocean-charter' ) );
    }
    check_admin_referer( 'oc_demo_import' );

    @set_time_limit( 0 );
    if ( function_exists( 'wp_raise_memory_limit' ) ) {
        wp_raise_memory_limit( 'image' );
    }

    $results = oc_run_demo_import();
    set_transient( 'oc_demo_import_results', $results, 120 );

    wp_safe_redirect( add_query_arg(
        array( 'page' => 'oc-demo-import', 'imported' => 1 ),
        admin_url( 'themes.php' )
    ) );
    exit;
} );

/* ── Helpers ────────────────────────────────────────────────────────────────── */

/**
 * Find a post by exact title without the deprecated get_page_by_title().
 */
function oc_demo_find_post_by_title( string $title, string $post_type ): int {
    $found = get_posts( array(
        'post_type'        => $post_type,
        'title'            => $title,
        'post_status'      => 'any',
        'numberposts'      => 1,
        'fields'           => 'ids',
        'suppress_filters' => false,
    ) );

    return $found ? (int) $found[0] : 0;
}

/**
 * Remove all Elementor meta from a post so the theme's PHP template renders
 * instead of an old saved Elementor layout. Idempotent.
 */
function oc_demo_strip_elementor( int $post_id ): void {
    global $wpdb;
    $keys = $wpdb->get_col( $wpdb->prepare(
        "SELECT meta_key FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key LIKE %s",
        $post_id,
        $wpdb->esc_like( '_elementor' ) . '%'
    ) );
    foreach ( $keys as $key ) {
        delete_post_meta( $post_id, $key );
    }
}

/**
 * Sideload an image URL into the media library and return its attachment ID.
 * Returns 0 on failure (templates fall back to the OC_IMG_* constant).
 */
function oc_demo_sideload_image( string $url, int $parent_id = 0, string $alt = '' ): int {
    if ( ! $url ) {
        return 0;
    }

    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    $att_id = media_sideload_image( $url, $parent_id, $alt, 'id' );

    if ( is_wp_error( $att_id ) || ! $att_id ) {
        return 0;
    }

    if ( $alt ) {
        update_post_meta( $att_id, '_wp_attachment_image_alt', sanitize_text_field( $alt ) );
    }

    return (int) $att_id;
}

/* ── The importer ───────────────────────────────────────────────────────────── */

/**
 * Run the full demo import. Idempotent. Returns an array of log lines.
 *
 * @return string[]
 */
function oc_run_demo_import(): array {
    $results = array();

    /* 1. YACHTS (boat CPT) — matches front-page.php queries + _bbc_* meta. */
    $vessel_imgs = array(
        defined( 'OC_IMG_VESSEL_1' ) ? OC_IMG_VESSEL_1 : '',
        defined( 'OC_IMG_VESSEL_2' ) ? OC_IMG_VESSEL_2 : '',
        defined( 'OC_IMG_VESSEL_3' ) ? OC_IMG_VESSEL_3 : '',
        defined( 'OC_IMG_VESSEL_4' ) ? OC_IMG_VESSEL_4 : '',
        defined( 'OC_IMG_VESSEL_5' ) ? OC_IMG_VESSEL_5 : '',
        defined( 'OC_IMG_VESSEL_6' ) ? OC_IMG_VESSEL_6 : '',
    );

    $amenities = 'Jacuzzi on Sundeck, Fully-Equipped Gym, Salon & Dining Area, Retractable Sun Roof, High-Speed WiFi, Dolby Atmos Sound System, Jet Ski & Water Toys, Beach Club Platform, Air Conditioning Throughout, Professional Chef Available';

    // Boat data uses the meta keys the current templates read:
    //   page-fleet.php / archive-boat.php / single-boat.php look up
    //   _bbc_boat_type, _bbc_condition, _bbc_price_hour|day|week,
    //   _bbc_year_built, _bbc_bathrooms, _bbc_berths,
    //   _bbc_captain_included, _bbc_fuel_included.
    // The older _bbc_year and _bbc_price_half_day keys are kept alongside
    // because single-boat.php still falls back to them for related boats
    // and for pages built before the template rename.
    $demo_yachts = array(
        array(
            'title'   => 'The Azure Muse',
            'content' => "The Azure Muse represents the pinnacle of performance and luxury. Designed for those who demand both speed and sophistication, this vessel offers an expansive open-air experience with a retractable carbon fiber roof that invites the Mediterranean sun into the heart of the vessel.\n\nEvery inch of the interior has been crafted by award-winning designers, combining hand-stitched Italian leather with exotic woods and polished stainless steel accents.",
            'excerpt' => 'A Predator 74 at its finest. Designed for those who demand both speed and sophistication.',
            'meta'    => array(
                '_bbc_length'              => '74',
                '_bbc_max_guests'          => '10',
                '_bbc_guests'              => '10',
                '_bbc_cabins'              => '4',
                '_bbc_bathrooms'           => '4',
                '_bbc_berths'              => '8',
                '_bbc_location'            => 'Palma, Mallorca',
                '_bbc_boat_type'           => 'motor yacht',
                '_bbc_condition'           => 'like-new',
                '_bbc_price_hour'          => '1450',
                '_bbc_price_half_day'      => '7900',
                '_bbc_price_day'           => '14800',
                '_bbc_price_week'          => '89000',
                '_bbc_year'                => '2020',
                '_bbc_year_built'          => '2020',
                '_bbc_builder'             => 'Sunseeker',
                '_bbc_max_speed'           => '28',
                '_bbc_crew'                => '4',
                '_bbc_beam'                => '5.4',
                '_bbc_captain_included'    => 'yes',
                '_bbc_fuel_included'       => 'no',
                '_boat_captain_name'       => 'Capt. Marcus Sterling',
                '_boat_captain_bio'        => 'With over 15 years navigating the French Riviera and Amalfi Coast, Marcus ensures your journey is as safe as it is spectacular.',
            ),
        ),
        array(
            'title'   => 'The Azure Sovereign',
            'content' => "A masterpiece of naval architecture combining raw performance with palatial comfort. The Azure Sovereign dominates every anchorage and marina she enters — a true superyacht presence in a motor yacht format.\n\nThe main saloon features 3-metre floor-to-ceiling panoramic windows, a dedicated cinema room, and an on-deck Jacuzzi overlooking the sea. Accommodation for 12 guests across 5 staterooms.",
            'excerpt' => 'Superyacht presence in a motor yacht format. 92ft of raw performance and palatial comfort.',
            'meta'    => array(
                '_bbc_length'              => '92',
                '_bbc_max_guests'          => '12',
                '_bbc_guests'              => '12',
                '_bbc_cabins'              => '5',
                '_bbc_bathrooms'           => '5',
                '_bbc_berths'              => '10',
                '_bbc_location'            => 'Monaco, France',
                '_bbc_boat_type'           => 'superyacht',
                '_bbc_condition'           => 'new',
                '_bbc_price_hour'          => '2300',
                '_bbc_price_half_day'      => '12500',
                '_bbc_price_day'           => '23500',
                '_bbc_price_week'          => '141000',
                '_bbc_year'                => '2023',
                '_bbc_year_built'          => '2023',
                '_bbc_builder'             => 'Sunseeker',
                '_bbc_max_speed'           => '26',
                '_bbc_crew'                => '6',
                '_bbc_beam'                => '6.2',
                '_bbc_captain_included'    => 'yes',
                '_bbc_fuel_included'       => 'yes',
            ),
        ),
        array(
            'title'   => 'Midnight Serenity',
            'content' => "With her distinctive obsidian hull and interior curated by a Renzo Piano Studio alumnus, Midnight Serenity is the most photographed vessel in the Côte d'Azur.\n\nThe expansive beach club at stern level converts in minutes to a fully equipped spa and wellness terrace.",
            'excerpt' => 'Distinctive obsidian hull, expansive beach club, and an interior by Renzo Piano Studio alumnus.',
            'meta'    => array(
                '_bbc_length'              => '78',
                '_bbc_max_guests'          => '10',
                '_bbc_guests'              => '10',
                '_bbc_cabins'              => '4',
                '_bbc_bathrooms'           => '4',
                '_bbc_berths'              => '8',
                '_bbc_location'            => 'Cannes, France',
                '_bbc_boat_type'           => 'motor yacht',
                '_bbc_condition'           => 'like-new',
                '_bbc_price_hour'          => '1800',
                '_bbc_price_half_day'      => '9800',
                '_bbc_price_day'           => '18500',
                '_bbc_price_week'          => '110000',
                '_bbc_year'                => '2022',
                '_bbc_year_built'          => '2022',
                '_bbc_builder'             => 'Ferretti',
                '_bbc_max_speed'           => '24',
                '_bbc_crew'                => '5',
                '_bbc_beam'                => '5.8',
                '_bbc_captain_included'    => 'yes',
                '_bbc_fuel_included'       => 'no',
            ),
        ),
        array(
            'title'   => 'Golden Horizon',
            'content' => "Warm teak decks, rich mahogany interiors, and a sun-kissed Mediterranean soul define the Golden Horizon experience. Perfect for intimate groups who appreciate warmth over ostentation.\n\nThe generous cockpit and fly bridge provide multiple social settings for entertaining.",
            'excerpt' => 'Warm teak decks and Mediterranean soul. Perfect for intimate groups of up to 8 guests.',
            'meta'    => array(
                '_bbc_length'              => '68',
                '_bbc_max_guests'          => '8',
                '_bbc_guests'              => '8',
                '_bbc_cabins'              => '3',
                '_bbc_bathrooms'           => '3',
                '_bbc_berths'              => '6',
                '_bbc_location'            => 'Ibiza, Spain',
                '_bbc_boat_type'           => 'motor yacht',
                '_bbc_condition'           => 'like-new',
                '_bbc_price_hour'          => '1150',
                '_bbc_price_half_day'      => '6200',
                '_bbc_price_day'           => '11800',
                '_bbc_price_week'          => '70000',
                '_bbc_year'                => '2021',
                '_bbc_year_built'          => '2021',
                '_bbc_builder'             => 'Princess Yachts',
                '_bbc_max_speed'           => '22',
                '_bbc_crew'                => '3',
                '_bbc_beam'                => '5.0',
                '_bbc_captain_included'    => 'yes',
                '_bbc_fuel_included'       => 'no',
            ),
        ),
        array(
            'title'   => 'The Obsidian Edge',
            'content' => "Sharp bow, aggressive stance, obsidian hull — The Obsidian Edge is a vessel that demands attention. A Riva superyacht born from the racing pedigree that made the Italian brand legendary.\n\nBelow decks, the interior is a study in contemporary Italian design: Nero Marquina marble, brushed brass, and hand-laid carbon fibre surfaces throughout.",
            'excerpt' => 'Sharp lines, obsidian hull, and relentless speed. A yacht that turns heads in every marina.',
            'meta'    => array(
                '_bbc_length'              => '82',
                '_bbc_max_guests'          => '10',
                '_bbc_guests'              => '10',
                '_bbc_cabins'              => '4',
                '_bbc_bathrooms'           => '4',
                '_bbc_berths'              => '8',
                '_bbc_location'            => 'Cannes, France',
                '_bbc_boat_type'           => 'superyacht',
                '_bbc_condition'           => 'new',
                '_bbc_price_hour'          => '1900',
                '_bbc_price_half_day'      => '10400',
                '_bbc_price_day'           => '19500',
                '_bbc_price_week'          => '117000',
                '_bbc_year'                => '2023',
                '_bbc_year_built'          => '2023',
                '_bbc_builder'             => 'Riva',
                '_bbc_max_speed'           => '30',
                '_bbc_crew'                => '5',
                '_bbc_beam'                => '5.6',
                '_bbc_captain_included'    => 'yes',
                '_bbc_fuel_included'       => 'yes',
            ),
        ),
        array(
            'title'   => 'Silver Serenity',
            'content' => "Silver Serenity redefines the art of gentle living at sea. At 92 feet, she carries her guests in an atmosphere of hushed luxury — white-gloved service, Michelin-starred menus, and curated art collections.\n\nThe master stateroom spans the full beam of the vessel and opens onto a private balcony.",
            'excerpt' => 'A superyacht-class experience. Silver Serenity redefines the art of gentle living at sea.',
            'meta'    => array(
                '_bbc_length'              => '92',
                '_bbc_max_guests'          => '12',
                '_bbc_guests'              => '12',
                '_bbc_cabins'              => '5',
                '_bbc_bathrooms'           => '5',
                '_bbc_berths'              => '10',
                '_bbc_location'            => 'Amalfi Coast, Italy',
                '_bbc_boat_type'           => 'superyacht',
                '_bbc_condition'           => 'like-new',
                '_bbc_price_hour'          => '2550',
                '_bbc_price_half_day'      => '13800',
                '_bbc_price_day'           => '26000',
                '_bbc_price_week'          => '156000',
                '_bbc_year'                => '2022',
                '_bbc_year_built'          => '2022',
                '_bbc_builder'             => 'Benetti',
                '_bbc_max_speed'           => '18',
                '_bbc_crew'                => '6',
                '_bbc_beam'                => '6.8',
                '_bbc_captain_included'    => 'yes',
                '_bbc_fuel_included'       => 'yes',
            ),
        ),
    );

    if ( post_type_exists( 'boat' ) ) {
        foreach ( $demo_yachts as $i => $yacht ) {
            $existing_id = oc_demo_find_post_by_title( $yacht['title'], 'boat' );
            $is_new      = false;

            if ( $existing_id ) {
                $post_id = $existing_id;
            } else {
                $post_id = wp_insert_post( array(
                    'post_title'   => $yacht['title'],
                    'post_content' => $yacht['content'],
                    'post_excerpt' => $yacht['excerpt'],
                    'post_status'  => 'publish',
                    'post_type'    => 'boat',
                ) );

                if ( is_wp_error( $post_id ) ) {
                    $results[] = "Error creating yacht {$yacht['title']}: " . $post_id->get_error_message();
                    continue;
                }
                $is_new = true;
            }

            // Always (re)apply meta. Existing boats may have been seeded by an
            // older importer that used `_bbc_year` / `_bbc_price_half_day` only —
            // the current templates read `_bbc_year_built`, `_bbc_price_hour`,
            // `_bbc_price_day`, `_bbc_price_week`, `_bbc_boat_type`,
            // `_bbc_condition`, `_bbc_bathrooms`, `_bbc_berths`, and so on, so
            // those would render empty. Re-applying on every import fills the
            // gap idempotently without clobbering anything the user has hand-
            // edited (we only set keys that exist in our seed array).
            foreach ( $yacht['meta'] as $key => $val ) {
                update_post_meta( $post_id, $key, $val );
            }
            update_post_meta( $post_id, '_bbc_amenities', $amenities );

            // Featured image + gallery — only sideload when missing, so we
            // don't churn the media library on re-imports.
            $has_thumb = (bool) get_post_thumbnail_id( $post_id );
            $att_id    = 0;
            if ( ! $has_thumb ) {
                $att_id = oc_demo_sideload_image( $vessel_imgs[ $i ] ?? '', $post_id, $yacht['title'] );
                if ( $att_id ) {
                    set_post_thumbnail( $post_id, $att_id );
                    update_post_meta( $post_id, '_bbc_gallery', array( $att_id ) );
                }
            }

            $label = $is_new ? 'Created yacht' : 'Yacht refreshed';
            $notes = array();
            $notes[] = 'meta synced';
            if ( ! $is_new && ! $has_thumb && $att_id ) {
                $notes[] = 'image restored';
            } elseif ( $is_new ) {
                $notes[] = $att_id ? 'with image' : 'image fell back to CDN';
            }
            $results[] = "{$label}: {$yacht['title']} (" . implode( ', ', $notes ) . ')';
        }
    } else {
        $results[] = 'Skipped yachts — boat post type not registered (activate Boat Booking Core).';
    }

    /* 2. PAGES + hero images. */
    $demo_pages = array(
        array( 'title' => 'Home',         'slug' => 'home',         'template' => '',                     'hero' => 'OC_IMG_HERO_HOME' ),
        array( 'title' => 'Our Fleet',    'slug' => 'fleet',        'template' => 'page-fleet.php',       'hero' => 'OC_IMG_HERO_FLEET' ),
        array( 'title' => 'Services',     'slug' => 'services',     'template' => 'page-services.php',     'hero' => 'OC_IMG_HERO_SERVICES' ),
        array( 'title' => 'Destinations', 'slug' => 'destinations', 'template' => 'page-destinations.php', 'hero' => 'OC_IMG_HERO_DESTINATIONS' ),
        array( 'title' => 'Packages',     'slug' => 'packages',     'template' => 'page-packages.php',     'hero' => 'OC_IMG_HERO_PACKAGES' ),
        array( 'title' => 'Itinerary',    'slug' => 'itinerary',    'template' => 'page-itinerary.php',    'hero' => 'OC_IMG_HERO_ITINERARY' ),
        array( 'title' => 'Contact',      'slug' => 'contact',      'template' => 'page-contact.php',      'hero' => 'OC_IMG_HERO_CONTACT' ),
    );

    $managed_marker = '<!-- Managed by the Ocean Charter template -->';

    $page_ids = array();
    foreach ( $demo_pages as $page ) {
        $existing = get_page_by_path( $page['slug'] );

        if ( $existing ) {
            $post_id   = (int) $existing->ID;
            $is_new    = false;
            $notes     = array();
        } else {
            $post_id = wp_insert_post( array(
                'post_title'   => $page['title'],
                'post_name'    => $page['slug'],
                'post_content' => $managed_marker,
                'post_status'  => 'publish',
                'post_type'    => 'page',
            ) );

            if ( is_wp_error( $post_id ) ) {
                $results[] = "Error creating page {$page['title']}: " . $post_id->get_error_message();
                continue;
            }
            $is_new = true;
            $notes  = array();
        }

        // Template assignment (skip if the demo entry has no template, e.g. Home).
        if ( ! empty( $page['template'] ) ) {
            update_post_meta( $post_id, '_wp_page_template', $page['template'] );
        }

        // Strip stale Elementor meta so the redesigned PHP template wins. A page
        // left in Elementor "builder" mode renders its old saved layout via
        // the_content() and bypasses the theme template entirely.
        oc_demo_strip_elementor( $post_id );

        // Reset post_content if it still carries stale OCDI HTML — old imports
        // shipped pages with huge inline <style> blocks that aren't rendered by
        // the new templates but confuse anyone editing the page in WP admin.
        if ( ! $is_new ) {
            $current_content = (string) get_post_field( 'post_content', $post_id );
            if ( strpos( $current_content, $managed_marker ) === false ) {
                wp_update_post( array(
                    'ID'           => $post_id,
                    'post_content' => $managed_marker,
                ) );
                $notes[] = 'content reset';
            }
        }

        // Hero image — sideload if missing. Without this, an existing page
        // re-imported after a prior cleanup ends up with no hero, and the
        // template renders an empty hero block. Best-effort: silently skips
        // on download failure and the template's CDN fallback takes over.
        $current_hero = absint( get_post_meta( $post_id, '_oc_hero_image', true ) );
        if ( ! $current_hero ) {
            $hero_url = ! empty( $page['hero'] ) && defined( $page['hero'] ) ? constant( $page['hero'] ) : '';
            $hero_id  = oc_demo_sideload_image( $hero_url, $post_id, $page['title'] . ' hero' );
            if ( $hero_id ) {
                update_post_meta( $post_id, '_oc_hero_image', $hero_id );
                set_post_thumbnail( $post_id, $hero_id );
                $notes[] = 'hero restored';
            }
        }

        $page_ids[ $page['slug'] ] = $post_id;

        $label = $is_new ? 'Created page' : 'Page refreshed';
        $tail  = $notes ? ' (' . implode( ', ', $notes ) . ')' : '';
        $results[] = "{$label}: {$page['title']}{$tail}";
    }

    /* 3. FRONT PAGE. front-page.php renders the home template automatically. */
    if ( isset( $page_ids['home'] ) ) {
        update_option( 'show_on_front', 'page' );
        update_option( 'page_on_front', $page_ids['home'] );
        $results[] = 'Set Home as the front page.';
    }

    /* 4. PACKAGES (bbc_package CPT). */
    $demo_packages = array(
        array(
            'title'   => 'Mediterranean Escape',
            'excerpt' => 'Eight hours along the Amalfi Coast aboard a crewed luxury yacht.',
            'img'     => defined( 'OC_IMG_HERO_PACKAGES' ) ? OC_IMG_HERO_PACKAGES : '',
            'meta'    => array( '_bbc_pkg_price' => '8500', '_bbc_pkg_location' => 'Amalfi Coast', '_bbc_pkg_max_guests' => '12', '_bbc_pkg_durations' => '8 hours', '_bbc_pkg_label' => 'Popular' ),
        ),
        array(
            'title'   => 'Caribbean Odyssey',
            'excerpt' => 'A three-day island-hopping voyage through St. Barts and the Grenadines.',
            'img'     => defined( 'OC_IMG_DEST_CARIBBEAN' ) ? OC_IMG_DEST_CARIBBEAN : '',
            'meta'    => array( '_bbc_pkg_price' => '11200', '_bbc_pkg_location' => 'St. Barts', '_bbc_pkg_max_guests' => '8', '_bbc_pkg_durations' => '3 days', '_bbc_pkg_label' => 'Exclusive' ),
        ),
        array(
            'title'   => 'Aegean Day Charter',
            'excerpt' => 'A six-hour Santorini sunset cruise with chef and water toys.',
            'img'     => defined( 'OC_IMG_DEST_MEDITERRANEAN' ) ? OC_IMG_DEST_MEDITERRANEAN : '',
            'meta'    => array( '_bbc_pkg_price' => '2800', '_bbc_pkg_location' => 'Santorini', '_bbc_pkg_max_guests' => '6', '_bbc_pkg_durations' => '6 hours', '_bbc_pkg_label' => 'New' ),
        ),
    );

    if ( post_type_exists( 'bbc_package' ) ) {
        foreach ( $demo_packages as $pkg ) {
            $existing = oc_demo_find_post_by_title( $pkg['title'], 'bbc_package' );
            if ( $existing ) {
                $results[] = "Package already exists: {$pkg['title']}";
                continue;
            }

            $post_id = wp_insert_post( array(
                'post_title'   => $pkg['title'],
                'post_excerpt' => $pkg['excerpt'],
                'post_content' => $pkg['excerpt'],
                'post_status'  => 'publish',
                'post_type'    => 'bbc_package',
            ) );

            if ( is_wp_error( $post_id ) ) {
                $results[] = "Error creating package {$pkg['title']}: " . $post_id->get_error_message();
                continue;
            }

            foreach ( $pkg['meta'] as $key => $val ) {
                update_post_meta( $post_id, $key, $val );
            }

            $att_id = oc_demo_sideload_image( $pkg['img'], $post_id, $pkg['title'] );
            if ( $att_id ) {
                set_post_thumbnail( $post_id, $att_id );
            }

            $results[] = "Created package: {$pkg['title']}";
        }
    } else {
        $results[] = 'Skipped packages — bbc_package post type not registered.';
    }

    /* 5. MENUS — assign to the locations the theme actually renders (menu-1, footer). */
    $primary_items = array(
        array( 'title' => 'Home',         'url' => home_url( '/' ) ),
        array( 'title' => 'The Fleet',    'url' => get_post_type_archive_link( 'boat' ) ?: home_url( '/fleet/' ) ),
        array( 'title' => 'Services',     'url' => isset( $page_ids['services'] )     ? get_permalink( $page_ids['services'] )     : home_url( '/services/' ) ),
        array( 'title' => 'Destinations', 'url' => isset( $page_ids['destinations'] ) ? get_permalink( $page_ids['destinations'] ) : home_url( '/destinations/' ) ),
        array( 'title' => 'Packages',     'url' => isset( $page_ids['packages'] )     ? get_permalink( $page_ids['packages'] )     : home_url( '/packages/' ) ),
        array( 'title' => 'Contact',      'url' => isset( $page_ids['contact'] )      ? get_permalink( $page_ids['contact'] )      : home_url( '/contact/' ) ),
    );
    $footer_items = array(
        array( 'title' => 'Our Fleet', 'url' => get_post_type_archive_link( 'boat' ) ?: home_url( '/fleet/' ) ),
        array( 'title' => 'Services',  'url' => isset( $page_ids['services'] ) ? get_permalink( $page_ids['services'] ) : home_url( '/services/' ) ),
        array( 'title' => 'Contact',   'url' => isset( $page_ids['contact'] )  ? get_permalink( $page_ids['contact'] )  : home_url( '/contact/' ) ),
    );

    $locations = get_theme_mod( 'nav_menu_locations', array() );

    $primary_id = oc_demo_build_menu( 'Primary Navigation', $primary_items );
    if ( $primary_id ) {
        $locations['menu-1'] = $primary_id;
        $results[]           = 'Primary menu created and assigned.';
    }

    $footer_id = oc_demo_build_menu( 'Footer Menu', $footer_items );
    if ( $footer_id ) {
        $locations['footer'] = $footer_id;
        $results[]           = 'Footer menu created and assigned.';
    }

    set_theme_mod( 'nav_menu_locations', $locations );

    /* 6. THEME OPTIONS. */
    set_theme_mod( 'ocean_charter_company_name', 'Ocean Charter' );
    set_theme_mod( 'ocean_charter_tagline',      'Define Your Horizon' );
    set_theme_mod( 'ocean_charter_phone',        '+377 99 99 00 00' );
    set_theme_mod( 'ocean_charter_email',        'concierge@oceancharter.com' );
    set_theme_mod( 'ocean_charter_address',      '7 Quai Antoine 1er, 98000 Monaco' );
    set_theme_mod( 'ocean_charter_whatsapp',     'https://wa.me/377000000' );
    set_theme_mod( 'ocean_charter_instagram',    'https://instagram.com/' );
    set_theme_mod( 'ocean_charter_facebook',     'https://facebook.com/' );
    update_option( 'blogdescription', 'Define Your Horizon — Luxury Yacht Charter' );
    $results[] = 'Theme options set.';

    /* 7. Flush permalinks for the CPT archives. */
    flush_rewrite_rules();
    $results[] = 'Rewrite rules flushed.';

    /* 8. Flush Boat Booking Core caches. The plugin caches lowest-price and
       blocked-dates per boat via transients (24h / 1h). After a demo re-import
       changes a boat's pricing meta the front-end would otherwise keep serving
       the stale cached value until the transient expires. */
    if ( function_exists( 'bbc_flush_all_caches' ) ) {
        $deleted = bbc_flush_all_caches();
        $results[] = sprintf( 'BBC caches flushed (%d transient rows).', $deleted );
    }

    /* 9. Seed Ocean Charter CPTs. Until now the importer only created BBC posts
       (boat, bbc_package) and the page shells — every OC CPT the theme registers
       (destinations, services, team members, testimonials, vessels, itineraries,
       offers, FAQs, press) was left empty, so the redesigned templates rendered
       half-populated and looked like an older demo. inc/cpt/demo-content*.php and
       seed-images.php have a complete CPT dataset; wire them in here so a single
       import populates the whole theme. */
    foreach ( oc_demo_run_cpt_seeders() as $line ) {
        $results[] = $line;
    }

    return $results;
}

/**
 * Run the three CPT seeders that live in inc/cpt/ and surface their output as
 * importer result lines. Each seeder protects itself with its own
 * `oc_demo_seeded` / `oc_demo_extra_seeded` option; we clear those first so
 * a re-import actually re-runs (the seeders themselves are idempotent —
 * oc_seed_post() reuses existing posts by title).
 *
 * @return string[]
 */
function oc_demo_run_cpt_seeders(): array {
    $base = OC_THEME_DIR . '/inc/cpt';
    $jobs = array(
        'demo content (destinations, services, team, testimonials, itineraries, FAQs, offers, press)' => 'demo-content.php',
        'extra demo content (more itineraries, offers, vessels)'                                       => 'demo-content-extra.php',
        'CPT featured images (sideloaded from Pexels)'                                                 => 'seed-images.php',
    );

    // Clear the seeders' run-once flags so re-importing actually re-runs.
    delete_option( 'oc_demo_seeded' );
    delete_option( 'oc_demo_extra_seeded' );

    $out = array();
    foreach ( $jobs as $label => $file ) {
        $path = $base . '/' . $file;
        if ( ! file_exists( $path ) ) {
            $out[] = "Skipped: {$label} — file missing ({$file}).";
            continue;
        }

        ob_start();
        try {
            // include (not require_once) so re-runs in the same request still
            // execute. The seeders guard against re-defining helpers internally.
            include $path;
            $captured = ob_get_clean();
        } catch ( \Throwable $e ) {
            ob_end_clean();
            $out[] = "Error seeding {$label}: " . $e->getMessage();
            continue;
        }

        // Compress the seeder's chatty echo output into a single summary line
        // plus any [ERROR] lines surfaced verbatim, so the admin result list
        // stays readable.
        $line_count = 0;
        $errors     = array();
        foreach ( preg_split( "/\r\n|\n|\r/", trim( (string) $captured ) ) as $line ) {
            $line = trim( $line );
            if ( $line === '' ) {
                continue;
            }
            $line_count++;
            if ( stripos( $line, '[ERROR]' ) !== false || stripos( $line, 'error' ) !== false ) {
                $errors[] = $line;
            }
        }

        $out[] = sprintf(
            'CPT seeder: %s — %d log lines%s',
            $label,
            $line_count,
            $errors ? ', ' . count( $errors ) . ' errors' : ''
        );
        foreach ( $errors as $err ) {
            $out[] = '    ' . $err;
        }
    }

    return $out;
}

/**
 * Create (or reuse) a nav menu by name, replace its items, and return its term ID.
 */
function oc_demo_build_menu( string $name, array $items ): int {
    $menu = wp_get_nav_menu_object( $name );
    $menu_id = $menu ? (int) $menu->term_id : (int) wp_create_nav_menu( $name );

    if ( ! $menu_id || is_wp_error( $menu_id ) ) {
        return 0;
    }

    // Clear existing items so re-runs stay clean.
    $existing = wp_get_nav_menu_items( $menu_id );
    if ( $existing ) {
        foreach ( $existing as $item ) {
            wp_delete_post( $item->ID, true );
        }
    }

    $position = 1;
    foreach ( $items as $item ) {
        wp_update_nav_menu_item( $menu_id, 0, array(
            'menu-item-title'    => $item['title'],
            'menu-item-url'      => $item['url'],
            'menu-item-status'   => 'publish',
            'menu-item-position' => $position++,
        ) );
    }

    return $menu_id;
}
