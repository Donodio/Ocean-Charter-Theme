<?php
/**
 * Template Name: Sample Itinerary
 * Ocean Charter - Itinerary - Faithful to Stitch "Ocean Charter - Itinerary" design
 *
 * @package OceanCharter
 */

get_header();

// If this page has Elementor-built content, render it and bail out
if ( have_posts() ) {
	the_post();
	if ( get_post_meta( get_the_ID(), '_elementor_edit_mode', true ) === 'builder' ) {
		echo '<main id="main" class="oc-page oc-page--itinerary">';
		the_content();
		echo '</main>';
		get_footer();
		exit;
	}
}

// ── Image constants with fallbacks ───────────────────────────────────────────
$hero_img   = defined( 'OC_IMG_HERO_ITINERARY' ) ? OC_IMG_HERO_ITINERARY : 'https://images.pexels.com/photos/1285625/pexels-photo-1285625.jpeg?auto=compress&cs=tinysrgb&w=1920';
$it_imgs    = [
	1 => defined( 'OC_IMG_ITINERARY_1' ) ? OC_IMG_ITINERARY_1 : 'https://images.pexels.com/photos/1285624/pexels-photo-1285624.jpeg?auto=compress&cs=tinysrgb&w=800',
	2 => defined( 'OC_IMG_ITINERARY_2' ) ? OC_IMG_ITINERARY_2 : 'https://images.pexels.com/photos/1430676/pexels-photo-1430676.jpeg?auto=compress&cs=tinysrgb&w=800',
	3 => defined( 'OC_IMG_ITINERARY_3' ) ? OC_IMG_ITINERARY_3 : 'https://images.pexels.com/photos/1533721/pexels-photo-1533721.jpeg?auto=compress&cs=tinysrgb&w=800',
	4 => defined( 'OC_IMG_ITINERARY_4' ) ? OC_IMG_ITINERARY_4 : 'https://images.pexels.com/photos/1268856/pexels-photo-1268856.jpeg?auto=compress&cs=tinysrgb&w=800',
	5 => defined( 'OC_IMG_ITINERARY_5' ) ? OC_IMG_ITINERARY_5 : 'https://images.pexels.com/photos/1705255/pexels-photo-1705255.jpeg?auto=compress&cs=tinysrgb&w=800',
];
$med_img    = defined( 'OC_IMG_DEST_MEDITERRANEAN' ) ? OC_IMG_DEST_MEDITERRANEAN : 'https://images.pexels.com/photos/1010657/pexels-photo-1010657.jpeg?auto=compress&cs=tinysrgb&w=800';

$whatsapp_url = function_exists( 'oc_whatsapp_url' ) ? oc_whatsapp_url( 'Hi, I would like to book the 7-Day Aegean Itinerary.' ) : 'https://wa.me/15551234567';

