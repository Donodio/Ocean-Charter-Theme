<?php
/**
 * Custom template tags for this theme
 *
 * @package OceanCharter
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// ── Legacy helpers (kept for backwards-compatibility) ─────────────────────────

/**
 * Custom logo or site title
 */
function ocean_charter_the_custom_logo() {
    if ( has_custom_logo() ) {
        the_custom_logo();
    } else {
        echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="site-title">' . esc_html( get_bloginfo( 'name' ) ) . '</a>';
    }
}

/**
 * Display yacht price/meta helper (uses plugin functions)
 */
function ocean_charter_get_boat_meta_helper( $post_id, $key ) {
    if ( function_exists( 'bbc_get_boat_meta' ) ) {
        return bbc_get_boat_meta( $post_id, $key );
    }
    return get_post_meta( $post_id, $key, true );
}

/**
 * SEO & OpenGraph Meta Tags
 */
function ocean_charter_seo_meta() {
    if ( ! is_singular() ) return;

    $post_id = get_the_ID();
    $excerpt = get_the_excerpt();
    $img_url = get_the_post_thumbnail_url( $post_id, 'large' );

    echo '<meta property="og:title" content="' . esc_attr( get_the_title() ) . '" />' . "\n";
    echo '<meta property="og:description" content="' . esc_attr( $excerpt ) . '" />' . "\n";
    if ( $img_url ) {
        echo '<meta property="og:image" content="' . esc_url( $img_url ) . '" />' . "\n";
    }
    echo '<meta property="og:type" content="website" />' . "\n";
}
add_action( 'wp_head', 'ocean_charter_seo_meta', 5 );

/**
 * Schema.org Markup for Yachts
 */
function ocean_charter_boat_schema() {
    if ( ! is_singular( 'boat' ) ) return;

    $post_id = get_the_ID();
    $length  = ocean_charter_get_boat_meta_helper( $post_id, '_bbc_length' );
    $price   = ocean_charter_get_boat_meta_helper( $post_id, '_bbc_price_half_day' );

    $schema = [
        '@context'    => 'https://schema.org/',
        '@type'       => 'Product',
        'name'        => get_the_title(),
        'description' => get_the_excerpt(),
        'offers'      => [
            '@type'        => 'Offer',
            'price'        => $price,
            'priceCurrency'=> 'USD',
            'availability' => 'https://schema.org/InStock',
        ],
    ];

    echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>' . "\n";
}
add_action( 'wp_footer', 'ocean_charter_boat_schema' );

/**
 * Display the time a post was posted
 */
function ocean_charter_posted_on() {
    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
    if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
        $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
    }

    $time_string = sprintf(
        $time_string,
        esc_attr( get_the_date( DATE_W3C ) ),
        esc_html( get_the_date() ),
        esc_attr( get_the_modified_date( DATE_W3C ) ),
        esc_html( get_the_modified_date() )
    );

    echo '<span class="posted-on">' . $time_string . '</span>';
}

// ── New Ocean Charter helpers ─────────────────────────────────────────────────

/**
 * Build a wa.me WhatsApp URL with optional pre-filled message.
 *
 * @param string $message Optional URL-encoded message text.
 * @return string Escaped WhatsApp URL.
 */
function oc_whatsapp_url( string $message = '' ): string {
    $number = get_theme_mod( 'oc_whatsapp_number', '15551234567' );
    $url    = 'https://wa.me/' . preg_replace( '/[^0-9]/', '', $number );
    if ( $message ) {
        $url .= '?text=' . rawurlencode( $message );
    }
    return esc_url( $url );
}

/**
 * Return up to 3 image URLs for a boat post.
 * Falls back to Pexels vessel constants when ACF/plugin gallery is incomplete.
 *
 * @param int $boat_id Post ID of the boat.
 * @return string[] Array of image URLs (max 3).
 */
function oc_get_boat_gallery( int $boat_id ): array {
    $gallery = get_post_meta( $boat_id, '_bbc_gallery', true );
    $images  = [];

    if ( ! empty( $gallery ) && is_array( $gallery ) ) {
        foreach ( array_slice( $gallery, 0, 3 ) as $att_id ) {
            $url = wp_get_attachment_image_url( (int) $att_id, 'large' );
            if ( $url ) {
                $images[] = $url;
            }
        }
    }

    if ( empty( $images ) ) {
        $thumb = get_the_post_thumbnail_url( $boat_id, 'large' );
        if ( $thumb ) {
            $images[] = $thumb;
        }
    }

    // Pexels fallbacks — constants defined in inc/pexels-images.php
    if ( count( $images ) < 3 ) {
        $fallbacks = [ OC_IMG_VESSEL_1, OC_IMG_VESSEL_2, OC_IMG_VESSEL_3 ];
        $images    = array_merge( $images, array_slice( $fallbacks, 0, 3 - count( $images ) ) );
    }

    return array_slice( $images, 0, 3 );
}

/**
 * Retrieve a single boat meta value (prefixed with _bbc_).
 *
 * @param int    $boat_id  Post ID.
 * @param string $key      Meta key (without _bbc_ prefix).
 * @param string $fallback Value to return when meta is empty.
 * @return string
 */
function oc_boat_meta( int $boat_id, string $key, string $fallback = '' ): string {
    $val = get_post_meta( $boat_id, '_bbc_' . $key, true );
    return ( $val !== '' && $val !== false ) ? esc_html( (string) $val ) : $fallback;
}

/**
 * Return a formatted price string from _bbc_price_day meta.
 *
 * @param int $boat_id Post ID.
 * @return string  e.g. "$12,500" or empty string when no price is set.
 */
function oc_price( int $boat_id ): string {
    $price = get_post_meta( $boat_id, '_bbc_price_day', true );
    return $price ? '$' . number_format( (float) $price ) : '';
}
