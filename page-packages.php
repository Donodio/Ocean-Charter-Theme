<?php
/**
 * Template Name: Packages Page
 * Ocean Charter - Packages - Faithful to Stitch "Ocean Charter - Packages" design
 *
 * @package OceanCharter
 */

get_header();

if ( have_posts() ) {
    the_post();
    if ( get_post_meta( get_the_ID(), '_elementor_edit_mode', true ) === 'builder' ) {
        echo '<main id="main" class="oc-page oc-page--packages">';
        the_content();
        echo '</main>';
        get_footer();
        exit;
    }
}
?>

<main id="primary" class="site-main page-packages">

	<!-- ═══════════════════════════════════════════════
	     HERO
	     ═══════════════════════════════════════════════ -->
	<?php
	$_pid      = get_the_ID();
	$_hero_h   = get_post_meta( $_pid, '_oc_hero_height',  true ) ?: '30vh';
	$_hero_op  = get_post_meta( $_pid, '_oc_hero_opacity', true );
	$_hero_op  = ( $_hero_op !== '' ) ? floatval( $_hero_op ) : 0.6;
	$_hero_col = get_post_meta( $_pid, '_oc_hero_color',   true ) ?: '#0a0f1a';
	list( $_r, $_g, $_b ) = sscanf( $_hero_col, '#%02x%02x%02x' );
	$_overlay_css = "background:rgba({$_r},{$_g},{$_b},{$_hero_op});";
	$_hero_img_id  = absint( get_post_meta( $_pid, '_oc_hero_image', true ) );
	$_hero_img_url = $_hero_img_id
		? wp_get_attachment_image_url( $_hero_img_id, 'full' )
		: get_the_post_thumbnail_url( $_pid, 'full' );
	$_hero_pos = get_post_meta( $_pid, '_oc_hero_position', true ) ?: 'center center';
	?>
	<section class="pk-hero" style="min-height:<?php echo esc_attr( $_hero_h ); ?>;">
		<div class="pk-hero__bg"<?php if ( $_hero_img_url ) : ?> style="background-image:url('<?php echo esc_url( $_hero_img_url ); ?>');background-size:cover;background-position:<?php echo esc_attr( $_hero_pos ); ?>;"<?php endif; ?>></div>
		<div class="pk-hero__overlay" style="position:absolute;inset:0;z-index:1;<?php echo esc_attr( $_overlay_css ); ?>"></div>
		<div class="container pk-hero__content" style="position:relative;z-index:2;">
			<span class="fp-eyebrow">Charter Packages</span>
			<h1 class="pk-hero__title">Exclusive Charter<br><span class="pk-hero__accent">Packages</span></h1>
			<p class="pk-hero__desc">Indulge in the ultimate seafaring luxury. From intimate sunsets to grand celebrations, find your perfect voyage.</p>
		</div>
	</section>

	<!-- ═══════════════════════════════════════════════
	     PACKAGES GRID — Dynamic (Plugin CPT first, then demo)
	     ═══════════════════════════════════════════════ -->
	<section class="pk-grid-section">
		<div class="container">
			<?php
			// Try to load BBC packages from plugin CPT.
			$packages_query = new WP_Query( array(
				'post_type'      => 'bbc_package',
				'posts_per_page' => -1,
				'orderby'        => 'menu_order',
				'order'          => 'ASC',
			) );

			if ( $packages_query->have_posts() ) : ?>
				<div class="pk-cards-grid">
				<?php while ( $packages_query->have_posts() ) : $packages_query->the_post();
					$pkg_id   = get_the_ID();
					$price    = get_post_meta( $pkg_id, '_bbc_pkg_price', true );
					$label    = get_post_meta( $pkg_id, '_bbc_pkg_label', true );
					$location = get_post_meta( $pkg_id, '_bbc_pkg_location', true );
					$guests   = (int) get_post_meta( $pkg_id, '_bbc_pkg_max_guests', true );
					$durs     = get_post_meta( $pkg_id, '_bbc_pkg_durations', true );
					$features = get_post_meta( $pkg_id, '_bbc_pkg_features', true );
					$featured = ! empty( $label ) && strtolower( $label ) === 'signature';
					$hours    = ( is_array( $durs ) && ! empty( $durs ) ) ? intval( $durs[0]['hours'] ?? 0 ) : 0;
					?>
					<article class="pk-card <?php echo $featured ? 'pk-card--featured' : ''; ?>">
						<div class="pk-card__header">
							<?php if ( $label ) : ?><span class="pk-card__badge <?php echo $featured ? 'pk-card__badge--gold' : ''; ?>"><?php echo esc_html( $label ); ?></span><?php endif; ?>
							<h3 class="pk-card__title"><?php the_title(); ?></h3>
							<p class="pk-card__brief"><?php echo wp_trim_words( get_the_excerpt(), 20 ); ?></p>
						</div>
						<div class="pk-card__body">
							<?php if ( $price ) : ?>
							<div class="pk-card__price">
								<span>From</span>
								<strong>$<?php echo number_format( floatval( $price ) ); ?></strong>
							</div>
							<?php endif; ?>
							<div class="pk-card__specs">
								<?php if ( $hours ) : ?>
								<span class="pk-card__spec">
									<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
									<?php echo esc_html( $hours ); ?>h
								</span>
								<?php endif; ?>
								<?php if ( $guests ) : ?>
								<span class="pk-card__spec">
									<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
									<?php echo esc_html( $guests ); ?> guests
								</span>
								<?php endif; ?>
								<?php if ( $location ) : ?>
								<span class="pk-card__spec">
									<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
									<?php echo esc_html( $location ); ?>
								</span>
								<?php endif; ?>
							</div>
							<?php if ( is_array( $features ) && ! empty( $features ) ) : ?>
							<ul class="pk-card__features">
								<?php foreach ( array_slice( $features, 0, 5 ) as $feat ) : ?>
								<li><?php echo esc_html( $feat ); ?></li>
								<?php endforeach; ?>
							</ul>
							<?php endif; ?>
							<a href="<?php the_permalink(); ?>" class="btn-primary">Book Now</a>
						</div>
					</article>
				<?php endwhile; wp_reset_postdata(); ?>
				</div>
			<?php else :
				// Stitch-faithful demo packages ?>
				<div class="pk-cards-grid">

					<article class="pk-card">
						<div class="pk-card__header">
							<span class="pk-card__badge">Popular</span>
							<h3 class="pk-card__title">Sunset Cruise</h3>
							<p class="pk-card__brief">Experience the magic of the golden hour with premium champagne and gourmet hors d'oeuvres while navigating the calm shoreline.</p>
						</div>
						<div class="pk-card__body">
							<div class="pk-card__price">
								<span>From</span>
								<strong>€1,200</strong>
								<span>per charter</span>
							</div>
							<ul class="pk-card__features">
								<li>3-Hour Coastal Cruise</li>
								<li>Premium Champagne Selection</li>
								<li>Gourmet Hors d'oeuvres</li>
								<li>Professional Captain & Crew</li>
								<li>Safety Equipment & Insurance</li>
							</ul>
							<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>" class="btn-primary">Book Now</a>
						</div>
					</article>

					<article class="pk-card pk-card--featured">
						<div class="pk-card__header">
							<span class="pk-card__badge pk-card__badge--gold">Signature</span>
							<h3 class="pk-card__title">Corporate Events</h3>
							<p class="pk-card__brief">Elevate your brand with a luxury yacht meeting. Fully equipped with AV facilities and tailored catering for up to 30 guests.</p>
						</div>
						<div class="pk-card__body">
							<div class="pk-card__price">
								<span>From</span>
								<strong>€4,800</strong>
								<span>per day</span>
							</div>
							<ul class="pk-card__features">
								<li>Full-Day Charter (8 Hours)</li>
								<li>State-of-the-Art AV Facilities</li>
								<li>Tailored Catering Menu</li>
								<li>up to 30 Guests</li>
								<li>Dedicated Event Manager</li>
								<li>Professional Photography</li>
							</ul>
							<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>" class="btn-primary">Enquire Now</a>
						</div>
					</article>

					<article class="pk-card">
						<div class="pk-card__header">
							<span class="pk-card__badge">Celebration</span>
							<h3 class="pk-card__title">Birthday Parties</h3>
							<p class="pk-card__brief">Celebrate your special day with a curated party atmosphere. Includes professional DJ, party decor, and custom beverage packages.</p>
						</div>
						<div class="pk-card__body">
							<div class="pk-card__price">
								<span>From</span>
								<strong>€3,200</strong>
								<span>per charter</span>
							</div>
							<ul class="pk-card__features">
								<li>5-Hour Charter</li>
								<li>Professional DJ</li>
								<li>Custom Party Décor</li>
								<li>Bespoke Beverage Packages</li>
								<li>Photographer Available</li>
							</ul>
							<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>" class="btn-primary">Book Now</a>
						</div>
					</article>

				</div>
			<?php endif; ?>
		</div>
	</section>

	<!-- ═══════════════════════════════════════════════
	     BESPOKE VOYAGES
	     ═══════════════════════════════════════════════ -->
	<section class="pk-bespoke">
		<div class="container pk-bespoke__inner">
			<div class="pk-bespoke__content">
				<span class="fp-eyebrow">Fully Custom</span>
				<h2 class="pk-bespoke__title">Bespoke Voyages</h2>
				<p>None of our packages match your vision? Perfect. Our most discerning clients work directly with our charter architects to design a voyage that exists nowhere else in the world.</p>
				<p>Tell us your dream. We'll build the journey around it.</p>
				<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>" class="btn-primary">Speak to a Charter Architect</a>
			</div>
			<div class="pk-bespoke__checklist">
				<h3>What We Can Arrange</h3>
				<div class="pk-checklist">
					<div class="pk-checklist__item"><span class="pk-check">✓</span>Custom multi-day itineraries across multiple countries</div>
					<div class="pk-checklist__item"><span class="pk-check">✓</span>Private helicopter transfers between destinations</div>
					<div class="pk-checklist__item"><span class="pk-check">✓</span>Themed experiences (film screenings, stargazing nights, dive expeditions)</div>
					<div class="pk-checklist__item"><span class="pk-check">✓</span>Celebrity chef residencies aboard</div>
					<div class="pk-checklist__item"><span class="pk-check">✓</span>Superyacht fleet coordination for large groups</div>
					<div class="pk-checklist__item"><span class="pk-check">✓</span>Underwater photography & marine biologist guides</div>
					<div class="pk-checklist__item"><span class="pk-check">✓</span>VIP shoreside access — private islands, exclusive beach clubs</div>
					<div class="pk-checklist__item"><span class="pk-check">✓</span>Complete privacy — NDA arrangements available</div>
				</div>
			</div>
		</div>
	</section>

	<!-- ═══════════════════════════════════════════════
	     TESTIMONIALS
	     ═══════════════════════════════════════════════ -->
	<section class="pk-testimonials">
		<div class="container">
			<div class="pk-section-header">
				<span class="fp-eyebrow">Guest Experiences</span>
				<h2 class="pk-section-title">What Our Charter Guests Say</h2>
			</div>
			<div class="pk-testimonials__grid">
				<blockquote class="pk-testi">
					<p>"An absolute dream. Captain Marcus took us to the most secluded lagoons away from the crowds. The Sunset Cruise package was the highlight of our anniversary."</p>
					<footer>
						<strong>Julian de Silva</strong>
						<span>Sunset Cruise, Amalfi Coast</span>
					</footer>
				</blockquote>
				<blockquote class="pk-testi pk-testi--featured">
					<p>"The private chef onboard was world-class. Having a 5-course dinner under the stars at anchor was the highlight of our summer trip. The Corporate package exceeded every expectation."</p>
					<footer>
						<strong>Elena Wright</strong>
						<span>Corporate Events, Monaco</span>
					</footer>
				</blockquote>
				<blockquote class="pk-testi">
					<p>"Pure luxury. From the seamless booking through Ocean Charter to the pristine condition of the yacht. Our birthday party was beyond anything we could have imagined. 10/10."</p>
					<footer>
						<strong>Maximilian Koch</strong>
						<span>Birthday Celebration, Ibiza</span>
					</footer>
				</blockquote>
			</div>
		</div>
	</section>

