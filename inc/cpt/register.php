<?php
/**
 * Ocean Charter — Custom Post Types & Taxonomies
 *
 * All site content is stored in CPTs. Elementor widgets query these CPTs
 * and render them on the front end. Content editors never touch Elementor
 * JSON — they manage content through these admin screens.
 *
 * CPTs registered:
 *   oc_itinerary       — Multi-day sailing itineraries
 *   oc_itinerary_day   — Individual days (child posts of oc_itinerary)
 *   oc_service         — Onboard services (chef, water toys, etc.)
 *   oc_package         — Charter packages (sunset cruise, corporate, etc.)
 *   oc_testimonial     — Guest reviews
 *   oc_destination     — Sailing destinations
 *   oc_vessel          — Yachts / vessels available for charter
 *   oc_team_member     — Crew and team profiles
 *   oc_faq             — Frequently asked questions
 *   oc_offer           — Special offers and promotions
 *   oc_press           — Press mentions and media coverage
 *
 * Taxonomies:
 *   oc_package_type       — Day Charters / Celebrations / Corporate
 *   oc_destination_region — Mediterranean / Caribbean / etc.
 *   oc_vessel_type        — Motor Yacht / Sailing Yacht / Catamaran / Superyacht
 *   oc_team_role          — Captain / Chef / Crew / Management
 *   oc_faq_category       — Booking / Onboard Experience / Pricing / General
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* ============================================================
   Itineraries
   ============================================================ */
add_action( 'init', function () {

    register_post_type( 'oc_itinerary', [
        'labels'             => [
            'name'               => 'Itineraries',
            'singular_name'      => 'Itinerary',
            'add_new_item'       => 'Add New Itinerary',
            'edit_item'          => 'Edit Itinerary',
            'new_item'           => 'New Itinerary',
            'view_item'          => 'View Itinerary',
            'search_items'       => 'Search Itineraries',
            'not_found'          => 'No itineraries found',
            'menu_name'          => 'Itineraries',
        ],
        'public'             => true,
        'show_ui'            => true,
        'show_in_menu'       => 'ocean-charter',
        'menu_icon'          => 'dashicons-location-alt',
        'supports'           => [ 'title', 'thumbnail', 'excerpt', 'editor' ],
        'has_archive'        => false,
        'rewrite'            => [ 'slug' => 'itineraries', 'with_front' => false ],
        'show_in_rest'       => true,
        'exclude_from_search'=> false,
    ] );

    register_post_type( 'oc_itinerary_day', [
        'labels'        => [
            'name'          => 'Itinerary Days',
            'singular_name' => 'Itinerary Day',
            'add_new_item'  => 'Add Day',
            'edit_item'     => 'Edit Day',
            'menu_name'     => 'Days',
        ],
        'public'        => false,
        'show_ui'       => true,
        'show_in_menu'  => false,   // shown via parent itinerary screen
        'supports'      => [ 'title', 'thumbnail', 'page-attributes' ],
        'rewrite'       => false,
        'show_in_rest'  => true,
        'hierarchical'  => false,
    ] );

} );

/* ============================================================
   Services
   ============================================================ */
add_action( 'init', function () {

    register_post_type( 'oc_service', [
        'labels'             => [
            'name'          => 'Services',
            'singular_name' => 'Service',
            'add_new_item'  => 'Add New Service',
            'edit_item'     => 'Edit Service',
            'menu_name'     => 'Services',
        ],
        'public'             => true,
        'show_ui'            => true,
        'show_in_menu'       => 'ocean-charter',
        'menu_icon'          => 'dashicons-star-filled',
        'supports'           => [ 'title', 'thumbnail', 'excerpt', 'editor' ],
        'has_archive'        => false,
        'rewrite'            => [ 'slug' => 'services', 'with_front' => false ],
        'show_in_rest'       => true,
        'exclude_from_search'=> false,
    ] );

} );

/* ============================================================
   Packages
   ============================================================ */
add_action( 'init', function () {

    register_taxonomy( 'oc_package_type', 'oc_package', [
        'labels'            => [
            'name'          => 'Package Types',
            'singular_name' => 'Package Type',
            'menu_name'     => 'Package Types',
        ],
        'public'            => false,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'hierarchical'      => true,
        'show_in_rest'      => true,
        'rewrite'           => false,
    ] );

    register_post_type( 'oc_package', [
        'labels'             => [
            'name'          => 'Packages',
            'singular_name' => 'Package',
            'add_new_item'  => 'Add New Package',
            'edit_item'     => 'Edit Package',
            'menu_name'     => 'Packages',
        ],
        'public'             => true,
        'show_ui'            => true,
        'show_in_menu'       => 'ocean-charter',
        'menu_icon'          => 'dashicons-tickets-alt',
        'supports'           => [ 'title', 'thumbnail', 'excerpt', 'editor' ],
        'has_archive'        => false,
        'rewrite'            => [ 'slug' => 'packages', 'with_front' => false ],
        'show_in_rest'       => true,
        'taxonomies'         => [ 'oc_package_type' ],
        'exclude_from_search'=> false,
    ] );

} );

