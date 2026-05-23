<?php
/**
 * Elementor integration for Ocean Charter theme.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Add theme support for Elementor.
 */
function oc_elementor_setup() {
    add_theme_support( 'elementor' );
}
add_action( 'after_setup_theme', 'oc_elementor_setup' );

/**
 * Register Ocean Charter global colors in Elementor.
 */
function oc_elementor_global_colors( $config ) {
    if ( ! isset( $config['globals']['colors'] ) ) {
        $config['globals']['colors'] = [];
    }
    $oc_colors = [
        [ 'id' => 'oc_primary',   'title' => 'OC Gold',      'value' => '#d9b230' ],
        [ 'id' => 'oc_secondary', 'title' => 'OC Navy',      'value' => '#0a101a' ],
        [ 'id' => 'oc_surface',   'title' => 'OC Surface',   'value' => '#111a28' ],
        [ 'id' => 'oc_text',      'title' => 'OC Text',      'value' => '#f0ece3' ],
        [ 'id' => 'oc_muted',     'title' => 'OC Muted',     'value' => '#8a9bb0' ],
    ];
    foreach ( $oc_colors as $color ) {
        $config['globals']['colors'][ $color['id'] ] = $color;
    }
    return $config;
}
add_filter( 'elementor/editor/localize_settings', 'oc_elementor_global_colors' );

/**
 * Register Ocean Charter fonts in Elementor.
 */
function oc_elementor_global_fonts( $config ) {
    if ( ! isset( $config['globals']['typography'] ) ) {
        $config['globals']['typography'] = [];
    }
    $oc_fonts = [
        [
            'id'    => 'oc_heading',
            'title' => 'OC Heading',
            'value' => [ 'font_family' => 'Playfair Display', 'font_weight' => '700' ],
        ],
        [
            'id'    => 'oc_body',
            'title' => 'OC Body',
            'value' => [ 'font_family' => 'Inter', 'font_weight' => '400' ],
        ],
    ];
    foreach ( $oc_fonts as $font ) {
        $config['globals']['typography'][ $font['id'] ] = $font;
    }
    return $config;
}
add_filter( 'elementor/editor/localize_settings', 'oc_elementor_global_fonts' );

/**
 * Register BBC boat-booking widget category.
 * (BBC registers its own, but ensure OC category also exists.)
 */
function oc_elementor_widget_categories( $elements_manager ) {
    $elements_manager->add_category( 'ocean-charter', [
        'title' => __( 'Ocean Charter', 'ocean-charter' ),
        'icon'  => 'fa fa-anchor',
    ] );
}
add_action( 'elementor/elements/categories_registered', 'oc_elementor_widget_categories' );

/**
 * Load elementor-widgets.php after Elementor core classes are available.
 * Widget_Base exists by elementor/init; the file's own add_action handles registration.
 */
add_action( 'elementor/init', function() {
    require_once get_template_directory() . '/inc/elementor-widgets.php';
    require_once get_template_directory() . '/inc/elementor-query-widgets.php';

    \Elementor\Plugin::instance()->widgets_manager->register( new OC_Destination_Grid_Widget() );
    \Elementor\Plugin::instance()->widgets_manager->register( new OC_Service_Grid_Widget() );
    \Elementor\Plugin::instance()->widgets_manager->register( new OC_Package_Grid_Widget() );
    \Elementor\Plugin::instance()->widgets_manager->register( new OC_Testimonial_Carousel_Widget() );
    \Elementor\Plugin::instance()->widgets_manager->register( new OC_Team_Grid_Widget() );
    \Elementor\Plugin::instance()->widgets_manager->register( new OC_FAQ_Accordion_Widget() );
    \Elementor\Plugin::instance()->widgets_manager->register( new OC_Offer_Cards_Widget() );
    \Elementor\Plugin::instance()->widgets_manager->register( new OC_Press_Strip_Widget() );
    \Elementor\Plugin::instance()->widgets_manager->register( new OC_Vessel_Grid_Widget() );
    \Elementor\Plugin::instance()->widgets_manager->register( new OC_Itinerary_Grid_Widget() );
    \Elementor\Plugin::instance()->widgets_manager->register( new OC_Featured_Vessels_Widget() );
    \Elementor\Plugin::instance()->widgets_manager->register( new OC_Why_Us_Widget() );
    \Elementor\Plugin::instance()->widgets_manager->register( new OC_Destinations_Gallery_Widget() );
} );

/**
 * Allow Elementor full-width on all page types.
 */
add_filter( 'elementor/utils/get_public_post_types', function( $post_types ) {
    $post_types['boat']        = 'Boat';
    $post_types['bbc_package'] = 'Package';
    return $post_types;
} );
