<?php
/**
 * Single BBC Package Template (Dark Luxury Theme)
 *
 * Displays a Boat Booking Core package with full details and booking form.
 *
 * @package OceanCharter
 */

if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

if ( ! have_posts() ) {
    echo '<p style="text-align:center;padding:80px 20px;color:#f0ece3;">' . esc_html__( 'Package not found.', 'ocean-charter' ) . '</p>';
    get_footer();
    return;
}

the_post();

$package_id = get_the_ID();

/* ── BBC Meta ── */
$price       = get_post_meta( $package_id, '_bbc_pkg_price', true );
$discount    = get_post_meta( $package_id, '_bbc_pkg_discount', true );
$label       = get_post_meta( $package_id, '_bbc_pkg_label', true );
$location    = get_post_meta( $package_id, '_bbc_pkg_location', true );
$min_guests  = (int) get_post_meta( $package_id, '_bbc_pkg_min_guests', true ) ?: 1;
$max_guests  = (int) get_post_meta( $package_id, '_bbc_pkg_max_guests', true ) ?: 20;
$durations   = get_post_meta( $package_id, '_bbc_pkg_durations', true );
$features    = get_post_meta( $package_id, '_bbc_pkg_features', true );
$whats_in    = get_post_meta( $package_id, '_bbc_pkg_whats_included', true );
$itinerary   = get_post_meta( $package_id, '_bbc_pkg_itinerary', true );
$gallery_ids = get_post_meta( $package_id, '_bbc_pkg_gallery', true );
$amenities   = get_post_meta( $package_id, '_bbc_pkg_amenities', true );
$valid_from    = get_post_meta( $package_id, '_bbc_pkg_valid_from', true );
$valid_to      = get_post_meta( $package_id, '_bbc_pkg_valid_to', true );
$difficulty    = get_post_meta( $package_id, '_bbc_pkg_difficulty', true );
$cancellation  = get_post_meta( $package_id, '_bbc_pkg_cancellation', true );

if ( ! is_array( $durations ) )   $durations   = [];
if ( ! is_array( $features ) )    $features    = [];
if ( ! is_array( $gallery_ids ) ) $gallery_ids = [];
if ( ! is_array( $amenities ) )   $amenities   = [];

/* Variables required by the BBC booking form template */
$addon_ids       = get_post_meta( $package_id, '_bbc_pkg_addon_ids', true );
if ( ! is_array( $addon_ids ) ) $addon_ids = [];
$global_addons   = get_option( 'bbc_global_addons', [] );
$included_addons = [];
foreach ( $global_addons as $cat ) {
    foreach ( ( $cat['items'] ?? [] ) as $item ) {
        if ( in_array( $item['id'] ?? '', $addon_ids, true ) ) {
            $included_addons[] = $item;
        }
    }
}
$page_settings   = get_option( 'bbc_page_settings', [] );
$thank_you_page  = ! empty( $page_settings['thank_you_page'] ) ? (int) $page_settings['thank_you_page'] : 0;
$gen_settings    = get_option( 'bbc_general_settings', [] );
$payment_gateway = ! empty( $gen_settings['payment_gateway'] ) ? $gen_settings['payment_gateway'] : 'manual';
$widget_id       = 'bbc-pkg-oc-' . $package_id;

