<?php
/**
 * Ocean Charter custom Elementor widgets.
 * Loaded only when ELEMENTOR_VERSION is defined.
 */
if ( ! defined( 'ABSPATH' ) || ! defined( 'ELEMENTOR_VERSION' ) ) exit;

/* ============================================================
   1. OC Hero Widget
   ============================================================ */
class OC_Hero_Widget extends \Elementor\Widget_Base {
    use OC_Widget_Style_Trait;

    public function get_name()       { return 'oc-hero'; }
    public function get_title()      { return __( 'OC Hero Section', 'ocean-charter' ); }
    public function get_icon()       { return 'eicon-banner'; }
    public function get_categories() { return [ 'ocean-charter' ]; }

    protected function register_controls() {

        /* ── Content ─────────────────────────────────────────── */
        $this->start_controls_section( 'content', [ 'label' => __( 'Content', 'ocean-charter' ) ] );
        $this->add_control( 'eyebrow', [
            'label'   => __( 'Eyebrow Text', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'Luxury Yacht Charters', 'ocean-charter' ),
        ] );
        $this->add_control( 'heading', [
            'label'   => __( 'Heading (white part)', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'Define Your', 'ocean-charter' ),
        ] );
        $this->add_control( 'heading_accent', [
            'label'       => __( 'Heading Accent (gold italic)', 'ocean-charter' ),
            'type'        => \Elementor\Controls_Manager::TEXT,
            'default'     => __( 'Horizon', 'ocean-charter' ),
            'description' => __( 'Appears in gold italic after the main heading.', 'ocean-charter' ),
        ] );
        $this->add_control( 'subheading', [
            'label'   => __( 'Subheading', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::TEXTAREA,
            'default' => __( 'Experience unparalleled luxury with our curated fleet of private yachts. From the Mediterranean to the Caribbean, sail the world on your terms.', 'ocean-charter' ),
        ] );
        $this->add_control( 'bg_image', [
            'label' => __( 'Background Image', 'ocean-charter' ),
            'type'  => \Elementor\Controls_Manager::MEDIA,
        ] );
        $this->add_control( 'bg_image_position', [
            'label'       => __( 'Image Position', 'ocean-charter' ),
            'type'        => \Elementor\Controls_Manager::SELECT,
            'default'     => 'center center',
            'options'     => [
                'center center' => __( 'Center (default)', 'ocean-charter' ),
                'top left'      => __( 'Top Left', 'ocean-charter' ),
                'top center'    => __( 'Top Center', 'ocean-charter' ),
                'top right'     => __( 'Top Right', 'ocean-charter' ),
                'center left'   => __( 'Middle Left', 'ocean-charter' ),
                'center right'  => __( 'Middle Right', 'ocean-charter' ),
                'bottom left'   => __( 'Bottom Left', 'ocean-charter' ),
                'bottom center' => __( 'Bottom Center', 'ocean-charter' ),
                'bottom right'  => __( 'Bottom Right', 'ocean-charter' ),
            ],
            'description' => __( 'Controls which part of the image stays in view when the hero section is cropped (e.g. top center for landscape photos).', 'ocean-charter' ),
        ] );
        $this->add_control( 'overlay_opacity', [
            'label'   => __( 'Overlay Opacity', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::SLIDER,
            'range'   => [ 'px' => [ 'min' => 0, 'max' => 1, 'step' => 0.05 ] ],
            'default' => [ 'size' => 0.45 ],
        ] );
        $this->add_control( 'show_search', [
            'label'        => __( 'Show Built-in Search Bar', 'ocean-charter' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => '',
            'description'  => __( 'Turn OFF to use the BBC Boat Search widget instead (drag it below the hero).', 'ocean-charter' ),
        ] );
        $this->add_control( 'cta_label', [
            'label'     => __( 'CTA Button Label', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::TEXT,
            'default'   => __( 'Explore Fleet', 'ocean-charter' ),
            'condition' => [ 'show_search!' => 'yes' ],
        ] );
        $this->add_control( 'cta_url', [
            'label'     => __( 'CTA URL', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::URL,
            'default'   => [ 'url' => '/fleet/' ],
            'condition' => [ 'show_search!' => 'yes' ],
        ] );
        $this->add_control( 'secondary_label', [
            'label'     => __( 'Secondary CTA Label', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::TEXT,
            'default'   => __( 'View Packages', 'ocean-charter' ),
            'condition' => [ 'show_search!' => 'yes' ],
        ] );
        $this->add_control( 'secondary_url', [
            'label'     => __( 'Secondary CTA URL', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::URL,
            'default'   => [ 'url' => '/packages/' ],
            'condition' => [ 'show_search!' => 'yes' ],
        ] );
        $this->add_control( 'hero_style', [
            'label'   => __( 'Hero Style', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => 'full',
            'options' => [
                'full'   => __( 'Full Height', 'ocean-charter' ),
                'banner' => __( 'Banner / Short', 'ocean-charter' ),
            ],
        ] );
        $this->end_controls_section();

        /* ── Search Bar ──────────────────────────────────────── */
        $this->start_controls_section( 'section_search', [
            'label'     => __( 'Search Bar Fields', 'ocean-charter' ),
            'condition' => [ 'show_search' => 'yes' ],
        ] );
        $this->add_control( 'search_dest_label',       [ 'label' => __( 'Destination Label', 'ocean-charter' ),    'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Destination' ] );
        $this->add_control( 'search_dest_placeholder', [ 'label' => __( 'Destination Placeholder', 'ocean-charter' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Where to?' ] );
        $this->add_control( 'search_dates_label',      [ 'label' => __( 'Dates Label', 'ocean-charter' ),          'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Dates' ] );
        $this->add_control( 'search_dates_placeholder',[ 'label' => __( 'Dates Placeholder', 'ocean-charter' ),    'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Select dates' ] );
        $this->add_control( 'search_guests_label',     [ 'label' => __( 'Guests Label', 'ocean-charter' ),         'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Guests' ] );
        $this->add_control( 'search_btn_label',        [ 'label' => __( 'Search Button Text', 'ocean-charter' ),   'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Search Fleet' ] );
        $this->add_control( 'search_action_url',       [ 'label' => __( 'Search Button URL', 'ocean-charter' ),    'type' => \Elementor\Controls_Manager::URL,  'default' => [ 'url' => '/fleet/' ] ] );
        $this->end_controls_section();

        /* ── Style: Heading ──────────────────────────────────── */
        $this->start_controls_section( 'style_hero_heading', [
            'label' => __( 'Heading', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'hero_heading_typography',
            'label'    => __( 'Typography', 'ocean-charter' ),
            'selector' => '{{WRAPPER}} .oc-hero__content h1',
        ] );
        $this->add_control( 'hero_heading_color', [
            'label'     => __( 'Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#f0ece3',
            'selectors' => [ '{{WRAPPER}} .oc-hero__content h1' => 'color: {{VALUE}};' ],
        ] );
        $this->add_control( 'hero_accent_color', [
            'label'     => __( 'Accent Word Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#d9b230',
            'selectors' => [ '{{WRAPPER}} .oc-hero__accent' => 'color: {{VALUE}};' ],
        ] );
        $this->end_controls_section();

        /* ── Style: Subheading ───────────────────────────────── */
        $this->start_controls_section( 'style_hero_subheading', [
            'label' => __( 'Subheading', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'hero_subheading_typography',
            'label'    => __( 'Typography', 'ocean-charter' ),
            'selector' => '{{WRAPPER}} .oc-hero__content p',
        ] );
        $this->add_control( 'hero_subheading_color', [
            'label'     => __( 'Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .oc-hero__content p' => 'color: {{VALUE}};' ],
        ] );
        $this->end_controls_section();

        /* ── Style: Search Form ──────────────────────────────── */
        $this->start_controls_section( 'style_search_form', [
            'label'     => __( 'Search Form', 'ocean-charter' ),
            'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => [ 'show_search' => 'yes' ],
        ] );
        $this->add_control( 'search_bg_color', [
            'label'     => __( 'Form Background', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => 'rgba(10,16,26,0.72)',
            'selectors' => [ '{{WRAPPER}} .oc-hero-search' => 'background: {{VALUE}};' ],
        ] );
        $this->add_control( 'search_field_label_color', [
            'label'     => __( 'Field Label Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#d9b230',
            'selectors' => [ '{{WRAPPER}} .oc-hero-search__label' => 'color: {{VALUE}};' ],
        ] );
        $this->add_control( 'search_input_text_color', [
            'label'     => __( 'Input Text Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#f0ece3',
            'selectors' => [ '{{WRAPPER}} .oc-hero-search__input, {{WRAPPER}} .oc-hero-search__select' => 'color: {{VALUE}};' ],
        ] );
        $this->add_control( 'search_btn_bg_color', [
            'label'     => __( 'Button Background', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#d9b230',
            'selectors' => [ '{{WRAPPER}} .oc-hero-search__btn' => 'background: {{VALUE}};' ],
        ] );
        $this->add_control( 'search_btn_text_color', [
            'label'     => __( 'Button Text Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#0a0f1a',
            'selectors' => [ '{{WRAPPER}} .oc-hero-search__btn' => 'color: {{VALUE}};' ],
        ] );
        $this->end_controls_section();

        /* ── Style: CTA Buttons ──────────────────────────────── */
        $this->start_controls_section( 'style_hero_btn_primary', [
            'label'     => __( 'Primary Button', 'ocean-charter' ),
            'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => [ 'show_search!' => 'yes' ],
        ] );
        $this->add_control( 'hero_btn_primary_bg',     [ 'label' => __( 'Background', 'ocean-charter' ),   'type' => \Elementor\Controls_Manager::COLOR,  'selectors' => [ '{{WRAPPER}} .oc-hero__actions .btn-primary' => 'background: {{VALUE}};' ] ] );
        $this->add_control( 'hero_btn_primary_color',  [ 'label' => __( 'Text Color', 'ocean-charter' ),   'type' => \Elementor\Controls_Manager::COLOR,  'selectors' => [ '{{WRAPPER}} .oc-hero__actions .btn-primary' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'hero_btn_primary_radius', [ 'label' => __( 'Border Radius', 'ocean-charter' ),'type' => \Elementor\Controls_Manager::SLIDER, 'size_units' => [ 'px','rem' ], 'range' => [ 'px' => [ 'min' => 0, 'max' => 9999 ] ], 'selectors' => [ '{{WRAPPER}} .oc-hero__actions .btn-primary' => 'border-radius: {{SIZE}}{{UNIT}};' ] ] );
        $this->end_controls_section();
        $this->start_controls_section( 'style_hero_btn_secondary', [
            'label'     => __( 'Secondary Button', 'ocean-charter' ),
            'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => [ 'show_search!' => 'yes' ],
        ] );
        $this->add_control( 'hero_btn_secondary_border', [ 'label' => __( 'Border Color', 'ocean-charter' ), 'type' => \Elementor\Controls_Manager::COLOR,  'selectors' => [ '{{WRAPPER}} .oc-hero__actions .btn-secondary' => 'border-color: {{VALUE}};' ] ] );
        $this->add_control( 'hero_btn_secondary_color',  [ 'label' => __( 'Text Color', 'ocean-charter' ),   'type' => \Elementor\Controls_Manager::COLOR,  'selectors' => [ '{{WRAPPER}} .oc-hero__actions .btn-secondary' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'hero_btn_secondary_radius', [ 'label' => __( 'Border Radius', 'ocean-charter' ),'type' => \Elementor\Controls_Manager::SLIDER, 'size_units' => [ 'px','rem' ], 'range' => [ 'px' => [ 'min' => 0, 'max' => 9999 ] ], 'selectors' => [ '{{WRAPPER}} .oc-hero__actions .btn-secondary' => 'border-radius: {{SIZE}}{{UNIT}};' ] ] );
        $this->end_controls_section();

        /* ── Style: Section Spacing ─────────────────────────── */
        $this->start_controls_section( 'style_hero_spacing', [
            'label' => __( 'Section Spacing', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_responsive_control( 'hero_min_height', [
            'label'      => __( 'Min Height', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'vh', 'px', '%' ],
            'range'      => [ 'vh' => [ 'min' => 20, 'max' => 100 ], 'px' => [ 'min' => 200, 'max' => 1200 ], '%' => [ 'min' => 20, 'max' => 100 ] ],
            'default'    => [ 'size' => 100, 'unit' => 'vh' ],
            'selectors'  => [ '{{WRAPPER}} .oc-hero' => 'min-height: {{SIZE}}{{UNIT}};' ],
        ] );
        $this->add_responsive_control( 'hero_section_padding', [
            'label'      => __( 'Section Padding', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'rem', '%' ],
            'selectors'  => [ '{{WRAPPER}} .oc-hero' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );
        $this->add_responsive_control( 'hero_section_margin', [
            'label'      => __( 'Section Margin', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'rem', '%' ],
            'selectors'  => [ '{{WRAPPER}} .oc-hero' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );
        $this->add_responsive_control( 'hero_content_padding', [
            'label'      => __( 'Content Padding', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'rem', '%' ],
            'selectors'  => [ '{{WRAPPER}} .oc-hero__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );
        $this->add_responsive_control( 'hero_heading_line_height', [
            'label'      => __( 'Heading Line Height', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'em', 'px', '' ],
            'range'      => [ 'em' => [ 'min' => 0.8, 'max' => 2, 'step' => 0.05 ], 'px' => [ 'min' => 20, 'max' => 120 ] ],
            'selectors'  => [ '{{WRAPPER}} .oc-hero__content h1' => 'line-height: {{SIZE}}{{UNIT}};' ],
        ] );
        $this->add_responsive_control( 'hero_subheading_margin', [
            'label'      => __( 'Subheading Margin', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'rem' ],
            'selectors'  => [ '{{WRAPPER}} .oc-hero__content p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );
        $this->end_controls_section();
    }

    protected function render() {
        $s         = $this->get_settings_for_display();
        $bg        = ! empty( $s['bg_image']['url'] ) ? $s['bg_image']['url'] : ( defined('OC_IMG_HERO_HOME') ? OC_IMG_HERO_HOME : '' );
        $bg_pos    = ! empty( $s['bg_image_position'] ) ? $s['bg_image_position'] : 'center center';
        $opacity   = isset( $s['overlay_opacity']['size'] ) ? floatval( $s['overlay_opacity']['size'] ) : 0.45;
        $cta_url   = ! empty( $s['cta_url']['url'] ) ? $s['cta_url']['url'] : '/fleet/';
        $sec_url   = ! empty( $s['secondary_url']['url'] ) ? $s['secondary_url']['url'] : '/packages/';
        $search_url = ! empty( $s['search_action_url']['url'] ) ? $s['search_action_url']['url'] : '/fleet/';
        $is_banner = isset( $s['hero_style'] ) && $s['hero_style'] === 'banner';
        $show_search = ( $s['show_search'] ?? '' ) === 'yes' && ! $is_banner;
        ?>
        <style>
        .oc-hero__accent{color:var(--primary,#d9b230)}
        /* ── Hero Search Form ── */
        .oc-hero-search{display:grid;grid-template-columns:1fr auto 1fr auto 1fr auto auto;align-items:stretch;background:rgba(26,34,51,.75);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border:1px solid rgba(217,178,48,.12);border-radius:1rem;overflow:hidden;margin-top:2.75rem;max-width:900px;margin-left:auto;margin-right:auto;width:100%;padding:.5rem;box-shadow:0 8px 32px rgba(0,0,0,.4)}
        .oc-hero-search__field{padding:.5rem 1.25rem;display:flex;flex-direction:column;gap:.35rem;cursor:text;min-width:0}
        .oc-hero-search__label{font-size:.6rem;font-weight:800;letter-spacing:.16em;text-transform:uppercase;color:var(--primary,#d9b230);white-space:nowrap}
        .oc-hero-search__input-wrap{display:flex;align-items:center;gap:.5rem}
        .oc-hero-search__icon{flex-shrink:0;color:rgba(240,236,227,.45);display:flex}
        .oc-hero-search__input{background:none;border:none;outline:none;font-size:.9375rem;color:var(--text,#f0ece3);width:100%;font-family:inherit;padding:0}
        .oc-hero-search__input::placeholder{color:rgba(240,236,227,.5)}
        .oc-hero-search__select{background:none;border:none;outline:none;font-size:.9375rem;color:var(--text,#f0ece3);width:100%;font-family:inherit;cursor:pointer;appearance:none;-webkit-appearance:none;padding:0}
        .oc-hero-search__select option{background:#0a101a;color:#f0ece3}
        .oc-hero-search__chevron{flex-shrink:0;color:rgba(240,236,227,.45);pointer-events:none;display:flex}
        .oc-hero-search__divider{width:1px;background:rgba(255,255,255,.1);align-self:center;height:36px;flex-shrink:0}
        .oc-hero-search__btn{display:flex;align-items:center;gap:.625rem;background:var(--primary,#d9b230);color:#0a0f1a;border:none;padding:.875rem 1.75rem;font-size:.875rem;font-weight:800;letter-spacing:.04em;cursor:pointer;white-space:nowrap;font-family:inherit;flex-shrink:0;transition:opacity .2s;border-radius:12px}
        .oc-hero-search__btn:hover{opacity:.88}
        @media(max-width:768px){.oc-hero-search{grid-template-columns:1fr;max-width:480px;border-radius:.875rem;padding:.75rem}.oc-hero-search__divider{width:100%;height:1px;margin:0}.oc-hero-search__btn{justify-content:center;padding:1rem 1.5rem;border-radius:.75rem}}
        @media(max-width:480px){.oc-hero-search{max-width:100%}}
        </style>
        <?php
        // Render the hero image as a CSS background on the <section> instead of an
        // <img> element. This sidesteps lazy-loading plugins (Smush) that rewrite
        // img attributes, and gives us direct, reliable background-position control.
        $_hero_bg_style = $bg
            ? sprintf(
                "background-image:url('%s');background-size:cover;background-position:%s;background-repeat:no-repeat;",
                esc_url( $bg ),
                esc_attr( $bg_pos )
            )
            : '';
        ?>
        <section class="oc-hero<?php echo $is_banner ? ' oc-hero--banner' : ''; ?>" style="<?php echo $_hero_bg_style; ?>--oc-hero-pos:<?php echo esc_attr( $bg_pos ); ?>;">
          <div class="oc-hero__overlay" style="background:linear-gradient(to bottom,rgba(10,16,26,<?php echo esc_attr( $opacity * 0.55 ); ?>) 0%,rgba(10,16,26,<?php echo esc_attr( $opacity ); ?>) 100%);"></div>
          <div class="oc-hero__content">
            <?php if ( ! empty( $s['eyebrow'] ) ) : ?>
              <span class="oc-hero__eyebrow" data-animate><?php echo esc_html( $s['eyebrow'] ); ?></span>
            <?php endif; ?>
            <h1 data-animate data-delay="0.1"><?php echo esc_html( wp_strip_all_tags( $s['heading'] ) ); ?><?php if ( ! empty( $s['heading_accent'] ) ) : ?> <span class="oc-hero__accent"><?php echo esc_html( $s['heading_accent'] ); ?></span><?php endif; ?></h1>
            <?php if ( ! empty( $s['subheading'] ) ) : ?>
              <p data-animate data-delay="0.2"><?php echo esc_html( $s['subheading'] ); ?></p>
            <?php endif; ?>
            <?php if ( ! $is_banner ) : ?>
              <?php if ( $show_search ) : ?>
                <form class="oc-hero-search" action="<?php echo esc_url( $search_url ); ?>" method="get" data-animate data-delay="0.35">
                  <!-- Destination -->
                  <div class="oc-hero-search__field">
                    <span class="oc-hero-search__label"><?php echo esc_html( $s['search_dest_label'] ?? 'Destination' ); ?></span>
                    <div class="oc-hero-search__input-wrap">
                      <span class="oc-hero-search__icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></span>
                      <input class="oc-hero-search__input" type="text" name="destination" placeholder="<?php echo esc_attr( $s['search_dest_placeholder'] ?? 'Where to?' ); ?>">
                    </div>
                  </div>
                  <div class="oc-hero-search__divider"></div>
                  <!-- Dates -->
                  <div class="oc-hero-search__field">
                    <span class="oc-hero-search__label"><?php echo esc_html( $s['search_dates_label'] ?? 'Dates' ); ?></span>
                    <div class="oc-hero-search__input-wrap">
                      <span class="oc-hero-search__icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></span>
                      <input class="oc-hero-search__input" type="text" name="dates" placeholder="<?php echo esc_attr( $s['search_dates_placeholder'] ?? 'Select dates' ); ?>">
                    </div>
                  </div>
                  <div class="oc-hero-search__divider"></div>
                  <!-- Guests -->
                  <div class="oc-hero-search__field">
                    <span class="oc-hero-search__label"><?php echo esc_html( $s['search_guests_label'] ?? 'Guests' ); ?></span>
                    <div class="oc-hero-search__input-wrap">
                      <span class="oc-hero-search__icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></span>
                      <select class="oc-hero-search__select" name="guests">
                        <option value="1-2">1–2 Guests</option>
                        <option value="2-4" selected>2–4 Guests</option>
                        <option value="5-8">5–8 Guests</option>
                        <option value="9+">9+ Guests</option>
                      </select>
                      <span class="oc-hero-search__chevron"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg></span>
                    </div>
                  </div>
                  <div class="oc-hero-search__divider"></div>
                  <!-- Button -->
                  <button class="oc-hero-search__btn" type="submit">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <?php echo esc_html( $s['search_btn_label'] ?? 'Search Fleet' ); ?>
                  </button>
                </form>
              <?php else : ?>
                <div class="oc-hero__actions" data-animate data-delay="0.35">
                  <?php if ( ! empty( $s['cta_label'] ) ) : ?><a href="<?php echo esc_url( $cta_url ); ?>" class="btn-primary"><?php echo esc_html( $s['cta_label'] ); ?></a><?php endif; ?>
                  <?php if ( ! empty( $s['secondary_label'] ) ) : ?><a href="<?php echo esc_url( $sec_url ); ?>" class="btn-secondary"><?php echo esc_html( $s['secondary_label'] ); ?></a><?php endif; ?>
                </div>
              <?php endif; ?>
            <?php endif; ?>
          </div>
        </section>
        <?php
    }
}

/* ============================================================
   2. OC Stats Bar Widget
   ============================================================ */
class OC_Stats_Bar_Widget extends \Elementor\Widget_Base {
    use OC_Widget_Style_Trait;

    public function get_name()       { return 'oc-stats-bar'; }
    public function get_title()      { return __( 'OC Stats Bar', 'ocean-charter' ); }
    public function get_icon()       { return 'eicon-counter'; }
    public function get_categories() { return [ 'ocean-charter' ]; }

    protected function register_controls() {
        $this->start_controls_section( 'content', [ 'label' => __( 'Stats', 'ocean-charter' ) ] );
        $this->add_control( 'stats', [
            'label'   => __( 'Stats', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::REPEATER,
            'fields'  => [
                [
                    'name'    => 'number',
                    'label'   => __( 'Number', 'ocean-charter' ),
                    'type'    => \Elementor\Controls_Manager::TEXT,
                    'default' => '150',
                ],
                [
                    'name'    => 'suffix',
                    'label'   => __( 'Suffix', 'ocean-charter' ),
                    'type'    => \Elementor\Controls_Manager::TEXT,
                    'default' => '+',
                ],
                [
                    'name'    => 'label',
                    'label'   => __( 'Label', 'ocean-charter' ),
                    'type'    => \Elementor\Controls_Manager::TEXT,
                    'default' => 'Vessels',
                ],
            ],
            'default' => [
                [ 'number' => '150', 'suffix' => '+', 'label' => 'Luxury Vessels' ],
                [ 'number' => '25',  'suffix' => '',  'label' => 'Destinations' ],
                [ 'number' => '12',  'suffix' => '',  'label' => 'Years Experience' ],
                [ 'number' => '4.9', 'suffix' => '★', 'label' => 'Guest Rating' ],
            ],
            'title_field' => '{{{ number }}}{{{ suffix }}} {{{ label }}}',
        ] );
        $this->end_controls_section();

        // ── Style: Stat Number ────────────────────────────────────────────────
        $this->start_controls_section( 'style_stat_number', [
            'label' => __( 'Stat Number', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'stat_number_typography',
            'label'    => __( 'Typography', 'ocean-charter' ),
            'selector' => '{{WRAPPER}} .oc-stat__number',
        ] );
        $this->add_control( 'stat_number_color', [
            'label'     => __( 'Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .oc-stat__number' => 'color: {{VALUE}};' ],
        ] );
        $this->end_controls_section();

        // ── Style: Stat Label ─────────────────────────────────────────────────
        $this->start_controls_section( 'style_stat_label', [
            'label' => __( 'Stat Label', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'stat_label_typography',
            'label'    => __( 'Typography', 'ocean-charter' ),
            'selector' => '{{WRAPPER}} .oc-stat__label',
        ] );
        $this->add_control( 'stat_label_color', [
            'label'     => __( 'Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .oc-stat__label' => 'color: {{VALUE}};' ],
        ] );
        $this->end_controls_section();

        // ── Style: Bar ────────────────────────────────────────────────────────
        $this->start_controls_section( 'style_stats_bar_bg', [
            'label' => __( 'Bar Background & Divider', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_control( 'stats_bar_bg_color', [
            'label'     => __( 'Bar Background Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .oc-stats-bar' => 'background: {{VALUE}};' ],
        ] );
        $this->add_control( 'stats_bar_divider_color', [
            'label'     => __( 'Divider Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .oc-stats-bar' => 'border-top-color: {{VALUE}}; border-bottom-color: {{VALUE}};',
            ],
        ] );
        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        if ( empty( $s['stats'] ) ) return;
        ?>
        <div class="oc-stats-bar">
          <div class="oc-container">
            <div class="oc-stats-bar__grid">
              <?php foreach ( $s['stats'] as $stat ) : ?>
                <div class="oc-stat" data-animate>
                  <span class="oc-stat__number" data-target="<?php echo esc_attr( $stat['number'] ); ?>" data-suffix="<?php echo esc_attr( $stat['suffix'] ); ?>">
                    <?php echo esc_html( $stat['number'] . $stat['suffix'] ); ?>
                  </span>
                  <span class="oc-stat__label"><?php echo esc_html( $stat['label'] ); ?></span>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
        <style>
        .oc-stats-bar { background: var(--surface); border-top: 1px solid var(--border); border-bottom: 1px solid var(--border); padding: 2.5rem 0; }
        .oc-stats-bar__grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 2rem; text-align: center; }
        .oc-stat__number { display: block; font-family: var(--font-heading); font-size: clamp(2.4rem, 4vw, 3.5rem); color: var(--primary); line-height: 1; margin-bottom: 0.5rem; }
        .oc-stat__label  { display: block; font-size: 0.8125rem; font-weight: 500; letter-spacing: 0.1em; text-transform: uppercase; color: var(--text-muted); }
        @media (max-width: 600px) { .oc-stats-bar__grid { grid-template-columns: 1fr 1fr; } }\n        @media (max-width: 380px) { .oc-stats-bar__grid { grid-template-columns: 1fr; } }
        </style>
        <?php
    }
}

/* ============================================================
   3. OC Destination Card Widget
   ============================================================ */
class OC_Destination_Card_Widget extends \Elementor\Widget_Base {
    use OC_Widget_Style_Trait;

    public function get_name()       { return 'oc-destination-card'; }
    public function get_title()      { return __( 'OC Destination Card', 'ocean-charter' ); }
    public function get_icon()       { return 'eicon-google-maps'; }
    public function get_categories() { return [ 'ocean-charter' ]; }

    protected function register_controls() {
        $this->start_controls_section( 'content', [ 'label' => __( 'Content', 'ocean-charter' ) ] );
        $this->add_control( 'image',        [ 'label' => __( 'Image', 'ocean-charter' ),        'type' => \Elementor\Controls_Manager::MEDIA ] );
        $this->add_control( 'region_name',  [ 'label' => __( 'Region Name', 'ocean-charter' ),  'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Mediterranean' ] );
        $this->add_control( 'countries',    [ 'label' => __( 'Countries', 'ocean-charter' ),     'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Greece · Italy · Croatia' ] );
        $this->add_control( 'vessel_count', [ 'label' => __( 'Vessel Count', 'ocean-charter' ),  'type' => \Elementor\Controls_Manager::TEXT, 'default' => '48 yachts' ] );
        $this->add_control( 'link',         [ 'label' => __( 'Link URL', 'ocean-charter' ),      'type' => \Elementor\Controls_Manager::URL, 'default' => [ 'url' => '/fleet/' ] ] );
        $this->end_controls_section();

        // ── Style: Title ──────────────────────────────────────────────────────
        $this->start_controls_section( 'style_dest_title', [
            'label' => __( 'Title', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'dest_title_typography',
            'label'    => __( 'Typography', 'ocean-charter' ),
            'selector' => '{{WRAPPER}} .oc-dest-card__body h3',
        ] );
        $this->add_control( 'dest_title_color', [
            'label'     => __( 'Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .oc-dest-card__body h3' => 'color: {{VALUE}};' ],
        ] );
        $this->end_controls_section();

        // ── Style: Description ────────────────────────────────────────────────
        $this->start_controls_section( 'style_dest_desc', [
            'label' => __( 'Description', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'dest_desc_typography',
            'label'    => __( 'Typography', 'ocean-charter' ),
            'selector' => '{{WRAPPER}} .oc-dest-card__body p',
        ] );
        $this->add_control( 'dest_desc_color', [
            'label'     => __( 'Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .oc-dest-card__body p' => 'color: {{VALUE}};' ],
        ] );
        $this->end_controls_section();

        // ── Style: Card ───────────────────────────────────────────────────────
        $this->oc_register_card_style( '.oc-dest-card', 'Card' );
    }

    protected function render() {
        $s   = $this->get_settings_for_display();
        $img = ! empty( $s['image']['url'] ) ? $s['image']['url'] : ( defined('OC_IMG_DEST_MEDITERRANEAN') ? OC_IMG_DEST_MEDITERRANEAN : '' );
        $url = ! empty( $s['link']['url'] ) ? $s['link']['url'] : '#';
        ?>
        <a href="<?php echo esc_url( $url ); ?>" class="oc-dest-card" data-animate>
          <?php if ( $img ) : ?>
            <img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $s['region_name'] ); ?>" loading="lazy">
          <?php endif; ?>
          <div class="oc-dest-card__overlay">
            <div class="oc-dest-card__body">
              <div class="oc-dest-card__badge"><?php echo esc_html( $s['vessel_count'] ); ?></div>
              <h3><?php echo esc_html( $s['region_name'] ); ?></h3>
              <p><?php echo esc_html( $s['countries'] ); ?></p>
            </div>
          </div>
        </a>
        <style>
        .oc-dest-card { display:block; position:relative; border-radius:var(--radius-lg); overflow:hidden; aspect-ratio:3/4; text-decoration:none; }
        .oc-dest-card img { width:100%; height:100%; object-fit:cover; transition:transform 0.5s ease; }
        .oc-dest-card:hover img { transform:scale(1.06); }
        .oc-dest-card__overlay { position:absolute; inset:0; background:linear-gradient(to top,rgba(10,16,26,0.85) 0%,transparent 50%); display:flex; align-items:flex-end; padding:1.5rem; }
        .oc-dest-card__body { color:white; }
        .oc-dest-card__badge { font-size:0.75rem; font-weight:600; letter-spacing:0.1em; text-transform:uppercase; color:var(--primary); margin-bottom:0.375rem; }
        .oc-dest-card__body h3 { font-size:1.375rem; color:white; margin-bottom:0.25rem; }
        .oc-dest-card__body p { font-size:0.875rem; color:rgba(240,236,227,0.7); margin:0; }
        .oc-dest-card:hover { box-shadow:var(--shadow-glow); }
        @media(max-width:768px){
          .oc-dest-card{aspect-ratio:3/3;}
          .oc-dest-card__overlay{padding:1.25rem;}
          .oc-dest-card__body h3{font-size:1.15rem;}
        }
        @media(max-width:480px){
          .oc-dest-card{aspect-ratio:4/3;}
          .oc-dest-card__overlay{padding:1rem;}
          .oc-dest-card__body h3{font-size:1rem;}
          .oc-dest-card__body p{font-size:0.8125rem;}
          .oc-dest-card__badge{font-size:0.6875rem;}
        }
        </style>
        <?php
    }
}

/* ============================================================
   4. OC Package Card Widget
   ============================================================ */
class OC_Package_Card_Widget extends \Elementor\Widget_Base {
    use OC_Widget_Style_Trait;

    public function get_name()       { return 'oc-package-card'; }
    public function get_title()      { return __( 'OC Package Card', 'ocean-charter' ); }
    public function get_icon()       { return 'eicon-price-table'; }
    public function get_categories() { return [ 'ocean-charter' ]; }

    protected function register_controls() {
        $this->start_controls_section( 'content', [ 'label' => __( 'Content', 'ocean-charter' ) ] );
        $this->add_control( 'image',      [ 'label' => __( 'Image', 'ocean-charter' ),        'type' => \Elementor\Controls_Manager::MEDIA ] );
        $this->add_control( 'tag',        [ 'label' => __( 'Tag', 'ocean-charter' ),           'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Popular' ] );
        $this->add_control( 'title',      [ 'label' => __( 'Title', 'ocean-charter' ),         'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Mediterranean Escape' ] );
        $this->add_control( 'price',      [ 'label' => __( 'Price', 'ocean-charter' ),         'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'From $8,500' ] );
        $this->add_control( 'duration',   [ 'label' => __( 'Duration', 'ocean-charter' ),      'type' => \Elementor\Controls_Manager::TEXT, 'default' => '7 Days' ] );
        $this->add_control( 'inclusions', [
            'label'   => __( 'Inclusions', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::REPEATER,
            'fields'  => [ [ 'name' => 'text', 'label' => 'Item', 'type' => \Elementor\Controls_Manager::TEXT ] ],
            'default' => [ [ 'text' => 'Crewed yacht' ], [ 'text' => 'All meals included' ], [ 'text' => 'Port fees & fuel' ] ],
            'title_field' => '{{{ text }}}',
        ] );
        $this->add_control( 'cta_url', [ 'label' => __( 'CTA URL', 'ocean-charter' ), 'type' => \Elementor\Controls_Manager::URL, 'default' => [ 'url' => '/packages/' ] ] );
        $this->end_controls_section();

        // ── Style: Title ──────────────────────────────────────────────────────
        $this->start_controls_section( 'style_pkg_title', [
            'label' => __( 'Title', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'pkg_title_typography',
            'label'    => __( 'Typography', 'ocean-charter' ),
            'selector' => '{{WRAPPER}} .oc-pkg-card__body h3',
        ] );
        $this->add_control( 'pkg_title_color', [
            'label'     => __( 'Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .oc-pkg-card__body h3' => 'color: {{VALUE}};' ],
        ] );
        $this->end_controls_section();

        // ── Style: Body Text ──────────────────────────────────────────────────
        $this->start_controls_section( 'style_pkg_body', [
            'label' => __( 'Body Text', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'pkg_body_typography',
            'label'    => __( 'Typography', 'ocean-charter' ),
            'selector' => '{{WRAPPER}} .oc-pkg-card__inclusions li',
        ] );
        $this->add_control( 'pkg_body_color', [
            'label'     => __( 'Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .oc-pkg-card__inclusions li' => 'color: {{VALUE}};' ],
        ] );
        $this->end_controls_section();

        // ── Style: Button ─────────────────────────────────────────────────────
        $this->oc_register_button_style( '.oc-pkg-card .btn-secondary' );
    }

    protected function render() {
        $s   = $this->get_settings_for_display();
        $img = ! empty( $s['image']['url'] ) ? $s['image']['url'] : ( defined('OC_IMG_HERO_PACKAGES') ? OC_IMG_HERO_PACKAGES : '' );
        $url = ! empty( $s['cta_url']['url'] ) ? $s['cta_url']['url'] : '/packages/';
        ?>
        <div class="oc-pkg-card oc-card" data-animate>
          <div class="oc-pkg-card__img-wrap">
            <?php if ( $img ) : ?><img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($s['title']); ?>" class="oc-card__img" loading="lazy"><?php endif; ?>
            <?php if ( $s['tag'] ) : ?><span class="oc-pkg-card__tag"><?php echo esc_html($s['tag']); ?></span><?php endif; ?>
          </div>
          <div class="oc-pkg-card__body">
            <div class="oc-pkg-card__meta">
              <span class="oc-caption"><?php echo esc_html($s['duration']); ?></span>
              <span class="oc-pkg-card__price"><?php echo esc_html($s['price']); ?></span>
            </div>
            <h3><?php echo esc_html($s['title']); ?></h3>
            <?php if ( ! empty($s['inclusions']) ) : ?>
              <ul class="oc-pkg-card__inclusions">
                <?php foreach ( array_slice($s['inclusions'], 0, 3) as $item ) : ?>
                  <li><?php echo esc_html($item['text']); ?></li>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>
            <a href="<?php echo esc_url($url); ?>" class="btn-secondary oc-pkg-card__cta">View Details</a>
          </div>
        </div>
        <style>
        .oc-pkg-card__img-wrap { position:relative; height:256px; overflow:hidden; }
        .oc-pkg-card__img-wrap img { width:100%; height:100%; object-fit:cover; transition:transform .6s ease; }
        .oc-pkg-card:hover .oc-pkg-card__img-wrap img { transform:scale(1.04); }
        .oc-pkg-card__img-ph { width:100%; height:100%; background:linear-gradient(135deg,#0d1f35 0%,#1a2d45 100%); }
        .oc-pkg-card__tag { position:absolute; top:1rem; left:1rem; background:var(--primary); color:#000; font-size:0.75rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; padding:0.25rem 0.75rem; border-radius:9999px; }
        .oc-pkg-card__body { padding:1.5rem; }
        .oc-pkg-card__meta { display:flex; justify-content:space-between; align-items:baseline; margin-bottom:0.75rem; padding-bottom:0.75rem; border-bottom:1px solid rgba(255,255,255,0.07); }
        .oc-pkg-card__price { font-family:var(--font-heading); font-size:1.5rem; color:var(--primary); }
        .oc-pkg-card__duration { font-size:0.8rem; color:var(--text-muted); letter-spacing:0.05em; }
        .oc-pkg-card__body h3 { font-family:var(--font-heading); font-size:1.25rem; color:#f8fafc; margin:0.5rem 0 0.75rem; font-weight:400; }
        .oc-pkg-card__inclusions { list-style:none; padding:0; margin:0 0 1.25rem; }
        .oc-pkg-card__inclusions li { font-size:0.875rem; color:var(--text-muted); padding:0.375rem 0; border-bottom:1px solid rgba(255,255,255,0.05); display:flex; align-items:center; gap:0.5rem; }
        .oc-pkg-card__inclusions li::before { content:'✓'; color:var(--primary); font-weight:700; flex-shrink:0; }
        .oc-pkg-card__cta { width:100%; justify-content:center; margin-top:1.25rem; }
        @media(max-width:768px){
          .oc-pkg-card__img-wrap{height:200px;}
          .oc-pkg-card__body{padding:1.25rem;}
          .oc-pkg-card__price{font-size:1.25rem;}
          .oc-pkg-card__body h3{font-size:1.1rem;}
        }
        @media(max-width:480px){
          .oc-pkg-card__img-wrap{height:180px;}
          .oc-pkg-card__body{padding:1rem;}
          .oc-pkg-card__meta{flex-direction:column;gap:0.25rem;}
          .oc-pkg-card__inclusions li{font-size:0.8125rem;padding:0.25rem 0;}
        }
        </style>
        <?php
    }
}

/* ============================================================
   5. OC Testimonial Widget
   ============================================================ */
class OC_Testimonial_Widget extends \Elementor\Widget_Base {
    use OC_Widget_Style_Trait;

    public function get_name()       { return 'oc-testimonial'; }
    public function get_title()      { return __( 'OC Testimonial', 'ocean-charter' ); }
    public function get_icon()       { return 'eicon-testimonial'; }
    public function get_categories() { return [ 'ocean-charter' ]; }

    protected function register_controls() {
        $this->start_controls_section( 'content', [ 'label' => __( 'Testimonial', 'ocean-charter' ) ] );
        $this->add_control( 'quote',  [ 'label' => __( 'Quote', 'ocean-charter' ),       'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'An extraordinary experience. Every detail was flawlessly executed. We\'ll be booking again next summer.' ] );
        $this->add_control( 'author', [ 'label' => __( 'Author Name', 'ocean-charter' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'James & Sarah Whitfield' ] );
        $this->add_control( 'role',   [ 'label' => __( 'Role / Location', 'ocean-charter' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Greek Isles Charter, 2024' ] );
        $this->add_control( 'avatar', [ 'label' => __( 'Avatar Image', 'ocean-charter' ), 'type' => \Elementor\Controls_Manager::MEDIA ] );
        $this->end_controls_section();

        // ── Style: Quote Text ─────────────────────────────────────────────────
        $this->start_controls_section( 'style_testimonial_quote', [
            'label' => __( 'Quote Text', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'testimonial_quote_typography',
            'label'    => __( 'Typography', 'ocean-charter' ),
            'selector' => '{{WRAPPER}} .oc-testimonial__quote',
        ] );
        $this->add_control( 'testimonial_quote_color', [
            'label'     => __( 'Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .oc-testimonial__quote' => 'color: {{VALUE}};' ],
        ] );
        $this->end_controls_section();

        // ── Style: Author Name ────────────────────────────────────────────────
        $this->start_controls_section( 'style_testimonial_author', [
            'label' => __( 'Author Name', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'testimonial_author_typography',
            'label'    => __( 'Typography', 'ocean-charter' ),
            'selector' => '{{WRAPPER}} .oc-testimonial__author strong',
        ] );
        $this->add_control( 'testimonial_author_color', [
            'label'     => __( 'Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .oc-testimonial__author strong' => 'color: {{VALUE}};' ],
        ] );
        $this->end_controls_section();

        // ── Style: Stars / Accent ─────────────────────────────────────────────
        $this->oc_register_accent_style(
            [ '{{WRAPPER}} .oc-testimonial__quote-mark' => 'color: {{VALUE}};' ],
            'Stars / Accent Color'
        );
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        ?>
        <div class="oc-testimonial" data-animate>
          <div class="oc-testimonial__quote-mark">"</div>
          <blockquote class="oc-testimonial__quote"><?php echo esc_html( $s['quote'] ); ?></blockquote>
          <div class="oc-testimonial__author">
            <?php if ( ! empty($s['avatar']['url']) ) : ?>
              <img src="<?php echo esc_url($s['avatar']['url']); ?>" alt="<?php echo esc_attr($s['author']); ?>" class="oc-testimonial__avatar" loading="lazy">
            <?php endif; ?>
            <div>
              <strong><?php echo esc_html( $s['author'] ); ?></strong>
              <span class="oc-caption"><?php echo esc_html( $s['role'] ); ?></span>
            </div>
          </div>
        </div>
        <style>
        .oc-testimonial { background:var(--surface); border:1px solid var(--border); border-radius:var(--radius-lg); padding:2rem; position:relative; }
        .oc-testimonial__quote-mark { font-family:var(--font-heading); font-size:5rem; line-height:1; color:var(--primary); opacity:0.3; position:absolute; top:1rem; left:1.5rem; }
        .oc-testimonial__quote { font-size:1.0625rem; line-height:1.8; color:var(--text); font-style:italic; margin:1.5rem 0 1.5rem; padding-left:0; border:none; }
        .oc-testimonial__author { display:flex; align-items:center; gap:1rem; }
        .oc-testimonial__avatar { width:48px; height:48px; border-radius:50%; object-fit:cover; aspect-ratio:1/1; flex-shrink:0; }
        .oc-testimonial__author strong { display:block; font-family:var(--font-heading); font-size:1rem; margin-bottom:0.25rem; }
        @media(max-width:768px){
          .oc-testimonial{padding:1.5rem;}
          .oc-testimonial__quote-mark{font-size:3.5rem;top:0.75rem;left:1rem;}
          .oc-testimonial__quote{font-size:1rem;margin:1rem 0 1.25rem;}
        }
        @media(max-width:480px){
          .oc-testimonial{padding:1.25rem;}
          .oc-testimonial__quote-mark{font-size:2.5rem;}
          .oc-testimonial__quote{font-size:0.9375rem;line-height:1.7;}
          .oc-testimonial__avatar{width:40px;height:40px;}
          .oc-testimonial__author strong{font-size:0.9rem;}
        }
        </style>
        <?php
    }
}

/* ============================================================
   6. OC CTA Strip Widget
   ============================================================ */
class OC_CTA_Strip_Widget extends \Elementor\Widget_Base {
    use OC_Widget_Style_Trait;

    public function get_name()       { return 'oc-cta-strip'; }
    public function get_title()      { return __( 'OC CTA Strip', 'ocean-charter' ); }
    public function get_icon()       { return 'eicon-call-to-action'; }
    public function get_categories() { return [ 'ocean-charter' ]; }

    protected function register_controls() {
        $this->start_controls_section( 'content', [ 'label' => __( 'Content', 'ocean-charter' ) ] );
        $this->add_control( 'heading',         [ 'label' => __( 'Heading', 'ocean-charter' ),            'type' => \Elementor\Controls_Manager::TEXT,     'default' => 'Ready to Set Sail?' ] );
        $this->add_control( 'subtext',         [ 'label' => __( 'Subtext', 'ocean-charter' ),            'type' => \Elementor\Controls_Manager::TEXTAREA,  'default' => 'Your bespoke charter experience awaits. Let\'s plan your perfect voyage.' ] );
        $this->add_control( 'primary_label',   [ 'label' => __( 'Primary CTA Label', 'ocean-charter' ),  'type' => \Elementor\Controls_Manager::TEXT,     'default' => 'Book Now' ] );
        $this->add_control( 'primary_url',     [ 'label' => __( 'Primary CTA URL', 'ocean-charter' ),    'type' => \Elementor\Controls_Manager::URL,      'default' => [ 'url' => '/contact/' ] ] );
        $this->add_control( 'secondary_label', [ 'label' => __( 'Secondary CTA Label', 'ocean-charter' ),'type' => \Elementor\Controls_Manager::TEXT,     'default' => 'WhatsApp Us' ] );
        $this->end_controls_section();

        // ── Style: Heading ────────────────────────────────────────────────────
        $this->start_controls_section( 'style_cta_heading', [
            'label' => __( 'Heading', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'cta_heading_typography',
            'label'    => __( 'Typography', 'ocean-charter' ),
            'selector' => '{{WRAPPER}} .oc-cta-strip__heading',
        ] );
        $this->add_control( 'cta_heading_color', [
            'label'     => __( 'Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .oc-cta-strip__heading' => 'color: {{VALUE}};' ],
        ] );
        $this->end_controls_section();

        // ── Style: Sub Text ───────────────────────────────────────────────────
        $this->start_controls_section( 'style_cta_sub', [
            'label' => __( 'Sub Text', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'cta_sub_typography',
            'label'    => __( 'Typography', 'ocean-charter' ),
            'selector' => '{{WRAPPER}} .oc-cta-strip__sub',
        ] );
        $this->add_control( 'cta_sub_color', [
            'label'     => __( 'Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .oc-cta-strip__sub' => 'color: {{VALUE}};' ],
        ] );
        $this->end_controls_section();

        // ── Style: Primary Button ─────────────────────────────────────────────
        $this->start_controls_section( 'style_cta_btn_primary', [
            'label' => __( 'Primary Button', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'cta_btn_primary_typography',
            'label'    => __( 'Typography', 'ocean-charter' ),
            'selector' => '{{WRAPPER}} .oc-cta-strip__btn--primary',
        ] );
        $this->add_control( 'cta_btn_primary_bg', [
            'label'     => __( 'Background', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .oc-cta-strip__btn--primary' => 'background: {{VALUE}};' ],
        ] );
        $this->add_control( 'cta_btn_primary_color', [
            'label'     => __( 'Text Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .oc-cta-strip__btn--primary' => 'color: {{VALUE}};' ],
        ] );
        $this->add_control( 'cta_btn_primary_radius', [
            'label'      => __( 'Border Radius', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'rem' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 9999 ] ],
            'selectors'  => [ '{{WRAPPER}} .oc-cta-strip__btn--primary' => 'border-radius: {{SIZE}}{{UNIT}};' ],
        ] );
        $this->add_responsive_control( 'cta_btn_primary_padding', [
            'label'      => __( 'Padding', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em', 'rem' ],
            'selectors'  => [ '{{WRAPPER}} .oc-cta-strip__btn--primary' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );
        $this->end_controls_section();

        // ── Style: Secondary Button ───────────────────────────────────────────
        $this->start_controls_section( 'style_cta_btn_secondary', [
            'label' => __( 'Secondary Button', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'cta_btn_secondary_typography',
            'label'    => __( 'Typography', 'ocean-charter' ),
            'selector' => '{{WRAPPER}} .oc-cta-strip__btn--secondary',
        ] );
        $this->add_control( 'cta_btn_secondary_border', [
            'label'     => __( 'Border Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .oc-cta-strip__btn--secondary' => 'border-color: {{VALUE}};' ],
        ] );
        $this->add_control( 'cta_btn_secondary_color', [
            'label'     => __( 'Text Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .oc-cta-strip__btn--secondary' => 'color: {{VALUE}};' ],
        ] );
        $this->add_control( 'cta_btn_secondary_radius', [
            'label'      => __( 'Border Radius', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'rem' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 9999 ] ],
            'selectors'  => [ '{{WRAPPER}} .oc-cta-strip__btn--secondary' => 'border-radius: {{SIZE}}{{UNIT}};' ],
        ] );
        $this->add_responsive_control( 'cta_btn_secondary_padding', [
            'label'      => __( 'Padding', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em', 'rem' ],
            'selectors'  => [ '{{WRAPPER}} .oc-cta-strip__btn--secondary' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );
        $this->end_controls_section();

        // ── Style: Section Background ─────────────────────────────────────────
        $this->start_controls_section( 'style_cta_bg', [
            'label' => __( 'Section Background', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_control( 'cta_bg_color', [
            'label'     => __( 'Background Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .oc-cta-strip' => 'background: {{VALUE}};' ],
        ] );
        $this->end_controls_section();
    }

    protected function render() {
        $s        = $this->get_settings_for_display();
        $prim_url = ! empty($s['primary_url']['url']) ? $s['primary_url']['url'] : '/contact/';
        $wa_url   = function_exists('oc_whatsapp_url') ? oc_whatsapp_url( 'Hello, I\'d like to book a charter.' ) : '#';
        ?>
        <div class="oc-cta-strip">
            <div class="oc-cta-strip__inner">
                <span class="oc-cta-strip__eyebrow">&#9875; Set Sail Today</span>
                <h2 class="oc-cta-strip__heading"><?php echo esc_html($s['heading']); ?></h2>
                <p class="oc-cta-strip__sub"><?php echo esc_html($s['subtext']); ?></p>
                <div class="oc-cta-strip__actions">
                    <a href="<?php echo esc_url($prim_url); ?>" class="oc-cta-strip__btn oc-cta-strip__btn--primary"><?php echo esc_html($s['primary_label']); ?></a>
                    <a href="<?php echo esc_url($wa_url); ?>" class="oc-cta-strip__btn oc-cta-strip__btn--secondary" target="_blank" rel="noopener"><?php echo esc_html($s['secondary_label']); ?></a>
                </div>
            </div>
        </div>
        <style>
        .oc-cta-strip { width:100%; text-align:center; }
        .oc-cta-strip__inner { display:flex; flex-direction:column; align-items:center; gap:1.25rem; }
        .oc-cta-strip__eyebrow { font-size:0.8rem; font-weight:700; letter-spacing:0.18em; text-transform:uppercase; color:var(--primary,#d9b230); }
        .oc-cta-strip__heading { font-family:var(--font-heading,'Playfair Display',serif); font-size:clamp(2.25rem,4vw,3.75rem); color:var(--text,#f0ece3); font-weight:400; font-style:italic; margin:0; line-height:1.2; max-width:800px; }
        .oc-cta-strip__sub { font-size:1.0625rem; color:var(--text-muted,rgba(148,163,184,1)); line-height:1.7; max-width:560px; margin:0; }
        .oc-cta-strip__actions { display:flex; gap:1rem; flex-wrap:wrap; justify-content:center; margin-top:0.5rem; }
        .oc-cta-strip__btn { display:inline-block; padding:0.9rem 2.25rem; border-radius:9999px; font-size:0.9375rem; font-weight:700; text-decoration:none; letter-spacing:0.04em; transition:background 0.2s,color 0.2s,transform 0.2s; }
        .oc-cta-strip__btn:hover { transform:translateY(-2px); }
        .oc-cta-strip__btn--primary { background:var(--primary,#d9b230); color:#0a0f1a; }
        .oc-cta-strip__btn--primary:hover { background:#c9a420; }
        .oc-cta-strip__btn--secondary { border:2px solid rgba(217,178,48,0.4); color:var(--primary,#d9b230); }
        .oc-cta-strip__btn--secondary:hover { border-color:var(--primary,#d9b230); background:rgba(217,178,48,0.1); }
        @media(max-width:768px){
          .oc-cta-strip__sub{max-width:100%;}
          .oc-cta-strip__btn{padding:0.75rem 1.75rem;font-size:0.875rem;}
        }
        @media(max-width:480px){
          .oc-cta-strip__actions{flex-direction:column;width:100%;max-width:320px;}
          .oc-cta-strip__btn{text-align:center;padding:0.875rem 1.5rem;}
          .oc-cta-strip__sub{font-size:0.9375rem;}
        }
        </style>
        <?php
    }
}

/* ============================================================
   7. OC Itinerary Day Widget
   ============================================================ */
class OC_Itinerary_Day_Widget extends \Elementor\Widget_Base {
    use OC_Widget_Style_Trait;

    public function get_name()       { return 'oc-itinerary-day'; }
    public function get_title()      { return __( 'OC Itinerary Day', 'ocean-charter' ); }
    public function get_icon()       { return 'eicon-post-list'; }
    public function get_categories() { return [ 'ocean-charter' ]; }

    protected function register_controls() {
        $this->start_controls_section( 'content', [ 'label' => __( 'Day Content', 'ocean-charter' ) ] );
        $this->add_control( 'day_number',   [ 'label' => __( 'Day Number', 'ocean-charter' ),   'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Day 1' ] );
        $this->add_control( 'location',     [ 'label' => __( 'Location', 'ocean-charter' ),     'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Departure — Athens' ] );
        $this->add_control( 'description',  [ 'label' => __( 'Description', 'ocean-charter' ),  'type' => \Elementor\Controls_Manager::WYSIWYG, 'default' => 'Board your vessel at Piraeus Marina and sail into the Saronic Gulf as the sun begins its descent.' ] );
        $this->add_control( 'activities',   [
            'label'   => __( 'Activities', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::REPEATER,
            'fields'  => [ [ 'name' => 'label', 'label' => 'Activity', 'type' => \Elementor\Controls_Manager::TEXT ] ],
            'default' => [ [ 'label' => 'Sunset cocktails on deck' ], [ 'label' => 'Welcome dinner aboard' ] ],
            'title_field' => '{{{ label }}}',
        ] );
        $this->add_control( 'image_a', [ 'label' => __( 'Image — Morning', 'ocean-charter' ), 'type' => \Elementor\Controls_Manager::MEDIA ] );
        $this->add_control( 'image_b', [ 'label' => __( 'Image — Afternoon', 'ocean-charter' ), 'type' => \Elementor\Controls_Manager::MEDIA ] );
        $this->end_controls_section();

        // ── Style: Day Number ─────────────────────────────────────────────────
        $this->start_controls_section( 'style_itin_day_num', [
            'label' => __( 'Day Number', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'itin_day_num_typography',
            'label'    => __( 'Typography', 'ocean-charter' ),
            'selector' => '{{WRAPPER}} .oc-itin-day__circle',
        ] );
        $this->add_control( 'itin_day_num_color', [
            'label'     => __( 'Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .oc-itin-day__circle' => 'color: {{VALUE}}; border-color: {{VALUE}};',
            ],
        ] );
        $this->end_controls_section();

        // ── Style: Title ──────────────────────────────────────────────────────
        $this->start_controls_section( 'style_itin_title', [
            'label' => __( 'Location Title', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'itin_title_typography',
            'label'    => __( 'Typography', 'ocean-charter' ),
            'selector' => '{{WRAPPER}} .oc-itin-day__content h3',
        ] );
        $this->add_control( 'itin_title_color', [
            'label'     => __( 'Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .oc-itin-day__content h3' => 'color: {{VALUE}};' ],
        ] );
        $this->end_controls_section();

        // ── Style: Body Text ──────────────────────────────────────────────────
        $this->start_controls_section( 'style_itin_body', [
            'label' => __( 'Body Text', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'itin_body_typography',
            'label'    => __( 'Typography', 'ocean-charter' ),
            'selector' => '{{WRAPPER}} .oc-itin-day__desc',
        ] );
        $this->add_control( 'itin_body_color', [
            'label'     => __( 'Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .oc-itin-day__desc' => 'color: {{VALUE}};' ],
        ] );
        $this->end_controls_section();
    }

    protected function render() {
        $s     = $this->get_settings_for_display();
        $img_a = ! empty( $s['image_a']['url'] ) ? $s['image_a']['url'] : '';
        $img_b = ! empty( $s['image_b']['url'] ) ? $s['image_b']['url'] : '';
        ?>
        <div class="oc-itin-day" data-animate>
          <div class="oc-itin-day__marker">
            <div class="oc-itin-day__circle"><?php echo esc_html($s['day_number']); ?></div>
            <div class="oc-itin-day__line" aria-hidden="true"></div>
          </div>
          <div class="oc-itin-day__content">
            <h3><?php echo esc_html($s['location']); ?></h3>
            <div class="oc-itin-day__desc"><?php echo wp_kses_post($s['description']); ?></div>
            <?php if ( ! empty($s['activities']) ) : ?>
              <ul class="oc-itin-day__activities">
                <?php foreach ( $s['activities'] as $act ) : ?>
                  <li><?php echo esc_html($act['label']); ?></li>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>
            <?php if ( $img_a || $img_b ) : ?>
              <div class="oc-itin-day__imgs">
                <?php if ( $img_a ) : ?><img src="<?php echo esc_url($img_a); ?>" alt="<?php echo esc_attr($s['location']); ?> — morning" loading="lazy"><?php endif; ?>
                <?php if ( $img_b ) : ?><img src="<?php echo esc_url($img_b); ?>" alt="<?php echo esc_attr($s['location']); ?> — afternoon" loading="lazy"><?php endif; ?>
              </div>
            <?php endif; ?>
          </div>
        </div>
        <style>
        .oc-itin-day{display:grid;grid-template-columns:80px 1fr;gap:1.5rem;margin-bottom:3rem;}
        .oc-itin-day__marker{display:flex;flex-direction:column;align-items:center;}
        .oc-itin-day__circle{width:52px;height:52px;border-radius:50%;border:2px solid var(--primary);display:flex;align-items:center;justify-content:center;font-size:0.6875rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;color:var(--primary);background:var(--surface);text-align:center;flex-shrink:0;}
        .oc-itin-day__line{flex:1;width:2px;background:var(--border);margin-top:0.75rem;}
        .oc-itin-day__content h3{font-size:1.5rem;margin-bottom:0.75rem;}
        .oc-itin-day__desc{color:var(--text-muted);margin-bottom:1rem;}
        .oc-itin-day__activities{list-style:none;padding:0;margin:0 0 1.25rem;display:flex;flex-wrap:wrap;gap:0.5rem;}
        .oc-itin-day__activities li{font-size:0.8125rem;background:var(--surface-2);border:1px solid var(--border);border-radius:var(--radius-pill);padding:0.3rem 0.875rem;color:var(--text-muted);}
        .oc-itin-day__imgs{display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;margin-top:1.25rem;}
        .oc-itin-day__imgs img{width:100%;aspect-ratio:16/9;object-fit:cover;border-radius:var(--radius);}
        @media(max-width:540px){.oc-itin-day{grid-template-columns:60px 1fr;gap:1rem;}.oc-itin-day__imgs{grid-template-columns:1fr;}.oc-itin-day__content h3{font-size:1.25rem;}}
        </style>
        <?php
    }
}

/* ============================================================
   8. OC Service Card Widget
   ============================================================ */
class OC_Service_Card_Widget extends \Elementor\Widget_Base {
    use OC_Widget_Style_Trait;

    public function get_name()       { return 'oc-service-card'; }
    public function get_title()      { return __( 'OC Service Card', 'ocean-charter' ); }
    public function get_icon()       { return 'eicon-image-box'; }
    public function get_categories() { return [ 'ocean-charter' ]; }

    protected function register_controls() {
        $this->start_controls_section( 'content', [ 'label' => __( 'Content', 'ocean-charter' ) ] );

        $this->add_control( 'image', [
            'label' => __( 'Card Image', 'ocean-charter' ),
            'type'  => \Elementor\Controls_Manager::MEDIA,
        ] );
        $this->add_control( 'eyebrow', [
            'label'   => __( 'Badge Label', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'Culinary Excellence', 'ocean-charter' ),
        ] );
        $this->add_control( 'badge_icon', [
            'label'   => __( 'Badge Icon', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => 'chef',
            'options' => [
                'chef'      => __( 'Chef Hat', 'ocean-charter' ),
                'water'     => __( 'Waves', 'ocean-charter' ),
                'events'    => __( 'Events', 'ocean-charter' ),
                'concierge' => __( 'Concierge', 'ocean-charter' ),
            ],
        ] );
        $this->add_control( 'title', [
            'label'   => __( 'Title', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'Private Michelin Chefs', 'ocean-charter' ),
        ] );
        $this->add_control( 'description', [
            'label'   => __( 'Description', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::TEXTAREA,
            'default' => __( 'Savor gourmet menus tailored to your palate by world-renowned chefs.', 'ocean-charter' ),
        ] );
        $this->add_control( 'features', [
            'label'       => __( 'Feature Tags', 'ocean-charter' ),
            'type'        => \Elementor\Controls_Manager::REPEATER,
            'fields'      => [
                [
                    'name'    => 'label',
                    'label'   => __( 'Tag', 'ocean-charter' ),
                    'type'    => \Elementor\Controls_Manager::TEXT,
                    'default' => 'Custom Menus',
                ],
            ],
            'default'     => [
                [ 'label' => 'Custom Menus' ],
                [ 'label' => 'Wine Pairing' ],
                [ 'label' => 'Local Sourcing' ],
            ],
            'title_field' => '{{{ label }}}',
        ] );
        $this->add_control( 'link', [
            'label'   => __( 'Link URL', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::URL,
            'default' => [ 'url' => '/contact/' ],
        ] );

        $this->end_controls_section();

        // ── Style: Title ──────────────────────────────────────────────────────
        $this->start_controls_section( 'style_svc_title', [
            'label' => __( 'Title', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'svc_title_typography',
            'label'    => __( 'Typography', 'ocean-charter' ),
            'selector' => '{{WRAPPER}} .oc-svc-card__title',
        ] );
        $this->add_control( 'svc_title_color', [
            'label'     => __( 'Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .oc-svc-card__title' => 'color: {{VALUE}};' ],
        ] );
        $this->end_controls_section();

        // ── Style: Body Text ──────────────────────────────────────────────────
        $this->start_controls_section( 'style_svc_body', [
            'label' => __( 'Body Text', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'svc_body_typography',
            'label'    => __( 'Typography', 'ocean-charter' ),
            'selector' => '{{WRAPPER}} .oc-svc-card__desc',
        ] );
        $this->add_control( 'svc_body_color', [
            'label'     => __( 'Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .oc-svc-card__desc' => 'color: {{VALUE}};' ],
        ] );
        $this->end_controls_section();

        // ── Style: Card ───────────────────────────────────────────────────────
        $this->oc_register_card_style( '.oc-svc-card', 'Card' );
    }

    private function get_badge_icon_svg( string $type ): string {
        $svgs = [
            'chef' => '<path d="M18 11h-4V9a4 4 0 10-8 0v2H2v2h1l1 8h14l1-8h1v-2zm-10-2a2 2 0 014 0v2h-4V9zm7 10H7l-.75-6h9.5L15 19z" fill="currentColor"/>',
            'water' => '<path d="M17 16.99c-1.35 0-2.2.42-2.95.8-.65.33-1.18.6-2.05.6-.9 0-1.4-.25-2.05-.6C9.2 17.4 8.35 17 7 17s-2.2.42-2.95.8L3 18.17V20l1.05-.38c.65-.33 1.18-.6 2.05-.6.9 0 1.4.25 2.05.6.75.38 1.6.8 2.95.8s2.2-.42 2.95-.8c.65-.33 1.18-.6 2.05-.6.9 0 1.4.25 2.05.6L21 20v-1.83l-1.05-.38c-.75-.38-1.6-.8-2.95-.8zm0-4.5c-1.35 0-2.2.43-2.95.8-.65.32-1.18.6-2.05.6-.9 0-1.4-.25-2.05-.6C9.2 12.9 8.35 12.5 7 12.5s-2.2.43-2.95.8L3 13.67v1.84l1.05-.38c.65-.33 1.18-.6 2.05-.6.9 0 1.4.25 2.05.6.75.38 1.6.8 2.95.8s2.2-.43 2.95-.8c.65-.32 1.18-.6 2.05-.6.9 0 1.4.25 2.05.6L21 15.5v-1.83l-1.05-.38c-.75-.37-1.6-.8-2.95-.8zM17 8c-1.35 0-2.2.43-2.95.8-.65.32-1.18.6-2.05.6-.9 0-1.4-.25-2.05-.6C9.2 8.42 8.35 8 7 8s-2.2.43-2.95.8L3 9.17V11l1.05-.38c.65-.33 1.18-.6 2.05-.6.9 0 1.4.25 2.05.6.75.38 1.6.8 2.95.8s2.2-.43 2.95-.8c.65-.32 1.18-.6 2.05-.6.9 0 1.4.25 2.05.6L21 11V9.17l-1.05-.38C19.2 8.42 18.35 8 17 8z" fill="currentColor"/>',
            'events' => '<path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z" fill="currentColor"/>',
            'concierge' => '<path d="M12 1a9 9 0 019 9H3a9 9 0 019-9zm-7 11h14v1H5v-1zM3 20v-3h18v3H3zm8-9h2v5h-2v-5z" fill="currentColor"/>',
        ];
        return $svgs[ $type ] ?? $svgs['chef'];
    }

    protected function render() {
        $s   = $this->get_settings_for_display();
        $img = ! empty( $s['image']['url'] ) ? $s['image']['url'] : '';
        $url = ! empty( $s['link']['url'] ) ? $s['link']['url'] : '/contact/';
        ?>
        <div class="oc-svc-card" data-animate>
          <div class="oc-svc-card__img-wrap">
            <?php if ( $img ) : ?>
              <img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $s['title'] ); ?>" loading="lazy">
            <?php else : ?>
              <div class="oc-svc-card__img-ph"></div>
            <?php endif; ?>
            <div class="oc-svc-card__badge">
              <svg width="14" height="14" viewBox="0 0 24 24" aria-hidden="true">
                <?php echo $this->get_badge_icon_svg( $s['badge_icon'] ); ?>
              </svg>
              <?php echo esc_html( $s['eyebrow'] ); ?>
            </div>
          </div>
          <div class="oc-svc-card__body">
            <h3 class="oc-svc-card__title">
              <a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $s['title'] ); ?></a>
            </h3>
            <?php if ( $s['description'] ) : ?>
              <p class="oc-svc-card__desc"><?php echo esc_html( $s['description'] ); ?></p>
            <?php endif; ?>
            <?php if ( ! empty( $s['features'] ) ) : ?>
              <div class="oc-svc-card__tags">
                <?php foreach ( $s['features'] as $f ) : ?>
                  <span class="oc-svc-card__tag"><?php echo esc_html( $f['label'] ); ?></span>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>
        </div>
        <style>
        .oc-svc-card{display:flex;flex-direction:column;overflow:hidden;border-radius:16px;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);transition:border-color .3s ease,transform .3s ease;margin-bottom:32px;}
        .oc-svc-card:last-child{margin-bottom:0;}
        .oc-svc-card:hover{border-color:rgba(217,178,48,.45);transform:translateY(-4px);box-shadow:0 20px 60px rgba(0,0,0,.35);}
        .oc-svc-card__img-wrap{position:relative;aspect-ratio:4/5;overflow:hidden;flex-shrink:0;}
        .oc-svc-card__img-wrap img{width:100%;height:100%;object-fit:cover;transition:transform .7s ease;}
        .oc-svc-card:hover .oc-svc-card__img-wrap img{transform:scale(1.06);}
        .oc-svc-card__img-ph{width:100%;height:100%;background:linear-gradient(135deg,#0d1828 0%,#162440 100%);}
        .oc-svc-card__badge{position:absolute;bottom:16px;left:16px;z-index:1;display:inline-flex;align-items:center;gap:6px;background:rgba(10,15,26,.82);backdrop-filter:blur(10px);-webkit-backdrop-filter:blur(10px);border:1px solid rgba(217,178,48,.3);border-radius:8px;color:#d9b230;font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;padding:8px 14px;}
        .oc-svc-card__body{padding:24px;}
        .oc-svc-card__title{font-family:var(--font-heading);font-size:1.375rem;color:#f8fafc;margin:0 0 10px;font-weight:600;line-height:1.2;}
        .oc-svc-card__title a{color:inherit;text-decoration:none;transition:color .2s;}
        .oc-svc-card:hover .oc-svc-card__title a{color:#d9b230;}
        .oc-svc-card__desc{font-size:.875rem;color:rgba(148,163,184,1);line-height:1.6;margin:0 0 16px;}
        .oc-svc-card__tags{display:flex;flex-wrap:wrap;gap:6px;}
        .oc-svc-card__tag{font-size:11px;font-weight:600;padding:4px 10px;border-radius:9999px;background:rgba(217,178,48,.1);border:1px solid rgba(217,178,48,.2);color:#d9b230;}
        @media(max-width:768px){
          .oc-svc-card{margin-bottom:20px;}
          .oc-svc-card__img-wrap{aspect-ratio:16/9;}
          .oc-svc-card__body{padding:18px;}
          .oc-svc-card__title{font-size:1.2rem;}
        }
        @media(max-width:480px){
          .oc-svc-card{margin-bottom:16px;}
          .oc-svc-card__body{padding:14px;}
          .oc-svc-card__title{font-size:1.1rem;}
          .oc-svc-card__desc{font-size:0.8125rem;}
          .oc-svc-card__badge{padding:6px 10px;font-size:10px;}
        }
        </style>
        <?php
    }
}

/* ============================================================
   9. OC Contact Section Widget
   ============================================================ */
class OC_Contact_Section_Widget extends \Elementor\Widget_Base {
    use OC_Widget_Style_Trait;

    public function get_name()       { return 'oc-contact-section'; }
    public function get_title()      { return __( 'OC Contact Section', 'ocean-charter' ); }
    public function get_icon()       { return 'eicon-mail'; }
    public function get_categories() { return [ 'ocean-charter' ]; }

    protected function register_controls() {
        // ── Form column ───────────────────────────────────────────────────────
        $this->start_controls_section( 'form_content', [ 'label' => __( 'Form', 'ocean-charter' ) ] );
        $this->add_control( 'form_heading', [
            'label'   => __( 'Form Heading', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'Send an Inquiry', 'ocean-charter' ),
        ] );
        $this->add_control( 'form_subtitle', [
            'label'   => __( 'Form Subtitle', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'Our charter specialists typically respond within 2 hours during business hours.', 'ocean-charter' ),
        ] );
        $this->end_controls_section();

        // ── Info column ───────────────────────────────────────────────────────
        $this->start_controls_section( 'info_content', [ 'label' => __( 'Contact Info', 'ocean-charter' ) ] );
        $this->add_control( 'info_heading', [
            'label'   => __( 'Info Heading', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'Global Headquarters', 'ocean-charter' ),
        ] );
        $this->add_control( 'address', [
            'label'   => __( 'Address', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => '7 Quai Antoine 1er, 98000 Monaco',
        ] );
        $this->add_control( 'phone', [
            'label'   => __( 'Phone', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => '+377 99 99 00 00',
        ] );
        $this->add_control( 'email', [
            'label'   => __( 'Email', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => 'concierge@oceancharter.com',
        ] );
        $this->add_control( 'whatsapp_number', [
            'label'       => __( 'WhatsApp Number (digits only)', 'ocean-charter' ),
            'type'        => \Elementor\Controls_Manager::TEXT,
            'default'     => '377000000',
            'description' => __( 'e.g. 447911123456', 'ocean-charter' ),
        ] );
        $this->add_control( 'map_label', [
            'label'   => __( 'Map Location Label', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => 'Monaco — Global HQ',
        ] );
        $this->end_controls_section();

        // ── Style: Heading ────────────────────────────────────────────────────
        $this->start_controls_section( 'style_contact_heading', [
            'label' => __( 'Heading Color', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_control( 'contact_form_heading_color', [
            'label'     => __( 'Form Heading Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .ct-form__title' => 'color: {{VALUE}};' ],
        ] );
        $this->add_control( 'contact_info_heading_color', [
            'label'     => __( 'Info Heading Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .ct-info__title' => 'color: {{VALUE}};' ],
        ] );
        $this->end_controls_section();

        // ── Style: Form Button ────────────────────────────────────────────────
        $this->oc_register_button_style( '.ct-form__submit' );
    }

    protected function render() {
        $s      = $this->get_settings_for_display();
        $wa_num = preg_replace( '/\D/', '', $s['whatsapp_number'] ?: '377000000' );
        $wa_url = 'https://wa.me/' . $wa_num . '?text=' . rawurlencode( "Hello, I'd like to enquire about a charter." );
        ?>
        <div class="ct-section-el">
          <div class="ct-section-el__inner">

            <!-- ── LEFT: Form ───────────────────────────────────────── -->
            <div class="ct-form-col">
              <div class="ct-form-wrap">
                <h2 class="ct-form__title"><?php echo esc_html( $s['form_heading'] ); ?></h2>
                <p class="ct-form__subtitle"><?php echo esc_html( $s['form_subtitle'] ); ?></p>
                <form class="ct-form" id="charter-inquiry-form" method="post" novalidate>
                  <?php wp_nonce_field( 'ocean_charter_contact', 'oc_nonce' ); ?>

                  <div class="ct-form__field">
                    <label for="ct-full-name"><?php esc_html_e( 'Full Name', 'ocean-charter' ); ?> *</label>
                    <input type="text" id="ct-full-name" name="full_name" placeholder="James Hartley" required>
                  </div>

                  <div class="ct-form__field">
                    <label for="ct-email"><?php esc_html_e( 'Email Address', 'ocean-charter' ); ?> *</label>
                    <input type="email" id="ct-email" name="email" placeholder="james@example.com" required>
                  </div>

                  <div class="ct-form__field">
                    <label for="ct-interest"><?php esc_html_e( 'Charter Interest', 'ocean-charter' ); ?></label>
                    <select id="ct-interest" name="interest">
                      <option value=""><?php esc_html_e( 'Select a charter type...', 'ocean-charter' ); ?></option>
                      <option><?php esc_html_e( 'Sunset Cruise', 'ocean-charter' ); ?></option>
                      <option><?php esc_html_e( 'Day Charter', 'ocean-charter' ); ?></option>
                      <option><?php esc_html_e( 'Multi-Day Voyage', 'ocean-charter' ); ?></option>
                      <option><?php esc_html_e( 'Corporate Event', 'ocean-charter' ); ?></option>
                      <option><?php esc_html_e( 'Birthday / Celebration', 'ocean-charter' ); ?></option>
                      <option><?php esc_html_e( 'Wedding at Sea', 'ocean-charter' ); ?></option>
                      <option><?php esc_html_e( 'Bespoke Voyage', 'ocean-charter' ); ?></option>
                    </select>
                  </div>

                  <div class="ct-form__field">
                    <label for="ct-message"><?php esc_html_e( 'Message', 'ocean-charter' ); ?></label>
                    <textarea id="ct-message" name="message" rows="5" placeholder="<?php esc_attr_e( 'Tell us about your dream voyage...', 'ocean-charter' ); ?>"></textarea>
                  </div>

                  <div class="ct-form__field ct-form__field--check">
                    <label class="ct-checkbox">
                      <input type="checkbox" name="privacy" required>
                      <span><?php echo wp_kses( __( 'I agree to the <a href="#">Privacy Policy</a> and <a href="#">Terms of Service</a>', 'ocean-charter' ), [ 'a' => [ 'href' => [] ] ] ); ?></span>
                    </label>
                  </div>

                  <button type="submit" class="btn-primary ct-form__submit">
                    <?php esc_html_e( 'Send My Inquiry →', 'ocean-charter' ); ?>
                  </button>
                </form>
              </div>
            </div><!-- /.ct-form-col -->

            <!-- ── RIGHT: Info + Map ─────────────────────────────────── -->
            <div class="ct-info-col">
              <h2 class="ct-info__title"><?php echo esc_html( $s['info_heading'] ); ?></h2>

              <!-- Address -->
              <div class="ct-info-block">
                <div class="ct-info-block__icon">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                </div>
                <div>
                  <strong><?php esc_html_e( 'Monaco Office', 'ocean-charter' ); ?></strong>
                  <span><?php echo esc_html( $s['address'] ); ?></span>
                </div>
              </div>

              <!-- Phone -->
              <div class="ct-info-block">
                <div class="ct-info-block__icon">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81a19.79 19.79 0 01-3.07-8.7A2 2 0 012 .99h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 8.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
                </div>
                <div>
                  <strong><?php esc_html_e( 'International Support', 'ocean-charter' ); ?></strong>
                  <span><?php echo esc_html( $s['phone'] ); ?></span>
                </div>
              </div>

              <!-- Email -->
              <div class="ct-info-block">
                <div class="ct-info-block__icon">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                </div>
                <div>
                  <strong><?php esc_html_e( 'General Enquiries', 'ocean-charter' ); ?></strong>
                  <span><?php echo esc_html( $s['email'] ); ?></span>
                </div>
              </div>

              <!-- WhatsApp -->
              <div class="ct-whatsapp">
                <div class="ct-whatsapp__label">
                  <strong><?php esc_html_e( 'Instant Booking', 'ocean-charter' ); ?></strong>
                  <span><?php esc_html_e( 'WhatsApp our Concierge', 'ocean-charter' ); ?></span>
                </div>
                <a href="<?php echo esc_url( $wa_url ); ?>" target="_blank" rel="noopener" class="ct-whatsapp__btn">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                  <?php esc_html_e( 'Chat Now', 'ocean-charter' ); ?>
                </a>
              </div>

              <!-- Map visual -->
              <div class="ct-map-visual" aria-hidden="true">
                <div class="ct-map-visual__glow"></div>
                <div class="ct-map-visual__globe">
                  <svg viewBox="0 0 160 160" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="80" cy="80" r="72" stroke="#d9b230" stroke-width="1.5" opacity="0.4"/>
                    <ellipse cx="80" cy="80" rx="36" ry="72" stroke="#d9b230" stroke-width="1" opacity="0.3"/>
                    <line x1="8" y1="80" x2="152" y2="80" stroke="#d9b230" stroke-width="1" opacity="0.3"/>
                    <line x1="80" y1="8" x2="80" y2="152" stroke="#d9b230" stroke-width="1" opacity="0.3"/>
                    <ellipse cx="80" cy="80" rx="72" ry="28" stroke="#d9b230" stroke-width="1" opacity="0.3"/>
                  </svg>
                </div>
                <div class="ct-map-pin">
                  <div class="ct-map-pin__dot"></div>
                  <svg class="ct-map-pin__icon" width="28" height="36" viewBox="0 0 28 36" fill="none">
                    <path d="M14 0C6.27 0 0 6.27 0 14c0 9.75 14 22 14 22S28 23.75 28 14C28 6.27 21.73 0 14 0zm0 19a5 5 0 110-10 5 5 0 010 10z" fill="#d9b230"/>
                  </svg>
                  <span class="ct-map-pin__label"><?php echo esc_html( $s['map_label'] ); ?></span>
                </div>
              </div>

            </div><!-- /.ct-info-col -->
          </div>
        </div>

        <style>
        /* Contact section layout */
        .ct-section-el{padding:100px 0 120px;}
        .ct-section-el__inner{display:grid;grid-template-columns:7fr 5fr;gap:64px;align-items:start;max-width:1140px;margin:0 auto;padding:0 clamp(1rem,4vw,2.5rem);}

        /* Form column */
        .ct-form-col .ct-form-wrap{background:var(--surface);border:1px solid var(--glass-border);border-radius:var(--radius-lg);padding:56px;}
        .ct-form__title{font-family:var(--font-heading);font-size:30px;color:var(--text-light);font-weight:400;margin:0 0 12px;}
        .ct-form__subtitle{color:var(--text-muted);font-size:15px;margin-bottom:40px;}
        .ct-form__field{display:flex;flex-direction:column;gap:8px;margin-bottom:24px;}
        .ct-form__field label{font-size:13px;color:var(--text-muted);font-weight:500;text-transform:uppercase;letter-spacing:.05em;}
        .ct-form input[type=text],.ct-form input[type=email],.ct-form select,.ct-form textarea{width:100%;background:rgba(255,255,255,.04);border:1px solid var(--glass-border);border-radius:var(--radius);padding:14px 18px;color:var(--text-light);font-family:var(--font-body);font-size:15px;transition:border-color .2s,box-shadow .2s;}
        .ct-form input:focus,.ct-form select:focus,.ct-form textarea:focus{outline:none;border-color:var(--primary);box-shadow:0 0 0 3px rgba(217,178,48,.1);}
        .ct-form input::placeholder,.ct-form textarea::placeholder{color:rgba(255,255,255,.2);}
        .ct-form select{appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23667' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 16px center;background-color:rgba(255,255,255,.04);}
        .ct-form select option{background:var(--surface);color:var(--text-light);}
        .ct-form textarea{resize:vertical;min-height:120px;}
        .ct-form__field--check{margin-top:8px;}
        .ct-checkbox{display:flex;gap:12px;align-items:flex-start;cursor:pointer;}
        .ct-checkbox input{margin-top:3px;accent-color:var(--primary);flex-shrink:0;}
        .ct-checkbox span{color:var(--text-muted);font-size:14px;line-height:1.5;}
        .ct-checkbox a{color:var(--primary);}
        .ct-form__submit{width:100%;margin-top:24px;padding:18px;font-size:15px;display:block;text-align:center;}

        /* Info column */
        .ct-info-col{padding-top:8px;}
        .ct-info__title{font-family:var(--font-heading);font-size:30px;color:var(--text-light);font-weight:400;margin:0 0 40px;}
        .ct-info-block{display:flex;gap:16px;align-items:flex-start;margin-bottom:28px;padding-bottom:28px;border-bottom:1px solid var(--glass-border);}
        .ct-info-block:last-of-type{border-bottom:none;}
        .ct-info-block__icon{width:44px;height:44px;background:var(--surface);border:1px solid var(--glass-border);border-radius:var(--radius);display:flex;align-items:center;justify-content:center;color:var(--primary);flex-shrink:0;}
        .ct-info-block strong{display:block;color:var(--text-light);font-size:15px;margin-bottom:4px;font-weight:600;}
        .ct-info-block span{color:var(--text-muted);font-size:15px;}
        .ct-whatsapp{background:linear-gradient(135deg,rgba(37,211,102,.06),rgba(37,211,102,.02));border:1px solid rgba(37,211,102,.2);border-radius:var(--radius-lg);padding:24px;display:flex;align-items:center;justify-content:space-between;gap:20px;margin:32px 0;}
        .ct-whatsapp__label strong{display:block;color:var(--text-light);font-size:15px;margin-bottom:4px;}
        .ct-whatsapp__label span{color:var(--text-muted);font-size:14px;}
        .ct-whatsapp__btn{display:inline-flex;align-items:center;gap:10px;background:#25D366;color:#fff;padding:12px 20px;border-radius:var(--radius);font-weight:600;font-size:14px;text-decoration:none;transition:background .2s;white-space:nowrap;}
        .ct-whatsapp__btn:hover{background:#1DB954;color:#fff;}

        /* Map visual */
        .ct-map-visual{position:relative;height:300px;background:#070d18;border:1px solid rgba(255,255,255,.08);border-radius:16px;overflow:hidden;margin-top:8px;}
        .ct-map-visual__glow{position:absolute;inset:0;background:radial-gradient(circle at 60% 50%,rgba(217,178,48,.18) 0%,transparent 65%);pointer-events:none;}
        .ct-map-visual__globe{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;opacity:.18;}
        .ct-map-visual__globe svg{width:220px;height:220px;}
        .ct-map-pin{position:absolute;bottom:32px;left:50%;transform:translateX(-50%);display:flex;flex-direction:column;align-items:center;gap:6px;}
        .ct-map-pin__dot{width:10px;height:10px;border-radius:50%;background:#d9b230;box-shadow:0 0 0 4px rgba(217,178,48,.25);animation:map-pulse 2s ease-in-out infinite;}
        @keyframes map-pulse{0%,100%{box-shadow:0 0 0 4px rgba(217,178,48,.25);}50%{box-shadow:0 0 0 8px rgba(217,178,48,.1);}}
        .ct-map-pin__icon{filter:drop-shadow(0 4px 12px rgba(217,178,48,.4));}
        .ct-map-pin__label{font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#d9b230;background:rgba(10,15,26,.8);backdrop-filter:blur(8px);border:1px solid rgba(217,178,48,.2);border-radius:6px;padding:5px 12px;white-space:nowrap;}

        @media(max-width:1024px){.ct-section-el__inner{grid-template-columns:1fr;gap:56px;}.ct-form-col .ct-form-wrap{padding:40px 32px;}}
        @media(max-width:640px){.ct-form-col .ct-form-wrap{padding:28px 20px;}.ct-whatsapp{flex-direction:column;align-items:flex-start;}.ct-section-el{padding:60px 0 80px;}}
        </style>
        <?php
    }
}

/* ============================================================
   10. OC Itinerary Sidebar Widget
   ============================================================ */
class OC_Itinerary_Sidebar_Widget extends \Elementor\Widget_Base {
    use OC_Widget_Style_Trait;

    public function get_name()        { return 'oc-itinerary-sidebar'; }
    public function get_title()       { return __( 'OC Itinerary Sidebar', 'ocean-charter' ); }
    public function get_icon()        { return 'eicon-navigation-horizontal'; }
    public function get_categories()  { return [ 'ocean-charter' ]; }

    /**
     * Catmull-Rom → cubic Bezier conversion.
     * Produces a smooth SVG path string through all given [x,y] points.
     */
    private function catmull_rom_path( array $pts ): string {
        $n = count( $pts );
        if ( $n < 2 ) return '';
        if ( $n === 2 ) {
            return "M {$pts[0][0]},{$pts[0][1]} L {$pts[1][0]},{$pts[1][1]}";
        }
        $d = "M {$pts[0][0]},{$pts[0][1]}";
        for ( $i = 0; $i < $n - 1; $i++ ) {
            $p0 = $i > 0         ? $pts[ $i - 1 ] : $pts[0];
            $p1 = $pts[ $i ];
            $p2 = $pts[ $i + 1 ];
            $p3 = $i < $n - 2    ? $pts[ $i + 2 ] : $pts[ $n - 1 ];
            $cp1x = round( $p1[0] + ( $p2[0] - $p0[0] ) / 6 );
            $cp1y = round( $p1[1] + ( $p2[1] - $p0[1] ) / 6 );
            $cp2x = round( $p2[0] - ( $p3[0] - $p1[0] ) / 6 );
            $cp2y = round( $p2[1] - ( $p3[1] - $p1[1] ) / 6 );
            $d   .= " C {$cp1x},{$cp1y} {$cp2x},{$cp2y} {$p2[0]},{$p2[1]}";
        }
        return $d;
    }

    protected function register_controls() {
        // ── Map section ──────────────────────────────────────────────────────
        $this->start_controls_section( 'map_section', [ 'label' => __( 'Route Map', 'ocean-charter' ) ] );

        $this->add_control( 'map_title', [
            'label'   => __( 'Map Label', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => 'Route Map',
        ] );

        $this->add_control( 'map_stops', [
            'label'       => __( 'Route Stops', 'ocean-charter' ),
            'type'        => \Elementor\Controls_Manager::REPEATER,
            'fields'      => [
                [
                    'name'    => 'stop_name',
                    'label'   => __( 'Stop Name', 'ocean-charter' ),
                    'type'    => \Elementor\Controls_Manager::TEXT,
                    'default' => 'Port',
                ],
                [
                    'name'      => 'stop_lat',
                    'label'     => __( 'Latitude', 'ocean-charter' ),
                    'type'      => \Elementor\Controls_Manager::NUMBER,
                    'min'       => -90,
                    'max'       => 90,
                    'step'      => 0.0001,
                    'default'   => 0,
                ],
                [
                    'name'      => 'stop_lng',
                    'label'     => __( 'Longitude', 'ocean-charter' ),
                    'type'      => \Elementor\Controls_Manager::NUMBER,
                    'min'       => -180,
                    'max'       => 180,
                    'step'      => 0.0001,
                    'default'   => 0,
                ],
            ],
            'default' => [
                [ 'stop_name' => 'Athens',    'stop_lat' => 37.9838, 'stop_lng' => 23.7275 ],
                [ 'stop_name' => 'Hydra',     'stop_lat' => 37.3489, 'stop_lng' => 23.4620 ],
                [ 'stop_name' => 'Paros',     'stop_lat' => 37.0853, 'stop_lng' => 25.1520 ],
                [ 'stop_name' => 'Mykonos',   'stop_lat' => 37.4467, 'stop_lng' => 25.3289 ],
                [ 'stop_name' => 'Santorini', 'stop_lat' => 36.3932, 'stop_lng' => 25.4615 ],
                [ 'stop_name' => 'Athens',    'stop_lat' => 37.9838, 'stop_lng' => 23.7275 ],
            ],
            'title_field' => '{{{ stop_name }}}',
        ] );

        $this->end_controls_section();

        // ── Booking card section ──────────────────────────────────────────────
        $this->start_controls_section( 'booking', [ 'label' => __( 'Booking Card', 'ocean-charter' ) ] );

        $this->add_control( 'card_title',   [ 'label' => __( 'Card Title',    'ocean-charter' ), 'type' => \Elementor\Controls_Manager::TEXT,     'default' => 'Reserve Your Suite' ] );
        $this->add_control( 'price',        [ 'label' => __( 'Price',         'ocean-charter' ), 'type' => \Elementor\Controls_Manager::TEXT,     'default' => '$18,500' ] );
        $this->add_control( 'price_period', [ 'label' => __( 'Price Period',  'ocean-charter' ), 'type' => \Elementor\Controls_Manager::TEXT,     'default' => 'for 7 days (yacht only)' ] );
        $this->add_control( 'price_note',   [ 'label' => __( 'Price Note',    'ocean-charter' ), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'Price varies by vessel selection and season. Includes crew, fuel, and all onboard provisions.' ] );
        $this->add_control( 'cta_label',    [ 'label' => __( 'CTA Label',     'ocean-charter' ), 'type' => \Elementor\Controls_Manager::TEXT,     'default' => 'Book This Itinerary' ] );
        $this->add_control( 'cta_url',      [ 'label' => __( 'CTA URL',       'ocean-charter' ), 'type' => \Elementor\Controls_Manager::URL,      'default' => [ 'url' => '/contact/' ] ] );
        $this->add_control( 'whatsapp',     [ 'label' => __( 'WhatsApp No.',  'ocean-charter' ), 'type' => \Elementor\Controls_Manager::TEXT,     'default' => '+15551234567' ] );

        $this->add_control( 'inclusions', [
            'label'       => __( 'Inclusions', 'ocean-charter' ),
            'type'        => \Elementor\Controls_Manager::REPEATER,
            'fields'      => [ [ 'name' => 'item', 'label' => 'Item', 'type' => \Elementor\Controls_Manager::TEXT ] ],
            'default'     => [
                [ 'item' => 'Dedicated crew (captain + chef)' ],
                [ 'item' => 'All meals & beverages' ],
                [ 'item' => 'Fuel & marina fees' ],
                [ 'item' => 'Water sports equipment' ],
                [ 'item' => 'Private guide at Delos' ],
                [ 'item' => '24/7 concierge service' ],
            ],
            'title_field' => '{{{ item }}}',
        ] );

        $this->end_controls_section();

        // ── Style: Price ──────────────────────────────────────────────────────
        $this->start_controls_section( 'style_sidebar_price', [
            'label' => __( 'Price', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'sidebar_price_typography',
            'label'    => __( 'Typography', 'ocean-charter' ),
            'selector' => '{{WRAPPER}} .oc-it-sidebar__price',
        ] );
        $this->add_control( 'sidebar_price_color', [
            'label'     => __( 'Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .oc-it-sidebar__price' => 'color: {{VALUE}};' ],
        ] );
        $this->end_controls_section();

        // ── Style: Button ─────────────────────────────────────────────────────
        $this->oc_register_button_style( '.oc-it-sidebar__cta-btn' );
    }

    protected function render() {
        wp_enqueue_style( 'leaflet', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css', [], '1.9.4' );
        wp_enqueue_script( 'leaflet', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', [], '1.9.4', true );

        $s        = $this->get_settings_for_display();
        $cta_url  = ! empty( $s['cta_url']['url'] ) ? $s['cta_url']['url'] : '/contact/';
        $wa_num   = preg_replace( '/\D/', '', $s['whatsapp'] ?? '' );
        $wa_msg   = rawurlencode( 'Hi, I would like to book the itinerary.' );
        $wa_url   = $wa_num ? 'https://wa.me/' . $wa_num . '?text=' . $wa_msg : '#';
        $map_id   = 'oc-route-' . $this->get_id();

        // Build coordinate array from repeater
        $raw_stops = ! empty( $s['map_stops'] ) ? $s['map_stops'] : [];
        $has_stops = ! empty( $raw_stops ) && isset( $raw_stops[0]['stop_lat'] );
        ?>
        <div class="oc-it-sidebar">

          <!-- Route Map (Leaflet.js) -->
          <div class="oc-it-sidebar__map">
            <div class="oc-it-sidebar__map-label">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="3" fill="#d9b230"/><circle cx="12" cy="12" r="7" stroke="#d9b230" stroke-width="1.5" fill="none" opacity=".4"/></svg>
              <?php echo esc_html( $s['map_title'] ?? 'Route Map' ); ?>
            </div>
            <?php if ( $has_stops ) : ?>
            <div id="<?php echo esc_attr( $map_id ); ?>" class="oc-route-map" style="height:260px;border-radius:0.5rem;"></div>
            <script>
            (function(){
                if (typeof L === 'undefined') return;
                var stops = <?php echo wp_json_encode( array_map( function( $st ) {
                    return [
                        'name' => $st['stop_name'] ?? '',
                        'lat'  => (float) ( $st['stop_lat'] ?? 0 ),
                        'lng'  => (float) ( $st['stop_lng'] ?? 0 ),
                    ];
                }, $raw_stops ) ); ?>;
                var map = L.map(<?php echo wp_json_encode( $map_id ); ?>, {
                    scrollWheelZoom: false, attributionControl: false, zoomControl: true
                });
                L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { maxZoom: 19 }).addTo(map);
                L.control.attribution({ position: 'bottomright', prefix: false })
                    .addAttribution('&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a>')
                    .addTo(map);
                var goldIcon = L.divIcon({ className:'oc-map-marker', html:'<span class="oc-map-dot"></span>', iconSize:[14,14], iconAnchor:[7,7] });
                var startIcon = L.divIcon({ className:'oc-map-marker oc-map-marker--start', html:'<span class="oc-map-dot oc-map-dot--start"></span>', iconSize:[18,18], iconAnchor:[9,9] });
                var latlngs = [];
                stops.forEach(function(s, i){
                    var icon = (i === 0 || i === stops.length - 1) ? startIcon : goldIcon;
                    var m = L.marker([s.lat, s.lng], { icon: icon }).addTo(map);
                    if (s.name) m.bindTooltip(s.name, { permanent:true, direction:'right', offset:[10,0], className:'oc-map-label' });
                    latlngs.push([s.lat, s.lng]);
                });
                if (latlngs.length > 1) L.polyline(latlngs, { color:'#d9b230', weight:2.5, opacity:0.7, dashArray:'8 5' }).addTo(map);
                map.fitBounds(L.latLngBounds(latlngs).pad(0.15));
            })();
            </script>
            <?php else : ?>
            <p style="color:rgba(148,163,184,.6);font-size:12px;padding:8px 0;">Add route stops with lat/lng to display the map.</p>
            <?php endif; ?>
          </div>

          <!-- Booking Card -->
          <div class="oc-it-sidebar__card">
            <h3 class="oc-it-sidebar__card-title"><?php echo esc_html( $s['card_title'] ); ?></h3>
            <div class="oc-it-sidebar__price-wrap">
              <span class="oc-it-sidebar__price-from">Starting from</span>
              <span class="oc-it-sidebar__price"><?php echo esc_html( $s['price'] ); ?></span>
              <span class="oc-it-sidebar__price-period"><?php echo esc_html( $s['price_period'] ); ?></span>
            </div>
            <?php if ( $s['price_note'] ) : ?>
            <p class="oc-it-sidebar__price-note"><?php echo esc_html( $s['price_note'] ); ?></p>
            <?php endif; ?>

            <div class="oc-it-sidebar__ctas">
              <a href="<?php echo esc_url( $cta_url ); ?>" class="btn-primary oc-it-sidebar__cta-btn"><?php echo esc_html( $s['cta_label'] ); ?></a>
              <?php if ( $wa_url !== '#' ) : ?>
              <a href="<?php echo esc_url( $wa_url ); ?>" class="oc-it-sidebar__wa" target="_blank" rel="noopener noreferrer">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                Chat on WhatsApp
              </a>
              <?php endif; ?>
            </div>

            <?php if ( ! empty( $s['inclusions'] ) ) : ?>
            <div class="oc-it-sidebar__includes-wrap">
              <h4 class="oc-it-sidebar__includes-heading">What's Included</h4>
              <ul class="oc-it-sidebar__includes">
                <?php foreach ( $s['inclusions'] as $inc ) : ?>
                <li>
                  <svg width="14" height="14" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M13 4L6.5 11 3 7.5" stroke="#d9b230" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                  <?php echo esc_html( $inc['item'] ); ?>
                </li>
                <?php endforeach; ?>
              </ul>
            </div>
            <?php endif; ?>
          </div>

        </div>
        <style>
        .oc-it-sidebar{display:flex;flex-direction:column;gap:20px;position:sticky;top:100px;}
        .oc-it-sidebar__map{background:#071120;border:1px solid rgba(217,178,48,0.15);border-radius:16px;overflow:hidden;padding:16px 16px 12px;}
        .oc-it-sidebar__map-label{display:flex;align-items:center;gap:6px;font-size:10px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#d9b230;margin-bottom:10px;}
        .oc-route-map{width:100%;border-radius:0.5rem;z-index:1;}
        .oc-map-dot{display:block;width:10px;height:10px;background:#1e3a5f;border:2px solid #d9b230;border-radius:50%;}
        .oc-map-dot--start{width:14px;height:14px;background:#d9b230;}
        .oc-map-label{background:rgba(13,24,37,0.85)!important;border:1px solid rgba(217,178,48,0.4)!important;color:rgba(240,236,227,0.9)!important;font-size:11px!important;padding:2px 8px!important;border-radius:4px!important;box-shadow:0 2px 6px rgba(0,0,0,0.3)!important;}
        .oc-map-label::before{border-right-color:rgba(217,178,48,0.4)!important;}
        .leaflet-control-zoom a{background:rgba(13,24,37,0.9)!important;color:#d9b230!important;border-color:rgba(217,178,48,0.3)!important;}
        .oc-it-sidebar__card{background:linear-gradient(160deg,#111827 0%,#0d1a2e 100%);border:1px solid rgba(217,178,48,0.2);border-radius:16px;padding:24px;}
        .oc-it-sidebar__card-title{font-family:var(--font-heading);font-size:1.2rem;color:#f8fafc;font-weight:400;margin:0 0 16px;letter-spacing:.01em;}
        .oc-it-sidebar__price-wrap{text-align:center;padding:16px 0;border-top:1px solid rgba(255,255,255,0.08);border-bottom:1px solid rgba(255,255,255,0.08);margin-bottom:14px;}
        .oc-it-sidebar__price-from{display:block;font-size:10px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:rgba(148,163,184,1);margin-bottom:4px;}
        .oc-it-sidebar__price{display:block;font-family:var(--font-heading);font-size:2rem;font-weight:700;color:#d9b230;line-height:1;}
        .oc-it-sidebar__price-period{display:block;font-size:12px;color:rgba(148,163,184,1);margin-top:4px;}
        .oc-it-sidebar__price-note{font-size:12px;color:rgba(148,163,184,.8);line-height:1.6;margin:0 0 16px;}
        .oc-it-sidebar__ctas{display:flex;flex-direction:column;gap:10px;margin-bottom:20px;}
        .oc-it-sidebar__cta-btn{display:block;text-align:center;width:100%;}
        .oc-it-sidebar__wa{display:flex;align-items:center;justify-content:center;gap:8px;padding:.65em 1em;border-radius:9999px;border:1px solid #25D366;color:#25D366;font-size:.8125rem;font-weight:600;text-decoration:none;transition:background .2s,color .2s;}
        .oc-it-sidebar__wa:hover{background:#25D366;color:#fff;}
        .oc-it-sidebar__includes-wrap{border-top:1px solid rgba(255,255,255,0.08);padding-top:16px;}
        .oc-it-sidebar__includes-heading{font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(148,163,184,1);margin:0 0 12px;}
        .oc-it-sidebar__includes{list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:8px;}
        .oc-it-sidebar__includes li{display:flex;align-items:flex-start;gap:8px;font-size:13px;color:rgba(148,163,184,1);line-height:1.4;}
        .oc-it-sidebar__includes svg{flex-shrink:0;margin-top:2px;}
        @media(max-width:1024px){
          .oc-it-sidebar{position:static;top:auto;}
        }
        @media(max-width:768px){
          .oc-it-sidebar__card{padding:18px;}
          .oc-it-sidebar__price{font-size:1.75rem;}
          .oc-it-sidebar__map{padding:12px 12px 8px;}
        }
        @media(max-width:480px){
          .oc-it-sidebar__card{padding:14px;}
          .oc-it-sidebar__card-title{font-size:1.1rem;}
          .oc-it-sidebar__price{font-size:1.5rem;}
          .oc-it-sidebar__wa{font-size:0.75rem;padding:0.5em 0.75em;}
          .oc-it-sidebar__includes li{font-size:12px;}
        }
        </style>
        <?php
    }
}

/* ============================================================
   11. OC Bespoke Section Widget
   ============================================================ */
class OC_Bespoke_Section_Widget extends \Elementor\Widget_Base {
    use OC_Widget_Style_Trait;

    public function get_name()        { return 'oc-bespoke-section'; }
    public function get_title()       { return __( 'OC Bespoke Section', 'ocean-charter' ); }
    public function get_icon()        { return 'eicon-call-to-action'; }
    public function get_categories()  { return [ 'ocean-charter' ]; }

    protected function register_controls() {
        $this->start_controls_section( 'content', [ 'label' => __( 'Content', 'ocean-charter' ) ] );

        $this->add_control( 'eyebrow',   [ 'label' => __( 'Eyebrow',      'ocean-charter' ), 'type' => \Elementor\Controls_Manager::TEXT,     'default' => 'Fully Custom' ] );
        $this->add_control( 'heading',   [ 'label' => __( 'Heading',      'ocean-charter' ), 'type' => \Elementor\Controls_Manager::TEXT,     'default' => 'Bespoke Voyages' ] );
        $this->add_control( 'body_text', [ 'label' => __( 'Body Text',    'ocean-charter' ), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => "None of our packages match your vision? Perfect. Our most discerning clients work directly with our charter architects to design a voyage that exists nowhere else in the world.\n\nTell us your dream. We'll build the journey around it." ] );
        $this->add_control( 'cta_label', [ 'label' => __( 'CTA Label',    'ocean-charter' ), 'type' => \Elementor\Controls_Manager::TEXT,     'default' => 'Speak to a Charter Architect' ] );
        $this->add_control( 'cta_url',   [ 'label' => __( 'CTA URL',      'ocean-charter' ), 'type' => \Elementor\Controls_Manager::URL,      'default' => [ 'url' => '/contact/' ] ] );
        $this->add_control( 'image',     [ 'label' => __( 'Image',        'ocean-charter' ), 'type' => \Elementor\Controls_Manager::MEDIA ] );
        $this->add_control( 'badge_text',[ 'label' => __( 'Badge Text',   'ocean-charter' ), 'type' => \Elementor\Controls_Manager::TEXT,     'default' => '100% Satisfaction' ] );

        $this->end_controls_section();

        // ── Style: Heading ────────────────────────────────────────────────────
        $this->start_controls_section( 'style_bespoke_heading', [
            'label' => __( 'Heading', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'bespoke_heading_typography',
            'label'    => __( 'Typography', 'ocean-charter' ),
            'selector' => '{{WRAPPER}} .oc-bespoke-el__heading',
        ] );
        $this->add_control( 'bespoke_heading_color', [
            'label'     => __( 'Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .oc-bespoke-el__heading' => 'color: {{VALUE}};' ],
        ] );
        $this->end_controls_section();

        // ── Style: Description ────────────────────────────────────────────────
        $this->start_controls_section( 'style_bespoke_desc', [
            'label' => __( 'Description', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'bespoke_desc_typography',
            'label'    => __( 'Typography', 'ocean-charter' ),
            'selector' => '{{WRAPPER}} .oc-bespoke-el__content p',
        ] );
        $this->add_control( 'bespoke_desc_color', [
            'label'     => __( 'Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .oc-bespoke-el__content p' => 'color: {{VALUE}};' ],
        ] );
        $this->end_controls_section();

        // ── Style: Button ─────────────────────────────────────────────────────
        $this->oc_register_button_style( '.oc-bespoke-el__cta' );

        // ── Style: Background ─────────────────────────────────────────────────
        $this->start_controls_section( 'style_bespoke_bg', [
            'label' => __( 'Background', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_control( 'bespoke_bg_color', [
            'label'     => __( 'Background Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .oc-bespoke-el' => 'background: {{VALUE}};' ],
        ] );
        $this->end_controls_section();
    }

    protected function render() {
        $s       = $this->get_settings_for_display();
        $img     = ! empty( $s['image']['url'] ) ? $s['image']['url'] : '';
        $url     = ! empty( $s['cta_url']['url'] ) ? $s['cta_url']['url'] : '/contact/';
        $paras   = array_filter( array_map( 'trim', explode( "\n\n", $s['body_text'] ) ) );
        ?>
        <section class="oc-bespoke-el">
          <div class="oc-bespoke-el__inner">

            <div class="oc-bespoke-el__content">
              <span class="oc-bespoke-el__eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></span>
              <h2 class="oc-bespoke-el__heading"><?php echo esc_html( $s['heading'] ); ?></h2>
              <?php foreach ( $paras as $p ) : ?>
                <p><?php echo esc_html( $p ); ?></p>
              <?php endforeach; ?>
              <a href="<?php echo esc_url( $url ); ?>" class="btn-primary oc-bespoke-el__cta"><?php echo esc_html( $s['cta_label'] ); ?></a>
            </div>

            <div class="oc-bespoke-el__visual">
              <?php if ( $img ) : ?>
                <img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $s['heading'] ); ?>" loading="lazy">
              <?php else : ?>
                <div class="oc-bespoke-el__img-ph"></div>
              <?php endif; ?>
              <?php if ( $s['badge_text'] ) : ?>
              <div class="oc-bespoke-el__badge" aria-hidden="true">
                <span class="oc-bespoke-el__badge-check">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                </span>
                <span><?php echo esc_html( $s['badge_text'] ); ?></span>
              </div>
              <?php endif; ?>
            </div>

          </div>
        </section>
        <style>
        .oc-bespoke-el{padding:100px clamp(1rem,4vw,2.5rem);background:#111827;}
        .oc-bespoke-el__inner{display:grid;grid-template-columns:1.2fr 1fr;gap:80px;align-items:center;max-width:1140px;margin:0 auto;}
        .oc-bespoke-el__eyebrow{display:block;font-size:11px;font-weight:700;letter-spacing:.15em;text-transform:uppercase;color:#d9b230;margin-bottom:16px;}
        .oc-bespoke-el__heading{font-family:var(--font-heading);font-size:clamp(32px,4vw,52px);color:#f8fafc;font-weight:400;margin:0 0 24px;line-height:1.2;}
        .oc-bespoke-el__content p{color:rgba(148,163,184,1);line-height:1.8;font-size:17px;margin-bottom:20px;}
        .oc-bespoke-el__cta{margin-top:12px;display:inline-flex;}
        .oc-bespoke-el__visual{position:relative;border-radius:16px;overflow:hidden;aspect-ratio:4/5;}
        .oc-bespoke-el__visual img{width:100%;height:100%;object-fit:cover;transition:transform .7s ease;}
        .oc-bespoke-el__visual:hover img{transform:scale(1.03);}
        .oc-bespoke-el__img-ph{width:100%;height:100%;min-height:400px;background:linear-gradient(135deg,#0d1f35 0%,#1a2d45 100%);}
        .oc-bespoke-el__badge{position:absolute;top:24px;right:24px;display:flex;align-items:center;gap:10px;background:rgba(10,15,26,.85);backdrop-filter:blur(12px);border:1px solid rgba(217,178,48,.3);border-radius:12px;padding:14px 20px;color:#f8fafc;font-size:14px;font-weight:700;}
        .oc-bespoke-el__badge-check{width:32px;height:32px;background:#d9b230;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;color:#0a0f1a;}
        @media(max-width:1024px){.oc-bespoke-el__inner{grid-template-columns:1fr;gap:48px;}.oc-bespoke-el__visual{aspect-ratio:16/9;}}
        @media(max-width:640px){.oc-bespoke-el{padding:60px clamp(1rem,4vw,2rem);}.oc-bespoke-el__heading{font-size:clamp(24px,6vw,36px);}.oc-bespoke-el__content p{font-size:15px;}.oc-bespoke-el__badge{padding:10px 14px;font-size:12px;}}
        </style>
        <?php
    }
}

/* ============================================================
   12. OC Search Form Widget (standalone, drag & drop)
   ============================================================ */
class OC_Search_Form_Widget extends \Elementor\Widget_Base {
    use OC_Widget_Style_Trait;

    public function get_name()       { return 'oc-search-form'; }
    public function get_title()      { return __( 'OC Search Form', 'ocean-charter' ); }
    public function get_icon()       { return 'eicon-search'; }
    public function get_categories() { return [ 'ocean-charter' ]; }

    protected function register_controls() {

        /* ── Content: Fields ──────────────────────────────── */
        $this->start_controls_section( 'content_fields', [ 'label' => __( 'Search Fields', 'ocean-charter' ) ] );

        $repeater = new \Elementor\Repeater();
        $repeater->add_control( 'field_label', [
            'label'   => __( 'Label', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => 'Field',
        ] );
        $repeater->add_control( 'field_type', [
            'label'   => __( 'Field Type', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => 'text',
            'options' => [
                'text'   => __( 'Text Input', 'ocean-charter' ),
                'date'   => __( 'Date Picker', 'ocean-charter' ),
                'select' => __( 'Dropdown', 'ocean-charter' ),
            ],
        ] );
        $repeater->add_control( 'field_name', [
            'label'   => __( 'Field Name (for form)', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => 'field',
        ] );
        $repeater->add_control( 'field_placeholder', [
            'label'   => __( 'Placeholder', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => '',
        ] );
        $repeater->add_control( 'field_options', [
            'label'       => __( 'Options (one per line, for dropdowns)', 'ocean-charter' ),
            'type'        => \Elementor\Controls_Manager::TEXTAREA,
            'default'     => '',
            'condition'   => [ 'field_type' => 'select' ],
            'description' => __( 'Format: value|Label (e.g. 1-2|1–2 Guests)', 'ocean-charter' ),
        ] );
        $repeater->add_control( 'field_icon', [
            'label'   => __( 'Icon', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => 'none',
            'options' => [
                'none'     => __( 'None', 'ocean-charter' ),
                'pin'      => __( 'Location Pin', 'ocean-charter' ),
                'calendar' => __( 'Calendar', 'ocean-charter' ),
                'users'    => __( 'Users/Guests', 'ocean-charter' ),
                'search'   => __( 'Search', 'ocean-charter' ),
                'ship'     => __( 'Ship', 'ocean-charter' ),
                'price'    => __( 'Price/Dollar', 'ocean-charter' ),
            ],
        ] );

        $this->add_control( 'fields', [
            'label'       => __( 'Fields', 'ocean-charter' ),
            'type'        => \Elementor\Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'default'     => [
                [ 'field_label' => 'Destination', 'field_type' => 'text',   'field_name' => 'destination', 'field_placeholder' => 'Where to?',     'field_icon' => 'pin' ],
                [ 'field_label' => 'Dates',       'field_type' => 'date',   'field_name' => 'dates',       'field_placeholder' => 'Select dates',  'field_icon' => 'calendar' ],
                [ 'field_label' => 'Guests',      'field_type' => 'select', 'field_name' => 'guests',      'field_placeholder' => '',              'field_icon' => 'users', 'field_options' => "1-2|1–2 Guests\n2-4|2–4 Guests\n5-8|5–8 Guests\n9+|9+ Guests" ],
            ],
            'title_field' => '{{{ field_label }}}',
        ] );
        $this->end_controls_section();

        /* ── Content: Button & Action ─────────────────────── */
        $this->start_controls_section( 'content_action', [ 'label' => __( 'Button & Action', 'ocean-charter' ) ] );
        $this->add_control( 'btn_label',  [ 'label' => __( 'Button Text', 'ocean-charter' ),  'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Search Fleet' ] );
        $this->add_control( 'action_url', [ 'label' => __( 'Form Action URL', 'ocean-charter' ), 'type' => \Elementor\Controls_Manager::URL, 'default' => [ 'url' => '/fleet/' ] ] );
        $this->add_control( 'show_btn_icon', [
            'label'        => __( 'Show Search Icon', 'ocean-charter' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ] );
        $this->end_controls_section();

        /* ── Style: Form Container ────────────────────────── */
        $this->start_controls_section( 'style_form', [
            'label' => __( 'Form Container', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_control( 'form_bg', [
            'label'     => __( 'Background', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => 'rgba(26,34,51,0.75)',
            'selectors' => [ '{{WRAPPER}} .oc-sf' => 'background: {{VALUE}};' ],
        ] );
        $this->add_control( 'form_border_color', [
            'label'     => __( 'Border Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => 'rgba(217,178,48,0.12)',
            'selectors' => [ '{{WRAPPER}} .oc-sf' => 'border-color: {{VALUE}};' ],
        ] );
        $this->add_responsive_control( 'form_border_radius', [
            'label'      => __( 'Border Radius', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'rem' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
            'default'    => [ 'size' => 16, 'unit' => 'px' ],
            'selectors'  => [ '{{WRAPPER}} .oc-sf' => 'border-radius: {{SIZE}}{{UNIT}};' ],
        ] );
        $this->add_responsive_control( 'form_padding', [
            'label'      => __( 'Padding', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'rem' ],
            'selectors'  => [ '{{WRAPPER}} .oc-sf' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );
        $this->add_responsive_control( 'form_max_width', [
            'label'      => __( 'Max Width', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', '%' ],
            'range'      => [ 'px' => [ 'min' => 300, 'max' => 1400 ] ],
            'selectors'  => [ '{{WRAPPER}} .oc-sf' => 'max-width: {{SIZE}}{{UNIT}};' ],
        ] );
        $this->end_controls_section();

        /* ── Style: Labels ────────────────────────────────── */
        $this->start_controls_section( 'style_labels', [
            'label' => __( 'Field Labels', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_control( 'label_color', [
            'label'     => __( 'Label Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#d9b230',
            'selectors' => [ '{{WRAPPER}} .oc-sf__label' => 'color: {{VALUE}};' ],
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'label_typography',
            'label'    => __( 'Label Typography', 'ocean-charter' ),
            'selector' => '{{WRAPPER}} .oc-sf__label',
        ] );
        $this->end_controls_section();

        /* ── Style: Inputs ────────────────────────────────── */
        $this->start_controls_section( 'style_inputs', [
            'label' => __( 'Input Fields', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_control( 'input_color', [
            'label'     => __( 'Text Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#f0ece3',
            'selectors' => [ '{{WRAPPER}} .oc-sf__input, {{WRAPPER}} .oc-sf__select' => 'color: {{VALUE}};' ],
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'input_typography',
            'label'    => __( 'Input Typography', 'ocean-charter' ),
            'selector' => '{{WRAPPER}} .oc-sf__input, {{WRAPPER}} .oc-sf__select',
        ] );
        $this->add_control( 'divider_color', [
            'label'     => __( 'Divider Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => 'rgba(255,255,255,0.1)',
            'selectors' => [ '{{WRAPPER}} .oc-sf__divider' => 'background: {{VALUE}};' ],
        ] );
        $this->end_controls_section();

        /* ── Style: Button ────────────────────────────────── */
        $this->start_controls_section( 'style_button', [
            'label' => __( 'Search Button', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_control( 'btn_bg', [
            'label'     => __( 'Background', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#d9b230',
            'selectors' => [ '{{WRAPPER}} .oc-sf__btn' => 'background: {{VALUE}};' ],
        ] );
        $this->add_control( 'btn_color', [
            'label'     => __( 'Text Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#0a0f1a',
            'selectors' => [ '{{WRAPPER}} .oc-sf__btn' => 'color: {{VALUE}};' ],
        ] );
        $this->add_responsive_control( 'btn_border_radius', [
            'label'      => __( 'Border Radius', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
            'default'    => [ 'size' => 12, 'unit' => 'px' ],
            'selectors'  => [ '{{WRAPPER}} .oc-sf__btn' => 'border-radius: {{SIZE}}{{UNIT}};' ],
        ] );
        $this->add_responsive_control( 'btn_padding', [
            'label'      => __( 'Padding', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'rem' ],
            'selectors'  => [ '{{WRAPPER}} .oc-sf__btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'btn_typography',
            'label'    => __( 'Typography', 'ocean-charter' ),
            'selector' => '{{WRAPPER}} .oc-sf__btn',
        ] );
        $this->end_controls_section();
    }

    protected function render() {
        $s          = $this->get_settings_for_display();
        $fields     = $s['fields'] ?? [];
        $action_url = ! empty( $s['action_url']['url'] ) ? $s['action_url']['url'] : '/fleet/';

        $icon_map = [
            'pin'      => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>',
            'calendar' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>',
            'users'    => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
            'search'   => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>',
            'ship'     => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 21c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.5 0 2.5 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/><path d="M19.38 20A11.6 11.6 0 0 0 21 14l-9-4-9 4c0 2.9.94 5.34 2.81 7.76"/><path d="M19 13V7a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v6"/><path d="M12 1v4"/></svg>',
            'price'    => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>',
        ];
        ?>
        <style>
        .oc-sf{display:grid;grid-template-columns:<?php
            $col_count = count( $fields );
            $cols = [];
            for ( $i = 0; $i < $col_count; $i++ ) {
                $cols[] = '1fr';
                if ( $i < $col_count - 1 ) $cols[] = 'auto';
            }
            $cols[] = 'auto'; // divider before button
            $cols[] = 'auto'; // button
            echo implode( ' ', $cols );
        ?>;align-items:stretch;background:rgba(26,34,51,.75);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border:1px solid rgba(217,178,48,.12);border-radius:1rem;overflow:hidden;width:100%;padding:.5rem;box-shadow:0 8px 32px rgba(0,0,0,.4)}
        .oc-sf__field{padding:.5rem 1.25rem;display:flex;flex-direction:column;gap:.35rem;cursor:text;min-width:0}
        .oc-sf__label{font-size:.6rem;font-weight:800;letter-spacing:.16em;text-transform:uppercase;color:var(--primary,#d9b230);white-space:nowrap}
        .oc-sf__input-wrap{display:flex;align-items:center;gap:.5rem}
        .oc-sf__icon{flex-shrink:0;color:rgba(240,236,227,.45);display:flex}
        .oc-sf__input{background:none;border:none;outline:none;font-size:.9375rem;color:var(--text,#f0ece3);width:100%;font-family:inherit;padding:0}
        .oc-sf__input::placeholder{color:rgba(240,236,227,.5)}
        .oc-sf__select{background:none;border:none;outline:none;font-size:.9375rem;color:var(--text,#f0ece3);width:100%;font-family:inherit;cursor:pointer;appearance:none;-webkit-appearance:none;padding:0}
        .oc-sf__select option{background:#0a101a;color:#f0ece3}
        .oc-sf__chevron{flex-shrink:0;color:rgba(240,236,227,.45);pointer-events:none;display:flex}
        .oc-sf__divider{width:1px;background:rgba(255,255,255,.1);align-self:center;height:36px;flex-shrink:0}
        .oc-sf__btn{display:flex;align-items:center;gap:.625rem;background:var(--primary,#d9b230);color:#0a0f1a;border:none;padding:.875rem 1.75rem;font-size:.875rem;font-weight:800;letter-spacing:.04em;cursor:pointer;white-space:nowrap;font-family:inherit;flex-shrink:0;transition:opacity .2s;border-radius:12px}
        .oc-sf__btn:hover{opacity:.88}
        @media(max-width:768px){.oc-sf{grid-template-columns:1fr!important;max-width:480px;border-radius:.875rem;padding:.75rem}.oc-sf__divider{width:100%;height:1px}.oc-sf__btn{justify-content:center;padding:1rem 1.5rem;border-radius:.75rem}}
        @media(max-width:480px){.oc-sf{max-width:100%}}
        </style>
        <form class="oc-sf" action="<?php echo esc_url( $action_url ); ?>" method="get">
        <?php
        foreach ( $fields as $idx => $field ) {
            $type  = $field['field_type'] ?? 'text';
            $name  = sanitize_title( $field['field_name'] ?? 'field' );
            $label = $field['field_label'] ?? '';
            $ph    = $field['field_placeholder'] ?? '';
            $icon  = $field['field_icon'] ?? 'none';
            ?>
            <div class="oc-sf__field">
                <span class="oc-sf__label"><?php echo esc_html( $label ); ?></span>
                <div class="oc-sf__input-wrap">
                    <?php if ( $icon !== 'none' && isset( $icon_map[ $icon ] ) ) : ?>
                        <span class="oc-sf__icon"><?php echo $icon_map[ $icon ]; ?></span>
                    <?php endif; ?>
                    <?php if ( $type === 'select' ) : ?>
                        <?php
                        $options = [];
                        $raw = $field['field_options'] ?? '';
                        foreach ( explode( "\n", $raw ) as $line ) {
                            $line = trim( $line );
                            if ( ! $line ) continue;
                            if ( strpos( $line, '|' ) !== false ) {
                                list( $val, $lbl ) = explode( '|', $line, 2 );
                                $options[ trim( $val ) ] = trim( $lbl );
                            } else {
                                $options[ $line ] = $line;
                            }
                        }
                        ?>
                        <select class="oc-sf__select" name="<?php echo esc_attr( $name ); ?>">
                            <?php foreach ( $options as $val => $lbl ) : ?>
                                <option value="<?php echo esc_attr( $val ); ?>"><?php echo esc_html( $lbl ); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="oc-sf__chevron"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg></span>
                    <?php elseif ( $type === 'date' ) : ?>
                        <input class="oc-sf__input" type="date" name="<?php echo esc_attr( $name ); ?>" placeholder="<?php echo esc_attr( $ph ); ?>">
                    <?php else : ?>
                        <input class="oc-sf__input" type="text" name="<?php echo esc_attr( $name ); ?>" placeholder="<?php echo esc_attr( $ph ); ?>">
                    <?php endif; ?>
                </div>
            </div>
            <?php if ( $idx < count( $fields ) - 1 ) : ?>
                <div class="oc-sf__divider"></div>
            <?php endif; ?>
            <?php
        }
        ?>
        <div class="oc-sf__divider"></div>
        <button class="oc-sf__btn" type="submit">
            <?php if ( ( $s['show_btn_icon'] ?? 'yes' ) === 'yes' ) : ?>
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <?php endif; ?>
            <?php echo esc_html( $s['btn_label'] ?? 'Search Fleet' ); ?>
        </button>
        </form>
        <?php
    }
}

/* ============================================================
   14. OC Contact Form Widget (with Signature Pad)
   ============================================================ */
class OC_Contact_Form_Widget extends \Elementor\Widget_Base {
    use OC_Widget_Style_Trait;

    public function get_name()       { return 'oc-contact-form'; }
    public function get_title()      { return __( 'OC Contact Form', 'ocean-charter' ); }
    public function get_icon()       { return 'eicon-form-horizontal'; }
    public function get_categories() { return [ 'ocean-charter' ]; }

    protected function register_controls() {

        /* ── Content: Form Settings ──────────────────────────── */
        $this->start_controls_section( 'section_form', [ 'label' => __( 'Form Settings', 'ocean-charter' ) ] );

        $this->add_control( 'form_heading', [
            'label'   => __( 'Form Heading', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'Get in Touch', 'ocean-charter' ),
        ] );

        $this->add_control( 'form_description', [
            'label' => __( 'Form Description', 'ocean-charter' ),
            'type'  => \Elementor\Controls_Manager::TEXTAREA,
        ] );

        $this->add_control( 'email_recipient', [
            'label'       => __( 'Recipient Email', 'ocean-charter' ),
            'type'        => \Elementor\Controls_Manager::TEXT,
            'default'     => get_option( 'admin_email' ),
            'description' => __( 'Email address to receive form submissions.', 'ocean-charter' ),
        ] );

        $this->add_control( 'success_message', [
            'label'   => __( 'Success Message', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'Thank you! We\'ll be in touch shortly.', 'ocean-charter' ),
        ] );

        $this->add_control( 'submit_text', [
            'label'   => __( 'Submit Button Text', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'Send Message', 'ocean-charter' ),
        ] );

        $this->add_control( 'show_signature', [
            'label'        => __( 'Show Signature Pad', 'ocean-charter' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ] );

        $this->end_controls_section();

        /* ── Content: Form Fields (Repeater) ─────────────────── */
        $this->start_controls_section( 'section_fields', [ 'label' => __( 'Form Fields', 'ocean-charter' ) ] );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control( 'field_label', [
            'label'   => __( 'Label', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'Field', 'ocean-charter' ),
        ] );

        $repeater->add_control( 'field_type', [
            'label'   => __( 'Type', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => 'text',
            'options' => [
                'text'     => __( 'Text', 'ocean-charter' ),
                'email'    => __( 'Email', 'ocean-charter' ),
                'tel'      => __( 'Phone', 'ocean-charter' ),
                'textarea' => __( 'Textarea', 'ocean-charter' ),
                'select'   => __( 'Select', 'ocean-charter' ),
                'date'     => __( 'Date', 'ocean-charter' ),
                'number'   => __( 'Number', 'ocean-charter' ),
                'hidden'   => __( 'Hidden', 'ocean-charter' ),
            ],
        ] );

        $repeater->add_control( 'field_name', [
            'label'       => __( 'Field Name', 'ocean-charter' ),
            'type'        => \Elementor\Controls_Manager::TEXT,
            'description' => __( 'HTML name attribute (no spaces).', 'ocean-charter' ),
        ] );

        $repeater->add_control( 'field_placeholder', [
            'label' => __( 'Placeholder', 'ocean-charter' ),
            'type'  => \Elementor\Controls_Manager::TEXT,
        ] );

        $repeater->add_control( 'field_options', [
            'label'       => __( 'Options (one per line)', 'ocean-charter' ),
            'type'        => \Elementor\Controls_Manager::TEXTAREA,
            'condition'   => [ 'field_type' => 'select' ],
            'description' => __( 'One option per line for select dropdowns.', 'ocean-charter' ),
        ] );

        $repeater->add_control( 'field_required', [
            'label'        => __( 'Required', 'ocean-charter' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'return_value' => 'yes',
        ] );

        $repeater->add_control( 'field_width', [
            'label'   => __( 'Width', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => 'full',
            'options' => [
                'full' => __( 'Full Width', 'ocean-charter' ),
                'half' => __( 'Half Width', 'ocean-charter' ),
            ],
        ] );

        $this->add_control( 'form_fields', [
            'label'       => __( 'Fields', 'ocean-charter' ),
            'type'        => \Elementor\Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'title_field' => '{{{ field_label }}}',
            'default'     => [
                [
                    'field_label'       => __( 'Name', 'ocean-charter' ),
                    'field_type'        => 'text',
                    'field_name'        => 'name',
                    'field_placeholder' => __( 'Your full name', 'ocean-charter' ),
                    'field_required'    => 'yes',
                    'field_width'       => 'half',
                ],
                [
                    'field_label'       => __( 'Email', 'ocean-charter' ),
                    'field_type'        => 'email',
                    'field_name'        => 'email',
                    'field_placeholder' => __( 'your@email.com', 'ocean-charter' ),
                    'field_required'    => 'yes',
                    'field_width'       => 'half',
                ],
                [
                    'field_label'       => __( 'Phone', 'ocean-charter' ),
                    'field_type'        => 'tel',
                    'field_name'        => 'phone',
                    'field_placeholder' => __( '+1 (555) 000-0000', 'ocean-charter' ),
                    'field_required'    => '',
                    'field_width'       => 'half',
                ],
                [
                    'field_label'       => __( 'Subject', 'ocean-charter' ),
                    'field_type'        => 'text',
                    'field_name'        => 'subject',
                    'field_placeholder' => __( 'How can we help?', 'ocean-charter' ),
                    'field_required'    => '',
                    'field_width'       => 'half',
                ],
                [
                    'field_label'       => __( 'Message', 'ocean-charter' ),
                    'field_type'        => 'textarea',
                    'field_name'        => 'message',
                    'field_placeholder' => __( 'Tell us about your inquiry...', 'ocean-charter' ),
                    'field_required'    => 'yes',
                    'field_width'       => 'full',
                ],
            ],
        ] );

        $this->end_controls_section();

        /* ── Style: Form Container ───────────────────────────── */
        $this->start_controls_section( 'style_form_container', [
            'label' => __( 'Form Container', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_control( 'form_bg', [
            'label'     => __( 'Background', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#111a28',
            'selectors' => [ '{{WRAPPER}} .oc-cf' => 'background: {{VALUE}};' ],
        ] );
        $this->add_responsive_control( 'form_padding', [
            'label'      => __( 'Padding', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em', 'rem' ],
            'selectors'  => [ '{{WRAPPER}} .oc-cf' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );
        $this->add_control( 'form_border_radius', [
            'label'      => __( 'Border Radius', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
            'selectors'  => [ '{{WRAPPER}} .oc-cf' => 'border-radius: {{SIZE}}{{UNIT}};' ],
        ] );
        $this->add_group_control( \Elementor\Group_Control_Border::get_type(), [
            'name'     => 'form_border',
            'label'    => __( 'Border', 'ocean-charter' ),
            'selector' => '{{WRAPPER}} .oc-cf',
        ] );
        $this->end_controls_section();

        /* ── Style: Field Labels ─────────────────────────────── */
        $this->start_controls_section( 'style_labels', [
            'label' => __( 'Field Labels', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'label_typography',
            'selector' => '{{WRAPPER}} .oc-cf__label',
        ] );
        $this->add_control( 'label_color', [
            'label'     => __( 'Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#8a9bb0',
            'selectors' => [ '{{WRAPPER}} .oc-cf__label' => 'color: {{VALUE}};' ],
        ] );
        $this->end_controls_section();

        /* ── Style: Input Fields ─────────────────────────────── */
        $this->start_controls_section( 'style_inputs', [
            'label' => __( 'Input Fields', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_control( 'input_bg', [
            'label'     => __( 'Background', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .oc-cf__input, {{WRAPPER}} .oc-cf__textarea, {{WRAPPER}} .oc-cf__select' => 'background: {{VALUE}};' ],
        ] );
        $this->add_control( 'input_text_color', [
            'label'     => __( 'Text Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .oc-cf__input, {{WRAPPER}} .oc-cf__textarea, {{WRAPPER}} .oc-cf__select' => 'color: {{VALUE}};' ],
        ] );
        $this->add_group_control( \Elementor\Group_Control_Border::get_type(), [
            'name'     => 'input_border',
            'selector' => '{{WRAPPER}} .oc-cf__input, {{WRAPPER}} .oc-cf__textarea, {{WRAPPER}} .oc-cf__select',
        ] );
        $this->add_control( 'input_border_radius', [
            'label'      => __( 'Border Radius', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'selectors'  => [ '{{WRAPPER}} .oc-cf__input, {{WRAPPER}} .oc-cf__textarea, {{WRAPPER}} .oc-cf__select' => 'border-radius: {{SIZE}}{{UNIT}};' ],
        ] );
        $this->add_responsive_control( 'input_padding', [
            'label'      => __( 'Padding', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em' ],
            'selectors'  => [ '{{WRAPPER}} .oc-cf__input, {{WRAPPER}} .oc-cf__textarea, {{WRAPPER}} .oc-cf__select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );
        $this->add_control( 'input_focus_border_color', [
            'label'     => __( 'Focus Border Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#d9b230',
            'selectors' => [ '{{WRAPPER}} .oc-cf__input:focus, {{WRAPPER}} .oc-cf__textarea:focus, {{WRAPPER}} .oc-cf__select:focus' => 'border-color: {{VALUE}};' ],
        ] );
        $this->end_controls_section();

        /* ── Style: Submit Button ────────────────────────────── */
        $this->start_controls_section( 'style_submit', [
            'label' => __( 'Submit Button', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_control( 'submit_bg', [
            'label'     => __( 'Background', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#d9b230',
            'selectors' => [ '{{WRAPPER}} .oc-cf__submit' => 'background: {{VALUE}};' ],
        ] );
        $this->add_control( 'submit_text_color', [
            'label'     => __( 'Text Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#0a101a',
            'selectors' => [ '{{WRAPPER}} .oc-cf__submit' => 'color: {{VALUE}};' ],
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'submit_typography',
            'selector' => '{{WRAPPER}} .oc-cf__submit',
        ] );
        $this->add_responsive_control( 'submit_padding', [
            'label'      => __( 'Padding', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em' ],
            'selectors'  => [ '{{WRAPPER}} .oc-cf__submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );
        $this->add_control( 'submit_border_radius', [
            'label'      => __( 'Border Radius', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'selectors'  => [ '{{WRAPPER}} .oc-cf__submit' => 'border-radius: {{SIZE}}{{UNIT}};' ],
        ] );
        $this->add_control( 'submit_hover_bg', [
            'label'     => __( 'Hover Background', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#c4a02a',
            'selectors' => [ '{{WRAPPER}} .oc-cf__submit:hover' => 'background: {{VALUE}};' ],
        ] );
        $this->end_controls_section();

        /* ── Style: Signature Pad ────────────────────────────── */
        $this->start_controls_section( 'style_signature', [
            'label'     => __( 'Signature Pad', 'ocean-charter' ),
            'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => [ 'show_signature' => 'yes' ],
        ] );
        $this->add_control( 'sig_bg', [
            'label'     => __( 'Background', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .oc-cf__sig-canvas' => 'background: {{VALUE}};' ],
        ] );
        $this->add_group_control( \Elementor\Group_Control_Border::get_type(), [
            'name'     => 'sig_border',
            'selector' => '{{WRAPPER}} .oc-cf__sig-canvas',
        ] );
        $this->add_control( 'sig_border_radius', [
            'label'      => __( 'Border Radius', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'selectors'  => [ '{{WRAPPER}} .oc-cf__sig-canvas' => 'border-radius: {{SIZE}}{{UNIT}};' ],
        ] );
        $this->add_control( 'sig_height', [
            'label'      => __( 'Height', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 80, 'max' => 400 ] ],
            'default'    => [ 'size' => 150, 'unit' => 'px' ],
            'selectors'  => [ '{{WRAPPER}} .oc-cf__sig-canvas' => 'height: {{SIZE}}{{UNIT}};' ],
        ] );
        $this->end_controls_section();
    }

    protected function render() {
        $s      = $this->get_settings_for_display();
        $fields = $s['form_fields'] ?? [];
        $uid    = 'oc-cf-' . $this->get_id();
        ?>
        <style>
            .oc-cf {
                background: #111a28;
                padding: 40px;
                border-radius: 16px;
                border: 1px solid rgba(255,255,255,.06);
                max-width: 720px;
                margin: 0 auto;
            }
            .oc-cf__heading {
                font-family: 'Playfair Display', serif;
                font-size: 28px;
                color: #f0ece3;
                margin: 0 0 8px;
            }
            .oc-cf__desc {
                color: #8a9bb0;
                font-size: 15px;
                margin: 0 0 28px;
                line-height: 1.6;
            }
            .oc-cf__grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 18px;
            }
            .oc-cf__field { display: flex; flex-direction: column; }
            .oc-cf__field--full  { grid-column: span 2; }
            .oc-cf__field--half  { grid-column: span 1; }
            .oc-cf__label {
                font-size: 13px;
                font-weight: 500;
                color: #8a9bb0;
                margin-bottom: 6px;
                text-transform: uppercase;
                letter-spacing: .5px;
            }
            .oc-cf__input,
            .oc-cf__textarea,
            .oc-cf__select {
                width: 100%;
                background: rgba(255,255,255,.05);
                border: 1px solid rgba(255,255,255,.1);
                border-radius: 8px;
                padding: 12px 14px;
                color: #f0ece3;
                font-size: 15px;
                font-family: 'Inter', sans-serif;
                outline: none;
                transition: border-color .25s;
                box-sizing: border-box;
            }
            .oc-cf__textarea { resize: vertical; min-height: 100px; }
            .oc-cf__select { appearance: none; cursor: pointer; }
            .oc-cf__input:focus,
            .oc-cf__textarea:focus,
            .oc-cf__select:focus {
                border-color: #d9b230;
            }
            .oc-cf__input::placeholder,
            .oc-cf__textarea::placeholder {
                color: rgba(255,255,255,.25);
            }
            .oc-cf__sig-wrap {
                grid-column: span 2;
                margin-top: 4px;
            }
            .oc-cf__sig-label {
                font-size: 13px;
                font-weight: 500;
                color: #8a9bb0;
                margin-bottom: 6px;
                text-transform: uppercase;
                letter-spacing: .5px;
            }
            .oc-cf__sig-canvas {
                width: 100%;
                height: 150px;
                border: 1px dashed rgba(255,255,255,.2);
                border-radius: 8px;
                background: rgba(0,0,0,.2);
                cursor: crosshair;
                touch-action: none;
                display: block;
            }
            .oc-cf__sig-clear {
                margin-top: 8px;
                background: none;
                border: 1px solid rgba(255,255,255,.15);
                color: #8a9bb0;
                padding: 6px 14px;
                border-radius: 6px;
                cursor: pointer;
                font-size: 13px;
                transition: color .2s, border-color .2s;
            }
            .oc-cf__sig-clear:hover { color: #f0ece3; border-color: rgba(255,255,255,.3); }
            .oc-cf__submit-wrap { grid-column: span 2; margin-top: 8px; }
            .oc-cf__submit {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 100%;
                padding: 14px 32px;
                background: #d9b230;
                color: #0a101a;
                font-family: 'Inter', sans-serif;
                font-size: 15px;
                font-weight: 600;
                border: none;
                border-radius: 8px;
                cursor: pointer;
                transition: background .25s, opacity .25s;
                letter-spacing: .3px;
            }
            .oc-cf__submit:hover { background: #c4a02a; }
            .oc-cf__submit:disabled { opacity: .6; cursor: not-allowed; }
            .oc-cf__msg {
                grid-column: span 2;
                padding: 14px 18px;
                border-radius: 8px;
                font-size: 14px;
                display: none;
            }
            .oc-cf__msg--success {
                background: rgba(34,197,94,.12);
                color: #22c55e;
                border: 1px solid rgba(34,197,94,.25);
                display: block;
            }
            .oc-cf__msg--error {
                background: rgba(239,68,68,.12);
                color: #ef4444;
                border: 1px solid rgba(239,68,68,.25);
                display: block;
            }
            @media (max-width: 768px) {
                .oc-cf { padding: 28px 20px; max-width: 100%; }
                .oc-cf__heading { font-size: 24px; }
                .oc-cf__sig-canvas { height: 120px; }
            }
            @media (max-width: 600px) {
                .oc-cf { padding: 24px 18px; }
                .oc-cf__grid { grid-template-columns: 1fr; }
                .oc-cf__field--half,
                .oc-cf__field--full,
                .oc-cf__sig-wrap,
                .oc-cf__submit-wrap,
                .oc-cf__msg { grid-column: span 1; }
            }
            @media (max-width: 480px) {
                .oc-cf { padding: 18px 14px; }
                .oc-cf__heading { font-size: 20px; }
                .oc-cf__label { font-size: 10px; }
                .oc-cf__input, .oc-cf__textarea { font-size: 14px; padding: 10px 12px; }
                .oc-cf__sig-canvas { height: 100px; }
            }
        </style>

        <div class="oc-cf" id="<?php echo esc_attr( $uid ); ?>">
            <?php if ( ! empty( $s['form_heading'] ) ) : ?>
                <h2 class="oc-cf__heading"><?php echo esc_html( $s['form_heading'] ); ?></h2>
            <?php endif; ?>
            <?php if ( ! empty( $s['form_description'] ) ) : ?>
                <p class="oc-cf__desc"><?php echo esc_html( $s['form_description'] ); ?></p>
            <?php endif; ?>

            <form class="oc-cf__form" novalidate>
                <?php wp_nonce_field( 'oc_contact_form', 'oc_cf_nonce' ); ?>
                <div class="oc-cf__grid">
                    <?php foreach ( $fields as $i => $field ) :
                        $type     = $field['field_type']        ?: 'text';
                        $name     = $field['field_name']        ?: 'field_' . $i;
                        $label    = $field['field_label']       ?: '';
                        $ph       = $field['field_placeholder'] ?: '';
                        $req      = $field['field_required'] === 'yes';
                        $width_cl = $field['field_width'] === 'half' ? 'oc-cf__field--half' : 'oc-cf__field--full';

                        if ( $type === 'hidden' ) : ?>
                            <input type="hidden" name="<?php echo esc_attr( $name ); ?>" value="">
                        <?php continue; endif; ?>

                        <div class="oc-cf__field <?php echo esc_attr( $width_cl ); ?>">
                            <label class="oc-cf__label">
                                <?php echo esc_html( $label ); ?>
                                <?php if ( $req ) echo ' *'; ?>
                            </label>

                            <?php if ( $type === 'textarea' ) : ?>
                                <textarea class="oc-cf__textarea" name="<?php echo esc_attr( $name ); ?>" placeholder="<?php echo esc_attr( $ph ); ?>" rows="5" <?php if ( $req ) echo 'required'; ?>></textarea>

                            <?php elseif ( $type === 'select' ) :
                                $options = array_filter( array_map( 'trim', explode( "\n", $field['field_options'] ?? '' ) ) ); ?>
                                <select class="oc-cf__select" name="<?php echo esc_attr( $name ); ?>" <?php if ( $req ) echo 'required'; ?>>
                                    <option value=""><?php echo esc_html( $ph ?: __( 'Select...', 'ocean-charter' ) ); ?></option>
                                    <?php foreach ( $options as $opt ) : ?>
                                        <option value="<?php echo esc_attr( $opt ); ?>"><?php echo esc_html( $opt ); ?></option>
                                    <?php endforeach; ?>
                                </select>

                            <?php else : ?>
                                <input class="oc-cf__input" type="<?php echo esc_attr( $type ); ?>" name="<?php echo esc_attr( $name ); ?>" placeholder="<?php echo esc_attr( $ph ); ?>" <?php if ( $req ) echo 'required'; ?>>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>

                    <?php if ( $s['show_signature'] === 'yes' ) : ?>
                        <!-- Signature pad -->
                        <div class="oc-cf__sig-wrap">
                            <div class="oc-cf__sig-label"><?php esc_html_e( 'Signature', 'ocean-charter' ); ?></div>
                            <canvas class="oc-cf__sig-canvas" id="<?php echo esc_attr( $uid ); ?>-sig"></canvas>
                            <button type="button" class="oc-cf__sig-clear"><?php esc_html_e( 'Clear Signature', 'ocean-charter' ); ?></button>
                            <input type="hidden" name="signature_data" class="oc-cf__sig-data">
                        </div>
                    <?php endif; ?>

                    <div class="oc-cf__msg" id="<?php echo esc_attr( $uid ); ?>-msg"></div>

                    <div class="oc-cf__submit-wrap">
                        <button type="submit" class="oc-cf__submit">
                            <?php echo esc_html( $s['submit_text'] ?: 'Send Message' ); ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <script>
        (function(){
            var wrap = document.getElementById('<?php echo esc_js( $uid ); ?>');
            if (!wrap) return;

            /* ── Signature Pad ────────────────────────────────── */
            var canvas = document.getElementById('<?php echo esc_js( $uid ); ?>-sig');
            if (canvas) {
                var ctx = canvas.getContext('2d');
                var drawing = false;
                var hasSignature = false;

                function resizeCanvas() {
                    var rect = canvas.getBoundingClientRect();
                    canvas.width  = rect.width;
                    canvas.height = rect.height;
                }
                resizeCanvas();
                window.addEventListener('resize', resizeCanvas);

                function getPos(e) {
                    var rect = canvas.getBoundingClientRect();
                    var t = e.touches ? e.touches[0] : e;
                    return { x: t.clientX - rect.left, y: t.clientY - rect.top };
                }

                function startDraw(e) {
                    e.preventDefault();
                    drawing = true;
                    hasSignature = true;
                    var p = getPos(e);
                    ctx.beginPath();
                    ctx.moveTo(p.x, p.y);
                }

                function draw(e) {
                    if (!drawing) return;
                    e.preventDefault();
                    var p = getPos(e);
                    ctx.strokeStyle = '#f0ece3';
                    ctx.lineWidth   = 2;
                    ctx.lineCap     = 'round';
                    ctx.lineJoin    = 'round';
                    ctx.lineTo(p.x, p.y);
                    ctx.stroke();
                }

                function stopDraw(e) {
                    if (drawing) { e.preventDefault(); }
                    drawing = false;
                }

                canvas.addEventListener('mousedown',  startDraw);
                canvas.addEventListener('mousemove',  draw);
                canvas.addEventListener('mouseup',    stopDraw);
                canvas.addEventListener('mouseleave', stopDraw);
                canvas.addEventListener('touchstart', startDraw, { passive: false });
                canvas.addEventListener('touchmove',  draw, { passive: false });
                canvas.addEventListener('touchend',   stopDraw);
                canvas.addEventListener('touchcancel', stopDraw);

                wrap.querySelector('.oc-cf__sig-clear').addEventListener('click', function() {
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    hasSignature = false;
                });
            }

            /* ── AJAX Submit ──────────────────────────────────── */
            var form   = wrap.querySelector('.oc-cf__form');
            var msgBox = document.getElementById('<?php echo esc_js( $uid ); ?>-msg');
            var btn    = wrap.querySelector('.oc-cf__submit');

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // Signature → hidden field
                if (canvas && hasSignature) {
                    wrap.querySelector('.oc-cf__sig-data').value = canvas.toDataURL('image/png');
                }

                btn.disabled = true;
                btn.textContent = '<?php echo esc_js( __( 'Sending...', 'ocean-charter' ) ); ?>';
                msgBox.className = 'oc-cf__msg';
                msgBox.style.display = 'none';

                var fd = new FormData(form);
                fd.append('action', 'oc_contact_form_submit');

                fetch('<?php echo esc_js( admin_url( 'admin-ajax.php' ) ); ?>', {
                    method: 'POST',
                    body: fd,
                    credentials: 'same-origin'
                })
                .then(function(r){ return r.json(); })
                .then(function(data){
                    if (data.success) {
                        msgBox.className = 'oc-cf__msg oc-cf__msg--success';
                        msgBox.textContent = data.data.message || '<?php echo esc_js( $s['success_message'] ); ?>';
                        msgBox.style.display = 'block';
                        form.reset();
                        if (canvas) {
                            ctx.clearRect(0, 0, canvas.width, canvas.height);
                            hasSignature = false;
                        }
                    } else {
                        msgBox.className = 'oc-cf__msg oc-cf__msg--error';
                        msgBox.textContent = data.data.message || '<?php echo esc_js( __( 'Something went wrong. Please try again.', 'ocean-charter' ) ); ?>';
                        msgBox.style.display = 'block';
                    }
                })
                .catch(function(){
                    msgBox.className = 'oc-cf__msg oc-cf__msg--error';
                    msgBox.textContent = '<?php echo esc_js( __( 'Network error. Please try again.', 'ocean-charter' ) ); ?>';
                    msgBox.style.display = 'block';
                })
                .finally(function(){
                    btn.disabled = false;
                    btn.textContent = '<?php echo esc_js( $s['submit_text'] ?: 'Send Message' ); ?>';
                });
            });
        })();
        </script>
        <?php
    }
}


/* OC_Featured_Vessels_Widget and OC_Why_Us_Widget live in elementor-query-widgets.php */

/* ============================================================
   16. OC Destinations Bento Widget
   5-card bento grid: 1 hero (spans 2 rows) + 4 small cards.
   ============================================================ */
class OC_Destinations_Bento_Widget extends \Elementor\Widget_Base {
    use OC_Widget_Style_Trait;

    public function get_name()       { return 'oc-destinations-bento'; }
    public function get_title()      { return __( 'OC Destinations Bento', 'ocean-charter' ); }
    public function get_icon()       { return 'eicon-gallery-masonry'; }
    public function get_categories() { return [ 'ocean-charter' ]; }

    protected function register_controls() {
        $this->start_controls_section( 'content', [ 'label' => __( 'Content', 'ocean-charter' ) ] );
        $this->add_control( 'section_eyebrow', [ 'label' => __( 'Eyebrow', 'ocean-charter' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Where We Sail' ] );
        $this->add_control( 'section_heading', [ 'label' => __( 'Heading', 'ocean-charter' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Dream' ] );
        $this->add_control( 'section_heading_accent', [ 'label' => __( 'Heading Accent', 'ocean-charter' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Destinations' ] );
        $this->add_control( 'destinations', [
            'label'  => __( 'Destinations', 'ocean-charter' ),
            'type'   => \Elementor\Controls_Manager::REPEATER,
            'fields' => [
                [ 'name' => 'image', 'label' => 'Image', 'type' => \Elementor\Controls_Manager::MEDIA ],
                [ 'name' => 'name', 'label' => 'Name', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Mediterranean' ],
                [ 'name' => 'sub', 'label' => 'Subtitle', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Greece · Italy · Croatia' ],
                [ 'name' => 'count', 'label' => 'Badge Text', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => '48 yachts' ],
                [ 'name' => 'url', 'label' => 'Link', 'type' => \Elementor\Controls_Manager::URL, 'default' => [ 'url' => '/fleet/' ] ],
            ],
            'default' => [
                [ 'name' => 'Mediterranean', 'sub' => 'Greece · Italy · Croatia', 'count' => '48 yachts' ],
                [ 'name' => 'Caribbean', 'sub' => 'BVI · St. Barts · Grenadines', 'count' => '32 yachts' ],
                [ 'name' => 'Indian Ocean', 'sub' => 'Maldives · Seychelles · Zanzibar', 'count' => '24 yachts' ],
                [ 'name' => 'Pacific', 'sub' => 'French Polynesia · Fiji · Hawaii', 'count' => '18 yachts' ],
                [ 'name' => 'Northern Europe', 'sub' => 'Norway · Iceland · Scotland', 'count' => '14 yachts' ],
            ],
            'title_field' => '{{{ name }}}',
        ] );
        $this->add_control( 'show_footer_link', [ 'label' => __( 'Show Footer Link', 'ocean-charter' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => 'yes' ] );
        $this->add_control( 'footer_label', [ 'label' => __( 'Footer Link Text', 'ocean-charter' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'All Destinations →', 'condition' => [ 'show_footer_link' => 'yes' ] ] );
        $this->add_control( 'footer_url', [ 'label' => __( 'Footer Link URL', 'ocean-charter' ), 'type' => \Elementor\Controls_Manager::URL, 'default' => [ 'url' => '/destinations/' ], 'condition' => [ 'show_footer_link' => 'yes' ] ] );
        $this->end_controls_section();

        // Style: Heading
        $this->start_controls_section( 'style_db_heading', [ 'label' => __( 'Section Header', 'ocean-charter' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'db_heading_typo', 'selector' => '{{WRAPPER}} .oc-db__heading' ] );
        $this->add_control( 'db_heading_color', [ 'label' => __( 'Color', 'ocean-charter' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .oc-db__heading' => 'color: {{VALUE}};' ] ] );
        $this->end_controls_section();
    }

    protected function render() {
        $s    = $this->get_settings_for_display();
        $hero_img = defined('OC_IMG_HERO_HOME') ? OC_IMG_HERO_HOME : '';
        ?>
        <div class="oc-db">
          <div class="oc-section-header">
            <?php if ( ! empty( $s['section_eyebrow'] ) ) : ?><span class="oc-caption" data-animate><?php echo esc_html( $s['section_eyebrow'] ); ?></span><?php endif; ?>
            <h2 class="oc-db__heading" data-animate data-delay="0.1"><?php echo esc_html( $s['section_heading'] ?? '' ); ?><?php if ( ! empty( $s['section_heading_accent'] ) ) : ?> <span class="text-gold"><?php echo esc_html( $s['section_heading_accent'] ); ?></span><?php endif; ?></h2>
          </div>
          <?php if ( ! empty( $s['destinations'] ) ) : ?>
          <div class="oc-db__grid" data-animate>
            <?php foreach ( $s['destinations'] as $i => $dest ) :
              $img = ! empty( $dest['image']['url'] ) ? $dest['image']['url'] : $hero_img;
              $url = ! empty( $dest['url']['url'] ) ? $dest['url']['url'] : '#';
            ?>
            <a href="<?php echo esc_url( $url ); ?>"
               class="oc-db__card<?php echo $i === 0 ? ' oc-db__card--hero' : ''; ?>"
               data-animate data-delay="<?php echo esc_attr( $i * 0.08 ); ?>">
              <?php if ( $img ) : ?><img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $dest['name'] ); ?>" loading="lazy"><?php endif; ?>
              <div class="oc-db__card-overlay"></div>
              <div class="oc-db__card-content">
                <?php if ( ! empty( $dest['count'] ) ) : ?><span class="oc-db__card-count"><?php echo esc_html( $dest['count'] ); ?></span><?php endif; ?>
                <h3><?php echo esc_html( $dest['name'] ); ?></h3>
                <p><?php echo esc_html( $dest['sub'] ?? '' ); ?></p>
              </div>
            </a>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
          <?php
          $furl = ! empty( $s['footer_url']['url'] ) ? $s['footer_url']['url'] : '/destinations/';
          if ( ( $s['show_footer_link'] ?? '' ) === 'yes' ) : ?>
            <div class="oc-section-footer" data-animate><a href="<?php echo esc_url( $furl ); ?>" class="btn-secondary"><?php echo esc_html( $s['footer_label'] ?? 'All Destinations →' ); ?></a></div>
          <?php endif; ?>
        </div>
        <style>
        .oc-db__grid{display:grid;grid-template-columns:1fr 1fr 1fr;grid-template-rows:260px 260px;gap:1rem;margin-bottom:2.5rem;}
        .oc-db__card{position:relative;border-radius:var(--radius-lg,16px);overflow:hidden;display:block;text-decoration:none;min-height:0;}
        .oc-db__card--hero{grid-row:1 / 3;grid-column:1;}
        .oc-db__card:nth-child(2){grid-row:1;grid-column:2;}
        .oc-db__card:nth-child(3){grid-row:1;grid-column:3;}
        .oc-db__card:nth-child(4){grid-row:2;grid-column:2;}
        .oc-db__card:nth-child(5){grid-row:2;grid-column:3;}
        .oc-db__card img{width:100%;height:100%;object-fit:cover;transition:transform .6s ease;}
        .oc-db__card:hover img{transform:scale(1.05);}
        .oc-db__card-overlay{position:absolute;inset:0;background:linear-gradient(to top,rgba(10,15,26,0.85) 0%,rgba(10,15,26,0.1) 60%);}
        .oc-db__card-content{position:absolute;bottom:0;left:0;right:0;padding:1.25rem 1.5rem;}
        .oc-db__card--hero .oc-db__card-content{padding:2rem;}
        .oc-db__card-count{display:inline-block;font-size:.6875rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--primary,#d9b230);background:rgba(217,178,48,0.15);border:1px solid rgba(217,178,48,0.25);border-radius:9999px;padding:.2rem .7rem;margin-bottom:.5rem;}
        .oc-db__card h3{color:#fff;font-size:1.125rem;margin:0 0 .25rem;}
        .oc-db__card--hero h3{font-size:1.75rem;}
        .oc-db__card p{color:rgba(255,255,255,0.65);font-size:.8125rem;margin:0;}
        .oc-db__card:hover{box-shadow:var(--shadow-glow,0 0 24px rgba(217,178,48,0.15));}
        @media(max-width:1024px){.oc-db__grid{grid-template-columns:1fr 1fr;grid-template-rows:240px 240px 240px;}.oc-db__card--hero{grid-row:1/2;grid-column:1;}.oc-db__card:nth-child(2){grid-row:1;grid-column:2;}.oc-db__card:nth-child(3){grid-row:2;grid-column:1;}.oc-db__card:nth-child(4){grid-row:2;grid-column:2;}.oc-db__card:nth-child(5){grid-row:3;grid-column:1 / 3;}}
        @media(max-width:480px){.oc-db__grid{grid-template-columns:1fr;grid-template-rows:repeat(5,220px);}.oc-db__card,.oc-db__card--hero,.oc-db__card:nth-child(n){grid-row:auto;grid-column:auto;}.oc-db__card--hero h3{font-size:1.375rem;}}
        </style>
        <?php
    }
}

/* ============================================================
   Register all widgets
   ============================================================ */
function oc_register_elementor_widgets( $widgets_manager ) {
    $widgets = [
        'OC_Hero_Widget',
        'OC_Stats_Bar_Widget',
        'OC_Destination_Card_Widget',
        'OC_Package_Card_Widget',
        'OC_Testimonial_Widget',
        'OC_CTA_Strip_Widget',
        'OC_Itinerary_Day_Widget',
        'OC_Service_Card_Widget',
        'OC_Contact_Section_Widget',
        'OC_Itinerary_Sidebar_Widget',
        'OC_Bespoke_Section_Widget',
        'OC_Search_Form_Widget',
        'OC_Contact_Form_Widget',
        'OC_Destinations_Bento_Widget',
    ];
    foreach ( $widgets as $class ) {
        $widgets_manager->register( new $class() );
    }
}
add_action( 'elementor/widgets/register', 'oc_register_elementor_widgets' );
