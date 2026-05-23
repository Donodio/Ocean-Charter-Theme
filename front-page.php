<?php
/**
 * Ocean Charter — front-page.php
 * Home page template. Elementor-first: renders Elementor content if set,
 * otherwise outputs full static fallback HTML.
 */
get_header();

// If this page has Elementor-built content, render it
// Note: Elementor sets post_content to empty and stores its layout in _elementor_data,
// so we check _elementor_edit_mode instead of get_the_content().
if ( have_posts() ) {
    the_post();
    if ( get_post_meta( get_the_ID(), '_elementor_edit_mode', true ) === 'builder' ) {
        echo '<main id="main" class="oc-page oc-page--home">';
        the_content();
        echo '</main>';
        get_footer();
        exit;
    }
}

// ── STATIC FALLBACK ──────────────────────────────────────────────────────────
// Allow the home page (Settings → Reading → Front page) to override the hero
// image and image position via the Hero Section Controls meta box.
$home_page_id = (int) get_option( 'page_on_front' );
$home_hero_id  = $home_page_id ? absint( get_post_meta( $home_page_id, '_oc_hero_image', true ) ) : 0;
$home_hero_url = $home_hero_id ? wp_get_attachment_image_url( $home_hero_id, 'full' ) : '';
$home_hero_pos = $home_page_id ? ( get_post_meta( $home_page_id, '_oc_hero_position', true ) ?: 'center center' ) : 'center center';

$hero_img    = $home_hero_url
    ?: ( defined('OC_IMG_HERO_HOME') ? OC_IMG_HERO_HOME : 'https://images.pexels.com/photos/1118448/pexels-photo-1118448.jpeg?auto=compress&cs=tinysrgb&w=1920' );