// ── Day data ──────────────────────────────────────────────────────────────────
$days = [
	[
		'day'      => 'Day 1',
		'label'    => 'Departure — Athens, Greece',
		'short'    => 'Athens',
		'desc'     => '<p>Your adventure begins in Athens, where you will board your private yacht at Piraeus Marina. After a welcome briefing and champagne toast with your expert crew, you will set sail toward the crystal-clear waters of the Saronic Gulf, watching the ancient city fade gently into the horizon.</p><p>Anchor in a secluded cove for sunset cocktails before dining under the stars — a breathtaking overture to seven days of maritime luxury.</p>',
		'img_a'    => $hero_img,
		'img_b'    => $med_img,
	],
	[
		'day'      => 'Day 2',
		'label'    => 'Santorini — The Caldera',
		'short'    => 'Santorini',
		'desc'     => '<p>Arrive at the world-famous caldera of Santorini at dawn, when the whitewashed clifftop villages glow amber in the early light. Tender ashore to explore Oia\'s cobbled streets and boutique galleries before returning to the yacht for a freshly prepared Aegean lunch.</p><p>In the afternoon, snorkel the volcanic hot springs at Nea Kameni before watching Santorini\'s legendary sunset from the deck &mdash; one of the most photographed moments on earth, yours exclusively.</p>',
		'img_a'    => $it_imgs[1],
		'img_b'    => $it_imgs[2],
	],
	[
		'day'      => 'Day 3',
		'label'    => 'Mykonos — Cosmopolitan Energy',
		'short'    => 'Mykonos',
		'desc'     => '<p>Sail north to Mykonos, arriving by mid-morning at the iconic windmill peninsula. Stroll the labyrinthine lanes of Mykonos Town, browsing designer boutiques and stopping for a frappe at a waterfront caf&eacute; overlooking Little Venice.</p><p>Your concierge will secure a private table at a rooftop beach club for the afternoon. Return to the yacht as the island\'s legendary nightlife awakens &mdash; cocktails on deck while the distant beat carries softly across the water.</p>',
		'img_a'    => $it_imgs[3],
		'img_b'    => $it_imgs[4],
	],
	[
		'day'      => 'Day 4',
		'label'    => 'Delos — Sacred Island',
		'short'    => 'Delos',
		'desc'     => '<p>A short sail from Mykonos brings you to Delos, the mythological birthplace of Apollo and Artemis. Walk among the remarkably preserved ruins — marble lions, ancient mosaics, and the Avenue of Lions — with your private guide bringing the 3,000-year-old stories to life.</p><p>Picnic lunch is served aboard, anchored in a quiet bay with views of the uninhabited island. The afternoon is yours for swimming in the pellucid water, paddleboarding, or simply reading on the sun deck.</p>',
		'img_a'    => $it_imgs[2],
		'img_b'    => $it_imgs[1],
	],
	[
		'day'      => 'Day 5',
		'label'    => 'Paros — Golden Villages',
		'short'    => 'Paros',
		'desc'     => '<p>Head south to Paros, anchoring off the golden-hued village of Naoussa. Wander the narrow lanes lined with bougainvillea and traditional blue-door churches. Your chef will source the day\'s ingredients here &mdash; locally caught fish, sun-ripened tomatoes, and Parian marble-white goat cheese.</p><p>As afternoon rolls in, launch the jet skis and water toys for an exhilarating session in the sheltered bay. Dinner al fresco on the aft deck as stars multiply above you.</p>',
		'img_a'    => $it_imgs[4],
		'img_b'    => $it_imgs[5],
	],
	[
		'day'      => 'Day 6',
		'label'    => 'Hydra — Car-Free Elegance',
		'short'    => 'Hydra',
		'desc'     => '<p>Sail west to Hydra, the car-free island of donkeys and stone mansions beloved by artists and writers. Stroll along the horseshoe harbour and climb to the monastery for panoramic views over the Saronic Gulf. Time seems to slow here — intentionally.</p><p>Afternoon is dedicated to a full spa session aboard the yacht: deep-tissue massage, facial, and a restorative steam bath. Farewell dinner features the finest mezze and chilled Assyrtiko wine — a final toast to an unforgettable journey.</p>',
		'img_a'    => $it_imgs[3],
		'img_b'    => $med_img,
	],
	[
		'day'      => 'Day 7',
		'label'    => 'Return — Athens, Greece',
		'short'    => 'Athens Return',
		'desc'     => '<p>Your final morning at sea. As the yacht cruises back toward Piraeus, savour a leisurely breakfast on deck watching the Greek coastline slide by. The crew will have your luggage packed and your transfers arranged by your concierge.</p><p>Disembark with memories to last a lifetime — and already wondering when you will return to these extraordinary waters.</p>',
		'img_a'    => $hero_img,
		'img_b'    => $it_imgs[1],
	],
];
?>

