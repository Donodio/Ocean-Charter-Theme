<?php
/**
 * Ocean Charter — Demo Installer (CLI / direct-run wrapper)
 *
 * The actual import logic lives in inc/demo-importer.php (oc_run_demo_import()),
 * which is also exposed in the admin under Appearance → Import Demo. This file is
 * a thin entry point for WP-CLI and direct execution.
 *
 * USAGE:
 *   wp eval-file install-demo.php
 *   …or visit /wp-content/themes/ocean-charter/install-demo.php as a logged-in admin.
 *
 * @package OceanCharter
 */

if ( ! defined( 'ABSPATH' ) ) {
    // Bootstrap WordPress when run directly (not via WP-CLI).
    require_once dirname( __DIR__, 3 ) . '/wp-load.php';
}

if ( ! current_user_can( 'manage_options' ) && ! ( defined( 'WP_CLI' ) && WP_CLI ) ) {
    wp_die( 'Unauthorized access.' );
}

if ( ! function_exists( 'oc_run_demo_import' ) ) {
    wp_die( 'Ocean Charter theme must be active to import demo content.' );
}

@set_time_limit( 0 );
if ( function_exists( 'wp_raise_memory_limit' ) ) {
    wp_raise_memory_limit( 'image' );
}

$results = oc_run_demo_import();

if ( defined( 'WP_CLI' ) && WP_CLI ) {
    foreach ( $results as $msg ) {
        WP_CLI::log( $msg );
    }
    WP_CLI::success( 'Ocean Charter demo installed.' );
} else {
    echo '<!DOCTYPE html><html><body style="font-family:monospace;padding:40px;background:#0a1628;color:#aaa;">';
    echo '<h1 style="color:#d9b230;font-family:Georgia,serif;font-weight:400;">Ocean Charter Demo Installer</h1><ul style="line-height:2;">';
    foreach ( $results as $msg ) {
        $err = stripos( $msg, 'error' ) !== false;
        printf(
            '<li style="color:%s;">%s %s</li>',
            $err ? '#e55' : '#6fba6f',
            $err ? '&#10007;' : '&#10003;',
            esc_html( $msg )
        );
    }
    echo '</ul><p style="margin-top:30px;"><a href="' . esc_url( home_url( '/' ) ) . '" style="color:#d9b230;font-size:16px;">&larr; View Your Site</a></p></body></html>';
}
