<?php
/**
 * Single Itinerary Template
 *
 * @package OceanCharter
 */

get_header();
?>

<main id="primary" class="site-main oc-single-itinerary">

<?php if ( have_posts() ) : the_post();

    $post_id = get_the_ID();

    /* ── Elementor builder pass-through ── */
    $elementor_mode = get_post_meta( $post_id, '_elementor_edit_mode', true );
    if ( 'builder' === $elementor_mode ) :
        the_content();
    else :

    /* ── Meta ── */
    $_oc_hero_img_id = absint( get_post_meta( $post_id, '_oc_hero_image', true ) );
    $_oc_hero_custom = $_oc_hero_img_id ? wp_get_attachment_image_url( $_oc_hero_img_id, 'full' ) : '';
    $hero_img        = $_oc_hero_custom ?: ( get_the_post_thumbnail_url( $post_id, 'full' ) ?: 'https://images.pexels.com/photos/1001682/pexels-photo-1001682.jpeg?auto=compress&cs=tinysrgb&w=1920' );
    $_hero_pos       = get_post_meta( $post_id, '_oc_hero_position', true ) ?: 'center center';
    $region          = get_post_meta( $post_id, '_oc_region', true );
    $duration        = get_post_meta( $post_id, '_oc_duration', true );
    $tags_raw        = get_post_meta( $post_id, '_oc_tags', true );
    $price           = get_post_meta( $post_id, '_oc_price', true );
    $price_period    = get_post_meta( $post_id, '_oc_price_period', true );
    $price_note      = get_post_meta( $post_id, '_oc_price_note', true );
    $card_title      = get_post_meta( $post_id, '_oc_card_title', true );
    $cta_url         = get_post_meta( $post_id, '_oc_cta_url', true );
    $wa_itin         = get_post_meta( $post_id, '_oc_whatsapp', true );
    $inclusions_raw  = get_post_meta( $post_id, '_oc_inclusions', true );
    $route_stops_raw = get_post_meta( $post_id, '_oc_route_stops', true );
    $inclusions      = $inclusions_raw  ? json_decode( $inclusions_raw, true )  : [];
    $route_stops     = $route_stops_raw ? json_decode( $route_stops_raw, true ) : [];

    $tags = $tags_raw ? array_map( 'trim', explode( ',', $tags_raw ) ) : [];

    $wa_url = $wa_itin
        ? 'https://wa.me/' . preg_replace( '/[^0-9]/', '', $wa_itin ) . '?text=' . rawurlencode( 'Hello, I\'d like to book: ' . get_the_title() . '.' )
        : ( function_exists( 'oc_whatsapp_url' ) ? oc_whatsapp_url( 'Hello, I\'d like to book: ' . get_the_title() . '.' ) : 'https://wa.me/' . get_theme_mod( 'oc_whatsapp_number', '15551234567' ) );

    // Day timeline
    $days_q = new WP_Query( [
        'post_type'      => 'oc_itinerary_day',
        'posts_per_page' => 30,
        'meta_query'     => [ [
            'key'     => '_oc_parent_itinerary',
            'value'   => $post_id,
            'compare' => '=',
        ] ],
        'meta_key'       => '_oc_day_number',
        'orderby'        => 'meta_value_num',
        'order'          => 'ASC',
    ] );
    ?>

    <!-- ══ HERO ══════════════════════════════════════════════════════════════ -->
    <section class="oc-itin-hero" style="background-image:url('<?php echo esc_url( $hero_img ); ?>');background-size:cover;background-position:<?php echo esc_attr( $_hero_pos ); ?>;">
        <div class="oc-itin-hero__overlay"></div>
        <div class="oc-itin-hero__content oc-container">
            <?php if ( $region ) : ?>
                <span class="oc-itin-eyebrow"><?php echo esc_html( $region ); ?></span>
            <?php endif; ?>
            <h1 class="oc-itin-hero__title"><?php the_title(); ?></h1>
            <div class="oc-itin-hero__pills">
                <?php if ( $duration ) : ?>
                    <span class="oc-spec-pill">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <?php echo esc_html( $duration ); ?>
                    </span>
                <?php endif; ?>
                <?php foreach ( $tags as $tag ) : if ( $tag ) : ?>
                    <span class="oc-spec-pill"><?php echo esc_html( $tag ); ?></span>
                <?php endif; endforeach; ?>
                <?php if ( $price ) : ?>
                    <span class="oc-spec-pill">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        From $<?php echo esc_html( number_format( (float) $price ) ); ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- ══ BODY ══════════════════════════════════════════════════════════════ -->
    <div class="oc-itin-body oc-container">

        <!-- Left column -->
        <div class="oc-itin-main">

            <!-- Intro -->
            <?php $excerpt = get_the_excerpt(); if ( $excerpt ) : ?>
            <section class="oc-itin-section">
                <h2 class="oc-section-heading">Itinerary at a Glance</h2>
                <p class="oc-itin-intro"><?php echo wp_kses_post( $excerpt ); ?></p>
            </section>
            <?php endif; ?>

            <!-- Day Timeline -->
            <?php if ( $days_q->have_posts() ) : ?>
            <section class="oc-itin-section">
                <h2 class="oc-section-heading">Day by Day</h2>
                <div class="oc-day-timeline">
                    <?php while ( $days_q->have_posts() ) : $days_q->the_post();
                        $day_id         = get_the_ID();
                        $day_num        = get_post_meta( $day_id, '_oc_day_number', true );
                        $day_desc       = get_post_meta( $day_id, '_oc_description', true );
                        $day_activities = get_post_meta( $day_id, '_oc_activities', true );
                        $day_img_a      = get_post_meta( $day_id, '_oc_image_a', true );
                        $day_img_b      = get_post_meta( $day_id, '_oc_image_b', true );
                        $activities     = $day_activities ? json_decode( $day_activities, true ) : [];
                    ?>
                        <div class="oc-day-entry">
                            <div class="oc-day-number">
                                <span><?php echo esc_html( $day_num ?: $days_q->current_post + 1 ); ?></span>
                            </div>
                            <div class="oc-day-content">
                                <h3 class="oc-day-location"><?php the_title(); ?></h3>
                                <?php if ( $day_desc ) : ?>
                                    <div class="oc-day-desc"><?php echo wp_kses_post( wpautop( $day_desc ) ); ?></div>
                                <?php endif; ?>
                                <?php if ( ! empty( $activities ) ) : ?>
                                    <div class="oc-day-activities">
                                        <?php foreach ( $activities as $act ) : ?>
                                            <span class="oc-activity-pill"><?php echo esc_html( $act ); ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ( $day_img_a || $day_img_b ) : ?>
                                    <div class="oc-day-photos">
                                        <?php if ( $day_img_a ) : ?>
                                            <img src="<?php echo esc_url( $day_img_a ); ?>" alt="Day <?php echo esc_attr( $day_num ); ?> — <?php the_title(); ?>" loading="lazy">
                                        <?php endif; ?>
                                        <?php if ( $day_img_b ) : ?>
                                            <img src="<?php echo esc_url( $day_img_b ); ?>" alt="Day <?php echo esc_attr( $day_num ); ?> — <?php the_title(); ?>" loading="lazy">
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            </section>
            <?php endif; ?>

        </div><!-- /.oc-itin-main -->

        <!-- Sticky sidebar -->
        <aside class="oc-itin-sidebar">

            <!-- Route map (Leaflet.js + OpenStreetMap) -->
            <?php
            $has_latlng = ! empty( $route_stops ) && isset( $route_stops[0]['lat'] );
            if ( $has_latlng ) :
            ?>
            <div class="oc-route-map-card">
                <h3 class="oc-booking-card__title">Route Map</h3>
                <div id="oc-route-map" class="oc-route-map"></div>
            </div>
            <?php endif; ?>

            <!-- Sidebar Booking Form -->
            <div class="oc-sidebar-form-card" id="booking">
                <?php if ( $price ) : ?>
                    <div class="oc-booking-price-header">
                        <span class="oc-booking-price-header__amount">$<?php echo esc_html( number_format( (float) $price ) ); ?></span>
                        <?php if ( $duration ) : ?>
                            <span class="oc-booking-price-header__duration">for <?php echo esc_html( $duration ); ?></span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <form class="oc-sidebar-form" id="oc-booking-form" novalidate>
                    <?php wp_nonce_field( 'oc_itinerary_booking', 'oc_booking_nonce' ); ?>
                    <input type="hidden" name="action" value="oc_itinerary_booking">
                    <input type="hidden" name="itinerary_id" value="<?php echo esc_attr( $post_id ); ?>">
                    <input type="hidden" name="itinerary_title" value="<?php echo esc_attr( get_the_title( $post_id ) ); ?>">
                    <input type="hidden" name="itinerary_duration" value="<?php echo esc_attr( $duration ); ?>">
                    <input type="hidden" name="end_date" id="oc-end-date-hidden" value="">

                    <div class="oc-form-grid">
                        <div class="oc-sidebar-form__field">
                            <label for="oc-start-date">Start Date <span class="oc-required">*</span></label>
                            <input type="text" id="oc-start-date" name="start_date" required placeholder="Select date" autocomplete="off">
                        </div>
                        <div class="oc-sidebar-form__field">
                            <label for="oc-end-date-display">End Date</label>
                            <input type="text" id="oc-end-date-display" placeholder="Select or auto-calc" autocomplete="off" style="cursor:pointer;">
                        </div>
                        <div class="oc-sidebar-form__field">
                            <label for="oc-book-name">Full Name <span class="oc-required">*</span></label>
                            <input type="text" id="oc-book-name" name="guest_name" required placeholder="Your full name">
                        </div>
                        <div class="oc-sidebar-form__field">
                            <label for="oc-book-email">Email <span class="oc-required">*</span></label>
                            <input type="email" id="oc-book-email" name="guest_email" required placeholder="you@example.com">
                        </div>
                        <div class="oc-sidebar-form__field oc-field--wide">
                            <label for="oc-book-phone">Phone <span class="oc-required">*</span></label>
                            <input type="tel" id="oc-book-phone" name="guest_phone" required placeholder="+1 555 123 4567">
                        </div>
                        <div class="oc-sidebar-form__field oc-field--narrow">
                            <label for="oc-book-guests">Guests <span class="oc-required">*</span></label>
                            <input type="number" id="oc-book-guests" name="guest_count" required min="1" max="50" placeholder="1">
                        </div>
                    </div>

                    <button type="submit" class="oc-btn-gold oc-btn-full oc-btn-submit" id="oc-booking-submit">Request Booking</button>
                    <div class="oc-booking-msg" id="oc-booking-msg" style="display:none;"></div>
                </form>

                <a href="<?php echo esc_url( $wa_url ); ?>" class="oc-btn-whatsapp oc-btn-full" target="_blank" rel="noopener noreferrer">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    WhatsApp Us
                </a>
            </div>

            <!-- What's Included -->
            <?php if ( ! empty( $inclusions ) ) : ?>
            <div class="oc-sidebar-inclusions">
                <h3 class="oc-sidebar-inclusions__title">What&rsquo;s Included</h3>
                <ul class="oc-inclusions-list">
                    <?php foreach ( $inclusions as $item ) : ?>
                        <li>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M5 13l4 4L19 7" stroke="#d9b230" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <?php echo esc_html( $item ); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

        </aside>

    </div><!-- /.oc-itin-body -->

    <!-- ══ OTHER ITINERARIES ══════════════════════════════════════════════════ -->
    <?php
    $other_itins = new WP_Query( [
        'post_type'      => 'oc_itinerary',
        'posts_per_page' => 3,
        'post__not_in'   => [ $post_id ],
        'orderby'        => 'rand',
    ] );
    if ( $other_itins->have_posts() ) : ?>
    <section class="oc-related-section oc-container">
        <h2 class="oc-section-heading">Other Itineraries</h2>
        <div class="oc-related-grid">
            <?php while ( $other_itins->have_posts() ) : $other_itins->the_post();
                $r_id    = get_the_ID();
                $r_thumb = get_the_post_thumbnail_url( $r_id, 'medium_large' ) ?: $hero_img;
                $r_dur   = get_post_meta( $r_id, '_oc_duration', true );
                $r_price = get_post_meta( $r_id, '_oc_price', true );
            ?>
                <a href="<?php echo esc_url( get_permalink( $r_id ) ); ?>" class="oc-related-card">
                    <div class="oc-related-card__img" style="background-image:url('<?php echo esc_url( $r_thumb ); ?>');">
                        <?php if ( $r_dur ) : ?>
                            <span class="oc-related-card__badge"><?php echo esc_html( $r_dur ); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="oc-related-card__body">
                        <h3 class="oc-related-card__title"><?php the_title(); ?></h3>
                        <?php if ( $r_price ) : ?>
                            <span class="oc-related-card__price">From $<?php echo esc_html( number_format( (float) $r_price ) ); ?></span>
                        <?php endif; ?>
                    </div>
                </a>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- ══ CTA STRIP ═════════════════════════════════════════════════════════ -->
    <section class="oc-cta-strip">
        <div class="oc-container">
            <div class="oc-cta-strip__inner">
                <div class="oc-cta-strip__text">
                    <h2>Chart Your <span class="text-primary">Course</span></h2>
                    <p>Every great voyage begins with a single booking.</p>
                </div>
                <div class="oc-cta-strip__actions">
                    <a href="#booking" class="btn-primary">Book Now</a>
                    <a href="<?php echo esc_url( $wa_url ); ?>" class="btn-secondary" target="_blank" rel="noopener noreferrer">WhatsApp Us</a>
                </div>
            </div>
        </div>
    </section>

    <?php endif; // end elementor check ?>