<main id="primary" class="site-main page-itinerary">

	<!-- ═══════════════════════════════════════════════
	     PAGE HERO
	     ═══════════════════════════════════════════════ -->
	<?php
	$_pid      = get_the_ID();
	$_hero_h   = get_post_meta( $_pid, '_oc_hero_height',  true ) ?: '55vh';
	$_hero_op  = get_post_meta( $_pid, '_oc_hero_opacity', true );
	$_hero_op  = ( $_hero_op !== '' ) ? floatval( $_hero_op ) : 0.65;
	$_hero_col = get_post_meta( $_pid, '_oc_hero_color',   true ) ?: '#0a0f1a';
	list( $_r, $_g, $_b ) = sscanf( $_hero_col, '#%02x%02x%02x' );
	$_overlay_css = "background:rgba({$_r},{$_g},{$_b},{$_hero_op});";
	$_hero_pos = get_post_meta( $_pid, '_oc_hero_position', true ) ?: 'center center';
	?>
	<section class="it-hero" style="min-height:<?php echo esc_attr( $_hero_h ); ?>;background-image:url('<?php echo esc_url( $hero_img ); ?>');background-size:cover;background-position:<?php echo esc_attr( $_hero_pos ); ?>;">
		<div class="it-hero__overlay" style="<?php echo esc_attr( $_overlay_css ); ?>"></div>
		<div class="container it-hero__content">
			<span class="fp-eyebrow" data-animate data-delay="0">Luxury Sailing</span>
			<h1 class="it-hero__title" data-animate data-delay="0.15">Sample <span class="it-hero__accent">Itinerary</span></h1>
			<p class="it-hero__sub" data-animate data-delay="0.3">A 7-Day Aegean Journey</p>
			<div class="it-hero__meta" data-animate data-delay="0.45">
				<span class="it-hero__badge"><svg viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M10 2a8 8 0 1 0 0 16A8 8 0 0 0 10 2Zm1 4.5v4l3 2-1 1.5L9 12V6.5h2Z" fill="currentColor"/></svg>7 Days</span>
				<span class="it-hero__badge"><svg viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M10 2C7.24 2 5 4.24 5 7c0 3.75 5 11 5 11s5-7.25 5-11c0-2.76-2.24-5-5-5Zm0 6.5A1.5 1.5 0 1 1 10 5a1.5 1.5 0 0 1 0 3Z" fill="currentColor"/></svg>Greek Islands</span>
				<span class="it-hero__badge"><svg viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M10 2a1 1 0 0 1 .9.55l7 14A1 1 0 0 1 17 18H3a1 1 0 0 1-.9-1.45l7-14A1 1 0 0 1 10 2Zm1 11H9v2h2v-2Zm0-6H9v5h2V7Z" fill="currentColor"/></svg>All-inclusive</span>
			</div>
		</div>
	</section>

	<!-- ═══════════════════════════════════════════════
	     MAIN LAYOUT: TIMELINE + SIDEBAR
	     ═══════════════════════════════════════════════ -->
	<section class="it-body">
		<div class="container it-body__inner">

			<!-- ── LEFT: Day Timeline ── -->
			<div class="it-timeline" role="main">
				<p class="it-timeline__intro">Follow your private yacht through seven of the most spectacular islands in the world. Every detail curated, every moment extraordinary.</p>

				<?php foreach ( $days as $i => $d ) :
					$delay = number_format( $i * 0.15, 2 );
				?>
				<article class="it-day" id="day-<?php echo $i + 1; ?>" data-animate data-delay="<?php echo esc_attr( $delay ); ?>">
					<div class="it-day__marker">
						<span class="it-day__circle"><?php echo $i + 1; ?></span>
						<div class="it-day__line" aria-hidden="true"></div>
					</div>
					<div class="it-day__content">
						<div class="it-day__header">
							<span class="it-day__label"><?php echo esc_html( $d['day'] ); ?></span>
							<h2 class="it-day__location"><?php echo esc_html( $d['label'] ); ?></h2>
						</div>
						<div class="it-day__desc">
							<?php echo wp_kses_post( $d['desc'] ); ?>
						</div>
						<div class="it-day__photos">
							<figure class="it-day__photo">
								<img src="<?php echo esc_url( $d['img_a'] ); ?>" alt="<?php echo esc_attr( $d['label'] ) . ' — morning'; ?>" loading="lazy" width="600" height="400">
							</figure>
							<figure class="it-day__photo">
								<img src="<?php echo esc_url( $d['img_b'] ); ?>" alt="<?php echo esc_attr( $d['label'] ) . ' — afternoon'; ?>" loading="lazy" width="600" height="400">
							</figure>
						</div>
					</div>
				</article>
				<?php endforeach; ?>
			</div><!-- .it-timeline -->

			<!-- ── RIGHT: Sticky Sidebar ── -->
			<aside class="it-sidebar" aria-label="Itinerary summary">
				<div class="it-sidebar__inner">

					<!-- Day Summary List -->
					<div class="it-sidebar__block">
						<h3 class="it-sidebar__heading">Itinerary at a Glance</h3>
						<ol class="it-sidebar__daylist">
							<?php foreach ( $days as $i => $d ) : ?>
							<li class="it-sidebar__dayitem">
								<a href="#day-<?php echo $i + 1; ?>">
									<span class="it-sidebar__day-num">Day <?php echo $i + 1; ?></span>
									<span class="it-sidebar__day-dest"><?php echo esc_html( $d['short'] ); ?></span>
								</a>
							</li>
							<?php endforeach; ?>
						</ol>
					</div>

					<!-- Price Estimate -->
					<div class="it-sidebar__block it-sidebar__price">
						<div class="it-sidebar__price-badge">
							<span class="it-sidebar__price-from">Starting from</span>
							<span class="it-sidebar__price-amount">$18,500</span>
							<span class="it-sidebar__price-period">for 7 days (yacht only)</span>
						</div>
						<p class="it-sidebar__price-note">Price varies by vessel selection and season. Includes crew, fuel, and all onboard provisions.</p>
					</div>

					<!-- Book CTA -->
					<div class="it-sidebar__block it-sidebar__ctas">
						<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn-primary it-sidebar__btn">
							Book This Itinerary
						</a>
						<a href="<?php echo esc_url( $whatsapp_url ); ?>" class="it-sidebar__wa" target="_blank" rel="noopener noreferrer" aria-label="Enquire on WhatsApp">
							<svg class="it-sidebar__wa-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
							Chat on WhatsApp
						</a>
					</div>

					<!-- Inclusions -->
					<div class="it-sidebar__block">
						<h3 class="it-sidebar__heading">What's Included</h3>
						<ul class="it-sidebar__includes">
							<li><svg viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M13 4L6.5 11 3 7.5" stroke="#d9b230" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>Dedicated crew (captain + chef)</li>
							<li><svg viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M13 4L6.5 11 3 7.5" stroke="#d9b230" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>All meals &amp; beverages</li>
							<li><svg viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M13 4L6.5 11 3 7.5" stroke="#d9b230" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>Fuel &amp; marina fees</li>
							<li><svg viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M13 4L6.5 11 3 7.5" stroke="#d9b230" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>Water sports equipment</li>
							<li><svg viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M13 4L6.5 11 3 7.5" stroke="#d9b230" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>Private guide at Delos</li>
							<li><svg viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M13 4L6.5 11 3 7.5" stroke="#d9b230" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>24/7 concierge service</li>
						</ul>
					</div>

				</div><!-- .it-sidebar__inner -->
			</aside><!-- .it-sidebar -->

		</div><!-- .it-body__inner -->
	</section><!-- .it-body -->

	<!-- ═══════════════════════════════════════════════
	     CTA STRIP
	     ═══════════════════════════════════════════════ -->
	<section class="it-cta-strip" data-animate>
		<div class="container it-cta-strip__inner">
			<div class="it-cta-strip__text">
				<h2 class="it-cta-strip__heading">Design Your <span class="it-hero__accent">Own Itinerary</span></h2>
				<p class="it-cta-strip__sub">This is one example of the experiences we craft. Tell us your dream destination and we will create an itinerary tailored entirely to you.</p>
			</div>
			<div class="it-cta-strip__actions">
				<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn-primary">Plan My Journey</a>
				<a href="<?php echo esc_url( $whatsapp_url ); ?>" class="btn-secondary" target="_blank" rel="noopener noreferrer">WhatsApp Us</a>
			</div>
		</div>
	</section>

