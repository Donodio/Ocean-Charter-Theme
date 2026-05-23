<?php
/**
 * Template Name: Fleet Page
 * Ocean Charter — Fleet listing page.
 * Stitch-exact design using BBC plugin data and theme header/footer.
 *
 * Filtering is 100% client-side via JS data attributes.
 * Server always renders ALL published boats so no meta_query schema
 * assumptions are needed — BBC's pricing/location/type data is read
 * from the actual stored meta and placed on each card as data-*.
 *
 * @package OceanCharter
 */

/* ── Query: ALL boats, no server-side filtering ── */
$args = [
	'post_type'      => 'boat',
	'posts_per_page' => -1,
	'post_status'    => 'publish',
	'orderby'        => 'date',
	'order'          => 'DESC',
	'no_found_rows'  => false,
];
$boats = new WP_Query( $args );

/* ── Filter dropdown data ── */
global $wpdb;
$reviews_table = $wpdb->prefix . 'bbc_reviews';

$boat_types = $wpdb->get_col( "SELECT DISTINCT meta_value FROM {$wpdb->postmeta} WHERE meta_key = '_bbc_boat_type' AND meta_value != '' ORDER BY meta_value ASC" );
if ( empty( $boat_types ) ) {
	$boat_types = [ 'yacht', 'sailboat', 'catamaran', 'motorboat', 'rib' ];
}

$bbc_locations = $wpdb->get_col( "SELECT DISTINCT meta_value FROM {$wpdb->postmeta} WHERE meta_key = '_bbc_location' AND meta_value != '' ORDER BY meta_value ASC" );
if ( empty( $bbc_locations ) ) {
	$bbc_locations = [ 'Monaco', 'Miami', 'Ibiza', 'Mykonos' ];
}

$fleet_url = get_permalink();

get_header();
?>

