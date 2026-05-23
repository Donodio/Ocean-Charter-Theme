<?php
/**
 * Destination Inquiry AJAX Handler
 *
 * Handles the oc_destination_inquiry form submissions.
 *
 * @package OceanCharter
 */

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wp_ajax_oc_destination_inquiry',        'oc_handle_destination_inquiry' );
add_action( 'wp_ajax_nopriv_oc_destination_inquiry', 'oc_handle_destination_inquiry' );

/**
 * Process the destination inquiry form submission.
 */
function oc_handle_destination_inquiry() {

    // Verify nonce
    if ( ! isset( $_POST['oc_inquiry_nonce'] ) || ! wp_verify_nonce( $_POST['oc_inquiry_nonce'], 'oc_destination_inquiry' ) ) {
        wp_send_json_error( [ 'message' => 'Security check failed. Please refresh the page and try again.' ] );
    }

    // Validate required fields
    $name  = isset( $_POST['guest_name'] )  ? sanitize_text_field( wp_unslash( $_POST['guest_name'] ) )  : '';
    $email = isset( $_POST['guest_email'] ) ? sanitize_email( wp_unslash( $_POST['guest_email'] ) )      : '';

    if ( empty( $name ) ) {
        wp_send_json_error( [ 'message' => 'Please enter your name.' ] );
    }
    if ( empty( $email ) || ! is_email( $email ) ) {
        wp_send_json_error( [ 'message' => 'Please enter a valid email address.' ] );
    }

    // Gather all fields
    $phone             = isset( $_POST['guest_phone'] )       ? sanitize_text_field( wp_unslash( $_POST['guest_phone'] ) )       : '';
    $preferred_dates   = isset( $_POST['preferred_dates'] )   ? sanitize_text_field( wp_unslash( $_POST['preferred_dates'] ) )   : '';
    $num_guests        = isset( $_POST['num_guests'] )        ? absint( $_POST['num_guests'] )                                    : '';
    $message           = isset( $_POST['guest_message'] )     ? sanitize_textarea_field( wp_unslash( $_POST['guest_message'] ) ) : '';
    $destination_id    = isset( $_POST['destination_id'] )    ? absint( $_POST['destination_id'] )                                : 0;
    $destination_title = isset( $_POST['destination_title'] ) ? sanitize_text_field( wp_unslash( $_POST['destination_title'] ) ) : '';

    // Build email
    $admin_email = get_option( 'admin_email' );
    $site_name   = get_bloginfo( 'name' );
    $subject     = sprintf( '[%s] New Inquiry: %s', $site_name, $destination_title );

    $body  = "New destination inquiry received:\n\n";
    $body .= "Destination: {$destination_title}\n";
    if ( $destination_id ) {
        $body .= "Destination URL: " . get_permalink( $destination_id ) . "\n";
    }
    $body .= "\n--- Guest Details ---\n\n";
    $body .= "Name:            {$name}\n";
    $body .= "Email:           {$email}\n";
    if ( $phone ) {
        $body .= "Phone:           {$phone}\n";
    }
    if ( $preferred_dates ) {
        $body .= "Preferred Dates: {$preferred_dates}\n";
    }
    if ( $num_guests ) {
        $body .= "Number of Guests: {$num_guests}\n";
    }
    if ( $message ) {
        $body .= "\n--- Message ---\n\n{$message}\n";
    }

    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
        'Reply-To: ' . $name . ' <' . $email . '>',
    ];

    $sent = wp_mail( $admin_email, $subject, $body, $headers );

    if ( $sent ) {
        do_action( 'oc_crm_event', 'inquiry.destination', [
            'name'             => $name,
            'email'            => $email,
            'phone'            => $phone,
            'message'          => $message,
            'destination_id'   => $destination_id,
            'destination_name' => $destination_title,
        ] );
        wp_send_json_success( [ 'message' => 'Thank you for your inquiry! We\'ll be in touch soon.' ] );
    } else {
        wp_send_json_error( [ 'message' => 'We couldn\'t send your inquiry right now. Please try again or contact us via WhatsApp.' ] );
    }
}

// ── Itinerary Booking Handler ────────────────────────────────────────────────

add_action( 'wp_ajax_oc_itinerary_booking',        'oc_handle_itinerary_booking' );
add_action( 'wp_ajax_nopriv_oc_itinerary_booking',  'oc_handle_itinerary_booking' );

