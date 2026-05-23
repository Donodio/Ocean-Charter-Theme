<?php
/**
 * Single Vessel Template
 *
 * @package OceanCharter
 */

get_header();
?>

<main id="primary" class="site-main oc-single-vessel">

<?php if ( have_posts() ) : the_post();

    $post_id = get_the_ID();

    /* ── Elementor builder pass-through ── */
    $elementor_mode = get_post_meta( $post_id, '_elementor_edit_mode', true );
    if ( 'builder' === $elementor_mode ) :
        the_content();
    else :

    /* ── Meta ── */
    $hero_img      = get_the_post_thumbnail_url( $post_id, 'full' ) ?: get_post_meta( $post_id, '_oc_gallery_1', true );
    $fallback_img  = 'https://images.pexels.com/photos/1531660/pexels-photo-1531660.jpeg?auto=compress&cs=tinysrgb&w=1920';
    $hero_img      = $hero_img ?: $fallback_img;

    $length        = get_post_meta( $post_id, '_oc_length', true );
    $guests        = get_post_meta( $post_id, '_oc_guests', true );
    $cabins        = get_post_meta( $post_id, '_oc_cabins', true );
    $speed         = get_post_meta( $post_id, '_oc_speed', true );
    $year_built    = get_post_meta( $post_id, '_oc_year_built', true );
    $builder       = get_post_meta( $post_id, '_oc_builder', true );
    $flag          = get_post_meta( $post_id, '_oc_flag', true );
    $home_port     = get_post_meta( $post_id, '_oc_home_port', true );
    $price_day     = get_post_meta( $post_id, '_oc_price_per_day', true );
    $price_week    = get_post_meta( $post_id, '_oc_price_per_week', true );
    $cta_url       = get_post_meta( $post_id, '_oc_cta_url', true ) ?: home_url( '/contact/' );
    $specs_raw     = get_post_meta( $post_id, '_oc_specs', true );
    $amenities_raw = get_post_meta( $post_id, '_oc_amenities', true );
    $gallery       = [
        get_post_meta( $post_id, '_oc_gallery_1', true ),
        get_post_meta( $post_id, '_oc_gallery_2', true ),
        get_post_meta( $post_id, '_oc_gallery_3', true ),
        get_post_meta( $post_id, '_oc_gallery_4', true ),
    ];
    $gallery       = array_filter( $gallery );

    $specs     = $specs_raw     ? json_decode( $specs_raw, true )     : [];
    $amenities = $amenities_raw ? json_decode( $amenities_raw, true ) : [];

    $vessel_types = wp_get_post_terms( $post_id, 'oc_vessel_type', [ 'fields' => 'names' ] );
    $type_label   = ( ! is_wp_error( $vessel_types ) && ! empty( $vessel_types ) ) ? $vessel_types[0] : '';

    $phone       = get_theme_mod( 'oc_contact_phone', '+1 (555) 123-4567' );

    // Build WhatsApp message with vessel details
    $wa_parts = [ 'Hello! I\'m interested in chartering *' . get_the_title() . '*.' ];
    if ( $price_week ) $wa_parts[] = 'Listed at $' . number_format( (float) $price_week ) . '/week.';
    elseif ( $price_day ) $wa_parts[] = 'Listed at $' . number_format( (float) $price_day ) . '/day.';
    if ( $guests ) $wa_parts[] = 'Guests: ' . $guests . '.';
    $wa_parts[] = 'Could you share availability and booking details?';
    $wa_msg  = implode( ' ', $wa_parts );
    $wa_num  = preg_replace( '/\D/', '', get_theme_mod( 'oc_whatsapp_number', '15551234567' ) );
    $wa_url  = 'https://wa.me/' . $wa_num . '?text=' . rawurlencode( $wa_msg );
    ?>

    <!-- ══ HERO ══════════════════════════════════════════════════════════════ -->
    <section class="oc-vessel-hero" style="background-image:url('<?php echo esc_url( $hero_img ); ?>');">
        <div class="oc-vessel-hero__overlay"></div>
        <div class="oc-vessel-hero__content oc-container">
            <?php if ( $type_label ) : ?>
                <span class="oc-vessel-badge"><?php echo esc_html( $type_label ); ?></span>
            <?php endif; ?>
            <h1 class="oc-vessel-hero__title"><?php the_title(); ?></h1>
            <div class="oc-vessel-specs-strip">
                <?php if ( $length ) : ?>
                    <span class="oc-spec-pill">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M3 17l6-6 4 4 8-8"/></svg>
                        <?php echo esc_html( $length ); ?> Length
                    </span>
                <?php endif; ?>
                <?php if ( $guests ) : ?>
                    <span class="oc-spec-pill">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                        <?php echo esc_html( $guests ); ?> Guests
                    </span>
                <?php endif; ?>
                <?php if ( $cabins ) : ?>
                    <span class="oc-spec-pill">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/></svg>
                        <?php echo esc_html( $cabins ); ?> Cabins
                    </span>
                <?php endif; ?>
                <?php if ( $speed ) : ?>
                    <span class="oc-spec-pill">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                        <?php echo esc_html( $speed ); ?> Knots
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- ══ BODY ══════════════════════════════════════════════════════════════ -->
    <div class="oc-vessel-body oc-container">

        <!-- Left column -->
        <div class="oc-vessel-main">

            <!-- Description -->
            <?php $excerpt = get_the_excerpt(); if ( $excerpt ) : ?>
            <section class="oc-vessel-section">
                <h2 class="oc-section-heading">About This Vessel</h2>
                <p class="oc-vessel-desc"><?php echo wp_kses_post( $excerpt ); ?></p>
            </section>
            <?php endif; ?>

            <!-- Specs table -->
            <?php if ( $year_built || $builder || $flag || $home_port ) : ?>
            <section class="oc-vessel-section">
                <h2 class="oc-section-heading">Vessel Details</h2>
                <div class="oc-vessel-details-grid">
                    <?php if ( $year_built ) : ?>
                        <div class="oc-detail-item"><span class="oc-detail-label">Year Built</span><span class="oc-detail-value"><?php echo esc_html( $year_built ); ?></span></div>
                    <?php endif; ?>
                    <?php if ( $builder ) : ?>
                        <div class="oc-detail-item"><span class="oc-detail-label">Builder</span><span class="oc-detail-value"><?php echo esc_html( $builder ); ?></span></div>
                    <?php endif; ?>
                    <?php if ( $flag ) : ?>
                        <div class="oc-detail-item"><span class="oc-detail-label">Flag</span><span class="oc-detail-value"><?php echo esc_html( $flag ); ?></span></div>
                    <?php endif; ?>
                    <?php if ( $home_port ) : ?>
                        <div class="oc-detail-item"><span class="oc-detail-label">Home Port</span><span class="oc-detail-value"><?php echo esc_html( $home_port ); ?></span></div>
                    <?php endif; ?>
                </div>
            </section>
            <?php endif; ?>

            <!-- Key specs pills -->
            <?php if ( ! empty( $specs ) ) : ?>
            <section class="oc-vessel-section">
                <h2 class="oc-section-heading">Key Specifications</h2>
                <div class="oc-specs-pills">
                    <?php foreach ( $specs as $spec_key => $spec_val ) : ?>
                        <div class="oc-key-spec-pill">
                            <span class="oc-key-spec-label"><?php echo esc_html( $spec_key ); ?></span>
                            <span class="oc-key-spec-value"><?php echo esc_html( $spec_val ); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

            <!-- Amenities -->
            <?php if ( ! empty( $amenities ) ) : ?>
            <section class="oc-vessel-section">
                <h2 class="oc-section-heading">Amenities</h2>
                <div class="oc-amenities-grid">
                    <?php foreach ( $amenities as $amenity ) : ?>
                        <div class="oc-amenity-card">
                            <span class="oc-amenity-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            </span>
                            <span class="oc-amenity-label"><?php echo esc_html( $amenity ); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

            <!-- Gallery -->
            <?php if ( ! empty( $gallery ) ) : ?>
            <section class="oc-vessel-section">
                <h2 class="oc-section-heading">Gallery</h2>
                <div class="oc-vessel-gallery">
                    <?php foreach ( $gallery as $img_url ) : ?>
                        <div class="oc-gallery-item">
                            <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy">
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

        </div><!-- /.oc-vessel-main -->

        <!-- Right sticky sidebar -->
        <aside class="oc-vessel-sidebar">
            <div class="oc-booking-card">
                <h3 class="oc-booking-card__title">Book This Vessel</h3>

                <?php if ( $price_day ) : ?>
                    <div class="oc-booking-card__price-primary">
                        <span class="oc-price-label">From</span>
                        <span class="oc-price-amount">$<?php echo esc_html( number_format( (float) $price_day ) ); ?></span>
                        <span class="oc-price-per">/ day</span>
                    </div>
                <?php endif; ?>

                <?php if ( $price_week ) : ?>
                    <div class="oc-booking-card__price-secondary">
                        $<?php echo esc_html( number_format( (float) $price_week ) ); ?> <span>/ week</span>
                    </div>
                <?php endif; ?>

                <a href="<?php echo esc_url( $cta_url ); ?>" class="oc-btn-gold oc-btn-full">Request Charter</a>

                <a href="<?php echo esc_url( $wa_url ); ?>" class="oc-btn-whatsapp oc-btn-full" target="_blank" rel="noopener noreferrer">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    WhatsApp Us
                </a>

                <?php if ( $phone ) : ?>
                    <p class="oc-booking-card__call">
                        or call us: <a href="tel:<?php echo esc_attr( preg_replace( '/[^+0-9]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
                    </p>
                <?php endif; ?>
            </div>
        </aside>

    </div><!-- /.oc-vessel-body -->

    <!-- ══ RELATED VESSELS ═══════════════════════════════════════════════════ -->
    <?php
    $related_args = [
        'post_type'      => 'oc_vessel',
        'posts_per_page' => 3,
        'post__not_in'   => [ $post_id ],
        'orderby'        => 'rand',
    ];
    if ( ! empty( $vessel_types ) && ! is_wp_error( $vessel_types ) ) {
        $type_terms = wp_get_post_terms( $post_id, 'oc_vessel_type', [ 'fields' => 'ids' ] );
        if ( ! is_wp_error( $type_terms ) && ! empty( $type_terms ) ) {
            $related_args['tax_query'] = [ [
                'taxonomy' => 'oc_vessel_type',
                'field'    => 'term_id',
                'terms'    => $type_terms,
            ] ];
        }
    }
    $related = new WP_Query( $related_args );
    if ( $related->have_posts() ) : ?>
    <section class="oc-related-section oc-container">
        <h2 class="oc-section-heading">Similar Vessels</h2>
        <div class="oc-related-grid">
            <?php while ( $related->have_posts() ) : $related->the_post();
                $rel_id    = get_the_ID();
                $rel_thumb = get_the_post_thumbnail_url( $rel_id, 'medium_large' ) ?: $fallback_img;
                $rel_types = wp_get_post_terms( $rel_id, 'oc_vessel_type', [ 'fields' => 'names' ] );
                $rel_type  = ( ! is_wp_error( $rel_types ) && ! empty( $rel_types ) ) ? $rel_types[0] : '';
                $rel_price = get_post_meta( $rel_id, '_oc_price_per_day', true );
            ?>
                <a href="<?php echo esc_url( get_permalink( $rel_id ) ); ?>" class="oc-related-card">
                    <div class="oc-related-card__img" style="background-image:url('<?php echo esc_url( $rel_thumb ); ?>');">
                        <?php if ( $rel_type ) : ?>
                            <span class="oc-related-card__badge"><?php echo esc_html( $rel_type ); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="oc-related-card__body">
                        <h3 class="oc-related-card__title"><?php the_title(); ?></h3>
                        <?php if ( $rel_price ) : ?>
                            <span class="oc-related-card__price">From $<?php echo esc_html( number_format( (float) $rel_price ) ); ?>/day</span>
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
                    <h2>Ready to Set <span class="text-primary">Sail?</span></h2>
                    <p>Your bespoke charter experience awaits. Let&rsquo;s plan your perfect voyage.</p>
                </div>
                <div class="oc-cta-strip__actions">
                    <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn-primary">Book Now</a>
                    <a href="<?php echo esc_url( $wa_url ); ?>" class="btn-secondary" target="_blank" rel="noopener noreferrer">WhatsApp Us</a>
                </div>
            </div>
        </div>
    </section>

    <?php endif; // end elementor check ?>