</main>

<style>
.page-packages { background: var(--secondary); }

/* Hero */
.pk-hero { position: relative; min-height: 30vh; display: flex; align-items: center; overflow: hidden; padding: 100px 0 60px; }
.pk-hero__bg { position: absolute; inset: 0; background: linear-gradient(160deg, #060e1a 0%, #0d1f35 60%, #060e1a 100%); background-size: cover; background-position: center center; }
.pk-hero__bg::before { content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 200px; background: linear-gradient(to top, var(--secondary), transparent); }
.pk-hero__content { position: relative; z-index: 2; max-width: 800px; }
.pk-hero__title { font-family: var(--font-heading); font-size: clamp(42px, 6vw, 80px); font-weight: 400; color: var(--text-light); margin: 16px 0 24px; line-height: 1.1; }
.pk-hero__accent { color: var(--primary); }
.pk-hero__desc { font-size: 20px; color: var(--text-muted); max-width: 600px; line-height: 1.7; }

/* Cards Grid */
.pk-grid-section { padding: 100px 0; }
.pk-cards-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 32px; }
.pk-card { background: var(--surface); border-radius: var(--radius-lg); border: 1px solid var(--glass-border); display: flex; flex-direction: column; overflow: hidden; transition: transform var(--transition-normal), border-color var(--transition-normal); }
.pk-card:hover { transform: translateY(-6px); border-color: rgba(217,178,48,0.3); }
.pk-card--featured { border-color: rgba(217,178,48,0.4); background: linear-gradient(160deg, #111a28 0%, #182030 100%); position: relative; }
.pk-card--featured::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px; background: linear-gradient(90deg, transparent, var(--primary), transparent); }
.pk-card__header { padding: 40px 40px 32px; }
.pk-card__badge { display: inline-block; padding: 6px 14px; border-radius: var(--radius-full); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; background: rgba(255,255,255,0.08); color: var(--text-muted); margin-bottom: 20px; }
.pk-card__badge--gold { background: var(--primary); color: #000; }
.pk-card__title { font-family: var(--font-heading); font-size: 28px; color: var(--text-light); margin: 0 0 12px; font-weight: 400; }
.pk-card__brief { color: var(--text-muted); font-size: 15px; line-height: 1.7; margin: 0; }
.pk-card__body { padding: 0 40px 40px; flex: 1; display: flex; flex-direction: column; }
.pk-card__price { display: flex; align-items: baseline; gap: 8px; padding: 24px 0; border-top: 1px solid var(--glass-border); border-bottom: 1px solid var(--glass-border); margin-bottom: 24px; }
.pk-card__price span { font-size: 13px; color: var(--text-muted); }
.pk-card__price strong { font-family: var(--font-heading); font-size: 42px; color: var(--primary); line-height: 1; }
.pk-card__features { list-style: none; padding: 0; margin: 0 0 32px; flex: 1; }
.pk-card__features li { padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.04); color: var(--text-muted); font-size: 14px; display: flex; align-items: center; gap: 10px; }
.pk-card__features li::before { content: '✓'; color: var(--primary); font-weight: 700; flex-shrink: 0; }
.pk-card__specs { display: flex; flex-wrap: wrap; gap: 0.5rem 1rem; padding: 16px 0; border-top: 1px solid var(--glass-border); border-bottom: 1px solid var(--glass-border); margin-bottom: 16px; }
.pk-card__spec { display: inline-flex; align-items: center; gap: 5px; font-size: 13px; color: var(--text-muted); }
.pk-card__spec svg { color: var(--primary); flex-shrink: 0; }

/* Bespoke */
.pk-bespoke { padding: 100px 0; background: var(--surface); }
.pk-bespoke__inner { display: grid; grid-template-columns: 1.2fr 1fr; gap: 80px; align-items: start; }
.pk-bespoke__title { font-family: var(--font-heading); font-size: clamp(32px, 4vw, 52px); color: var(--text-light); font-weight: 400; margin: 16px 0 24px; }
.pk-bespoke__content p { color: var(--text-muted); line-height: 1.8; margin-bottom: 24px; font-size: 17px; }
.pk-bespoke__checklist h3 { font-family: var(--font-heading); font-size: 22px; color: var(--text-light); margin: 0 0 24px; font-weight: 500; }
.pk-checklist { display: flex; flex-direction: column; gap: 16px; }
.pk-checklist__item { display: flex; gap: 14px; align-items: flex-start; color: var(--text-muted); font-size: 15px; line-height: 1.5; }
.pk-check { color: var(--primary); font-weight: 700; flex-shrink: 0; margin-top: 1px; }

/* Section header */
.pk-section-header { text-align: center; margin-bottom: 60px; }
.pk-section-title { font-family: var(--font-heading); font-size: clamp(28px, 4vw, 44px); color: var(--text-light); font-weight: 400; margin: 12px 0 0; }

/* Testimonials */
.pk-testimonials { padding: 100px 0; }
.pk-testimonials__grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 32px; }
.pk-testi { background: var(--surface); border: 1px solid var(--glass-border); border-radius: var(--radius-lg); padding: 40px; margin: 0; transition: transform var(--transition-normal); }
.pk-testi:hover { transform: translateY(-4px); }
.pk-testi--featured { border-color: rgba(217,178,48,0.3); background: linear-gradient(160deg, #111a28, #182030); }
.pk-testi p { color: var(--text-muted); line-height: 1.8; font-size: 15px; font-style: italic; margin-bottom: 24px; }
.pk-testi footer { display: flex; flex-direction: column; gap: 4px; }
.pk-testi footer strong { color: var(--text-light); font-size: 16px; font-family: var(--font-heading); }
.pk-testi footer span { color: var(--primary); font-size: 13px; letter-spacing: 0.05em; }

@media (max-width: 1024px) {
	.pk-cards-grid, .pk-testimonials__grid { grid-template-columns: 1fr; }
	.pk-bespoke__inner { grid-template-columns: 1fr; gap: 48px; }
}
</style>

<?php get_footer(); ?>
