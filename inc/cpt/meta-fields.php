<?php
/**
 * Ocean Charter — Meta Boxes
 *
 * Provides the back-end editing UI for all Ocean Charter CPTs.
 * Uses WordPress native meta boxes — no ACF or third-party plugins required.
 *
 * Every field stored as a dedicated post meta key prefixed _oc_.
 * Repeater fields (activities, features, inclusions, route stops) are
 * stored as JSON arrays in a single meta key.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* ============================================================
   Enqueue admin scripts + styles (only on OC CPT screens)
   ============================================================ */
add_action( 'admin_enqueue_scripts', function ( $hook ) {
    $screen = get_current_screen();
    $oc_types = [
        'oc_itinerary', 'oc_itinerary_day', 'oc_service', 'oc_package',
        'oc_testimonial', 'oc_destination', 'oc_vessel', 'oc_team_member',
        'oc_faq', 'oc_offer', 'oc_press',
    ];
    if ( ! $screen || ! in_array( $screen->post_type, $oc_types, true ) ) return;

    wp_enqueue_media();
    wp_enqueue_style(  'oc-admin', get_template_directory_uri() . '/inc/cpt/admin.css', [], OC_VERSION );
    wp_enqueue_script( 'oc-admin', get_template_directory_uri() . '/inc/cpt/admin.js',  [ 'jquery' ], OC_VERSION, true );
} );

/* ============================================================
   Helper: text / textarea / number / select fields
   ============================================================ */
function oc_meta_field( string $meta_key, string $label, $value, string $type = 'text', array $opts = [] ): void {
    $id = esc_attr( $meta_key );
    echo "<div class='oc-field'><label for='{$id}'>{$label}</label>";

    if ( $type === 'textarea' ) {
        echo "<textarea id='{$id}' name='{$id}' rows='4'>" . esc_textarea( $value ) . '</textarea>';
    } elseif ( $type === 'select' ) {
        echo "<select id='{$id}' name='{$id}'>";
        foreach ( $opts as $k => $v ) {
            echo "<option value='" . esc_attr( $k ) . "'" . selected( $value, $k, false ) . '>' . esc_html( $v ) . '</option>';
        }
        echo '</select>';
    } else {
        $attrs = $type === 'number' ? " step='any'" : '';
        echo "<input type='{$type}' id='{$id}' name='{$id}' value='" . esc_attr( $value ) . "'{$attrs}>";
    }

    if ( ! empty( $opts['description'] ) ) {
        echo '<p class="description">' . esc_html( $opts['description'] ) . '</p>';
    }
    echo '</div>';
}

/* ============================================================
   Helper: image upload field
   ============================================================ */
function oc_meta_image( string $meta_key, string $label, string $url ): void {
    $id = esc_attr( $meta_key );
    echo "<div class='oc-field oc-field--image'>
        <label>{$label}</label>
        <div class='oc-image-preview'>" . ( $url ? "<img src='" . esc_url( $url ) . "' style='max-width:200px;max-height:150px;display:block;margin-bottom:8px;'>" : '' ) . "</div>
        <input type='hidden' id='{$id}' name='{$id}' value='" . esc_attr( $url ) . "'>
        <button type='button' class='button oc-upload-btn' data-target='{$id}' data-preview='{$id}_preview'>
            " . ( $url ? 'Change Image' : 'Upload Image' ) . "
        </button>
        " . ( $url ? "<button type='button' class='button oc-remove-image' data-target='{$id}'>Remove</button>" : '' ) . "
    </div>";
}

/* ============================================================
   Helper: JSON repeater (activities, features, inclusions, etc.)
   Each repeater row is just a text input.
   ============================================================ */
function oc_meta_repeater( string $meta_key, string $label, array $items, string $placeholder = 'Enter item' ): void {
    echo "<div class='oc-field oc-repeater' data-meta='" . esc_attr( $meta_key ) . "'>
        <label>{$label}</label>
        <div class='oc-repeater-rows'>";

    $items = $items ?: [''];
    foreach ( $items as $item ) {
        echo "<div class='oc-repeater-row'>
            <input type='text' class='oc-repeater-input' value='" . esc_attr( $item ) . "' placeholder='" . esc_attr( $placeholder ) . "'>
            <button type='button' class='oc-repeater-remove dashicons-before dashicons-no-alt' title='Remove'></button>
        </div>";
    }

    echo "</div>
        <button type='button' class='button oc-repeater-add'>+ Add Item</button>
        <input type='hidden' name='" . esc_attr( $meta_key ) . "' class='oc-repeater-value'>
    </div>";
}

/* ============================================================
   Helper: Route stops repeater (name + lat/lng for Leaflet map)
   ============================================================ */
function oc_meta_route_stops( string $meta_key, array $stops ): void {
    echo "<div class='oc-field oc-route-stops' data-meta='" . esc_attr( $meta_key ) . "'>
        <label>Route Stops <span class='description'>(Name, Latitude, Longitude)</span></label>
        <div class='oc-route-rows'>";

    $stops = $stops ?: [ [ 'name' => '', 'lat' => 0, 'lng' => 0 ] ];
    foreach ( $stops as $stop ) {
        $name = esc_attr( $stop['name'] ?? '' );
        $lat  = esc_attr( $stop['lat'] ?? 0 );
        $lng  = esc_attr( $stop['lng'] ?? 0 );
        echo "<div class='oc-route-row'>
            <input type='text'   class='oc-stop-name' value='{$name}' placeholder='Stop name'>
            <input type='number' class='oc-stop-lat'  value='{$lat}'  placeholder='Lat' step='any' style='width:100px'>
            <input type='number' class='oc-stop-lng'  value='{$lng}'  placeholder='Lng' step='any' style='width:100px'>
            <button type='button' class='oc-route-remove dashicons-before dashicons-no-alt' title='Remove'></button>
        </div>";
    }

    echo "</div>
        <button type='button' class='button oc-route-add'>+ Add Stop</button>
        <input type='hidden' name='" . esc_attr( $meta_key ) . "' class='oc-route-value'>
    </div>";
}

