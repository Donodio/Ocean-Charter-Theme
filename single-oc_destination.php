<?php
/**
 * Single Destination Template
 *
 * @package OceanCharter
 */

get_header();
?>

<main id="primary" class="site-main oc-single-destination">

<?php if ( have_posts() ) : the_post();

    $post_id = get_the_ID();

    /* ── Elementor builder pass-through ── */
    $elementor_mode = get_post_meta( $post_id, '_elementor_edit_mode', true );
    if ( 'builder' === $elementor_mode ) :
        the_content();
    else :

    /* ── Meta ── */
    $hero_img      = get_the_post_thumbnail_url( $post_id, 'full' ) ?: 'https://images.pexels.com/photos/1320684/pexels-photo-1320684.jpeg?auto=compress&cs=tinysrgb&w=1920';
    $vessel_count  = get_post_meta( $post_id, '_oc_vessel_count', true );
    $is_popular    = get_post_meta( $post_id, '_oc_is_popular', true );
    $wa_number     = get_post_meta( $post_id, '_oc_whatsapp', true );
    $additional    = get_post_meta( $post_id, '_oc_additional_content', true );

    $regions      = wp_get_post_terms( $post_id, 'oc_destination_region', [ 'fields' => 'names' ] );
    $region_label = ( ! is_wp_error( $regions ) && ! empty( $regions ) ) ? $regions[0] : '';

    $wa_url = $wa_number
        ? 'https://wa.me/' . preg_replace( '/[^0-9]/', '', $wa_number ) . '?text=' . rawurlencode( 'Hello, I\'d like to enquire about ' . get_the_title() . '.' )
        : ( function_exists( 'oc_whatsapp_url' ) ? oc_whatsapp_url( 'Hello, I\'d like to enquire about ' . get_the_title() . '.' ) : 'https://wa.me/' . get_theme_mod( 'oc_whatsapp_number', '15551234567' ) );

    // Gallery images
    $gallery = [];
    for ( $gi = 1; $gi <= 4; $gi++ ) {
        $gurl = get_post_meta( $post_id, '_oc_dest_gallery_' . $gi, true );
        if ( $gurl ) $gallery[] = $gurl;
    }
    ?>

    <!-- ══ HERO ══════════════════════════════════════════════════════════════ -->
    <?php $_hero_pos = get_post_meta( $post_id, '_oc_hero_position', true ) ?: 'center center'; ?>
    <section class="oc-dest-hero" style="background-image:url('<?php echo esc_url( $hero_img ); ?>');background-size:cover;background-position:<?php echo esc_attr( $_hero_pos ); ?>;">
        <div class="oc-dest-hero__overlay"></div>
        <div class="oc-dest-hero__content oc-container">
            <?php if ( $region_label ) : ?>
                <span class="oc-dest-badge"><?php echo esc_html( $region_label ); ?></span>
            <?php endif; ?>
            <h1 class="oc-dest-hero__title"><?php the_title(); ?></h1>
            <div class="oc-dest-hero__pills">
                <?php if ( $vessel_count ) : ?>
                    <span class="oc-spec-pill">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                        <?php echo esc_html( $vessel_count ); ?> Vessels Available
                    </span>
                <?php endif; ?>
                <?php if ( $is_popular ) : ?>
                    <span class="oc-spec-pill oc-spec-pill--gold">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="var(--primary)" stroke="var(--primary)" stroke-width="1" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        Popular
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- ══ BODY ══════════════════════════════════════════════════════════════ -->
    <div class="oc-dest-body oc-container">

        <!-- Left column -->
        <div class="oc-dest-main">

            <!-- Description -->
            <?php $excerpt = get_the_excerpt(); if ( $excerpt ) : ?>
            <section class="oc-dest-section">
                <h2 class="oc-section-heading">About <?php the_title(); ?></h2>
                <p class="oc-dest-desc"><?php echo wp_kses_post( $excerpt ); ?></p>
            </section>
            <?php endif; ?>

            <!-- Image Gallery -->
            <?php if ( ! empty( $gallery ) ) : ?>
            <section class="oc-dest-section">
                <h2 class="oc-section-heading">Gallery</h2>
                <div class="oc-dest-gallery oc-dest-gallery--<?php echo count( $gallery ); ?>">
                    <?php foreach ( $gallery as $idx => $gimg ) : ?>
                        <div class="oc-dest-gallery__item" style="background-image:url('<?php echo esc_url( $gimg ); ?>');" role="img" aria-label="<?php echo esc_attr( get_the_title() . ' gallery image ' . ( $idx + 1 ) ); ?>"></div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

            <!-- Additional Content -->
            <?php if ( $additional ) : ?>
            <section class="oc-dest-section">
                <div class="oc-dest-additional"><?php echo wp_kses_post( wpautop( $additional ) ); ?></div>
            </section>
            <?php endif; ?>

            <!-- Full content (Elementor / WP editor) -->
            <?php $content = get_the_content(); if ( trim( $content ) ) : ?>
            <section class="oc-dest-section">
                <div class="oc-dest-prose"><?php the_content(); ?></div>
            </section>
            <?php endif; ?>

            <!-- Available Itineraries (manually linked via meta) -->
            <?php
            $linked_itin_ids = get_post_meta( $post_id, '_oc_linked_itineraries', true );
            $linked_itin_ids = is_array( $linked_itin_ids ) ? array_filter( $linked_itin_ids ) : [];
            $itinerary_q_args = [
                'post_type'      => 'oc_itinerary',
                'posts_per_page' => 4,
                'orderby'        => 'title',
                'order'          => 'ASC',
            ];
            if ( ! empty( $linked_itin_ids ) ) {
                $itinerary_q_args['post__in'] = $linked_itin_ids;
                $itinerary_q_args['orderby']  = 'post__in';
            }
            $itinerary_q = new WP_Query( $itinerary_q_args );
            if ( $itinerary_q->have_posts() ) : ?>
            <section class="oc-dest-section">
                <h2 class="oc-section-heading">Available Itineraries</h2>
                <div class="oc-dest-itineraries">
                    <?php while ( $itinerary_q->have_posts() ) : $itinerary_q->the_post();
                        $itin_id    = get_the_ID();
                        $itin_thumb = get_the_post_thumbnail_url( $itin_id, 'thumbnail' ) ?: 'https://images.pexels.com/photos/1001682/pexels-photo-1001682.jpeg?auto=compress&cs=tinysrgb&w=400';
                        $itin_dur   = get_post_meta( $itin_id, '_oc_duration', true );
                    ?>
                        <a href="<?php echo esc_url( get_permalink( $itin_id ) ); ?>" class="oc-itin-card">
                            <div class="oc-itin-card__img" style="background-image:url('<?php echo esc_url( $itin_thumb ); ?>');" aria-hidden="true"></div>
                            <div class="oc-itin-card__body">
                                <h3 class="oc-itin-card__title"><?php the_title(); ?></h3>
                                <?php if ( $itin_dur ) : ?>
                                    <span class="oc-itin-card__dur"><?php echo esc_html( $itin_dur ); ?></span>
                                <?php endif; ?>
                            </div>
                            <span class="oc-itin-card__link">View &rarr;</span>
                        </a>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            </section>
            <?php endif; ?>

            <!-- Available Vessels (BBC boat CPT — same as Fleet page) -->
            <?php
            $vessel_q = new WP_Query( [
                'post_type'      => 'boat',
                'posts_per_page' => 3,
                'orderby'        => 'rand',
            ] );
            if ( $vessel_q->have_posts() ) : ?>
            <section class="oc-dest-section">
                <h2 class="oc-section-heading">Available Vessels</h2>
                <div class="oc-dest-vessels">
                    <?php while ( $vessel_q->have_posts() ) : $vessel_q->the_post();
                        $v_id    = get_the_ID();
                        $v_thumb = get_the_post_thumbnail_url( $v_id, 'medium' ) ?: 'https://images.pexels.com/photos/1531660/pexels-photo-1531660.jpeg?auto=compress&cs=tinysrgb&w=600';
                        $v_price = get_post_meta( $v_id, '_bbc_price_day', true );
                        $v_type  = get_post_meta( $v_id, '_bbc_boat_type', true );
                        $v_type  = $v_type ? ucfirst( str_replace( '_', ' ', $v_type ) ) : '';
                    ?>
                        <a href="<?php echo esc_url( get_permalink( $v_id ) ); ?>" class="oc-dest-vessel-card">
                            <div class="oc-dest-vessel-card__img" style="background-image:url('<?php echo esc_url( $v_thumb ); ?>');" aria-hidden="true">
                                <?php if ( $v_type ) : ?>
                                    <span class="oc-related-card__badge"><?php echo esc_html( $v_type ); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="oc-dest-vessel-card__body">
                                <h3><?php the_title(); ?></h3>
                                <?php if ( $v_price ) : ?>
                                    <span class="oc-related-card__price">From $<?php echo esc_html( number_format( (float) $v_price ) ); ?>/day</span>
                                <?php endif; ?>
                            </div>
                        </a>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            </section>
            <?php endif; ?>

        </div><!-- /.oc-dest-main -->

        <!-- Sticky sidebar with inquiry form -->
        <aside class="oc-dest-sidebar">

            <!-- Inquiry Form -->
            <div class="oc-sidebar-form-card" id="inquiry">
                <h3 class="oc-booking-card__title">Enquire About <?php the_title(); ?></h3>

                <form id="oc-inquiry-form" class="oc-sidebar-form" novalidate>
                    <input type="hidden" name="action" value="oc_destination_inquiry">
                    <input type="hidden" name="destination_id" value="<?php echo esc_attr( $post_id ); ?>">
                    <input type="hidden" name="destination_title" value="<?php echo esc_attr( get_the_title( $post_id ) ); ?>">
                    <?php wp_nonce_field( 'oc_destination_inquiry', 'oc_inquiry_nonce' ); ?>

                    <div class="oc-sidebar-form__field">
                        <label for="oc-inq-name">Full Name <span class="oc-required">*</span></label>
                        <input type="text" id="oc-inq-name" name="guest_name" required placeholder="Your full name" autocomplete="name">
                        <span class="oc-field-error" id="oc-inq-name-err" style="display:none;color:#fc8181;font-size:12px;margin-top:4px;"></span>
                    </div>
                    <div class="oc-form-row--2col">
                        <div class="oc-sidebar-form__field">
                            <label for="oc-inq-email">Email <span class="oc-required">*</span></label>
                            <input type="email" id="oc-inq-email" name="guest_email" required placeholder="you@example.com" autocomplete="email">
                            <span class="oc-field-error" id="oc-inq-email-err" style="display:none;color:#fc8181;font-size:12px;margin-top:4px;"></span>
                        </div>
                        <div class="oc-sidebar-form__field">
                            <label for="oc-inq-phone">Phone</label>
                            <input type="tel" id="oc-inq-phone" name="guest_phone" placeholder="+1 (555) 000-0000" autocomplete="tel">
                            <span class="oc-field-error" id="oc-inq-phone-err" style="display:none;color:#fc8181;font-size:12px;margin-top:4px;"></span>
                        </div>
                    </div>
                    <div class="oc-sidebar-form__field">
                        <label for="oc-inq-message">Message</label>
                        <textarea id="oc-inq-message" name="guest_message" rows="3" placeholder="Tell us about your dream charter..."></textarea>
                    </div>

                    <button type="submit" class="oc-btn-gold oc-btn-full oc-btn-submit" id="oc-inq-submit">Send Inquiry</button>
                    <div id="oc-inquiry-msg" class="oc-inquiry-msg" style="display:none;"></div>
                </form>

                <a href="<?php echo esc_url( $wa_url ); ?>" class="oc-btn-whatsapp oc-btn-full" target="_blank" rel="noopener noreferrer">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    WhatsApp Us
                </a>
            </div>

            <!-- Info card -->
            <?php if ( $vessel_count || $is_popular ) : ?>
            <div class="oc-booking-card">
                <h3 class="oc-booking-card__title">Destination Info</h3>
                <?php if ( $vessel_count ) : ?>
                    <div class="oc-dest-card-count">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" aria-hidden="true"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                        <span><strong><?php echo esc_html( $vessel_count ); ?></strong> vessels available</span>
                    </div>
                <?php endif; ?>
                <?php if ( $is_popular ) : ?>
                    <div class="oc-dest-popular-badge">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="var(--primary)" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        Popular Destination
                    </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

        </aside>

    </div><!-- /.oc-dest-body -->

    <!-- ══ OTHER DESTINATIONS ════════════════════════════════════════════════ -->
    <?php
    $other_dests = new WP_Query( [
        'post_type'      => 'oc_destination',
        'posts_per_page' => 3,
        'post__not_in'   => [ $post_id ],
        'orderby'        => 'rand',
    ] );
    if ( $other_dests->have_posts() ) : ?>
    <section class="oc-related-section oc-container">
        <h2 class="oc-section-heading">Other Destinations</h2>
        <div class="oc-related-grid">
            <?php while ( $other_dests->have_posts() ) : $other_dests->the_post();
                $d_id    = get_the_ID();
                $d_thumb = get_the_post_thumbnail_url( $d_id, 'medium_large' ) ?: $hero_img;
                $d_regs  = wp_get_post_terms( $d_id, 'oc_destination_region', [ 'fields' => 'names' ] );
                $d_reg   = ( ! is_wp_error( $d_regs ) && ! empty( $d_regs ) ) ? $d_regs[0] : '';
            ?>
                <a href="<?php echo esc_url( get_permalink( $d_id ) ); ?>" class="oc-related-card oc-related-card--portrait">
                    <div class="oc-related-card__img" style="background-image:url('<?php echo esc_url( $d_thumb ); ?>');">
                        <?php if ( $d_reg ) : ?>
                            <span class="oc-related-card__badge"><?php echo esc_html( $d_reg ); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="oc-related-card__body">
                        <h3 class="oc-related-card__title"><?php the_title(); ?></h3>
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
                    <h2>Explore <span class="text-primary"><?php the_title(); ?></span></h2>
                    <p>Start planning your dream charter experience today.</p>
                </div>
                <div class="oc-cta-strip__actions">
                    <a href="#inquiry" class="btn-primary">Enquire Now</a>
                    <a href="<?php echo esc_url( $wa_url ); ?>" class="btn-secondary" target="_blank" rel="noopener noreferrer">WhatsApp Us</a>
                </div>
            </div>
        </div>
    </section>

    <script>
    (function(){
        // ── Validation helpers ──
        function ocValEmail(v) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v.trim()); }
        function ocValPhone(v) { return v.trim() === '' || /^[+]?[\d\s\-(). ]{7,20}$/.test(v.trim()); }
        function showErr(id, msg) {
            var el = document.getElementById(id);
            if (el) { el.textContent = msg; el.style.display = msg ? 'block' : 'none'; }
        }

        // Real-time validation
        var emailInp = document.getElementById('oc-inq-email');
        if (emailInp) emailInp.addEventListener('blur', function() {
            showErr('oc-inq-email-err', ocValEmail(this.value) ? '' : 'Please enter a valid email.');
        });
        var phoneInp = document.getElementById('oc-inq-phone');
        if (phoneInp) phoneInp.addEventListener('blur', function() {
            showErr('oc-inq-phone-err', ocValPhone(this.value) ? '' : 'Please enter a valid phone number.');
        });
        var nameInp = document.getElementById('oc-inq-name');
        if (nameInp) nameInp.addEventListener('blur', function() {
            showErr('oc-inq-name-err', this.value.trim() ? '' : 'Full name is required.');
        });

        var form = document.getElementById('oc-inquiry-form');
        if (!form) return;
        form.addEventListener('submit', function(e){
            e.preventDefault();

            // Client-side validation before submit
            var valid = true;
            if (nameInp && !nameInp.value.trim()) { showErr('oc-inq-name-err', 'Full name is required.'); valid = false; }
            if (emailInp && !ocValEmail(emailInp.value)) { showErr('oc-inq-email-err', 'Please enter a valid email.'); valid = false; }
            if (phoneInp && !ocValPhone(phoneInp.value)) { showErr('oc-inq-phone-err', 'Please enter a valid phone number.'); valid = false; }
            if (!valid) return;

            var btn = document.getElementById('oc-inq-submit');
            var msgEl = document.getElementById('oc-inquiry-msg');
            btn.disabled = true;
            btn.textContent = 'Sending...';
            msgEl.style.display = 'none';
            msgEl.className = 'oc-inquiry-msg';

            var fd = new FormData(form);
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>');
            xhr.onload = function(){
                btn.disabled = false;
                btn.textContent = 'Send Inquiry';
                try {
                    var res = JSON.parse(xhr.responseText);
                    msgEl.style.display = 'block';
                    if (res.success) {
                        msgEl.className = 'oc-inquiry-msg oc-inquiry-msg--success';
                        msgEl.textContent = res.data.message || 'Thank you! We\'ll be in touch soon.';
                        form.reset();
                    } else {
                        msgEl.className = 'oc-inquiry-msg oc-inquiry-msg--error';
                        msgEl.textContent = res.data.message || 'Something went wrong. Please try again.';
                    }
                } catch(err) {
                    msgEl.style.display = 'block';
                    msgEl.className = 'oc-inquiry-msg oc-inquiry-msg--error';
                    msgEl.textContent = 'Something went wrong. Please try again.';
                }
            };
            xhr.onerror = function(){
                btn.disabled = false;
                btn.textContent = 'Send Inquiry';
                msgEl.style.display = 'block';
                msgEl.className = 'oc-inquiry-msg oc-inquiry-msg--error';
                msgEl.textContent = 'Network error. Please check your connection and try again.';
            };
            xhr.send(fd);
        });
    })();
    </script>

    <?php endif; // end elementor check ?>
