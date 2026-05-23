<?php
/**
 * Template Name: Services Page
 * Ocean Charter - Services - Faithful to Stitch "Ocean Charter - Services" design
 *
 * @package OceanCharter
 */

get_header();

if ( have_posts() ) {
    the_post();
    if ( get_post_meta( get_the_ID(), '_elementor_edit_mode', true ) === 'builder' ) {
        echo '<main id="main" class="oc-page oc-page--services">';
        the_content();
        echo '</main>';
        get_footer();
        exit;
    }
}
?>

<main id="primary" class="site-main page-services">

	<!-- ═══════════════════════════════════════════════
	     PAGE HERO
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
	<section class="sv-hero" style="min-height:<?php echo esc_attr( $_hero_h ); ?>;">
		<div class="sv-hero__bg"<?php if ( $_hero_img_url ) : ?> style="background-image:url('<?php echo esc_url( $_hero_img_url ); ?>');background-size:cover;background-position:<?php echo esc_attr( $_hero_pos ); ?>;"<?php endif; ?>></div>
		<div class="sv-hero__overlay" style="position:absolute;inset:0;z-index:1;<?php echo esc_attr( $_overlay_css ); ?>"></div>
		<div class="container sv-hero__content" style="position:relative;z-index:2;">
			<span class="fp-eyebrow">Premium Services</span>
			<h1 class="sv-hero__title">Bespoke Maritime<br><span class="sv-hero__accent">Experiences</span></h1>
			<p class="sv-hero__desc">Elevate your journey with a suite of premium services designed to satisfy every desire. From Michelin-star dining to adrenaline-fueled water sports.</p>
		</div>
	</section>

	<!-- ═══════════════════════════════════════════════
	     INTRO: Tailored to Your Tastes
	     ═══════════════════════════════════════════════ -->
	<section class="sv-intro">
		<div class="container sv-intro__inner">
			<div class="sv-intro__text">
				<span class="fp-eyebrow">Our Philosophy</span>
				<h2 class="sv-intro__title">Tailored To Your Tastes</h2>
				<p>Our team of experts curate every detail of your voyage, ensuring an atmosphere of absolute luxury and effortless relaxation. Whether you desire a private chef conjuring culinary masterpieces, exhilarating water sports, or a serene spa day at sea — we deliver it flawlessly.</p>
				<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>" class="btn-primary">Design Your Journey</a>
			</div>
			<div class="sv-intro__img-wrap">
				<div class="sv-intro__img sv-intro__img--placeholder"></div>
			</div>
		</div>
	</section>

	<!-- ═══════════════════════════════════════════════
	     SERVICE DETAIL: Private Michelin Chefs
	     ═══════════════════════════════════════════════ -->
	<section class="sv-detail sv-detail--alt" id="michelin-chefs">
		<div class="container sv-detail__inner">
			<div class="sv-detail__img-col">
				<div class="sv-detail__img sv-detail__img--chef"></div>
				<div class="sv-detail__badge">
					<span class="sv-detail__badge-icon">✦</span>
					<span>Michelin Rated Partners</span>
				</div>
			</div>
			<div class="sv-detail__content">
				<span class="fp-eyebrow">Culinary Excellence</span>
				<h2 class="sv-detail__title">Private Michelin Chefs</h2>
				<p class="sv-detail__lead">Savor gourmet menus tailored to your palate by world-renowned chefs. From intimate candlelit dinners to grand seafood galas under the stars.</p>
				<div class="sv-detail__features">
					<div class="sv-feature">
						<span class="sv-feature__icon">✦</span>
						<div>
							<h4>Custom Menus</h4>
							<p>Every dish designed around your tastes, dietary needs, and the finest local ingredients sourced from each port.</p>
						</div>
					</div>
					<div class="sv-feature">
						<span class="sv-feature__icon">✦</span>
						<div>
							<h4>Wine Pairing</h4>
							<p>A curated cellar aboard, with our sommelier recommending perfect pairings for each course from our 300-bottle collection.</p>
						</div>
					</div>
					<div class="sv-feature">
						<span class="sv-feature__icon">✦</span>
						<div>
							<h4>Local Sourcing</h4>
							<p>From the truffle markets of Provence to Sicilian fish auctions — authenticity is our commitment to you.</p>
						</div>
					</div>
				</div>
				<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>" class="sv-detail__cta">Enquire About Dining →</a>
			</div>
		</div>
	</section>

	<!-- ═══════════════════════════════════════════════
	     SERVICE DETAIL: Luxury Water Toys
	     ═══════════════════════════════════════════════ -->
	<section class="sv-detail" id="water-toys">
		<div class="container sv-detail__inner sv-detail__inner--reverse">
			<div class="sv-detail__content">
				<span class="fp-eyebrow">Aquatic Adventures</span>
				<h2 class="sv-detail__title">Luxury Water Toys</h2>
				<p class="sv-detail__lead">High-speed aquatic adventures with our premium fleet of Yamaha & Sea-Doo jet skis, Seabobs, and luxury inflatable beach clubs.</p>
				<div class="sv-detail__features">
					<div class="sv-feature">
						<span class="sv-feature__icon">✦</span>
						<div>
							<h4>Latest Models</h4>
							<p>2024-model watercraft including Seabob F5 SR, Yamaha FX Cruiser, and carbon-fiber paddleboards.</p>
						</div>
					</div>
					<div class="sv-feature">
						<span class="sv-feature__icon">✦</span>
						<div>
							<h4>Safety Training</h4>
							<p>Professional instruction available for every activity. Your safety is our absolute priority on every charter.</p>
						</div>
					</div>
					<div class="sv-feature">
						<span class="sv-feature__icon">✦</span>
						<div>
							<h4>Tender Support</h4>
							<p>Our dedicated chase tender follows your adventures, providing support and access to the most secluded beaches.</p>
						</div>
					</div>
				</div>
				<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>" class="sv-detail__cta">Enquire About Water Sports →</a>
			</div>
			<div class="sv-detail__img-col">
				<div class="sv-detail__img sv-detail__img--watertoys"></div>
			</div>
		</div>
	</section>

	<!-- ═══════════════════════════════════════════════
	     SERVICE DETAIL: Event Curation
	     ═══════════════════════════════════════════════ -->
	<section class="sv-detail sv-detail--alt" id="event-curation">
		<div class="container sv-detail__inner">
			<div class="sv-detail__img-col">
				<div class="sv-detail__img sv-detail__img--events"></div>
			</div>
			<div class="sv-detail__content">
				<span class="fp-eyebrow">Events at Sea</span>
				<h2 class="sv-detail__title">Event Curation</h2>
				<p class="sv-detail__lead">Unforgettable maritime celebrations designed by our elite planning team. From corporate retreats to bespoke weddings and private galas.</p>
				<div class="sv-detail__features">
					<div class="sv-feature">
						<span class="sv-feature__icon">✦</span>
						<div>
							<h4>Full Production</h4>
							<p>End-to-end event management including décor, lighting, sound, and entertainment — flawlessly orchestrated.</p>
						</div>
					</div>
					<div class="sv-feature">
						<span class="sv-feature__icon">✦</span>
						<div>
							<h4>Live Music</h4>
							<p>From jazz quartets to world-class DJs, we source talent to create the perfect atmosphere for your celebration.</p>
						</div>
					</div>
					<div class="sv-feature">
						<span class="sv-feature__icon">✦</span>
						<div>
							<h4>Floral Design</h4>
							<p>Bespoke floral installations sourced from the finest florists, tailored to your color palette and vision.</p>
						</div>
					</div>
				</div>
				<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>" class="sv-detail__cta">Plan Your Event →</a>
			</div>
		</div>
	</section>

	<!-- ═══════════════════════════════════════════════
	     SERVICE DETAIL: 24/7 Concierge
	     ═══════════════════════════════════════════════ -->
	<section class="sv-detail" id="concierge">
		<div class="container sv-detail__inner sv-detail__inner--reverse">
			<div class="sv-detail__content">
				<span class="fp-eyebrow">Always Available</span>
				<h2 class="sv-detail__title">24/7 Concierge</h2>
				<p class="sv-detail__lead">Your personal assistant on the water. Seamless logistics, local expertise, and VIP access to the world's most exclusive shore-side clubs.</p>
				<div class="sv-detail__features">
					<div class="sv-feature">
						<span class="sv-feature__icon">✦</span>
						<div>
							<h4>VIP Access</h4>
							<p>Exclusive access to private clubs and restaurants in Mykonos, St. Tropez, Cannes, and beyond — reserved just for you.</p>
						</div>
					</div>
					<div class="sv-feature">
						<span class="sv-feature__icon">✦</span>
						<div>
							<h4>Port Logistics</h4>
							<p>Seamless marina bookings, customs clearance, and bunkering arranged before you arrive at every destination.</p>
						</div>
					</div>
					<div class="sv-feature">
						<span class="sv-feature__icon">✦</span>
						<div>
							<h4>Travel Transfers</h4>
							<p>Helicopter transfers, private jets, and limousines coordinated with precision from door to gangway.</p>
						</div>
					</div>
				</div>
				<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>" class="sv-detail__cta">Access Our Concierge →</a>
			</div>
			<div class="sv-detail__img-col">
				<div class="sv-detail__img sv-detail__img--concierge"></div>
			</div>
		</div>
	</section>

	<!-- ═══════════════════════════════════════════════
	     CTA STRIP
	     ═══════════════════════════════════════════════ -->
	<section class="sv-cta">
		<div class="container sv-cta__inner">
			<h2 class="sv-cta__title">Ready to Design Your Journey?</h2>
			<p class="sv-cta__desc">Contact our bespoke services team today to start tailoring your next maritime adventure.</p>
			<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>" class="btn-primary">Contact Our Concierge</a>
		</div>
	</section>

