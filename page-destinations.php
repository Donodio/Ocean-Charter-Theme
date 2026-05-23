<?php
/**
 * Template Name: Destinations Page
 * Ocean Charter - Destinations - Faithful to Stitch "Ocean Charter - Destinations" design
 *
 * @package OceanCharter
 */

get_header();

if ( have_posts() ) {
    the_post();
    if ( get_post_meta( get_the_ID(), '_elementor_edit_mode', true ) === 'builder' ) {
        echo '<main id="main" class="oc-page oc-page--destinations">';
        the_content();
        echo '</main>';
        get_footer();
        exit;
    }
}
?>

<main id="primary" class="site-main page-destinations">

	<!-- ═══════════════════════════════════════════════
	     PAGE HERO
	     ═══════════════════════════════════════════════ -->
	<?php
	$_pid        = get_the_ID();
	$_hero_h     = get_post_meta( $_pid, '_oc_hero_height',  true ) ?: '70vh';
	$_hero_op    = get_post_meta( $_pid, '_oc_hero_opacity', true );
	$_hero_op    = ( $_hero_op !== '' ) ? floatval( $_hero_op ) : 0.6;
	$_hero_col   = get_post_meta( $_pid, '_oc_hero_color',   true ) ?: '#0a0f1a';
	// Convert hex to rgb for rgba()
	list( $_r, $_g, $_b ) = sscanf( $_hero_col, '#%02x%02x%02x' );
	$_overlay_css = "background:rgba({$_r},{$_g},{$_b},{$_hero_op});";
	$_hero_img_id  = absint( get_post_meta( $_pid, '_oc_hero_image', true ) );
	$_hero_img_url = $_hero_img_id
		? wp_get_attachment_image_url( $_hero_img_id, 'full' )
		: get_the_post_thumbnail_url( $_pid, 'full' );
	$_hero_pos = get_post_meta( $_pid, '_oc_hero_position', true ) ?: 'center center';
	?>
	<section class="dt-hero" style="min-height:<?php echo esc_attr( $_hero_h ); ?>;">
		<div class="dt-hero__bg"<?php if ( $_hero_img_url ) : ?> style="background-image:url('<?php echo esc_url( $_hero_img_url ); ?>');background-size:cover;background-position:<?php echo esc_attr( $_hero_pos ); ?>;"<?php endif; ?>></div>
		<div class="dt-hero__overlay" style="position:absolute;inset:0;z-index:1;<?php echo esc_attr( $_overlay_css ); ?>"></div>
		<div class="container dt-hero__content" style="position:relative;z-index:2;">
			<span class="fp-eyebrow">Sail the World</span>
			<h1 class="dt-hero__title">Chart Your Course<br>to <span class="dt-hero__accent">Paradise</span></h1>
			<p class="dt-hero__desc">From the crystalline waters of the Mediterranean to the untouched atolls of the South Pacific — we take you there, in unrivaled style.</p>
		</div>
		<div class="dt-hero__caption">Motor Yacht 'Aurelia' · Off the coast of Mykonos</div>
	</section>

	<!-- ═══════════════════════════════════════════════
	     DESTINATIONS GRID: Iconic Archipelagos
	     ═══════════════════════════════════════════════ -->
	<section class="dt-regions">
		<div class="container">
			<div class="dt-section-header">
				<span class="fp-eyebrow">Six Oceans, Endless Dreams</span>
				<h2 class="dt-section-title">Iconic Archipelagos</h2>
			</div>

			<div class="dt-regions__grid">

				<!-- Mediterranean -->
				<article class="dt-region-card dt-region-card--full">
					<div class="dt-region-card__bg dt-region-card__bg--med"></div>
					<div class="dt-region-card__overlay"></div>
					<div class="dt-region-card__content">
						<div class="dt-region-card__header">
							<span class="dt-region-card__num">01</span>
							<span class="dt-region-card__tag">Most Popular</span>
						</div>
						<h3 class="dt-region-card__name">The Mediterranean</h3>
						<p class="dt-region-card__desc">From the glamorous French Riviera to the ancient charm of the Amalfi Coast and Greek Isles. The Mediterranean remains the definitive canvas for luxury charter.</p>
						<div class="dt-region-card__highlights">
							<span>French Riviera</span>
							<span>Amalfi Coast</span>
							<span>Greek Islands</span>
							<span>Ibiza</span>
							<span>Croatia</span>
						</div>
						<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>" class="dt-region-card__cta">Explore <span>→</span></a>
					</div>
				</article>

				<!-- Caribbean & Bahamas -->
				<article class="dt-region-card">
					<div class="dt-region-card__bg dt-region-card__bg--carib"></div>
					<div class="dt-region-card__overlay"></div>
					<div class="dt-region-card__content">
						<div class="dt-region-card__header">
							<span class="dt-region-card__num">02</span>
						</div>
						<h3 class="dt-region-card__name">Caribbean & Bahamas</h3>
						<p class="dt-region-card__desc">Unwind in the Exumas, sail the British Virgin Islands, or explore the lush Grenadines. Every cove a new postcard of perfection.</p>
						<div class="dt-region-card__highlights">
							<span>The Exumas</span>
							<span>BVI</span>
							<span>St. Barts</span>
							<span>Grenadines</span>
						</div>
						<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>" class="dt-region-card__cta">Explore <span>→</span></a>
					</div>
				</article>

				<!-- South Pacific -->
				<article class="dt-region-card">
					<div class="dt-region-card__bg dt-region-card__bg--pacific"></div>
					<div class="dt-region-card__overlay"></div>
					<div class="dt-region-card__content">
						<div class="dt-region-card__header">
							<span class="dt-region-card__num">03</span>
						</div>
						<h3 class="dt-region-card__name">South Pacific</h3>
						<p class="dt-region-card__desc">Discover the untouched beauty of French Polynesia, Fiji, and the Whitsunday Islands. A world apart, preserved for those who seek the extraordinary.</p>
						<div class="dt-region-card__highlights">
							<span>Bora Bora</span>
							<span>Fiji</span>
							<span>Whitsundays</span>
							<span>New Caledonia</span>
						</div>
						<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>" class="dt-region-card__cta">Explore <span>→</span></a>
					</div>
				</article>

				<!-- South East Asia -->
				<article class="dt-region-card">
					<div class="dt-region-card__bg dt-region-card__bg--asia"></div>
					<div class="dt-region-card__overlay"></div>
					<div class="dt-region-card__content">
						<div class="dt-region-card__header">
							<span class="dt-region-card__num">04</span>
						</div>
						<h3 class="dt-region-card__name">South East Asia</h3>
						<p class="dt-region-card__desc">The magical limestone karsts of Phuket, Indonesia's Raja Ampat, and Palawan's hidden lagoons. Asia's waters hold some of the world's best kept secrets.</p>
						<div class="dt-region-card__highlights">
							<span>Phuket</span>
							<span>Raja Ampat</span>
							<span>Palawan</span>
							<span>Komodo</span>
						</div>
						<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>" class="dt-region-card__cta">Explore <span>→</span></a>
					</div>
				</article>

				<!-- Northern Europe -->
				<article class="dt-region-card">
					<div class="dt-region-card__bg dt-region-card__bg--nordic"></div>
					<div class="dt-region-card__overlay"></div>
					<div class="dt-region-card__content">
						<div class="dt-region-card__header">
							<span class="dt-region-card__num">05</span>
						</div>
						<h3 class="dt-region-card__name">Northern Europe</h3>
						<p class="dt-region-card__desc">Venture into the majestic Norwegian Fjords or the serene Baltic Sea for an Arctic adventure unlike anything you've ever experienced.</p>
						<div class="dt-region-card__highlights">
							<span>Norwegian Fjords</span>
							<span>Baltic Sea</span>
							<span>Denmark</span>
							<span>Arctic Circle</span>
						</div>
						<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>" class="dt-region-card__cta">Explore <span>→</span></a>
					</div>
				</article>

				<!-- Indian Ocean -->
				<article class="dt-region-card">
					<div class="dt-region-card__bg dt-region-card__bg--indian"></div>
					<div class="dt-region-card__overlay"></div>
					<div class="dt-region-card__content">
						<div class="dt-region-card__header">
							<span class="dt-region-card__num">06</span>
						</div>
						<h3 class="dt-region-card__name">Indian Ocean</h3>
						<p class="dt-region-card__desc">Paradise redefined in the Maldives, the granitic beauty of Seychelles, and the lush shores of Mauritius. Sanctuary beyond compare.</p>
						<div class="dt-region-card__highlights">
							<span>Maldives</span>
							<span>Seychelles</span>
							<span>Mauritius</span>
							<span>Réunion</span>
						</div>
						<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>" class="dt-region-card__cta">Explore <span>→</span></a>
					</div>
				</article>

			</div>
		</div>
	</section>

	<!-- ═══════════════════════════════════════════════
	     NAUTICAL CHART SECTION
	     ═══════════════════════════════════════════════ -->
	<section class="dt-chart">
		<div class="container dt-chart__inner">
			<div class="dt-chart__content">
				<span class="fp-eyebrow">Navigation</span>
				<h2 class="dt-chart__title">Explore by Nautical Chart</h2>
				<p>Our interactive charter planning service lets you trace your ideal route across the globe. Speak with our specialists to build a bespoke itinerary, sail by sail, port by port.</p>
				<div class="dt-chart__stats">
					<div class="dt-stat">
						<strong>60+</strong>
						<span>Countries</span>
					</div>
					<div class="dt-stat">
						<strong>200+</strong>
						<span>Marinas</span>
					</div>
					<div class="dt-stat">
						<strong>1,000+</strong>
						<span>Anchorages</span>
					</div>
					<div class="dt-stat">
						<strong>12</strong>
						<span>Ocean Routes</span>
					</div>
				</div>
				<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>" class="btn-primary">Plan My Route</a>
			</div>
			<div class="dt-chart__map">
				<div class="dt-chart__map-placeholder">
					<div class="dt-map-dot" style="top:38%;left:48%;" title="Mediterranean"></div>
					<div class="dt-map-dot" style="top:52%;left:28%;" title="Caribbean"></div>
					<div class="dt-map-dot" style="top:58%;left:77%;" title="South East Asia"></div>
					<div class="dt-map-dot" style="top:62%;left:85%;" title="South Pacific"></div>
					<div class="dt-map-dot" style="top:50%;left:60%;" title="Indian Ocean"></div>
					<div class="dt-map-dot" style="top:22%;left:47%;" title="Northern Europe"></div>
				</div>
			</div>
		</div>
	</section>

	<!-- ═══════════════════════════════════════════════
	     FEATURED ITINERARY TEASER
	     ═══════════════════════════════════════════════ -->
	<section class="dt-itinerary-teaser">
		<div class="container">
			<div class="dt-section-header">
				<span class="fp-eyebrow">Sample Voyage</span>
				<h2 class="dt-section-title">Featured Itinerary</h2>
				<p class="dt-section-desc">A taste of what a bespoke Ocean Charter voyage looks like. Every journey is crafted exclusively for you.</p>
			</div>
			<div class="dt-itin-preview">
				<div class="dt-itin-map">
					<div class="dt-itin-map__inner"></div>
				</div>
				<div class="dt-itin-days">
					<div class="dt-itin-header">
						<h3>Amalfi Coast & Aegean Wonders</h3>
						<p>Naples → Positano → Capri → Greek Islands</p>
					</div>
					<div class="dt-itin-day">
						<div class="dt-itin-day__num">Day 1</div>
						<div class="dt-itin-day__content">
							<h4>Departure: Naples, Italy — Boarding & Welcome Gala</h4>
							<p>The journey begins at the gateway to the Mediterranean, with a champagne reception on the aft deck as you depart the historic port of Naples.</p>
						</div>
					</div>
					<div class="dt-itin-day">
						<div class="dt-itin-day__num">Days 2–3</div>
						<div class="dt-itin-day__content">
							<h4>Positano & The Amalfi Gems — Cliffs, Colors, and Limoncello</h4>
							<p>Two glorious days anchored off the vertical village of Positano. Explore boutique-lined streets or enjoy a private beach club excursion at Da Adolfo.</p>
						</div>
					</div>
					<div class="dt-itin-day">
						<div class="dt-itin-day__num">Day 4</div>
						<div class="dt-itin-day__content">
							<h4>The Blue Grotto of Capri — Island of Glamour</h4>
							<p>A day of sheer elegance. From the luminous Blue Grotto to the designer boutiques of the Piazzetta, Capri offers an unmatched luxury experience.</p>
						</div>
					</div>
					<div class="dt-itin-day">
						<div class="dt-itin-day__num">Day 5</div>
						<div class="dt-itin-day__content">
							<h4>At Sea: Tyrrhenian Crossing — Wellness & Relaxation</h4>
							<p>Complimentary 90-minute signature massage for all guests during the crossing. Prepare for the wonders of the Aegean that await.</p>
						</div>
					</div>
					<div class="dt-itin-footer">
						<div class="dt-itin-price">
							<span>Inclusive of all excursions & spirits</span>
							<strong>4 staterooms remaining for Summer 2024</strong>
						</div>
						<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>" class="btn-primary">Reserve Your Suite</a>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- ═══════════════════════════════════════════════
	     CTA
	     ═══════════════════════════════════════════════ -->
	<section class="dt-cta">
		<div class="container dt-cta__inner">
			<h2 class="dt-cta__title">Ready to set sail?</h2>
			<p>Our world-class charter specialists are standing by to build your perfect voyage.</p>
			<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>" class="btn-primary">Contact a Specialist</a>
		</div>
	</section>