/**
 * Process the itinerary booking form submission.
 */
function oc_handle_itinerary_booking() {

    // Verify nonce
    if ( ! isset( $_POST['oc_booking_nonce'] ) || ! wp_verify_nonce( $_POST['oc_booking_nonce'], 'oc_itinerary_booking' ) ) {
        wp_send_json_error( [ 'message' => 'Security check failed. Please refresh the page and try again.' ] );
    }

    // Required fields
    $required = [
        'guest_name'  => 'Full Name',
        'guest_email' => 'Email',
        'guest_phone' => 'Phone',
        'guest_count' => 'Number of Guests',
        'start_date'  => 'Start Date',
    ];

    foreach ( $required as $field => $label ) {
        if ( empty( $_POST[ $field ] ) ) {
            wp_send_json_error( [ 'message' => sprintf( '%s is required.', $label ) ] );
        }
    }

    // Sanitize inputs
    $name       = sanitize_text_field( wp_unslash( $_POST['guest_name'] ) );
    $email      = sanitize_email( wp_unslash( $_POST['guest_email'] ) );
    $phone      = sanitize_text_field( wp_unslash( $_POST['guest_phone'] ) );
    $guests     = absint( $_POST['guest_count'] );
    $start_date = sanitize_text_field( wp_unslash( $_POST['start_date'] ) );
    $end_date   = isset( $_POST['end_date'] ) ? sanitize_text_field( wp_unslash( $_POST['end_date'] ) ) : '';
    $requests   = isset( $_POST['special_requests'] ) ? sanitize_textarea_field( wp_unslash( $_POST['special_requests'] ) ) : '';

    // Itinerary info
    $itinerary_id    = isset( $_POST['itinerary_id'] )    ? absint( $_POST['itinerary_id'] )                                : 0;
    $itinerary_title = isset( $_POST['itinerary_title'] ) ? sanitize_text_field( wp_unslash( $_POST['itinerary_title'] ) ) : '';
    $duration        = isset( $_POST['itinerary_duration'] ) ? sanitize_text_field( wp_unslash( $_POST['itinerary_duration'] ) ) : '';

    // Validate email
    if ( ! is_email( $email ) ) {
        wp_send_json_error( [ 'message' => 'Please enter a valid email address.' ] );
    }

    // Validate guest count
    if ( $guests < 1 ) {
        wp_send_json_error( [ 'message' => 'Number of guests must be at least 1.' ] );
    }

    // Build email
    $admin_email = get_option( 'admin_email' );
    $site_name   = get_bloginfo( 'name' );
    $subject     = sprintf( '[%s] Itinerary Booking Request: %s', $site_name, $itinerary_title );

    $body  = "New itinerary booking request received:\n\n";
    $body .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    $body .= "ITINERARY DETAILS\n";
    $body .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    $body .= sprintf( "Itinerary: %s\n", $itinerary_title );
    $body .= sprintf( "Duration:  %s\n", $duration );
    if ( $itinerary_id ) {
        $body .= sprintf( "View:      %s\n", get_permalink( $itinerary_id ) );
    }
    $body .= "\n";
    $body .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    $body .= "TRAVEL DATES\n";
    $body .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    $body .= sprintf( "Start:     %s\n", $start_date );
    $body .= sprintf( "End:       %s\n", $end_date );
    $body .= "\n";
    $body .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    $body .= "GUEST INFORMATION\n";
    $body .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    $body .= sprintf( "Name:      %s\n", $name );
    $body .= sprintf( "Email:     %s\n", $email );
    $body .= sprintf( "Phone:     %s\n", $phone );
    $body .= sprintf( "Guests:    %d\n", $guests );

    if ( $requests ) {
        $body .= "\n";
        $body .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $body .= "SPECIAL REQUESTS\n";
        $body .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $body .= $requests . "\n";
    }

    $body .= "\n---\nSent from the booking form on " . home_url() . "\n";

    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
        'Reply-To: ' . $name . ' <' . $email . '>',
    ];

    $sent = wp_mail( $admin_email, $subject, $body, $headers );

    if ( $sent ) {
        do_action( 'oc_crm_event', 'inquiry.itinerary', [
            'name'            => $name,
            'email'           => $email,
            'phone'           => $phone,
            'guests'          => $guests,
            'start_date'      => $start_date,
            'end_date'        => $end_date,
            'itinerary_id'    => $itinerary_id,
            'itinerary_title' => $itinerary_title,
        ] );
        wp_send_json_success( [
            'message' => 'Your booking request has been sent successfully! We will contact you within 24 hours to confirm availability.',
        ] );
    } else {
        wp_send_json_error( [
            'message' => 'We could not send your request at this time. Please try again or contact us directly.',
        ] );
    }
}

