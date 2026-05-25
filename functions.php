<?php
/**
 * Ocean Charter — functions.php
 *
 * @package OceanCharter
 */

if ( ! function_exists( 'ocwt_fs' ) ) {
    // Create a helper function for easy SDK access.
    function ocwt_fs() {
        global $ocwt_fs;

        if ( ! isset( $ocwt_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/vendor/freemius/start.php';

            $ocwt_fs = fs_dynamic_init( array(
                'id'                  => '28365',
                'slug'                => 'ocean-charter-wordpress-theme',
                'type'                => 'theme',
                'public_key'          => 'pk_9288403d9c66188ee69dd2074fc6c',
                'is_premium'          => true,
                'is_premium_only'     => true,
                'has_addons'          => false,
                'has_paid_plans'      => true,
                'is_org_compliant'    => true,
                // Automatically removed in the free version. If you're not using the
                // auto-generated free version, delete this line before uploading to wp.org.
                'wp_org_gatekeeper'   => 'OA7#BoRiBNqdf52FvzEf!!074aRLPs8fspif$7K1#4u4Csys1fQlCecVcUTOs2mcpeVHi#C2j9d09fOTvbC0HloPT7fFee5WdS3G',
                'menu'                => array(
                    'support'        => false,
                ),
            ) );
        }

        return $ocwt_fs;
    }

    // Init Freemius.
    ocwt_fs();
    // Signal that SDK was initiated.
    do_action( 'ocwt_fs_loaded' );
}

if ( ! defined( 'ABSPATH' ) ) exit;

// ── Theme constants ───────────────────────────────────────────────────────────
define( 'OC_VERSION',   '1.2.0' );
define( 'OC_THEME_DIR', get_template_directory() );
define( 'OC_THEME_URL', get_template_directory_uri() );

// Legacy constant aliases (kept for any existing template code)
define( 'OCEAN_CHARTER_VERSION', OC_VERSION );
define( 'OCEAN_CHARTER_DIR',     OC_THEME_DIR );
define( 'OCEAN_CHARTER_URI',     OC_THEME_URL );

// ── Core includes (order matters) ────────────────────────────────────────────
require_once get_template_directory() . '/inc/theme-settings.php';
require_once OC_THEME_DIR . '/inc/pexels-images.php';
require_once OC_THEME_DIR . '/inc/setup.php';
require_once OC_THEME_DIR . '/inc/enqueue.php';
require_once OC_THEME_DIR . '/inc/customizer.php';
require_once OC_THEME_DIR . '/inc/template-tags.php';
require_once OC_THEME_DIR . '/inc/elementor-style-trait.php';
require_once OC_THEME_DIR . '/inc/elementor-support.php';

// ── Custom Post Types ─────────────────────────────────────────────────────────
require_once OC_THEME_DIR . '/inc/cpt/register.php';
require_once OC_THEME_DIR . '/inc/cpt/meta-fields.php';

// Destination inquiry AJAX handler
require_once OC_THEME_DIR . '/inc/inquiry-handler.php';

// CRM outbound webhook dispatcher
require_once OC_THEME_DIR . '/inc/crm-webhook.php';

// Self-contained demo content importer (Appearance → Import Demo)
require_once OC_THEME_DIR . '/inc/demo-importer.php';

// BBC plugin page generator (admin tool)
require_once OC_THEME_DIR . '/inc/bbc-page-generator.php';

// ── Translations ─────────────────────────────────────────────────────────────
load_theme_textdomain( 'ocean-charter', OC_THEME_DIR . '/languages' );

// Performance: disable emojis if opted out
add_action( 'init', function() {
    if ( OC_Theme_Settings::get('disable_emojis') === '1' ) {
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
    }
} );

// ── Fleet archive — filter by search params from homepage form ────────────────
add_action( 'pre_get_posts', function( $query ) {
    if ( ! $query->is_main_query() || is_admin() ) return;
    if ( ! $query->is_post_type_archive( 'boat' ) ) return;

    $meta_query = [];

    // Filter by location text — check both BBC and theme meta keys
    $location = isset( $_GET['location'] ) ? sanitize_text_field( wp_unslash( $_GET['location'] ) ) : '';
    if ( $location ) {
        $meta_query[] = [
            'relation' => 'OR',
            [
                'key'     => '_bbc_location',
                'value'   => $location,
                'compare' => 'LIKE',
            ],
            [
                'key'     => '_boat_location',
                'value'   => $location,
                'compare' => 'LIKE',
            ],
        ];
    }

    // Filter by guest count — map numeric to min capacity
    $guests = isset( $_GET['guests'] ) ? absint( $_GET['guests'] ) : 0;
    if ( $guests ) {
        $meta_query[] = [
            'key'     => '_bbc_max_guests',
            'value'   => $guests,
            'compare' => '>=',
            'type'    => 'NUMERIC',
        ];
    }

    if ( ! empty( $meta_query ) ) {
        $meta_query['relation'] = 'AND';
        $query->set( 'meta_query', $meta_query );
    }
} );