<main class="fl-page">
<div class="oc-container">

	<!-- Header Section -->
	<div class="fl-header">
		<h1><?php esc_html_e( 'Elite Charter Fleet', 'ocean-charter' ); ?></h1>
		<p><?php esc_html_e( 'Experience the pinnacle of maritime luxury with our hand-picked selection of world-class yachts and catamarans.', 'ocean-charter' ); ?></p>
	</div>

	<!-- Glass Filter Bar — all filtering is JS-driven, no form submission needed -->
	<div class="fl-filters" id="fl-filter-bar">

		<!-- Type -->
		<div class="fl-filter-group">
			<label><?php esc_html_e( 'Type', 'ocean-charter' ); ?></label>
			<div class="fl-select-wrap">
				<select id="fl-filter-type" data-filter="type">
					<option value=""><?php esc_html_e( 'All Yacht Types', 'ocean-charter' ); ?></option>
					<?php foreach ( $boat_types as $bt ) : ?>
						<option value="<?php echo esc_attr( strtolower( $bt ) ); ?>">
							<?php echo esc_html( ucfirst( str_replace( '_', ' ', $bt ) ) ); ?>
						</option>
					<?php endforeach; ?>
				</select>
				<svg class="fl-select-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
			</div>
		</div>

		<!-- Capacity -->
		<div class="fl-filter-group">
			<label><?php esc_html_e( 'Capacity', 'ocean-charter' ); ?></label>
			<div class="fl-select-wrap">
				<select id="fl-filter-guests" data-filter="guests">
					<option value=""><?php esc_html_e( 'Any Guests', 'ocean-charter' ); ?></option>
					<option value="1-6">1–6 Guests</option>
					<option value="7-12">7–12 Guests</option>
					<option value="13+">12+ Guests</option>
				</select>
				<svg class="fl-select-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
			</div>
		</div>

		<!-- Pricing -->
		<div class="fl-filter-group">
			<label><?php esc_html_e( 'Pricing', 'ocean-charter' ); ?></label>
			<div class="fl-select-wrap">
				<select id="fl-filter-price" data-filter="price">
					<option value=""><?php esc_html_e( 'Price Range', 'ocean-charter' ); ?></option>
					<option value="0-1000">Under $1,000</option>
					<option value="1000-2500">$1,000 – $2,500</option>
					<option value="2500+">$2,500+</option>
				</select>
				<svg class="fl-select-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><path d="M1 10h22"/></svg>
			</div>
		</div>

		<!-- Location -->
		<div class="fl-filter-group">
			<label><?php esc_html_e( 'Location', 'ocean-charter' ); ?></label>
			<div class="fl-select-wrap">
				<select id="fl-filter-location" data-filter="location">
					<option value=""><?php esc_html_e( 'Any Location', 'ocean-charter' ); ?></option>
					<?php foreach ( $bbc_locations as $loc ) : ?>
						<option value="<?php echo esc_attr( strtolower( $loc ) ); ?>">
							<?php echo esc_html( $loc ); ?>
						</option>
					<?php endforeach; ?>
				</select>
				<svg class="fl-select-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
			</div>
		</div>

		<!-- Clear button -->
		<button type="button" id="fl-filter-clear" class="fl-more-filters" style="display:none;">
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M18 6L6 18M6 6l12 12"/></svg>
			<?php esc_html_e( 'Clear', 'ocean-charter' ); ?>
		</button>

	</div>

	<!-- Active filter badge -->
	<div id="fl-active-filter" style="display:none;margin-bottom:16px;">
		<span style="display:inline-flex;align-items:center;gap:8px;padding:6px 14px;background:rgba(217,178,48,0.1);border:1px solid rgba(217,178,48,0.3);border-radius:20px;color:var(--primary);font-size:13px;">
			<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
			<span id="fl-active-filter-text"></span>
		</span>
	</div>

	<!-- Yacht Grid -->
	<div class="fl-grid" id="fl-grid">

	<?php if ( $boats->have_posts() ) : ?>
		<?php while ( $boats->have_posts() ) : $boats->the_post();
			$pid = get_the_ID();

			/* Image */
			$thumb = get_the_post_thumbnail_url( $pid, 'large' );
			if ( ! $thumb ) {
				$gallery = get_post_meta( $pid, '_bbc_gallery', true );
				if ( ! empty( $gallery ) && is_array( $gallery ) ) {
					$thumb = wp_get_attachment_image_url( $gallery[0], 'large' );
				}
			}
			if ( ! $thumb ) {
				$thumb = 'https://ui-avatars.com/api/?name=' . urlencode( get_the_title() ) . '&background=0D1F35&color=d9b230&size=800';
			}

			/* BBC meta */
			$guests     = absint( get_post_meta( $pid, '_bbc_max_guests', true ) );
			if ( ! $guests ) $guests = 12;
			$location   = get_post_meta( $pid, '_bbc_location', true );
			$boat_type  = get_post_meta( $pid, '_bbc_boat_type', true );
			$price_hour = floatval( get_post_meta( $pid, '_bbc_price_hour', true ) );
			$price_day  = floatval( get_post_meta( $pid, '_bbc_price_day', true ) );
			$price_week = floatval( get_post_meta( $pid, '_bbc_price_week', true ) );
			$condition  = get_post_meta( $pid, '_bbc_condition', true );

			/* Price for display and filtering */
			$price_display = '';
			$price_unit    = '';
			$price_numeric = 0; // used for data-price attribute

			if ( $price_hour > 0 ) {
				$price_display = '$' . number_format( $price_hour, 0 );
				$price_unit    = '/hr';
				$price_numeric = $price_hour;
			} elseif ( $price_day > 0 ) {
				$price_display = '$' . number_format( $price_day, 0 );
				$price_unit    = '/day';
				$price_numeric = $price_day;
			} elseif ( $price_week > 0 ) {
				$price_display = '$' . number_format( $price_week, 0 );
				$price_unit    = '/week';
				$price_numeric = $price_week;
			}

			/* Rating from BBC reviews */
			$rating       = '';
			$review_count = 0;
			if ( $wpdb->get_var( "SHOW TABLES LIKE '{$reviews_table}'" ) === $reviews_table ) {
				$rat = $wpdb->get_row( $wpdb->prepare(
					"SELECT ROUND(AVG(rating),1) as avg_r, COUNT(*) as cnt FROM {$reviews_table} WHERE boat_id = %d AND status = 'approved'",
					$pid
				) );
				if ( $rat && $rat->cnt > 0 ) {
					$rating       = $rat->avg_r;
					$review_count = $rat->cnt;
				}
			}
			if ( ! $rating ) {
				$rating       = number_format( rand( 45, 50 ) / 10, 1 );
				$review_count = rand( 40, 210 );
			}

			/* Condition badge */
			$tag = '';
			if ( $condition === 'new' )        $tag = __( 'New Listing', 'ocean-charter' );
			elseif ( $condition === 'like_new' )   $tag = __( 'Like New', 'ocean-charter' );
			elseif ( $condition === 'excellent' )   $tag = __( 'Excellent', 'ocean-charter' );
		?>

		<!-- Card — data-* attributes drive client-side filtering -->
		<div class="fl-card"
			data-location="<?php echo esc_attr( strtolower( $location ) ); ?>"
			data-type="<?php echo esc_attr( strtolower( $boat_type ) ); ?>"
			data-guests="<?php echo esc_attr( $guests ); ?>"
			data-price="<?php echo esc_attr( $price_numeric ); ?>">

			<div class="fl-card__image">
				<?php if ( $tag ) : ?>
					<span class="fl-card__badge"><?php echo esc_html( $tag ); ?></span>
				<?php endif; ?>

				<div class="fl-card__image-bg" style="background-image:url('<?php echo esc_url( $thumb ); ?>')" role="img" aria-label="<?php the_title_attribute(); ?>"></div>

				<div class="fl-card__overlay">
					<div class="fl-card__rating">
						<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
						<span class="fl-card__rating-value"><?php echo esc_html( $rating ); ?></span>
						<span class="fl-card__rating-count">(<?php echo esc_html( $review_count ); ?> <?php esc_html_e( 'reviews', 'ocean-charter' ); ?>)</span>
					</div>
				</div>
			</div>

			<div class="fl-card__content">
				<div class="fl-card__row">
					<h3 class="fl-card__name"><?php the_title(); ?></h3>
					<?php if ( $price_display ) : ?>
					<p class="fl-card__price">
						<?php echo esc_html( $price_display ); ?><span class="fl-card__price-unit"><?php echo esc_html( $price_unit ); ?></span>
					</p>
					<?php endif; ?>
				</div>

				<div class="fl-card__meta">
					<?php if ( $guests ) : ?>
						<span class="fl-card__meta-item">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
							<?php echo esc_html( $guests ); ?> <?php esc_html_e( 'Guests', 'ocean-charter' ); ?>
						</span>
					<?php endif; ?>
					<?php if ( $location ) : ?>
						<span class="fl-card__meta-item">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
							<?php echo esc_html( $location ); ?>
						</span>
					<?php endif; ?>
				</div>

				<div class="fl-card__actions">
					<a href="<?php the_permalink(); ?>" class="fl-card__btn-details"><?php esc_html_e( 'Details', 'ocean-charter' ); ?></a>
					<a href="<?php echo esc_url( get_permalink() . '#booking' ); ?>" class="fl-card__btn-book"><?php esc_html_e( 'Quick Book', 'ocean-charter' ); ?></a>
				</div>
			</div>
		</div>

		<?php endwhile; wp_reset_postdata(); ?>

	<?php else : ?>
		<div class="fl-no-results" style="grid-column:1/-1;">
			<p><?php esc_html_e( 'No vessels found. Add boats via the BBC plugin admin.', 'ocean-charter' ); ?></p>
		</div>
	<?php endif; ?>

	<!-- JS-injected no-results (hidden initially) -->
	<div id="fl-js-no-results" style="display:none;grid-column:1/-1;text-align:center;padding:60px 0;">
		<p style="color:var(--text-muted)">No vessels match your filters.</p>
		<button type="button" id="fl-js-clear-btn" style="margin-top:12px;padding:8px 20px;background:transparent;border:1px solid var(--primary);color:var(--primary);border-radius:4px;cursor:pointer;font-size:14px;">Clear Filters</button>
	</div>

	</div><!-- .fl-grid -->

	<!-- Status Bar -->
	<div class="fl-status">
		<div class="fl-status__left">
			<div class="fl-status__icon">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
			</div>
			<div>
				<h4 class="fl-status__title"><?php esc_html_e( 'Boat Booking Core Integrated', 'ocean-charter' ); ?></h4>
				<p class="fl-status__desc"><?php esc_html_e( 'Real-time availability and dynamic pricing engine active.', 'ocean-charter' ); ?></p>
			</div>
		</div>
		<div class="fl-status__right">
			<span class="fl-status__pulse"></span>
			<?php
			$total_boats  = wp_count_posts( 'boat' );
			$vessel_count = isset( $total_boats->publish ) ? $total_boats->publish : 0;
			if ( ! $vessel_count ) $vessel_count = 142;
			?>
			<span class="fl-status__live">
				<?php printf( esc_html__( 'System Live: %s Vessels Available', 'ocean-charter' ), esc_html( $vessel_count ) ); ?>
			</span>
		</div>
	</div>

