<?php
/**
 * Single Offer Template
 *
 * @package OceanCharter
 */

get_header();
?>

<main id="primary" class="site-main oc-single-offer">

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
    $hero_img      = $_oc_hero_custom ?: ( get_the_post_thumbnail_url( $post_id, 'full' ) ?: 'https://images.pexels.com/photos/3225517/pexels-photo-3225517.jpeg?auto=compress&cs=tinysrgb&w=1920' );
    $_hero_pos     = get_post_meta( $post_id, '_oc_hero_position', true ) ?: 'center center';
    $badge_text    = get_post_meta( $post_id, '_oc_badge_text', true );
    $subtitle      = get_post_meta( $post_id, '_oc_subtitle', true );
    $discount      = get_post_meta( $post_id, '_oc_discount', true );
    $discount_type = get_post_meta( $post_id, '_oc_discount_type', true );
    $valid_from    = get_post_meta( $post_id, '_oc_valid_from', true );
    $valid_to      = get_post_meta( $post_id, '_oc_valid_to', true );
    $cta_url       = get_post_meta( $post_id, '_oc_cta_url', true );
    $terms         = get_post_meta( $post_id, '_oc_terms', true );
    $linked_pkg    = (int) get_post_meta( $post_id, '_oc_linked_package', true );

    $fmt_from = $valid_from ? date_i18n( 'j M Y', strtotime( $valid_from ) ) : '';
    $fmt_to   = $valid_to   ? date_i18n( 'j M Y', strtotime( $valid_to ) )   : '';

    $discount_type_labels = [
        'percent'     => 'Percentage Discount',
        'fixed'       => 'Fixed Amount Off',
        'upgrade'     => 'Free Upgrade',
        'nights_free' => 'Free Night(s)',
        'bundle'      => 'Bundle Deal',
    ];
    $discount_type_label = isset( $discount_type_labels[ $discount_type ] ) ? $discount_type_labels[ $discount_type ] : $discount_type;

    $wa_url = function_exists( 'oc_whatsapp_url' ) ? oc_whatsapp_url( 'Hello, I\'d like to claim the offer: ' . get_the_title() . '.' ) : 'https://wa.me/' . get_theme_mod( 'oc_whatsapp_number', '15551234567' );
    ?>

    <!-- ══ HERO ══════════════════════════════════════════════════════════════ -->
    <section class="oc-offer-hero" style="background-image:url('<?php echo esc_url( $hero_img ); ?>');background-size:cover;background-position:<?php echo esc_attr( $_hero_pos ); ?>;">
        <div class="oc-offer-hero__overlay"></div>
        <div class="oc-offer-hero__content oc-container">
            <?php if ( $badge_text ) : ?>
                <span class="oc-offer-badge"><?php echo esc_html( $badge_text ); ?></span>
            <?php endif; ?>
            <h1 class="oc-offer-hero__title"><?php the_title(); ?></h1>
        </div>
    </section>

    <!-- ══ BODY ══════════════════════════════════════════════════════════════ -->
    <div class="oc-offer-body oc-container">

        <!-- Left column -->
        <div class="oc-offer-main">

            <?php if ( $subtitle ) : ?>
                <p class="oc-offer-subtitle"><?php echo esc_html( $subtitle ); ?></p>
            <?php endif; ?>

            <?php $excerpt = get_the_excerpt(); if ( $excerpt ) : ?>
            <section class="oc-offer-section">
                <h2 class="oc-section-heading">About This Offer</h2>
                <p class="oc-offer-desc"><?php echo wp_kses_post( $excerpt ); ?></p>
            </section>
            <?php endif; ?>

            <?php $content = get_the_content(); if ( trim( $content ) ) : ?>
            <section class="oc-offer-section">
                <h2 class="oc-section-heading">Offer Details</h2>
                <div class="oc-offer-prose"><?php the_content(); ?></div>
            </section>
            <?php endif; ?>

            <?php if ( $fmt_from || $fmt_to ) : ?>
            <section class="oc-offer-section">
                <h2 class="oc-section-heading">Offer Validity</h2>
                <div class="oc-offer-validity">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--primary, #d9b230)" stroke-width="2" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    Valid:
                    <?php if ( $fmt_from && $fmt_to ) : ?>
                        <strong><?php echo esc_html( $fmt_from ); ?></strong> &mdash; <strong><?php echo esc_html( $fmt_to ); ?></strong>
                    <?php elseif ( $fmt_to ) : ?>
                        Until <strong><?php echo esc_html( $fmt_to ); ?></strong>
                    <?php else : ?>
                        From <strong><?php echo esc_html( $fmt_from ); ?></strong>
                    <?php endif; ?>
                </div>
            </section>
            <?php endif; ?>

            <?php if ( $discount_type_label ) : ?>
            <section class="oc-offer-section">
                <h2 class="oc-section-heading">Offer Type</h2>
                <span class="oc-offer-type-label"><?php echo esc_html( $discount_type_label ); ?></span>
            </section>
            <?php endif; ?>

            <?php if ( $terms ) : ?>
            <section class="oc-offer-section">
                <h2 class="oc-section-heading">Terms &amp; Conditions</h2>
                <div class="oc-offer-prose oc-offer-terms"><?php echo wp_kses_post( wpautop( $terms ) ); ?></div>
            </section>
            <?php endif; ?>

            <?php if ( $linked_pkg && get_post_type( $linked_pkg ) === 'bbc_package' ) : ?>
            <section class="oc-offer-section">
                <h2 class="oc-section-heading">Book This Package</h2>
                <div class="oc-offer-bbc-form-wrap">
                    <?php echo do_shortcode( '[bbc_booking_form package_id="' . $linked_pkg . '"]' ); ?>
                </div>
            </section>
            <?php endif; ?>

        </div><!-- /.oc-offer-main -->

        <!-- Sticky sidebar with booking form -->
        <aside class="oc-offer-sidebar">

            <!-- Discount info card -->
            <div class="oc-booking-card">
                <h3 class="oc-booking-card__title">Claim This Offer</h3>
                <?php if ( $discount ) : ?>
                    <div class="oc-offer-card-discount"><?php echo esc_html( $discount ); ?></div>
                <?php endif; ?>
                <?php if ( $fmt_to ) : ?>
                    <p class="oc-offer-card-valid">Valid until <strong><?php echo esc_html( $fmt_to ); ?></strong></p>
                <?php endif; ?>
            </div>

            <!-- Booking / Inquiry Form -->
            <div class="oc-sidebar-form-card" id="oc-offer-inquiry">
                <h3 class="oc-booking-card__title">Book This Offer</h3>

                <form class="oc-sidebar-form" id="oc-offer-inquiry-form" novalidate>
                    <?php wp_nonce_field( 'oc_offer_inquiry', 'oc_inquiry_nonce' ); ?>
                    <input type="hidden" name="action" value="oc_offer_inquiry">
                    <input type="hidden" name="offer_name" value="<?php echo esc_attr( get_the_title() ); ?>">
                    <input type="hidden" name="offer_id" value="<?php echo esc_attr( $post_id ); ?>">

                    <div class="oc-form-grid">
                        <div class="oc-sidebar-form__field">
                            <label for="oc-offer-start">Start Date <span class="oc-required">*</span></label>
                            <input type="text" id="oc-offer-start" name="start_date" required placeholder="Select date" autocomplete="off">
                        </div>
                        <div class="oc-sidebar-form__field">
                            <label for="oc-offer-end">End Date <span class="oc-required">*</span></label>
                            <input type="text" id="oc-offer-end" name="end_date" required placeholder="Select date" autocomplete="off">
                        </div>
                        <div class="oc-sidebar-form__field">
                            <label for="oc-inq-name">Full Name <span class="oc-required">*</span></label>
                            <input type="text" id="oc-inq-name" name="inq_name" required placeholder="Your full name">
                        </div>
                        <div class="oc-sidebar-form__field">
                            <label for="oc-inq-email">Email <span class="oc-required">*</span></label>
                            <input type="email" id="oc-inq-email" name="inq_email" required placeholder="your@email.com">
                        </div>
                        <div class="oc-sidebar-form__field oc-field--wide">
                            <label for="oc-inq-phone">Phone</label>
                            <input type="tel" id="oc-inq-phone" name="inq_phone" placeholder="+1 (555) 000-0000">
                        </div>
                        <div class="oc-sidebar-form__field oc-field--narrow">
                            <label for="oc-inq-guests">Guests</label>
                            <input type="number" id="oc-inq-guests" name="guest_count" min="1" max="50" placeholder="e.g. 4">
                        </div>
                    </div>

                    <button type="submit" class="oc-btn-gold oc-btn-full oc-btn-submit" id="oc-inq-submit">Book Now</button>
                    <div class="oc-inquiry-status" id="oc-inquiry-status" style="display:none;"></div>
                </form>

                <a href="<?php echo esc_url( $wa_url ); ?>" class="oc-btn-whatsapp oc-btn-full" target="_blank" rel="noopener noreferrer">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    WhatsApp Us
                </a>

                <p class="oc-booking-card__note">Offer subject to availability</p>
            </div>

        </aside>

    </div><!-- /.oc-offer-body -->

    <!-- ══ OTHER OFFERS ═══════════════════════════════════════════════════════ -->
    <?php
    $other_offers = new WP_Query( [
        'post_type'      => 'oc_offer',
        'posts_per_page' => 3,
        'post__not_in'   => [ $post_id ],
        'orderby'        => 'rand',
    ] );
    if ( $other_offers->have_posts() ) : ?>
    <section class="oc-related-section oc-container">
        <h2 class="oc-section-heading">Other Offers</h2>
        <div class="oc-related-grid">
            <?php while ( $other_offers->have_posts() ) : $other_offers->the_post();
                $rel_id       = get_the_ID();
                $rel_thumb    = get_the_post_thumbnail_url( $rel_id, 'medium_large' ) ?: $hero_img;
                $rel_discount = get_post_meta( $rel_id, '_oc_discount', true );
                $rel_badge    = get_post_meta( $rel_id, '_oc_badge_text', true );
            ?>
                <a href="<?php echo esc_url( get_permalink( $rel_id ) ); ?>" class="oc-related-card">
                    <div class="oc-related-card__img" style="background-image:url('<?php echo esc_url( $rel_thumb ); ?>');">
                        <?php if ( $rel_badge ) : ?>
                            <span class="oc-related-card__badge"><?php echo esc_html( $rel_badge ); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="oc-related-card__body">
                        <h3 class="oc-related-card__title"><?php the_title(); ?></h3>
                        <?php if ( $rel_discount ) : ?>
                            <span class="oc-related-card__price"><?php echo esc_html( $rel_discount ); ?></span>
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
                    <h2>Don&rsquo;t Miss <span class="text-primary">This Deal</span></h2>
                    <p>Exclusive offers are available for a limited time only.</p>
                </div>
                <div class="oc-cta-strip__actions">
                    <a href="#oc-offer-inquiry" class="btn-primary">Book Now</a>
                    <a href="<?php echo esc_url( $wa_url ); ?>" class="btn-secondary" target="_blank" rel="noopener noreferrer">WhatsApp Us</a>
                </div>
            </div>
        </div>
    </section>

    <?php endif; // end elementor check ?>