/* ============================================================
   Testimonials
   ============================================================ */
add_action( 'init', function () {

    register_post_type( 'oc_testimonial', [
        'labels'       => [
            'name'          => 'Testimonials',
            'singular_name' => 'Testimonial',
            'add_new_item'  => 'Add Testimonial',
            'edit_item'     => 'Edit Testimonial',
            'menu_name'     => 'Testimonials',
        ],
        'public'       => false,
        'show_ui'      => true,
        'show_in_menu' => 'ocean-charter',
        'menu_icon'    => 'dashicons-format-quote',
        'supports'     => [ 'title', 'thumbnail' ],   // title = author name; thumbnail = fallback photo
        'rewrite'      => false,
        'show_in_rest' => true,
    ] );

} );

/* ============================================================
   Destinations
   ============================================================ */
add_action( 'init', function () {

    register_taxonomy( 'oc_destination_region', 'oc_destination', [
        'labels'       => [
            'name'          => 'Regions',
            'singular_name' => 'Region',
            'menu_name'     => 'Regions',
        ],
        'public'       => false,
        'show_ui'      => true,
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite'      => false,
    ] );

    register_post_type( 'oc_destination', [
        'labels'             => [
            'name'          => 'Destinations',
            'singular_name' => 'Destination',
            'add_new_item'  => 'Add Destination',
            'edit_item'     => 'Edit Destination',
            'menu_name'     => 'Destinations',
        ],
        'public'             => true,
        'show_ui'            => true,
        'show_in_menu'       => 'ocean-charter',
        'menu_icon'          => 'dashicons-palmtree',
        'supports'           => [ 'title', 'thumbnail', 'excerpt', 'editor' ],
        'has_archive'        => false,
        'rewrite'            => [ 'slug' => 'destinations', 'with_front' => false ],
        'show_in_rest'       => true,
        'taxonomies'         => [ 'oc_destination_region' ],
        'exclude_from_search'=> false,
    ] );

} );

/* ============================================================
   Vessels
   ============================================================ */
add_action( 'init', function () {

    register_taxonomy( 'oc_vessel_type', 'oc_vessel', [
        'labels'       => [
            'name'          => 'Vessel Types',
            'singular_name' => 'Vessel Type',
            'menu_name'     => 'Vessel Types',
        ],
        'public'       => false,
        'show_ui'      => true,
        'hierarchical' => false,
        'show_in_rest' => true,
        'rewrite'      => false,
    ] );

    register_post_type( 'oc_vessel', [
        'labels'             => [
            'name'          => 'Vessels',
            'singular_name' => 'Vessel',
            'add_new_item'  => 'Add Vessel',
            'edit_item'     => 'Edit Vessel',
            'menu_name'     => 'Vessels',
        ],
        'public'             => true,
        'show_ui'            => true,
        'show_in_menu'       => 'ocean-charter',
        'menu_icon'          => 'dashicons-admin-site-alt3',
        'supports'           => [ 'title', 'thumbnail', 'excerpt', 'editor' ],
        'has_archive'        => false,
        'rewrite'            => [ 'slug' => 'vessels', 'with_front' => false ],
        'show_in_rest'       => true,
        'taxonomies'         => [ 'oc_vessel_type' ],
        'exclude_from_search'=> false,
    ] );

} );

/* ============================================================
   Team Members
   ============================================================ */
add_action( 'init', function () {

    register_taxonomy( 'oc_team_role', 'oc_team_member', [
        'labels'       => [
            'name'          => 'Team Roles',
            'singular_name' => 'Team Role',
            'menu_name'     => 'Team Roles',
        ],
        'public'       => false,
        'show_ui'      => true,
        'hierarchical' => false,
        'show_in_rest' => true,
        'rewrite'      => false,
    ] );

    register_post_type( 'oc_team_member', [
        'labels'             => [
            'name'          => 'Team',
            'singular_name' => 'Team Member',
            'add_new_item'  => 'Add Member',
            'edit_item'     => 'Edit Member',
            'menu_name'     => 'Team',
        ],
        'public'             => false,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => 'ocean-charter',
        'menu_icon'          => 'dashicons-id',
        'supports'           => [ 'title', 'thumbnail', 'excerpt' ],
        'has_archive'        => false,
        'rewrite'            => [ 'slug' => 'crew', 'with_front' => false ],
        'show_in_rest'       => true,
        'taxonomies'         => [ 'oc_team_role' ],
    ] );

} );