<?php endif; // end have_posts ?>

</main>

<?php if ( $has_latlng ) : ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<?php endif; ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">

<style>
/* ── Ocean Charter: Single Itinerary ──────────────────────────────────────── */

.oc-single-itinerary {
    background: #0a0f1a;
    color: var(--text);
}

/* Hero */
.oc-itin-hero {
    position: relative;
    min-height: 65vh;
    background-size: cover;
    background-position: center;
    display: flex;
    align-items: flex-end;
    padding-bottom: 60px;
}
.oc-itin-hero__overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(10,15,26,0.95) 0%, rgba(10,15,26,0.4) 55%, rgba(10,15,26,0.1) 100%);
}
.oc-itin-hero__content {
    position: relative;
    z-index: 2;
    max-width: 900px;
}
.oc-itin-eyebrow {
    display: block;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: var(--primary);
    margin-bottom: 14px;
}
.oc-itin-hero__title {
    font-family: var(--font-heading);
    font-size: clamp(36px, 5vw, 68px);
    font-weight: 400;
    font-style: italic;
    color: var(--text-light, #f8fafc);
    line-height: 1.1;
    margin: 0 0 24px;
}
.oc-itin-hero__pills {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}
.oc-spec-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(17,26,40,0.75);
    border: 1px solid rgba(217,178,48,0.3);
    backdrop-filter: blur(8px);
    color: var(--text);
    font-size: 13px;
    padding: 7px 14px;
    border-radius: 999px;
}
.oc-spec-pill svg { color: var(--primary); flex-shrink: 0; }