</main><!-- #primary -->

<style>
/* ── Itinerary Hero ─────────────────────────────────────────────────────────── */
.it-hero {
	position: relative;
	min-height: 35vh;
	display: flex;
	align-items: flex-end;
	background-size: cover;
	background-position: center;
	padding-bottom: clamp(3rem, 6vw, 5rem);
}
.it-hero__overlay {
	position: absolute;
	inset: 0;
	background: linear-gradient(to bottom, rgba(10,16,26,0.25) 0%, rgba(10,16,26,0.75) 100%);
}
.it-hero__content {
	position: relative;
	z-index: 1;
	max-width: 680px;
}
.it-hero__title {
	font-family: var(--font-heading);
	font-size: clamp(2.8rem, 5vw, 4.5rem);
	color: var(--text);
	margin: 0.3em 0 0.4em;
	line-height: 1.1;
}
.it-hero__accent {
	color: var(--primary);
}
.it-hero__sub {
	font-family: var(--font-body);
	font-size: clamp(1.05rem, 2vw, 1.3rem);
	color: var(--text-muted);
	margin-bottom: 1.5rem;
}
.it-hero__meta {
	display: flex;
	gap: 1rem;
	flex-wrap: wrap;
}
.it-hero__badge {
	display: inline-flex;
	align-items: center;
	gap: 0.4em;
	background: var(--glass-bg);
	border: 1px solid var(--glass-border);
	backdrop-filter: blur(10px);
	border-radius: var(--radius-pill);
	padding: 0.45em 1em;
	font-size: 0.8rem;
	font-weight: 600;
	letter-spacing: 0.06em;
	color: var(--text);
	text-transform: uppercase;
}
.it-hero__badge svg {
	width: 14px;
	height: 14px;
	color: var(--primary);
}

