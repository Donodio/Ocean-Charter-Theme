<?php
/**
 * Ocean Charter — footer.php
 *
 * @package OceanCharter
 */
?>

<!-- ── WhatsApp Floating Action Button ────────────────────────────────────── -->
<a href="<?php echo oc_whatsapp_url( 'Hello, I\'d like to enquire about a yacht charter.' ); ?>"
   class="oc-whatsapp-fab"
   target="_blank"
   rel="noopener noreferrer"
   aria-label="<?php esc_attr_e( 'Chat with us on WhatsApp', 'ocean-charter' ); ?>">
  <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
  </svg>
</a>

<!-- ── Site Footer ─────────────────────────────────────────────────────────── -->
<footer class="oc-footer" role="contentinfo">
  <div class="oc-container">
    <div class="oc-footer__grid">

      <!-- Brand column -->
      <div class="oc-footer__brand">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="oc-logo" aria-label="<?php bloginfo( 'name' ); ?> — Home">
          <svg class="oc-logo__icon" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M5 22 L16 6 L16 22 Z" stroke="#d9b230" stroke-width="1.5" fill="rgba(217,178,48,0.15)" stroke-linejoin="round"/>
            <path d="M16 22 L27 18 L16 10 Z" stroke="#d9b230" stroke-width="1.5" fill="rgba(217,178,48,0.08)" stroke-linejoin="round"/>
            <line x1="16" y1="4" x2="16" y2="24" stroke="#d9b230" stroke-width="1.5" stroke-linecap="round"/>
            <path d="M3 24 Q10 27 16 26 Q22 25 29 24" stroke="#d9b230" stroke-width="2" stroke-linecap="round" fill="none"/>
            <path d="M1 27 Q8 30 16 29 Q24 28 31 27" stroke="rgba(217,178,48,0.4)" stroke-width="1.5" stroke-linecap="round" fill="none"/>
          </svg>
          <span class="oc-logo__text">Ocean <em>Charter</em></span>
        </a>
        <p><?php esc_html_e( 'Luxury yacht charters across the world\'s most coveted waters.', 'ocean-charter' ); ?></p>
        <div class="oc-footer__socials">
          <!-- Instagram -->
          <a href="#" aria-label="<?php esc_attr_e( 'Instagram', 'ocean-charter' ); ?>" target="_blank" rel="noopener noreferrer">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
              <rect x="2" y="2" width="20" height="20" rx="5"/>
              <circle cx="12" cy="12" r="4"/>
              <circle cx="17.5" cy="6.5" r="0.5" fill="currentColor" stroke="none"/>
            </svg>
          </a>
          <!-- Facebook -->
          <a href="#" aria-label="<?php esc_attr_e( 'Facebook', 'ocean-charter' ); ?>" target="_blank" rel="noopener noreferrer">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
              <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
            </svg>
          </a>
          <!-- YouTube -->
          <a href="#" aria-label="<?php esc_attr_e( 'YouTube', 'ocean-charter' ); ?>" target="_blank" rel="noopener noreferrer">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
              <path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 0 0-1.95 1.96A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58A2.78 2.78 0 0 0 3.41 19.54C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.96A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/>
              <polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02" stroke="none" fill="currentColor"/>
            </svg>
          </a>
        </div><!-- .oc-footer__socials -->
      </div><!-- .oc-footer__brand -->

      <!-- Quick Links column -->
      <div class="oc-footer__col">
        <h4><?php esc_html_e( 'Quick Links', 'ocean-charter' ); ?></h4>
        <?php
        wp_nav_menu( [
          'theme_location' => 'footer',
          'container'      => false,
          'depth'          => 1,
          'fallback_cb'    => function() {
            echo '<ul>';
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
      </div><!-- .oc-footer__col -->

      <!-- Services column -->
      <div class="oc-footer__col">
        <h4><?php esc_html_e( 'Services', 'ocean-charter' ); ?></h4>
        <ul>
          <li><a href="<?php echo esc_url( home_url( '/services/' ) ); ?>"><?php esc_html_e( 'Crewed Charters',    'ocean-charter' ); ?></a></li>
          <li><a href="<?php echo esc_url( home_url( '/services/' ) ); ?>"><?php esc_html_e( 'Private Events',     'ocean-charter' ); ?></a></li>
          <li><a href="<?php echo esc_url( home_url( '/services/' ) ); ?>"><?php esc_html_e( 'Water Sports',       'ocean-charter' ); ?></a></li>
          <li><a href="<?php echo esc_url( home_url( '/services/' ) ); ?>"><?php esc_html_e( 'Corporate Charters', 'ocean-charter' ); ?></a></li>
          <li><a href="<?php echo esc_url( home_url( '/services/' ) ); ?>"><?php esc_html_e( 'Concierge',          'ocean-charter' ); ?></a></li>
        </ul>
      </div><!-- .oc-footer__col -->

      <!-- Contact column -->
      <div class="oc-footer__col oc-footer__contact">
        <h4><?php esc_html_e( 'Contact', 'ocean-charter' ); ?></h4>

        <p>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.36 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.11 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.09a16 16 0 0 0 6 6l.56-.56a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
          </svg>
          <?php $oc_phone = OC_Theme_Settings::get( 'phone', '+1 (555) 123-4567' ); ?>
          <a href="tel:<?php echo esc_attr( preg_replace( '/[^+0-9]/', '', $oc_phone ) ); ?>">
            <?php echo esc_html( $oc_phone ); ?>
          </a>
        </p>

        <p>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
            <polyline points="22,6 12,13 2,6"/>
          </svg>
          <?php $oc_email = OC_Theme_Settings::get( 'email', 'info@oceancharter.com' ); ?>
          <a href="mailto:<?php echo esc_attr( $oc_email ); ?>">
            <?php echo esc_html( $oc_email ); ?>
          </a>
        </p>

        <p>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
            <circle cx="12" cy="10" r="3"/>
          </svg>
          <?php echo esc_html( OC_Theme_Settings::get( 'address', '123 Marina Drive, Miami, FL 33101' ) ); ?>
        </p>

        <a href="<?php echo oc_whatsapp_url( 'Hello, I\'d like to book a yacht charter.' ); ?>"
           class="btn-primary oc-footer__whatsapp-btn"
           target="_blank"
           rel="noopener noreferrer">
          <?php esc_html_e( 'WhatsApp Us', 'ocean-charter' ); ?>
        </a>
      </div><!-- .oc-footer__col -->

    </div><!-- .oc-footer__grid -->

    <!-- Bottom bar -->
    <div class="oc-footer__bottom">
      <p>
        &copy; <?php echo esc_html( date( 'Y' ) ); ?>
        <?php bloginfo( 'name' ); ?>.
        <?php esc_html_e( 'All rights reserved.', 'ocean-charter' ); ?>
      </p>
    </div>

  </div><!-- .oc-container -->
</footer><!-- .oc-footer -->

<?php wp_footer(); ?>
</body>
</html>
