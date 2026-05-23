<?php
/**
 * Yacht Details — Ocean Charter Theme
 * Faithful to Stitch "Ocean Charter - Yacht Details" ("The Azure Muse") design
 *
 * @package OceanCharter
 */

get_header();

$boat_id = get_the_ID();

/* ── Helper: fetch BBC meta with fallback keys ── */
if ( ! function_exists( 'oc_meta' ) ) :
function oc_meta( $id, ...$keys ) {
	foreach ( $keys as $k ) {
		$v = get_post_meta( $id, $k, true );
		if ( $v ) return $v;
	}
	return '';
}
endif;

/* ── Boat Detail Settings ── */
$detail_settings = get_option( 'bbc_boat_detail_settings', array() );
$sections_order  = ! empty( $detail_settings['sections_order'] )
	? array_map( 'sanitize_key', $detail_settings['sections_order'] )
	: array( 'description', 'amenities', 'captain', 'reviews' );
$hidden_sections = ! empty( $detail_settings['hidden_sections'] )
	? array_map( 'sanitize_key', $detail_settings['hidden_sections'] )
	: array();
$show_related    = empty( $detail_settings['hide_related'] );
$show_specs_bar  = empty( $detail_settings['hide_specs_bar'] );

/* ── Boat Meta ── */
$length      = oc_meta( $boat_id, '_bbc_length',      '_boat_length' );
$guests      = oc_meta( $boat_id, '_bbc_max_guests',  '_boat_guests' );
$cabins      = oc_meta( $boat_id, '_bbc_cabins',      '_boat_cabins' );
$location    = oc_meta( $boat_id, '_bbc_location',    '_boat_location' );
$price_hour  = oc_meta( $boat_id, '_bbc_price_hour',  '_boat_hourly_rate' );
$price_day   = oc_meta( $boat_id, '_bbc_price_day',   '_boat_daily_rate' );
$price_week  = oc_meta( $boat_id, '_bbc_price_week',  '_boat_weekly_rate' );
$year        = oc_meta( $boat_id, '_bbc_year_built',  '_boat_year' );
$builder     = oc_meta( $boat_id, '_bbc_builder',     '_boat_builder' );
$speed       = oc_meta( $boat_id, '_bbc_max_speed',   '_boat_max_speed' );
$crew        = oc_meta( $boat_id, '_bbc_crew',        '_boat_crew' );
$beam        = oc_meta( $boat_id, '_bbc_beam',        '_boat_beam' );
$bathrooms   = oc_meta( $boat_id, '_bbc_bathrooms' );
$berths      = oc_meta( $boat_id, '_bbc_berths' );
$boat_type   = oc_meta( $boat_id, '_bbc_boat_type' );
$captain_inc = oc_meta( $boat_id, '_bbc_captain_included' );
$fuel_inc    = oc_meta( $boat_id, '_bbc_fuel_included' );
$captain_name = oc_meta( $boat_id, '_boat_captain_name' );
$captain_bio  = oc_meta( $boat_id, '_boat_captain_bio' );
$condition   = oc_meta( $boat_id, '_bbc_condition' );
$amenities   = get_post_meta( $boat_id, '_bbc_amenities', true );
if ( empty( $amenities ) ) $amenities = get_post_meta( $boat_id, '_boat_amenities', true );
if ( is_string( $amenities ) && ! empty( $amenities ) ) {
	$amenities = array_filter( array_map( 'trim', explode( ',', $amenities ) ) );
}

/* ── Gallery Images ── */
$gallery_ids = get_post_meta( $boat_id, '_bbc_gallery', true );
if ( is_string( $gallery_ids ) && ! empty( $gallery_ids ) ) {
	$gallery_ids = array_filter( array_map( 'intval', explode( ',', $gallery_ids ) ) );
}
if ( ! is_array( $gallery_ids ) ) $gallery_ids = array();
$featured_id = get_post_thumbnail_id( $boat_id );

/* ── Reviews ── */
global $wpdb;
$reviews_table = $wpdb->prefix . 'bbc_reviews';
$reviews = $wpdb->get_results( $wpdb->prepare(
	"SELECT guest_name, rating, title, review, created_at FROM {$reviews_table} WHERE boat_id = %d AND status = 'approved' ORDER BY created_at DESC LIMIT 6",
	$boat_id
) );
$avg_rating   = $wpdb->get_var( $wpdb->prepare(
	"SELECT ROUND(AVG(rating),1) FROM {$reviews_table} WHERE boat_id = %d AND status = 'approved'",
	$boat_id
) );
$review_count = count( $reviews );

