<?php
/**
 * Theme Setup
 *
 * @package OceanCharter
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! function_exists( 'ocean_charter_setup' ) ) :
    function ocean_charter_setup() {
        add_theme_support( 'automatic-feed-links' );
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'html5', [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ] );
        add_theme_support( 'customize-selective-refresh-widgets' );
        add_theme_support( 'responsive-embeds' );
        add_theme_support( 'align-wide' );
        add_theme_support( 'editor-styles' );
        add_theme_support( 'wp-block-styles' );

        // Custom logo — appears in Customizer > Site Identity
        add_theme_support( 'custom-logo', [
            'height'               => 60,
            'width'                => 200,
            'flex-height'          => true,
            'flex-width'           => true,
            'header-text'          => [ 'site-title', 'site-description' ],
            'unlink-homepage-logo' => false,
        ] );

        register_nav_menus( [
            'menu-1' => esc_html__( 'Primary', 'ocean-charter' ),
            'footer' => esc_html__( 'Footer',  'ocean-charter' ),
        ] );
    }
endif;
add_action( 'after_setup_theme', 'ocean_charter_setup' );

// ── Package Type taxonomy ─────────────────────────────────────────────────────

function oc_register_package_type_taxonomy() {
    register_taxonomy( 'package_type', 'bbc_package', [
        'label'        => __( 'Package Types', 'ocean-charter' ),
        'hierarchical' => false,
        'public'       => true,
        'rewrite'      => [ 'slug' => 'package-type' ],
        'show_in_rest' => true,
    ] );
}
add_action( 'init', 'oc_register_package_type_taxonomy' );

/**
 * Create default Package Type terms on theme activation.
 */
function oc_create_default_package_terms() {
    $terms = [
        'Day Charter'       => 'day-charter',
        'Weekend Getaway'   => 'weekend-getaway',
        'Blue Water Voyage' => 'blue-water-voyage',
    ];
    foreach ( $terms as $name => $slug ) {
        if ( ! term_exists( $slug, 'package_type' ) ) {
            wp_insert_term( $name, 'package_type', [ 'slug' => $slug ] );
        }
    }
}
add_action( 'after_switch_theme', 'oc_create_default_package_terms' );

// BBC plugin assets needed on all OC frontend pages (hero search, booking forms, boat grids).
add_filter( 'bbc_should_load_assets', '__return_true' );