<?php endif; // end have_posts ?>

</main>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">

<style>
/* ── Ocean Charter: Single Offer ──────────────────────────────────────────── */

.oc-single-offer {
    background: #0a0f1a;
    color: var(--text, #f0ece3);
}

/* Hero */
.oc-offer-hero {
    position: relative;
    min-height: 50vh;
    background-size: cover;
    background-position: center;
    display: flex;
    align-items: flex-end;
    padding-bottom: 56px;
}
.oc-offer-hero__overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(10,15,26,0.95) 0%, rgba(10,15,26,0.35) 55%, rgba(10,15,26,0.1) 100%);
}
.oc-offer-hero__content {
    position: relative;
    z-index: 2;
    max-width: 900px;
}
.oc-offer-badge {
    display: inline-block;
    background: var(--primary, #d9b230);
    color: #0a0f1a;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    padding: 5px 14px;
    border-radius: 999px;
    margin-bottom: 20px;
}
.oc-offer-hero__title {
    font-family: var(--font-heading);
    font-size: clamp(32px, 4.5vw, 60px);
    font-weight: 400;
    color: var(--text-light, #f8fafc);
    line-height: 1.1;
    margin: 0 0 20px;
}
.oc-offer-discount-callout {
    font-size: clamp(32px, 5vw, 64px);
    font-weight: 900;
    color: var(--primary, #d9b230);
    line-height: 1;
    letter-spacing: -0.02em;
}

/* Body layout */
.oc-offer-body {
    display: grid;
    grid-template-columns: 1fr 420px;
    gap: 60px;
    padding-top: 64px;
    padding-bottom: 100px;
    align-items: start;
}
.oc-offer-section {
    margin-bottom: 48px;
}
.oc-section-heading {
    font-family: var(--font-heading);
    font-size: 28px;
    font-weight: 400;
    color: var(--text-light, #f8fafc);
    margin: 0 0 24px;
    padding-bottom: 14px;
    border-bottom: 1px solid var(--border, rgba(255,255,255,0.08));
}
.oc-offer-subtitle {
    font-family: var(--font-heading);
    font-size: 22px;
    font-style: italic;
    color: var(--primary, #d9b230);
    margin-bottom: 24px;
    line-height: 1.4;
}
.oc-offer-desc {
    font-size: 17px;
    line-height: 1.8;
    color: var(--text-muted, rgba(240,236,227,0.7));
}
.oc-offer-prose {
    font-size: 16px;
    line-height: 1.8;
    color: var(--text-muted, rgba(240,236,227,0.7));
}
.oc-offer-prose p { margin: 0 0 16px; }
.oc-offer-terms {
    font-size: 14px;
    opacity: 0.8;
}
.oc-offer-validity {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 16px;
    color: var(--text-muted, rgba(240,236,227,0.7));
    background: var(--surface, #111a28);
    border: 1px solid var(--border, rgba(255,255,255,0.08));
    border-radius: 0.5rem;
    padding: 14px 18px;
}
.oc-offer-validity strong { color: var(--text, #f0ece3); }
.oc-offer-type-label {
    display: inline-block;
    background: rgba(217,178,48,0.12);
    border: 1px solid rgba(217,178,48,0.35);
    color: var(--primary, #d9b230);
    font-size: 14px;
    font-weight: 600;
    padding: 7px 16px;
    border-radius: 999px;
    letter-spacing: 0.04em;
}

/* BBC form override */
.oc-offer-bbc-form-wrap .bbc-booking-form,
.oc-offer-bbc-form-wrap .bbc-pkg-booking-widget {
    background: var(--surface, #111a28) !important;
    border: 1px solid var(--border, rgba(255,255,255,0.08)) !important;
    border-radius: 12px !important;
    padding: 24px !important;
    color: #f0ece3 !important;
}
.oc-offer-bbc-form-wrap label { color: rgba(240,236,227,0.7) !important; }
.oc-offer-bbc-form-wrap input,
.oc-offer-bbc-form-wrap select,
.oc-offer-bbc-form-wrap textarea {
    background: rgba(255,255,255,0.05) !important;
    border: 1px solid rgba(255,255,255,0.12) !important;
    color: #f0ece3 !important;
    border-radius: 8px !important;
}
.oc-offer-bbc-form-wrap button[type="submit"],
.oc-offer-bbc-form-wrap .bbc-btn-primary {
    background: #d9b230 !important;
    color: #0a0f1a !important;
    border: none !important;
    font-weight: 700 !important;
}

/* Sidebar */
.oc-offer-sidebar {
    position: sticky;
    top: 100px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}
.oc-booking-card {
    background: var(--surface, #111a28);
    border: 1px solid var(--border, rgba(255,255,255,0.08));
    border-radius: 1rem;
    padding: 1.5rem;
}
.oc-booking-card__title {
    font-family: var(--font-heading);
    font-size: 18px;
    font-weight: 400;
    color: var(--text-light, #f8fafc);
    margin: 0 0 9px;
    padding-bottom: 9px;
    border-bottom: 1px solid var(--border, rgba(255,255,255,0.08));
    text-align: center;
}
.oc-offer-card-discount {
    font-size: 48px;
    font-weight: 900;
    color: var(--primary, #d9b230);
    text-align: center;
    line-height: 1;
    margin-bottom: 12px;
}
.oc-offer-card-valid {
    text-align: center;
    font-size: 14px;
    color: var(--text-muted, rgba(240,236,227,0.7));
    margin: 0 0 8px;
}
.oc-offer-card-valid strong { color: var(--text, #f0ece3); }
.oc-booking-card__note {
    text-align: center;
    font-size: 12px;
    color: var(--text-muted, rgba(240,236,227,0.5));
    margin: 10px 0 0;
    font-style: italic;
}

/* Sidebar form */
.oc-sidebar-form-card {
    background: var(--surface, #111a28);
    border: 1px solid var(--border, rgba(255,255,255,0.08));
    border-radius: 1rem;
    padding: 1.5rem;
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
.oc-required { color: #d9b230; }
.oc-sidebar-form__field input,
.oc-sidebar-form__field textarea {
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
.oc-sidebar-form__field input:focus,
.oc-sidebar-form__field textarea:focus {
    outline: none;
    border-color: #d9b230;
    box-shadow: 0 0 0 2px rgba(217,178,48,0.15);
}
.oc-sidebar-form__field input::placeholder,
.oc-sidebar-form__field textarea::placeholder {
    color: rgba(240,236,227,0.3);
}
/* 2-column form grid */
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
/* flatpickr dark overrides */
.flatpickr-calendar { background: #111a28 !important; border: 1px solid rgba(217,178,48,0.3) !important; box-shadow: 0 8px 32px rgba(0,0,0,0.5) !important; border-radius: 12px !important; }
.flatpickr-months .flatpickr-month, .flatpickr-current-month .flatpickr-monthDropdown-months { background: #111a28 !important; color: #f0ece3 !important; }
.flatpickr-current-month input.cur-year { color: #f0ece3 !important; }
.flatpickr-day { color: #f0ece3 !important; border-radius: 6px !important; }
.flatpickr-day:hover { background: rgba(217,178,48,0.2) !important; border-color: transparent !important; }
.flatpickr-day.selected { background: #d9b230 !important; color: #0a0f1a !important; border-color: #d9b230 !important; }
.flatpickr-day.today { border-color: #d9b230 !important; }
.flatpickr-day.flatpickr-disabled { color: rgba(240,236,227,0.15) !important; }
span.flatpickr-weekday { color: #d9b230 !important; }
.flatpickr-months .flatpickr-prev-month, .flatpickr-months .flatpickr-next-month { fill: #d9b230 !important; color: #d9b230 !important; }

/* Buttons */
.oc-btn-gold {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: var(--primary, #d9b230);
    color: #0a0f1a;
    font-weight: 700;
    font-size: 14px;
    padding: 12px 20px;
    border-radius: 0.5rem;
    text-decoration: none;
    transition: background 0.2s, transform 0.15s;
    margin-bottom: 10px;
    border: none;
    cursor: pointer;
    font-family: inherit;
}
.oc-btn-gold:hover { background: var(--primary-hover, #f1c944); transform: translateY(-1px); color: #0a0f1a; }
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

/* Inquiry status */
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
    border: 1px solid var(--border, rgba(255,255,255,0.08));
    border-radius: 0.75rem;
    overflow: hidden;
    transition: border-color 0.2s, transform 0.2s;
}
.oc-related-card:hover { border-color: var(--primary, #d9b230); transform: translateY(-3px); }
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
    background: var(--primary, #d9b230);
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
.oc-related-card__price {
    font-size: 15px;
    font-weight: 700;
    color: var(--primary, #d9b230);
}

/* CTA strip */
.oc-cta-strip {
    background: var(--surface, #111a28);
    border-top: 1px solid var(--border, rgba(255,255,255,0.08));
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
.oc-cta-strip__text p { font-size: 17px; color: var(--text-muted, rgba(240,236,227,0.7)); margin: 0; }
.oc-cta-strip__actions { display: flex; gap: 16px; flex-wrap: wrap; }

@media (max-width: 1024px) {
    .oc-offer-body { grid-template-columns: 1fr; gap: 40px; }
    .oc-offer-sidebar { position: static; order: -1; }
}
@media (max-width: 768px) {
    .oc-related-grid { grid-template-columns: 1fr; }
    .oc-cta-strip__inner { flex-direction: column; text-align: center; }
    .oc-cta-strip__actions { justify-content: center; }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
(function() {
    var form = document.getElementById('oc-offer-inquiry-form');
    if (!form) return;

    var startEl = document.getElementById('oc-offer-start');
    var endEl = document.getElementById('oc-offer-end');

    var endPicker = endEl ? flatpickr(endEl, {
        dateFormat: 'Y-m-d',
        minDate: 'today',
        disableMobile: true,
        theme: 'dark',
    }) : null;

    if (startEl) {
        flatpickr(startEl, {
            dateFormat: 'Y-m-d',
            minDate: 'today',
            disableMobile: true,
            theme: 'dark',
            onChange: function(selectedDates) {
                if (selectedDates.length && endPicker) {
                    endPicker.set('minDate', selectedDates[0]);
                }
            },
        });
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        var btn = document.getElementById('oc-inq-submit');
        var status = document.getElementById('oc-inquiry-status');
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
})();
</script>

<?php get_footer(); ?>