/* ── Amenity slug → human label map (from BBC MetaBoxes) ── */
$amenity_labels = array(
	'gps' => 'GPS/Chart Plotter', 'radar' => 'Radar', 'autopilot' => 'Autopilot',
	'vhf_radio' => 'VHF Radio', 'depth_sounder' => 'Depth Sounder', 'wind_instrument' => 'Wind Instrument',
	'compass' => 'Compass', 'satnav' => 'Satellite Navigation', 'bow_thruster' => 'Bow Thruster',
	'stern_thruster' => 'Stern Thruster', 'chartplotter' => 'Chart Plotter', 'gps_plotter' => 'GPS Plotter',
	'satellite_phone' => 'Satellite Phone', 'fish_finder' => 'Fish Finder',
	'ac' => 'Air Conditioning', 'airconditioning' => 'Air Conditioning', 'aircon' => 'Air Con',
	'heating' => 'Heating', 'generator' => 'Generator', 'hot_water' => 'Hot Water',
	'jacuzzi' => 'Jacuzzi', 'shower' => 'Outdoor Shower', 'outsideshower' => 'Outside Shower',
	'transom_shower' => 'Transom Shower', 'sun_bed' => 'Sun Bed', 'bimini' => 'Bimini Top',
	'flybridge' => 'Flybridge', 'cushion' => 'Cushions', 'hair_dryer' => 'Hair Dryer',
	'bath' => 'Bath', 'toilet' => 'Toilet',
	'tv' => 'TV/DVD', 'samsungtv' => 'Smart TV', 'wifi' => 'WiFi',
	'sound_system' => 'Sound System', 'speakers' => 'Exterior Speakers', 'mp3' => 'MP3 Player',
	'cdplayer' => 'CD Player', 'cd' => 'CD Player', 'bluetooth' => 'Bluetooth', 'usb' => 'USB Ports',
	'kayak' => 'Kayak', 'paddleboard' => 'Paddleboard', 'snorkel_gear' => 'Snorkeling Gear',
	'diving_mask' => 'Diving Mask', 'fishing_gear' => 'Fishing Gear', 'tender' => 'Dinghy',
	'dinghy_engine' => 'Dinghy with Engine', 'outboard' => 'Outboard Engine', 'jet_ski' => 'Jet Ski',
	'water_ski' => 'Water Skis', 'tube' => 'Tube', 'seabob' => 'Sea Bob',
	'gennaker' => 'Gennaker', 'spinnaker' => 'Spinnaker', 'canon' => 'Cannon',
	'kitchen' => 'Kitchen/Galley', 'refrigerator' => 'Refrigerator', 'freezer' => 'Freezer',
	'microwave' => 'Microwave', 'coffee_maker' => 'Coffee Maker', 'stove' => 'Stove',
	'ice_maker' => 'Ice Maker', 'grill' => 'BBQ/Grill', 'bbq' => 'BBQ', 'dishwasher' => 'Dishwasher',
	'swim_platform' => 'Swim Platform', 'swim_ladder' => 'Swim Ladder', 'hydraulic' => 'Hydraulic Platform',
	'anchor' => 'Anchor', 'electric_anchor' => 'Electric Anchor', 'spare_anchor' => 'Spare Anchor',
	'anchor_chain' => 'Anchor Chain', 'electric_winch' => 'Electric Winch',
	'safety_equipment' => 'Safety Equipment', 'life_jackets' => 'Life Jackets', 'first_aid' => 'First Aid Kit',
	'holdingtank' => 'Holding Tank', 'watermaker' => 'Water Maker', 'inverter' => 'Inverter',
	'solarpanel' => 'Solar Panels', 'radararch' => 'Radar Arch', 'teak' => 'Teak Deck',
	'cockpittable' => 'Cockpit Table', 'dining_table' => 'Dining Table', 'bath_towels' => 'Bath Towels',
	'bed_linen' => 'Bed Linen', 'soft_drinks' => 'Soft Drinks', 'ice' => 'Ice',
	'spare_fuel' => 'Spare Fuel', 'bin' => 'Bin', 'crew' => 'Crew Available',
	'skipper' => 'Skipper Included', 'fuel_included' => 'Fuel Included',
	'washing_machine' => 'Washing Machine', 'dryer' => 'Dryer',
);