/* ── Body Layout ─────────────────────────────────────────────────────────────── */
.it-body {
	background: var(--secondary);
	padding: clamp(3rem, 6vw, 5rem) 0;
}
.it-body__inner {
	display: grid;
	grid-template-columns: 1fr 340px;
	gap: clamp(2rem, 4vw, 4rem);
	align-items: start;
}

/* ── Timeline ────────────────────────────────────────────────────────────────── */
.it-timeline__intro {
	color: var(--text-muted);
	font-size: 1.05rem;
	line-height: 1.8;
	max-width: 600px;
	margin-bottom: clamp(2rem, 4vw, 3rem);
}
.it-day {
	display: grid;
	grid-template-columns: 56px 1fr;
	gap: 0 1.5rem;
	margin-bottom: clamp(2.5rem, 5vw, 4rem);
}
.it-day__marker {
	display: flex;
	flex-direction: column;
	align-items: center;
}
.it-day__circle {
	width: 48px;
	height: 48px;
	border-radius: 50%;
	border: 2px solid var(--primary);
	background: var(--surface);
	display: flex;
	align-items: center;
	justify-content: center;
	font-family: var(--font-heading);
	font-size: 1.05rem;
	font-weight: 700;
	color: var(--primary);
	flex-shrink: 0;
	z-index: 1;
}
.it-day__line {
	width: 2px;
	flex: 1;
	background: var(--border);
	margin-top: 8px;
}
.it-day:last-child .it-day__line {
	display: none;
}
.it-day__header {
	margin-bottom: 0.75rem;
}
.it-day__label {
	display: block;
	font-size: 0.7rem;
	font-weight: 700;
	letter-spacing: 0.14em;
	text-transform: uppercase;
	color: var(--primary);
	margin-bottom: 0.3em;
}
.it-day__location {
	font-family: var(--font-heading);
	font-size: clamp(1.3rem, 2vw, 1.6rem);
	color: var(--text);
	font-weight: 600;
	margin: 0;
}
.it-day__desc p {
	color: var(--text-muted);
	line-height: 1.8;
	margin-bottom: 0.75em;
}
.it-day__photos {
	display: grid;
	grid-template-columns: 1fr 1fr;
	gap: 0.75rem;
	margin-top: 1.25rem;
}
.it-day__photo {
	margin: 0;
	overflow: hidden;
	border-radius: var(--radius);
	aspect-ratio: 4/3;
}
.it-day__photo img {
	width: 100%;
	height: 100%;
	object-fit: cover;
	display: block;
	transition: transform var(--transition);
}
.it-day__photo:hover img {
	transform: scale(1.04);
}