/* ============================================================
   ITINERARY meta box
   ============================================================ */
add_action( 'add_meta_boxes', function () {

    add_meta_box( 'oc_itinerary_details', 'Itinerary Details', function ( $post ) {
        wp_nonce_field( 'oc_save_itinerary', 'oc_itinerary_nonce' );

        $g   = fn( $k ) => get_post_meta( $post->ID, $k, true );
        $stops = json_decode( $g( '_oc_route_stops' ) ?: '[]', true );

        echo '<div class="oc-meta-grid">';
        oc_meta_field( '_oc_subtitle',     'Subtitle / Tagline',   $g( '_oc_subtitle' ) );
        oc_meta_field( '_oc_duration',     'Duration',             $g( '_oc_duration' ),   'text', [] );
        oc_meta_field( '_oc_region',       'Region',               $g( '_oc_region' ) );
        oc_meta_field( '_oc_tags',         'Hero Tags (comma-sep)', $g( '_oc_tags' ) );
        oc_meta_field( '_oc_price',        'Starting Price',       $g( '_oc_price' ),      'text' );
        oc_meta_field( '_oc_price_period', 'Price Period',         $g( '_oc_price_period' ) ?: 'per charter' );
        oc_meta_field( '_oc_price_note',   'Price Note',           $g( '_oc_price_note' ), 'textarea' );
        oc_meta_field( '_oc_card_title',   'Booking Card Title (legacy)', $g( '_oc_card_title' ) );
        oc_meta_field( '_oc_cta_url',      'Booking CTA URL (blank = detail page)', $g( '_oc_cta_url' ) );
        oc_meta_field( '_oc_whatsapp',     'WhatsApp Number',      $g( '_oc_whatsapp' ) );
        echo '</div>';

        // Inclusions repeater
        $inclusions = json_decode( $g( '_oc_inclusions' ) ?: '[]', true );
        oc_meta_repeater( '_oc_inclusions', "What's Included (sidebar list)", $inclusions, 'e.g. All meals & beverages' );

        // Route stops
        oc_meta_route_stops( '_oc_route_stops', $stops );

    }, 'oc_itinerary', 'normal', 'high' );

    // Itinerary Days sub-list (shows all days for this itinerary)
    add_meta_box( 'oc_itinerary_days', 'Itinerary Days', function ( $post ) {
        $days = get_posts( [
            'post_type'      => 'oc_itinerary_day',
            'posts_per_page' => -1,
            'meta_key'       => '_oc_parent_itinerary',
            'meta_value'     => $post->ID,
            'orderby'        => 'meta_value_num',
            'meta_query'     => [ [ 'key' => '_oc_day_number', 'type' => 'NUMERIC' ] ],
            'order'          => 'ASC',
        ] );
        echo '<table class="widefat striped"><thead><tr><th>Day #</th><th>Location</th><th>Actions</th></tr></thead><tbody>';
        foreach ( $days as $day ) {
            $num = get_post_meta( $day->ID, '_oc_day_number', true );
            $loc = get_post_meta( $day->ID, '_oc_location', true );
            echo '<tr><td>' . esc_html( $num ) . '</td><td>' . esc_html( $loc ?: $day->post_title ) . '</td>'
               . '<td><a href="' . get_edit_post_link( $day->ID ) . '">Edit</a></td></tr>';
        }
        if ( ! $days ) {
            echo '<tr><td colspan="3">No days added yet.</td></tr>';
        }
        echo '</tbody></table>';
        $add_url = admin_url( 'post-new.php?post_type=oc_itinerary_day&parent_itinerary=' . $post->ID );
        echo '<p><a href="' . esc_url( $add_url ) . '" class="button button-primary">+ Add New Day</a></p>';
    }, 'oc_itinerary', 'normal', 'default' );

} );

/* ============================================================
   ITINERARY DAY meta box
   ============================================================ */
add_action( 'add_meta_boxes', function () {

    add_meta_box( 'oc_day_details', 'Day Details', function ( $post ) {
        wp_nonce_field( 'oc_save_day', 'oc_day_nonce' );

        $g = fn( $k ) => get_post_meta( $post->ID, $k, true );

        // Pre-fill parent itinerary from URL param (new posts)
        $parent_id = $g( '_oc_parent_itinerary' ) ?: absint( $_GET['parent_itinerary'] ?? 0 );

        // Itinerary select
        $itineraries = get_posts( [ 'post_type' => 'oc_itinerary', 'posts_per_page' => -1 ] );
        $itin_opts   = [ '' => '— Select Itinerary —' ];
        foreach ( $itineraries as $it ) { $itin_opts[ $it->ID ] = $it->post_title; }

        echo '<div class="oc-meta-grid">';
        oc_meta_field( '_oc_parent_itinerary', 'Parent Itinerary', $parent_id, 'select', $itin_opts );
        oc_meta_field( '_oc_day_number',  'Day Number',  $g( '_oc_day_number' ),  'number' );
        oc_meta_field( '_oc_location',    'Location',    $g( '_oc_location' ) );
        oc_meta_field( '_oc_description', 'Description', $g( '_oc_description' ), 'textarea' );
        echo '</div>';

        // Activities repeater
        $acts = json_decode( $g( '_oc_activities' ) ?: '[]', true );
        oc_meta_repeater( '_oc_activities', 'Activities (highlight tags)', $acts, 'e.g. Sunset cocktails on deck' );

        // Images
        echo '<div class="oc-meta-grid">';
        oc_meta_image( '_oc_image_a', 'Morning Image',   $g( '_oc_image_a' ) );
        oc_meta_image( '_oc_image_b', 'Afternoon Image', $g( '_oc_image_b' ) );
        echo '</div>';

    }, 'oc_itinerary_day', 'normal', 'high' );

} );

/* ============================================================
   SERVICE meta box
   ============================================================ */