/* ── Amenity slug → icon map ── */
$amenity_icons = array(
	'gps' => 'gps_fixed', 'radar' => 'radar', 'autopilot' => 'assistant_navigation',
	'vhf_radio' => 'settings_input_antenna', 'depth_sounder' => 'water', 'wind_instrument' => 'air',
	'compass' => 'explore', 'satnav' => 'satellite_alt', 'bow_thruster' => 'swap_horiz',
	'stern_thruster' => 'swap_horiz', 'chartplotter' => 'map', 'gps_plotter' => 'pin_drop',
	'satellite_phone' => 'phone_in_talk', 'fish_finder' => 'phishing',
	'ac' => 'ac_unit', 'airconditioning' => 'ac_unit', 'aircon' => 'ac_unit',
	'heating' => 'thermostat', 'generator' => 'bolt', 'hot_water' => 'water_drop',
	'jacuzzi' => 'hot_tub', 'shower' => 'shower', 'outsideshower' => 'shower',
	'transom_shower' => 'shower', 'sun_bed' => 'wb_sunny', 'bimini' => 'wb_shade',
	'flybridge' => 'deck', 'cushion' => 'weekend', 'hair_dryer' => 'dry',
	'bath' => 'bathtub', 'toilet' => 'bathroom',
	'tv' => 'tv', 'samsungtv' => 'connected_tv', 'wifi' => 'wifi',
	'sound_system' => 'speaker', 'speakers' => 'speaker_group', 'mp3' => 'music_note',
	'cdplayer' => 'album', 'cd' => 'album', 'bluetooth' => 'bluetooth', 'usb' => 'usb',
	'kayak' => 'kayaking', 'paddleboard' => 'surfing', 'snorkel_gear' => 'scuba_diving',
	'diving_mask' => 'scuba_diving', 'fishing_gear' => 'phishing', 'tender' => 'directions_boat',
	'dinghy_engine' => 'directions_boat', 'outboard' => 'directions_boat', 'jet_ski' => 'sailing',
	'water_ski' => 'surfing', 'tube' => 'pool', 'seabob' => 'pool',
	'kitchen' => 'kitchen', 'refrigerator' => 'kitchen', 'freezer' => 'ac_unit',
	'microwave' => 'microwave', 'coffee_maker' => 'coffee', 'stove' => 'local_fire_department',
	'ice_maker' => 'severe_cold', 'grill' => 'outdoor_grill', 'bbq' => 'outdoor_grill',
	'dishwasher' => 'dishwasher', 'swim_platform' => 'pool', 'swim_ladder' => 'pool',
	'hydraulic' => 'pool', 'anchor' => 'anchor', 'electric_anchor' => 'anchor',
	'spare_anchor' => 'anchor', 'anchor_chain' => 'anchor', 'electric_winch' => 'settings',
	'safety_equipment' => 'health_and_safety', 'life_jackets' => 'health_and_safety',
	'first_aid' => 'medical_services', 'solarpanel' => 'solar_power', 'teak' => 'deck',
	'dining_table' => 'table_restaurant', 'bath_towels' => 'dry_cleaning',
	'bed_linen' => 'bed', 'crew' => 'group', 'skipper' => 'person', 'fuel_included' => 'local_gas_station',
);

/**
 * Get amenity display label — converts slugs to human-readable names.
 * If the value is already human-readable (contains spaces/capitals), returns as-is.
 */
function oc_amenity_label( $raw, $labels ) {
	if ( isset( $labels[ $raw ] ) ) return $labels[ $raw ];
	// Already human-readable (contains space or uppercase)
	if ( preg_match( '/[A-Z ]/', $raw ) ) return $raw;
	// Fallback: convert slug to title case
	return ucwords( str_replace( '_', ' ', $raw ) );
}

/**
 * Get amenity icon from the slug or label.
 */
function oc_amenity_icon( $raw, $icons ) {
	// Direct slug match
	if ( isset( $icons[ $raw ] ) ) return $icons[ $raw ];
	// Keyword search in label
	$lower = strtolower( $raw );
	$keyword_map = array(
		'jacuzzi' => 'hot_tub', 'gym' => 'fitness_center', 'wifi' => 'wifi',
		'sound' => 'speaker', 'jet ski' => 'sailing', 'beach' => 'beach_access',
		'air con' => 'ac_unit', 'chef' => 'restaurant_menu', 'bar' => 'local_bar',
		'sun' => 'wb_sunny', 'snorkel' => 'scuba_diving', 'fish' => 'phishing',
		'tv' => 'tv', 'kitchen' => 'kitchen', 'bbq' => 'outdoor_grill', 'grill' => 'outdoor_grill',
		'bluetooth' => 'bluetooth', 'shower' => 'shower', 'anchor' => 'anchor',
		'pool' => 'pool', 'kayak' => 'kayaking', 'cinema' => 'theaters',
		'helipad' => 'flight', 'submarine' => 'scuba_diving',
	);
	foreach ( $keyword_map as $kw => $icon ) {
		if ( strpos( $lower, $kw ) !== false ) return $icon;
	}
	return 'check_circle';
}
?>