<?php endif; // end have_posts ?>

</main>

<style>
/* ── Ocean Charter: Single Vessel ─────────────────────────────────────────── */

.oc-single-vessel {
    background: #0a0f1a;
    color: var(--text);
}

/* Hero */
.oc-vessel-hero {
    position: relative;
    min-height: 60vh;
    background-size: cover;
    background-position: center;
    display: flex;
    align-items: flex-end;
    padding-bottom: 56px;
}
.oc-vessel-hero__overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(10,15,26,0.95) 0%, rgba(10,15,26,0.4) 55%, rgba(10,15,26,0.1) 100%);
}
.oc-vessel-hero__content {
    position: relative;
    z-index: 2;
    max-width: 900px;
}
.oc-vessel-badge {
    display: inline-block;
    background: var(--primary);
    color: #0a0f1a;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    padding: 5px 14px;
    border-radius: 999px;
    margin-bottom: 20px;
}
.oc-vessel-hero__title {
    font-family: var(--font-heading);
    font-size: clamp(36px, 5vw, 68px);
    font-weight: 400;
    font-style: italic;
    color: var(--text-light, #f8fafc);
    line-height: 1.1;
    margin: 0 0 24px;
}
.oc-vessel-specs-strip {
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
.oc-vessel-body {
    display: grid;
    grid-template-columns: 1fr 360px;
    gap: 60px;
    padding-top: 64px;
    padding-bottom: 100px;
    align-items: start;
}
.oc-vessel-section {
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
.oc-vessel-desc {
    font-size: 17px;
    line-height: 1.8;
    color: var(--text-muted);
}

/* Details grid */
.oc-vessel-details-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1px;
    background: var(--border);
    border: 1px solid var(--border);
    border-radius: 0.75rem;
    overflow: hidden;
}
.oc-detail-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
    background: var(--surface, #111a28);
    padding: 18px 20px;
}
.oc-detail-label {
    font-size: 11px;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--text-muted);
}
.oc-detail-value {
    font-size: 16px;
    font-weight: 600;
    color: var(--text-light, #f8fafc);
}

/* Key spec pills */
.oc-specs-pills {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}
.oc-key-spec-pill {
    display: flex;
    flex-direction: column;
    align-items: center;
    background: var(--surface, #111a28);
    border: 1px solid var(--border);
    border-radius: 0.5rem;
    padding: 12px 18px;
    min-width: 100px;
    text-align: center;
}
.oc-key-spec-label {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--text-muted);
}
.oc-key-spec-value {
    font-size: 15px;
    font-weight: 600;
    color: var(--primary);
    margin-top: 2px;
}

/* Amenities */
.oc-amenities-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 12px;
}
.oc-amenity-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    background: var(--surface, #111a28);
    border: 1px solid var(--border);
    border-radius: 0.75rem;
    padding: 16px 12px;
    text-align: center;
    transition: border-color 0.2s;
}
.oc-amenity-card:hover { border-color: var(--primary); }
.oc-amenity-icon { color: var(--primary); }
.oc-amenity-label {
    font-size: 13px;
    color: var(--text);
    line-height: 1.3;
}

/* Gallery */
.oc-vessel-gallery {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
}
.oc-gallery-item {
    aspect-ratio: 4/3;
    overflow: hidden;
    border-radius: 0.5rem;
}
.oc-gallery-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.4s ease;
}
.oc-gallery-item:hover img { transform: scale(1.04); }