add_action( 'add_meta_boxes', function () {

    add_meta_box( 'oc_service_details', 'Service Details', function ( $post ) {
        wp_nonce_field( 'oc_save_service', 'oc_service_nonce' );

        $g = fn( $k ) => get_post_meta( $post->ID, $k, true );

        echo '<div class="oc-meta-grid">';
        oc_meta_field( '_oc_eyebrow',    'Eyebrow Label',  $g( '_oc_eyebrow' ) );
        oc_meta_field( '_oc_badge_icon', 'Badge Icon',     $g( '_oc_badge_icon' ), 'select', [
            'chef'      => 'Chef Hat',
            'water'     => 'Water Toys',
            'events'    => 'Events',
            'concierge' => 'Concierge',
        ] );
        oc_meta_field( '_oc_link_url', 'Card Link URL (blank = detail page)', $g( '_oc_link_url' ) );
        echo '</div>';

        $features = json_decode( $g( '_oc_features' ) ?: '[]', true );
        oc_meta_repeater( '_oc_features', 'Feature Tags', $features, 'e.g. Custom Menus' );

        // Gallery images
        echo '<h4 style="margin:24px 0 8px;">Service Gallery (Bento Layout)</h4>';
        echo '<div class="oc-meta-grid">';
        oc_meta_image( '_oc_svc_gallery_1', 'Gallery Image 1 (large)', $g( '_oc_svc_gallery_1' ) );
        oc_meta_image( '_oc_svc_gallery_2', 'Gallery Image 2', $g( '_oc_svc_gallery_2' ) );
        oc_meta_image( '_oc_svc_gallery_3', 'Gallery Image 3', $g( '_oc_svc_gallery_3' ) );
        oc_meta_image( '_oc_svc_gallery_4', 'Gallery Image 4', $g( '_oc_svc_gallery_4' ) );
        echo '</div>';

        // Service highlights (icon + title + description)
        echo '<h4 style="margin:24px 0 8px;">Experience Highlights</h4>';
        echo '<p class="description">Add up to 4 highlights with icon, title, and description. Icons: star, utensils, music, compass, anchor, shield, heart, sun</p>';
        $highlights = json_decode( $g( '_oc_svc_highlights' ) ?: '[]', true );
        $hl_count   = max( 4, count( $highlights ) );
        for ( $i = 0; $i < $hl_count; $i++ ) {
            $hl = $highlights[ $i ] ?? [ 'icon' => '', 'title' => '', 'desc' => '' ];
            echo '<div style="display:grid;grid-template-columns:120px 1fr 2fr;gap:8px;margin-bottom:8px;align-items:start;">';
            echo '<input type="text" name="_oc_svc_hl_icon[]" value="' . esc_attr( $hl['icon'] ?? '' ) . '" placeholder="star" style="width:100%">';
            echo '<input type="text" name="_oc_svc_hl_title[]" value="' . esc_attr( $hl['title'] ?? '' ) . '" placeholder="Highlight title" style="width:100%">';
            echo '<input type="text" name="_oc_svc_hl_desc[]" value="' . esc_attr( $hl['desc'] ?? '' ) . '" placeholder="Short description (max 120 chars)" maxlength="120" style="width:100%">';
            echo '</div>';
        }

        // Testimonial
        echo '<h4 style="margin:24px 0 8px;">Testimonial Quote</h4>';
        echo '<div class="oc-meta-grid">';
        oc_meta_field( '_oc_svc_testimonial', 'Quote Text', $g( '_oc_svc_testimonial' ), 'textarea' );
        oc_meta_field( '_oc_svc_testimonial_author', 'Author Name', $g( '_oc_svc_testimonial_author' ) );
        echo '</div>';

        // WhatsApp
        echo '<div class="oc-meta-grid">';
        oc_meta_field( '_oc_svc_whatsapp', 'WhatsApp Number', $g( '_oc_svc_whatsapp' ) );
        echo '</div>';

        echo '<p class="description">Set the <strong>card description</strong> in the Excerpt box below, and the <strong>image</strong> as the Featured Image.</p>';

    }, 'oc_service', 'normal', 'high' );

} );

/* ============================================================
   PACKAGE meta box
   ============================================================ */
add_action( 'add_meta_boxes', function () {

    add_meta_box( 'oc_package_details', 'Package Details', function ( $post ) {
        wp_nonce_field( 'oc_save_package', 'oc_package_nonce' );

        $g = fn( $k ) => get_post_meta( $post->ID, $k, true );

        echo '<div class="oc-meta-grid">';
        oc_meta_field( '_oc_tag',      'Badge Tag',    $g( '_oc_tag' ),     'select', [
            'Popular'     => 'Popular',
            'Signature'   => 'Signature',
            'Celebration' => 'Celebration',
            'Featured'    => 'Featured',
        ] );
        oc_meta_field( '_oc_price',    'Price',        $g( '_oc_price' ),    'text' );
        oc_meta_field( '_oc_duration', 'Duration',     $g( '_oc_duration' ), 'text' );
        oc_meta_field( '_oc_cta_url',  'CTA URL (blank = detail page)', $g( '_oc_cta_url' ) );
        echo '</div>';

        $inclusions = json_decode( $g( '_oc_inclusions' ) ?: '[]', true );
        oc_meta_repeater( '_oc_inclusions', 'Inclusions', $inclusions, 'e.g. Professional Captain & Crew' );

        echo '<p class="description">Set the <strong>description</strong> in the Excerpt, and the <strong>card image</strong> as the Featured Image.</p>';

    }, 'oc_package', 'normal', 'high' );

} );

/* ============================================================
   TESTIMONIAL meta box
   ============================================================ */