<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,300,0,0" />

<main id="primary" class="site-main yacht-detail">

	<?php while ( have_posts() ) : the_post(); ?>

	<!-- ═══════════════════════════════════════════════
	     HERO — Image Grid (8-col main + 4-col side)
	     ═══════════════════════════════════════════════ -->
	<section class="yd-hero">
		<div class="container yd-hero__grid">
			<!-- Main large image -->
			<div class="yd-hero__main">
				<?php if ( $featured_id ) : ?>
					<?php echo wp_get_attachment_image( $featured_id, 'full', false, array( 'class' => 'yd-hero__img', 'loading' => 'eager' ) ); ?>
				<?php else : ?>
					<div class="yd-hero__img yd-hero__img--placeholder"></div>
				<?php endif; ?>
			</div>
			<!-- Side thumbnails -->
			<div class="yd-hero__side">
				<?php
				$thumb_ids = array_slice( $gallery_ids, 0, 2 );
				if ( count( $thumb_ids ) < 2 && $featured_id ) {
					// Pad with featured if not enough gallery images
					while ( count( $thumb_ids ) < 2 ) $thumb_ids[] = $featured_id;
				}
				foreach ( $thumb_ids as $i => $tid ) : ?>
					<div class="yd-hero__thumb">
						<?php echo wp_get_attachment_image( $tid, 'medium_large', false, array( 'class' => 'yd-hero__img' ) ); ?>
						<?php if ( $i === 1 && count( $gallery_ids ) > 2 ) : ?>
							<button class="yd-hero__more" type="button" aria-label="View all photos">
								<span class="material-symbols-outlined">photo_library</span>
								+<?php echo count( $gallery_ids ) - 2; ?> Photos
							</button>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<!-- ═══════════════════════════════════════════════
	     TITLE + QUICK SPECS
	     ═══════════════════════════════════════════════ -->
	<section class="yd-intro">
		<div class="container">
			<!-- Breadcrumb -->
			<nav class="yd-breadcrumb" aria-label="Breadcrumb">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
				<span class="material-symbols-outlined" style="font-size:16px;">chevron_right</span>
				<a href="<?php echo esc_url( get_post_type_archive_link( 'boat' ) ); ?>">The Fleet</a>
				<span class="material-symbols-outlined" style="font-size:16px;">chevron_right</span>
				<span><?php the_title(); ?></span>
			</nav>

			<div class="yd-intro__header">
				<div>
					<?php if ( $builder ) : ?>
						<span class="yd-intro__builder"><?php echo esc_html( $builder ); ?></span>
					<?php endif; ?>
					<h1 class="yd-intro__title"><?php the_title(); ?></h1>
					<?php if ( $location ) : ?>
						<span class="yd-intro__loc">
							<span class="material-symbols-outlined">location_on</span>
							<?php echo esc_html( $location ); ?>
						</span>
					<?php endif; ?>
				</div>
				<?php if ( $avg_rating ) : ?>
					<div class="yd-intro__rating">
						<span class="yd-intro__stars">★ <?php echo esc_html( $avg_rating ); ?></span>
						<span class="yd-intro__review-count">(<?php echo esc_html( $review_count ); ?> reviews)</span>
					</div>
				<?php endif; ?>
			</div>

			<?php if ( $show_specs_bar ) : ?>
			<!-- Quick Specs — always 6 fixed columns -->
			<div class="yd-specs yd-specs--fixed6">
				<?php
				// Define all 6 spec slots in order; value is empty string when not set
				$spec_slots = array(
					array( 'icon' => 'straighten',    'val' => $length ? $length . 'ft' : '', 'lbl' => 'Length' ),
					array( 'icon' => 'group',         'val' => $guests  ?: '',                'lbl' => 'Guests' ),
					array( 'icon' => 'bed',           'val' => $cabins  ?: '',                'lbl' => 'Cabins' ),
					array( 'icon' => 'calendar_today','val' => $year    ?: '',                'lbl' => 'Built' ),
					array( 'icon' => 'speed',         'val' => $speed   ? $speed . 'kn' : '', 'lbl' => 'Max Speed' ),
					array( 'icon' => 'location_on',   'val' => $location ?: '',               'lbl' => 'Location' ),
				);

				foreach ( $spec_slots as $spec ) : ?>
					<div class="yd-spec<?php echo empty( $spec['val'] ) ? ' yd-spec--empty' : ''; ?>">
						<?php if ( ! empty( $spec['val'] ) ) : ?>
							<span class="material-symbols-outlined yd-spec__icon"><?php echo esc_html( $spec['icon'] ); ?></span>
							<div class="yd-spec__text">
								<span class="yd-spec__val"><?php echo esc_html( $spec['val'] ); ?></span>
								<span class="yd-spec__lbl"><?php echo esc_html( $spec['lbl'] ); ?></span>
							</div>
						<?php else : ?>
							<span class="yd-spec__empty-label"><?php echo esc_html( $spec['lbl'] ); ?></span>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>
		</div>
	</section>

	<!-- ═══════════════════════════════════════════════
	     MAIN CONTENT + BOOKING SIDEBAR
	     ═══════════════════════════════════════════════ -->
	<section class="yd-body">
		<div class="container yd-body__inner">

			<!-- Main Left Column — Sections driven by settings order -->
			<div class="yd-content">
				<?php foreach ( $sections_order as $section ) :
					if ( in_array( $section, $hidden_sections, true ) ) continue;

					switch ( $section ) :

						case 'description': ?>
							<!-- Description -->
							<div class="yd-section" id="description">
								<h2 class="yd-section__title"><em>About <?php the_title(); ?></em></h2>
								<div class="yd-description">
									<?php if ( get_the_content() ) : ?>
										<?php the_content(); ?>
									<?php else : ?>
										<p><?php the_title(); ?> represents the pinnacle of performance and luxury. Designed for those who demand both speed and sophistication, this vessel offers an expansive open-air experience with a retractable carbon fiber roof that invites the Mediterranean sun into the heart of the vessel.</p>
										<p>Every inch of the interior has been crafted by award-winning designers, combining hand-stitched Italian leather with exotic woods and polished stainless steel accents. The result is an environment that feels as exclusive as a five-star hotel suite — yet moves with breathtaking speed across the water.</p>
									<?php endif; ?>
								</div>
							</div>
						<?php break;

						case 'amenities': ?>
							<!-- Amenities -->
							<div class="yd-section" id="amenities">
								<h2 class="yd-section__title"><em>Onboard Amenities</em></h2>
								<div class="yd-amenities-grid">
									<?php
									$default_amenities = array(
										'Jacuzzi on Sundeck', 'Fully-Equipped Gym', 'Salon & Dining Area', 'Retractable Sun Roof',
										'High-Speed WiFi', 'Dolby Atmos Sound System', 'Jet Ski & Water Toys', 'Beach Club Platform',
										'Air Conditioning Throughout', 'Electric Folding Terrace', 'Professional Chef Available', 'Premium Bar & Cellar',
									);
									$display_amenities = ( ! empty( $amenities ) && is_array( $amenities ) ) ? $amenities : $default_amenities;
									foreach ( $display_amenities as $item ) :
										$label = oc_amenity_label( $item, $amenity_labels );
										$icon  = oc_amenity_icon( $item, $amenity_icons );
									?>
										<div class="yd-amenity">
											<span class="material-symbols-outlined yd-amenity__icon"><?php echo esc_html( $icon ); ?></span>
											<span class="yd-amenity__text"><?php echo esc_html( $label ); ?></span>
										</div>
									<?php endforeach; ?>
								</div>
							</div>
						<?php break;

						case 'captain': ?>
							<!-- Captain -->
							<div class="yd-section" id="captain">
								<h2 class="yd-section__title"><em>Meet Your Captain</em></h2>
								<div class="yd-captain__card">
									<div class="yd-captain__avatar">
										<span class="material-symbols-outlined">person</span>
									</div>
									<div class="yd-captain__bio">
										<h3 class="yd-captain__name"><?php echo esc_html( $captain_name ?: 'Capt. Marcus Sterling' ); ?></h3>
										<p><?php echo esc_html( $captain_bio ?: 'With over 15 years of experience navigating the French Riviera and Amalfi Coast, Marcus ensures your journey is as safe as it is spectacular. Specializing in hidden coves and exclusive beach club access.' ); ?></p>
										<div class="yd-captain__credentials">
											<span><span class="material-symbols-outlined">verified</span> MCA Certified</span>
											<span><span class="material-symbols-outlined">schedule</span> 15+ Years</span>
											<span><span class="material-symbols-outlined">explore</span> Mediterranean</span>
										</div>
									</div>
								</div>
							</div>
						<?php break;

						case 'reviews': ?>
							<!-- Reviews (inline in content column) -->
							<div class="yd-section" id="reviews">
								<h2 class="yd-section__title">
									<em>Guest Reviews</em>
									<?php if ( $avg_rating ) : ?>
										<span class="yd-section__rating">★ <?php echo esc_html( $avg_rating ); ?> <small>(<?php echo esc_html( $review_count ); ?>)</small></span>
									<?php endif; ?>
								</h2>
								<div class="yd-reviews-grid">
									<?php if ( $reviews ) :
										foreach ( $reviews as $rev ) : ?>
											<div class="yd-review-card">
												<div class="yd-review-card__stars">
													<?php echo str_repeat( '★', intval( $rev->rating ) ); ?><?php echo str_repeat( '☆', 5 - intval( $rev->rating ) ); ?>
												</div>
												<?php if ( $rev->title ) : ?>
													<h4 class="yd-review-card__title"><?php echo esc_html( $rev->title ); ?></h4>
												<?php endif; ?>
												<p class="yd-review-card__text">"<?php echo esc_html( $rev->review ); ?>"</p>
												<div class="yd-review-card__author">
													<div class="yd-review-card__avatar">
														<?php echo esc_html( mb_substr( $rev->guest_name, 0, 1 ) ); ?>
													</div>
													<div>
														<cite><?php echo esc_html( $rev->guest_name ); ?></cite>
														<span><?php echo esc_html( date_i18n( 'F Y', strtotime( $rev->created_at ) ) ); ?></span>
													</div>
												</div>
											</div>
										<?php endforeach;
									else : ?>
										<p class="yd-reviews-empty">No reviews yet. Be the first to charter this vessel!</p>
									<?php endif; ?>
								</div>
							</div>
						<?php break;

					endswitch;
				endforeach; ?>
			</div><!-- /.yd-content -->

			<!-- ═══════════════════════════════════════
			     BOOKING SIDEBAR
			     ═══════════════════════════════════════ -->
			<aside class="yd-sidebar">
				<div class="yd-booking-wrap bbc-booking-form-container" id="booking">
					<?php echo do_shortcode( '[bbc_booking_form boat_id="' . intval( $boat_id ) . '"]' ); ?>
				</div>

				<!-- WhatsApp Contact -->
				<a href="<?php echo esc_url( function_exists( 'oc_whatsapp_url' ) ? oc_whatsapp_url( 'Hi! I\'m interested in chartering ' . get_the_title() . '. Could you provide more details?' ) : '#' ); ?>" target="_blank" rel="noopener noreferrer" class="yd-whatsapp-btn">
					<svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
					Chat on WhatsApp
				</a>

				<!-- Charter Includes box -->
				<div class="yd-sidebar-box">
					<h4 class="yd-sidebar-box__title">Charter Includes</h4>
					<ul class="yd-sidebar-box__list">
						<?php if ( $captain_inc === 'yes' ) : ?>
							<li>Professional Captain & Crew</li>
						<?php endif; ?>
						<?php if ( $fuel_inc === 'yes' ) : ?>
							<li>All Fuel Included</li>
						<?php endif; ?>
						<li>Safety Equipment & Insurance</li>
						<li>Onboard Provisioning Service</li>
						<li>Airport Transfer Coordination</li>
					</ul>
				</div>
			</aside>

		</div>
	</section>

	<?php if ( $show_related ) : ?>
	<!-- ═══════════════════════════════════════════════
	     RELATED VESSELS
	     ═══════════════════════════════════════════════ -->
	<section class="yd-related">
		<div class="container">
			<div class="yd-related__header">
				<h2 class="yd-related__title"><em>Related Vessels</em></h2>
			</div>
			<div class="yd-related__grid">
				<?php
				$related = new WP_Query( array(
					'post_type'      => 'boat',
					'posts_per_page' => 3,
					'post__not_in'   => array( $boat_id ),
					'orderby'        => 'rand',
				) );

				if ( $related->have_posts() ) :
					while ( $related->have_posts() ) : $related->the_post();
						$r_id       = get_the_ID();
						$r_price    = get_post_meta( $r_id, '_bbc_price_day', true ) ?: get_post_meta( $r_id, '_bbc_price_half_day', true );
						$r_location = get_post_meta( $r_id, '_bbc_location', true );
						$r_guests   = get_post_meta( $r_id, '_bbc_max_guests', true );
						$r_length   = get_post_meta( $r_id, '_bbc_length', true );
						$r_cabins   = get_post_meta( $r_id, '_bbc_cabins', true );
						$r_type     = get_post_meta( $r_id, '_bbc_boat_type', true );
						$r_cond     = get_post_meta( $r_id, '_bbc_condition', true );
						$r_avg      = $wpdb->get_var( $wpdb->prepare(
							"SELECT ROUND(AVG(rating),1) FROM {$reviews_table} WHERE boat_id = %d AND status = 'approved'", $r_id
						) );
						$r_count    = $wpdb->get_var( $wpdb->prepare(
							"SELECT COUNT(*) FROM {$reviews_table} WHERE boat_id = %d AND status = 'approved'", $r_id
						) );
						?>
						<article class="yd-related-card">
							<a href="<?php the_permalink(); ?>" class="yd-related-card__img-wrap">
								<?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'medium_large', array( 'class' => 'yd-related-card__img' ) ); else : ?>
									<div class="yd-related-card__img yd-related-card__img--placeholder"></div>
								<?php endif; ?>
								<?php if ( $r_cond ) : ?>
									<span class="yd-related-card__badge"><?php echo esc_html( ucfirst( $r_cond ) ); ?></span>
								<?php endif; ?>
								<div class="yd-related-card__overlay">
									<?php if ( $r_avg ) : ?>
										<div class="yd-related-card__rating">
											<svg viewBox="0 0 20 20" fill="currentColor" width="14" height="14"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.287 3.957c.3.921-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.176 0l-3.37 2.448c-.784.57-1.838-.197-1.539-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.063 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.957z"/></svg>
											<span class="yd-related-card__rating-val"><?php echo esc_html( $r_avg ); ?></span>
											<span class="yd-related-card__rating-count">(<?php echo esc_html( $r_count ); ?>)</span>
										</div>
									<?php endif; ?>
								</div>
							</a>
							<div class="yd-related-card__body">
								<div class="yd-related-card__row">
									<h3 class="yd-related-card__name">
										<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
									</h3>
									<?php if ( $r_price ) : ?>
										<span class="yd-related-card__price">$<?php echo number_format( floatval( $r_price ) ); ?><small>/day</small></span>
									<?php endif; ?>
								</div>
								<div class="yd-related-card__meta">
									<?php if ( $r_guests ) : ?>
										<span class="yd-related-card__meta-item">
											<span class="material-symbols-outlined">group</span>
											<?php echo esc_html( $r_guests ); ?> guests
										</span>
									<?php endif; ?>
									<?php if ( $r_location ) : ?>
										<span class="yd-related-card__meta-item">
											<span class="material-symbols-outlined">location_on</span>
											<?php echo esc_html( $r_location ); ?>
										</span>
									<?php endif; ?>
								</div>
								<div class="yd-related-card__actions">
									<a href="<?php the_permalink(); ?>" class="yd-related-card__btn-details">Details</a>
									<a href="<?php the_permalink(); ?>#booking" class="yd-related-card__btn-book">Quick Book</a>
								</div>
							</div>
						</article>
					<?php endwhile;
					wp_reset_postdata();
				else :
					$demo_related = array(
						array( 'name' => 'The Obsidian Edge', 'guests' => '12', 'length' => '82', 'cabins' => '4', 'location' => 'Cannes, France', 'price' => '10,400', 'rating' => '4.9', 'reviews' => '18' ),
						array( 'name' => 'Golden Horizon',    'guests' => '8',  'length' => '68', 'cabins' => '3', 'location' => 'Ibiza, Spain',   'price' => '6,200',  'rating' => '4.7', 'reviews' => '12' ),
						array( 'name' => 'Silver Serenity',   'guests' => '14', 'length' => '92', 'cabins' => '5', 'location' => 'Amalfi Coast',   'price' => '13,800', 'rating' => '5.0', 'reviews' => '8' ),
					);
					foreach ( $demo_related as $v ) : ?>
						<article class="yd-related-card">
							<div class="yd-related-card__img-wrap">
								<div class="yd-related-card__img yd-related-card__img--placeholder"></div>
								<div class="yd-related-card__overlay">
									<div class="yd-related-card__rating">
										<svg viewBox="0 0 20 20" fill="currentColor" width="14" height="14"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.287 3.957c.3.921-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.176 0l-3.37 2.448c-.784.57-1.838-.197-1.539-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.063 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.957z"/></svg>
										<span class="yd-related-card__rating-val"><?php echo esc_html( $v['rating'] ); ?></span>
										<span class="yd-related-card__rating-count">(<?php echo esc_html( $v['reviews'] ); ?>)</span>
									</div>
								</div>
							</div>
							<div class="yd-related-card__body">
								<div class="yd-related-card__row">
									<h3 class="yd-related-card__name"><?php echo esc_html( $v['name'] ); ?></h3>
									<span class="yd-related-card__price">$<?php echo esc_html( $v['price'] ); ?><small>/day</small></span>
								</div>
								<div class="yd-related-card__meta">
									<span class="yd-related-card__meta-item">
										<span class="material-symbols-outlined">group</span>
										<?php echo esc_html( $v['guests'] ); ?> guests
									</span>
									<span class="yd-related-card__meta-item">
										<span class="material-symbols-outlined">location_on</span>
										<?php echo esc_html( $v['location'] ); ?>
									</span>
								</div>
								<div class="yd-related-card__actions">
									<a href="<?php echo esc_url( get_post_type_archive_link( 'boat' ) ); ?>" class="yd-related-card__btn-details">Details</a>
									<a href="<?php echo esc_url( get_post_type_archive_link( 'boat' ) ); ?>" class="yd-related-card__btn-book">Quick Book</a>
								</div>
							</div>
						</article>
					<?php endforeach;
				endif; ?>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<?php endwhile; ?>