// ── Offer Inquiry Handler ────────────────────────────────────────────────────

add_action( 'wp_ajax_oc_offer_inquiry',        'oc_handle_offer_inquiry' );
add_action( 'wp_ajax_nopriv_oc_offer_inquiry',  'oc_handle_offer_inquiry' );

/**
 * Process the offer inquiry form submission.
 */
function oc_handle_offer_inquiry() {

    // Verify nonce
    if ( ! isset( $_POST['oc_inquiry_nonce'] ) || ! wp_verify_nonce( $_POST['oc_inquiry_nonce'], 'oc_offer_inquiry' ) ) {
        wp_send_json_error( 'Security check failed. Please refresh the page and try again.' );
    }

    $name       = sanitize_text_field( wp_unslash( $_POST['inq_name']    ?? '' ) );
    $email      = sanitize_email( wp_unslash( $_POST['inq_email']        ?? '' ) );
    $phone      = sanitize_text_field( wp_unslash( $_POST['inq_phone']   ?? '' ) );
    $message    = sanitize_textarea_field( wp_unslash( $_POST['inq_message'] ?? '' ) );
    $offer_name = sanitize_text_field( wp_unslash( $_POST['offer_name']  ?? '' ) );
    $offer_id   = intval( $_POST['offer_id'] ?? 0 );

    // Validate required fields
    if ( empty( $name ) || empty( $email ) ) {
        wp_send_json_error( 'Please fill in your name and email address.' );
    }

    if ( ! is_email( $email ) ) {
        wp_send_json_error( 'Please enter a valid email address.' );
    }

    // Build the email
    $admin_email = get_option( 'admin_email' );
    $site_name   = get_bloginfo( 'name' );

    $subject = sprintf( '[%s] New Offer Enquiry: %s', $site_name, $offer_name ?: 'General' );

    $body  = "New offer enquiry received:\n\n";
    $body .= "Offer: {$offer_name}\n";
    if ( $offer_id ) {
        $body .= "Offer URL: " . get_permalink( $offer_id ) . "\n";
    }
    $body .= "\n--- Guest Details ---\n\n";
    $body .= "Name:    {$name}\n";
    $body .= "Email:   {$email}\n";
    if ( $phone ) {
        $body .= "Phone:   {$phone}\n";
    }
    if ( $message ) {
        $body .= "\n--- Message ---\n\n{$message}\n";
    }
    $body .= "\n---\n";
    $body .= "Sent from: {$site_name}\n";

    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
        'Reply-To: ' . $name . ' <' . $email . '>',
    ];

    $sent = wp_mail( $admin_email, $subject, $body, $headers );

    if ( $sent ) {
        do_action( 'oc_crm_event', 'inquiry.offer', [
            'name'       => $name,
            'email'      => $email,
            'phone'      => $phone,
            'offer_id'   => $offer_id,
            'offer_name' => $offer_name,
        ] );
        wp_send_json_success( 'Thank you! Your enquiry has been sent. We will be in touch shortly.' );
    } else {
        wp_send_json_error( 'We could not send your enquiry at this time. Please try WhatsApp or call us directly.' );
    }
}

// ── Service Inquiry Handler ─────────────────────────────────────────────────

add_action( 'wp_ajax_oc_service_inquiry',        'oc_handle_service_inquiry' );
add_action( 'wp_ajax_nopriv_oc_service_inquiry',  'oc_handle_service_inquiry' );

/**
 * Process the service inquiry form submission.
 */