/* ── Sidebar ─────────────────────────────────────────────────────────────────── */
.it-sidebar__inner {
	position: sticky;
	top: 100px;
	display: flex;
	flex-direction: column;
	gap: 1.25rem;
}
.it-sidebar__block {
	background: var(--surface);
	border: 1px solid var(--border);
	border-radius: var(--radius-lg);
	padding: 1.5rem;
}
.it-sidebar__heading {
	font-family: var(--font-heading);
	font-size: 1rem;
	font-weight: 700;
	color: var(--text);
	margin: 0 0 1rem;
	letter-spacing: 0.02em;
}
.it-sidebar__daylist {
	list-style: none;
	margin: 0;
	padding: 0;
	display: flex;
	flex-direction: column;
	gap: 0.1rem;
}
.it-sidebar__dayitem a {
	display: flex;
	justify-content: space-between;
	align-items: center;
	padding: 0.6rem 0.75rem;
	border-radius: var(--radius);
	text-decoration: none;
	transition: background var(--transition);
}
.it-sidebar__dayitem a:hover {
	background: rgba(217,178,48,0.08);
}
.it-sidebar__day-num {
	font-size: 0.75rem;
	font-weight: 700;
	text-transform: uppercase;
	letter-spacing: 0.08em;
	color: var(--primary);
	min-width: 46px;
}
.it-sidebar__day-dest {
	font-size: 0.875rem;
	color: var(--text-muted);
	text-align: right;
}
/* Price block */
.it-sidebar__price {
	background: linear-gradient(135deg, var(--surface) 0%, rgba(217,178,48,0.06) 100%);
}
.it-sidebar__price-badge {
	text-align: center;
	padding: 1rem 0 0.75rem;
}
.it-sidebar__price-from {
	display: block;
	font-size: 0.7rem;
	font-weight: 700;
	letter-spacing: 0.12em;
	text-transform: uppercase;
	color: var(--text-muted);
	margin-bottom: 0.25rem;
}
.it-sidebar__price-amount {
	display: block;
	font-family: var(--font-heading);
	font-size: 2rem;
	font-weight: 700;
	color: var(--primary);
	line-height: 1;
}
.it-sidebar__price-period {
	display: block;
	font-size: 0.8rem;
	color: var(--text-muted);
	margin-top: 0.3rem;
}
.it-sidebar__price-note {
	font-size: 0.8rem;
	color: var(--text-muted);
	line-height: 1.6;
	margin: 0.75rem 0 0;
	padding-top: 0.75rem;
	border-top: 1px solid var(--border);
}
/* CTA block */
.it-sidebar__ctas {
	display: flex;
	flex-direction: column;
	gap: 0.75rem;
	padding: 1.25rem;
}
.it-sidebar__btn {
	display: block;
	text-align: center;
	width: 100%;
}
.it-sidebar__wa {
	display: flex;
	align-items: center;
	justify-content: center;
	gap: 0.5rem;
	padding: 0.75em 1.5em;
	border-radius: var(--radius-pill);
	border: 1px solid #25D366;
	color: #25D366;
	font-size: 0.875rem;
	font-weight: 600;
	text-decoration: none;
	transition: background var(--transition), color var(--transition);
}
.it-sidebar__wa:hover {
	background: #25D366;
	color: #fff;
}
.it-sidebar__wa-icon {
	width: 18px;
	height: 18px;
	flex-shrink: 0;
}
/* Inclusions */
.it-sidebar__includes {
	list-style: none;
	margin: 0;
	padding: 0;
	display: flex;
	flex-direction: column;
	gap: 0.6rem;
}
.it-sidebar__includes li {
	display: flex;
	align-items: flex-start;
	gap: 0.6em;
	font-size: 0.875rem;
	color: var(--text-muted);
	line-height: 1.4;
}
.it-sidebar__includes svg {
	width: 16px;
	height: 16px;
	flex-shrink: 0;
	margin-top: 0.1em;
}

/* ── CTA Strip ───────────────────────────────────────────────────────────────── */
.it-cta-strip {
	background: var(--surface);
	border-top: 1px solid var(--border);
	padding: clamp(3rem, 6vw, 5rem) 0;
}
.it-cta-strip__inner {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 2rem;
	flex-wrap: wrap;
}
.it-cta-strip__heading {
	font-family: var(--font-heading);
	font-size: clamp(1.8rem, 3vw, 2.6rem);
	color: var(--text);
	margin: 0 0 0.5rem;
}
.it-cta-strip__heading em {
	color: var(--primary);
	font-style: italic;
}
.it-cta-strip__sub {
	color: var(--text-muted);
	max-width: 540px;
	line-height: 1.7;
	margin: 0;
}
.it-cta-strip__actions {
	display: flex;
	gap: 1rem;
	flex-wrap: wrap;
	flex-shrink: 0;
}

/* ── Responsive ──────────────────────────────────────────────────────────────── */
@media (max-width: 1024px) {
	.it-body__inner {
		grid-template-columns: 1fr;
	}
	.it-sidebar__inner {
		position: static;
	}
	.it-sidebar {
		order: -1;
	}
	/* Move sidebar above timeline on mobile as a collapsed summary */
	.it-sidebar__daylist {
		display: grid;
		grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
		gap: 0.5rem;
	}
	.it-sidebar__dayitem a {
		flex-direction: column;
		align-items: flex-start;
		gap: 0.1rem;
	}
	.it-sidebar__day-dest {
		text-align: left;
	}
}
@media (max-width: 768px) {
	.it-hero {
		min-height: 30vh;
	}
	.it-day {
		grid-template-columns: 44px 1fr;
		gap: 0 1rem;
	}
	.it-day__photos {
		grid-template-columns: 1fr;
	}
	.it-cta-strip__inner {
		flex-direction: column;
		text-align: center;
	}
	.it-cta-strip__actions {
		justify-content: center;
	}
	.it-hero__meta {
		gap: 0.6rem;
	}
}
@media (max-width: 480px) {
	.it-day__circle {
		width: 38px;
		height: 38px;
		font-size: 0.9rem;
	}
	.it-sidebar__daylist {
		grid-template-columns: 1fr 1fr;
	}
}
</style>

<?php get_footer(); ?>
