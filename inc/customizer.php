<?php
/**
 * Theme Customizer settings
 *
 * @package OceanCharter
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function oc_customizer_register( $wp_customize ) {

    // ── Logo & Branding ───────────────────────────────────────────────────────
    // Note: add_theme_support( 'custom-logo' ) is registered in setup.php.
    // The native WP site_logo control will appear in the Customizer automatically.
    $wp_customize->add_section( 'oc_branding', [
        'title'    => __( 'Logo & Branding', 'ocean-charter' ),
        'priority' => 25,
    ] );

    $wp_customize->add_setting( 'oc_brand_name', [
        'default'           => 'Ocean Charter',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'oc_brand_name', [
        'label'       => __( 'Brand Name', 'ocean-charter' ),
        'description' => __( 'Shown in the navigation bar when no logo is set.', 'ocean-charter' ),
        'section'     => 'oc_branding',
        'type'        => 'text',
    ] );

    $wp_customize->add_setting( 'oc_brand_tagline', [
        'default'           => 'Luxury Yacht Charters',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'oc_brand_tagline', [
        'label'   => __( 'Brand Tagline', 'ocean-charter' ),
        'section' => 'oc_branding',
        'type'    => 'text',
    ] );

    $wp_customize->add_setting( 'oc_favicon_url', [
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'oc_favicon_url', [
        'label'   => __( 'Favicon URL', 'ocean-charter' ),
        'section' => 'oc_branding',
        'type'    => 'text',
    ] );

    // ── Theme Colors ──────────────────────────────────────────────────────────
    $wp_customize->add_section( 'oc_colors', [
        'title'       => __( 'Theme Colors', 'ocean-charter' ),
        'description' => __( 'Customise the Ocean Charter colour scheme.', 'ocean-charter' ),
        'priority'    => 30,
    ] );

    // Primary / Gold
    $wp_customize->add_setting( 'oc_color_primary', [
        'default'           => '#d9b230',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'oc_color_primary', [
        'label'       => __( 'Primary / Gold Colour', 'ocean-charter' ),
        'description' => __( 'The accent gold used for highlights, buttons, and borders.', 'ocean-charter' ),
        'section'     => 'oc_colors',
    ] ) );

    // Page Background
    $wp_customize->add_setting( 'oc_color_background', [
        'default'           => '#0a0f1a',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'oc_color_background', [
        'label'       => __( 'Page Background', 'ocean-charter' ),
        'description' => __( 'Deep dark navy background colour.', 'ocean-charter' ),
        'section'     => 'oc_colors',
    ] ) );

    // Card / Surface Background
    $wp_customize->add_setting( 'oc_color_surface', [
        'default'           => '#111a28',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'oc_color_surface', [
        'label'   => __( 'Card / Surface Background', 'ocean-charter' ),
        'section' => 'oc_colors',
    ] ) );

    // Body Text
    $wp_customize->add_setting( 'oc_color_text', [
        'default'           => '#f0ece3',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'oc_color_text', [
        'label'   => __( 'Body Text Colour', 'ocean-charter' ),
        'section' => 'oc_colors',
    ] ) );

    // Muted Text
    $wp_customize->add_setting( 'oc_color_text_muted', [
        'default'           => '#8a9bb0',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'oc_color_text_muted', [
        'label'   => __( 'Muted Text Colour', 'ocean-charter' ),
        'section' => 'oc_colors',
    ] ) );

    // Border (rgba — uses text control)
    $wp_customize->add_setting( 'oc_color_border', [
        'default'           => 'rgba(217,178,48,0.12)',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'oc_color_border', [
        'label'       => __( 'Border Colour', 'ocean-charter' ),
        'description' => __( 'Use rgba() format e.g. rgba(217,178,48,0.12)', 'ocean-charter' ),
        'section'     => 'oc_colors',
        'type'        => 'text',
    ] );

    // ── Colour Presets ────────────────────────────────────────────────────────
    $wp_customize->add_section( 'oc_color_presets', [
        'title'       => __( 'Colour Presets', 'ocean-charter' ),
        'description' => __( 'Quick-apply a full colour scheme. Selecting a preset will override individual colour settings on save.', 'ocean-charter' ),
        'priority'    => 32,
    ] );

    $wp_customize->add_setting( 'oc_color_preset', [
        'default'           => 'custom',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'oc_color_preset', [
        'label'   => __( 'Apply Colour Preset', 'ocean-charter' ),
        'section' => 'oc_color_presets',
        'type'    => 'select',
        'choices' => [
            'custom'        => __( 'Custom', 'ocean-charter' ),
            'dark_luxury'   => __( 'Dark Luxury (Default — navy + gold)', 'ocean-charter' ),
            'midnight_blue' => __( 'Midnight Blue — deep blue + ice blue accent', 'ocean-charter' ),
            'white_gold'    => __( 'White & Gold — light background, gold accents', 'ocean-charter' ),
            'forest_green'  => __( 'Forest Green — dark green + warm gold', 'ocean-charter' ),
            'ocean_teal'    => __( 'Ocean Teal — dark + teal accent', 'ocean-charter' ),
        ],
    ] );

    // ── Typography ────────────────────────────────────────────────────────────
    $wp_customize->add_section( 'oc_typography', [
        'title'    => __( 'Typography', 'ocean-charter' ),
        'priority' => 35,
    ] );

    // Heading Font
    $wp_customize->add_setting( 'oc_font_heading', [
        'default'           => 'Playfair Display',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'oc_font_heading', [
        'label'   => __( 'Heading Font', 'ocean-charter' ),
        'section' => 'oc_typography',
        'type'    => 'select',
        'choices' => [
            'Playfair Display'   => __( 'Playfair Display (Serif, Default)', 'ocean-charter' ),
            'Cormorant Garamond' => __( 'Cormorant Garamond', 'ocean-charter' ),
            'Libre Baskerville'  => __( 'Libre Baskerville', 'ocean-charter' ),
            'Cinzel'             => __( 'Cinzel', 'ocean-charter' ),
            'Merriweather'       => __( 'Merriweather', 'ocean-charter' ),
        ],
    ] );

    // Body Font
    $wp_customize->add_setting( 'oc_font_body', [
        'default'           => 'Inter',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'oc_font_body', [
        'label'   => __( 'Body Font', 'ocean-charter' ),
        'section' => 'oc_typography',
        'type'    => 'select',
        'choices' => [
            'Inter'      => __( 'Inter (Default)', 'ocean-charter' ),
            'Lato'       => __( 'Lato', 'ocean-charter' ),
            'Open Sans'  => __( 'Open Sans', 'ocean-charter' ),
            'Raleway'    => __( 'Raleway', 'ocean-charter' ),
            'Nunito'     => __( 'Nunito', 'ocean-charter' ),
        ],
    ] );

    // Base Font Size
    $wp_customize->add_setting( 'oc_font_size_base', [
        'default'           => '16',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'oc_font_size_base', [
        'label'        => __( 'Base Font Size (px)', 'ocean-charter' ),
        'section'      => 'oc_typography',
        'type'         => 'range',
        'input_attrs'  => [ 'min' => 14, 'max' => 20, 'step' => 1 ],
    ] );

    // ── Layout ────────────────────────────────────────────────────────────────
    $wp_customize->add_section( 'oc_layout', [
        'title'    => __( 'Layout', 'ocean-charter' ),
        'priority' => 40,
    ] );

    // Section Horizontal Padding
    $wp_customize->add_setting( 'oc_section_padding_x', [
        'default'           => '120',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'oc_section_padding_x', [
        'label'       => __( 'Section Horizontal Padding (px)', 'ocean-charter' ),
        'description' => __( 'Left and right padding for all content sections.', 'ocean-charter' ),
        'section'     => 'oc_layout',
        'type'        => 'range',
        'input_attrs' => [ 'min' => 40, 'max' => 200, 'step' => 10 ],
    ] );

    // Section Vertical Padding
    $wp_customize->add_setting( 'oc_section_padding_y', [
        'default'           => '80',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'oc_section_padding_y', [
        'label'       => __( 'Section Vertical Padding (px)', 'ocean-charter' ),
        'section'     => 'oc_layout',
        'type'        => 'range',
        'input_attrs' => [ 'min' => 40, 'max' => 160, 'step' => 10 ],
    ] );

    // Max Content Width
    $wp_customize->add_setting( 'oc_container_max_width', [
        'default'           => '1400',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'oc_container_max_width', [
        'label'       => __( 'Max Content Width (px)', 'ocean-charter' ),
        'section'     => 'oc_layout',
        'type'        => 'range',
        'input_attrs' => [ 'min' => 960, 'max' => 1920, 'step' => 40 ],
    ] );

    // Card Border Radius
    $wp_customize->add_setting( 'oc_card_radius', [
        'default'           => '12',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'oc_card_radius', [
        'label'       => __( 'Card Border Radius (px)', 'ocean-charter' ),
        'section'     => 'oc_layout',
        'type'        => 'range',
        'input_attrs' => [ 'min' => 0, 'max' => 32, 'step' => 2 ],
    ] );

    // Badge Border Width (25+ Years card)
    $wp_customize->add_setting( 'oc_badge_border_width', [
        'default'           => '3',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'oc_badge_border_width', [
        'label'       => __( 'Experience Badge Border Width (px)', 'ocean-charter' ),
        'description' => __( 'Border thickness on the "25+ Years" card on the homepage.', 'ocean-charter' ),
        'section'     => 'oc_layout',
        'type'        => 'range',
        'input_attrs' => [ 'min' => 0, 'max' => 10, 'step' => 1 ],
    ] );

    // Grid Gap
    $wp_customize->add_setting( 'oc_grid_gap', [
        'default'           => '24',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'oc_grid_gap', [
        'label'       => __( 'Grid Gap (px)', 'ocean-charter' ),
        'section'     => 'oc_layout',
        'type'        => 'range',
        'input_attrs' => [ 'min' => 8, 'max' => 64, 'step' => 4 ],
    ] );

    // ── Header & Navigation ───────────────────────────────────────────────────
    $wp_customize->add_section( 'oc_header', [
        'title'    => __( 'Header & Navigation', 'ocean-charter' ),
        'priority' => 45,
    ] );

    // Sticky Navigation
    $wp_customize->add_setting( 'oc_nav_sticky', [
        'default'           => '1',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'oc_nav_sticky', [
        'label'   => __( 'Sticky Navigation', 'ocean-charter' ),
        'section' => 'oc_header',
        'type'    => 'checkbox',
    ] );

    // Nav Blur Effect
    $wp_customize->add_setting( 'oc_nav_blur', [
        'default'           => '1',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'oc_nav_blur', [
        'label'   => __( 'Nav Blur Effect (glassmorphism)', 'ocean-charter' ),
        'section' => 'oc_header',
        'type'    => 'checkbox',
    ] );

    // CTA Button Label
    $wp_customize->add_setting( 'oc_cta_button_label', [
        'default'           => 'Book a Charter',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'oc_cta_button_label', [
        'label'   => __( 'Nav CTA Button Text', 'ocean-charter' ),
        'section' => 'oc_header',
        'type'    => 'text',
    ] );

    // CTA Button URL
    $wp_customize->add_setting( 'oc_cta_button_url', [
        'default'           => '/contact/',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'oc_cta_button_url', [
        'label'   => __( 'Nav CTA Button URL', 'ocean-charter' ),
        'section' => 'oc_header',
        'type'    => 'text',
    ] );

    // CTA Button Background Colour
    $wp_customize->add_setting( 'oc_cta_bg_color', [
        'default'           => '#d9b230',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'oc_cta_bg_color', [
        'label'   => __( 'CTA Button Background', 'ocean-charter' ),
        'section' => 'oc_header',
    ] ) );

    // CTA Button Text Colour
    $wp_customize->add_setting( 'oc_cta_text_color', [
        'default'           => '#0a0f1a',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'oc_cta_text_color', [
        'label'   => __( 'CTA Button Text Colour', 'ocean-charter' ),
        'section' => 'oc_header',
    ] ) );

    // Header Glass Opacity
    $wp_customize->add_setting( 'oc_header_glass_opacity', [
        'default'           => '70',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'oc_header_glass_opacity', [
        'label'       => __( 'Header Glass Opacity (%)', 'ocean-charter' ),
        'description' => __( 'Transparency of the navigation pill background.', 'ocean-charter' ),
        'section'     => 'oc_header',
        'type'        => 'range',
        'input_attrs' => [ 'min' => 0, 'max' => 100, 'step' => 5 ],
    ] );

    // Nav Link Colour
    $wp_customize->add_setting( 'oc_nav_link_color', [
        'default'           => '#8a9bb0',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'oc_nav_link_color', [
        'label'   => __( 'Nav Link Colour', 'ocean-charter' ),
        'section' => 'oc_header',
    ] ) );

    // ── Contact & Social ──────────────────────────────────────────────────────
    $wp_customize->add_section( 'oc_contact', [
        'title'       => __( 'Contact & Social', 'ocean-charter' ),
        'description' => __( 'Phone, email, address and WhatsApp number displayed site-wide.', 'ocean-charter' ),
        'priority'    => 120,
    ] );

    $contact_settings = [
        'oc_whatsapp_number' => [
            'default' => '15551234567',
            'label'   => __( 'WhatsApp Number (E.164, no +)', 'ocean-charter' ),
        ],
        'oc_contact_email' => [
            'default' => 'info@oceancharter.com',
            'label'   => __( 'Contact Email', 'ocean-charter' ),
        ],
        'oc_contact_phone' => [
            'default' => '+1 (555) 123-4567',
            'label'   => __( 'Contact Phone', 'ocean-charter' ),
        ],
        'oc_contact_address' => [
            'default' => '123 Marina Drive, Miami, FL 33101',
            'label'   => __( 'Office Address', 'ocean-charter' ),
        ],
    ];

    foreach ( $contact_settings as $id => $args ) {
        $wp_customize->add_setting( $id, [
            'default'           => $args['default'],
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ] );
        $wp_customize->add_control( $id, [
            'label'   => $args['label'],
            'section' => 'oc_contact',
            'type'    => 'text',
        ] );
    }

    // ── Footer ────────────────────────────────────────────────────────────────
    $wp_customize->add_section( 'oc_footer', [
        'title'    => __( 'Footer', 'ocean-charter' ),
        'priority' => 150,
    ] );

    $wp_customize->add_setting( 'oc_footer_tagline', [
        'default'           => 'Bespoke yacht charters across the world\'s most coveted waters.',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'oc_footer_tagline', [
        'label'   => __( 'Footer Tagline', 'ocean-charter' ),
        'section' => 'oc_footer',
        'type'    => 'text',
    ] );

    $wp_customize->add_setting( 'oc_footer_copyright', [
        'default'           => '© 2026 Ocean Charter. All rights reserved.',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'oc_footer_copyright', [
        'label'   => __( 'Copyright Text', 'ocean-charter' ),
        'section' => 'oc_footer',
        'type'    => 'text',
    ] );
}
add_action( 'customize_register', 'oc_customizer_register' );

// =============================================================================
// CSS OUTPUT — injects CSS custom properties into <head>
// =============================================================================
function oc_customizer_css() {
    $primary    = get_theme_mod( 'oc_color_primary',    '#d9b230' );
    $bg         = get_theme_mod( 'oc_color_background', '#0a0f1a' );
    $surface    = get_theme_mod( 'oc_color_surface',    '#111a28' );
    $text       = get_theme_mod( 'oc_color_text',       '#f0ece3' );
    $muted      = get_theme_mod( 'oc_color_text_muted', '#8a9bb0' );
    $border     = get_theme_mod( 'oc_color_border',     'rgba(217,178,48,0.12)' );
    $font_h     = get_theme_mod( 'oc_font_heading',     'Playfair Display' );
    $font_b     = get_theme_mod( 'oc_font_body',        'Inter' );
    $font_sz    = (int) get_theme_mod( 'oc_font_size_base', 16 );
    $pad_x      = (int) get_theme_mod( 'oc_section_padding_x', 120 );
    $pad_y      = (int) get_theme_mod( 'oc_section_padding_y', 80 );
    $max_w      = (int) get_theme_mod( 'oc_container_max_width', 1400 );
    $radius     = (int) get_theme_mod( 'oc_card_radius', 12 );
    $gap        = (int) get_theme_mod( 'oc_grid_gap', 24 );
    $badge_bw   = (int) get_theme_mod( 'oc_badge_border_width', 3 );
    $cta_bg     = get_theme_mod( 'oc_cta_bg_color', '#d9b230' );
    $cta_txt    = get_theme_mod( 'oc_cta_text_color', '#0a0f1a' );
    $glass_op   = (int) get_theme_mod( 'oc_header_glass_opacity', 70 );
    $nav_link   = get_theme_mod( 'oc_nav_link_color', '#8a9bb0' );
    $preset     = get_theme_mod( 'oc_color_preset', 'custom' );

    // Apply preset overrides
    $presets = [
        'midnight_blue' => [ 'primary' => '#4da6ff', 'bg' => '#050d1a', 'surface' => '#0a1628', 'text' => '#e8f0fe', 'muted' => '#7090b0', 'border' => 'rgba(77,166,255,0.15)' ],
        'white_gold'    => [ 'primary' => '#c9a227', 'bg' => '#f8f5ef', 'surface' => '#ffffff',  'text' => '#1a1a2e', 'muted' => '#6b6b8a', 'border' => 'rgba(201,162,39,0.2)' ],
        'forest_green'  => [ 'primary' => '#c9a227', 'bg' => '#0a1a0d', 'surface' => '#112216', 'text' => '#e8f0e8', 'muted' => '#7a9a7a', 'border' => 'rgba(201,162,39,0.15)' ],
        'ocean_teal'    => [ 'primary' => '#00c9b1', 'bg' => '#060f14', 'surface' => '#0a1e22', 'text' => '#e0f0ee', 'muted' => '#6a9ea0', 'border' => 'rgba(0,201,177,0.15)' ],
    ];

    if ( $preset !== 'custom' && isset( $presets[ $preset ] ) ) {
        $p       = $presets[ $preset ];
        $primary = $p['primary'];
        $bg      = $p['bg'];
        $surface = $p['surface'];
        $text    = $p['text'];
        $muted   = $p['muted'];
        $border  = $p['border'];
    }

    // Enqueue Google Fonts dynamically
    $google_fonts = [ $font_h, $font_b ];
    $font_pairs   = array_unique( array_filter( $google_fonts ) );
    if ( $font_pairs ) {
        $families = implode( '&family=', array_map( fn( $f ) => urlencode( $f ) . ':ital,wght@0,300;0,400;0,600;0,700;0,900;1,400;1,700', $font_pairs ) );
        echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
        echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
        echo '<link href="https://fonts.googleapis.com/css2?family=' . $families . '&display=swap" rel="stylesheet">';
    }
    ?>
    <style id="oc-customizer-css">
    :root {
        --primary:        <?php echo esc_attr( $primary ); ?>;
        --secondary:      <?php echo esc_attr( $bg ); ?>;
        --surface:        <?php echo esc_attr( $surface ); ?>;
        --text:           <?php echo esc_attr( $text ); ?>;
        --text-muted:     <?php echo esc_attr( $muted ); ?>;
        --border:         <?php echo esc_attr( $border ); ?>;
        --font-heading:   '<?php echo esc_attr( $font_h ); ?>', serif;
        --font-body:      '<?php echo esc_attr( $font_b ); ?>', sans-serif;
        --font-size-base: <?php echo $font_sz; ?>px;
        --section-pad-x:  <?php echo $pad_x; ?>px;
        --section-pad-y:  <?php echo $pad_y; ?>px;
        --container-max:  <?php echo $max_w; ?>px;
        --radius:         <?php echo $radius; ?>px;
        --radius-lg:      <?php echo round( $radius * 1.5 ); ?>px;
        --radius-pill:    9999px;
        --grid-gap:       <?php echo $gap; ?>px;
        --badge-border-width: <?php echo $badge_bw; ?>px;
        --glass-bg:       rgba(26,34,51,<?php echo round( $glass_op / 100, 2 ); ?>);
        --glass-border:   rgba(<?php echo oc_hex_to_rgb( $primary ); ?>,0.15);
        --cta-bg:         <?php echo esc_attr( $cta_bg ); ?>;
        --cta-text:       <?php echo esc_attr( $cta_txt ); ?>;
        --nav-link-color: <?php echo esc_attr( $nav_link ); ?>;
        --transition:     0.2s ease;
    }
    body { background-color: var(--secondary); color: var(--text); font-family: var(--font-body); font-size: var(--font-size-base); }
    .container { max-width: var(--container-max); margin: 0 auto; padding-left: var(--section-pad-x); padding-right: var(--section-pad-x); }
    </style>
    <?php
}
add_action( 'wp_head', 'oc_customizer_css', 99 );

// Helper: convert hex colour to r,g,b string for use in rgba()
function oc_hex_to_rgb( string $hex ): string {
    $hex = ltrim( $hex, '#' );
    if ( strlen( $hex ) === 3 ) {
        $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
    }
    return hexdec( substr( $hex, 0, 2 ) ) . ',' . hexdec( substr( $hex, 2, 2 ) ) . ',' . hexdec( substr( $hex, 4, 2 ) );
}