</main>

<style>
.page-destinations { background: var(--secondary); }

/* Hero */
.dt-hero { position: relative; min-height: 35vh; display: flex; align-items: center; overflow: hidden; padding: 100px 0 60px; }
.dt-hero__bg { position: absolute; inset: 0; background: linear-gradient(160deg, #060e1a 0%, #0d1f35 50%, #060e1a 100%); background-size: cover; background-position: center center; }
.dt-hero__bg::before { content: ''; position: absolute; top: 0; right: 0; width: 60%; height: 100%; background: radial-gradient(ellipse at 80% 50%, rgba(217,178,48,0.06) 0%, transparent 60%); }
.dt-hero__content { position: relative; z-index: 2; }
.dt-hero__title { font-family: var(--font-heading); font-size: clamp(42px, 7vw, 88px); font-weight: 400; color: var(--text-light); margin: 16px 0 24px; line-height: 1.1; }
.dt-hero__accent { color: var(--primary); }
.dt-hero__desc { font-size: 20px; color: var(--text-muted); max-width: 600px; line-height: 1.7; }
.dt-hero__caption { position: absolute; bottom: 32px; right: 32px; font-size: 12px; color: var(--text-muted); letter-spacing: 0.1em; text-transform: uppercase; z-index: 2; opacity: 0.7; }

/* Regions */
.dt-regions { padding: 100px 0; }
.dt-section-header { text-align: center; margin-bottom: 60px; }
.dt-section-title { font-family: var(--font-heading); font-size: clamp(32px, 4vw, 52px); color: var(--text-light); font-weight: 400; margin: 12px 0 0; }
.dt-section-desc { color: var(--text-muted); font-size: 18px; margin-top: 16px; }
.dt-regions__grid { display: grid; grid-template-columns: repeat(2, 1fr); grid-template-rows: auto; gap: 24px; }
.dt-region-card--full { grid-column: 1 / -1; }
.dt-region-card { position: relative; border-radius: var(--radius-lg); overflow: hidden; min-height: 380px; display: flex; flex-direction: column; justify-content: flex-end; transition: transform var(--transition-normal); }
.dt-region-card--full { min-height: 500px; }
.dt-region-card:hover { transform: translateY(-4px); }
.dt-region-card__bg { position: absolute; inset: 0; background-size: cover; background-position: center; transition: transform var(--transition-slow); }
.dt-region-card:hover .dt-region-card__bg { transform: scale(1.03); }
/* Background gradients as stand-in for images */
.dt-region-card__bg--med { background: linear-gradient(135deg, #0a2040 0%, #1a4060 40%, #0d3558 100%); }
.dt-region-card__bg--carib { background: linear-gradient(135deg, #003d40 0%, #007060 40%, #005540 100%); }
.dt-region-card__bg--pacific { background: linear-gradient(135deg, #001a3a 0%, #002a5a 40%, #001530 100%); }
.dt-region-card__bg--asia { background: linear-gradient(135deg, #2a1a00 0%, #4a3000 40%, #3a2500 100%); }
.dt-region-card__bg--nordic { background: linear-gradient(135deg, #0a0a2a 0%, #1a1a4a 40%, #0d0d3a 100%); }
.dt-region-card__bg--indian { background: linear-gradient(135deg, #001a30 0%, #002a50 40%, #001525 100%); }
.dt-region-card__overlay { position: absolute; inset: 0; background: linear-gradient(to top, rgba(5, 10, 20, 0.92) 0%, rgba(5, 10, 20, 0.3) 60%, transparent 100%); }
.dt-region-card__content { position: relative; z-index: 2; padding: 40px; }
.dt-region-card__header { display: flex; align-items: center; gap: 12px; margin-bottom: 16px; }
.dt-region-card__num { font-size: 12px; color: var(--primary); font-weight: 700; letter-spacing: 0.15em; text-transform: uppercase; }
.dt-region-card__tag { font-size: 11px; background: var(--primary); color: #000; padding: 4px 10px; border-radius: var(--radius-full); font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; }
.dt-region-card__name { font-family: var(--font-heading); font-size: clamp(24px, 3vw, 36px); color: var(--text-light); margin: 0 0 12px; font-weight: 400; }
.dt-region-card--full .dt-region-card__name { font-size: clamp(32px, 4vw, 52px); }
.dt-region-card__desc { color: var(--text-muted); font-size: 15px; line-height: 1.7; margin-bottom: 20px; max-width: 600px; }
.dt-region-card__highlights { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 24px; }
.dt-region-card__highlights span { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12); color: var(--text-light); padding: 6px 14px; border-radius: var(--radius-full); font-size: 12px; font-weight: 500; }
.dt-region-card__cta { display: inline-flex; align-items: center; gap: 8px; color: var(--primary); font-weight: 600; font-size: 14px; letter-spacing: 0.05em; text-transform: uppercase; text-decoration: none; transition: gap var(--transition-fast); }
.dt-region-card__cta:hover { gap: 14px; }

/* Nautical Chart */
.dt-chart { padding: 100px 0; background: var(--surface); }
.dt-chart__inner { display: grid; grid-template-columns: 1fr 1fr; gap: 80px; align-items: center; }
.dt-chart__title { font-family: var(--font-heading); font-size: clamp(30px, 4vw, 46px); color: var(--text-light); font-weight: 400; margin: 16px 0 20px; }
.dt-chart__inner p { color: var(--text-muted); line-height: 1.8; margin-bottom: 40px; font-size: 17px; }
.dt-chart__stats { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 40px; }
.dt-stat { }
.dt-stat strong { display: block; font-family: var(--font-heading); font-size: 42px; color: var(--primary); line-height: 1; margin-bottom: 6px; }
.dt-stat span { font-size: 13px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.1em; }
.dt-chart__map { position: relative; }
.dt-chart__map-placeholder { height: 400px; background: linear-gradient(135deg, #0a1628 0%, #0d1f35 100%); border-radius: var(--radius-lg); border: 1px solid var(--glass-border); position: relative; overflow: hidden; }
.dt-chart__map-placeholder::before { content: ''; position: absolute; inset: 0; background: repeating-linear-gradient(0deg, transparent, transparent 40px, rgba(255,255,255,0.02) 40px, rgba(255,255,255,0.02) 41px), repeating-linear-gradient(90deg, transparent, transparent 40px, rgba(255,255,255,0.02) 40px, rgba(255,255,255,0.02) 41px); }
.dt-map-dot { position: absolute; width: 12px; height: 12px; border-radius: 50%; background: var(--primary); box-shadow: 0 0 0 4px rgba(217,178,48,0.2), 0 0 20px rgba(217,178,48,0.4); animation: mapPulse 2s ease infinite; }
@keyframes mapPulse { 0%, 100% { transform: scale(1); opacity: 1; } 50% { transform: scale(1.3); opacity: 0.7; } }

/* Itinerary Teaser */
.dt-itinerary-teaser { padding: 100px 0; }
.dt-itin-preview { display: grid; grid-template-columns: 1fr 1.4fr; gap: 60px; align-items: stretch; margin-top: 60px; }
.dt-itin-map { background: var(--surface); border-radius: var(--radius-lg); border: 1px solid var(--glass-border); overflow: hidden; }
.dt-itin-map__inner { height: 100%; min-height: 400px; background: linear-gradient(135deg, #091522 0%, #122030 100%); position: relative; }
.dt-itin-days { background: var(--surface); border-radius: var(--radius-lg); border: 1px solid var(--glass-border); padding: 48px; display: flex; flex-direction: column; }
.dt-itin-header { margin-bottom: 32px; padding-bottom: 32px; border-bottom: 1px solid var(--glass-border); }
.dt-itin-header h3 { font-family: var(--font-heading); font-size: 26px; color: var(--text-light); margin-bottom: 8px; font-weight: 400; }
.dt-itin-header p { color: var(--primary); font-size: 14px; margin: 0; letter-spacing: 0.05em; }
.dt-itin-day { display: grid; grid-template-columns: 80px 1fr; gap: 20px; padding: 20px 0; border-bottom: 1px solid var(--glass-border); }
.dt-itin-day:last-of-type { border-bottom: none; }
.dt-itin-day__num { font-size: 12px; color: var(--primary); font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; padding-top: 3px; }
.dt-itin-day__content h4 { font-family: var(--font-heading); font-size: 16px; color: var(--text-light); margin: 0 0 6px; font-weight: 600; }
.dt-itin-day__content p { color: var(--text-muted); font-size: 14px; line-height: 1.7; margin: 0; }
.dt-itin-footer { margin-top: 32px; padding-top: 32px; border-top: 1px solid var(--glass-border); display: flex; align-items: center; justify-content: space-between; gap: 24px; flex-wrap: wrap; }
.dt-itin-price span { display: block; color: var(--text-muted); font-size: 13px; margin-bottom: 4px; }
.dt-itin-price strong { font-family: var(--font-heading); font-size: 16px; color: var(--primary); }

/* CTA */
.dt-cta { padding: 120px 0; text-align: center; background: linear-gradient(135deg, var(--surface), #0a1628); }
.dt-cta__inner { max-width: 600px; margin: 0 auto; }
.dt-cta__title { font-family: var(--font-heading); font-size: clamp(32px, 4vw, 52px); color: var(--text-light); font-weight: 400; margin-bottom: 20px; }
.dt-cta__inner p { color: var(--text-muted); font-size: 18px; margin-bottom: 40px; }

@media (max-width: 1024px) {
	.dt-chart__inner, .dt-itin-preview { grid-template-columns: 1fr; gap: 40px; }
}
@media (max-width: 768px) {
	.dt-regions__grid { grid-template-columns: 1fr; }
	.dt-region-card--full { grid-column: unset; }
	.dt-itin-footer { flex-direction: column; }
}
</style>

<?php get_footer(); ?>
