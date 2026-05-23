<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class OC_Theme_Settings {
    const OPTION = 'oc_theme_settings';

    public static function init() {
        // Priority 11: the parent 'ocean-charter' top-level menu is registered
        // in inc/cpt/register.php at default priority 10. Our submenu must
        // attach AFTER that parent exists, or WP orphans the slug → 404.
        add_action( 'admin_menu',            [ __CLASS__, 'add_menu' ], 11 );
        add_action( 'admin_init',            [ __CLASS__, 'register_settings' ] );
        add_action( 'wp_head',               [ __CLASS__, 'inject_css_vars' ], 5 );
        add_action( 'admin_enqueue_scripts', [ __CLASS__, 'enqueue_admin_assets' ] );
    }

    /* ── Admin Menu ───────────────────────────────────────────── */
    public static function add_menu() {
        // The top-level "Ocean Charter" menu is registered by inc/cpt/register.php
        // so all OC custom post types can group under it. We only add a submenu
        // here for theme settings — registering another top-level item with the
        // same slug would cause a duplicate "Ocean Charter" entry in the admin.
        add_submenu_page(
            'ocean-charter',
            __( 'Theme Settings', 'ocean-charter' ),
            __( 'Theme Settings', 'ocean-charter' ),
            'manage_options',
            'oc-theme-settings',
            [ __CLASS__, 'render_page' ]
        );
    }

    public static function enqueue_admin_assets( $hook ) {
        // Load only on the Theme Settings submenu page
        if ( ! in_array( $hook, [ 'ocean-charter_page_oc-theme-settings', 'appearance_page_oc-theme-settings' ], true ) ) return;
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );
        wp_enqueue_media();
        wp_add_inline_script( 'wp-color-picker', 'jQuery(function($){ $(".oc-color-picker").wpColorPicker(); });' );
    }

    /* ── Settings Registration ────────────────────────────────── */
    public static function register_settings() {
        register_setting( 'oc_theme_group', self::OPTION, [ 'sanitize_callback' => [ __CLASS__, 'sanitize' ] ] );

        // ── GLOBAL DESIGN ────────────────────────────────────────
        add_settings_section( 'oc_design', '🎨 Global Design', '__return_false', 'oc-theme-settings' );
        self::color_field( 'primary_color',  'Primary Gold Color',  '#d9b230', 'oc_design' );
        self::color_field( 'bg_dark',        'Dark Background',     '#0a0f1a', 'oc_design' );
        self::color_field( 'surface_color',  'Card Surface Color',  '#111a28', 'oc_design' );
        self::color_field( 'text_color',     'Body Text Color',     '#f0ece3', 'oc_design' );
        self::color_field( 'muted_color',    'Muted Text Color',    '#94a3b8', 'oc_design' );
        self::text_field(  'heading_font',   'Heading Font (Google Fonts name)', 'Playfair Display', 'oc_design' );
        self::text_field(  'body_font',      'Body Font (Google Fonts name)',    'Inter',            'oc_design' );

        // ── TYPOGRAPHY ────────────────────────────────────────────
        add_settings_section( 'oc_typography', '✍️ Typography', '__return_false', 'oc-theme-settings' );
        self::select_field( 'body_font_weight',    'Body Font Weight',      '400', [ '300' => 'Light (300)', '400' => 'Regular (400)', '500' => 'Medium (500)', '600' => 'SemiBold (600)', '700' => 'Bold (700)' ], 'oc_typography' );
        self::text_field(   'body_font_size',      'Body Font Size (px)',   '16',  'oc_typography' );
        self::color_field(  'body_font_color',     'Body Font Color',       '#f0ece3', 'oc_typography' );
        self::select_field( 'heading_font_weight', 'Heading Font Weight',   '400', [ '300' => 'Light (300)', '400' => 'Regular (400)', '600' => 'SemiBold (600)', '700' => 'Bold (700)' ], 'oc_typography' );
        self::text_field(   'body_letter_spacing',    'Body Letter Spacing (em, e.g. 0)',    '0',    'oc_typography' );
        self::text_field(   'heading_letter_spacing', 'Heading Letter Spacing (em, e.g. 0)', '0',    'oc_typography' );
        self::text_field(   'body_line_height',       'Body Line Height (e.g. 1.7)',          '1.7',  'oc_typography' );

        // ── HEADER / NAVIGATION ───────────────────────────────────
        add_settings_section( 'oc_header', '🧭 Header & Navigation', '__return_false', 'oc-theme-settings' );
        self::select_field(   'header_variant',  'Header Style (desktop only — mobile is unchanged)', 'pill', [
            'pill'        => 'Glass Pill (default)',
            'bar'         => 'Solid Glass Bar',
            'centered'    => 'Centered Logo (stacked)',
            'minimal'     => 'Minimal Underline',
            'transparent' => 'Transparent Floating',
            'split'       => 'Split — Logo Left / Nav Right',
        ], 'oc_header' );
        self::text_field(     'site_name',       'Site Name',                    'Ocean Charter', 'oc_header' );
        self::text_field(     'logo_max_height', 'Logo Max Height (px)',          '56',            'oc_header' );
        self::color_field(    'header_bg_color', 'Header Background Color',      'rgba(10,15,26,0.85)', 'oc_header' );
        self::text_field(     'header_height',   'Header Height (px)',            '72',            'oc_header' );
        self::text_field(     'nav_font_size',   'Nav Link Font Size (px)',       '14',            'oc_header' );
        self::color_field(    'nav_link_color',  'Nav Link Color',                '#f0ece3',       'oc_header' );
        self::color_field(    'mobile_header_bg','Mobile Header Background',      '#0a0f1a',       'oc_header' );
        self::text_field(     'nav_cta_label',   'Nav CTA Button Label',          'Charter Now',   'oc_header' );
        self::url_field(      'nav_cta_url',     'Nav CTA Button URL',            '/contact/',     'oc_header' );
        self::checkbox_field( 'header_show_whatsapp', 'Show WhatsApp in Header',  '1',            'oc_header' );

        // ── FOOTER ────────────────────────────────────────────────
        add_settings_section( 'oc_footer', '📄 Footer', '__return_false', 'oc-theme-settings' );
        self::color_field(    'footer_bg_color',    'Footer Background Color',    '#060d18',       'oc_footer' );
        self::color_field(    'footer_text_color',  'Footer Text Color',          '#94a3b8',       'oc_footer' );
        self::color_field(    'footer_link_color',  'Footer Link Color',          '#f0ece3',       'oc_footer' );
        self::textarea_field( 'footer_tagline',     'Footer Tagline',             'Extraordinary yacht charters crafted for the discerning traveller.', 'oc_footer' );
        self::text_field(     'copyright_text',     'Copyright Text',             '© 2025 Ocean Charter. All rights reserved.', 'oc_footer' );
        self::text_field(     'footer_col1_heading','Column 1 Heading',           'Ocean Charter', 'oc_footer' );
        self::text_field(     'footer_col2_heading','Column 2 Heading',           'Quick Links',   'oc_footer' );
        self::text_field(     'footer_col3_heading','Column 3 Heading',           'Services',      'oc_footer' );
        self::text_field(     'footer_col4_heading','Column 4 Heading',           'Contact',       'oc_footer' );

        // ── MOBILE / OFF-CANVAS ───────────────────────────────────
        add_settings_section( 'oc_mobile', '📱 Mobile / Off-Canvas Menu', '__return_false', 'oc-theme-settings' );
        self::color_field(    'offcanvas_bg_color',    'Off-Canvas Background Color',  '#070d1a',    'oc_mobile' );
        self::color_field(    'offcanvas_link_color',  'Off-Canvas Link Color',         '#f0ece3',   'oc_mobile' );
        self::text_field(     'offcanvas_font_size',   'Off-Canvas Link Font Size (px)', '18',       'oc_mobile' );
        self::text_field(     'offcanvas_width',       'Off-Canvas Menu Width (px)',     '300',       'oc_mobile' );
        self::checkbox_field( 'offcanvas_show_social', 'Show Social Icons in Mobile Menu', '1',      'oc_mobile' );

        // ── CONTACT & BRAND ───────────────────────────────────────
        add_settings_section( 'oc_contact', '📞 Contact & Brand', '__return_false', 'oc-theme-settings' );
        self::text_field( 'phone',      'Phone Number',   '+1 (800) 555-0199',          'oc_contact' );
        self::text_field( 'email',      'Email Address',  'info@oceancharter.com',       'oc_contact' );
        self::text_field( 'address',    'Office Address', 'Monaco Yacht Club, Monte Carlo', 'oc_contact' );
        self::text_field( 'whatsapp',   'WhatsApp Number (with country code)', '', 'oc_contact' );
        self::url_field(  'instagram',  'Instagram URL',  '', 'oc_contact' );
        self::url_field(  'facebook',   'Facebook URL',   '', 'oc_contact' );
        self::url_field(  'youtube',    'YouTube URL',    '', 'oc_contact' );
        self::url_field(  'linkedin',   'LinkedIn URL',   '', 'oc_contact' );

        // ── INTEGRATIONS ──────────────────────────────────────────
        add_settings_section( 'oc_integrations', '🔌 Integrations', '__return_false', 'oc-theme-settings' );
        self::text_field( 'google_maps_key',    'Google Maps / Places API Key',  '', 'oc_integrations' );
        self::text_field( 'google_analytics_id','Google Analytics ID (GA4)',     '', 'oc_integrations' );
        self::text_field( 'mailchimp_list_id',  'Mailchimp List/Audience ID',    '', 'oc_integrations' );
        self::checkbox_field( 'crm_webhook_enabled', 'Enable CRM Webhook',       '', 'oc_integrations' );
        self::url_field(  'crm_webhook_url',    'CRM Webhook URL',               '', 'oc_integrations' );
        self::text_field( 'crm_webhook_secret', 'Webhook Secret Key (HMAC-SHA256)', '', 'oc_integrations' );

        // ── SEARCH FORM ───────────────────────────────────────────
        add_settings_section( 'oc_search', '🔍 Search Form', '__return_false', 'oc-theme-settings' );
        self::text_field(  'search_field_order',   'Field Order (comma-separated slugs: location, date_from, date_to, guests)', 'location,date_from,date_to,guests', 'oc_search' );
        self::text_field(  'search_fields_hidden',  'Hidden Fields (comma-separated slugs to remove from form)', '', 'oc_search' );
        self::text_field(  'search_button_text',    'Search Button Label', 'Search Vessels', 'oc_search' );
        self::url_field(   'search_results_url',    'Search Results Page URL', '/fleet/', 'oc_search' );

        // ── PERFORMANCE ───────────────────────────────────────────
        add_settings_section( 'oc_perf', '⚡ Performance', '__return_false', 'oc-theme-settings' );
        self::checkbox_field( 'disable_emojis',    'Disable WordPress Emoji Scripts', '1', 'oc_perf' );
        self::checkbox_field( 'disable_gutenberg', 'Disable Gutenberg on Front-end',  '1', 'oc_perf' );
    }

    /* ── Field helpers ────────────────────────────────────────── */

    private static function text_field( $key, $label, $default, $section ) {
        add_settings_field( $key, $label, function() use ( $key, $default ) {
            $val = esc_attr( self::get( $key, $default ) );
            echo "<input type='text' name='" . self::OPTION . "[$key]' value='$val' class='regular-text'>";
        }, 'oc-theme-settings', $section );
    }

    private static function url_field( $key, $label, $default, $section ) {
        add_settings_field( $key, $label, function() use ( $key, $default ) {
            $val = esc_attr( self::get( $key, $default ) );
            // NOTE: type='text' (not 'url') — some fields accept relative paths
            // like "/contact/". HTML5 type='url' validation rejects these and
            // silently blocks form submission when they sit on a hidden tab,
            // so users get no error and think "Save" does nothing. Server-side
            // esc_url_raw in sanitize() still validates the value.
            echo "<input type='text' inputmode='url' name='" . self::OPTION . "[$key]' value='$val' class='regular-text' placeholder='https://... or /relative/path/'>";
        }, 'oc-theme-settings', $section );
    }

    private static function color_field( $key, $label, $default, $section ) {
        add_settings_field( $key, $label, function() use ( $key, $default ) {
            $val = esc_attr( self::get( $key, $default ) );
            echo "<input type='text' name='" . self::OPTION . "[$key]' value='$val' class='oc-color-picker' data-default-color='" . esc_attr( $default ) . "'>";
        }, 'oc-theme-settings', $section );
    }

    private static function textarea_field( $key, $label, $default, $section ) {
        add_settings_field( $key, $label, function() use ( $key, $default ) {
            $val = esc_textarea( self::get( $key, $default ) );
            echo "<textarea name='" . self::OPTION . "[$key]' rows='3' class='large-text'>$val</textarea>";
        }, 'oc-theme-settings', $section );
    }

    private static function checkbox_field( $key, $label, $default, $section ) {
        add_settings_field( $key, $label, function() use ( $key ) {
            $val = self::get( $key, '' );
            $chk = checked( $val, '1', false );
            echo "<input type='checkbox' name='" . self::OPTION . "[$key]' value='1' $chk>";
        }, 'oc-theme-settings', $section );
    }

    private static function select_field( $key, $label, $default, $options, $section ) {
        add_settings_field( $key, $label, function() use ( $key, $default, $options ) {
            $val = self::get( $key, $default );
            echo "<select name='" . self::OPTION . "[$key]'>";
            foreach ( $options as $opt_val => $opt_label ) {
                $sel = selected( $val, $opt_val, false );
                echo "<option value='" . esc_attr( $opt_val ) . "' $sel>" . esc_html( $opt_label ) . "</option>";
            }
            echo "</select>";
        }, 'oc-theme-settings', $section );
    }

    /* ── Get a single option value ────────────────────────────── */
    public static function get( $key, $default = '' ) {
        $opts = get_option( self::OPTION, [] );
        return $opts[ $key ] ?? $default;
    }

    /* ── Sanitize ─────────────────────────────────────────────── */
    public static function sanitize( $input ) {
        if ( ! is_array( $input ) ) return [];
        $clean = [];
        $color_keys = [
            'primary_color', 'bg_dark', 'surface_color', 'text_color', 'muted_color',
            'body_font_color', 'header_bg_color', 'nav_link_color', 'mobile_header_bg',
            'footer_bg_color', 'footer_text_color', 'footer_link_color',
            'offcanvas_bg_color', 'offcanvas_link_color',
        ];
        $url_keys = [ 'instagram', 'facebook', 'youtube', 'linkedin', 'nav_cta_url', 'crm_webhook_url', 'search_results_url' ];
        $textarea_keys = [ 'footer_tagline' ];
        $checkbox_keys = [ 'disable_emojis', 'disable_gutenberg', 'header_show_whatsapp', 'offcanvas_show_social', 'crm_webhook_enabled' ];

        foreach ( $input as $k => $v ) {
            $k = sanitize_key( $k );
            if ( in_array( $k, $color_keys, true ) ) {
                // Allow rgba() values in addition to hex
                $clean[$k] = sanitize_text_field( $v );
            } elseif ( in_array( $k, $url_keys, true ) ) {
                $clean[$k] = esc_url_raw( $v );
            } elseif ( in_array( $k, $textarea_keys, true ) ) {
                $clean[$k] = sanitize_textarea_field( $v );
            } elseif ( in_array( $k, $checkbox_keys, true ) ) {
                $clean[$k] = ( $v === '1' ) ? '1' : '';
            } else {
                $clean[$k] = sanitize_text_field( $v );
            }
        }
        // Preserve unchecked checkboxes (they don't appear in POST)
        foreach ( $checkbox_keys as $ck ) {
            if ( ! isset( $clean[ $ck ] ) ) $clean[ $ck ] = '';
        }
        return $clean;
    }

    /* ── Inject CSS custom properties into <head> ─────────────── */
    public static function inject_css_vars() {
        $primary     = self::get( 'primary_color',   '#d9b230' );
        $bg_dark     = self::get( 'bg_dark',          '#0a0f1a' );
        $surface     = self::get( 'surface_color',    '#111a28' );
        $text        = self::get( 'text_color',       '#f0ece3' );
        $muted       = self::get( 'muted_color',      '#94a3b8' );
        $hfont       = esc_attr( self::get( 'heading_font',   'Playfair Display' ) );
        $bfont       = esc_attr( self::get( 'body_font',      'Inter' ) );

        // Typography
        $bfont_weight  = esc_attr( self::get( 'body_font_weight',    '400' ) );
        $bfont_size    = (int) self::get( 'body_font_size',    16 );
        $bfont_color   = self::get( 'body_font_color',   '#f0ece3' );
        $hfont_weight  = esc_attr( self::get( 'heading_font_weight', '400' ) );
        $body_ls       = esc_attr( self::get( 'body_letter_spacing',    '0' ) );
        $head_ls       = esc_attr( self::get( 'heading_letter_spacing', '0' ) );
        $body_lh       = esc_attr( self::get( 'body_line_height',       '1.7' ) );

        // Header
        $logo_h        = (int) self::get( 'logo_max_height', 56 );
        $header_bg     = self::get( 'header_bg_color', 'rgba(10,15,26,0.85)' );
        $header_h      = (int) self::get( 'header_height', 72 );
        $nav_fs        = (int) self::get( 'nav_font_size', 14 );
        $nav_lc        = self::get( 'nav_link_color', '#f0ece3' );
        $mob_header_bg = self::get( 'mobile_header_bg', '#0a0f1a' );

        // Footer
        $footer_bg     = self::get( 'footer_bg_color',   '#060d18' );
        $footer_text   = self::get( 'footer_text_color', '#94a3b8' );
        $footer_link   = self::get( 'footer_link_color', '#f0ece3' );

        // Off-canvas
        $oc_bg         = self::get( 'offcanvas_bg_color',   '#070d1a' );
        $oc_link       = self::get( 'offcanvas_link_color', '#f0ece3' );
        $oc_fs         = (int) self::get( 'offcanvas_font_size', 18 );
        $oc_width      = (int) self::get( 'offcanvas_width',     300 );

        echo "<style id='oc-theme-vars'>:root{"
            . "--primary:{$primary};"
            . "--bg-dark:{$bg_dark};"
            . "--surface:{$surface};"
            . "--text:{$text};"
            . "--text-muted:{$muted};"
            . "--font-heading:'{$hfont}',serif;"
            . "--font-body:'{$bfont}',sans-serif;"
            . "--body-font-weight:{$bfont_weight};"
            . "--body-font-size:{$bfont_size}px;"
            . "--body-font-color:{$bfont_color};"
            . "--heading-font-weight:{$hfont_weight};"
            . "--body-letter-spacing:{$body_ls}em;"
            . "--heading-letter-spacing:{$head_ls}em;"
            . "--body-line-height:{$body_lh};"
            . "--logo-max-height:{$logo_h}px;"
            . "--header-bg:{$header_bg};"
            . "--header-height:{$header_h}px;"
            . "--nav-font-size:{$nav_fs}px;"
            . "--nav-link-color:{$nav_lc};"
            . "--mobile-header-bg:{$mob_header_bg};"
            . "--footer-bg:{$footer_bg};"
            . "--footer-text:{$footer_text};"
            . "--footer-link:{$footer_link};"
            . "--offcanvas-bg:{$oc_bg};"
            . "--offcanvas-link:{$oc_link};"
            . "--offcanvas-font-size:{$oc_fs}px;"
            . "--offcanvas-width:{$oc_width}px;"
            . "}</style>\n";
    }

    /* ── Render admin page ────────────────────────────────────── */
    public static function render_page() {
        if ( ! current_user_can( 'manage_options' ) ) return;
        $saved = isset( $_GET['settings-updated'] ) && $_GET['settings-updated'];

        $tabs = [
            'oc-tab-design'       => [ 'icon' => '🎨', 'label' => 'Design',        'section' => 'oc_design' ],
            'oc-tab-typography'   => [ 'icon' => '✍️',  'label' => 'Typography',    'section' => 'oc_typography' ],
            'oc-tab-header'       => [ 'icon' => '🧭', 'label' => 'Header',         'section' => 'oc_header' ],
            'oc-tab-footer'       => [ 'icon' => '📄', 'label' => 'Footer',         'section' => 'oc_footer' ],
            'oc-tab-mobile'       => [ 'icon' => '📱', 'label' => 'Mobile',         'section' => 'oc_mobile' ],
            'oc-tab-contact'      => [ 'icon' => '📞', 'label' => 'Contact',        'section' => 'oc_contact' ],
            'oc-tab-integrations' => [ 'icon' => '🔌', 'label' => 'Integrations',   'section' => 'oc_integrations' ],
            'oc-tab-search'       => [ 'icon' => '🔍', 'label' => 'Search Form',    'section' => 'oc_search' ],
            'oc-tab-performance'  => [ 'icon' => '⚡', 'label' => 'Performance',    'section' => 'oc_perf' ],
        ];
        ?>
        <div class="wrap oc-settings-wrap">
            <h1 style="display:flex;align-items:center;gap:.5rem;margin-bottom:1.5rem;">
                ⚓ Ocean Charter — Theme Settings
            </h1>

            <?php if ( $saved ) : ?>
                <div class="notice notice-success is-dismissible"><p><strong>Settings saved successfully.</strong></p></div>
            <?php endif; ?>

            <nav class="nav-tab-wrapper" id="oc-tabs-nav" style="margin-bottom:0;">
                <?php $first = true; foreach ( $tabs as $id => $tab ) : ?>
                    <a href="#<?php echo esc_attr( $id ); ?>"
                       class="nav-tab<?php echo $first ? ' nav-tab-active' : ''; ?>"
                       data-tab="<?php echo esc_attr( $id ); ?>">
                        <?php echo esc_html( $tab['icon'] . ' ' . $tab['label'] ); ?>
                    </a>
                <?php $first = false; endforeach; ?>
            </nav>

            <form method="post" action="options.php" style="margin-top:0;">
                <?php settings_fields( 'oc_theme_group' ); ?>

                <?php $first = true; foreach ( $tabs as $id => $tab ) : ?>
                <div id="<?php echo esc_attr( $id ); ?>"
                     class="oc-tab-panel"
                     <?php echo $first ? '' : 'hidden'; ?>
                     style="background:#fff;border:1px solid #c3c4c7;border-top:none;padding:1.5rem 2rem 2rem;">
                    <table class="form-table" role="presentation">
                        <?php do_settings_fields( 'oc-theme-settings', $tab['section'] ); ?>
                    </table>
                </div>
                <?php $first = false; endforeach; ?>

                <div style="padding:1.5rem 0 0;">
                    <?php submit_button( 'Save All Settings' ); ?>
                </div>
            </form>
        </div>

        <script>
        (function() {
            var tabs   = document.querySelectorAll('#oc-tabs-nav .nav-tab');
            var panels = document.querySelectorAll('.oc-tab-panel');
            var stored = localStorage.getItem('oc_settings_tab');

            function activateTab(targetId) {
                tabs.forEach(function(t) { t.classList.remove('nav-tab-active'); });
                panels.forEach(function(p) { p.setAttribute('hidden', ''); });
                var activeTab = document.querySelector('[data-tab="' + targetId + '"]');
                var activePanel = document.getElementById(targetId);
                if (activeTab)  activeTab.classList.add('nav-tab-active');
                if (activePanel) activePanel.removeAttribute('hidden');
                localStorage.setItem('oc_settings_tab', targetId);
            }

            tabs.forEach(function(tab) {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    activateTab(tab.getAttribute('data-tab'));
                });
            });

            // Restore last active tab
            if (stored && document.getElementById(stored)) {
                activateTab(stored);
            }
        })();
        </script>
        <?php
    }
}

OC_Theme_Settings::init();

/**
 * Get an Ocean Charter theme setting.
 *
 * @param string $key     Setting key.
 * @param string $default Fallback value.
 * @return string
 */
function oc_setting( $key, $default = '' ) {
    return OC_Theme_Settings::get( $key, $default );
}