/* Enqueue BBC assets + flatpickr for date picker */
if ( defined( 'BOAT_BOOKING_CORE_URL' ) && defined( 'BOAT_BOOKING_CORE_VERSION' ) ) {
    wp_enqueue_style( 'boat-booking-public', BOAT_BOOKING_CORE_URL . 'public/css/boat-booking-public.css', [], BOAT_BOOKING_CORE_VERSION );
}
wp_enqueue_script( 'flatpickr', 'https://cdn.jsdelivr.net/npm/flatpickr', [], '4.6.13', true );
wp_enqueue_style( 'flatpickr', 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css', [], '4.6.13' );

$hero_img = get_the_post_thumbnail_url( $package_id, 'full' ) ?: 'https://images.pexels.com/photos/1032650/pexels-photo-1032650.jpeg?auto=compress&cs=tinysrgb&w=1920';

$fmt_from = $valid_from ? date_i18n( 'j M Y', strtotime( $valid_from ) ) : '';
$fmt_to   = $valid_to   ? date_i18n( 'j M Y', strtotime( $valid_to ) )   : '';

$wa_url = function_exists( 'oc_whatsapp_url' )
    ? oc_whatsapp_url( 'Hello, I\'d like to book the ' . get_the_title() . ' package.' )
    : 'https://wa.me/' . get_theme_mod( 'oc_whatsapp_number', '15551234567' );

/* ── Amenity maps ── */
$amenity_icons = [
    'catering' => '&#127869;', 'open_bar' => '&#127865;', 'snacks' => '&#129386;', 'champagne' => '&#129346;', 'meals_included' => '&#127857;',
    'dj' => '&#127911;', 'live_music' => '&#127925;', 'sound_system' => '&#128266;', 'bluetooth' => '&#128246;', 'projector' => '&#128253;',
    'snorkeling' => '&#129343;', 'kayaking' => '&#128758;', 'paddleboard' => '&#127940;', 'fishing' => '&#127907;', 'diving' => '&#129341;',
    'ac' => '&#10052;', 'jacuzzi' => '&#9832;', 'sun_beds' => '&#128717;', 'crew_service' => '&#128104;&#8205;&#9992;&#65039;', 'towels' => '&#128705;', 'wifi' => '&#128246;',
    'life_jackets' => '&#129658;', 'first_aid' => '&#129657;', 'safety_equipment' => '&#9937;', 'gps' => '&#129517;',
];
$amenity_labels = [
    'catering' => 'Catering', 'open_bar' => 'Open Bar', 'snacks' => 'Snacks', 'champagne' => 'Champagne', 'meals_included' => 'Meals',
    'dj' => 'DJ', 'live_music' => 'Live Music', 'sound_system' => 'Sound System', 'bluetooth' => 'Bluetooth', 'projector' => 'Projector',
    'snorkeling' => 'Snorkeling', 'kayaking' => 'Kayaking', 'paddleboard' => 'Paddleboard', 'fishing' => 'Fishing', 'diving' => 'Diving',
    'ac' => 'A/C', 'jacuzzi' => 'Jacuzzi', 'sun_beds' => 'Sun Beds', 'crew_service' => 'Crew Service', 'towels' => 'Towels', 'wifi' => 'WiFi',
    'life_jackets' => 'Life Jackets', 'first_aid' => 'First Aid', 'safety_equipment' => 'Safety Equip.', 'gps' => 'GPS',
];

// Find the lowest price from durations
$lowest_price = 0;
if ( $price ) {
    $lowest_price = (float) $price;
} elseif ( ! empty( $durations ) ) {
    $prices = array_filter( array_map( function( $d ) { return floatval( $d['price'] ?? 0 ); }, $durations ) );
    $lowest_price = $prices ? min( $prices ) : 0;
}
?>

<main id="primary" class="site-main oc-single-bbc-package">

    <!-- ══ HERO ══════════════════════════════════════════════════════════════ -->
    <?php $_hero_pos = get_post_meta( get_the_ID(), '_oc_hero_position', true ) ?: 'center center'; ?>
    <section class="oc-bpkg-hero" style="background-image:url('<?php echo esc_url( $hero_img ); ?>');background-size:cover;background-position:<?php echo esc_attr( $_hero_pos ); ?>;">
        <div class="oc-bpkg-hero__overlay"></div>
        <div class="oc-bpkg-hero__content oc-container">
            <?php if ( $label ) : ?>
                <span class="oc-bpkg-badge"><?php echo esc_html( $label ); ?></span>
            <?php endif; ?>
            <h1 class="oc-bpkg-hero__title"><?php the_title(); ?></h1>
            <div class="oc-bpkg-pills-strip">
                <?php if ( $location ) : ?>
                    <span class="oc-spec-pill">
                        <svg width="14" height="14" viewBox="0 0 16 16" fill="none"><path d="M8 1C5.24 1 3 3.24 3 6c0 3.75 5 9 5 9s5-5.25 5-9c0-2.76-2.24-5-5-5zm0 6.75A1.75 1.75 0 1 1 8 4.25a1.75 1.75 0 0 1 0 3.5z" fill="currentColor"/></svg>
                        <?php echo esc_html( $location ); ?>
                    </span>
                <?php endif; ?>
                <?php if ( $lowest_price ) : ?>
                    <span class="oc-spec-pill">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        From $<?php echo esc_html( number_format( $lowest_price ) ); ?>
                    </span>
                <?php endif; ?>
                <?php if ( $max_guests > 1 ) : ?>
                    <span class="oc-spec-pill">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        <?php echo esc_html( $min_guests ); ?>&ndash;<?php echo esc_html( $max_guests ); ?> Guests
                    </span>
                <?php endif; ?>
                <?php if ( $difficulty ) :
                    $diff_labels = [ 'easy' => 'Easy — All Levels', 'moderate' => 'Moderate', 'challenging' => 'Challenging', 'expert' => 'Expert Only' ];
                ?>
                    <span class="oc-spec-pill">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                        <?php echo esc_html( $diff_labels[ $difficulty ] ?? ucfirst( $difficulty ) ); ?>
                    </span>
                <?php endif; ?>
                <?php if ( $cancellation ) :
                    $cancel_labels = [ 'flexible' => 'Free Cancellation', 'moderate' => 'Moderate Policy', 'strict' => 'Strict Policy' ];
                ?>
                    <span class="oc-spec-pill">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/></svg>
                        <?php echo esc_html( $cancel_labels[ $cancellation ] ?? ucfirst( $cancellation ) ); ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- ══ BODY ══════════════════════════════════════════════════════════════ -->
    <div class="oc-bpkg-body oc-container">

        <!-- Left column -->
        <div class="oc-bpkg-main">

            <!-- Description / Excerpt -->
            <?php $excerpt = get_the_excerpt(); if ( $excerpt ) : ?>
            <section class="oc-bpkg-section">
                <h2 class="oc-section-heading">About This Package</h2>
                <p class="oc-bpkg-desc"><?php echo wp_kses_post( $excerpt ); ?></p>
            </section>
            <?php endif; ?>

            <!-- Duration Options -->
            <?php if ( ! empty( $durations ) ) : ?>
            <section class="oc-bpkg-section">
                <h2 class="oc-section-heading">Duration &amp; Pricing</h2>
                <div class="oc-bpkg-durations-grid">
                    <?php foreach ( $durations as $idx => $dur ) :
                        $dp = floatval( $dur['price'] ?? 0 );
                        $dh = intval( $dur['hours'] ?? 0 );
                    ?>
                        <div class="oc-bpkg-duration-card<?php echo $idx === 0 ? ' oc-bpkg-duration-card--featured' : ''; ?>">
                            <?php if ( $idx === 0 ) : ?>
                                <span class="oc-bpkg-duration-card__popular">Most Popular</span>
                            <?php endif; ?>
                            <div class="oc-bpkg-duration-card__icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            </div>
                            <div class="oc-bpkg-duration-card__label"><?php echo esc_html( $dur['label'] ?? '' ); ?></div>
                            <?php if ( $dh ) : ?>
                                <div class="oc-bpkg-duration-card__hours"><?php echo $dh; ?> hours</div>
                            <?php endif; ?>
                            <?php if ( $dp ) : ?>
                                <div class="oc-bpkg-duration-card__price">$<?php echo number_format( $dp ); ?></div>
                                <div class="oc-bpkg-duration-card__per">per charter</div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

            <!-- Features / What's Included list -->
            <?php if ( ! empty( $features ) ) : ?>
            <section class="oc-bpkg-section">
                <h2 class="oc-section-heading">What&rsquo;s Included</h2>
                <div class="oc-inclusions-grid">
                    <?php foreach ( $features as $feat ) : ?>
                        <div class="oc-inclusion-item">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M5 13l4 4L19 7" stroke="#d9b230" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span><?php echo esc_html( $feat ); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

            <!-- Package Details (rich text) -->
            <?php if ( $whats_in ) : ?>
            <section class="oc-bpkg-section">
                <h2 class="oc-section-heading">Package Details</h2>
                <div class="oc-bpkg-prose"><?php echo wp_kses_post( wpautop( $whats_in ) ); ?></div>
            </section>
            <?php endif; ?>

            <!-- Amenities -->
            <?php if ( ! empty( $amenities ) ) : ?>
            <section class="oc-bpkg-section">
                <h2 class="oc-section-heading">Amenities</h2>
                <div class="oc-bpkg-amenities-grid">
                    <?php foreach ( $amenities as $key ) :
                        $icon  = $amenity_icons[ $key ]  ?? '&#10003;';
                        $lbl   = $amenity_labels[ $key ] ?? ucfirst( str_replace( '_', ' ', $key ) );
                    ?>
                        <span class="oc-bpkg-amenity-pill"><?php echo $icon; ?> <?php echo esc_html( $lbl ); ?></span>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

            <!-- Itinerary -->
            <?php if ( $itinerary ) : ?>
            <section class="oc-bpkg-section">
                <h2 class="oc-section-heading">Itinerary</h2>
                <div class="oc-bpkg-timeline">
                    <?php
                    $itin_lines = array_filter( array_map( 'trim', explode( "\n", $itinerary ) ) );
                    foreach ( $itin_lines as $line ) :
                        // Try to split "TIME — DESCRIPTION" or "TIME - DESCRIPTION"
                        if ( preg_match( '/^(.+?)\s*[—–\-]\s*(.+)$/', $line, $m ) ) :
                    ?>
                        <div class="oc-bpkg-timeline__item">
                            <div class="oc-bpkg-timeline__dot"></div>
                            <div class="oc-bpkg-timeline__time"><?php echo esc_html( $m[1] ); ?></div>
                            <div class="oc-bpkg-timeline__desc"><?php echo esc_html( $m[2] ); ?></div>
                        </div>
                    <?php else : ?>
                        <div class="oc-bpkg-timeline__item">
                            <div class="oc-bpkg-timeline__dot"></div>
                            <div class="oc-bpkg-timeline__desc" style="grid-column: span 2;"><?php echo esc_html( $line ); ?></div>
                        </div>
                    <?php endif; endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

            <!-- Gallery -->
            <?php if ( ! empty( $gallery_ids ) ) : ?>
            <section class="oc-bpkg-section">
                <h2 class="oc-section-heading">Gallery</h2>
                <div class="oc-bpkg-gallery">
                    <?php foreach ( $gallery_ids as $att_id ) :
                        $src = wp_get_attachment_image_url( $att_id, 'medium' );
                        if ( ! $src ) continue;
                    ?>
                    <a href="#" class="oc-bpkg-gallery-item" data-lightbox="<?php echo esc_url( wp_get_attachment_image_url( $att_id, 'full' ) ); ?>">
                        <img src="<?php echo esc_url( $src ); ?>" alt="" loading="lazy">
                    </a>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

            <!-- Validity -->
            <?php if ( $fmt_from || $fmt_to ) : ?>
            <section class="oc-bpkg-section">
                <h2 class="oc-section-heading">Availability</h2>
                <div class="oc-bpkg-validity">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#d9b230" stroke-width="2" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    <?php if ( $fmt_from && $fmt_to ) : ?>
                        <strong><?php echo esc_html( $fmt_from ); ?></strong> &mdash; <strong><?php echo esc_html( $fmt_to ); ?></strong>
                    <?php elseif ( $fmt_to ) : ?>
                        Available until <strong><?php echo esc_html( $fmt_to ); ?></strong>
                    <?php else : ?>
                        Available from <strong><?php echo esc_html( $fmt_from ); ?></strong>
                    <?php endif; ?>
                </div>
            </section>
            <?php endif; ?>

            <!-- Good to Know (difficulty, cancellation, validity) -->
            <?php if ( $difficulty || $cancellation ) : ?>
            <section class="oc-bpkg-section">
                <h2 class="oc-section-heading">Good to Know</h2>
                <div class="oc-bpkg-info-cards">
                    <?php if ( $difficulty ) :
                        $diff_map = [
                            'easy'        => [ 'label' => 'Easy',        'desc' => 'Suitable for all ages and experience levels. No prior boating experience required.', 'icon' => '&#9733;' ],
                            'moderate'    => [ 'label' => 'Moderate',    'desc' => 'Some basic fitness recommended. Suitable for most guests.', 'icon' => '&#9733;&#9733;' ],
                            'challenging' => [ 'label' => 'Challenging', 'desc' => 'Good fitness level required. Prior water experience recommended.', 'icon' => '&#9733;&#9733;&#9733;' ],
                            'expert'      => [ 'label' => 'Expert',      'desc' => 'For experienced water sports enthusiasts only.', 'icon' => '&#9733;&#9733;&#9733;&#9733;' ],
                        ];
                        $d = $diff_map[ $difficulty ] ?? [ 'label' => ucfirst( $difficulty ), 'desc' => '', 'icon' => '&#9733;' ];
                    ?>
                        <div class="oc-bpkg-info-card">
                            <div class="oc-bpkg-info-card__icon">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#d9b230" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                            </div>
                            <div class="oc-bpkg-info-card__text">
                                <strong>Difficulty: <?php echo esc_html( $d['label'] ); ?></strong>
                                <span class="oc-bpkg-info-card__stars"><?php echo $d['icon']; ?></span>
                                <?php if ( $d['desc'] ) : ?>
                                    <p><?php echo esc_html( $d['desc'] ); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ( $cancellation ) :
                        $cancel_map = [
                            'flexible' => [ 'label' => 'Flexible Cancellation', 'desc' => 'Full refund if cancelled at least 24 hours before departure.', 'color' => '#25d366' ],
                            'moderate' => [ 'label' => 'Moderate Cancellation', 'desc' => 'Full refund if cancelled 7 days before departure. 50% refund within 3–7 days.', 'color' => '#d9b230' ],
                            'strict'   => [ 'label' => 'Strict Cancellation',   'desc' => 'Non-refundable within 14 days of departure. Full refund only 14+ days prior.', 'color' => '#e74c3c' ],
                        ];
                        $c = $cancel_map[ $cancellation ] ?? [ 'label' => ucfirst( $cancellation ) . ' Policy', 'desc' => '', 'color' => '#d9b230' ];
                    ?>
                        <div class="oc-bpkg-info-card">
                            <div class="oc-bpkg-info-card__icon">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="<?php echo esc_attr( $c['color'] ); ?>" stroke-width="2"><path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/></svg>
                            </div>
                            <div class="oc-bpkg-info-card__text">
                                <strong><?php echo esc_html( $c['label'] ); ?></strong>
                                <?php if ( $c['desc'] ) : ?>
                                    <p><?php echo esc_html( $c['desc'] ); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
            <?php endif; ?>

            <!-- Post content -->
            <?php
            $content = get_the_content();
            if ( trim( $content ) ) : ?>
            <section class="oc-bpkg-section">
                <h2 class="oc-section-heading">Experience Details</h2>
                <div class="oc-bpkg-prose"><?php the_content(); ?></div>
            </section>
            <?php endif; ?>

        </div><!-- /.oc-bpkg-main -->

        <!-- Sticky sidebar: Booking -->
        <aside class="oc-bpkg-sidebar">
            <div class="oc-booking-card">
                <h3 class="oc-booking-card__title">Book This Package</h3>

                <?php if ( $lowest_price ) : ?>
                    <div class="oc-booking-card__price-primary">
                        <span class="oc-price-label">From</span>
                        <span class="oc-price-amount">$<?php echo esc_html( number_format( $lowest_price ) ); ?></span>
                    </div>
                    <?php if ( $discount ) : ?>
                        <div class="oc-bpkg-discount-badge"><?php echo esc_html( $discount ); ?>% OFF</div>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if ( $max_guests > 1 ) : ?>
                    <div class="oc-booking-card__meta-row">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                        <?php echo esc_html( $min_guests ); ?>&ndash;<?php echo esc_html( $max_guests ); ?> guests
                    </div>
                <?php endif; ?>

                <!-- BBC Booking Form -->
                <div class="oc-bpkg-booking-form-wrap">
                    <?php
                    if ( ! empty( $durations ) ) {
                        // Try to include the BBC package booking form template directly
                        $bbc_template = defined( 'BOAT_BOOKING_CORE_PATH' )
                            ? BOAT_BOOKING_CORE_PATH . 'templates/package-booking-form.php'
                            : '';

                        if ( $bbc_template && file_exists( $bbc_template ) ) {
                            include $bbc_template;
                        } else {
                            // Fallback to shortcode
                            echo do_shortcode( '[bbc_booking_form package_id="' . $package_id . '"]' );
                        }
                    } else {
                        // No durations -- show CTA buttons instead
                    ?>
                        <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="oc-btn-gold oc-btn-full">Enquire About This Package</a>
                    <?php } ?>
                </div>

                <a href="<?php echo esc_url( $wa_url ); ?>" class="oc-btn-whatsapp oc-btn-full" target="_blank" rel="noopener noreferrer">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    WhatsApp Us
                </a>

                <p class="oc-booking-card__note">Prices include crew, fuel &amp; provisions</p>
            </div>
        </aside>

    </div><!-- /.oc-bpkg-body -->

    <!-- ══ RELATED PACKAGES ═══════════════════════════════════════════════════ -->
    <?php
    $related = new WP_Query( [
        'post_type'      => 'bbc_package',
        'posts_per_page' => 3,
        'post__not_in'   => [ $package_id ],
        'orderby'        => 'rand',
    ] );
    if ( $related->have_posts() ) : ?>
    <section class="oc-related-section oc-container">
        <h2 class="oc-section-heading">Other Packages</h2>
        <div class="oc-related-grid">
            <?php while ( $related->have_posts() ) : $related->the_post();
                $rel_id    = get_the_ID();
                $rel_thumb = get_the_post_thumbnail_url( $rel_id, 'medium_large' ) ?: $hero_img;
                $rel_price = get_post_meta( $rel_id, '_bbc_pkg_price', true );
                $rel_label = get_post_meta( $rel_id, '_bbc_pkg_label', true );
                $rel_loc   = get_post_meta( $rel_id, '_bbc_pkg_location', true );
            ?>
                <a href="<?php echo esc_url( get_permalink( $rel_id ) ); ?>" class="oc-related-card">
                    <div class="oc-related-card__img" style="background-image:url('<?php echo esc_url( $rel_thumb ); ?>');">
                        <?php if ( $rel_label ) : ?>
                            <span class="oc-related-card__badge"><?php echo esc_html( $rel_label ); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="oc-related-card__body">
                        <h3 class="oc-related-card__title"><?php the_title(); ?></h3>
                        <?php if ( $rel_loc ) : ?>
                            <span class="oc-related-card__excerpt"><?php echo esc_html( $rel_loc ); ?></span>
                        <?php endif; ?>
                        <?php if ( $rel_price ) : ?>
                            <span class="oc-related-card__price">From $<?php echo esc_html( number_format( (float) $rel_price ) ); ?></span>
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
                    <h2>Ready to <span class="text-primary">Book?</span></h2>
                    <p>Let us craft your perfect charter experience.</p>
                </div>
                <div class="oc-cta-strip__actions">
                    <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn-primary">Book Now</a>
                    <a href="<?php echo esc_url( $wa_url ); ?>" class="btn-secondary" target="_blank" rel="noopener noreferrer">WhatsApp Us</a>
                </div>
            </div>
        </div>
    </section>

</main>

<style>
/* ── Ocean Charter: Single BBC Package (Dark Luxury) ─────────────────────── */

.oc-single-bbc-package {
    background: #0a0f1a;
    color: #f0ece3;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    font-size: 16px;
    line-height: 1.7;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
.oc-single-bbc-package * { box-sizing: border-box; }

/* ── Hero ── */
.oc-bpkg-hero {
    position: relative;
    min-height: 55vh;
    background-size: cover;
    background-position: center;
    display: flex;
    align-items: flex-end;
    padding-bottom: 56px;
}
.oc-bpkg-hero__overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(10,15,26,0.97) 0%, rgba(10,15,26,0.4) 50%, rgba(10,15,26,0.1) 100%);
}
.oc-bpkg-hero__content {
    position: relative;
    z-index: 2;
    max-width: 900px;
}
.oc-bpkg-badge {
    display: inline-block;
    background: #d9b230;
    color: #0a0f1a;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    padding: 5px 14px;
    border-radius: 999px;
    margin-bottom: 20px;
}
.oc-bpkg-hero__title {
    font-family: var(--font-heading, 'Playfair Display', Georgia, serif);
    font-size: clamp(34px, 4.5vw, 62px);
    font-weight: 400;
    color: #f8fafc;
    line-height: 1.1;
    margin: 0 0 24px;
}
.oc-bpkg-pills-strip {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}
.oc-single-bbc-package .oc-spec-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(17,26,40,0.75);
    border: 1px solid rgba(217,178,48,0.3);
    backdrop-filter: blur(8px);
    color: #f0ece3;
    font-size: 13px;
    font-weight: 500;
    padding: 7px 14px;
    border-radius: 999px;
}
.oc-single-bbc-package .oc-spec-pill svg { color: #d9b230; flex-shrink: 0; }

/* ── Body layout ── */
.oc-bpkg-body {
    display: grid;
    grid-template-columns: 1fr 420px;
    gap: 60px;
    padding-top: 64px;
    padding-bottom: 100px;
    align-items: start;
}
.oc-bpkg-section {
    margin-bottom: 56px;
}
.oc-single-bbc-package .oc-section-heading {
    font-family: var(--font-heading, 'Playfair Display', Georgia, serif);
    font-size: 26px;
    font-weight: 400;
    color: #f8fafc;
    margin: 0 0 28px;
    padding-bottom: 14px;
    border-bottom: 1px solid rgba(255,255,255,0.08);
}
.oc-bpkg-desc {
    font-size: 17px;
    line-height: 1.85;
    color: rgba(240,236,227,0.8);
    letter-spacing: 0.01em;
}
.oc-bpkg-prose {
    font-size: 16px;
    line-height: 1.85;
    color: rgba(240,236,227,0.8);
    letter-spacing: 0.01em;
}
.oc-bpkg-prose p { margin: 0 0 18px; }
.oc-bpkg-prose h3, .oc-bpkg-prose h4 {
    font-family: var(--font-heading, 'Playfair Display', Georgia, serif);
    color: #f8fafc;
    margin: 28px 0 12px;
    font-weight: 400;
}

/* ── Duration & Pricing cards (premium) ── */
.oc-bpkg-durations-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
}
.oc-bpkg-duration-card {
    position: relative;
    background: linear-gradient(135deg, #111a28 0%, #0d1520 100%);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 16px;
    padding: 32px 24px 28px;
    text-align: center;
    transition: border-color 0.3s, transform 0.3s, box-shadow 0.3s;
}
.oc-bpkg-duration-card:hover {
    border-color: rgba(217,178,48,0.5);
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.3);
}
.oc-bpkg-duration-card--featured {
    border-color: rgba(217,178,48,0.4);
    background: linear-gradient(135deg, #141f30 0%, #111a28 100%);
}
.oc-bpkg-duration-card__popular {
    position: absolute;
    top: -10px;
    left: 50%;
    transform: translateX(-50%);
    background: #d9b230;
    color: #0a0f1a;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    padding: 3px 14px;
    border-radius: 999px;
    white-space: nowrap;
}
.oc-bpkg-duration-card__icon {
    color: #d9b230;
    margin-bottom: 14px;
    opacity: 0.7;
}
.oc-bpkg-duration-card__label {
    font-size: 17px;
    font-weight: 600;
    color: #f8fafc;
    margin-bottom: 4px;
    letter-spacing: 0.01em;
}
.oc-bpkg-duration-card__hours {
    font-size: 13px;
    color: rgba(240,236,227,0.45);
    margin-bottom: 16px;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}
.oc-bpkg-duration-card__price {
    color: #d9b230;
    font-weight: 700;
    font-size: 28px;
    line-height: 1;
    margin-bottom: 2px;
}
.oc-bpkg-duration-card__per {
    font-size: 12px;
    color: rgba(240,236,227,0.35);
    text-transform: uppercase;
    letter-spacing: 0.06em;
}

/* ── Features / What's Included (2 columns) ── */
.oc-single-bbc-package .oc-inclusions-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px 24px;
}
.oc-single-bbc-package .oc-inclusion-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    color: #f0ece3;
    font-size: 15px;
    line-height: 1.6;
    font-weight: 400;
    padding: 10px 14px;
    background: rgba(255,255,255,0.02);
    border: 1px solid rgba(255,255,255,0.05);
    border-radius: 10px;
    transition: border-color 0.2s;
}
.oc-single-bbc-package .oc-inclusion-item:hover { border-color: rgba(217,178,48,0.25); }
.oc-single-bbc-package .oc-inclusion-item svg { flex-shrink: 0; margin-top: 3px; }