add_action( 'add_meta_boxes', function () {

    add_meta_box( 'oc_testimonial_details', 'Testimonial Details', function ( $post ) {
        wp_nonce_field( 'oc_save_testimonial', 'oc_testimonial_nonce' );

        $g = fn( $k ) => get_post_meta( $post->ID, $k, true );

        echo '<div class="oc-meta-grid">';
        oc_meta_field( '_oc_quote',          'Quote',          $g( '_oc_quote' ),          'textarea' );
        oc_meta_field( '_oc_author_role',    'Author Role',    $g( '_oc_author_role' ) );
        oc_meta_field( '_oc_charter_location', 'Charter & Location', $g( '_oc_charter_location' ) );
        oc_meta_field( '_oc_is_featured',    'Featured?', $g( '_oc_is_featured' ), 'select', [
            '' => 'Standard',
            '1' => 'Featured (highlighted)',
        ] );
        oc_meta_image( '_oc_avatar_photo',   'Author Photo',   $g( '_oc_avatar_photo' ) );
        echo '</div>';

        echo '<p class="description">The post <strong>Title</strong> is the guest name shown below the quote. You can set an <strong>Author Photo</strong> above, or use the Featured Image sidebar as a fallback.</p>';

    }, 'oc_testimonial', 'normal', 'high' );

} );

/* ============================================================
   DESTINATION meta box
   ============================================================ */
add_action( 'add_meta_boxes', function () {

    add_meta_box( 'oc_destination_details', 'Destination Details', function ( $post ) {
        wp_nonce_field( 'oc_save_destination', 'oc_destination_nonce' );

        $g = fn( $k ) => get_post_meta( $post->ID, $k, true );

        echo '<div class="oc-meta-grid">';
        oc_meta_field( '_oc_vessel_count', 'Number of Vessels',  $g( '_oc_vessel_count' ), 'number' );
        oc_meta_field( '_oc_is_popular',   'Show "Popular" Badge?', $g( '_oc_is_popular' ), 'select', [
            '' => 'No',
            '1' => 'Yes',
        ] );
        oc_meta_field( '_oc_explore_url', 'Explore URL', $g( '_oc_explore_url' ) );
        echo '</div>';

        // ── Linked Itineraries (multi-select) ──
        $linked = get_post_meta( $post->ID, '_oc_linked_itineraries', true );
        $linked = is_array( $linked ) ? $linked : [];
        $itineraries = get_posts( [
            'post_type'      => 'oc_itinerary',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'title',
            'order'          => 'ASC',
        ] );
        if ( $itineraries ) {
            echo '<div class="oc-field">';
            echo '<label><strong>Linked Itineraries</strong></label>';
            echo '<p class="description" style="margin-bottom:8px;">Select itineraries that belong to this destination.</p>';
            echo '<div style="max-height:200px;overflow-y:auto;border:1px solid #ddd;padding:8px;border-radius:4px;">';
            foreach ( $itineraries as $it ) {
                $checked = in_array( $it->ID, $linked ) ? ' checked' : '';
                printf(
                    '<label style="display:block;margin-bottom:4px;"><input type="checkbox" name="_oc_linked_itineraries[]" value="%d"%s> %s</label>',
                    $it->ID,
                    $checked,
                    esc_html( $it->post_title )
                );
            }
            echo '</div></div>';
        }

        // Gallery images
        echo '<div class="oc-meta-grid">';
        oc_meta_image( '_oc_dest_gallery_1', 'Gallery Image 1', $g( '_oc_dest_gallery_1' ) );
        oc_meta_image( '_oc_dest_gallery_2', 'Gallery Image 2', $g( '_oc_dest_gallery_2' ) );
        oc_meta_image( '_oc_dest_gallery_3', 'Gallery Image 3', $g( '_oc_dest_gallery_3' ) );
        oc_meta_image( '_oc_dest_gallery_4', 'Gallery Image 4', $g( '_oc_dest_gallery_4' ) );
        echo '</div>';

        // WhatsApp + additional content
        echo '<div class="oc-meta-grid">';
        oc_meta_field( '_oc_whatsapp', 'WhatsApp Number', $g( '_oc_whatsapp' ) );
        echo '</div>';

        oc_meta_field( '_oc_additional_content', 'Additional Content (below gallery)', $g( '_oc_additional_content' ), 'textarea' );

        echo '<p class="description">Set the <strong>destination description</strong> in the Excerpt, and the <strong>card image</strong> as the Featured Image.</p>';

    }, 'oc_destination', 'normal', 'high' );

} );

/* ============================================================
   VESSEL meta box
   ============================================================ */
add_action( 'add_meta_boxes', function () {

    add_meta_box( 'oc_vessel_details', 'Vessel Details', function ( $post ) {
        wp_nonce_field( 'oc_save_vessel', 'oc_vessel_nonce' );

        $g = fn( $k ) => get_post_meta( $post->ID, $k, true );

        echo '<div class="oc-meta-grid">';
        oc_meta_field( '_oc_length',        'Length (e.g. 45m)',        $g( '_oc_length' ) );
        oc_meta_field( '_oc_guests',        'Max Guests',               $g( '_oc_guests' ),        'number' );
        oc_meta_field( '_oc_cabins',        'Cabins',                   $g( '_oc_cabins' ),        'number' );
        oc_meta_field( '_oc_speed',         'Cruising Speed (knots)',   $g( '_oc_speed' ) );
        oc_meta_field( '_oc_year_built',    'Year Built',               $g( '_oc_year_built' ),    'number' );
        oc_meta_field( '_oc_builder',       'Builder / Shipyard',       $g( '_oc_builder' ) );
        oc_meta_field( '_oc_flag',          'Flag / Registry',          $g( '_oc_flag' ) );
        oc_meta_field( '_oc_home_port',     'Home Port',                $g( '_oc_home_port' ) );
        oc_meta_field( '_oc_price_per_day', 'Price Per Day',            $g( '_oc_price_per_day' ) );
        oc_meta_field( '_oc_price_per_week','Price Per Week',           $g( '_oc_price_per_week' ) );
        oc_meta_field( '_oc_cta_url',       'Booking CTA URL (blank = detail page)', $g( '_oc_cta_url' ) );
        echo '</div>';

        // Amenities repeater
        $amenities = json_decode( $g( '_oc_amenities' ) ?: '[]', true );
        oc_meta_repeater( '_oc_amenities', 'Amenities', $amenities, 'e.g. Jacuzzi' );

        // Key specs repeater
        $specs = json_decode( $g( '_oc_specs' ) ?: '[]', true );
        oc_meta_repeater( '_oc_specs', 'Key Specs (label:value)', $specs, 'e.g. Beam: 9m' );

        // Gallery images
        echo '<div class="oc-meta-grid">';
        oc_meta_image( '_oc_gallery_1', 'Gallery Image 1', $g( '_oc_gallery_1' ) );
        oc_meta_image( '_oc_gallery_2', 'Gallery Image 2', $g( '_oc_gallery_2' ) );
        oc_meta_image( '_oc_gallery_3', 'Gallery Image 3', $g( '_oc_gallery_3' ) );
        oc_meta_image( '_oc_gallery_4', 'Gallery Image 4', $g( '_oc_gallery_4' ) );
        echo '</div>';

    }, 'oc_vessel', 'normal', 'high' );

} );