/* Body layout */
.oc-itin-body {
    display: grid;
    grid-template-columns: 1fr 420px;
    gap: 60px;
    padding-top: 64px;
    padding-bottom: 100px;
    align-items: start;
}
.oc-itin-section {
    margin-bottom: 56px;
}
.oc-section-heading {
    font-family: var(--font-heading);
    font-size: 28px;
    font-weight: 400;
    color: var(--text-light, #f8fafc);
    margin: 0 0 28px;
    padding-bottom: 14px;
    border-bottom: 1px solid var(--border);
}
.oc-itin-intro {
    font-size: 17px;
    line-height: 1.8;
    color: var(--text-muted);
}

/* Day timeline */
.oc-day-timeline {
    position: relative;
}
.oc-day-entry {
    display: grid;
    grid-template-columns: 48px 1fr;
    gap: 20px;
    margin-bottom: 48px;
    position: relative;
}
.oc-day-entry:not(:last-child)::after {
    content: '';
    position: absolute;
    left: 23px;
    top: 48px;
    bottom: -24px;
    width: 2px;
    background: linear-gradient(to bottom, var(--primary), rgba(217,178,48,0.1));
}
.oc-day-number {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    border: 2px solid var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    background: var(--surface, #111a28);
    z-index: 1;
}
.oc-day-number span {
    font-size: 14px;
    font-weight: 700;
    color: var(--primary);
}
.oc-day-content {
    padding-top: 10px;
}
.oc-day-location {
    font-family: var(--font-heading);
    font-size: 20px;
    font-weight: 400;
    color: var(--text-light, #f8fafc);
    margin: 0 0 12px;
}
.oc-day-desc {
    font-size: 15px;
    line-height: 1.7;
    color: var(--text-muted);
    margin-bottom: 14px;
}
.oc-day-desc p { margin: 0 0 10px; }
.oc-day-activities {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 16px;
}
.oc-activity-pill {
    background: rgba(217,178,48,0.1);
    border: 1px solid rgba(217,178,48,0.3);
    color: var(--primary);
    font-size: 12px;
    font-weight: 500;
    padding: 4px 12px;
    border-radius: 999px;
}
.oc-day-photos {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-top: 12px;
}
.oc-day-photos img {
    width: 100%;
    aspect-ratio: 4/3;
    object-fit: cover;
    border-radius: 0.5rem;
    display: block;
}

/* Sidebar */
.oc-itin-sidebar {
    position: sticky;
    top: 100px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}
.oc-route-map-card {
    background: var(--surface, #111a28);
    border: 1px solid var(--border);
    border-radius: 1rem;
    padding: 1.25rem;
}
.oc-route-map {
    width: 100%;
    height: 300px;
    border-radius: 0.5rem;
    z-index: 1;
}
.oc-map-dot {
    display: block;
    width: 10px; height: 10px;
    background: #1e3a5f;
    border: 2px solid #d9b230;
    border-radius: 50%;
}
.oc-map-dot--start {
    width: 14px; height: 14px;
    background: #d9b230;
}
.oc-map-label {
    background: rgba(13,24,37,0.85) !important;
    border: 1px solid rgba(217,178,48,0.4) !important;
    color: rgba(240,236,227,0.9) !important;
    font-size: 11px !important;
    font-family: var(--font-body, sans-serif) !important;
    padding: 2px 8px !important;
    border-radius: 4px !important;
    box-shadow: 0 2px 6px rgba(0,0,0,0.3) !important;
}
.oc-map-label::before {
    border-right-color: rgba(217,178,48,0.4) !important;
}
.leaflet-control-zoom a {
    background: rgba(13,24,37,0.9) !important;
    color: #d9b230 !important;
    border-color: rgba(217,178,48,0.3) !important;
}
.oc-booking-card {
    background: var(--surface, #111a28);
    border: 1px solid var(--border);
    border-radius: 1rem;
    padding: 1.5rem;
}
.oc-booking-card__title {
    font-family: var(--font-heading);
    font-size: 18px;
    font-weight: 400;
    color: var(--text-light, #f8fafc);
    margin: 0 0 18px;
    padding-bottom: 14px;
    border-bottom: 1px solid var(--border);
    text-align: center;
}
.oc-booking-card__price-primary {
    display: flex;
    align-items: baseline;
    gap: 6px;
    margin-bottom: 4px;
}
.oc-price-label { font-size: 13px; color: var(--text-muted); }
.oc-price-amount {
    font-size: 34px;
    font-weight: 700;
    color: var(--primary);
    line-height: 1;
}
.oc-booking-card__period {
    font-size: 14px;
    color: var(--text-muted);
    margin-bottom: 6px;
}
.oc-booking-card__note {
    font-size: 12px;
    color: var(--text-muted);
    font-style: italic;
    margin: 0 0 16px;
}

/* Booking price header (merged into form card) */
.oc-booking-price-header {
    text-align: center;
    padding-bottom: 16px;
    margin-bottom: 16px;
    border-bottom: 1px solid var(--border);
}
.oc-booking-price-header__amount {
    font-size: 32px;
    font-weight: 700;
    color: var(--primary);
    line-height: 1;
}
.oc-booking-price-header__duration {
    display: block;
    font-size: 14px;
    color: var(--text-muted);
    margin-top: 4px;
}

/* Sidebar form card */
.oc-sidebar-form-card {
    background: var(--surface, #111a28);
    border: 1px solid var(--border);
    border-radius: 1rem;
    padding: 1.5rem;
}
.oc-sidebar-form {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 14px;
}
.oc-form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}
.oc-field--wide { grid-column: 1; }
.oc-field--narrow { grid-column: 2; }
@media (max-width: 480px) {
    .oc-form-grid { grid-template-columns: 1fr; }
    .oc-field--wide,
    .oc-field--narrow { grid-column: auto; }
}
.oc-sidebar-form__field label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: var(--text-muted, #8a9bb5);
    margin-bottom: 5px;
    letter-spacing: 0.04em;
    text-transform: uppercase;
}
.oc-required { color: #d9b230; }
.oc-sidebar-form__field input,
.oc-sidebar-form__field textarea {
    width: 100%;
    background: rgba(17,26,40,0.8);
    border: 1px solid var(--border, rgba(255,255,255,0.08));
    border-radius: 0.5rem;
    color: #f0ece3;
    font-size: 14px;
    padding: 10px 12px;
    font-family: inherit;
    transition: border-color 0.2s;
    box-sizing: border-box;
}
.oc-sidebar-form__field input:focus,
.oc-sidebar-form__field textarea:focus {
    outline: none;
    border-color: #d9b230;
    box-shadow: 0 0 0 2px rgba(217,178,48,0.15);
}
.oc-sidebar-form__field input[readonly] {
    opacity: 0.6;
    cursor: not-allowed;
}
.oc-sidebar-form__field input::placeholder,
.oc-sidebar-form__field textarea::placeholder {
    color: rgba(240,236,227,0.3);
}
/* flatpickr dark theme overrides */
.flatpickr-calendar {
    background: #111a28 !important;
    border: 1px solid rgba(217,178,48,0.3) !important;
    box-shadow: 0 8px 32px rgba(0,0,0,0.5) !important;
    border-radius: 12px !important;
}
.flatpickr-months .flatpickr-month,
.flatpickr-current-month .flatpickr-monthDropdown-months {
    background: #111a28 !important;
    color: #f0ece3 !important;
}
.flatpickr-current-month input.cur-year { color: #f0ece3 !important; }
.flatpickr-day {
    color: #f0ece3 !important;
    border-radius: 6px !important;
}
.flatpickr-day:hover { background: rgba(217,178,48,0.2) !important; border-color: transparent !important; }
.flatpickr-day.selected { background: #d9b230 !important; color: #0a0f1a !important; border-color: #d9b230 !important; }
.flatpickr-day.today { border-color: #d9b230 !important; }
.flatpickr-day.flatpickr-disabled { color: rgba(240,236,227,0.15) !important; }
span.flatpickr-weekday { color: #d9b230 !important; }
.flatpickr-months .flatpickr-prev-month,
.flatpickr-months .flatpickr-next-month { fill: #d9b230 !important; color: #d9b230 !important; }
.flatpickr-months .flatpickr-prev-month:hover svg,
.flatpickr-months .flatpickr-next-month:hover svg { fill: #f0ece3 !important; }

/* Buttons */
.oc-btn-gold {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: var(--primary);
    color: #0a0f1a;
    font-weight: 700;
    font-size: 14px;
    padding: 12px 20px;
    border-radius: 0.5rem;
    text-decoration: none;
    transition: background 0.2s, transform 0.15s;
    margin-bottom: 10px;
}
.oc-btn-gold:hover { background: var(--primary-hover, #f1c944); transform: translateY(-1px); color: #0a0f1a; }
.oc-btn-submit {
    border: none;
    cursor: pointer;
    font-family: inherit;
}
.oc-btn-submit:disabled {
    opacity: 0.6;
    cursor: wait;
}
.oc-btn-whatsapp {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: transparent;
    color: #25d366;
    border: 1.5px solid #25d366;
    font-weight: 600;
    font-size: 14px;
    padding: 11px 20px;
    border-radius: 0.5rem;
    text-decoration: none;
    transition: background 0.2s, color 0.2s;
}
.oc-btn-whatsapp:hover { background: #25d366; color: #fff; }
.oc-btn-full { width: 100%; }

/* Booking msg */
.oc-booking-msg {
    padding: 12px 14px;
    border-radius: 0.5rem;
    font-size: 13px;
    line-height: 1.4;
    margin-top: 10px;
}
.oc-booking-msg--success {
    background: rgba(72,187,120,0.12);
    border: 1px solid rgba(72,187,120,0.3);
    color: #68d391;
}
.oc-booking-msg--error {
    background: rgba(245,101,101,0.12);
    border: 1px solid rgba(245,101,101,0.3);
    color: #fc8181;
}

/* Sidebar inclusions */
.oc-sidebar-inclusions {
    background: var(--surface, #111a28);
    border: 1px solid var(--border);
    border-radius: 1rem;
    padding: 1.25rem;
}
.oc-sidebar-inclusions__title {
    font-family: var(--font-heading);
    font-size: 17px;
    font-weight: 400;
    color: var(--text-light, #f8fafc);
    margin: 0 0 16px;
    padding-bottom: 12px;
    border-bottom: 1px solid var(--border);
}
.oc-inclusions-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.oc-inclusions-list li {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    font-size: 14px;
    color: var(--text-muted);
    line-height: 1.4;
}
.oc-inclusions-list li svg { flex-shrink: 0; margin-top: 1px; }

/* Related */
.oc-related-section { padding-bottom: 80px; }
.oc-related-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
    margin-top: 8px;
}
.oc-related-card {
    text-decoration: none;
    color: inherit;
    display: block;
    background: var(--surface, #111a28);
    border: 1px solid var(--border);
    border-radius: 0.75rem;
    overflow: hidden;
    transition: border-color 0.2s, transform 0.2s;
}
.oc-related-card:hover { border-color: var(--primary); transform: translateY(-3px); }
.oc-related-card__img {
    height: 200px;
    background-size: cover;
    background-position: center;
    position: relative;
}
.oc-related-card__badge {
    position: absolute;
    top: 12px;
    left: 12px;
    background: rgba(17,26,40,0.85);
    color: var(--primary);
    border: 1px solid rgba(217,178,48,0.4);
    font-size: 10px;
    font-weight: 600;
    padding: 3px 10px;
    border-radius: 999px;
}
.oc-related-card__body { padding: 16px; }
.oc-related-card__title {
    font-family: var(--font-heading);
    font-size: 16px;
    font-weight: 400;
    color: var(--text-light, #f8fafc);
    margin: 0 0 6px;
}
.oc-related-card__price { font-size: 14px; color: var(--primary); }

/* CTA strip */
.oc-cta-strip {
    background: var(--surface, #111a28);
    border-top: 1px solid var(--border);
    padding: 72px 0;
}
.oc-cta-strip__inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 32px;
    flex-wrap: wrap;
}
.oc-cta-strip__text h2 {
    font-family: var(--font-heading);
    font-size: clamp(28px, 4vw, 42px);
    font-weight: 400;
    color: var(--text-light, #f8fafc);
    margin: 0 0 8px;
}
.oc-cta-strip__text p { font-size: 17px; color: var(--text-muted); margin: 0; }
.oc-cta-strip__actions { display: flex; gap: 16px; flex-wrap: wrap; }

@media (max-width: 1024px) {
    .oc-itin-body { grid-template-columns: 1fr; gap: 40px; }
    .oc-itin-sidebar { position: static; order: -1; }
}
@media (max-width: 768px) {
    .oc-related-grid { grid-template-columns: 1fr; }
    .oc-day-photos { grid-template-columns: 1fr; }
    .oc-cta-strip__inner { flex-direction: column; text-align: center; }
    .oc-cta-strip__actions { justify-content: center; }
}
</style>

<?php if ( $has_latlng ) : ?>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
(function() {
    'use strict';
    var stops = <?php echo wp_json_encode( array_map( function( $s ) {
        return [
            'name' => $s['name'] ?? '',
            'lat'  => (float) ( $s['lat'] ?? 0 ),
            'lng'  => (float) ( $s['lng'] ?? 0 ),
        ];
    }, $route_stops ) ); ?>;

    if ( ! stops.length || typeof L === 'undefined' ) return;

    var map = L.map('oc-route-map', {
        scrollWheelZoom: false,
        attributionControl: false,
        zoomControl: true,
    });

    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        maxZoom: 19,
    }).addTo(map);

    L.control.attribution({ position: 'bottomright', prefix: false })
        .addAttribution('&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a> &copy; <a href="https://carto.com/">CARTO</a>')
        .addTo(map);

    var goldIcon = L.divIcon({
        className: 'oc-map-marker',
        html: '<span class="oc-map-dot"></span>',
        iconSize: [14, 14],
        iconAnchor: [7, 7],
    });
    var startEndIcon = L.divIcon({
        className: 'oc-map-marker oc-map-marker--start',
        html: '<span class="oc-map-dot oc-map-dot--start"></span>',
        iconSize: [18, 18],
        iconAnchor: [9, 9],
    });

    var latlngs = [];
    stops.forEach(function(stop, i) {
        var isTerminal = (i === 0 || i === stops.length - 1);
        var icon = isTerminal ? startEndIcon : goldIcon;
        var marker = L.marker([stop.lat, stop.lng], { icon: icon }).addTo(map);
        if (stop.name) {
            marker.bindTooltip(stop.name, {
                permanent: true,
                direction: 'right',
                offset: [10, 0],
                className: 'oc-map-label',
            });
        }
        latlngs.push([stop.lat, stop.lng]);
    });

    if (latlngs.length > 1) {
        L.polyline(latlngs, {
            color: '#d9b230',
            weight: 2.5,
            opacity: 0.7,
            dashArray: '8 5',
        }).addTo(map);
    }

    map.fitBounds(L.latLngBounds(latlngs).pad(0.15));
})();
</script>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
(function() {
    'use strict';

    var durationRaw = <?php echo wp_json_encode( $duration ?: '0' ); ?>;
    var ajaxUrl     = <?php echo wp_json_encode( admin_url( 'admin-ajax.php' ) ); ?>;

    var durationDays = parseInt( durationRaw, 10 ) || 0;

    var startInput   = document.getElementById('oc-start-date');
    var endDisplay   = document.getElementById('oc-end-date-display');
    var endHidden    = document.getElementById('oc-end-date-hidden');
    var form         = document.getElementById('oc-booking-form');
    var submitBtn    = document.getElementById('oc-booking-submit');
    var msgBox       = document.getElementById('oc-booking-msg');

    function addDays(dateStr, days) {
        var d = new Date(dateStr + 'T00:00:00');
        d.setDate(d.getDate() + days);
        return d.getFullYear() + '-' +
            String(d.getMonth() + 1).padStart(2, '0') + '-' +
            String(d.getDate()).padStart(2, '0');
    }

    function updateEndDate(selectedDates) {
        if (!selectedDates.length || durationDays < 1) {
            endDisplay.value = '';
            endHidden.value  = '';
            return;
        }
        var startStr = selectedDates[0].getFullYear() + '-' +
            String(selectedDates[0].getMonth() + 1).padStart(2, '0') + '-' +
            String(selectedDates[0].getDate()).padStart(2, '0');
        var endDate = addDays(startStr, durationDays - 1);
        endDisplay.value = endDate;
        endHidden.value  = endDate;
        if (endPicker) endPicker.setDate(endDate, false);
    }

    var fpConfig = {
        dateFormat: 'Y-m-d',
        minDate: 'today',
        disableMobile: true,
        theme: 'dark',
        onChange: updateEndDate,
    };

    var startPicker = startInput ? flatpickr(startInput, fpConfig) : null;

    // End date: user can click to set manually, OR it's auto-filled by start date + duration
    var endPicker = endDisplay ? flatpickr(endDisplay, {
        dateFormat: 'Y-m-d',
        minDate: 'today',
        disableMobile: true,
        allowInput: false,
        onChange: function(selectedDates, dateStr) {
            if (selectedDates.length) {
                endHidden.value = dateStr;
                // Also ensure start date is not after end date
                if (startPicker && startInput.value && dateStr < startInput.value) {
                    startPicker.setDate(dateStr, false);
                    endHidden.value = dateStr;
                }
            }
        },
    }) : null;

    // ── Validation helpers ──
    function ocValEmail(v) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v.trim()); }
    function ocValPhone(v) { return v.trim() === '' || /^[+]?[\d\s\-(). ]{7,20}$/.test(v.trim()); }

    // Real-time blur validation
    var emailInput = document.getElementById('oc-book-email');
    var phoneInput = document.getElementById('oc-book-phone');
    if (emailInput) emailInput.addEventListener('blur', function() {
        this.style.borderColor = ocValEmail(this.value) ? '' : '#fc8181';
    });
    if (phoneInput) phoneInput.addEventListener('blur', function() {
        this.style.borderColor = ocValPhone(this.value) ? '' : '#fc8181';
    });

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            msgBox.style.display = 'none';
            msgBox.className = 'oc-booking-msg';

            // Validate required fields
            var required = form.querySelectorAll('[required]');
            for (var i = 0; i < required.length; i++) {
                if (!required[i].value.trim()) {
                    showMsg('error', 'Please fill in all required fields.');
                    required[i].focus();
                    return;
                }
            }
            // Validate email
            if (emailInput && !ocValEmail(emailInput.value)) {
                showMsg('error', 'Please enter a valid email address.');
                emailInput.focus();
                return;
            }
            // Validate phone
            if (phoneInput && !ocValPhone(phoneInput.value)) {
                showMsg('error', 'Please enter a valid phone number (7-20 digits).');
                phoneInput.focus();
                return;
            }

            submitBtn.disabled = true;
            submitBtn.textContent = 'Sending...';

            var formData = new FormData(form);

            fetch(ajaxUrl, {
                method: 'POST',
                credentials: 'same-origin',
                body: formData
            })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                if (data.success) {
                    showMsg('success', data.data.message || 'Booking request sent! We will be in touch shortly.');
                    form.reset();
                    endDisplay.value = '';
                } else {
                    showMsg('error', data.data.message || 'Something went wrong. Please try again.');
                }
            })
            .catch(function() {
                showMsg('error', 'Network error. Please check your connection and try again.');
            })
            .finally(function() {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Request Booking';
            });
        });
    }

    function showMsg(type, text) {
        msgBox.className = 'oc-booking-msg oc-booking-msg--' + type;
        msgBox.textContent = text;
        msgBox.style.display = 'block';
    }
})();
</script>

<?php get_footer(); ?>
