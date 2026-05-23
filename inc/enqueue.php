<?php
/**
 * Enqueue scripts and styles
 *
 * @package OceanCharter
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function oc_enqueue_assets() {
    // Google Fonts — built dynamically from Theme Settings so that swapping
    // the body or heading font in admin actually loads the chosen font file.
    $heading_font = class_exists( 'OC_Theme_Settings' )
        ? ( OC_Theme_Settings::get( 'heading_font' ) ?: 'Playfair Display' )
        : 'Playfair Display';
    $body_font    = class_exists( 'OC_Theme_Settings' )
        ? ( OC_Theme_Settings::get( 'body_font' ) ?: 'Inter' )
        : 'Inter';

    $families = [
        str_replace( '%20', '+', rawurlencode( $body_font ) )    . ':wght@300;400;500;600;700',
        str_replace( '%20', '+', rawurlencode( $heading_font ) ) . ':ital,wght@0,400;0,600;0,700;1,400;1,600',
    ];
    wp_enqueue_style(
        'oc-fonts',
        'https://fonts.googleapis.com/css2?family=' . implode( '&family=', $families ) . '&display=swap',
        [],
        null
    );

    // Master stylesheet
    wp_enqueue_style(
        'ocean-charter-style',
        get_template_directory_uri() . '/style.css',
        [ 'oc-fonts' ],
        OC_VERSION
    );

    // Main JS (footer, no jQuery dependency needed)
    wp_enqueue_script(
        'ocean-charter-main',
        get_template_directory_uri() . '/assets/js/main.js',
        [],
        OC_VERSION,
        true
    );

    // Pass data to JS
    wp_localize_script( 'ocean-charter-main', 'ocData', [
        'ajaxUrl'        => admin_url( 'admin-ajax.php' ),
        'whatsappUrl'    => function_exists( 'oc_whatsapp_url' ) ? oc_whatsapp_url() : '',
        'siteUrl'        => home_url(),
        'googleMapsKey'  => oc_setting( 'google_maps_key', '' ),
        'fleetUrl'       => get_post_type_archive_link( 'boat' ) ?: home_url( '/fleet/' ),
    ] );

    // Flatpickr — enqueue on pages that need a date picker
    $needs_flatpickr = is_singular( [ 'oc_service', 'oc_itinerary', 'oc_offer', 'bbc_package' ] )
        || is_front_page()
        || is_page_template( 'page-itinerary.php' )
        || is_post_type_archive( 'boat' )
        || is_singular( 'boat' );

    if ( $needs_flatpickr ) {
        wp_enqueue_style(
            'flatpickr',
            'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css',
            [],
            '4.6.13'
        );
        wp_enqueue_script(
            'flatpickr',
            'https://cdn.jsdelivr.net/npm/flatpickr',
            [],
            '4.6.13',
            true
        );
    }

    // Google Places — enqueue on front page when API key is set
    $maps_key = oc_setting( 'google_maps_key', '' );
    if ( $maps_key && ( is_front_page() || is_page_template( 'front-page.php' ) ) ) {
        wp_enqueue_script(
            'google-places',
            'https://maps.googleapis.com/maps/api/js?key=' . urlencode( $maps_key ) . '&libraries=places&callback=initOCPlaces',
            [ 'ocean-charter-main' ],
            null,
            true
        );
    }
}
add_action( 'wp_enqueue_scripts', 'oc_enqueue_assets' );

/**
 * Preconnect to Google Fonts for performance.
 */
function oc_preconnect_fonts() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}
add_action( 'wp_head', 'oc_preconnect_fonts', 1 );
