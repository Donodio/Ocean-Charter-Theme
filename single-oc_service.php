<?php
/**
 * Single Service Template — Stitch Design
 *
 * @package OceanCharter
 */

get_header();
?>

<main id="primary" class="site-main oc-single-service">

<?php if ( have_posts() ) : the_post();

    $post_id = get_the_ID();

    /* ── Elementor builder pass-through ── */
    $elementor_mode = get_post_meta( $post_id, '_elementor_edit_mode', true );
    if ( 'builder' === $elementor_mode ) :
        the_content();
    else :

    /* ── Meta ── */
    $hero_img    = get_the_post_thumbnail_url( $post_id, 'full' ) ?: 'https://images.pexels.com/photos/1481909/pexels-photo-1481909.jpeg?auto=compress&cs=tinysrgb&w=1920';
    $eyebrow     = get_post_meta( $post_id, '_oc_eyebrow', true );
    $badge_icon  = get_post_meta( $post_id, '_oc_badge_icon', true );
    $features_raw = get_post_meta( $post_id, '_oc_features', true );
    $features    = $features_raw ? json_decode( $features_raw, true ) : [];

    // Gallery (meta stores image URLs directly, not attachment IDs)
    $gallery = [];
    for ( $gi = 1; $gi <= 4; $gi++ ) {
        $gurl = get_post_meta( $post_id, '_oc_svc_gallery_' . $gi, true );
        if ( $gurl && filter_var( $gurl, FILTER_VALIDATE_URL ) ) {
            $gallery[] = $gurl;
        }
    }

    // Highlights
    $highlights_raw = get_post_meta( $post_id, '_oc_svc_highlights', true );
    $highlights = $highlights_raw ? json_decode( $highlights_raw, true ) : [];
    if ( ! is_array( $highlights ) ) $highlights = [];
    $highlights = array_filter( $highlights, fn( $h ) => ! empty( $h['title'] ) );

    // Testimonial
    $testimonial        = get_post_meta( $post_id, '_oc_svc_testimonial', true );
    $testimonial_author = get_post_meta( $post_id, '_oc_svc_testimonial_author', true );

    // WhatsApp
    $wa_number = get_post_meta( $post_id, '_oc_svc_whatsapp', true ) ?: get_theme_mod( 'oc_whatsapp_number', '' );
    $wa_url    = $wa_number
        ? 'https://wa.me/' . preg_replace( '/[^0-9]/', '', $wa_number ) . '?text=' . rawurlencode( 'Hello, I\'d like to enquire about ' . get_the_title() . '.' )
        : '';
    ?>

    <!-- ══ HERO ══════════════════════════════════════════════════════════════ -->
    <?php $_hero_pos = get_post_meta( $post_id, '_oc_hero_position', true ) ?: 'center center'; ?>
    <section class="oc-svc-hero" style="background-image:url('<?php echo esc_url( $hero_img ); ?>');background-size:cover;background-position:<?php echo esc_attr( $_hero_pos ); ?>;">
        <div class="oc-svc-hero__overlay"></div>
        <div class="oc-svc-hero__content oc-container">
            <?php if ( $eyebrow && stripos( get_the_title(), $eyebrow ) === false ) : ?>
                <span class="oc-svc-eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
            <?php endif; ?>
            <div class="oc-svc-title-row">
                <h1 class="oc-svc-hero__title"><?php the_title(); ?></h1>
                <?php if ( $badge_icon ) : ?>
                    <span class="oc-svc-badge-icon" aria-hidden="true"><?php echo esc_html( $badge_icon ); ?></span>
                <?php endif; ?>
            </div>
            <?php $excerpt = get_the_excerpt(); if ( $excerpt ) : ?>
                <p class="oc-svc-hero__subtitle"><?php echo esc_html( $excerpt ); ?></p>
            <?php endif; ?>
        </div>
    </section>

    <!-- ══ BODY ══════════════════════════════════════════════════════════════ -->
    <div class="oc-svc-body oc-container">

        <!-- Left column -->
        <div class="oc-svc-main">

            <!-- Intro text (full WP content) -->
            <?php $content = get_the_content(); if ( trim( $content ) ) : ?>
            <section class="oc-svc-section">
                <div class="oc-svc-prose"><?php the_content(); ?></div>
            </section>
            <?php endif; ?>

            <!-- Bento Gallery -->
            <?php if ( ! empty( $gallery ) ) : $gc = count( $gallery ); ?>
            <section class="oc-svc-section">
                <div class="oc-svc-bento oc-svc-bento--<?php echo $gc; ?>">
                    <?php foreach ( $gallery as $idx => $gurl ) : ?>
                        <div class="oc-svc-bento__item<?php echo $idx === 0 ? ' oc-svc-bento__item--hero' : ''; ?>">
                            <img src="<?php echo esc_url( $gurl ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?> gallery" loading="lazy">
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

            <!-- Experience Highlights -->
            <?php if ( ! empty( $highlights ) ) : ?>
            <section class="oc-svc-section">
                <h2 class="oc-section-heading">Experience Highlights</h2>
                <div class="oc-highlights-grid">
                    <?php foreach ( $highlights as $hl ) :
                        $icon_name = $hl['icon'] ?? 'star';
                    ?>
                        <div class="oc-highlight-card">
                            <div class="oc-highlight-card__icon">
                                <?php echo oc_svc_highlight_icon( $icon_name ); ?>
                            </div>
                            <h3 class="oc-highlight-card__title"><?php echo esc_html( $hl['title'] ); ?></h3>
                            <?php if ( ! empty( $hl['desc'] ) ) : ?>
                                <p class="oc-highlight-card__desc"><?php echo esc_html( $hl['desc'] ); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

            <!-- Feature Tags (simple list) -->
            <?php if ( ! empty( $features ) ) : ?>
            <section class="oc-svc-section">
                <h2 class="oc-section-heading">What's Included</h2>
                <div class="oc-features-grid">
                    <?php foreach ( $features as $feature ) : ?>
                        <div class="oc-feature-pill">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M5 13l4 4L19 7" stroke="#d9b230" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span><?php echo esc_html( $feature ); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

            <!-- Testimonial -->
            <?php if ( $testimonial ) : ?>
            <section class="oc-svc-section">
                <blockquote class="oc-svc-testimonial">
                    <svg class="oc-svc-testimonial__quote-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M3 21c3 0 7-1 7-8V5c0-1.25-.757-2.017-2-2H4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V20c0 1 0 1 1 1z" fill="rgba(217,178,48,0.2)"/>
                        <path d="M15 21c3 0 7-1 7-8V5c0-1.25-.757-2.017-2-2h-4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V20c0 1 0 1 1 1z" fill="rgba(217,178,48,0.2)"/>
                    </svg>
                    <p class="oc-svc-testimonial__text"><?php echo wp_kses_post( $testimonial ); ?></p>
                    <?php if ( $testimonial_author ) : ?>
                        <cite class="oc-svc-testimonial__author">&mdash; <?php echo esc_html( $testimonial_author ); ?></cite>
                    <?php endif; ?>
                </blockquote>
            </section>
            <?php endif; ?>

        </div><!-- /.oc-svc-main -->

        <!-- Sticky sidebar -->
        <aside class="oc-svc-sidebar">

            <!-- Inquiry Form -->
            <div class="oc-sidebar-form-card" id="oc-svc-inquiry">
                <h3 class="oc-sidebar-form-card__title">Enquire About This Service</h3>

                <form class="oc-sidebar-form" id="oc-svc-inquiry-form" novalidate>
                    <?php wp_nonce_field( 'oc_service_inquiry', 'oc_inquiry_nonce' ); ?>
                    <input type="hidden" name="action" value="oc_service_inquiry">
                    <input type="hidden" name="service_name" value="<?php echo esc_attr( get_the_title() ); ?>">
                    <input type="hidden" name="service_id" value="<?php echo esc_attr( $post_id ); ?>">

                    <div class="oc-sidebar-form__field">
                        <label for="oc-svc-name">Full Name <span class="oc-required">*</span></label>
                        <input type="text" id="oc-svc-name" name="inq_name" required placeholder="Your full name" autocomplete="name">
                        <span class="oc-field-error" id="oc-svc-name-err" style="display:none;color:#fc8181;font-size:12px;margin-top:4px;"></span>
                    </div>
                    <div class="oc-form-row--2col">
                        <div class="oc-sidebar-form__field">
                            <label for="oc-svc-dates">Preferred Date</label>
                            <input type="text" id="oc-svc-dates" name="inq_dates" placeholder="Select date" autocomplete="off" readonly style="cursor:pointer;">
                        </div>
                        <div class="oc-sidebar-form__field">
                            <label for="oc-svc-guests">Guests</label>
                            <select id="oc-svc-guests" name="inq_guests">
                                <option value="">Select guests</option>
                                <option value="1-2">1-2 Guests</option>
                                <option value="3-4">3-4 Guests</option>
                                <option value="5-8">5-8 Guests</option>
                                <option value="9-12">9-12 Guests</option>
                                <option value="13+">13+ Guests</option>
                            </select>
                        </div>
                    </div>
                    <div class="oc-sidebar-form__field">
                        <label for="oc-svc-email">Email <span class="oc-required">*</span></label>
                        <input type="email" id="oc-svc-email" name="inq_email" required placeholder="your@email.com" autocomplete="email">
                        <span class="oc-field-error" id="oc-svc-email-err" style="display:none;color:#fc8181;font-size:12px;margin-top:4px;"></span>
                    </div>
                    <div class="oc-sidebar-form__field">
                        <label for="oc-svc-message">Additional Requests</label>
                        <textarea id="oc-svc-message" name="inq_message" rows="3" placeholder="Tell us about any special requirements..."></textarea>
                    </div>

                    <button type="submit" class="oc-btn-gold oc-btn-full oc-btn-submit" id="oc-svc-submit">Send Enquiry</button>
                    <div class="oc-inquiry-status" id="oc-svc-inquiry-status" style="display:none;"></div>
                </form>
            </div>

            <!-- WhatsApp CTA -->
            <?php if ( $wa_url ) : ?>
            <div class="oc-sidebar-wa-card">
                <div class="oc-sidebar-wa-card__icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="#25d366" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                </div>
                <div class="oc-sidebar-wa-card__text">
                    <strong>Prefer to chat?</strong>
                    <span>Get an instant response via WhatsApp</span>
                </div>
                <a href="<?php echo esc_url( $wa_url ); ?>" class="oc-btn-whatsapp oc-btn-full" target="_blank" rel="noopener noreferrer">
                    Chat on WhatsApp
                </a>
            </div>
            <?php endif; ?>

        </aside>

    </div><!-- /.oc-svc-body -->

    <!-- ══ OTHER SERVICES ════════════════════════════════════════════════════ -->
    <?php
    $other_svcs = new WP_Query( [
        'post_type'      => 'oc_service',
        'posts_per_page' => 3,
        'post__not_in'   => [ $post_id ],
        'orderby'        => 'rand',
    ] );
    if ( $other_svcs->have_posts() ) : ?>
    <section class="oc-related-section oc-container">
        <h2 class="oc-section-heading">Other Services</h2>
        <div class="oc-related-grid">
            <?php while ( $other_svcs->have_posts() ) : $other_svcs->the_post();
                $s_id    = get_the_ID();
                $s_thumb = get_the_post_thumbnail_url( $s_id, 'medium_large' ) ?: $hero_img;
                $s_eye   = get_post_meta( $s_id, '_oc_eyebrow', true );
            ?>
                <a href="<?php echo esc_url( get_permalink( $s_id ) ); ?>" class="oc-related-card">
                    <div class="oc-related-card__img" style="background-image:url('<?php echo esc_url( $s_thumb ); ?>');">
                        <?php if ( $s_eye ) : ?>
                            <span class="oc-related-card__badge"><?php echo esc_html( $s_eye ); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="oc-related-card__body">
                        <h3 class="oc-related-card__title"><?php the_title(); ?></h3>
                        <?php $s_exc = get_the_excerpt(); if ( $s_exc ) : ?>
                            <p class="oc-related-card__excerpt"><?php echo esc_html( wp_trim_words( $s_exc, 12 ) ); ?></p>
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
                    <h2>Elevate Your <span class="text-primary">Charter</span></h2>
                    <p>Add world-class services to create your perfect voyage.</p>
                </div>
                <div class="oc-cta-strip__actions">
                    <a href="#oc-svc-inquiry" class="btn-primary">Enquire Now</a>
                    <?php if ( $wa_url ) : ?>
                        <a href="<?php echo esc_url( $wa_url ); ?>" class="btn-secondary" target="_blank" rel="noopener noreferrer">WhatsApp Us</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <?php endif; // end elementor check ?>