function oc_handle_service_inquiry() {

    if ( ! isset( $_POST['oc_inquiry_nonce'] ) || ! wp_verify_nonce( $_POST['oc_inquiry_nonce'], 'oc_service_inquiry' ) ) {
        wp_send_json_error( 'Security check failed. Please refresh the page and try again.' );
    }

    $name         = sanitize_text_field( wp_unslash( $_POST['inq_name']    ?? '' ) );
    $email        = sanitize_email( wp_unslash( $_POST['inq_email']        ?? '' ) );
    $phone        = sanitize_text_field( wp_unslash( $_POST['inq_phone']   ?? '' ) );
    $message      = sanitize_textarea_field( wp_unslash( $_POST['inq_message'] ?? '' ) );
    $service_name = sanitize_text_field( wp_unslash( $_POST['service_name'] ?? '' ) );
    $service_id   = intval( $_POST['service_id'] ?? 0 );

    if ( empty( $name ) || empty( $email ) ) {
        wp_send_json_error( 'Please fill in your name and email address.' );
    }
    if ( ! is_email( $email ) ) {
        wp_send_json_error( 'Please enter a valid email address.' );
    }

    $admin_email = get_option( 'admin_email' );
    $site_name   = get_bloginfo( 'name' );
    $subject     = sprintf( '[%s] Service Enquiry: %s', $site_name, $service_name ?: 'General' );

    $body  = "New service enquiry received:\n\n";
    $body .= "Service: {$service_name}\n";
    if ( $service_id ) {
        $body .= "Service URL: " . get_permalink( $service_id ) . "\n";
    }
    $body .= "\n--- Guest Details ---\n\n";
    $body .= "Name:    {$name}\n";
    $body .= "Email:   {$email}\n";
    if ( $phone ) $body .= "Phone:   {$phone}\n";
    if ( $message ) $body .= "\n--- Message ---\n\n{$message}\n";
    $body .= "\n---\nSent from: {$site_name}\n";

    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
        'Reply-To: ' . $name . ' <' . $email . '>',
    ];

    $sent = wp_mail( $admin_email, $subject, $body, $headers );

    if ( $sent ) {
        do_action( 'oc_crm_event', 'inquiry.service', [
            'name'         => $name,
            'email'        => $email,
            'phone'        => $phone,
            'service_id'   => $service_id,
            'service_name' => $service_name,
        ] );
        wp_send_json_success( 'Thank you! Your enquiry has been sent. We will be in touch shortly.' );
    } else {
        wp_send_json_error( 'We could not send your enquiry at this time. Please try WhatsApp or call us directly.' );
    }
}

// ── Contact Form Handler (OC_Contact_Form_Widget) ───────────────────────────

add_action( 'wp_ajax_oc_contact_form_submit',        'oc_contact_form_handler' );
add_action( 'wp_ajax_nopriv_oc_contact_form_submit', 'oc_contact_form_handler' );

/**
 * Process the generic contact form submission (with optional signature).
 */