/* ── Amenities ── */
.oc-bpkg-amenities-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}
.oc-bpkg-amenity-pill {
    background: #111a28;
    border: 1px solid rgba(255,255,255,0.08);
    color: rgba(240,236,227,0.85);
    font-size: 13px;
    font-weight: 500;
    padding: 9px 16px;
    border-radius: 999px;
    transition: border-color 0.2s, background 0.2s;
}
.oc-bpkg-amenity-pill:hover {
    border-color: rgba(217,178,48,0.4);
    background: rgba(217,178,48,0.06);
}

/* ── Itinerary timeline ── */
.oc-bpkg-timeline {
    display: flex;
    flex-direction: column;
    gap: 0;
    position: relative;
    padding-left: 28px;
}
.oc-bpkg-timeline::before {
    content: '';
    position: absolute;
    left: 7px;
    top: 8px;
    bottom: 8px;
    width: 2px;
    background: linear-gradient(to bottom, #d9b230 0%, rgba(217,178,48,0.15) 100%);
    border-radius: 2px;
}
.oc-bpkg-timeline__item {
    display: grid;
    grid-template-columns: auto 1fr;
    gap: 6px 14px;
    padding: 14px 0;
    position: relative;
}
.oc-bpkg-timeline__item + .oc-bpkg-timeline__item {
    border-top: 1px solid rgba(255,255,255,0.04);
}
.oc-bpkg-timeline__dot {
    position: absolute;
    left: -28px;
    top: 18px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: #0a0f1a;
    border: 2px solid #d9b230;
    box-shadow: 0 0 0 3px rgba(217,178,48,0.1);
}
.oc-bpkg-timeline__time {
    font-size: 14px;
    font-weight: 700;
    color: #d9b230;
    white-space: nowrap;
    letter-spacing: 0.02em;
}
.oc-bpkg-timeline__desc {
    font-size: 15px;
    color: rgba(240,236,227,0.8);
    line-height: 1.5;
}

/* ── Validity ── */
.oc-bpkg-validity {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 15px;
    color: rgba(240,236,227,0.75);
    background: #111a28;
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 12px;
    padding: 16px 20px;
}
.oc-bpkg-validity strong { color: #f0ece3; }

/* ── Good to Know info cards ── */
.oc-bpkg-info-cards {
    display: flex;
    flex-direction: column;
    gap: 16px;
}
.oc-bpkg-info-card {
    display: flex;
    gap: 16px;
    align-items: flex-start;
    background: #111a28;
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 12px;
    padding: 20px;
    transition: border-color 0.2s;
}
.oc-bpkg-info-card:hover { border-color: rgba(217,178,48,0.3); }
.oc-bpkg-info-card__icon {
    flex-shrink: 0;
    width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(217,178,48,0.1);
    border-radius: 10px;
}
.oc-bpkg-info-card__text strong {
    display: block;
    font-size: 15px;
    color: #f8fafc;
    margin-bottom: 4px;
}
.oc-bpkg-info-card__stars {
    color: #d9b230;
    font-size: 13px;
    letter-spacing: 2px;
    display: block;
    margin-bottom: 6px;
}
.oc-bpkg-info-card__text p {
    margin: 0;
    font-size: 14px;
    line-height: 1.6;
    color: rgba(240,236,227,0.6);
}

/* ── Gallery ── */
.oc-bpkg-gallery {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 12px;
}
.oc-bpkg-gallery-item {
    display: block;
    overflow: hidden;
    border-radius: 10px;
    border: 1px solid rgba(255,255,255,0.08);
    cursor: pointer;
    transition: border-color 0.2s;
}
.oc-bpkg-gallery-item:hover { border-color: #d9b230; }
.oc-bpkg-gallery-item img {
    width: 100%;
    aspect-ratio: 4/3;
    object-fit: cover;
    display: block;
    transition: transform 0.3s;
}
.oc-bpkg-gallery-item:hover img { transform: scale(1.05); }

/* ══════════════════════════════════════════════════════════════════════════
   SIDEBAR — Booking card + BBC form overrides
   ══════════════════════════════════════════════════════════════════════════ */

.oc-bpkg-sidebar {
    position: sticky;
    top: 100px;
}
.oc-single-bbc-package .oc-booking-card {
    background: #111a28;
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 1rem;
    padding: 28px 24px;
}
.oc-single-bbc-package .oc-booking-card__title {
    font-family: var(--font-heading, 'Playfair Display', Georgia, serif);
    font-size: 20px;
    font-weight: 400;
    color: #f8fafc;
    margin: 0 0 20px;
    padding-bottom: 16px;
    border-bottom: 1px solid rgba(255,255,255,0.08);
    text-align: center;
}
.oc-single-bbc-package .oc-booking-card__price-primary {
    display: flex;
    align-items: baseline;
    gap: 8px;
    justify-content: center;
    margin-bottom: 12px;
}
.oc-single-bbc-package .oc-price-label {
    font-size: 14px;
    color: rgba(240,236,227,0.5);
}
.oc-single-bbc-package .oc-price-amount {
    font-size: 38px;
    font-weight: 700;
    color: #d9b230;
    line-height: 1;
}
.oc-bpkg-discount-badge {
    display: inline-block;
    background: rgba(217,178,48,0.15);
    color: #d9b230;
    font-size: 13px;
    font-weight: 700;
    padding: 4px 12px;
    border-radius: 999px;
    border: 1px solid rgba(217,178,48,0.3);
    margin-bottom: 16px;
}
.oc-single-bbc-package .oc-booking-card__meta-row {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    color: rgba(240,236,227,0.5);
    font-size: 14px;
    margin-bottom: 20px;
}
.oc-single-bbc-package .oc-booking-card__meta-row svg { color: #d9b230; }

/* ── BBC Booking Form: Complete Dark Luxury Override ── */
.oc-bpkg-booking-form-wrap {
    margin-bottom: 16px;
}

/* Global widget reset */
.oc-bpkg-booking-form-wrap .bbc-pkg-booking-widget,
.oc-bpkg-booking-form-wrap .bbc-booking-widget,
.oc-bpkg-booking-form-wrap .bbc-booking-form {
    background: transparent !important;
    border: none !important;
    box-shadow: none !important;
    padding: 0 !important;
    color: #f0ece3 !important;
    font-family: inherit !important;
}

/* ALL text inside booking widget */
.oc-bpkg-booking-form-wrap h3,
.oc-bpkg-booking-form-wrap h4,
.oc-bpkg-booking-form-wrap p,
.oc-bpkg-booking-form-wrap span,
.oc-bpkg-booking-form-wrap div,
.oc-bpkg-booking-form-wrap label {
    color: rgba(240,236,227,0.8) !important;
}
.oc-bpkg-booking-form-wrap h3 {
    color: #f8fafc !important;
    font-family: var(--font-heading, 'Playfair Display', Georgia, serif) !important;
    font-weight: 400 !important;
}

/* Form labels */
.oc-bpkg-booking-form-wrap label,
.oc-bpkg-booking-form-wrap .bbc-field-group label {
    color: rgba(240,236,227,0.7) !important;
    font-size: 14px !important;
    font-weight: 600 !important;
}

/* Inputs, selects, textareas */
.oc-bpkg-booking-form-wrap input[type="text"],
.oc-bpkg-booking-form-wrap input[type="email"],
.oc-bpkg-booking-form-wrap input[type="tel"],
.oc-bpkg-booking-form-wrap input[type="date"],
.oc-bpkg-booking-form-wrap select,
.oc-bpkg-booking-form-wrap textarea {
    background: rgba(255,255,255,0.04) !important;
    border: 1.5px solid rgba(255,255,255,0.12) !important;
    color: #f0ece3 !important;
    border-radius: 10px !important;
    font-size: 15px !important;
    padding: 12px 16px !important;
    transition: border-color 0.2s !important;
}
.oc-bpkg-booking-form-wrap input:focus,
.oc-bpkg-booking-form-wrap select:focus,
.oc-bpkg-booking-form-wrap textarea:focus {
    border-color: #d9b230 !important;
    outline: none !important;
    box-shadow: 0 0 0 3px rgba(217,178,48,0.1) !important;
}
.oc-bpkg-booking-form-wrap input::placeholder,
.oc-bpkg-booking-form-wrap textarea::placeholder {
    color: rgba(240,236,227,0.3) !important;
}
.oc-bpkg-booking-form-wrap select option {
    background: #111a28 !important;
    color: #f0ece3 !important;
}

/* Step indicator */
.oc-bpkg-booking-form-wrap .bbc-booking-steps {
    margin-bottom: 24px !important;
    padding: 0 !important;
}
.oc-bpkg-booking-form-wrap .bbc-step-number {
    background: rgba(255,255,255,0.04) !important;
    border: 2px solid rgba(255,255,255,0.12) !important;
    color: rgba(240,236,227,0.35) !important;
}
.oc-bpkg-booking-form-wrap .bbc-step.active .bbc-step-number {
    background: #d9b230 !important;
    border-color: #d9b230 !important;
    color: #0a0f1a !important;
    box-shadow: 0 0 0 4px rgba(217,178,48,0.15) !important;
}
.oc-bpkg-booking-form-wrap .bbc-step.completed .bbc-step-number {
    background: #d9b230 !important;
    border-color: #d9b230 !important;
    color: #0a0f1a !important;
}
.oc-bpkg-booking-form-wrap .bbc-step-label { color: rgba(240,236,227,0.35) !important; }
.oc-bpkg-booking-form-wrap .bbc-step.active .bbc-step-label,
.oc-bpkg-booking-form-wrap .bbc-step.completed .bbc-step-label { color: #d9b230 !important; }
.oc-bpkg-booking-form-wrap .bbc-step-line { background: rgba(255,255,255,0.06) !important; }

/* Duration option cards (in sidebar) */
.oc-bpkg-booking-form-wrap .bbc-pkg-dur-option {
    background: rgba(255,255,255,0.02) !important;
    border: 1.5px solid rgba(255,255,255,0.1) !important;
    border-radius: 12px !important;
    transition: all 0.2s !important;
}
.oc-bpkg-booking-form-wrap .bbc-pkg-dur-option:hover,
.oc-bpkg-booking-form-wrap .bbc-pkg-dur-option.selected {
    border-color: #d9b230 !important;
    background: rgba(217,178,48,0.06) !important;
}
.oc-bpkg-booking-form-wrap .bbc-pkg-dur-label {
    color: #f0ece3 !important;
    font-weight: 600 !important;
}
.oc-bpkg-booking-form-wrap .bbc-pkg-dur-meta { color: rgba(240,236,227,0.45) !important; }
.oc-bpkg-booking-form-wrap .bbc-pkg-dur-price {
    color: #d9b230 !important;
    font-weight: 700 !important;
}

/* Price summary box */
.oc-bpkg-booking-form-wrap .bbc-pkg-price-summary {
    background: rgba(217,178,48,0.06) !important;
    border: 1px solid rgba(217,178,48,0.2) !important;
    border-radius: 12px !important;
    padding: 16px 20px !important;
}
.oc-bpkg-booking-form-wrap .bbc-pkg-selected-price {
    color: #d9b230 !important;
    font-size: 24px !important;
    font-weight: 700 !important;
}
.oc-bpkg-booking-form-wrap .bbc-pkg-selected-label {
    color: rgba(240,236,227,0.4) !important;
}

/* Confirm summary card — override inline bg/border on every child */
.oc-bpkg-booking-form-wrap .bbc-pkg-confirm-summary {
    background: rgba(255,255,255,0.03) !important;
    border: 1px solid rgba(255,255,255,0.08) !important;
    border-radius: 12px !important;
    color: #f0ece3 !important;
}
.oc-bpkg-booking-form-wrap .bbc-pkg-confirm-summary div {
    border-color: rgba(255,255,255,0.06) !important;
}
.oc-bpkg-booking-form-wrap .bbc-pkg-confirm-summary span[style*="font-weight:700"],
.oc-bpkg-booking-form-wrap .bbc-pkg-confirm-summary span[style*="font-weight:600"] {
    color: #f0ece3 !important;
}
.oc-bpkg-booking-form-wrap .bbc-pkg-summary-total {
    color: #d9b230 !important;
    font-size: 20px !important;
}

/* Payment method cards */
.oc-bpkg-booking-form-wrap .bbc-pkg-payment-card {
    background: rgba(255,255,255,0.02) !important;
    border: 1.5px solid rgba(255,255,255,0.1) !important;
    border-radius: 12px !important;
    color: #f0ece3 !important;
    transition: all 0.2s !important;
}
.oc-bpkg-booking-form-wrap .bbc-pkg-payment-card.is-selected {
    border-color: #d9b230 !important;
    background: rgba(217,178,48,0.06) !important;
}
.oc-bpkg-booking-form-wrap .bbc-pkg-payment-section div[style*="color:#444"] {
    color: rgba(240,236,227,0.6) !important;
}

/* Buttons */
.oc-bpkg-booking-form-wrap .bbc-btn {
    border-radius: 10px !important;
    font-family: inherit !important;
    font-weight: 600 !important;
    transition: all 0.2s !important;
}
.oc-bpkg-booking-form-wrap .bbc-btn-primary {
    background: #d9b230 !important;
    color: #0a0f1a !important;
    border: none !important;
    font-weight: 700 !important;
    padding: 14px 24px !important;
    border-radius: 10px !important;
    cursor: pointer !important;
    font-size: 15px !important;
    box-shadow: 0 2px 12px rgba(217,178,48,0.2) !important;
    background-image: none !important;
}
.oc-bpkg-booking-form-wrap .bbc-btn-primary:hover {
    background: #e8c43e !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 16px rgba(217,178,48,0.3) !important;
}
.oc-bpkg-booking-form-wrap .bbc-btn-outline {
    background: transparent !important;
    color: rgba(240,236,227,0.6) !important;
    border: 1.5px solid rgba(255,255,255,0.12) !important;
    border-radius: 10px !important;
}
.oc-bpkg-booking-form-wrap .bbc-btn-outline:hover {
    border-color: #d9b230 !important;
    color: #d9b230 !important;
    background: rgba(217,178,48,0.04) !important;
}

/* Add-ons toggle & panel */
.oc-bpkg-booking-form-wrap .bbc-pkg-addons-toggle {
    background: rgba(255,255,255,0.02) !important;
    border: 1.5px dashed rgba(255,255,255,0.12) !important;
    border-radius: 10px !important;
    color: rgba(240,236,227,0.6) !important;
}
.oc-bpkg-booking-form-wrap .bbc-pkg-addons-toggle:hover {
    border-color: rgba(217,178,48,0.3) !important;
    color: #d9b230 !important;
}
.oc-bpkg-booking-form-wrap .bbc-pkg-addons-panel {
    background: rgba(255,255,255,0.02) !important;
    border: 1.5px solid rgba(255,255,255,0.08) !important;
    border-radius: 10px !important;
}
.oc-bpkg-booking-form-wrap .bbc-pkg-addons-panel label {
    border-color: rgba(255,255,255,0.05) !important;
}
.oc-bpkg-booking-form-wrap .bbc-pkg-addons-panel span[style*="color:#0066cc"] {
    color: #d9b230 !important;
}

/* Included addons box */
.oc-bpkg-booking-form-wrap div[style*="background:#f8f9fa"] {
    background: rgba(255,255,255,0.03) !important;
    border: 1px solid rgba(255,255,255,0.06) !important;
    border-radius: 12px !important;
}
.oc-bpkg-booking-form-wrap div[style*="background:#f8f9fa"] h4 {
    color: #f0ece3 !important;
}
.oc-bpkg-booking-form-wrap div[style*="border-bottom:1px solid #eee"] {
    border-color: rgba(255,255,255,0.06) !important;
}

/* Warning/info boxes */
.oc-bpkg-booking-form-wrap div[style*="background:#fff3cd"] {
    background: rgba(217,178,48,0.1) !important;
    border-color: rgba(217,178,48,0.25) !important;
    color: #d9b230 !important;
}

/* Error/success states */
.oc-bpkg-booking-form-wrap .bbc-pkg-booking-error {
    background: rgba(231,76,60,0.1) !important;
    border: 1px solid rgba(231,76,60,0.3) !important;
    color: #e74c3c !important;
    border-radius: 10px !important;
}
.oc-bpkg-booking-form-wrap .bbc-pkg-booking-success {
    background: rgba(37,211,102,0.08) !important;
    border: 1px solid rgba(37,211,102,0.25) !important;
    color: #25d366 !important;
    border-radius: 10px !important;
}

/* Override inline color:#666, color:#444, color:#888, color:#222 etc */
.oc-bpkg-booking-form-wrap span[style*="color:#666"],
.oc-bpkg-booking-form-wrap span[style*="color:#444"],
.oc-bpkg-booking-form-wrap div[style*="color:#444"],
.oc-bpkg-booking-form-wrap p[style*="color:#666"],
.oc-bpkg-booking-form-wrap div[style*="color:#666"] {
    color: rgba(240,236,227,0.5) !important;
}
.oc-bpkg-booking-form-wrap span[style*="color:#888"],
.oc-bpkg-booking-form-wrap div[style*="color:#888"] {
    color: rgba(240,236,227,0.4) !important;
}
.oc-bpkg-booking-form-wrap span[style*="color:#222"],
.oc-bpkg-booking-form-wrap div[style*="color:#222"],
.oc-bpkg-booking-form-wrap div[style*="color:#333"] {
    color: #f0ece3 !important;
}
.oc-bpkg-booking-form-wrap span[style*="color:#0066cc"] {
    color: #d9b230 !important;
}
/* Override inline background:#f0f6ff, border:#ddd */
.oc-bpkg-booking-form-wrap div[style*="background:#f0f6ff"] {
    background: rgba(217,178,48,0.06) !important;
}
.oc-bpkg-booking-form-wrap *[style*="border:1.5px solid #ddd"],
.oc-bpkg-booking-form-wrap *[style*="border: 1.5px solid #ddd"] {
    border-color: rgba(255,255,255,0.1) !important;
}
.oc-bpkg-booking-form-wrap div[style*="border-bottom:1px solid #eee"],
.oc-bpkg-booking-form-wrap div[style*="border-top:1px solid #eee"] {
    border-color: rgba(255,255,255,0.06) !important;
}
/* Override background:#fff on addons panel */
.oc-bpkg-booking-form-wrap div[style*="background:#fff"] {
    background: rgba(255,255,255,0.02) !important;
}
.oc-bpkg-booking-form-wrap label[style*="border-bottom:1px solid #f5f5f5"] {
    border-color: rgba(255,255,255,0.04) !important;
}
.oc-bpkg-booking-form-wrap span[style*="color:#777"] {
    color: rgba(240,236,227,0.4) !important;
}
/* Checkboxes styling */
.oc-bpkg-booking-form-wrap input[type="checkbox"] {
    accent-color: #d9b230 !important;
}

/* ── Flatpickr calendar dark theme ── */
.flatpickr-calendar {
    background: #111a28 !important;
    border: 1px solid rgba(255,255,255,0.12) !important;
    box-shadow: 0 12px 40px rgba(0,0,0,0.6) !important;
    border-radius: 12px !important;
}
.flatpickr-months, .flatpickr-month {
    background: #111a28 !important;
    border-radius: 12px 12px 0 0 !important;
}
.flatpickr-current-month .flatpickr-monthDropdown-months,
.flatpickr-current-month input.cur-year {
    color: #f0ece3 !important;
    background: transparent !important;
    font-weight: 600 !important;
}
.flatpickr-months .flatpickr-prev-month,
.flatpickr-months .flatpickr-next-month { color: #d9b230 !important; fill: #d9b230 !important; }
.flatpickr-months .flatpickr-prev-month svg,
.flatpickr-months .flatpickr-next-month svg { fill: #d9b230 !important; }
.flatpickr-months .flatpickr-prev-month:hover,
.flatpickr-months .flatpickr-next-month:hover { background: rgba(217,178,48,0.1) !important; }
span.flatpickr-weekday {
    color: rgba(240,236,227,0.45) !important;
    background: transparent !important;
    font-weight: 600 !important;
}
.flatpickr-day {
    color: rgba(240,236,227,0.8) !important;
    border-color: transparent !important;
    border-radius: 8px !important;
}
.flatpickr-day:hover {
    background: rgba(217,178,48,0.12) !important;
    border-color: rgba(217,178,48,0.3) !important;
}
.flatpickr-day.selected {
    background: #d9b230 !important;
    color: #0a0f1a !important;
    border-color: #d9b230 !important;
    font-weight: 600 !important;
}
.flatpickr-day.flatpickr-disabled { color: rgba(240,236,227,0.15) !important; }
.flatpickr-day.today { border-color: rgba(217,178,48,0.4) !important; }
.flatpickr-innerContainer { background: #111a28 !important; }
.flatpickr-rContainer { background: #111a28 !important; }
.dayContainer { background: #111a28 !important; }

/* ── Bottom buttons ── */
.oc-single-bbc-package .oc-booking-card__note {
    text-align: center;
    font-size: 12px;
    color: rgba(240,236,227,0.35);
    margin: 12px 0 0;
    font-style: italic;
}
.oc-single-bbc-package .oc-btn-gold {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #d9b230;
    color: #0a0f1a;
    font-weight: 700;
    font-size: 15px;
    padding: 14px 24px;
    border-radius: 10px;
    text-decoration: none;
    transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
    margin-bottom: 10px;
    box-shadow: 0 2px 12px rgba(217,178,48,0.2);
}
.oc-single-bbc-package .oc-btn-gold:hover {
    background: #e8c43e;
    transform: translateY(-1px);
    color: #0a0f1a;
    box-shadow: 0 4px 16px rgba(217,178,48,0.3);
}
.oc-single-bbc-package .oc-btn-whatsapp {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: transparent;
    color: #25d366;
    border: 1.5px solid rgba(37,211,102,0.4);
    font-weight: 600;
    font-size: 15px;
    padding: 13px 24px;
    border-radius: 10px;
    text-decoration: none;
    transition: background 0.2s, color 0.2s, border-color 0.2s;
    margin-bottom: 10px;
}
.oc-single-bbc-package .oc-btn-whatsapp:hover {
    background: #25d366;
    color: #fff;
    border-color: #25d366;
}
.oc-single-bbc-package .oc-btn-full { width: 100%; }

/* ── Related packages ── */
.oc-single-bbc-package .oc-related-section { padding-bottom: 80px; }
.oc-single-bbc-package .oc-related-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
    margin-top: 8px;
}
.oc-single-bbc-package .oc-related-card {
    text-decoration: none;
    color: inherit;
    display: block;
    background: #111a28;
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 14px;
    overflow: hidden;
    transition: border-color 0.2s, transform 0.3s, box-shadow 0.3s;
}
.oc-single-bbc-package .oc-related-card:hover {
    border-color: rgba(217,178,48,0.4);
    transform: translateY(-3px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.3);
}
.oc-single-bbc-package .oc-related-card__img {
    height: 200px;
    background-size: cover;
    background-position: center;
    position: relative;
}
.oc-single-bbc-package .oc-related-card__badge {
    position: absolute;
    top: 12px;
    left: 12px;
    background: rgba(17,26,40,0.85);
    color: #d9b230;
    border: 1px solid rgba(217,178,48,0.4);
    font-size: 10px;
    font-weight: 600;
    padding: 3px 10px;
    border-radius: 999px;
    backdrop-filter: blur(6px);
}
.oc-single-bbc-package .oc-related-card__body { padding: 18px; }
.oc-single-bbc-package .oc-related-card__title {
    font-family: var(--font-heading, 'Playfair Display', Georgia, serif);
    font-size: 17px;
    font-weight: 400;
    color: #f8fafc;
    margin: 0 0 6px;
}
.oc-single-bbc-package .oc-related-card__excerpt {
    font-size: 13px;
    color: rgba(240,236,227,0.5);
    margin: 0 0 4px;
    display: block;
}
.oc-single-bbc-package .oc-related-card__price {
    font-size: 15px;
    color: #d9b230;
    font-weight: 600;
}

/* ── CTA strip ── */
.oc-single-bbc-package .oc-cta-strip {
    background: #111a28;
    border-top: 1px solid rgba(255,255,255,0.08);
    padding: 72px 0;
}
.oc-single-bbc-package .oc-cta-strip__inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 32px;
    flex-wrap: wrap;
}
.oc-single-bbc-package .oc-cta-strip__text h2 {
    font-family: var(--font-heading, 'Playfair Display', Georgia, serif);
    font-size: clamp(28px, 4vw, 42px);
    font-weight: 400;
    color: #f8fafc;
    margin: 0 0 8px;
}
.oc-single-bbc-package .oc-cta-strip__text p { font-size: 17px; color: rgba(240,236,227,0.5); margin: 0; }
.oc-single-bbc-package .oc-cta-strip__actions { display: flex; gap: 16px; flex-wrap: wrap; }

/* ── Responsive ── */
@media (max-width: 1024px) {
    .oc-bpkg-body { grid-template-columns: 1fr; gap: 40px; }
    .oc-bpkg-sidebar { position: static; order: -1; }
}
@media (max-width: 768px) {
    .oc-single-bbc-package .oc-related-grid { grid-template-columns: 1fr; }
    .oc-single-bbc-package .oc-inclusions-grid { grid-template-columns: 1fr; }
    .oc-single-bbc-package .oc-cta-strip__inner { flex-direction: column; text-align: center; }
    .oc-single-bbc-package .oc-cta-strip__actions { justify-content: center; }
    .oc-bpkg-durations-grid { grid-template-columns: 1fr; }
    .oc-bpkg-hero__title { margin-bottom: 16px; }
    .oc-bpkg-timeline { padding-left: 24px; }
}
</style>

<!-- Lightbox for gallery -->
<div id="bbc-lightbox" class="bbc-lightbox" role="dialog" aria-modal="true">
    <div class="bbc-lightbox-overlay"></div>
    <button class="bbc-lightbox-close" aria-label="Close">&times;</button>
    <button class="bbc-lightbox-prev" aria-label="Previous">&#8249;</button>
    <button class="bbc-lightbox-next" aria-label="Next">&#8250;</button>
    <div class="bbc-lightbox-img-wrap">
        <img src="" class="bbc-lightbox-img" alt="">
    </div>
</div>

<style>
.bbc-lightbox {
    position: fixed; top: 0; left: 0; width: 100%; height: 100%;
    z-index: 99999; display: none; align-items: center; justify-content: center;
}
.bbc-lightbox.is-open { display: flex; }
.bbc-lightbox-overlay {
    position: absolute; top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0,0,0,.92); cursor: pointer;
}
.bbc-lightbox-img-wrap {
    position: relative; z-index: 1;
    max-width: 90vw; max-height: 85vh;
    display: flex; align-items: center; justify-content: center;
}
.bbc-lightbox-img { max-width: 90vw; max-height: 85vh; object-fit: contain; border-radius: 4px; display: block; }
.bbc-lightbox-close {
    position: fixed; top: 20px; right: 24px; z-index: 2;
    background: rgba(255,255,255,.15); border: none; color: #fff;
    width: 44px; height: 44px; border-radius: 50%; font-size: 26px;
    cursor: pointer; display: flex; align-items: center; justify-content: center;
    transition: background .2s; line-height: 1;
}
.bbc-lightbox-prev, .bbc-lightbox-next {
    position: fixed; top: 50%; transform: translateY(-50%); z-index: 2;
    background: rgba(255,255,255,.15); border: none; color: #fff;
    width: 52px; height: 52px; border-radius: 50%; font-size: 32px;
    cursor: pointer; display: flex; align-items: center; justify-content: center;
    transition: background .2s; line-height: 1;
}
.bbc-lightbox-prev { left: 20px; }
.bbc-lightbox-next { right: 20px; }
.bbc-lightbox-close:hover,
.bbc-lightbox-prev:hover,
.bbc-lightbox-next:hover { background: rgba(255,255,255,.3); }
</style>

<script>
(function() {
    var lb = document.getElementById('bbc-lightbox');
    if (!lb) return;
    var items = Array.from(document.querySelectorAll('.oc-bpkg-gallery-item[data-lightbox]'));
    if (!items.length) return;
    var lbImg = lb.querySelector('.bbc-lightbox-img');
    var idx = 0;

    function open(i) {
        idx = (i + items.length) % items.length;
        lbImg.src = items[idx].dataset.lightbox;
        lb.classList.add('is-open');
        document.body.style.overflow = 'hidden';
    }
    function close() {
        lb.classList.remove('is-open');
        document.body.style.overflow = '';
        lbImg.src = '';
    }

    items.forEach(function(el, i) {
        el.addEventListener('click', function(e) { e.preventDefault(); open(i); });
    });

    lb.querySelector('.bbc-lightbox-overlay').addEventListener('click', close);
    lb.querySelector('.bbc-lightbox-close').addEventListener('click', close);
    lb.querySelector('.bbc-lightbox-prev').addEventListener('click', function() { open(idx - 1); });
    lb.querySelector('.bbc-lightbox-next').addEventListener('click', function() { open(idx + 1); });

    document.addEventListener('keydown', function(e) {
        if (!lb.classList.contains('is-open')) return;
        if (e.key === 'Escape') close();
        if (e.key === 'ArrowLeft') open(idx - 1);
        if (e.key === 'ArrowRight') open(idx + 1);
    });
})();
</script>

<?php get_footer(); ?>