</main>

<!-- ═══════════════════════════════════════════════
     GALLERY LIGHTBOX MODAL
     ═══════════════════════════════════════════════ -->
<div class="yd-gallery-modal" id="yd-gallery-modal" hidden>
	<div class="yd-gallery-modal__backdrop"></div>
	<button class="yd-gallery-modal__close" type="button" aria-label="Close gallery">
		<span class="material-symbols-outlined">close</span>
	</button>
	<button class="yd-gallery-modal__nav yd-gallery-modal__prev" type="button" aria-label="Previous photo">
		<span class="material-symbols-outlined">chevron_left</span>
	</button>
	<button class="yd-gallery-modal__nav yd-gallery-modal__next" type="button" aria-label="Next photo">
		<span class="material-symbols-outlined">chevron_right</span>
	</button>
	<div class="yd-gallery-modal__stage">
		<img class="yd-gallery-modal__img" src="" alt="" />
	</div>
	<div class="yd-gallery-modal__counter"></div>
</div>

<script>
(function() {
	var images = <?php
		$all_ids = array();
		if ( $featured_id ) $all_ids[] = $featured_id;
		foreach ( $gallery_ids as $gid ) {
			if ( $gid != $featured_id ) $all_ids[] = $gid;
		}
		$urls = array();
		foreach ( $all_ids as $aid ) {
			$src = wp_get_attachment_image_url( $aid, 'large' );
			if ( $src ) $urls[] = $src;
		}
		echo wp_json_encode( $urls );
	?>;

	if (!images.length) return;

	var modal = document.getElementById('yd-gallery-modal');
	var img = modal.querySelector('.yd-gallery-modal__img');
	var counter = modal.querySelector('.yd-gallery-modal__counter');
	var idx = 0;

	function show(i) {
		idx = (i + images.length) % images.length;
		img.src = images[idx];
		counter.textContent = (idx + 1) + ' / ' + images.length;
	}

	function open(startIdx) {
		show(startIdx || 0);
		modal.hidden = false;
		document.body.style.overflow = 'hidden';
	}

	function close() {
		modal.hidden = true;
		document.body.style.overflow = '';
	}

	// Open on "+N Photos" button click
	var moreBtn = document.querySelector('.yd-hero__more');
	if (moreBtn) moreBtn.addEventListener('click', function() { open(0); });

	// Also open on clicking any hero image
	document.querySelectorAll('.yd-hero__main, .yd-hero__thumb').forEach(function(el, i) {
		el.style.cursor = 'pointer';
		el.addEventListener('click', function(e) {
			if (e.target.closest('.yd-hero__more')) return;
			open(i);
		});
	});

	modal.querySelector('.yd-gallery-modal__close').addEventListener('click', close);
	modal.querySelector('.yd-gallery-modal__backdrop').addEventListener('click', close);
	modal.querySelector('.yd-gallery-modal__prev').addEventListener('click', function() { show(idx - 1); });
	modal.querySelector('.yd-gallery-modal__next').addEventListener('click', function() { show(idx + 1); });

	document.addEventListener('keydown', function(e) {
		if (modal.hidden) return;
		if (e.key === 'Escape') close();
		if (e.key === 'ArrowLeft') show(idx - 1);
		if (e.key === 'ArrowRight') show(idx + 1);
	});
})();
</script>

<?php get_footer(); ?>
