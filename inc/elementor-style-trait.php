<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Shared Elementor style-control helpers for all Ocean Charter widgets.
 * Usage: add `use OC_Widget_Style_Trait;` inside any Widget_Base subclass.
 */
trait OC_Widget_Style_Trait {

    /* ── Section heading (eyebrow + h2) ─────────────────────── */
    protected function oc_register_heading_style( string $wrapper ) {
        $this->start_controls_section( 'style_heading', [
            'label' => __( 'Section Heading', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_control( 'eyebrow_color', [
            'label'     => __( 'Eyebrow Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#d9b230',
            'selectors' => [ "{{WRAPPER}} {$wrapper} .oc-section-eyebrow" => 'color: {{VALUE}};' ],
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'heading_typography',
            'label'    => __( 'Heading Typography', 'ocean-charter' ),
            'selector' => "{{WRAPPER}} {$wrapper} .oc-section-heading",
        ] );
        $this->add_control( 'heading_color', [
            'label'     => __( 'Heading Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#f0ece3',
            'selectors' => [ "{{WRAPPER}} {$wrapper} .oc-section-heading" => 'color: {{VALUE}};' ],
        ] );
        $this->end_controls_section();
    }

    /* ── Generic card box ────────────────────────────────────── */
    protected function oc_register_card_style( string $card_sel, string $label = 'Card' ) {
        $this->start_controls_section( 'style_card', [
            'label' => __( $label, 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_control( 'card_bg', [
            'label'     => __( 'Background', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#111a28',
            'selectors' => [ "{{WRAPPER}} {$card_sel}" => 'background: {{VALUE}};' ],
        ] );
        $this->add_control( 'card_border_color', [
            'label'     => __( 'Border Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ "{{WRAPPER}} {$card_sel}" => 'border-color: {{VALUE}};' ],
        ] );
        $this->add_control( 'card_radius', [
            'label'      => __( 'Border Radius', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'rem' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
            'selectors'  => [ "{{WRAPPER}} {$card_sel}" => 'border-radius: {{SIZE}}{{UNIT}};' ],
        ] );
        $this->add_responsive_control( 'card_padding', [
            'label'      => __( 'Body Padding', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em', 'rem' ],
            'selectors'  => [ "{{WRAPPER}} {$card_sel} .oc-card-body, {{WRAPPER}} {$card_sel} [class*='__body']" => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );
        $this->add_group_control( \Elementor\Group_Control_Box_Shadow::get_type(), [
            'name'     => 'card_shadow',
            'label'    => __( 'Box Shadow', 'ocean-charter' ),
            'selector' => "{{WRAPPER}} {$card_sel}",
        ] );
        $this->end_controls_section();
    }

    /* ── Card title ──────────────────────────────────────────── */
    protected function oc_register_title_style( string $title_sel, string $label = 'Card Title' ) {
        $this->start_controls_section( 'style_title', [
            'label' => __( $label, 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'title_typography',
            'selector' => "{{WRAPPER}} {$title_sel}",
        ] );
        $this->add_control( 'title_color', [
            'label'     => __( 'Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#f0ece3',
            'selectors' => [ "{{WRAPPER}} {$title_sel}" => 'color: {{VALUE}};' ],
        ] );
        $this->end_controls_section();
    }

    /* ── Excerpt / body text ─────────────────────────────────── */
    protected function oc_register_excerpt_style( string $excerpt_sel ) {
        $this->start_controls_section( 'style_excerpt', [
            'label' => __( 'Body Text', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'excerpt_typography',
            'selector' => "{{WRAPPER}} {$excerpt_sel}",
        ] );
        $this->add_control( 'excerpt_color', [
            'label'     => __( 'Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ "{{WRAPPER}} {$excerpt_sel}" => 'color: {{VALUE}};' ],
        ] );
        $this->end_controls_section();
    }

    /* ── Accent / primary color ──────────────────────────────── */
    protected function oc_register_accent_style( array $accent_sels, string $label = 'Accent Color' ) {
        $this->start_controls_section( 'style_accent', [
            'label' => __( $label, 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_control( 'accent_color', [
            'label'     => __( 'Accent / Gold Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#d9b230',
            'selectors' => $accent_sels,
        ] );
        $this->end_controls_section();
    }

    /* ── CTA Button ──────────────────────────────────────────── */
    protected function oc_register_button_style( string $btn_sel ) {
        $this->start_controls_section( 'style_button', [
            'label' => __( 'Button', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'button_typography',
            'selector' => "{{WRAPPER}} {$btn_sel}",
        ] );
        $this->add_control( 'button_text_color', [
            'label'     => __( 'Text Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ "{{WRAPPER}} {$btn_sel}" => 'color: {{VALUE}};' ],
        ] );
        $this->add_control( 'button_bg', [
            'label'     => __( 'Background', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ "{{WRAPPER}} {$btn_sel}" => 'background: {{VALUE}};' ],
        ] );
        $this->add_control( 'button_border_color', [
            'label'     => __( 'Border Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ "{{WRAPPER}} {$btn_sel}" => 'border-color: {{VALUE}};' ],
        ] );
        $this->add_control( 'button_radius', [
            'label'      => __( 'Border Radius', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'rem' ],
            'default'    => [ 'size' => 9999, 'unit' => 'px' ],
            'selectors'  => [ "{{WRAPPER}} {$btn_sel}" => 'border-radius: {{SIZE}}{{UNIT}};' ],
        ] );
        $this->add_responsive_control( 'button_padding', [
            'label'      => __( 'Padding', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em', 'rem' ],
            'selectors'  => [ "{{WRAPPER}} {$btn_sel}" => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );
        $this->end_controls_section();
    }

    /* ── Grid gap + column count (CSS var) ───────────────────── */
    protected function oc_register_grid_style( string $grid_sel ) {
        $this->start_controls_section( 'style_grid', [
            'label' => __( 'Grid Layout', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_responsive_control( 'grid_gap', [
            'label'      => __( 'Gap Between Cards', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'rem' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 80 ] ],
            'default'    => [ 'size' => 24, 'unit' => 'px' ],
            'selectors'  => [ "{{WRAPPER}} {$grid_sel}" => 'gap: {{SIZE}}{{UNIT}};' ],
        ] );
        $this->end_controls_section();
    }

    /* ── Image (aspect-ratio + border-radius) ────────────────── */
    protected function oc_register_image_style( string $img_wrap_sel ) {
        $this->start_controls_section( 'style_image', [
            'label' => __( 'Image', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_control( 'image_radius', [
            'label'      => __( 'Border Radius', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'rem', '%' ],
            'selectors'  => [ "{{WRAPPER}} {$img_wrap_sel}" => 'border-radius: {{SIZE}}{{UNIT}};' ],
        ] );
        $this->add_responsive_control( 'image_height', [
            'label'      => __( 'Image Height', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'vh' ],
            'range'      => [ 'px' => [ 'min' => 100, 'max' => 800 ], 'vh' => [ 'min' => 10, 'max' => 100 ] ],
            'selectors'  => [ "{{WRAPPER}} {$img_wrap_sel}" => 'aspect-ratio: unset; height: {{SIZE}}{{UNIT}};' ],
        ] );
        $this->end_controls_section();
    }
}