<?php endif; // end have_posts ?>

</main>

<?php
/**
 * SVG icon helper for service highlights.
 */
function oc_svc_highlight_icon( $name ) {
    $icons = [
        'star'    => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>',
        'utensils' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 002-2V2"/><path d="M7 2v20"/><path d="M21 15V2v0a5 5 0 00-5 5v6c0 1.1.9 2 2 2h3zm0 0v7"/></svg>',
        'music'   => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>',
        'compass' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/></svg>',
        'anchor'  => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="5" r="3"/><line x1="12" y1="22" x2="12" y2="8"/><path d="M5 12H2a10 10 0 0020 0h-3"/></svg>',
        'shield'  => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
        'heart'   => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>',
        'sun'     => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>',
    ];
    return $icons[ $name ] ?? $icons['star'];
}
?>

<style>
/* ── Ocean Charter: Single Service — Stitch Design ─────────────────────── */

.oc-single-service {
    background: #0a0f1a;
    color: var(--text);
}

/* Hero */
.oc-svc-hero {
    position: relative;
    min-height: 55vh;
    background-size: cover;
    background-position: center;
    display: flex;
    align-items: flex-end;
    padding-bottom: 56px;
}
.oc-svc-hero__overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(10,15,26,0.95) 0%, rgba(10,15,26,0.35) 55%, rgba(10,15,26,0.1) 100%);
}
.oc-svc-hero__content {
    position: relative;
    z-index: 2;
}
.oc-svc-eyebrow {
    display: inline-block;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: var(--primary);
    margin-bottom: 14px;
    background: rgba(217,178,48,0.1);
    padding: 4px 12px;
    border-radius: 9999px;
    border: 1px solid rgba(217,178,48,0.2);
}
.oc-svc-title-row {
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}
.oc-svc-hero__title {
    font-family: var(--font-heading);
    font-size: clamp(32px, 4.5vw, 56px);
    font-weight: 400;
    color: var(--text-light, #f8fafc);
    line-height: 1.1;
    margin: 0;
}
.oc-svc-badge-icon {
    font-size: 36px;
    line-height: 1;
}
.oc-svc-hero__subtitle {
    font-size: 17px;
    line-height: 1.7;
    color: var(--text-muted);
    margin: 16px 0 0;
    max-width: 640px;
}

/* Body layout */
.oc-svc-body {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 48px;
    padding-top: 56px;
    padding-bottom: 100px;
    align-items: start;
}
.oc-svc-section {
    margin-bottom: 48px;
}
.oc-section-heading {
    font-family: var(--font-heading);
    font-size: 26px;
    font-weight: 400;
    color: var(--text-light, #f8fafc);
    margin: 0 0 24px;
    padding-bottom: 14px;
    border-bottom: 1px solid var(--border);
}
.oc-svc-prose {
    font-size: 16px;
    line-height: 1.8;
    color: var(--text-muted);
}
.oc-svc-prose p { margin: 0 0 16px; }

/* ── Bento Gallery ── */
.oc-svc-bento {
    display: grid;
    gap: 8px;
    border-radius: 1rem;
    overflow: hidden;
}
.oc-svc-bento--1 { grid-template-columns: 1fr; }
.oc-svc-bento--2 { grid-template-columns: 1fr 1fr; }
.oc-svc-bento--3 {
    grid-template-columns: 1fr 1fr;
    grid-template-rows: auto auto;
}
.oc-svc-bento--3 .oc-svc-bento__item--hero { grid-row: 1 / 3; }
.oc-svc-bento--4 {
    grid-template-columns: 1fr 1fr;
    grid-template-rows: 280px 200px;
}
.oc-svc-bento--4 .oc-svc-bento__item--hero { grid-row: 1 / 3; }
.oc-svc-bento__item {
    overflow: hidden;
    border-radius: 0.5rem;
}
.oc-svc-bento__item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.5s ease;
}
.oc-svc-bento__item:hover img { transform: scale(1.05); }

/* ── Highlights Grid ── */
.oc-highlights-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}
.oc-highlight-card {
    background: var(--surface, #111a28);
    border: 1px solid var(--border);
    border-radius: 0.75rem;
    padding: 24px;
    transition: border-color 0.2s, transform 0.2s;
}
.oc-highlight-card:hover {
    border-color: rgba(217,178,48,0.3);
    transform: translateY(-2px);
}
.oc-highlight-card__icon {
    width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(217,178,48,0.1);
    border-radius: 0.75rem;
    color: var(--primary);
    margin-bottom: 16px;
}
.oc-highlight-card__title {
    font-family: var(--font-heading);
    font-size: 17px;
    font-weight: 400;
    color: var(--text-light, #f8fafc);
    margin: 0 0 8px;
}
.oc-highlight-card__desc {
    font-size: 14px;
    line-height: 1.6;
    color: var(--text-muted);
    margin: 0;
}

/* ── Feature Pills ── */
.oc-features-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}
.oc-feature-pill {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    background: var(--surface, #111a28);
    border: 1px solid var(--border);
    border-radius: 0.5rem;
    padding: 12px 14px;
    font-size: 14px;
    color: var(--text);
    line-height: 1.4;
    transition: border-color 0.2s;
}
.oc-feature-pill:hover { border-color: var(--primary); }
.oc-feature-pill svg { flex-shrink: 0; margin-top: 1px; }

/* ── Testimonial ── */
.oc-svc-testimonial {
    background: var(--surface, #111a28);
    border: 1px solid var(--border);
    border-left: 3px solid var(--primary);
    border-radius: 0.75rem;
    padding: 32px;
    margin: 0;
    position: relative;
}
.oc-svc-testimonial__quote-icon {
    position: absolute;
    top: 20px;
    right: 24px;
    opacity: 0.5;
}
.oc-svc-testimonial__text {
    font-size: 17px;
    font-style: italic;
    line-height: 1.7;
    color: var(--text-light, #f8fafc);
    margin: 0 0 16px;
}
.oc-svc-testimonial__author {
    font-style: normal;
    font-size: 14px;
    font-weight: 600;
    color: var(--primary);
}

/* ── Sidebar ── */
.oc-svc-sidebar {
    position: sticky;
    top: 100px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}
.oc-sidebar-form-card {
    background: var(--surface, #111a28);
    border: 1px solid var(--border);
    border-radius: 1rem;
    padding: 1.5rem;
}
.oc-sidebar-form-card__title {
    font-family: var(--font-heading);
    font-size: 18px;
    font-weight: 400;
    color: var(--text-light, #f8fafc);
    margin: 0 0 18px;
    padding-bottom: 14px;
    border-bottom: 1px solid var(--border);
    text-align: center;
}
.oc-sidebar-form {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 14px;
}
.oc-sidebar-form__field label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: var(--text-muted, rgba(240,236,227,0.7));
    margin-bottom: 5px;
    letter-spacing: 0.04em;
    text-transform: uppercase;
}
.oc-required { color: var(--primary); }
.oc-sidebar-form__field input,
.oc-sidebar-form__field textarea,
.oc-sidebar-form__field select {
    width: 100%;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    color: #f0ece3;
    font-size: 14px;
    padding: 10px 12px;
    border-radius: 0.5rem;
    transition: border-color 0.2s;
    font-family: inherit;
    box-sizing: border-box;
}
.oc-sidebar-form__field select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg width='12' height='8' viewBox='0 0 12 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1.5L6 6.5L11 1.5' stroke='%23f0ece3' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    padding-right: 32px;
}
.oc-sidebar-form__field input:focus,
.oc-sidebar-form__field textarea:focus,
.oc-sidebar-form__field select:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 2px rgba(217,178,48,0.15);
}
.oc-sidebar-form__field input::placeholder,
.oc-sidebar-form__field textarea::placeholder {
    color: rgba(240,236,227,0.3);
}
.oc-inquiry-status {
    margin-top: 12px;
    padding: 12px 14px;
    border-radius: 0.5rem;
    font-size: 13px;
    text-align: center;
}
.oc-inquiry-status.success {
    background: rgba(37,211,102,0.15);
    color: #25d366;
    border: 1px solid rgba(37,211,102,0.3);
}
.oc-inquiry-status.error {
    background: rgba(239,68,68,0.15);
    color: #ef4444;
    border: 1px solid rgba(239,68,68,0.3);
}

/* WhatsApp CTA card */
.oc-sidebar-wa-card {
    background: var(--surface, #111a28);
    border: 1px solid var(--border);
    border-radius: 1rem;
    padding: 1.25rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    text-align: center;
}
.oc-sidebar-wa-card__icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(37,211,102,0.1);
    border-radius: 50%;
}
.oc-sidebar-wa-card__text strong {
    display: block;
    font-size: 14px;
    color: var(--text-light, #f8fafc);
    margin-bottom: 2px;
}
.oc-sidebar-wa-card__text span {
    font-size: 13px;
    color: var(--text-muted);
}

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
}
.oc-btn-gold:hover { background: var(--primary-hover, #f1c944); transform: translateY(-1px); color: #0a0f1a; }
.oc-btn-submit { border: none; cursor: pointer; font-family: inherit; }
.oc-btn-submit:disabled { opacity: 0.6; cursor: wait; }
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

/* Related services */
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
    background: var(--primary);
    color: #0a0f1a;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    padding: 3px 10px;
    border-radius: 999px;
}
.oc-related-card__body { padding: 16px; }
.oc-related-card__title {
    font-family: var(--font-heading);
    font-size: 17px;
    font-weight: 400;
    color: var(--text-light, #f8fafc);
    margin: 0 0 6px;
}
.oc-related-card__excerpt {
    font-size: 13px;
    color: var(--text-muted);
    margin: 0;
    line-height: 1.5;
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
.oc-cta-strip__text p { font-size: 17px; color: var(--text-muted); margin: 0; }
.oc-cta-strip__actions { display: flex; gap: 16px; flex-wrap: wrap; }

/* ── Responsive ── */
@media (max-width: 1024px) {
    .oc-svc-body { grid-template-columns: 1fr; gap: 40px; }
    .oc-svc-sidebar { position: static; order: -1; }
}
@media (max-width: 768px) {
    .oc-related-grid { grid-template-columns: 1fr; }
    .oc-features-grid { grid-template-columns: 1fr; }
    .oc-highlights-grid { grid-template-columns: 1fr; }
    .oc-svc-bento--3,
    .oc-svc-bento--4 { grid-template-columns: 1fr; }
    .oc-svc-bento--3 .oc-svc-bento__item--hero,
    .oc-svc-bento--4 .oc-svc-bento__item--hero { grid-row: auto; }
    .oc-cta-strip__inner { flex-direction: column; text-align: center; }
    .oc-cta-strip__actions { justify-content: center; }
}
</style>

<script>
(function() {
    var form = document.getElementById('oc-svc-inquiry-form');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        var btn = document.getElementById('oc-svc-submit');
        var status = document.getElementById('oc-svc-inquiry-status');
        var origText = btn.textContent;
        btn.textContent = 'Sending...';
        btn.disabled = true;
        status.style.display = 'none';

        var formData = new FormData(form);

        fetch(<?php echo wp_json_encode( admin_url( 'admin-ajax.php' ) ); ?>, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            status.style.display = 'block';
            if (data.success) {
                status.className = 'oc-inquiry-status success';
                status.textContent = data.data || 'Thank you! We will be in touch shortly.';
                form.reset();
            } else {
                status.className = 'oc-inquiry-status error';
                status.textContent = data.data || 'Something went wrong. Please try again.';
            }
        })
        .catch(function() {
            status.style.display = 'block';
            status.className = 'oc-inquiry-status error';
            status.textContent = 'Network error. Please try again.';
        })
        .finally(function() {
            btn.textContent = origText;
            btn.disabled = false;
        });
    });

    // ── Flatpickr date picker for preferred date ──
    var dateInput = document.getElementById('oc-svc-dates');
    if (dateInput && typeof flatpickr !== 'undefined') {
        flatpickr(dateInput, {
            minDate: 'today',
            dateFormat: 'Y-m-d',
            disableMobile: false,
            allowInput: false,
        });
    }

    // ── Client-side validation ──
    function ocValidateEmail(v) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v.trim()); }
    function ocValidatePhone(v) { return v.trim() === '' || /^[+]?[\d\s\-(). ]{7,20}$/.test(v.trim()); }

    function ocShowErr(id, msg) {
        var el = document.getElementById(id);
        if (!el) return;
        el.textContent = msg; el.style.display = msg ? 'block' : 'none';
    }

    var emailInput = document.getElementById('oc-svc-email');
    if (emailInput) {
        emailInput.addEventListener('blur', function() {
            ocShowErr('oc-svc-email-err', ocValidateEmail(this.value) ? '' : 'Please enter a valid email address.');
        });
    }
    var nameInput = document.getElementById('oc-svc-name');
    if (nameInput) {
        nameInput.addEventListener('blur', function() {
            ocShowErr('oc-svc-name-err', this.value.trim() ? '' : 'Full name is required.');
        });
    }
})();
</script>

<?php get_footer(); ?>