<?php endif; // end have_posts ?>

</main>

<style>
/* ── Ocean Charter: Single Destination ───────────────────────────────────── */

.oc-single-destination {
    background: #0a0f1a;
    color: var(--text);
}

/* Hero — full-width */
.oc-dest-hero {
    position: relative;
    min-height: 65vh;
    background-size: cover;
    background-position: center;
    display: flex;
    align-items: flex-end;
    padding-bottom: 60px;
}
.oc-dest-hero__overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(10,15,26,0.95) 0%, rgba(10,15,26,0.35) 55%, rgba(10,15,26,0.05) 100%);
}
.oc-dest-hero__content {
    position: relative;
    z-index: 2;
    /* full-width — no max-width constraint */
}
.oc-dest-badge {
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
.oc-dest-hero__title {
    font-family: var(--font-heading);
    font-size: clamp(36px, 5vw, 70px);
    font-weight: 400;
    color: var(--text-light, #f8fafc);
    line-height: 1.05;
    margin: 0 0 24px;
}
.oc-dest-hero__pills {
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
.oc-spec-pill--gold {
    background: rgba(217,178,48,0.15);
    border-color: rgba(217,178,48,0.5);
    color: var(--primary);
}

/* Body layout */
.oc-dest-body {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 60px;
    padding-top: 64px;
    padding-bottom: 100px;
    align-items: start;
}
.oc-dest-section {
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
.oc-dest-desc {
    font-size: 17px;
    line-height: 1.8;
    color: var(--text-muted);
}
.oc-dest-additional {
    font-size: 16px;
    line-height: 1.8;
    color: var(--text-muted);
}
.oc-dest-additional p { margin: 0 0 16px; }
.oc-dest-prose {
    font-size: 16px;
    line-height: 1.8;
    color: var(--text-muted);
}
.oc-dest-prose p { margin: 0 0 16px; }

/* Gallery */
.oc-dest-gallery {
    display: grid;
    gap: 12px;
    border-radius: 0.75rem;
    overflow: hidden;
}
.oc-dest-gallery--1 { grid-template-columns: 1fr; }
.oc-dest-gallery--2 { grid-template-columns: 1fr 1fr; }
.oc-dest-gallery--3 {
    grid-template-columns: 1fr 1fr;
    grid-template-rows: auto auto;
}
.oc-dest-gallery--3 .oc-dest-gallery__item:first-child {
    grid-column: 1 / -1;
}
.oc-dest-gallery--4 { grid-template-columns: 1fr 1fr; }
.oc-dest-gallery__item {
    background-size: cover;
    background-position: center;
    border-radius: 0.5rem;
    min-height: 220px;
    transition: transform 0.3s;
}
.oc-dest-gallery__item:hover { transform: scale(1.02); }

/* Itinerary cards */
.oc-dest-itineraries {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.oc-itin-card {
    display: grid;
    grid-template-columns: 80px 1fr auto;
    align-items: center;
    gap: 16px;
    background: var(--surface, #111a28);
    border: 1px solid var(--border);
    border-radius: 0.75rem;
    overflow: hidden;
    text-decoration: none;
    color: inherit;
    transition: border-color 0.2s, transform 0.15s;
    padding-right: 16px;
}
.oc-itin-card:hover { border-color: var(--primary); transform: translateX(4px); }
.oc-itin-card__img {
    width: 80px;
    height: 80px;
    background-size: cover;
    background-position: center;
    flex-shrink: 0;
}
.oc-itin-card__body { padding: 12px 0; }
.oc-itin-card__title {
    font-family: var(--font-heading);
    font-size: 16px;
    font-weight: 400;
    color: var(--text-light, #f8fafc);
    margin: 0 0 4px;
}
.oc-itin-card__dur {
    font-size: 13px;
    color: var(--text-muted);
}
.oc-itin-card__link {
    font-size: 14px;
    color: var(--primary);
    flex-shrink: 0;
}

/* Vessel cards */
.oc-dest-vessels {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
}
.oc-dest-vessel-card {
    text-decoration: none;
    color: inherit;
    display: block;
    background: var(--surface, #111a28);
    border: 1px solid var(--border);
    border-radius: 0.75rem;
    overflow: hidden;
    transition: border-color 0.2s, transform 0.2s;
}
.oc-dest-vessel-card:hover { border-color: var(--primary); transform: translateY(-3px); }
.oc-dest-vessel-card__img {
    height: 140px;
    background-size: cover;
    background-position: center;
    position: relative;
}
.oc-dest-vessel-card__body {
    padding: 12px;
}
.oc-dest-vessel-card__body h3 {
    font-family: var(--font-heading);
    font-size: 15px;
    font-weight: 400;
    color: var(--text-light, #f8fafc);
    margin: 0 0 4px;
}
.oc-related-card__badge {
    position: absolute;
    top: 8px;
    left: 8px;
    background: var(--primary);
    color: #0a0f1a;
    font-size: 9px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    padding: 3px 8px;
    border-radius: 999px;
}
.oc-related-card__price {
    font-size: 13px;
    color: var(--primary);
}

/* Sidebar */
.oc-dest-sidebar {
    position: sticky;
    top: 100px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

/* Sidebar inquiry form card */
.oc-sidebar-form-card {
    background: var(--surface, #111a28);
    border: 1px solid var(--border);
    border-radius: 1rem;
    padding: 1.5rem;
}
.oc-sidebar-form {
    display: flex;
    flex-direction: column;
    gap: 14px;
    margin-bottom: 14px;
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
.oc-required { color: var(--primary, #d9b230); }
.oc-sidebar-form__field input,
.oc-sidebar-form__field textarea {
    width: 100%;
    background: rgba(10,15,26,0.6);
    border: 1px solid var(--border, rgba(217,178,48,0.2));
    border-radius: 0.5rem;
    padding: 10px 12px;
    font-size: 14px;
    color: var(--text, #f0ece3);
    font-family: inherit;
    transition: border-color 0.2s;
    box-sizing: border-box;
}
.oc-sidebar-form__field input::placeholder,
.oc-sidebar-form__field textarea::placeholder {
    color: var(--text-muted, #8a9bb5);
    opacity: 0.7;
}
.oc-sidebar-form__field input:focus,
.oc-sidebar-form__field textarea:focus {
    outline: none;
    border-color: var(--primary, #d9b230);
    box-shadow: 0 0 0 2px rgba(217,178,48,0.15);
}

/* Info card */
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
.oc-dest-card-count {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 15px;
    color: var(--text-muted);
    margin-bottom: 16px;
}
.oc-dest-card-count strong { color: var(--text); }
.oc-dest-popular-badge {
    display: flex;
    align-items: center;
    gap: 6px;
    background: rgba(217,178,48,0.12);
    border: 1px solid rgba(217,178,48,0.3);
    color: var(--primary);
    font-size: 13px;
    font-weight: 600;
    padding: 7px 12px;
    border-radius: 0.5rem;
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
    cursor: not-allowed;
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

/* Inquiry message */
.oc-inquiry-msg {
    margin-top: 12px;
    padding: 12px 14px;
    border-radius: 0.5rem;
    font-size: 14px;
    line-height: 1.4;
}
.oc-inquiry-msg--success {
    background: rgba(37,211,102,0.12);
    border: 1px solid rgba(37,211,102,0.3);
    color: #25d366;
}
.oc-inquiry-msg--error {
    background: rgba(239,68,68,0.12);
    border: 1px solid rgba(239,68,68,0.3);
    color: #ef4444;
}

/* Related destinations */
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
.oc-related-card--portrait .oc-related-card__img { height: 220px; }
.oc-related-card__body { padding: 16px; }
.oc-related-card__title {
    font-family: var(--font-heading);
    font-size: 17px;
    font-weight: 400;
    color: var(--text-light, #f8fafc);
    margin: 0;
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

@media (max-width: 1024px) {
    .oc-dest-body { grid-template-columns: 1fr; gap: 40px; }
    .oc-dest-sidebar { position: static; order: -1; }
    .oc-dest-vessels { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 768px) {
    .oc-related-grid { grid-template-columns: 1fr; }
    .oc-dest-vessels { grid-template-columns: 1fr; }
    .oc-dest-gallery--2,
    .oc-dest-gallery--3,
    .oc-dest-gallery--4 { grid-template-columns: 1fr; }
    .oc-dest-gallery--3 .oc-dest-gallery__item:first-child { grid-column: auto; }
    .oc-cta-strip__inner { flex-direction: column; text-align: center; }
    .oc-cta-strip__actions { justify-content: center; }
}
</style>

<?php get_footer(); ?>
