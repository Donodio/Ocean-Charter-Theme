<?php
/**
 * Ocean Charter — BBC Page Generator
 *
 * One-click admin tool that creates the pages the Boat Booking Core plugin
 * needs (Search Results, Thank You, Booking Confirmation, Booking Cancellation,
 * Payment Redirect, Add-on Upsell, Package Booking), pre-styled with Ocean
 * Charter theme classes so they match the rest of the site.
 *
 * Generated page IDs are written into the `bbc_page_settings` option that the
 * BBC plugin reads, so no further wiring is required.
 *
 * @package OceanCharter
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class OC_BBC_Page_Generator {

    const BBC_OPTION = 'bbc_page_settings';
    const NONCE      = 'oc_bbc_pages_nonce';

    public static function init() {
        add_action( 'admin_menu',        [ __CLASS__, 'add_menu' ], 12 );
        add_action( 'admin_post_oc_bbc_generate_page', [ __CLASS__, 'handle_generate' ] );
        add_action( 'admin_post_oc_bbc_generate_all',  [ __CLASS__, 'handle_generate_all' ] );
    }

    public static function add_menu() {
        add_submenu_page(
            'ocean-charter',
            __( 'BBC Pages', 'ocean-charter' ),
            __( 'BBC Pages', 'ocean-charter' ),
            'manage_options',
            'oc-bbc-pages',
            [ __CLASS__, 'render_page' ]
        );
    }

    /**
     * The page catalogue. Each entry:
     *   key   → slug used on forms and in the redirect query arg
     *   title → page title shown in admin + as H1
     *   slug  → wp post_name
     *   bbc_key → option key inside `bbc_page_settings`
     *   builder → method that returns the post_content HTML
     */
    public static function pages() {
        return [
            'search_results' => [
                'title'   => __( 'Yacht Search Results', 'ocean-charter' ),
                'slug'    => 'search-results',
                'bbc_key' => 'search_results_page',
                'builder' => 'build_search_results',
                'label'   => __( 'Search Results', 'ocean-charter' ),
            ],
            'thank_you' => [
                'title'   => __( 'Thank You', 'ocean-charter' ),
                'slug'    => 'booking-thank-you',
                'bbc_key' => 'thank_you_page',
                'builder' => 'build_thank_you',
                'label'   => __( 'Thank You (post-booking)', 'ocean-charter' ),
            ],
            'confirmation' => [
                'title'   => __( 'Booking Confirmed', 'ocean-charter' ),
                'slug'    => 'booking-confirmation',
                'bbc_key' => 'confirmation_page',
                'builder' => 'build_confirmation',
                'label'   => __( 'Booking Confirmation', 'ocean-charter' ),
            ],
            'cancellation' => [
                'title'   => __( 'Booking Cancelled', 'ocean-charter' ),
                'slug'    => 'booking-cancelled',
                'bbc_key' => 'cancellation_page',
                'builder' => 'build_cancellation',
                'label'   => __( 'Booking Cancellation', 'ocean-charter' ),
            ],
            'payment_redirect' => [
                'title'   => __( 'Processing Payment', 'ocean-charter' ),
                'slug'    => 'payment-processing',
                'bbc_key' => 'payment_redirect_page',
                'builder' => 'build_payment_redirect',
                'label'   => __( 'Payment Redirect', 'ocean-charter' ),
            ],
            'addon_upsell' => [
                'title'   => __( 'Enhance Your Charter', 'ocean-charter' ),
                'slug'    => 'booking-extras',
                'bbc_key' => 'addon_upsell_page',
                'builder' => 'build_addon_upsell',
                'label'   => __( 'Add-on Upsell', 'ocean-charter' ),
            ],
            'package_booking' => [
                'title'   => __( 'Book Your Package', 'ocean-charter' ),
                'slug'    => 'package-booking',
                'bbc_key' => 'package_booking_page',
                'builder' => 'build_package_booking',
                'label'   => __( 'Package Booking', 'ocean-charter' ),
            ],
        ];
    }

    /* ── Admin page renderer ─────────────────────────────────── */
    public static function render_page() {
        if ( ! current_user_can( 'manage_options' ) ) return;

        $pages      = self::pages();
        $bbc_opts   = get_option( self::BBC_OPTION, [] );
        $notice     = isset( $_GET['oc_msg'] ) ? sanitize_key( $_GET['oc_msg'] ) : '';
        $notice_key = isset( $_GET['oc_key'] ) ? sanitize_key( $_GET['oc_key'] ) : '';
        ?>
        <div class="wrap">
            <h1 style="display:flex;align-items:center;gap:.5rem;">⚓ Ocean Charter — BBC Pages</h1>

            <?php if ( $notice === 'created' ) : ?>
                <div class="notice notice-success is-dismissible"><p><strong><?php
                    printf( esc_html__( 'Page generated: %s', 'ocean-charter' ), esc_html( $notice_key ?: '' ) );
                ?></strong></p></div>
            <?php elseif ( $notice === 'updated' ) : ?>
                <div class="notice notice-success is-dismissible"><p><strong><?php
                    printf( esc_html__( 'Page regenerated with theme styling: %s', 'ocean-charter' ), esc_html( $notice_key ?: '' ) );
                ?></strong></p></div>
            <?php elseif ( $notice === 'bulk' ) : ?>
                <div class="notice notice-success is-dismissible"><p><strong><?php
                    esc_html_e( 'All missing pages generated and linked to the BBC plugin.', 'ocean-charter' );
                ?></strong></p></div>
            <?php elseif ( $notice === 'error' ) : ?>
                <div class="notice notice-error is-dismissible"><p><strong><?php
                    esc_html_e( 'Could not generate the page. Check error log.', 'ocean-charter' );
                ?></strong></p></div>
            <?php endif; ?>

            <p style="max-width:720px;color:#444;">
                <?php esc_html_e( 'Generate the pages the Boat Booking plugin needs (Search Results, Thank You, Booking Confirmation, etc.) in one click. The content is pre-styled to match the Ocean Charter theme, and the created page IDs are automatically linked in the BBC plugin settings.', 'ocean-charter' ); ?>
            </p>

            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="margin:1rem 0 1.5rem;">
                <?php wp_nonce_field( self::NONCE ); ?>
                <input type="hidden" name="action" value="oc_bbc_generate_all">
                <button type="submit" class="button button-primary button-hero">
                    <?php esc_html_e( '⚡ Generate All Missing Pages', 'ocean-charter' ); ?>
                </button>
                <span style="margin-left:.75rem;color:#666;">
                    <?php esc_html_e( 'Only creates pages that don\'t already exist. Existing pages are left alone.', 'ocean-charter' ); ?>
                </span>
            </form>

            <table class="widefat striped" style="max-width:1100px;">
                <thead>
                    <tr>
                        <th style="width:22%;"><?php esc_html_e( 'BBC Page', 'ocean-charter' ); ?></th>
                        <th style="width:28%;"><?php esc_html_e( 'Status', 'ocean-charter' ); ?></th>
                        <th style="width:22%;"><?php esc_html_e( 'Linked in plugin?', 'ocean-charter' ); ?></th>
                        <th><?php esc_html_e( 'Actions', 'ocean-charter' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ( $pages as $type => $info ) :
                    $linked_id = isset( $bbc_opts[ $info['bbc_key'] ] ) ? (int) $bbc_opts[ $info['bbc_key'] ] : 0;
                    $linked_ok = $linked_id && get_post_status( $linked_id );
                    $existing  = self::find_existing_page( $info['slug'] );
                    $existing_id = $existing ? (int) $existing : 0;
                    ?>
                    <tr>
                        <td><strong><?php echo esc_html( $info['label'] ); ?></strong><br>
                            <code style="font-size:11px;color:#666;">/<?php echo esc_html( $info['slug'] ); ?>/</code>
                        </td>
                        <td>
                            <?php if ( $existing_id ) :
                                $edit_url = get_edit_post_link( $existing_id );
                                $view_url = get_permalink( $existing_id );
                                ?>
                                <span style="color:#2e7d32;">✓ <?php esc_html_e( 'Created', 'ocean-charter' ); ?></span>
                                — <a href="<?php echo esc_url( $edit_url ); ?>"><?php esc_html_e( 'Edit', 'ocean-charter' ); ?></a>
                                · <a href="<?php echo esc_url( $view_url ); ?>" target="_blank"><?php esc_html_e( 'View', 'ocean-charter' ); ?></a>
                            <?php else : ?>
                                <span style="color:#c62828;">✗ <?php esc_html_e( 'Not created yet', 'ocean-charter' ); ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ( $linked_ok && $linked_id === $existing_id ) : ?>
                                <span style="color:#2e7d32;">✓ <?php esc_html_e( 'Linked', 'ocean-charter' ); ?></span>
                            <?php elseif ( $linked_ok ) : ?>
                                <span style="color:#e65100;">⚠ <?php
                                    printf( esc_html__( 'Linked to #%d', 'ocean-charter' ), $linked_id );
                                ?></span>
                            <?php else : ?>
                                <span style="color:#999;">— <?php esc_html_e( 'Unlinked', 'ocean-charter' ); ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline-block;margin:0;">
                                <?php wp_nonce_field( self::NONCE ); ?>
                                <input type="hidden" name="action" value="oc_bbc_generate_page">
                                <input type="hidden" name="page_type" value="<?php echo esc_attr( $type ); ?>">
                                <?php if ( $existing_id ) : ?>
                                    <button type="submit" name="mode" value="regenerate" class="button"
                                            onclick="return confirm('<?php echo esc_js( __( 'Overwrite existing page content with fresh theme-styled content?', 'ocean-charter' ) ); ?>');">
                                        <?php esc_html_e( 'Regenerate with theme', 'ocean-charter' ); ?>
                                    </button>
                                    <button type="submit" name="mode" value="link" class="button">
                                        <?php esc_html_e( 'Re-link to plugin', 'ocean-charter' ); ?>
                                    </button>
                                <?php else : ?>
                                    <button type="submit" name="mode" value="create" class="button button-primary">
                                        <?php esc_html_e( 'Generate', 'ocean-charter' ); ?>
                                    </button>
                                <?php endif; ?>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    /* ── Find an already-created page by slug (case-insensitive, any status) ── */
    private static function find_existing_page( $slug ) {
        $page = get_page_by_path( $slug, OBJECT, 'page' );
        return $page ? $page->ID : 0;
    }

    /* ── Action handlers ─────────────────────────────────────── */
    public static function handle_generate() {
        if ( ! current_user_can( 'manage_options' ) )      wp_die( 'Forbidden' );
        check_admin_referer( self::NONCE );

        $type = isset( $_POST['page_type'] ) ? sanitize_key( $_POST['page_type'] ) : '';
        $mode = isset( $_POST['mode'] )      ? sanitize_key( $_POST['mode'] )      : 'create';

        $pages = self::pages();
        if ( ! isset( $pages[ $type ] ) ) {
            self::redirect_with_notice( 'error', $type );
        }

        $info = $pages[ $type ];

        if ( $mode === 'regenerate' ) {
            $page_id = self::create_or_update_page( $info, true );
            $msg = $page_id ? 'updated' : 'error';
        } elseif ( $mode === 'link' ) {
            $page_id = self::find_existing_page( $info['slug'] );
            $msg = $page_id ? 'updated' : 'error';
        } else {
            $page_id = self::create_or_update_page( $info, false );
            $msg = $page_id ? 'created' : 'error';
        }

        if ( $page_id ) {
            self::link_page_to_bbc( $info['bbc_key'], $page_id );
        }

        self::redirect_with_notice( $msg, $info['label'] );
    }

    public static function handle_generate_all() {
        if ( ! current_user_can( 'manage_options' ) ) wp_die( 'Forbidden' );
        check_admin_referer( self::NONCE );

        foreach ( self::pages() as $info ) {
            $page_id = self::find_existing_page( $info['slug'] );
            if ( ! $page_id ) {
                $page_id = self::create_or_update_page( $info, false );
            }
            if ( $page_id ) {
                self::link_page_to_bbc( $info['bbc_key'], $page_id );
            }
        }

        self::redirect_with_notice( 'bulk', '' );
    }

    private static function redirect_with_notice( $msg, $key ) {
        wp_safe_redirect( add_query_arg( [
            'page'   => 'oc-bbc-pages',
            'oc_msg' => $msg,
            'oc_key' => rawurlencode( $key ),
        ], admin_url( 'admin.php' ) ) );
        exit;
    }

    /**
     * Insert or update a page with the theme-styled content for this type.
     * Returns the page ID or 0 on failure.
     */
    private static function create_or_update_page( $info, $overwrite ) {
        $content = call_user_func( [ __CLASS__, $info['builder'] ] );

        $existing_id = self::find_existing_page( $info['slug'] );

        if ( $existing_id && ! $overwrite ) {
            return $existing_id;
        }

        $data = [
            'post_title'   => $info['title'],
            'post_name'    => $info['slug'],
            'post_content' => $content,
            'post_status'  => 'publish',
            'post_type'    => 'page',
        ];

        if ( $existing_id ) {
            $data['ID'] = $existing_id;
            $result = wp_update_post( $data, true );
        } else {
            $result = wp_insert_post( $data, true );
        }

        return is_wp_error( $result ) ? 0 : (int) $result;
    }

    private static function link_page_to_bbc( $bbc_key, $page_id ) {
        $opts = get_option( self::BBC_OPTION, [] );
        if ( ! is_array( $opts ) ) $opts = [];
        $opts[ $bbc_key ] = (int) $page_id;
        update_option( self::BBC_OPTION, $opts );
    }

    /* ═══════════════════════════════════════════════════════════════════════
       Content builders — return raw HTML wrapped in Ocean Charter theme
       classes. Block delimiter comments use core/html so Gutenberg doesn't
       mangle the markup on re-save. Every page starts with a section hero
       that matches the site's visual language (.oc-section + .oc-container).
       ═══════════════════════════════════════════════════════════════════════ */

    private static function html_block( $html ) {
        return "<!-- wp:html -->\n" . $html . "\n<!-- /wp:html -->";
    }

    private static function hero_section( $eyebrow, $title, $subtitle, $icon = '⚓' ) {
        $home = esc_url( home_url( '/' ) );
        $fleet = esc_url( home_url( '/fleet/' ) );
        ob_start(); ?>
<section class="oc-section oc-section--surface" style="padding:clamp(6rem,10vw,9rem) 0;">
  <div class="oc-container" style="max-width:760px;text-align:center;">
    <div style="font-size:3rem;line-height:1;margin-bottom:1.25rem;opacity:0.95;"><?php echo $icon; ?></div>
    <span class="oc-caption" style="color:var(--primary);font-size:0.75rem;letter-spacing:0.22em;text-transform:uppercase;font-weight:700;"><?php echo esc_html( $eyebrow ); ?></span>
    <h1 style="font-family:var(--font-heading);font-size:clamp(2.5rem,5vw,4.25rem);font-weight:400;line-height:1.1;margin:1rem 0 1.5rem;color:var(--text);"><?php echo esc_html( $title ); ?></h1>
    <p style="color:var(--text-muted);font-size:1.0625rem;line-height:1.75;margin:0 auto 2.25rem;max-width:560px;"><?php echo wp_kses_post( $subtitle ); ?></p>
<?php
        return ob_get_clean();
    }

    private static function hero_close() {
        return "  </div>\n</section>";
    }

    private static function build_search_results() {
        $hero = self::hero_section(
            __( 'The Fleet', 'ocean-charter' ),
            __( 'Your Yacht Awaits', 'ocean-charter' ),
            __( 'Refine your search below, or browse our curated fleet of private charter yachts — each vessel vetted for comfort, performance, and the discretion our guests expect.', 'ocean-charter' ),
            '🔍'
        );
        $hero .= '<div style="margin-top:2rem;max-width:900px;margin-left:auto;margin-right:auto;">[boat_search]</div>';
        $hero .= self::hero_close();

        $hero .= '<section class="oc-section" style="padding-top:2rem;"><div class="oc-container">';
        $hero .= '<h2 style="font-family:var(--font-heading);font-size:clamp(1.75rem,3vw,2.5rem);text-align:center;margin-bottom:2.5rem;color:var(--text);">' . esc_html__( 'Matching Vessels', 'ocean-charter' ) . '</h2>';
        $hero .= '<p style="text-align:center;color:var(--text-muted);margin-bottom:2rem;">' . esc_html__( 'Results appear below. Not seeing what you need? Our concierge can source a yacht off-market — just reach out.', 'ocean-charter' ) . '</p>';
        $hero .= '<div style="text-align:center;"><a href="' . esc_url( home_url( '/fleet/' ) ) . '" class="btn-primary">' . esc_html__( 'View Full Fleet', 'ocean-charter' ) . '</a></div>';
        $hero .= '</div></section>';

        return self::html_block( $hero );
    }

    private static function build_thank_you() {
        $home  = esc_url( home_url( '/' ) );
        $fleet = esc_url( home_url( '/fleet/' ) );

        $html  = self::hero_section(
            __( 'Booking Received', 'ocean-charter' ),
            __( 'Thank You', 'ocean-charter' ),
            __( 'Your booking request has been received. A confirmation has been sent to your inbox, and our concierge team will be in touch shortly to finalise every detail.', 'ocean-charter' ),
            '⚓'
        );
        $html .= '<div class="glass-panel" style="padding:1.5rem 2rem;max-width:420px;margin:0 auto 2rem;text-align:center;">';
        $html .= '<div style="font-size:0.75rem;letter-spacing:0.18em;text-transform:uppercase;color:var(--primary);font-weight:700;margin-bottom:0.5rem;">' . esc_html__( 'Booking Reference', 'ocean-charter' ) . '</div>';
        $html .= '<div style="font-family:var(--font-heading);font-size:1.5rem;color:var(--text);">[booking_reference]</div>';
        $html .= '</div>';
        $html .= '<div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">';
        $html .= '<a href="' . $home  . '" class="btn-primary">' . esc_html__( 'Return Home', 'ocean-charter' ) . '</a>';
        $html .= '<a href="' . $fleet . '" class="btn-secondary">' . esc_html__( 'Browse More Yachts', 'ocean-charter' ) . '</a>';
        $html .= '</div>';
        $html .= self::hero_close();

        return self::html_block( $html );
    }

    private static function build_confirmation() {
        $home    = esc_url( home_url( '/' ) );
        $contact = esc_url( home_url( '/contact/' ) );

        $html  = self::hero_section(
            __( 'Confirmed', 'ocean-charter' ),
            __( 'Your Charter Is Locked In', 'ocean-charter' ),
            __( 'Your booking is confirmed. Everything below has been agreed with our concierge team — if any detail needs changing, reach out and we\'ll handle it.', 'ocean-charter' ),
            '✓'
        );
        $html .= '<div class="glass-panel" style="padding:1.5rem 2rem;max-width:420px;margin:0 auto 2rem;text-align:center;">';
        $html .= '<div style="font-size:0.75rem;letter-spacing:0.18em;text-transform:uppercase;color:var(--primary);font-weight:700;margin-bottom:0.5rem;">' . esc_html__( 'Booking Reference', 'ocean-charter' ) . '</div>';
        $html .= '<div style="font-family:var(--font-heading);font-size:1.5rem;color:var(--text);">[booking_reference]</div>';
        $html .= '</div>';
        $html .= '<p style="color:var(--text-muted);margin-bottom:2rem;">' . esc_html__( 'A detailed itinerary, crew contact, and embarkation instructions will follow by email.', 'ocean-charter' ) . '</p>';
        $html .= '<div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">';
        $html .= '<a href="' . $contact . '" class="btn-primary">' . esc_html__( 'Contact Concierge', 'ocean-charter' ) . '</a>';
        $html .= '<a href="' . $home    . '" class="btn-secondary">' . esc_html__( 'Return Home', 'ocean-charter' ) . '</a>';
        $html .= '</div>';
        $html .= self::hero_close();

        return self::html_block( $html );
    }

    private static function build_cancellation() {
        $home    = esc_url( home_url( '/' ) );
        $fleet   = esc_url( home_url( '/fleet/' ) );
        $contact = esc_url( home_url( '/contact/' ) );

        $html  = self::hero_section(
            __( 'Cancelled', 'ocean-charter' ),
            __( 'Your Booking Has Been Cancelled', 'ocean-charter' ),
            __( 'We\'re sorry to see this booking won\'t go ahead. If there\'s anything we can do differently — dates, vessel, itinerary — let us know and we\'ll find a better fit.', 'ocean-charter' ),
            '⊘'
        );
        $html .= '<div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">';
        $html .= '<a href="' . $fleet   . '" class="btn-primary">' . esc_html__( 'Browse The Fleet', 'ocean-charter' ) . '</a>';
        $html .= '<a href="' . $contact . '" class="btn-secondary">' . esc_html__( 'Talk to Concierge', 'ocean-charter' ) . '</a>';
        $html .= '</div>';
        $html .= self::hero_close();

        return self::html_block( $html );
    }

    private static function build_payment_redirect() {
        $html  = self::hero_section(
            __( 'Processing', 'ocean-charter' ),
            __( 'Securing Your Charter', 'ocean-charter' ),
            __( 'Your payment is being processed securely. Please don\'t close this tab — we\'ll redirect you as soon as the transaction completes.', 'ocean-charter' ),
            '⏳'
        );
        $html .= '<div style="width:56px;height:56px;border:3px solid rgba(217,178,48,0.25);border-top-color:var(--primary);border-radius:50%;margin:0 auto;animation:oc-spin 0.9s linear infinite;"></div>';
        $html .= '<style>@keyframes oc-spin{to{transform:rotate(360deg)}}</style>';
        $html .= self::hero_close();

        return self::html_block( $html );
    }

    private static function build_addon_upsell() {
        $html  = self::hero_section(
            __( 'Enhance Your Day', 'ocean-charter' ),
            __( 'Final Touches', 'ocean-charter' ),
            __( 'Add chef service, on-water toys, a private photographer, and more. Each add-on is coordinated with the crew ahead of your arrival.', 'ocean-charter' ),
            '✨'
        );
        $html .= self::hero_close();

        $html .= '<section class="oc-section" style="padding-top:2rem;"><div class="oc-container" style="max-width:960px;">';
        $html .= '[bbc_addon_upsell]';
        $html .= '</div></section>';

        return self::html_block( $html );
    }

    private static function build_package_booking() {
        $html  = self::hero_section(
            __( 'Book Your Package', 'ocean-charter' ),
            __( 'Reserve Your Journey', 'ocean-charter' ),
            __( 'Confirm your dates and guest details below. Our concierge will reach out within 24 hours to finalise the itinerary and arrange on-board preferences.', 'ocean-charter' ),
            '🗓'
        );
        $html .= self::hero_close();

        $html .= '<section class="oc-section" style="padding-top:2rem;"><div class="oc-container" style="max-width:820px;">';
        $html .= '[bbc_booking_form mode="package"]';
        $html .= '</div></section>';

        return self::html_block( $html );
    }
}

OC_BBC_Page_Generator::init();