/* ============================================================
   TEAM MEMBER meta box
   ============================================================ */
add_action( 'add_meta_boxes', function () {

    add_meta_box( 'oc_team_details', 'Team Member Details', function ( $post ) {
        wp_nonce_field( 'oc_save_team', 'oc_team_nonce' );

        $g = fn( $k ) => get_post_meta( $post->ID, $k, true );

        // Vessel select
        $vessels    = get_posts( [ 'post_type' => 'oc_vessel', 'posts_per_page' => -1 ] );
        $vessel_opts = [ '' => '— Select Vessel —' ];
        foreach ( $vessels as $v ) { $vessel_opts[ $v->ID ] = $v->post_title; }

        echo '<div class="oc-meta-grid">';
        oc_meta_field( '_oc_role_title', 'Role / Title',      $g( '_oc_role_title' ) );
        oc_meta_field( '_oc_years_exp',  'Years Experience',  $g( '_oc_years_exp' ),  'number' );
        oc_meta_field( '_oc_vessel_id',  'Assigned Vessel',   $g( '_oc_vessel_id' ),  'select', $vessel_opts );
        echo '</div>';

        // Bio textarea
        oc_meta_field( '_oc_bio', 'Biography', $g( '_oc_bio' ), 'textarea' );

        // Certifications repeater
        $certs = json_decode( $g( '_oc_certifications' ) ?: '[]', true );
        oc_meta_repeater( '_oc_certifications', 'Certifications', $certs, 'e.g. STCW95' );

        // Languages repeater
        $langs = json_decode( $g( '_oc_languages' ) ?: '[]', true );
        oc_meta_repeater( '_oc_languages', 'Languages Spoken', $langs, 'e.g. English' );

    }, 'oc_team_member', 'normal', 'high' );

} );

/* ============================================================
   FAQ meta box
   ============================================================ */
add_action( 'add_meta_boxes', function () {

    add_meta_box( 'oc_faq_details', 'FAQ Details', function ( $post ) {
        wp_nonce_field( 'oc_save_faq', 'oc_faq_nonce' );

        $g = fn( $k ) => get_post_meta( $post->ID, $k, true );

        // Answer textarea
        oc_meta_field( '_oc_answer', 'Answer', $g( '_oc_answer' ), 'textarea' );

        // Sort order
        echo '<div class="oc-meta-grid">';
        oc_meta_field( '_oc_sort_order', 'Sort Order (lower = first)', $g( '_oc_sort_order' ), 'number' );
        echo '</div>';

    }, 'oc_faq', 'normal', 'high' );

} );

/* ============================================================
   OFFER meta box
   ============================================================ */
add_action( 'add_meta_boxes', function () {

    add_meta_box( 'oc_offer_details', 'Offer Details', function ( $post ) {
        wp_nonce_field( 'oc_save_offer', 'oc_offer_nonce' );

        $g = fn( $k ) => get_post_meta( $post->ID, $k, true );

        echo '<div class="oc-meta-grid">';
        oc_meta_field( '_oc_subtitle',       'Subtitle',                         $g( '_oc_subtitle' ) );
        oc_meta_field( '_oc_discount',       'Discount Value e.g. 20% or $2,000', $g( '_oc_discount' ) );
        oc_meta_field( '_oc_discount_type',  'Discount Type',                    $g( '_oc_discount_type' ), 'select', [
            'percent' => 'Percentage',
            'fixed'   => 'Fixed Amount',
            'upgrade' => 'Complimentary Upgrade',
        ] );
        oc_meta_field( '_oc_valid_from',     'Valid From (YYYY-MM-DD)',           $g( '_oc_valid_from' ) );
        oc_meta_field( '_oc_valid_to',       'Valid To (YYYY-MM-DD)',             $g( '_oc_valid_to' ) );
        oc_meta_field( '_oc_badge_text',     'Badge Label e.g. Early Bird',      $g( '_oc_badge_text' ) );
        oc_meta_field( '_oc_cta_url',        'CTA URL (blank = offer detail page)', $g( '_oc_cta_url' ) );
        oc_meta_field( '_oc_is_featured',    'Featured?',                        $g( '_oc_is_featured' ), 'select', [
            ''  => 'Standard',
            '1' => 'Featured',
        ] );
        echo '</div>';

        echo '<p class="description">Set the <strong>offer description</strong> in the Excerpt box below, and the <strong>image</strong> as the Featured Image.</p>';

    }, 'oc_offer', 'normal', 'high' );

} );

/* ============================================================
   PRESS meta box
   ============================================================ */
