<?php
/**
 * Single Package Template
 *
 * @package OceanCharter
 */

get_header();
?>

<main id="primary" class="site-main oc-single-package">

<?php if ( have_posts() ) : the_post();

    $post_id = get_the_ID();

    /* ── Elementor builder pass-through ── */
    $elementor_mode = get_post_meta( $post_id, '_elementor_edit_mode', true );
    if ( 'builder' === $elementor_mode ) :
        the_content();
    else :

    /* ── Meta ── */
    $hero_img       = get_the_post_thumbnail_url( $post_id, 'full' ) ?: 'https://images.pexels.com/photos/1032650/pexels-photo-1032650.jpeg?auto=compress&cs=tinysrgb&w=1920';
    $tag            = get_post_meta( $post_id, '_oc_tag', true );
    $duration       = get_post_meta( $post_id, '_oc_duration', true );
    $price          = get_post_meta( $post_id, '_oc_price', true );
    $cta_url        = get_post_meta( $post_id, '_oc_cta_url', true ) ?: home_url( '/contact/' );
    $inclusions_raw = get_post_meta( $post_id, '_oc_inclusions', true );
    $inclusions     = $inclusions_raw ? json_decode( $inclusions_raw, true ) : [];

    $pkg_types     = wp_get_post_terms( $post_id, 'oc_package_type', [ 'fields' => 'names' ] );

    $wa_url = function_exists( 'oc_whatsapp_url' ) ? oc_whatsapp_url( 'Hello, I\'d like to book the ' . get_the_title() . ' package.' ) : 'https://wa.me/' . get_theme_mod( 'oc_whatsapp_number', '15551234567' );
    ?>

    <!-- ══ HERO ══════════════════════════════════════════════════════════════ -->
    <?php $_hero_pos = get_post_meta( $post_id, '_oc_hero_position', true ) ?: 'center center'; ?>
    <section class="oc-pkg-hero" style="background-image:url('<?php echo esc_url( $hero_img ); ?>');background-size:cover;background-position:<?php echo esc_attr( $_hero_pos ); ?>;">
        <div class="oc-pkg-hero__overlay"></div>
        <div class="oc-pkg-hero__content oc-container">
            <?php if ( $tag ) : ?>
                <span class="oc-pkg-badge"><?php echo esc_html( $tag ); ?></span>
            <?php endif; ?>
            <h1 class="oc-pkg-hero__title"><?php the_title(); ?></h1>
            <div class="oc-pkg-pills-strip">
                <?php if ( $duration ) : ?>
                    <span class="oc-spec-pill">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <?php echo esc_html( $duration ); ?>
                    </span>
                <?php endif; ?>
                <?php if ( $price ) : ?>
                    <span class="oc-spec-pill">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        From $<?php echo esc_html( number_format( (float) $price ) ); ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- ══ BODY ══════════════════════════════════════════════════════════════ -->
    <div class="oc-pkg-body oc-container">

        <!-- Left column -->
        <div class="oc-pkg-main">

            <!-- Description -->
            <?php $excerpt = get_the_excerpt(); if ( $excerpt ) : ?>
            <section class="oc-pkg-section">
                <h2 class="oc-section-heading">About This Package</h2>
                <p class="oc-pkg-desc"><?php echo wp_kses_post( $excerpt ); ?></p>
            </section>
            <?php endif; ?>

            <!-- What's Included -->
            <?php if ( ! empty( $inclusions ) ) : ?>
            <section class="oc-pkg-section">
                <h2 class="oc-section-heading">What&rsquo;s Included</h2>
                <div class="oc-inclusions-grid">
                    <?php foreach ( $inclusions as $item ) : ?>
                        <div class="oc-inclusion-item">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M5 13l4 4L19 7" stroke="#d9b230" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span><?php echo esc_html( $item ); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

            <!-- Suitable for -->
            <?php if ( ! is_wp_error( $pkg_types ) && ! empty( $pkg_types ) ) : ?>
            <section class="oc-pkg-section">
                <h2 class="oc-section-heading">Suitable For</h2>
                <div class="oc-pkg-type-pills">
                    <?php foreach ( $pkg_types as $pt ) : ?>
                        <span class="oc-type-pill"><?php echo esc_html( $pt ); ?></span>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

        </div><!-- /.oc-pkg-main -->

        <!-- Sticky sidebar -->
        <aside class="oc-pkg-sidebar">
            <div class="oc-booking-card">
                <h3 class="oc-booking-card__title">Book This Package</h3>

                <?php if ( $price ) : ?>
                    <div class="oc-booking-card__price-primary">
                        <span class="oc-price-label">From</span>
                        <span class="oc-price-amount">$<?php echo esc_html( number_format( (float) $price ) ); ?></span>
                    </div>
                <?php endif; ?>

                <?php if ( $duration ) : ?>
                    <div class="oc-booking-card__duration">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <?php echo esc_html( $duration ); ?>
                    </div>
                <?php endif; ?>

                <a href="<?php echo esc_url( $cta_url ); ?>" class="oc-btn-gold oc-btn-full">Book This Package</a>

                <a href="<?php echo esc_url( $wa_url ); ?>" class="oc-btn-whatsapp oc-btn-full" target="_blank" rel="noopener noreferrer">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    WhatsApp Us
                </a>

                <p class="oc-booking-card__note">Prices include crew, fuel &amp; provisions</p>
            </div>
        </aside>

    </div><!-- /.oc-pkg-body -->

    <!-- ══ RELATED PACKAGES ═══════════════════════════════════════════════════ -->
    <?php
    $related = new WP_Query( [
        'post_type'      => 'oc_package',
        'posts_per_page' => 3,
        'post__not_in'   => [ $post_id ],
        'orderby'        => 'rand',
    ] );
    if ( $related->have_posts() ) : ?>
    <section class="oc-related-section oc-container">
        <h2 class="oc-section-heading">Other Packages</h2>
        <div class="oc-related-grid">
            <?php while ( $related->have_posts() ) : $related->the_post();
                $rel_id    = get_the_ID();
                $rel_thumb = get_the_post_thumbnail_url( $rel_id, 'medium_large' ) ?: $hero_img;
                $rel_price = get_post_meta( $rel_id, '_oc_price', true );
                $rel_dur   = get_post_meta( $rel_id, '_oc_duration', true );
            ?>
                <a href="<?php echo esc_url( get_permalink( $rel_id ) ); ?>" class="oc-related-card">
                    <div class="oc-related-card__img" style="background-image:url('<?php echo esc_url( $rel_thumb ); ?>');">
                        <?php if ( $rel_dur ) : ?>
                            <span class="oc-related-card__badge"><?php echo esc_html( $rel_dur ); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="oc-related-card__body">
                        <h3 class="oc-related-card__title"><?php the_title(); ?></h3>
                        <?php if ( $rel_price ) : ?>
                            <span class="oc-related-card__price">From $<?php echo esc_html( number_format( (float) $rel_price ) ); ?></span>
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
                    <h2>Ready to <span class="text-primary">Book?</span></h2>
                    <p>Let us craft your perfect charter experience.</p>
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
/* ── Ocean Charter: Single Package ────────────────────────────────────────── */