$vessel_imgs = [
    defined('OC_IMG_VESSEL_1') ? OC_IMG_VESSEL_1 : $hero_img,
    defined('OC_IMG_VESSEL_2') ? OC_IMG_VESSEL_2 : $hero_img,
    defined('OC_IMG_VESSEL_3') ? OC_IMG_VESSEL_3 : $hero_img,
];
$dest_imgs = [
    'mediterranean' => defined('OC_IMG_DEST_MEDITERRANEAN') ? OC_IMG_DEST_MEDITERRANEAN : $hero_img,
    'caribbean'     => defined('OC_IMG_DEST_CARIBBEAN')     ? OC_IMG_DEST_CARIBBEAN     : $hero_img,
    'indian_ocean'  => defined('OC_IMG_DEST_INDIAN_OCEAN')  ? OC_IMG_DEST_INDIAN_OCEAN  : $hero_img,
    'pacific'       => defined('OC_IMG_DEST_PACIFIC')       ? OC_IMG_DEST_PACIFIC       : $hero_img,
];
$svc_imgs = [
    'chef'      => defined('OC_IMG_SVC_CHEF')      ? OC_IMG_SVC_CHEF      : $hero_img,
    'watertoys' => defined('OC_IMG_SVC_WATERTOYS') ? OC_IMG_SVC_WATERTOYS : $hero_img,
    'events'    => defined('OC_IMG_SVC_EVENTS')    ? OC_IMG_SVC_EVENTS    : $hero_img,
    'concierge' => defined('OC_IMG_SVC_CONCIERGE') ? OC_IMG_SVC_CONCIERGE : $hero_img,
];
?>
<main id="main" class="oc-page oc-page--home">

  <!-- ══ HERO ══════════════════════════════════════════════════════════════ -->
  <section class="oc-hero" style="--oc-hero-pos:<?php echo esc_attr( $home_hero_pos ); ?>;">
    <img src="<?php echo esc_url( $hero_img ); ?>" alt="Luxury yacht on open ocean" class="oc-hero__bg" style="object-position:<?php echo esc_attr( $home_hero_pos ); ?>;" loading="eager" fetchpriority="high">
    <div class="oc-hero__overlay"></div>
    <div class="oc-hero__content">
      <span class="oc-hero__eyebrow" data-animate>Luxury Yacht Charters</span>
      <h1 data-animate data-delay="0.1">Define Your <em class="text-gold">Horizon</em></h1>
      <p data-animate data-delay="0.2">Bespoke yacht charters across the world&rsquo;s most coveted waters. Every voyage, a masterpiece.</p>
      <!-- Booking Widget — BBC Search if available, else static fallback -->
      <div class="oc-hero__search-wrap" data-animate data-delay="0.4">
        <?php
        if ( shortcode_exists( 'boat_search' ) ) {
            echo do_shortcode( '[boat_search layout="horizontal"]' );
        } else {
            // Static fallback when BBC plugin is not active
        ?>
        <form class="oc-booking-widget__form" action="<?php echo esc_url( home_url( '/fleet/' ) ); ?>" method="get">
          <div class="oc-bw-field">
            <label class="oc-bw-label" for="oc-search-destination">Destination</label>
            <input id="oc-search-destination" name="location" type="text" class="oc-bw-input" placeholder="Where to sail?" autocomplete="off">
          </div>
          <div class="oc-bw-divider" aria-hidden="true"></div>
          <div class="oc-bw-field">
            <label class="oc-bw-label" for="oc-search-dates">Dates</label>
            <input id="oc-search-dates" name="dates" type="text" class="oc-bw-input" placeholder="Select dates" readonly style="cursor:pointer;">
          </div>
          <div class="oc-bw-divider" aria-hidden="true"></div>
          <div class="oc-bw-field">
            <label class="oc-bw-label" for="bw-guests">Guests</label>
            <select id="bw-guests" name="guests" class="oc-bw-select">
              <option value="">How many?</option>
              <?php foreach ( [2,4,6,8,10,12,16,20] as $n ) : ?>
                <option value="<?php echo esc_attr( $n ); ?>"><?php echo esc_html( $n ); ?> guests</option>
              <?php endforeach; ?>
            </select>
          </div>
          <button type="submit" class="oc-bw-submit">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            Search
          </button>
        </form>
        <script>
        (function() {
          var form = document.querySelector('.oc-booking-widget__form');
          if (!form) return;
          form.addEventListener('submit', function() {
            var loc    = (document.getElementById('oc-search-destination') || {}).value || '';
            var dates  = (document.getElementById('oc-search-dates') || {}).value || '';
            var guests = (document.getElementById('bw-guests') || {}).value || '';
            if (!loc && !dates && !guests) return;
            var fd = new FormData();
            fd.append('action', 'oc_search_intent');
            fd.append('nonce', '<?php echo esc_js( wp_create_nonce( 'oc_search_intent' ) ); ?>');
            fd.append('location', loc);
            fd.append('dates', dates);
            fd.append('guests', guests);
            navigator.sendBeacon('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', fd);
          });
        })();
        </script>
        <?php } ?>
      </div>
    </div>
  </section>

  <!-- Stats bar removed — not in Stitch design -->

  <!-- ══ FEATURED VESSELS ══════════════════════════════════════════════════ -->
  <section class="oc-section oc-section--dark">
    <div class="oc-container">
      <div class="oc-section-header">
        <span class="oc-caption" data-animate>Our Fleet</span>
        <h2 data-animate data-delay="0.1">Featured <span class="text-gold">Vessels</span></h2>
        <p data-animate data-delay="0.2">Hand-selected luxury yachts crewed by world-class professionals.</p>
      </div>
      <div class="oc-vessel-grid">
        <?php
        $vessels = new WP_Query( [
            'post_type'      => 'boat',
            'posts_per_page' => 3,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        ] );
        $fallback_i = 1;
        if ( $vessels->have_posts() ) :
            while ( $vessels->have_posts() ) : $vessels->the_post();
                $bid    = get_the_ID();
                $imgs   = function_exists('oc_get_boat_gallery') ? oc_get_boat_gallery( $bid ) : [];
                $img    = ! empty( $imgs[0] ) ? $imgs[0] : ( defined('OC_IMG_VESSEL_' . $fallback_i) ? constant('OC_IMG_VESSEL_' . $fallback_i) : $hero_img );
                $guests = function_exists('oc_boat_meta') ? oc_boat_meta( $bid, 'max_guests', '—' ) : get_post_meta( $bid, '_bbc_max_guests', true );
                $length = function_exists('oc_boat_meta') ? oc_boat_meta( $bid, 'length', '' ) : get_post_meta( $bid, '_bbc_length', true );
                $unit   = get_post_meta( $bid, '_bbc_length_unit', true ) ?: 'ft';
                $price  = function_exists('oc_price') ? oc_price( $bid ) : '';
                $fallback_i++;
        ?>
        <article class="oc-vessel-card" data-animate data-delay="<?php echo esc_attr( ($fallback_i - 2) * 0.1 ); ?>">
          <div class="oc-vessel-card__img-wrap">
            <img src="<?php echo esc_url( $img ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
            <span class="oc-vessel-card__badge">Top Rated</span>
          </div>
          <div class="oc-vessel-card__body">
            <h3 class="oc-vessel-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <?php if ( $price ) : ?>
              <p class="oc-vessel-card__price"><?php echo esc_html( $price ); ?><span>/day</span></p>
            <?php endif; ?>
            <div class="oc-vessel-card__specs">
              <?php if ( $length ) : ?>
              <span><?php echo esc_html( $length . ' ' . $unit ); ?></span>
              <?php endif; ?>
              <?php if ( $guests ) : ?>
              <span><?php echo esc_html( $guests ); ?> guests</span>
              <?php endif; ?>
              <span>Luxury</span>
            </div>
            <a href="<?php the_permalink(); ?>" class="oc-vessel-card__btn">Explore Vessel</a>
          </div>
        </article>
        <?php endwhile; wp_reset_postdata();
        else :
            $demo_vessels = [
                [ 'name' => 'Seraphine', 'guests' => 12, 'length' => '82 ft', 'price' => '$6,500', 'img' => $vessel_imgs[0] ],
                [ 'name' => 'Aurelia',   'guests' => 8,  'length' => '65 ft', 'price' => '$4,200', 'img' => $vessel_imgs[1] ],
                [ 'name' => 'Elara',     'guests' => 16, 'length' => '105 ft','price' => '$9,800', 'img' => $vessel_imgs[2] ],
            ];
            foreach ( $demo_vessels as $i => $v ) : ?>
            <article class="oc-vessel-card" data-animate data-delay="<?php echo esc_attr( $i * 0.1 ); ?>">
              <div class="oc-vessel-card__img-wrap">
                <img src="<?php echo esc_url( $v['img'] ); ?>" alt="<?php echo esc_attr( $v['name'] ); ?>" loading="lazy">
                <span class="oc-vessel-card__badge">Top Rated</span>
              </div>
              <div class="oc-vessel-card__body">
                <h3 class="oc-vessel-card__title"><a href="<?php echo esc_url( home_url( '/fleet/' ) ); ?>"><?php echo esc_html( $v['name'] ); ?></a></h3>
                <p class="oc-vessel-card__price"><?php echo esc_html( $v['price'] ); ?><span>/day</span></p>
                <div class="oc-vessel-card__specs">
                  <span><?php echo esc_html( $v['length'] ); ?></span>
                  <span><?php echo esc_html( $v['guests'] ); ?> guests</span>
                  <span>Luxury</span>
                </div>
                <a href="<?php echo esc_url( home_url( '/fleet/' ) ); ?>" class="oc-vessel-card__btn">Explore Vessel</a>
              </div>
            </article>
            <?php endforeach;
        endif; ?>
      </div>
      <div class="oc-section-footer" data-animate>
        <a href="<?php echo esc_url( home_url( '/fleet/' ) ); ?>" class="btn-secondary">View Full Fleet &rarr;</a>
      </div>
    </div>
  </section>

  <!-- ══ WHY US ════════════════════════════════════════════════════════════ -->
  <section class="oc-section oc-section--surface">
    <div class="oc-container">
      <div class="oc-why-us" data-animate>

        <!-- Left: image with experience badge -->
        <div class="oc-why-us__img-col">
          <div class="oc-why-us__img-wrap">
            <img src="<?php echo esc_url( defined('OC_IMG_VESSEL_5') ? OC_IMG_VESSEL_5 : $hero_img ); ?>" alt="Luxury yacht experience" loading="lazy">
            <div class="oc-why-us__badge">
              <span class="oc-why-us__badge-number">25+</span>
              <span class="oc-why-us__badge-label">Years of Excellence</span>
            </div>
          </div>
        </div>

        <!-- Right: heading + features -->
        <div class="oc-why-us__content">
          <span class="oc-caption">Why Ocean Charter</span>
          <h2>The <span class="text-gold">Difference</span> Is Everything</h2>
          <p class="oc-why-us__intro">From the moment you enquire to the final sunset toast, every detail is curated to exceed expectations.</p>

          <div class="oc-why-us__features">
            <?php
            $features = [
                [
                    'icon' => '<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>',
                    'title' => 'Expert Crew',
                    'desc'  => 'Certified mariners passionate about your journey, ensuring safety and exceptional service at sea.',
                ],
                [
                    'icon' => '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
                    'title' => 'Bespoke Itineraries',
                    'desc'  => 'Every voyage crafted around your desires — hidden coves, iconic harbours, and everything between.',
                ],
                [
                    'icon' => '<circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/>',
                    'title' => '24/7 Concierge',
                    'desc'  => 'Round-the-clock support throughout your voyage. We are always reachable when it matters most.',
                ],
            ];
            foreach ( $features as $i => $f ) : ?>
            <div class="oc-why-feature" data-animate data-delay="<?php echo esc_attr( ($i + 1) * 0.1 ); ?>">
              <div class="oc-why-feature__icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><?php echo $f['icon']; ?></svg>
              </div>
              <div class="oc-why-feature__text">
                <h3><?php echo esc_html( $f['title'] ); ?></h3>
                <p><?php echo esc_html( $f['desc'] ); ?></p>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- ══ DESTINATIONS ═══════════════════════════════════════════════════════ -->
  <section class="oc-section oc-section--dark">
    <div class="oc-container">
      <div class="oc-section-header">
        <span class="oc-caption" data-animate>Where We Sail</span>
        <h2 data-animate data-delay="0.1">Dream <span class="text-gold">Destinations</span></h2>
      </div>

      <?php
      $destinations = [
          [ 'key' => 'mediterranean', 'name' => 'Mediterranean',  'sub' => 'Greece · Italy · Croatia',          'count' => '48 yachts',  'img' => $dest_imgs['mediterranean'] ],
          [ 'key' => 'caribbean',     'name' => 'Caribbean',       'sub' => 'BVI · St. Barts · Grenadines',      'count' => '32 yachts',  'img' => $dest_imgs['caribbean'] ],
          [ 'key' => 'indian_ocean',  'name' => 'Indian Ocean',    'sub' => 'Maldives · Seychelles · Zanzibar',  'count' => '24 yachts',  'img' => $dest_imgs['indian_ocean'] ],
          [ 'key' => 'pacific',       'name' => 'Pacific',         'sub' => 'French Polynesia · Fiji · Hawaii',  'count' => '18 yachts',  'img' => $dest_imgs['pacific'] ],
      ];
      ?>

      <!-- Bento grid: Stitch 4-col × 2-row — large left (col-span-2 row-span-2), 2 small right, 1 wide bottom (col-span-2) -->
      <div class="oc-bento-grid" data-animate>
        <?php foreach ( $destinations as $i => $dest ) : ?>
        <a href="<?php echo esc_url( home_url( '/fleet/?location=' . $dest['key'] ) ); ?>"
           class="oc-bento-card<?php echo $i === 0 ? ' oc-bento-card--hero' : ''; ?><?php echo $i === 3 ? ' oc-bento-card--wide' : ''; ?>"
           data-animate data-delay="<?php echo esc_attr( $i * 0.08 ); ?>">
          <img src="<?php echo esc_url( $dest['img'] ); ?>" alt="<?php echo esc_attr( $dest['name'] ); ?>" loading="lazy">
          <div class="oc-bento-card__overlay"></div>
          <div class="oc-bento-card__content">
            <span class="oc-bento-card__count"><?php echo esc_html( $dest['count'] ); ?></span>
            <h3><?php echo esc_html( $dest['name'] ); ?></h3>
            <p><?php echo esc_html( $dest['sub'] ); ?></p>
          </div>
        </a>
        <?php endforeach; ?>
      </div>

      <div class="oc-section-footer" data-animate>
        <a href="<?php echo esc_url( home_url( '/destinations/' ) ); ?>" class="btn-secondary">All Destinations &rarr;</a>
      </div>
    </div>
  </section>

  <!-- ══ SERVICES ══════════════════════════════════════════════════════════ -->
  <section class="oc-section oc-section--surface">
    <div class="oc-container">
      <div class="oc-section-header">
        <span class="oc-caption" data-animate>What We Offer</span>
        <h2 data-animate data-delay="0.1">White-Glove <span class="text-gold">Services</span></h2>
      </div>
      <div class="oc-svc-stagger">
        <?php
        $services = [
            [
                'svg'   => '<path d="M3 2h18v4H3z"/><path d="M3 10h18v4H3z"/><path d="M3 18h18v4H3z"/>',
                'title' => 'Private Chef',
                'desc'  => 'Michelin-calibre cuisine prepared fresh daily using locally sourced, seasonal ingredients.',
                'img'   => $svc_imgs['chef'],
            ],
            [
                'svg'   => '<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/>',
                'title' => 'Water Toys',
                'desc'  => 'Jet skis, paddleboards, snorkel gear, and more — your private aquatic playground.',
                'img'   => $svc_imgs['watertoys'],
            ],
            [
                'svg'   => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
                'title' => 'Private Events',
                'desc'  => 'Weddings, corporate retreats, and milestone celebrations elevated by the open sea.',
                'img'   => $svc_imgs['events'],
            ],
            [
                'svg'   => '<path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/>',
                'title' => 'Concierge',
                'desc'  => 'Shore excursions, reservations, and bespoke island experiences arranged seamlessly.',
                'img'   => $svc_imgs['concierge'],
            ],
        ];
        foreach ( $services as $i => $svc ) : ?>
        <div class="oc-svc-card2<?php echo ( $i % 2 === 1 ) ? ' oc-svc-card2--offset' : ''; ?>" data-animate data-delay="<?php echo esc_attr( $i * 0.1 ); ?>">
          <div class="oc-svc-card2__img">
            <img src="<?php echo esc_url( $svc['img'] ); ?>" alt="<?php echo esc_attr( $svc['title'] ); ?>" loading="lazy">
            <div class="oc-svc-card2__icon-badge">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><?php echo $svc['svg']; ?></svg>
            </div>
          </div>
          <div class="oc-svc-card2__body">
            <h3><?php echo esc_html( $svc['title'] ); ?></h3>
            <p><?php echo esc_html( $svc['desc'] ); ?></p>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="oc-section-footer" data-animate>
        <a href="<?php echo esc_url( home_url( '/services/' ) ); ?>" class="btn-secondary">All Services &rarr;</a>
      </div>
    </div>
  </section>

  <!-- ══ PACKAGES TEASER ════════════════════════════════════════════════════ -->
  <section class="oc-section oc-section--dark">
    <div class="oc-container">
      <div class="oc-section-header">
        <span class="oc-caption" data-animate>Curated Experiences</span>
        <h2 data-animate data-delay="0.1">Signature <span class="text-gold">Packages</span></h2>
      </div>
      <div class="oc-pkg-grid">
        <?php
        $packages = new WP_Query( [ 'post_type' => 'bbc_package', 'posts_per_page' => 3, 'post_status' => 'publish' ] );
        if ( $packages->have_posts() ) :
            while ( $packages->have_posts() ) : $packages->the_post();
                $pkg_id    = get_the_ID();
                $pkg_img   = get_the_post_thumbnail_url( $pkg_id, 'large' ) ?: ( defined('OC_IMG_HERO_PACKAGES') ? OC_IMG_HERO_PACKAGES : $hero_img );
                $pkg_price = get_post_meta( $pkg_id, '_bbc_pkg_price', true );
                $pkg_loc   = get_post_meta( $pkg_id, '_bbc_pkg_location', true );
                $pkg_guests= (int) get_post_meta( $pkg_id, '_bbc_pkg_max_guests', true );
                $pkg_durs  = get_post_meta( $pkg_id, '_bbc_pkg_durations', true );
                $pkg_label = get_post_meta( $pkg_id, '_bbc_pkg_label', true );
                // Calculate total duration from first option
                $pkg_hours = 0;
                if ( is_array( $pkg_durs ) && ! empty( $pkg_durs ) ) {
                    $pkg_hours = intval( $pkg_durs[0]['hours'] ?? 0 );
                }
        ?>
        <div class="oc-pkg-card oc-card" data-animate>
          <div class="oc-pkg-card__img-wrap">
            <img src="<?php echo esc_url( $pkg_img ); ?>" alt="<?php the_title_attribute(); ?>" class="oc-card__img" loading="lazy">
            <?php if ( $pkg_label ) : ?><span class="oc-pkg-card__tag"><?php echo esc_html( $pkg_label ); ?></span><?php endif; ?>
          </div>
          <div class="oc-pkg-card__body">
            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <?php if ( $pkg_price ) : ?><p class="oc-pkg-card__price">From $<?php echo esc_html( number_format( floatval( $pkg_price ) ) ); ?></p><?php endif; ?>
            <div class="oc-pkg-card__specs">
              <?php if ( $pkg_hours ) : ?>
              <span class="oc-pkg-spec">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                <?php echo esc_html( $pkg_hours ); ?>h
              </span>
              <?php endif; ?>
              <?php if ( $pkg_guests ) : ?>
              <span class="oc-pkg-spec">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                <?php echo esc_html( $pkg_guests ); ?> guests
              </span>
              <?php endif; ?>
              <?php if ( $pkg_loc ) : ?>
              <span class="oc-pkg-spec">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <?php echo esc_html( $pkg_loc ); ?>
              </span>
              <?php endif; ?>
              <?php if ( is_array( $pkg_durs ) && count( $pkg_durs ) > 1 ) : ?>
              <span class="oc-pkg-spec">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                <?php echo count( $pkg_durs ); ?> options
              </span>
              <?php endif; ?>
            </div>
            <a href="<?php the_permalink(); ?>" class="btn-secondary oc-pkg-card__btn">View Details</a>
          </div>
        </div>
        <?php endwhile; wp_reset_postdata();
        else :
            $demo_pkgs = [
                [ 'name' => 'Mediterranean Escape',  'price' => 'From $8,500',  'tag' => 'Popular',   'hours' => 8, 'guests' => 12, 'loc' => 'Amalfi Coast', 'img' => defined('OC_IMG_HERO_PACKAGES') ? OC_IMG_HERO_PACKAGES : $hero_img ],
                [ 'name' => 'Caribbean Odyssey',     'price' => 'From $11,200', 'tag' => 'Exclusive', 'hours' => 72, 'guests' => 8, 'loc' => 'St. Barts',   'img' => defined('OC_IMG_DEST_CARIBBEAN') ? OC_IMG_DEST_CARIBBEAN : $hero_img ],
                [ 'name' => 'Aegean Day Charter',    'price' => 'From $2,800',  'tag' => 'New',       'hours' => 6, 'guests' => 6, 'loc' => 'Santorini',    'img' => defined('OC_IMG_DEST_MEDITERRANEAN') ? OC_IMG_DEST_MEDITERRANEAN : $hero_img ],
            ];
            foreach ( $demo_pkgs as $i => $pkg ) : ?>
            <div class="oc-pkg-card oc-card" data-animate data-delay="<?php echo esc_attr( $i * 0.1 ); ?>">
              <div class="oc-pkg-card__img-wrap">
                <img src="<?php echo esc_url( $pkg['img'] ); ?>" alt="<?php echo esc_attr( $pkg['name'] ); ?>" class="oc-card__img" loading="lazy">
                <span class="oc-pkg-card__tag"><?php echo esc_html( $pkg['tag'] ); ?></span>
              </div>
              <div class="oc-pkg-card__body">
                <h3><a href="<?php echo esc_url( home_url( '/packages/' ) ); ?>"><?php echo esc_html( $pkg['name'] ); ?></a></h3>
                <p class="oc-pkg-card__price"><?php echo esc_html( $pkg['price'] ); ?></p>
                <div class="oc-pkg-card__specs">
                  <span class="oc-pkg-spec">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                    <?php echo esc_html( $pkg['hours'] ); ?>h
                  </span>
                  <span class="oc-pkg-spec">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                    <?php echo esc_html( $pkg['guests'] ); ?> guests
                  </span>
                  <span class="oc-pkg-spec">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <?php echo esc_html( $pkg['loc'] ); ?>
                  </span>
                </div>
                <a href="<?php echo esc_url( home_url( '/packages/' ) ); ?>" class="btn-secondary oc-pkg-card__btn">View Details</a>
              </div>
            </div>
            <?php endforeach;
        endif; ?>
      </div>
    </div>
  </section>

  <!-- ══ TESTIMONIALS ═══════════════════════════════════════════════════════ -->
  <section class="oc-section oc-section--surface">
    <div class="oc-container">
      <div class="oc-testimonial" data-animate>
        <span class="oc-testimonial__quote" aria-hidden="true">&ldquo;</span>
        <blockquote class="oc-testimonial__text">
          From the moment we stepped aboard, every detail was flawless. The crew anticipated our every need, the itinerary was perfectly curated, and the sunsets were simply unforgettable. This wasn&rsquo;t a holiday &mdash; it was a masterpiece.
        </blockquote>
        <div class="oc-testimonial__author">
          <div class="oc-testimonial__avatar">
            <span>SC</span>
          </div>
          <div>
            <strong class="oc-testimonial__name">Sarah &amp; Christopher</strong>
            <span class="oc-testimonial__role">Mediterranean Charter, August 2025</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ══ CTA STRIP — Stitch: bg image + navy overlay + two buttons ═════════ -->
  <section class="oc-cta-strip" style="background-image:url('<?php echo esc_url( defined('OC_IMG_HERO_FLEET') ? OC_IMG_HERO_FLEET : $hero_img ); ?>');">
    <div class="oc-cta-strip__overlay"></div>
    <div class="oc-container">
      <div class="oc-cta-strip__inner" data-animate>
        <div class="oc-cta-strip__text">
          <h2>Ready to Set <em class="text-gold">Sail?</em></h2>
          <p>Your bespoke charter experience awaits. Let&rsquo;s plan your perfect voyage.</p>
        </div>
        <div class="oc-cta-strip__actions">
          <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn-primary">Book Now</a>
          <a href="<?php echo function_exists('oc_whatsapp_url') ? oc_whatsapp_url('Hello, I\'d like to book a charter.') : '#'; ?>" class="btn-secondary" target="_blank" rel="noopener">WhatsApp Us</a>
        </div>
      </div>
    </div>
  </section>

