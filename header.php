<?php
/**
 * Ocean Charter — header.php
 *
 * @package OceanCharter
 */

// Pull Customizer values for the header
$oc_brand_name = get_theme_mod( 'oc_brand_name', 'Ocean Charter' );
$oc_cta_label  = get_theme_mod( 'oc_cta_button_label', 'Book Now' );
$oc_cta_url    = get_theme_mod( 'oc_cta_button_url', '/contact/' );
$oc_cta_url    = $oc_cta_url ? esc_url( home_url( $oc_cta_url ) ) : esc_url( home_url( '/contact/' ) );

// Desktop header layout variant (mobile always uses the hamburger drawer)
$oc_header_variant = function_exists( 'oc_setting' ) ? oc_setting( 'header_variant', 'pill' ) : 'pill';
$oc_allowed_variants = [ 'pill', 'bar', 'centered', 'minimal', 'transparent', 'split' ];
if ( ! in_array( $oc_header_variant, $oc_allowed_variants, true ) ) {
    $oc_header_variant = 'pill';
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="profile" href="https://gmpg.org/xfn/11">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="oc-header" id="oc-header" role="banner" data-header-variant="<?php echo esc_attr( $oc_header_variant ); ?>">
  <div class="oc-header__inner">

    <!-- Glass pill wraps logo + nav + CTA (Stitch pattern) -->
    <div class="oc-header__pill">

      <!-- Logo: uses WP Custom Logo if set, otherwise SVG + brand name -->
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="oc-logo" aria-label="<?php echo esc_attr( $oc_brand_name ); ?> — Home">
        <?php if ( has_custom_logo() ) : ?>
          <?php
          $logo_id  = get_theme_mod( 'custom_logo' );
          $logo_url = wp_get_attachment_image_url( $logo_id, 'full' );
          ?>
          <img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( $oc_brand_name ); ?>" class="oc-logo__img">
        <?php else : ?>
          <svg class="oc-logo__icon" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M5 22 L16 6 L16 22 Z" stroke="#d9b230" stroke-width="1.5" fill="rgba(217,178,48,0.15)" stroke-linejoin="round"/>
            <path d="M16 22 L27 18 L16 10 Z" stroke="#d9b230" stroke-width="1.5" fill="rgba(217,178,48,0.08)" stroke-linejoin="round"/>
            <line x1="16" y1="4" x2="16" y2="24" stroke="#d9b230" stroke-width="1.5" stroke-linecap="round"/>
            <path d="M3 24 Q10 27 16 26 Q22 25 29 24" stroke="#d9b230" stroke-width="2" stroke-linecap="round" fill="none"/>
            <path d="M1 27 Q8 30 16 29 Q24 28 31 27" stroke="rgba(217,178,48,0.4)" stroke-width="1.5" stroke-linecap="round" fill="none"/>
          </svg>
        <?php endif; ?>
        <span class="oc-logo__text"><?php
          // Split brand name so the second word gets <em> styling
          $parts = explode( ' ', $oc_brand_name, 2 );
          echo esc_html( $parts[0] );
          if ( isset( $parts[1] ) ) {
              echo ' <em>' . esc_html( $parts[1] ) . '</em>';
          }
        ?></span>
      </a>

      <!-- Desktop primary navigation -->
      <nav class="oc-nav" role="navigation" aria-label="<?php esc_attr_e( 'Primary navigation', 'ocean-charter' ); ?>">
        <?php
        wp_nav_menu( [
          'theme_location' => 'menu-1',
          'container'      => false,
          'menu_class'     => 'oc-nav__list',
          'fallback_cb'    => false,
        ] );
        ?>
      </nav>

      <!-- CTA Button — text and URL from Customizer -->
      <a href="<?php echo $oc_cta_url; ?>" class="btn-primary oc-header__cta">
        <?php echo esc_html( $oc_cta_label ); ?>
      </a>

    </div><!-- .oc-header__pill -->

    <!-- Hamburger toggle (outside pill, mobile only) -->
    <button
      class="oc-hamburger"
      id="oc-hamburger"
      aria-label="<?php esc_attr_e( 'Open menu', 'ocean-charter' ); ?>"
      aria-expanded="false"
      aria-controls="oc-mobile-nav"
    >
      <span></span>
      <span></span>
      <span></span>
    </button>

  </div><!-- .oc-header__inner -->

  <!-- Mobile navigation drawer -->
  <div class="oc-mobile-nav" id="oc-mobile-nav" role="navigation" aria-label="<?php esc_attr_e( 'Mobile navigation', 'ocean-charter' ); ?>">
    <?php
    wp_nav_menu( [
      'theme_location' => 'menu-1',
      'container'      => false,
      'menu_class'     => 'oc-mobile-nav__list',
      'fallback_cb'    => function() {
        echo '<ul class="oc-mobile-nav__list">';
        $links = [
          'Fleet'        => '/fleet/',
          'Destinations' => '/destinations/',
          'Packages'     => '/packages/',
          'Services'     => '/services/',
          'Itinerary'    => '/itinerary/',
          'Contact'      => '/contact/',
        ];
        foreach ( $links as $label => $path ) {
          printf(
            '<li><a href="%s">%s</a></li>',
            esc_url( home_url( $path ) ),
            esc_html( $label )
          );
        }
        echo '</ul>';
      },
    ] );
    ?>
    <a href="<?php echo $oc_cta_url; ?>" class="btn-primary">
      <?php echo esc_html( $oc_cta_label ); ?>
    </a>
  </div><!-- .oc-mobile-nav -->

</header><!-- .oc-header -->

<?php
// On the front page the hero sits behind the header (overlay mode).
// On all other pages, insert a spacer so content doesn't hide behind the fixed header.
if ( ! is_front_page() ) : ?>
  <div class="oc-header-spacer" aria-hidden="true"></div>
<?php endif; ?>