.oc-single-package {
    background: #0a0f1a;
    color: var(--text);
}

/* Hero */
.oc-pkg-hero {
    position: relative;
    min-height: 50vh;
    background-size: cover;
    background-position: center;
    display: flex;
    align-items: flex-end;
    padding-bottom: 56px;
}
.oc-pkg-hero__overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(10,15,26,0.95) 0%, rgba(10,15,26,0.35) 55%, rgba(10,15,26,0.1) 100%);
}
.oc-pkg-hero__content {
    position: relative;
    z-index: 2;
    max-width: 900px;
}
.oc-pkg-badge {
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
.oc-pkg-hero__title {
    font-family: var(--font-heading);
    font-size: clamp(32px, 4.5vw, 60px);
    font-weight: 400;
    color: var(--text-light, #f8fafc);
    line-height: 1.1;
    margin: 0 0 24px;
}
.oc-pkg-pills-strip {
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
.oc-pkg-body {
    display: grid;
    grid-template-columns: 1fr 360px;
    gap: 60px;
    padding-top: 64px;
    padding-bottom: 100px;
    align-items: start;
}
.oc-pkg-section {
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
.oc-pkg-desc {
    font-size: 17px;
    line-height: 1.8;
    color: var(--text-muted);
}

/* Inclusions */
.oc-inclusions-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}
.oc-inclusion-item {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    color: var(--text);
    font-size: 15px;
    line-height: 1.5;
}
.oc-inclusion-item svg { flex-shrink: 0; margin-top: 2px; }

/* Type pills */
.oc-pkg-type-pills {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}
.oc-type-pill {
    background: var(--surface, #111a28);
    border: 1px solid var(--border);
    color: var(--text-muted);
    font-size: 13px;
    padding: 6px 14px;
    border-radius: 999px;
}

/* Booking card sidebar */
.oc-pkg-sidebar {
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
    margin-bottom: 12px;
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
.oc-booking-card__duration {
    display: flex;
    align-items: center;
    gap: 6px;
    color: var(--text-muted);
    font-size: 14px;
    margin-bottom: 20px;
}
.oc-booking-card__duration svg { color: var(--primary); }
.oc-booking-card__note {
    text-align: center;
    font-size: 12px;
    color: var(--text-muted);
    margin: 10px 0 0;
    font-style: italic;
}
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
    gap: 8px;
    background: transparent;
    color: #25d366;
    border: 1.5px solid #25d366;
    font-weight: 600;
    font-size: 15px;
    padding: 12px 24px;
    border-radius: 0.5rem;
    text-decoration: none;
    transition: background 0.2s, color 0.2s;
    margin-bottom: 10px;
}
.oc-btn-whatsapp:hover { background: #25d366; color: #fff; }
.oc-btn-full { width: 100%; }

/* Related packages */
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
    background: rgba(17,26,40,0.85);
    color: var(--primary);
    border: 1px solid rgba(217,178,48,0.4);
    font-size: 10px;
    font-weight: 600;
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

@media (max-width: 1024px) {
    .oc-pkg-body { grid-template-columns: 1fr; gap: 40px; }
    .oc-pkg-sidebar { position: static; order: -1; }
}
@media (max-width: 768px) {
    .oc-related-grid { grid-template-columns: 1fr; }
    .oc-inclusions-grid { grid-template-columns: 1fr; }
    .oc-cta-strip__inner { flex-direction: column; text-align: center; }
    .oc-cta-strip__actions { justify-content: center; }
}
</style>

<?php get_footer(); ?>