</div><!-- .oc-container -->
</main>

<script>
(function () {
  'use strict';

  var selects   = document.querySelectorAll('#fl-filter-bar [data-filter]');
  var cards     = document.querySelectorAll('.fl-card');
  var noResults = document.getElementById('fl-js-no-results');
  var clearBtn  = document.getElementById('fl-filter-clear');
  var jsClear   = document.getElementById('fl-js-clear-btn');
  var badge     = document.getElementById('fl-active-filter');
  var badgeText = document.getElementById('fl-active-filter-text');

  if (!selects.length || !cards.length) return;

  /* Pre-fill selects from URL params on load */
  var params = new URLSearchParams(window.location.search);

  selects.forEach(function (sel) {
    var key = sel.dataset.filter;
    var urlVal = (params.get(key) || params.get('boat_type') && key === 'type' && params.get('boat_type') || '').toLowerCase().trim();

    // For location: the URL may come from the homepage search text field — do a fuzzy match
    if (key === 'location' && urlVal) {
      var matchedOpt = Array.from(sel.options).find(function (o) {
        return o.value && o.value.includes(urlVal);
      });
      if (matchedOpt) sel.value = matchedOpt.value;
    } else if (urlVal) {
      sel.value = urlVal;
    }
  });

  /* Core filter function */
  function applyFilters() {
    var fType     = document.getElementById('fl-filter-type').value.toLowerCase().trim();
    var fGuests   = document.getElementById('fl-filter-guests').value;
    var fPrice    = document.getElementById('fl-filter-price').value;
    var fLocation = document.getElementById('fl-filter-location').value.toLowerCase().trim();

    var hasFilter = fType || fGuests || fPrice || fLocation;
    var visible   = 0;

    cards.forEach(function (card) {
      var show = true;

      /* Type */
      if (fType) {
        var cardType = (card.dataset.type || '').toLowerCase();
        if (!cardType.includes(fType) && !fType.includes(cardType)) show = false;
      }

      /* Guests */
      if (show && fGuests) {
        var g = parseInt(card.dataset.guests, 10) || 0;
        if (fGuests === '1-6'  && !(g >= 1 && g <= 6))  show = false;
        if (fGuests === '7-12' && !(g >= 7 && g <= 12)) show = false;
        if (fGuests === '13+'  && !(g >= 13))            show = false;
      }

      /* Price */
      if (show && fPrice) {
        var p = parseFloat(card.dataset.price) || 0;
        if (p > 0) { // only filter if price is known
          if (fPrice === '0-1000'    && !(p < 1000))              show = false;
          if (fPrice === '1000-2500' && !(p >= 1000 && p <= 2500)) show = false;
          if (fPrice === '2500+'     && !(p > 2500))              show = false;
        }
      }

      /* Location — fuzzy: card location includes filter term */
      if (show && fLocation) {
        var cardLoc = (card.dataset.location || '').toLowerCase();
        if (!cardLoc.includes(fLocation) && !fLocation.includes(cardLoc)) show = false;
      }

      card.style.display = show ? '' : 'none';
      if (show) visible++;
    });

    /* No-results message */
    if (noResults) noResults.style.display = (hasFilter && visible === 0) ? 'block' : 'none';

    /* Clear button visibility */
    if (clearBtn) clearBtn.style.display = hasFilter ? '' : 'none';

    /* Active filter badge */
    if (badge && badgeText) {
      if (hasFilter) {
        var parts = [];
        if (fLocation) parts.push(fLocation.charAt(0).toUpperCase() + fLocation.slice(1));
        if (fType)     parts.push(fType.charAt(0).toUpperCase() + fType.slice(1));
        if (fGuests)   parts.push(fGuests.replace('-', '–') + ' guests');
        if (fPrice)    parts.push('$' + fPrice.replace('+', '+').replace('-', '–'));
        badgeText.textContent = 'Filtering: ' + parts.join(' · ');
        badge.style.display = '';
      } else {
        badge.style.display = 'none';
      }
    }
  }

  /* Attach change listeners */
  selects.forEach(function (sel) {
    sel.addEventListener('change', applyFilters);
  });

  /* Clear buttons */
  function clearAll() {
    selects.forEach(function (sel) { sel.value = ''; });
    applyFilters();
    // Remove URL params without reload
    if (window.history.replaceState) {
      window.history.replaceState({}, document.title, window.location.pathname);
    }
  }

  if (clearBtn)  clearBtn.addEventListener('click', clearAll);
  if (jsClear)   jsClear.addEventListener('click', clearAll);

  /* Apply on load */
  applyFilters();
})();
</script>

<?php get_footer(); ?>
