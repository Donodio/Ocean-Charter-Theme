<?php
/**
 * Ocean Charter — CRM Outbound Webhook
 *
 * Dispatches event payloads to an external CRM via non-blocking JSON POST.
 * Configure the endpoint in Ocean Charter → Theme Settings → Integrations.
 *
 * @package OceanCharter
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Fire an outbound webhook event to the configured CRM endpoint.
 *
 * @param string $event  Event slug, e.g. "inquiry.destination".
 * @param array  $data   Arbitrary payload data for this event.
 */
function oc_crm_dispatch( string $event, array $data ): void {
    if ( OC_Theme_Settings::get( 'crm_webhook_enabled' ) !== '1' ) return;

    $url    = OC_Theme_Settings::get( 'crm_webhook_url' );
    $secret = OC_Theme_Settings::get( 'crm_webhook_secret' );

    if ( empty( $url ) || ! filter_var( $url, FILTER_VALIDATE_URL ) ) return;

    $payload = wp_json_encode( [
        'event'     => $event,
        'timestamp' => gmdate( 'c' ),
        'site_url'  => home_url(),
        'data'      => $data,
    ] );

    $headers = [ 'Content-Type' => 'application/json' ];
    if ( $secret ) {
        $headers['X-OC-Signature'] = 'sha256=' . hash_hmac( 'sha256', $payload, $secret );
    }

    wp_remote_post( $url, [
        'headers'   => $headers,
        'body'      => $payload,
        'blocking'  => false,
        'timeout'   => 5,
        'sslverify' => true,
    ] );
}

/* ── Hook into existing inquiry handlers via do_action ───────────────────── */

add_action( 'oc_crm_event', 'oc_crm_dispatch', 10, 2 );

/* ── BBC Booking hooks (fires when BBC plugin creates/updates a booking) ─── */

add_action( 'bbc_booking_created', function( $booking_id ) {
    if ( ! function_exists( 'bbc_get_booking' ) ) return;
    $booking = bbc_get_booking( $booking_id );
    if ( ! $booking ) return;

    oc_crm_dispatch( 'booking.bbc', [
        'booking_id' => $booking_id,
        'boat_id'    => $booking->boat_id ?? null,
        'boat_name'  => $booking->boat_id ? get_the_title( $booking->boat_id ) : null,
        'status'     => $booking->status ?? null,
        'start_date' => $booking->start_date ?? null,
        'end_date'   => $booking->end_date ?? null,
        'guests'     => $booking->guests ?? null,
        'guest_name' => $booking->guest_name ?? null,
        'guest_email'=> $booking->guest_email ?? null,
        'total'      => $booking->total ?? null,
    ] );
} );

add_action( 'bbc_booking_status_changed', function( $booking_id, $new_status ) {
    if ( ! function_exists( 'bbc_get_booking' ) ) return;
    $booking = bbc_get_booking( $booking_id );
    if ( ! $booking ) return;

    oc_crm_dispatch( 'booking.bbc_status_changed', [
        'booking_id' => $booking_id,
        'new_status' => $new_status,
        'boat_id'    => $booking->boat_id ?? null,
        'guest_name' => $booking->guest_name ?? null,
        'guest_email'=> $booking->guest_email ?? null,
    ] );
}, 10, 2 );

/* ── Homepage search intent AJAX endpoint ────────────────────────────────── */

add_action( 'wp_ajax_oc_search_intent',        'oc_handle_search_intent' );
add_action( 'wp_ajax_nopriv_oc_search_intent', 'oc_handle_search_intent' );

function oc_handle_search_intent(): void {
    // Verify nonce — allows cross-origin beacon from the front-page form
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'oc_search_intent' ) ) {
        wp_send_json_error( null, 403 );
    }

    oc_crm_dispatch( 'search.homepage', [
        'location' => sanitize_text_field( wp_unslash( $_POST['location'] ?? '' ) ),
        'dates'    => sanitize_text_field( wp_unslash( $_POST['dates']    ?? '' ) ),
        'guests'   => absint( $_POST['guests'] ?? 0 ),
    ] );

    wp_send_json_success();
}