add_action( 'add_meta_boxes', function () {

    add_meta_box( 'oc_press_details', 'Press Details', function ( $post ) {
        wp_nonce_field( 'oc_save_press', 'oc_press_nonce' );

        $g = fn( $k ) => get_post_meta( $post->ID, $k, true );

        echo '<div class="oc-meta-grid">';
        oc_meta_field( '_oc_publication', 'Publication Name',              $g( '_oc_publication' ) );
        oc_meta_field( '_oc_pub_date',    'Publication Date (YYYY-MM-DD)', $g( '_oc_pub_date' ) );
        oc_meta_field( '_oc_article_url', 'Article URL',                   $g( '_oc_article_url' ) );
        oc_meta_field( '_oc_is_featured', 'Featured?',                     $g( '_oc_is_featured' ), 'select', [
            ''  => 'Standard',
            '1' => 'Featured',
        ] );
        echo '</div>';

        // Pull quote textarea
        oc_meta_field( '_oc_quote', 'Pull Quote', $g( '_oc_quote' ), 'textarea' );

        // Publication logo
        oc_meta_image( '_oc_logo_url', 'Publication Logo', $g( '_oc_logo_url' ) );

    }, 'oc_press', 'normal', 'high' );

} );

/* ============================================================
   SAVE — all CPT meta boxes
   ============================================================ */