/* Booking card sidebar */
.oc-vessel-sidebar {
    position: sticky;
    top: 100px;
}
.oc-booking-card {
    background: var(--surface, #111a28);
    border: 1px solid var(--border);
    border-radius: 1rem;
    padding: 1.5rem;
}
.oc-booking-card__title {
    font-family: var(--font-heading);
    font-size: 20px;
    font-weight: 400;
    color: var(--text-light, #f8fafc);
    margin: 0 0 20px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--border);
    text-align: center;
}
.oc-booking-card__price-primary {
    display: flex;
    align-items: baseline;
    gap: 6px;
    margin-bottom: 8px;
}
.oc-price-label {
    font-size: 13px;
    color: var(--text-muted);
}
.oc-price-amount {
    font-size: 36px;
    font-weight: 700;
    color: var(--primary);
    line-height: 1;
}
.oc-price-per {
    font-size: 14px;
    color: var(--text-muted);
}
.oc-booking-card__price-secondary {
    font-size: 16px;
    color: var(--text-muted);
    margin-bottom: 20px;
}
.oc-booking-card__price-secondary span { font-size: 13px; }
.oc-btn-gold {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: var(--primary);
    color: #0a0f1a;
    font-weight: 700;
    font-size: 15px;
    padding: 13px 24px;
    border-radius: 0.5rem;
    text-decoration: none;
    transition: background 0.2s, transform 0.15s;
    margin-bottom: 10px;
}
.oc-btn-gold:hover { background: var(--primary-hover, #f1c944); transform: translateY(-1px); color: #0a0f1a; }
.oc-btn-whatsapp {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    background: #25d366;
    color: #fff;
    border: none;
    font-weight: 700;
    font-size: 16px;
    padding: 14px 28px;
    border-radius: 9999px;
    text-decoration: none;
    transition: background 0.2s, transform 0.15s, box-shadow 0.3s;
    margin-bottom: 10px;
    box-shadow: 0 4px 16px rgba(37,211,102,0.3);
    position: relative;
    overflow: visible;
}
.oc-btn-whatsapp::before {
    content: '';
    position: absolute;
    inset: -3px;
    border-radius: 9999px;
    border: 2px solid rgba(37,211,102,0.4);
    animation: oc-wa-pulse 2s ease-in-out infinite;
}
@keyframes oc-wa-pulse {
    0%, 100% { transform: scale(1); opacity: 0.6; }
    50% { transform: scale(1.06); opacity: 0; }
}
.oc-btn-whatsapp:hover {
    background: #1ebe5a;
    transform: translateY(-2px);
    box-shadow: 0 6px 24px rgba(37,211,102,0.4);
    color: #fff;
}
.oc-btn-full { width: 100%; }
.oc-booking-card__call {
    text-align: center;
    font-size: 13px;
    color: var(--text-muted);
    margin: 8px 0 0;
}
.oc-booking-card__call a {
    color: var(--primary);
    text-decoration: none;
}

/* Related vessels */
.oc-related-section {
    padding-bottom: 80px;
}
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
    background: var(--primary);
    color: #0a0f1a;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    padding: 3px 10px;
    border-radius: 999px;
}
.oc-related-card__body {
    padding: 16px;
}
.oc-related-card__title {
    font-family: var(--font-heading);
    font-size: 17px;
    font-weight: 400;
    color: var(--text-light, #f8fafc);
    margin: 0 0 6px;
}
.oc-related-card__price {
    font-size: 14px;
    color: var(--primary);
}

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
.oc-cta-strip__text p {
    font-size: 17px;
    color: var(--text-muted);
    margin: 0;
}
.oc-cta-strip__actions {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
}

/* Responsive */
@media (max-width: 1024px) {
    .oc-vessel-body {
        grid-template-columns: 1fr;
        gap: 40px;
    }
    .oc-vessel-sidebar {
        position: static;
        order: -1;
    }
}
@media (max-width: 768px) {
    .oc-related-grid { grid-template-columns: 1fr; }
    .oc-vessel-gallery { grid-template-columns: 1fr; }
    .oc-cta-strip__inner { flex-direction: column; text-align: center; }
    .oc-cta-strip__actions { justify-content: center; }
}
</style>

<?php get_footer(); ?>