function oc_contact_form_handler() {

    // Nonce check
    if ( ! isset( $_POST['oc_cf_nonce'] ) || ! wp_verify_nonce( $_POST['oc_cf_nonce'], 'oc_contact_form' ) ) {
        wp_send_json_error( [ 'message' => __( 'Security check failed. Please refresh the page and try again.', 'ocean-charter' ) ] );
    }

    // Minimum required: name + email
    $name  = isset( $_POST['name'] )  ? sanitize_text_field( wp_unslash( $_POST['name'] ) )  : '';
    $email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) )      : '';

    if ( empty( $name ) ) {
        wp_send_json_error( [ 'message' => __( 'Please enter your name.', 'ocean-charter' ) ] );
    }
    if ( empty( $email ) || ! is_email( $email ) ) {
        wp_send_json_error( [ 'message' => __( 'Please enter a valid email address.', 'ocean-charter' ) ] );
    }

    // Build field table (skip internal keys)
    $skip_keys   = [ 'action', 'oc_cf_nonce', '_wp_http_referer', 'signature_data', 'email_recipient' ];
    $fields_html = '';

    foreach ( $_POST as $key => $value ) {
        if ( in_array( $key, $skip_keys, true ) ) continue;

        $label = ucwords( str_replace( [ '_', '-' ], ' ', $key ) );
        $val   = is_array( $value )
            ? implode( ', ', array_map( 'sanitize_text_field', $value ) )
            : sanitize_text_field( wp_unslash( $value ) );

        if ( $val === '' ) continue;

        $fields_html .= '<tr>
            <td style="padding:8px 12px;border:1px solid #e5e7eb;font-weight:600;color:#374151;background:#f9fafb;width:140px;vertical-align:top;">'
            . esc_html( $label ) . '</td>
            <td style="padding:8px 12px;border:1px solid #e5e7eb;color:#111827;">'
            . esc_html( $val ) . '</td>
        </tr>';
    }

    // Signature handling
    $signature_path = '';
    $sig_data       = isset( $_POST['signature_data'] ) ? $_POST['signature_data'] : '';

    if ( ! empty( $sig_data ) && strpos( $sig_data, 'data:image/png;base64,' ) === 0 ) {
        $upload_dir = wp_upload_dir();
        $sig_dir    = $upload_dir['basedir'] . '/oc-signatures';

        if ( ! file_exists( $sig_dir ) ) {
            wp_mkdir_p( $sig_dir );
            file_put_contents( $sig_dir . '/.htaccess', "Options -Indexes\n" );
            file_put_contents( $sig_dir . '/index.php', "<?php\n// Silence is golden.\n" );
        }

        $raw      = base64_decode( str_replace( 'data:image/png;base64,', '', $sig_data ) );
        $filename = 'sig-' . wp_generate_uuid4() . '.png';

        if ( $raw && file_put_contents( $sig_dir . '/' . $filename, $raw ) ) {
            $signature_path = $sig_dir . '/' . $filename;
            $signature_url  = $upload_dir['baseurl'] . '/oc-signatures/' . $filename;

            $fields_html .= '<tr>
                <td style="padding:8px 12px;border:1px solid #e5e7eb;font-weight:600;color:#374151;background:#f9fafb;width:140px;vertical-align:top;">Signature</td>
                <td style="padding:8px 12px;border:1px solid #e5e7eb;color:#111827;">
                    <img src="' . esc_url( $signature_url ) . '" alt="Signature" style="max-width:300px;height:auto;">
                </td>
            </tr>';
        }
    }

    // Build HTML email
    $site_name = get_bloginfo( 'name' );
    $subject   = sprintf( __( '[%s] New Contact Form Submission from %s', 'ocean-charter' ), $site_name, $name );

    $body = '<!DOCTYPE html><html><head><meta charset="utf-8"></head><body style="font-family:Arial,sans-serif;color:#111827;margin:0;padding:0;">
    <div style="max-width:600px;margin:20px auto;background:#ffffff;border-radius:8px;overflow:hidden;border:1px solid #e5e7eb;">
        <div style="background:#0a101a;padding:24px 30px;">
            <h1 style="margin:0;font-size:20px;color:#d9b230;">' . esc_html( $site_name ) . '</h1>
            <p style="margin:6px 0 0;font-size:14px;color:#8a9bb0;">New contact form submission</p>
        </div>
        <div style="padding:24px 30px;">
            <table style="width:100%;border-collapse:collapse;font-size:14px;">'
            . $fields_html .
            '</table>
        </div>
        <div style="padding:16px 30px;background:#f9fafb;font-size:12px;color:#6b7280;border-top:1px solid #e5e7eb;">
            Sent from ' . esc_html( home_url() ) . ' on ' . esc_html( current_time( 'F j, Y \a\t g:i A' ) ) . '
        </div>
    </div>
    </body></html>';

    // Determine recipient
    $to = isset( $_POST['email_recipient'] ) ? sanitize_email( wp_unslash( $_POST['email_recipient'] ) ) : '';
    if ( empty( $to ) || ! is_email( $to ) ) {
        $to = get_option( 'admin_email' );
    }

    $headers = [
        'Content-Type: text/html; charset=UTF-8',
        'Reply-To: ' . $name . ' <' . $email . '>',
    ];

    $attachments = [];
    if ( ! empty( $signature_path ) && file_exists( $signature_path ) ) {
        $attachments[] = $signature_path;
    }

    $sent = wp_mail( $to, $subject, $body, $headers, $attachments );

    if ( $sent ) {
        do_action( 'oc_crm_event', 'contact.form', [
            'name'    => $name,
            'email'   => $email,
            'phone'   => isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '',
            'subject' => isset( $_POST['subject'] ) ? sanitize_text_field( wp_unslash( $_POST['subject'] ) ) : '',
            'message' => isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '',
        ] );
        wp_send_json_success( [ 'message' => __( 'Thank you! We\'ll be in touch shortly.', 'ocean-charter' ) ] );
    } else {
        wp_send_json_error( [ 'message' => __( 'Unable to send your message. Please try again later.', 'ocean-charter' ) ] );
    }
}