add_action( 'save_post', function ( $post_id ) {
    // Bail on autosave / bulk edit / wrong user
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $post_type = get_post_type( $post_id );

    // Nonce checks per type
    $nonces = [
        'oc_itinerary'     => [ 'oc_itinerary_nonce',   'oc_save_itinerary'   ],
        'oc_itinerary_day' => [ 'oc_day_nonce',          'oc_save_day'         ],
        'oc_service'       => [ 'oc_service_nonce',      'oc_save_service'     ],
        'oc_package'       => [ 'oc_package_nonce',      'oc_save_package'     ],
        'oc_testimonial'   => [ 'oc_testimonial_nonce',  'oc_save_testimonial' ],
        'oc_destination'   => [ 'oc_destination_nonce',  'oc_save_destination' ],
        'oc_vessel'        => [ 'oc_vessel_nonce',        'oc_save_vessel'      ],
        'oc_team_member'   => [ 'oc_team_nonce',          'oc_save_team'        ],
        'oc_faq'           => [ 'oc_faq_nonce',           'oc_save_faq'         ],
        'oc_offer'         => [ 'oc_offer_nonce',         'oc_save_offer'       ],
        'oc_press'         => [ 'oc_press_nonce',         'oc_save_press'       ],
    ];

    if ( ! isset( $nonces[ $post_type ] ) ) return;
    [ $nonce_field, $action ] = $nonces[ $post_type ];
    if ( empty( $_POST[ $nonce_field ] ) || ! wp_verify_nonce( $_POST[ $nonce_field ], $action ) ) return;

    // Simple text / number / select fields
    $simple_fields = [
        // Itinerary
        '_oc_subtitle', '_oc_duration', '_oc_region', '_oc_tags',
        '_oc_price', '_oc_price_period', '_oc_price_note', '_oc_card_title',
        '_oc_cta_url', '_oc_whatsapp',
        // Itinerary Day
        '_oc_parent_itinerary', '_oc_day_number', '_oc_location', '_oc_description',
        // Service
        '_oc_eyebrow', '_oc_badge_icon', '_oc_link_url',
        '_oc_svc_gallery_1', '_oc_svc_gallery_2', '_oc_svc_gallery_3', '_oc_svc_gallery_4',
        '_oc_svc_testimonial_author', '_oc_svc_whatsapp',
        // Package
        '_oc_tag',
        // Testimonial
        '_oc_quote', '_oc_author_role', '_oc_charter_location', '_oc_is_featured', '_oc_avatar_photo',
        // Destination
        '_oc_vessel_count', '_oc_is_popular', '_oc_explore_url',
        '_oc_dest_gallery_1', '_oc_dest_gallery_2', '_oc_dest_gallery_3', '_oc_dest_gallery_4',
        // Day images
        '_oc_image_a', '_oc_image_b',
        // Vessel
        '_oc_length', '_oc_guests', '_oc_cabins', '_oc_speed',
        '_oc_year_built', '_oc_builder', '_oc_flag', '_oc_home_port',
        '_oc_price_per_day', '_oc_price_per_week',
        '_oc_gallery_1', '_oc_gallery_2', '_oc_gallery_3', '_oc_gallery_4',
        // Team Member
        '_oc_role_title', '_oc_years_exp', '_oc_vessel_id',
        // FAQ
        '_oc_sort_order',
        // Offer
        '_oc_discount', '_oc_discount_type', '_oc_valid_from', '_oc_valid_to',
        '_oc_badge_text',
        // Press
        '_oc_publication', '_oc_pub_date', '_oc_article_url', '_oc_logo_url',
    ];
    foreach ( $simple_fields as $field ) {
        if ( array_key_exists( $field, $_POST ) ) {
            update_post_meta( $post_id, $field, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
        }
    }

    // JSON repeater fields (already collected by JS as JSON string)
    $json_fields = [
        '_oc_inclusions', '_oc_activities', '_oc_features', '_oc_route_stops',
        '_oc_amenities', '_oc_specs', '_oc_certifications', '_oc_languages',
    ];
    foreach ( $json_fields as $field ) {
        if ( array_key_exists( $field, $_POST ) ) {
            $raw = wp_unslash( $_POST[ $field ] );
            // Validate it's actually JSON
            $decoded = json_decode( $raw, true );
            update_post_meta( $post_id, $field, $decoded !== null ? wp_slash( $raw ) : '[]' );
        }
    }

    // Fields that allow basic HTML (wp_kses_post)
    $kses_fields = [ '_oc_description', '_oc_bio', '_oc_answer', '_oc_quote', '_oc_additional_content', '_oc_svc_testimonial' ];
    foreach ( $kses_fields as $field ) {
        if ( array_key_exists( $field, $_POST ) ) {
            update_post_meta( $post_id, $field,
                wp_kses_post( wp_unslash( $_POST[ $field ] ) ) );
        }
    }

    // Service highlights (icon + title + desc arrays → JSON)
    if ( $post_type === 'oc_service' && ! empty( $_POST['_oc_svc_hl_icon'] ) ) {
        $icons  = array_map( 'sanitize_text_field', wp_unslash( $_POST['_oc_svc_hl_icon'] ) );
        $titles = array_map( 'sanitize_text_field', wp_unslash( $_POST['_oc_svc_hl_title'] ) );
        $descs  = array_map( 'sanitize_text_field', wp_unslash( $_POST['_oc_svc_hl_desc'] ) );
        $highlights = [];
        foreach ( $icons as $i => $icon ) {
            if ( $titles[ $i ] || $icon ) {
                $highlights[] = [
                    'icon'  => $icon,
                    'title' => $titles[ $i ] ?? '',
                    'desc'  => $descs[ $i ] ?? '',
                ];
            }
        }
        update_post_meta( $post_id, '_oc_svc_highlights', wp_slash( wp_json_encode( $highlights ) ) );
    }

    // Linked Itineraries (array of post IDs, stored on oc_destination)
    if ( $post_type === 'oc_destination' ) {
        if ( isset( $_POST['_oc_linked_itineraries'] ) ) {
            $ids = array_map( 'absint', (array) $_POST['_oc_linked_itineraries'] );
            $ids = array_filter( $ids );
            update_post_meta( $post_id, '_oc_linked_itineraries', $ids );
        } else {
            delete_post_meta( $post_id, '_oc_linked_itineraries' );
        }
    }
} );

/* ============================================================
   Hero Controls — page templates (destinations, packages,
   services, itinerary, offers listing)
   ============================================================ */
add_action( 'add_meta_boxes', function () {
    $hero_templates = [
        'page-destinations.php',
        'page-packages.php',
        'page-services.php',
        'page-itinerary.php',
    ];
    // Post types that should get the hero controls meta box. Pages use it via
    // the templates listed above; CPT singles (oc_package, oc_destination,
    // oc_service, bbc_package) have hero sections in their single-* templates.
    $hero_cpts = [ 'page', 'oc_package', 'oc_destination', 'oc_service', 'bbc_package', 'oc_offer', 'oc_itinerary' ];

    $hero_callback = function ( $post ) use ( $hero_templates ) {
            $tpl = get_post_meta( $post->ID, '_wp_page_template', true );
            // Show on any page with one of our listing templates, or on generic pages
            wp_nonce_field( 'oc_hero_controls_save', 'oc_hero_nonce' );

            $height   = get_post_meta( $post->ID, '_oc_hero_height',  true ) ?: '70vh';
            $opacity  = get_post_meta( $post->ID, '_oc_hero_opacity', true );
            $opacity  = ( $opacity !== '' ) ? $opacity : '0.6';
            $color    = get_post_meta( $post->ID, '_oc_hero_color',   true ) ?: '#0a0f1a';
            $position = get_post_meta( $post->ID, '_oc_hero_position', true ) ?: 'center center';
            $position_options = [
                'center center' => 'Center (default)',
                'top left'      => 'Top Left',
                'top center'    => 'Top Center',
                'top right'     => 'Top Right',
                'center left'   => 'Middle Left',
                'center right'  => 'Middle Right',
                'bottom left'   => 'Bottom Left',
                'bottom center' => 'Bottom Center',
                'bottom right'  => 'Bottom Right',
            ];
            ?>
            <div class="oc-field">
                <label for="_oc_hero_height"><strong>Hero Min-Height</strong></label>
                <input type="text" id="_oc_hero_height" name="_oc_hero_height"
                       value="<?php echo esc_attr( $height ); ?>"
                       style="width:200px;" placeholder="e.g. 70vh or 600px">
                <p class="description">Use CSS units: <code>70vh</code>, <code>500px</code>, etc.</p>
            </div>
            <div class="oc-field" style="margin-top:12px;">
                <label for="_oc_hero_opacity"><strong>Overlay Opacity</strong> (0 = transparent, 1 = opaque)</label>
                <input type="number" id="_oc_hero_opacity" name="_oc_hero_opacity"
                       value="<?php echo esc_attr( $opacity ); ?>"
                       min="0" max="1" step="0.05" style="width:100px;">
            </div>
            <div class="oc-field" style="margin-top:12px;">
                <label for="_oc_hero_color"><strong>Overlay Color</strong></label>
                <input type="color" id="_oc_hero_color" name="_oc_hero_color"
                       value="<?php echo esc_attr( $color ); ?>" style="width:60px;height:36px;cursor:pointer;">
                <span style="margin-left:8px;color:#666;font-size:13px;"><?php echo esc_html( $color ); ?></span>
            </div>
            <div class="oc-field" style="margin-top:12px;">
                <label for="_oc_hero_position"><strong>Image Position</strong></label>
                <select id="_oc_hero_position" name="_oc_hero_position" style="width:100%;max-width:260px;">
                    <?php foreach ( $position_options as $val => $label ) : ?>
                        <option value="<?php echo esc_attr( $val ); ?>" <?php selected( $position, $val ); ?>><?php echo esc_html( $label ); ?></option>
                    <?php endforeach; ?>
                </select>
                <p class="description">Controls which part of the image is shown when cropped (e.g. top center for landscape photos).</p>
            </div>
            <div class="oc-field" style="margin-top:12px;">
                <label><strong>Hero Background Image</strong></label>
                <?php
                $hero_img_id  = absint( get_post_meta( $post->ID, '_oc_hero_image', true ) );
                $hero_img_url = $hero_img_id ? wp_get_attachment_image_url( $hero_img_id, 'medium' ) : '';
                ?>
                <div id="_oc_hero_image_preview" style="margin-bottom:8px;">
                    <?php if ( $hero_img_url ) : ?>
                        <img src="<?php echo esc_url( $hero_img_url ); ?>" style="max-width:200px;max-height:120px;display:block;border-radius:4px;">
                    <?php endif; ?>
                </div>
                <input type="hidden" id="_oc_hero_image" name="_oc_hero_image" value="<?php echo esc_attr( $hero_img_id ?: '' ); ?>">
                <button type="button" class="button" id="_oc_hero_image_btn"><?php echo $hero_img_id ? 'Change Image' : 'Select Image'; ?></button>
                <?php if ( $hero_img_id ) : ?>
                    <button type="button" class="button" id="_oc_hero_image_remove" style="margin-left:6px;">Remove</button>
                <?php endif; ?>
                <p class="description" style="margin-top:6px;">Sets the hero background image. Falls back to the page Featured Image if not set.</p>
            </div>
            <script>
            (function($) {
                var frame;
                $('#_oc_hero_image_btn').on('click', function() {
                    if (frame) { frame.open(); return; }
                    frame = wp.media({ title: 'Select Hero Image', button: { text: 'Use this image' }, multiple: false });
                    frame.on('select', function() {
                        var att = frame.state().get('selection').first().toJSON();
                        $('#_oc_hero_image').val(att.id);
                        $('#_oc_hero_image_preview').html('<img src="' + att.url + '" style="max-width:200px;max-height:120px;display:block;border-radius:4px;">');
                        $('#_oc_hero_image_btn').text('Change Image');
                    });
                    frame.open();
                });
                $('#_oc_hero_image_remove').on('click', function() {
                    $('#_oc_hero_image').val('');
                    $('#_oc_hero_image_preview').html('');
                    $('#_oc_hero_image_btn').text('Select Image');
                    $(this).hide();
                });
            })(jQuery);
            </script>
            <?php
    };

    foreach ( $hero_cpts as $_cpt ) {
        add_meta_box(
            'oc-hero-controls',
            '🎨 Hero Section Controls',
            $hero_callback,
            $_cpt,
            'side',
            'high'
        );
    }

    // Crew / Team Member extra fields
    add_meta_box(
        'oc-crew-extra',
        '🎨 Card Appearance',
        function ( $post ) {
            wp_nonce_field( 'oc_crew_extra_save', 'oc_crew_extra_nonce' );
            $tags    = get_post_meta( $post->ID, '_oc_tags',         true ) ?: '';
            $card_bg = get_post_meta( $post->ID, '_oc_card_bg_color', true ) ?: '#111a28';
            ?>
            <div class="oc-field">
                <label for="_oc_tags"><strong>Tags</strong></label>
                <input type="text" id="_oc_tags" name="_oc_tags"
                       value="<?php echo esc_attr( $tags ); ?>"
                       style="width:100%;" placeholder="Comma-separated tags, e.g. Sailing, Navigation, Mediterranean">
            </div>
            <div class="oc-field" style="margin-top:12px;">
                <label for="_oc_card_bg_color"><strong>Card Background Color</strong></label>
                <input type="color" id="_oc_card_bg_color" name="_oc_card_bg_color"
                       value="<?php echo esc_attr( $card_bg ); ?>" style="width:60px;height:36px;cursor:pointer;">
            </div>
            <?php
        },
        'oc_team_member',
        'side',
        'default'
    );
} );

// Save hero controls meta — fires for any post type that shows the meta box
add_action( 'save_post', function ( $post_id ) {
    if ( ! isset( $_POST['oc_hero_nonce'] ) ) return;
    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['oc_hero_nonce'] ) ), 'oc_hero_controls_save' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;
    $allowed_types = [ 'page', 'oc_package', 'oc_destination', 'oc_service', 'bbc_package', 'oc_offer', 'oc_itinerary' ];
    if ( ! in_array( get_post_type( $post_id ), $allowed_types, true ) ) return;

    if ( isset( $_POST['_oc_hero_height'] ) ) {
        update_post_meta( $post_id, '_oc_hero_height', sanitize_text_field( $_POST['_oc_hero_height'] ) );
    }
    if ( isset( $_POST['_oc_hero_opacity'] ) ) {
        $op = floatval( $_POST['_oc_hero_opacity'] );
        $op = max( 0, min( 1, $op ) );
        update_post_meta( $post_id, '_oc_hero_opacity', (string) $op );
    }
    if ( isset( $_POST['_oc_hero_color'] ) ) {
        $color = sanitize_hex_color( $_POST['_oc_hero_color'] );
        update_post_meta( $post_id, '_oc_hero_color', $color ?: '#0a0f1a' );
    }
    if ( isset( $_POST['_oc_hero_image'] ) ) {
        $img_id = absint( $_POST['_oc_hero_image'] );
        if ( $img_id ) {
            update_post_meta( $post_id, '_oc_hero_image', $img_id );
        } else {
            delete_post_meta( $post_id, '_oc_hero_image' );
        }
    }
    if ( isset( $_POST['_oc_hero_position'] ) ) {
        $allowed_positions = [
            'center center', 'top left', 'top center', 'top right',
            'center left', 'center right',
            'bottom left', 'bottom center', 'bottom right',
        ];
        $pos = sanitize_text_field( wp_unslash( $_POST['_oc_hero_position'] ) );
        update_post_meta( $post_id, '_oc_hero_position', in_array( $pos, $allowed_positions, true ) ? $pos : 'center center' );
    }
} );

// Save crew extra fields
add_action( 'save_post_oc_team_member', function ( $post_id ) {
    if ( ! isset( $_POST['oc_crew_extra_nonce'] ) ) return;
    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['oc_crew_extra_nonce'] ) ), 'oc_crew_extra_save' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    if ( isset( $_POST['_oc_tags'] ) ) {
        update_post_meta( $post_id, '_oc_tags', sanitize_text_field( $_POST['_oc_tags'] ) );
    }
    if ( isset( $_POST['_oc_card_bg_color'] ) ) {
        $bg = sanitize_hex_color( $_POST['_oc_card_bg_color'] );
        update_post_meta( $post_id, '_oc_card_bg_color', $bg ?: '#111a28' );
    }
} );
