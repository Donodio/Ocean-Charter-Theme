<?php
/**
 * Single Team Member Template
 *
 * @package OceanCharter
 */

get_header();
?>

<main id="primary" class="site-main oc-single-team-member">

<?php if ( have_posts() ) : the_post();

    $post_id = get_the_ID();

    /* ── Elementor builder pass-through ── */
    $elementor_mode = get_post_meta( $post_id, '_elementor_edit_mode', true );
    if ( 'builder' === $elementor_mode ) :
        the_content();
    else :

    /* ── Meta ── */
    $photo         = get_the_post_thumbnail_url( $post_id, 'large' ) ?: 'https://images.pexels.com/photos/1043471/pexels-photo-1043471.jpeg?auto=compress&cs=tinysrgb&w=600';
    $role_title    = get_post_meta( $post_id, '_oc_role_title', true );
    $years_exp     = get_post_meta( $post_id, '_oc_years_exp', true );
    $bio           = get_post_meta( $post_id, '_oc_bio', true );
    $vessel_id     = (int) get_post_meta( $post_id, '_oc_vessel_id', true );
    $languages_raw = get_post_meta( $post_id, '_oc_languages', true );
    $certs_raw     = get_post_meta( $post_id, '_oc_certifications', true );

    $languages = $languages_raw ? json_decode( $languages_raw, true ) : [];
    if ( ! is_array( $languages ) ) {
        // fallback: might be comma-separated string
        $languages = array_map( 'trim', explode( ',', $languages_raw ) );
    }
    $certs = $certs_raw ? json_decode( $certs_raw, true ) : [];

    $tags_raw    = get_post_meta( $post_id, '_oc_tags', true );
    $tags        = $tags_raw ? array_filter( array_map( 'trim', explode( ',', $tags_raw ) ) ) : [];
    $card_bg     = get_post_meta( $post_id, '_oc_card_bg_color', true );

    $team_roles  = wp_get_post_terms( $post_id, 'oc_team_role', [ 'fields' => 'names' ] );
    $role_term   = ( ! is_wp_error( $team_roles ) && ! empty( $team_roles ) ) ? $team_roles[0] : '';

    $vessel_title = $vessel_id ? get_the_title( $vessel_id ) : '';
    $vessel_link  = $vessel_id ? get_permalink( $vessel_id ) : '';

    $wa_url = function_exists( 'oc_whatsapp_url' ) ? oc_whatsapp_url( 'Hello, I\'d like to enquire about chartering with ' . get_the_title() . '.' ) : 'https://wa.me/' . get_theme_mod( 'oc_whatsapp_number', '15551234567' );
    ?>

    <!-- ══ HERO ══════════════════════════════════════════════════════════════ -->
    <section class="oc-crew-hero"<?php if ( $card_bg ) : ?> style="background:<?php echo esc_attr( $card_bg ); ?>;"<?php endif; ?>>
        <div class="oc-container oc-crew-hero__inner">
            <div class="oc-crew-photo-wrap">
                <img src="<?php echo esc_url( $photo ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" class="oc-crew-photo">
            </div>
            <h1 class="oc-crew-name"><?php the_title(); ?></h1>
            <?php if ( $role_title ) : ?>
                <span class="oc-crew-role"><?php echo esc_html( $role_title ); ?></span>
            <?php elseif ( $role_term ) : ?>
                <span class="oc-crew-role"><?php echo esc_html( $role_term ); ?></span>
            <?php endif; ?>
            <div class="oc-crew-hero-meta">
                <?php if ( $years_exp ) : ?>
                    <span class="oc-spec-pill">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <?php echo esc_html( $years_exp ); ?> Years Experience
                    </span>
                <?php endif; ?>
                <?php if ( ! empty( $languages ) ) : ?>
                    <?php foreach ( $languages as $lang ) : if ( trim( $lang ) ) : ?>
                        <span class="oc-spec-pill"><?php echo esc_html( trim( $lang ) ); ?></span>
                    <?php endif; endforeach; ?>
                <?php endif; ?>
                <?php if ( ! empty( $tags ) ) : ?>
                    <?php foreach ( $tags as $tag ) : ?>
                        <span class="oc-crew-tag"><?php echo esc_html( $tag ); ?></span>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- ══ BODY ══════════════════════════════════════════════════════════════ -->
    <div class="oc-crew-body oc-container">

        <!-- Left column -->
        <div class="oc-crew-main">

            <!-- Biography -->
            <?php if ( $bio ) : ?>
            <section class="oc-crew-section">
                <h2 class="oc-section-heading">Biography</h2>
                <div class="oc-crew-bio"><?php echo wp_kses_post( wpautop( $bio ) ); ?></div>
            </section>
            <?php elseif ( get_the_excerpt() ) : ?>
            <section class="oc-crew-section">
                <h2 class="oc-section-heading">Biography</h2>
                <div class="oc-crew-bio"><p><?php echo wp_kses_post( get_the_excerpt() ); ?></p></div>
            </section>
            <?php endif; ?>

            <!-- Full content -->
            <?php $content = get_the_content(); if ( trim( $content ) ) : ?>
            <section class="oc-crew-section">
                <h2 class="oc-section-heading">About</h2>
                <div class="oc-crew-bio"><?php the_content(); ?></div>
            </section>
            <?php endif; ?>

            <!-- Certifications -->
            <?php if ( ! empty( $certs ) ) : ?>
            <section class="oc-crew-section">
                <h2 class="oc-section-heading">Certifications</h2>
                <div class="oc-certs-list">
                    <?php foreach ( $certs as $cert ) : ?>
                        <div class="oc-cert-item">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            <span><?php echo esc_html( $cert ); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

        </div><!-- /.oc-crew-main -->

        <!-- Sidebar -->
        <aside class="oc-crew-sidebar">

            <!-- Quick facts card -->
            <div class="oc-booking-card">
                <h3 class="oc-booking-card__title">Quick Facts</h3>
                <ul class="oc-crew-facts">
                    <?php if ( $role_title || $role_term ) : ?>
                        <li>
                            <span class="oc-fact-label">Role</span>
                            <span class="oc-fact-value"><?php echo esc_html( $role_title ?: $role_term ); ?></span>
                        </li>
                    <?php endif; ?>
                    <?php if ( $years_exp ) : ?>
                        <li>
                            <span class="oc-fact-label">Experience</span>
                            <span class="oc-fact-value"><?php echo esc_html( $years_exp ); ?> years</span>
                        </li>
                    <?php endif; ?>
                    <?php if ( ! empty( $languages ) ) : ?>
                        <li>
                            <span class="oc-fact-label">Languages</span>
                            <span class="oc-fact-value"><?php echo esc_html( implode( ', ', array_filter( array_map( 'trim', $languages ) ) ) ); ?></span>
                        </li>
                    <?php endif; ?>
                    <?php if ( $vessel_title && $vessel_link ) : ?>
                        <li>
                            <span class="oc-fact-label">Vessel</span>
                            <span class="oc-fact-value"><a href="<?php echo esc_url( $vessel_link ); ?>"><?php echo esc_html( $vessel_title ); ?></a></span>
                        </li>
                    <?php elseif ( $vessel_title ) : ?>
                        <li>
                            <span class="oc-fact-label">Vessel</span>
                            <span class="oc-fact-value"><?php echo esc_html( $vessel_title ); ?></span>
                        </li>
                    <?php endif; ?>
                </ul>

                <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="oc-btn-gold oc-btn-full">Charter with Us</a>

                <a href="<?php echo esc_url( $wa_url ); ?>" class="oc-btn-whatsapp oc-btn-full" target="_blank" rel="noopener noreferrer">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    WhatsApp Us
                </a>
            </div>

        </aside>

    </div><!-- /.oc-crew-body -->

    <!-- ══ MEET THE REST OF THE CREW ═════════════════════════════════════════ -->
    <?php
    $other_crew = new WP_Query( [
        'post_type'      => 'oc_team_member',
        'posts_per_page' => 3,
        'post__not_in'   => [ $post_id ],
        'orderby'        => 'rand',
    ] );
    if ( $other_crew->have_posts() ) : ?>
    <section class="oc-crew-related oc-container">
        <h2 class="oc-section-heading">Meet the Rest of the Crew</h2>
        <div class="oc-crew-grid">
            <?php while ( $other_crew->have_posts() ) : $other_crew->the_post();
                $c_id    = get_the_ID();
                $c_photo = get_the_post_thumbnail_url( $c_id, 'medium' ) ?: 'https://images.pexels.com/photos/1043471/pexels-photo-1043471.jpeg?auto=compress&cs=tinysrgb&w=400';
                $c_role  = get_post_meta( $c_id, '_oc_role_title', true );
            ?>
                <a href="<?php echo esc_url( get_permalink( $c_id ) ); ?>" class="oc-crew-card">
                    <div class="oc-crew-card__photo-wrap">
                        <img src="<?php echo esc_url( $c_photo ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" class="oc-crew-card__photo">
                    </div>
                    <h3 class="oc-crew-card__name"><?php the_title(); ?></h3>
                    <?php if ( $c_role ) : ?>
                        <span class="oc-crew-card__role"><?php echo esc_html( $c_role ); ?></span>
                    <?php endif; ?>
                </a>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </section>
    <?php endif; ?>

    <?php endif; // end elementor check ?>