/* ============================================================
   FAQs
   ============================================================ */
add_action( 'init', function () {

    register_taxonomy( 'oc_faq_category', 'oc_faq', [
        'labels'       => [
            'name'          => 'FAQ Categories',
            'singular_name' => 'FAQ Category',
            'menu_name'     => 'FAQ Categories',
        ],
        'public'       => false,
        'show_ui'      => true,
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite'      => false,
    ] );

    register_post_type( 'oc_faq', [
        'labels'       => [
            'name'          => 'FAQs',
            'singular_name' => 'FAQ',
            'add_new_item'  => 'Add FAQ',
            'edit_item'     => 'Edit FAQ',
            'menu_name'     => 'FAQs',
        ],
        'public'       => false,
        'show_ui'      => true,
        'show_in_menu' => 'ocean-charter',
        'menu_icon'    => 'dashicons-editor-help',
        'supports'     => [ 'title' ],
        'rewrite'      => false,
        'show_in_rest' => true,
        'taxonomies'   => [ 'oc_faq_category' ],
    ] );

} );

/* ============================================================
   Offers
   ============================================================ */
add_action( 'init', function () {

    register_post_type( 'oc_offer', [
        'labels'             => [
            'name'          => 'Offers',
            'singular_name' => 'Offer',
            'add_new_item'  => 'Add Offer',
            'edit_item'     => 'Edit Offer',
            'menu_name'     => 'Offers',
        ],
        'public'             => true,
        'show_ui'            => true,
        'show_in_menu'       => 'ocean-charter',
        'menu_icon'          => 'dashicons-tag',
        'supports'           => [ 'title', 'thumbnail', 'excerpt', 'editor' ],
        'has_archive'        => false,
        'rewrite'            => [ 'slug' => 'offers', 'with_front' => false ],
        'show_in_rest'       => true,
        'exclude_from_search'=> false,
    ] );

} );

/* ============================================================
   Press
   ============================================================ */
add_action( 'init', function () {

    register_post_type( 'oc_press', [
        'labels'       => [
            'name'          => 'Press',
            'singular_name' => 'Press Item',
            'add_new_item'  => 'Add Press Item',
            'edit_item'     => 'Edit Press Item',
            'menu_name'     => 'Press',
        ],
        'public'       => false,
        'show_ui'      => true,
        'show_in_menu' => 'ocean-charter',
        'menu_icon'    => 'dashicons-media-document',
        'supports'     => [ 'title' ],
        'rewrite'      => false,
        'show_in_rest' => true,
    ] );

} );

/* ============================================================
   Flush rewrite rules on theme activation (so CPT permalinks work)
   ============================================================ */
add_action( 'after_switch_theme', function () {
    flush_rewrite_rules();
} );

// One-time flush when rewrite rules are stale (runs once, then removes itself)
add_action( 'init', function () {
    if ( get_option( 'oc_flush_rewrite_v3' ) !== '1' ) {
        flush_rewrite_rules();
        update_option( 'oc_flush_rewrite_v3', '1', true );
    }
}, 99 );

/* ============================================================
   Admin top-level menu (groups all OC CPTs under one icon)
   ============================================================ */
add_action( 'admin_menu', function () {
    add_menu_page(
        'Ocean Charter',
        'Ocean Charter',
        'edit_posts',
        'ocean-charter',
        function () {
            echo '<div class="wrap"><h1>Ocean Charter Content</h1>'
                . '<p>Use the sub-menu items to manage itineraries, services, packages, testimonials, destinations, vessels, team members, FAQs, offers and press.</p></div>';
        },
        'dashicons-palmtree',
        25
    );
} );

/* ============================================================
   Admin columns — Itinerary Days (show parent itinerary)
   ============================================================ */
add_filter( 'manage_oc_itinerary_day_posts_columns', function ( $cols ) {
    $cols['parent_itinerary'] = 'Itinerary';
    $cols['day_number']       = 'Day #';
    return $cols;
} );
add_action( 'manage_oc_itinerary_day_posts_custom_column', function ( $col, $post_id ) {
    if ( $col === 'parent_itinerary' ) {
        $pid = (int) get_post_meta( $post_id, '_oc_parent_itinerary', true );
        echo $pid ? '<a href="' . get_edit_post_link( $pid ) . '">' . esc_html( get_the_title( $pid ) ) . '</a>' : '—';
    }
    if ( $col === 'day_number' ) {
        echo esc_html( get_post_meta( $post_id, '_oc_day_number', true ) ?: '—' );
    }
}, 10, 2 );