</main>

<style>
/* ═══ Home-specific CSS ═══ */

/* ── Hero search wrapper — holds BBC search or static fallback ── */
.oc-hero__search-wrap {
  margin-top: 2.5rem;
  max-width: 64rem; /* max-w-5xl — Stitch spec */
  width: 100%;
  margin-left: auto;
  margin-right: auto;
}

/* ── BBC Search Form — Glass Override (Stitch hero booking widget) ── */
.oc-hero .bbc-search-wrapper-v2 {
  background: rgba(26, 34, 51, 0.75) !important;
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border: 1px solid rgba(217, 178, 48, 0.12);
  border-radius: 16px !important;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
  overflow: visible;
}
.oc-hero .bbc-search-form-v2 {
  background: transparent !important;
  border: none !important;
  box-shadow: none !important;
  border-radius: 16px;
  padding: 0.375rem;
}
.oc-hero .bbc-search-group-v2 {
  padding: 0.625rem 1.25rem;
}
.oc-hero .bbc-search-group-v2 label {
  font-size: 10px !important;
  font-weight: 700 !important;
  letter-spacing: 0.12em !important;
  text-transform: uppercase !important;
  color: var(--primary) !important;
  margin-bottom: 2px !important;
}
.oc-hero .bbc-search-input-clean-v2,
.oc-hero .bbc-search-select-clean-v2 {
  color: var(--text, #f0ece3) !important;
  font-size: 0.875rem !important;
  font-weight: 500 !important;
}
.oc-hero .bbc-search-input-clean-v2::placeholder {
  color: var(--text-muted, #8a9bb0) !important;
}
.oc-hero .bbc-search-select-clean-v2 option {
  background: #0a0f1a;
  color: #f0ece3;
}
.oc-hero .bbc-search-divider-v2 {
  background: rgba(255, 255, 255, 0.1) !important;
  width: 1px;
  margin: 10px 0;
}
.oc-hero .bbc-search-submit-btn-v2 {
  background: var(--primary) !important;
  color: #0a0f1a !important;
  font-weight: 700 !important;
  font-size: 0.875rem !important;
  border-radius: 12px !important;
  padding: 0.875rem 1.75rem !important;
  min-width: auto !important;
  letter-spacing: 0.04em !important;
  text-transform: none !important;
  transition: background 0.35s ease, transform 0.35s ease;
}
.oc-hero .bbc-search-submit-btn-v2:hover {
  background: #f0c840 !important;
  filter: none !important;
  transform: translateY(-1px);
}
/* Date input color fix for dark bg */
.oc-hero .bbc-search-group-v2 input[type="date"]::-webkit-calendar-picker-indicator {
  filter: invert(0.8);
}

/* ── Static fallback booking widget ── */
.oc-booking-widget__form {
  display: grid;
  grid-template-columns: 1fr auto 1fr auto 1fr auto auto;
  align-items: center;
  background: rgba(26, 34, 51, 0.75);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border: 1px solid rgba(217, 178, 48, 0.12);
  border-radius: 16px;
  padding: 0.5rem;
  gap: 0;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
}
.oc-bw-field { display: flex; flex-direction: column; padding: 0.5rem 1.25rem; min-width: 0; }
.oc-bw-divider { width: 1px; height: 36px; background: rgba(255, 255, 255, 0.1); flex-shrink: 0; }
.oc-bw-label {
  font-size: 10px; font-weight: 700; letter-spacing: 0.12em;
  text-transform: uppercase; color: var(--primary); margin-bottom: 2px; white-space: nowrap;
}
.oc-bw-input,
.oc-bw-select {
  background: transparent; border: none; outline: none;
  color: var(--text); font-size: 0.875rem; font-family: var(--font-body);
  padding: 0; width: 100%; min-width: 0;
}
.oc-bw-select { cursor: pointer; appearance: none; }
.oc-bw-input::placeholder { color: var(--text-muted); }
.oc-bw-select option { background: #0a0f1a; color: var(--text); }
.oc-bw-submit {
  display: flex; align-items: center; gap: 0.5rem;
  background: var(--primary); color: #0a0c10; font-weight: 700; font-size: 0.875rem;
  border: none; border-radius: 12px; padding: 0.875rem 1.75rem;
  cursor: pointer; transition: background var(--transition), transform var(--transition);
  white-space: nowrap; flex-shrink: 0;
}
.oc-bw-submit:hover { background: #f0c840; transform: translateY(-1px); }

@media (max-width: 768px) {
  .oc-booking-widget__form { grid-template-columns: 1fr; gap: 0; }
  .oc-bw-divider { width: 100%; height: 1px; margin: 0; }
  .oc-bw-submit { border-radius: 10px; justify-content: center; margin-top: 0.5rem; }
  /* BBC search mobile override */
  .oc-hero .bbc-search-submit-btn-v2 { border-radius: 10px !important; }
}

/* ── Grids ── */
.oc-vessel-grid, .oc-pkg-grid {
  display: grid; grid-template-columns: repeat(3, 1fr);
  gap: clamp(1.25rem, 3vw, 2rem); margin-bottom: 2.5rem;
}

/* ── Vessel card — Stitch portrait style ── */
.oc-vessel-card {
  background: rgba(255, 255, 255, 0.04);
  border: 1px solid rgba(255, 255, 255, 0.08);
  border-radius: 16px;
  overflow: hidden;
  transition: border-color var(--transition), transform var(--transition);
}
.oc-vessel-card:hover { border-color: rgba(217, 178, 48, 0.4); transform: translateY(-4px); }
.oc-vessel-card__img-wrap {
  position: relative; aspect-ratio: 4/5; overflow: hidden;
}
.oc-vessel-card__img-wrap img {
  width: 100%; height: 100%; object-fit: cover;
  transition: transform 0.6s ease;
}
.oc-vessel-card:hover .oc-vessel-card__img-wrap img { transform: scale(1.1); }
.oc-vessel-card__badge {
  position: absolute; top: 1rem; right: 1rem;
  background: var(--primary); color: #0a0f1a;
  font-size: 10px; font-weight: 900; letter-spacing: 0.08em; text-transform: uppercase;
  padding: 0.3rem 0.75rem; border-radius: 9999px;
}
.oc-vessel-card__body { padding: 1.5rem; display: flex; flex-direction: column; gap: 0.5rem; }
.oc-vessel-card__title { font-family: var(--font-heading); font-size: 1.5rem; margin: 0; }
.oc-vessel-card__title a { color: var(--text); text-decoration: none; transition: color var(--transition); }
.oc-vessel-card__title a:hover { color: var(--primary); }
.oc-vessel-card__price { font-size: 1.125rem; font-weight: 800; color: var(--primary); margin: 0; }
.oc-vessel-card__price span { font-size: 0.8125rem; font-weight: 400; color: var(--text-muted); margin-left: 2px; }
.oc-vessel-card__specs { display: flex; gap: 1rem; flex-wrap: wrap; margin: 0.25rem 0; }
.oc-vessel-card__specs span { font-size: 0.8125rem; color: var(--text-muted); }
.oc-vessel-card__btn {
  display: block; text-align: center; margin-top: 0.75rem;
  padding: 0.625rem 1.25rem;
  border: 1px solid rgba(217, 178, 48, 0.3); border-radius: var(--radius);
  color: var(--primary); font-size: 0.875rem; font-weight: 600; text-decoration: none;
  transition: background var(--transition), color var(--transition);
}
.oc-vessel-card__btn:hover { background: var(--primary); color: #0a0c10; }
.oc-section-footer { text-align: center; margin-top: 2rem; }

/* ── Why Us — 2-col Stitch layout ── */
.oc-why-us {
  display: grid; grid-template-columns: 1fr 1fr;
  gap: clamp(2.5rem, 5vw, 5rem); align-items: center;
}
.oc-why-us__img-wrap {
  position: relative; border-radius: var(--radius-lg);
  overflow: hidden; aspect-ratio: 1/1; /* Stitch: aspect-square */
}
.oc-why-us__img-wrap img { width: 100%; height: 100%; object-fit: cover; }
.oc-why-us__badge {
  --badge-border-width: 3px; /* Customizable border thickness */
  position: absolute; bottom: 2rem; left: 2rem;
  background: var(--primary); color: #0a0f1a;
  border-radius: var(--radius-lg);
  border: var(--badge-border-width) solid rgba(10, 15, 26, 0.3);
  padding: 1.25rem 1.5rem;
  text-align: center;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
}
.oc-why-us__badge-number {
  display: block; font-family: var(--font-heading);
  font-size: 2.5rem; font-weight: 900; line-height: 1;
}
.oc-why-us__badge-label {
  display: block; font-size: 0.6875rem; font-weight: 700;
  letter-spacing: 0.08em; text-transform: uppercase; margin-top: 4px; opacity: 0.75;
}
.oc-why-us__content h2 { margin: 0.5rem 0 1rem; }
.oc-why-us__intro { color: var(--text-muted); font-size: 1rem; line-height: 1.75; margin-bottom: 2rem; }
.oc-why-us__features { display: flex; flex-direction: column; gap: 1.5rem; }
.oc-why-feature { display: flex; gap: 1.125rem; align-items: flex-start; }
.oc-why-feature__icon {
  width: 48px; height: 48px; flex-shrink: 0;
  background: rgba(217, 178, 48, 0.1);
  border: 1px solid rgba(217, 178, 48, 0.2);
  border-radius: 10px;
  display: flex; align-items: center; justify-content: center;
  color: var(--primary);
}
.oc-why-feature__text h3 { font-size: 1rem; margin: 0 0 0.3rem; }
.oc-why-feature__text p { font-size: 0.9rem; color: var(--text-muted); line-height: 1.65; margin: 0; }

/* ── Bento destinations grid — Stitch 4-col × 2-row ── */
.oc-bento-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  grid-template-rows: 300px 300px;
  gap: 1rem;
  margin-bottom: 2.5rem;
}
.oc-bento-card {
  position: relative; border-radius: var(--radius-lg);
  overflow: hidden; display: block; text-decoration: none;
}
.oc-bento-card--hero {
  grid-column: span 2; grid-row: span 2;
}
.oc-bento-card--wide {
  grid-column: span 2;
}
.oc-bento-card img {
  width: 100%; height: 100%; object-fit: cover;
  transition: transform 0.6s ease;
}
.oc-bento-card:hover img { transform: scale(1.05); }
.oc-bento-card__overlay {
  position: absolute; inset: 0;
  background: linear-gradient(to top, rgba(10, 15, 26, 0.85) 0%, rgba(10, 15, 26, 0.1) 60%);
}
.oc-bento-card__content {
  position: absolute; bottom: 0; left: 0; right: 0; padding: 1.25rem 1.5rem;
}
.oc-bento-card--hero .oc-bento-card__content { padding: 2rem; }
.oc-bento-card__count {
  display: inline-block; font-size: 0.6875rem; font-weight: 700;
  letter-spacing: 0.1em; text-transform: uppercase; color: var(--primary);
  background: rgba(217, 178, 48, 0.15); border: 1px solid rgba(217, 178, 48, 0.25);
  border-radius: 9999px; padding: 0.2rem 0.7rem; margin-bottom: 0.5rem;
}
.oc-bento-card h3 { color: #fff; font-size: 1.125rem; margin: 0 0 0.25rem; }
.oc-bento-card--hero h3 { font-size: 1.75rem; }
.oc-bento-card p { color: rgba(255, 255, 255, 0.65); font-size: 0.8125rem; margin: 0; }

/* ── Services — 2×2 staggered (Stitch) with aspect-[4/5] images ── */
.oc-svc-stagger {
  display: grid; grid-template-columns: 1fr 1fr;
  gap: 1.5rem; margin-bottom: 2.5rem; align-items: start;
}
.oc-svc-card2 {
  background: rgba(255, 255, 255, 0.04);
  border: 1px solid rgba(255, 255, 255, 0.08);
  border-radius: var(--radius-lg); overflow: hidden;
  transition: border-color var(--transition), transform var(--transition);
}
.oc-svc-card2:hover { border-color: rgba(217, 178, 48, 0.35); transform: translateY(-4px); }
.oc-svc-card2--offset { margin-top: 6rem; /* Stitch md:mt-24 */ }
.oc-svc-card2__img {
  position: relative; aspect-ratio: 4/5; overflow: hidden;
}
.oc-svc-card2__img img {
  width: 100%; height: 100%; object-fit: cover;
  transition: transform 0.6s ease;
}
.oc-svc-card2:hover .oc-svc-card2__img img { transform: scale(1.1); }
.oc-svc-card2__icon-badge {
  position: absolute; bottom: 1rem; left: 1rem;
  background: rgba(10, 15, 26, 0.6);
  backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
  border: 1px solid rgba(217, 178, 48, 0.2);
  border-radius: 9999px; padding: 0.5rem 1rem;
  display: flex; align-items: center; justify-content: center;
  color: var(--primary);
}
.oc-svc-card2__body { padding: 1.5rem; }
.oc-svc-card2__body h3 { font-size: 1.25rem; margin: 0 0 0.5rem; }
.oc-svc-card2__body p { font-size: 0.9rem; color: var(--text-muted); line-height: 1.65; margin: 0; }

/* ── Testimonials — Stitch centered pattern ── */
.oc-testimonial {
  max-width: 56rem; /* max-w-4xl */
  margin: 0 auto;
  text-align: center;
  position: relative;
  padding-top: 3rem;
}
.oc-testimonial__quote {
  font-family: var(--font-heading);
  font-size: 120px;
  color: rgba(217, 178, 48, 0.1);
  line-height: 0.5;
  position: absolute;
  top: 0; left: 50%;
  transform: translateX(-50%);
  pointer-events: none;
}
.oc-testimonial__text {
  font-family: var(--font-heading);
  font-size: clamp(1.25rem, 2.5vw, 1.75rem);
  font-style: italic;
  line-height: 1.6;
  color: var(--text);
  margin: 0 0 2rem;
  border: none;
  padding: 0;
  quotes: none;
}
.oc-testimonial__author {
  display: flex; align-items: center; justify-content: center; gap: 1rem;
}
.oc-testimonial__avatar {
  width: 56px; height: 56px; border-radius: 50%;
  border: 2px solid var(--primary);
  background: rgba(217, 178, 48, 0.1);
  display: flex; align-items: center; justify-content: center;
  font-weight: 700; color: var(--primary); font-size: 0.875rem;
}
.oc-testimonial__name {
  display: block; color: var(--text); font-size: 1rem;
}
.oc-testimonial__role {
  display: block; color: var(--text-muted); font-size: 0.8125rem;
}

/* ── Package card ── */
.oc-pkg-card__tag {
  position: absolute; top: 1rem; left: 1rem;
  background: var(--primary); color: #0a0f1a;
  font-size: 10px; font-weight: 900; letter-spacing: 0.08em; text-transform: uppercase;
  padding: 0.3rem 0.75rem; border-radius: 9999px;
}
.oc-pkg-card__img-wrap { position: relative; aspect-ratio: 16/10; overflow: hidden; }
.oc-pkg-card__img-wrap img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease; }
.oc-pkg-card:hover .oc-pkg-card__img-wrap img { transform: scale(1.1); }
.oc-pkg-card__price { font-size: 1.125rem; font-weight: 800; color: var(--primary); margin: 0.25rem 0 0.5rem; }
.oc-pkg-card__specs {
  display: flex; flex-wrap: wrap; gap: 0.5rem 1rem; margin: 0.5rem 0;
  padding: 0.75rem 0; border-top: 1px solid rgba(255,255,255,0.06); border-bottom: 1px solid rgba(255,255,255,0.06);
}
.oc-pkg-spec {
  display: inline-flex; align-items: center; gap: 5px;
  font-size: 0.8125rem; color: var(--text-muted);
}
.oc-pkg-spec svg { color: var(--primary); flex-shrink: 0; }
.oc-pkg-card__btn { width: 100%; justify-content: center; margin-top: 1rem; }

/* ── CTA Strip — Stitch: bg image + navy overlay ── */
.oc-cta-strip {
  position: relative;
  background-size: cover;
  background-position: center;
  padding: clamp(4rem, 8vw, 7rem) 0;
  overflow: hidden;
}
.oc-cta-strip__overlay {
  position: absolute; inset: 0;
  background: rgba(10, 15, 26, 0.9); /* bg-navy-deep/90 */
}
.oc-cta-strip__inner {
  position: relative; z-index: 2;
  display: flex; align-items: center; justify-content: space-between;
  gap: 2rem;
}
.oc-cta-strip__text h2 { margin-bottom: 0.5rem; }
.oc-cta-strip__text h2 em { font-style: italic; color: var(--primary); }
.oc-cta-strip__text p { color: var(--text-muted); margin: 0; font-size: 1.0625rem; }
.oc-cta-strip__actions {
  display: flex; gap: 1rem; flex-shrink: 0;
}
@media (max-width: 768px) {
  .oc-cta-strip__inner { flex-direction: column; text-align: center; }
  .oc-cta-strip__actions { justify-content: center; }
}

/* ── Responsive ── */
@media (max-width: 1024px) {
  .oc-vessel-grid, .oc-pkg-grid { grid-template-columns: 1fr 1fr; }
  .oc-why-us { grid-template-columns: 1fr; }
  .oc-why-us__img-col { display: none; }
  .oc-bento-grid { grid-template-columns: 1fr 1fr; grid-template-rows: auto auto; }
  .oc-bento-card--hero { grid-column: span 2; grid-row: span 1; }
  .oc-bento-card--wide { grid-column: span 2; }
}
@media (max-width: 768px) {
  .oc-vessel-grid, .oc-pkg-grid { grid-template-columns: 1fr; }
  .oc-svc-stagger { grid-template-columns: 1fr; }
  .oc-svc-card2--offset { margin-top: 0; }
  .oc-bento-grid { grid-template-columns: 1fr 1fr; }
  .oc-bento-card--hero { grid-column: span 2; }
}
@media (max-width: 480px) {
  .oc-bento-grid { grid-template-columns: 1fr; }
  .oc-bento-card--hero,
  .oc-bento-card--wide { grid-column: span 1; }
}
</style>

<?php get_footer(); ?>