</main>

<style>
/* Services Page Styles */
.page-services { background: var(--secondary); }

/* Hero */
.sv-hero { position: relative; min-height: 30vh; display: flex; align-items: center; overflow: hidden; padding: 100px 0 50px; }
.sv-hero__bg { position: absolute; inset: 0; background: linear-gradient(135deg, #0a101a 0%, #0d1f35 100%); background-size: cover; background-position: center center; }
.sv-hero__bg::after { content: ''; position: absolute; inset: 0; background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23d9b230' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30v-4h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E"); }
.sv-hero__content { position: relative; z-index: 2; max-width: 800px; }
.sv-hero__title { font-family: var(--font-heading); font-size: clamp(42px, 6vw, 80px); font-weight: 400; color: var(--text-light); margin: 16px 0 24px; line-height: 1.1; }
.sv-hero__accent { color: var(--primary); }
.sv-hero__desc { font-size: 20px; color: var(--text-muted); line-height: 1.7; max-width: 600px; margin: 0; }

/* Intro */
.sv-intro { padding: 100px 0; }
.sv-intro__inner { display: grid; grid-template-columns: 1fr 1fr; gap: 80px; align-items: center; }
.sv-intro__title { font-family: var(--font-heading); font-size: clamp(32px, 4vw, 48px); color: var(--text-light); margin: 16px 0 24px; font-weight: 400; }
.sv-intro__text p { color: var(--text-muted); line-height: 1.8; margin-bottom: 40px; font-size: 17px; }
.sv-intro__img-wrap { position: relative; }
.sv-intro__img { height: 500px; border-radius: var(--radius-lg); }
.sv-intro__img--placeholder { background: linear-gradient(135deg, var(--surface) 0%, var(--surface-light) 100%); border: 1px solid var(--glass-border); }

/* Service Detail Sections */
.sv-detail { padding: 120px 0; }
.sv-detail--alt { background: var(--surface); }
.sv-detail__inner { display: grid; grid-template-columns: 1fr 1fr; gap: 100px; align-items: center; }
.sv-detail__inner--reverse { }
.sv-detail__img-col { position: relative; }
.sv-detail__img { height: 520px; border-radius: var(--radius-lg); position: relative; }
.sv-detail__img--chef { background: linear-gradient(135deg, #1a2a1a 0%, #2a3a2a 100%); border: 1px solid var(--glass-border); }
.sv-detail__img--watertoys { background: linear-gradient(135deg, #0a1528 0%, #152540 100%); border: 1px solid var(--glass-border); }
.sv-detail__img--events { background: linear-gradient(135deg, #1a1a2a 0%, #2a2a3a 100%); border: 1px solid var(--glass-border); }
.sv-detail__img--concierge { background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%); border: 1px solid var(--glass-border); }
.sv-detail__badge { position: absolute; bottom: -20px; right: 30px; background: var(--primary); color: #000; padding: 16px 24px; border-radius: var(--radius); font-weight: 700; font-size: 13px; display: flex; align-items: center; gap: 8px; }
.sv-detail__title { font-family: var(--font-heading); font-size: clamp(32px, 4vw, 48px); color: var(--text-light); margin: 16px 0 24px; font-weight: 400; }
.sv-detail__lead { font-size: 18px; color: var(--text-muted); line-height: 1.8; margin-bottom: 40px; }
.sv-detail__features { display: flex; flex-direction: column; gap: 28px; margin-bottom: 40px; }
.sv-feature { display: flex; gap: 16px; align-items: flex-start; }
.sv-feature__icon { color: var(--primary); font-size: 10px; margin-top: 6px; flex-shrink: 0; }
.sv-feature h4 { font-family: var(--font-heading); font-size: 18px; color: var(--text-light); margin: 0 0 6px; font-weight: 600; }
.sv-feature p { color: var(--text-muted); line-height: 1.7; margin: 0; font-size: 15px; }
.sv-detail__cta { color: var(--primary); font-weight: 600; font-size: 15px; letter-spacing: 0.02em; transition: all var(--transition-fast); text-decoration: none; }
.sv-detail__cta:hover { color: var(--primary-hover); letter-spacing: 0.04em; }

/* CTA Strip */
.sv-cta { padding: 120px 0; background: linear-gradient(135deg, #0a1628 0%, #0d1f35 100%); text-align: center; position: relative; overflow: hidden; }
.sv-cta::before { content: ''; position: absolute; top: -50%; left: 50%; transform: translate(-50%, 0); width: 800px; height: 800px; background: radial-gradient(circle, rgba(217,178,48,0.05) 0%, transparent 60%); border-radius: 50%; pointer-events: none; }
.sv-cta__inner { position: relative; z-index: 2; max-width: 700px; margin: 0 auto; }
.sv-cta__title { font-family: var(--font-heading); font-size: clamp(32px, 4vw, 52px); color: var(--text-light); margin-bottom: 20px; font-weight: 400; }
.sv-cta__desc { font-size: 18px; color: var(--text-muted); margin-bottom: 40px; line-height: 1.7; }

@media (max-width: 1024px) {
	.sv-intro__inner, .sv-detail__inner { grid-template-columns: 1fr; gap: 60px; }
	.sv-detail__inner--reverse .sv-detail__content { order: -1; }
}
@media (max-width: 768px) {
	.sv-hero { padding: 120px 0 60px; }
}
</style>

<?php get_footer(); ?>