<?php endif; // end have_posts ?>

</main>

<style>
/* ── Ocean Charter: Single Team Member ───────────────────────────────────── */

.oc-single-team-member {
    background: #0a0f1a;
    color: var(--text);
}

/* Hero */
.oc-crew-hero {
    background: var(--surface, #111a28);
    border-bottom: 1px solid var(--border);
    padding: 72px 0 56px;
    text-align: center;
}
.oc-crew-hero__inner {
    display: flex;
    flex-direction: column;
    align-items: center;
}
.oc-crew-photo-wrap {
    width: 250px;
    height: 250px;
    border-radius: 50%;
    border: 3px solid var(--primary);
    overflow: hidden;
    margin-bottom: 28px;
    box-shadow: 0 0 0 8px rgba(217,178,48,0.1);
}
.oc-crew-photo {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
.oc-crew-name {
    font-family: var(--font-heading);
    font-size: clamp(32px, 4vw, 52px);
    font-weight: 400;
    color: var(--text-light, #f8fafc);
    margin: 0 0 12px;
    line-height: 1.1;
}
.oc-crew-role {
    display: block;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: var(--primary);
    margin-bottom: 24px;
}
.oc-crew-hero-meta {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
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
.oc-crew-tag {
    display: inline-flex;
    align-items: center;
    background: rgba(217,178,48,0.12);
    border: 1px solid rgba(217,178,48,0.35);
    color: var(--primary, #d9b230);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    padding: 5px 12px;
    border-radius: 999px;
}

/* Body layout */
.oc-crew-body {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 60px;
    padding-top: 64px;
    padding-bottom: 100px;
    align-items: start;
}
.oc-crew-section {
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
.oc-crew-bio {
    font-size: 17px;
    line-height: 1.8;
    color: var(--text-muted);
}
.oc-crew-bio p { margin: 0 0 16px; }

/* Certifications */
.oc-certs-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.oc-cert-item {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    background: var(--surface, #111a28);
    border-left: 3px solid var(--primary);
    border-radius: 0 0.5rem 0.5rem 0;
    padding: 14px 16px;
    font-size: 15px;
    color: var(--text);
}
.oc-cert-item svg { flex-shrink: 0; margin-top: 1px; }

/* Sidebar */
.oc-crew-sidebar {
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
.oc-crew-facts {
    list-style: none;
    padding: 0;
    margin: 0 0 20px;
    display: flex;
    flex-direction: column;
    gap: 0;
}
.oc-crew-facts li {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    gap: 12px;
    padding: 10px 0;
    border-bottom: 1px solid var(--border);
    font-size: 14px;
}
.oc-crew-facts li:last-child { border-bottom: none; }
.oc-fact-label {
    color: var(--text-muted);
    flex-shrink: 0;
}
.oc-fact-value {
    color: var(--text);
    font-weight: 500;
    text-align: right;
}
.oc-fact-value a {
    color: var(--primary);
    text-decoration: none;
}
.oc-fact-value a:hover { text-decoration: underline; }
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

/* Crew grid */
.oc-crew-related {
    padding-bottom: 100px;
}
.oc-crew-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 32px;
    margin-top: 8px;
}
.oc-crew-card {
    text-decoration: none;
    color: inherit;
    text-align: center;
    display: block;
    transition: transform 0.2s;
}
.oc-crew-card:hover { transform: translateY(-4px); }
.oc-crew-card__photo-wrap {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 2px solid var(--border);
    overflow: hidden;
    margin: 0 auto 14px;
    transition: border-color 0.2s;
}
.oc-crew-card:hover .oc-crew-card__photo-wrap { border-color: var(--primary); }
.oc-crew-card__photo {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
.oc-crew-card__name {
    font-family: var(--font-heading);
    font-size: 17px;
    font-weight: 400;
    color: var(--text-light, #f8fafc);
    margin: 0 0 4px;
}
.oc-crew-card__role {
    font-size: 12px;
    color: var(--text-muted);
    letter-spacing: 0.08em;
    text-transform: uppercase;
}

@media (max-width: 1024px) {
    .oc-crew-body { grid-template-columns: 1fr; gap: 40px; }
    .oc-crew-sidebar { position: static; order: -1; }
}
@media (max-width: 768px) {
    .oc-crew-grid { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 480px) {
    .oc-crew-grid { grid-template-columns: 1fr; }
}
</style>

<?php get_footer(); ?>
