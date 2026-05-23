# Widget Edit Superpowers — Implementation Plan

> **For agentic workers:** REQUIRED: Use superpowers:subagent-driven-development to implement this plan. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Give every Ocean Charter Elementor widget a full Content / Style / Advanced panel so every visual decision — columns, typography, colors, spacing, backgrounds, border-radius, images, and all text — is editable live in Elementor without touching the back-end.

**Architecture:** Add a shared PHP Trait (`OC_Widget_Style_Trait`) that provides reusable `register_*_style_section()` helper methods, eliminating copy-paste across 25 widgets. Each widget uses the trait, adds its own Content controls (Repeater for Why Us features, SELECT for columns, NUMBER for excerpt length, MEDIA for images), then calls the appropriate trait methods for its Style tab. All style controls use Elementor's native `selectors` array — Elementor injects scoped CSS automatically, no manual `<style>` blocks needed for those properties. Structural layout (column count) uses a CSS custom property applied via `add_render_attribute`. A new Theme Settings admin page exposes global design tokens (primary color, fonts, contact details, social links). Mobile CSS is audited across all widgets and fixed.

**Tech Stack:** PHP 8.2, Elementor 3.x Widget_Base / Controls_Manager / Group_Control_Typography / Group_Control_Background / Group_Control_Border / Group_Control_Box_Shadow / Repeater, WordPress Settings API, CSS custom properties, CSS Grid

---

## Chunk 1: Shared Style Trait + Featured Vessels

### Task 1: Create `inc/elementor-style-trait.php`

**Files:**
- Create: `inc/elementor-style-trait.php`
- Modify: `functions.php` (require the new file before widget files)

- [ ] Create `inc/elementor-style-trait.php` with the following trait. This is the DRY core — every widget calls these helpers instead of duplicating control registration.

```php
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
        $this->add_control( 'image_height', [
            'label'      => __( 'Image Height', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'vh' ],
            'range'      => [ 'px' => [ 'min' => 100, 'max' => 800 ] ],
            'selectors'  => [ "{{WRAPPER}} {$img_wrap_sel}" => 'aspect-ratio: unset; height: {{SIZE}}{{UNIT}};' ],
        ] );
        $this->end_controls_section();
    }
}
```

- [ ] In `functions.php`, add before the existing widget require lines:
```php
require_once get_template_directory() . '/inc/elementor-style-trait.php';
```

- [ ] Verify PHP syntax: `php -l inc/elementor-style-trait.php`

---

### Task 2: Rewrite `OC_Featured_Vessels_Widget` with columns + full Style tab

**Files:**
- Modify: `inc/elementor-query-widgets.php` (class OC_Featured_Vessels_Widget, lines ~1505–1617)

- [ ] Add `use OC_Widget_Style_Trait;` inside the class body (first line after `{`)

- [ ] Replace `register_controls()` with this full version:

```php
protected function register_controls() {
    /* ── CONTENT ── */
    $this->start_controls_section( 'content', [ 'label' => __( 'Content', 'ocean-charter' ) ] );
    $this->add_control( 'posts_count', [ 'label' => __( 'Vessels to Show', 'ocean-charter' ), 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 3, 'min' => 1, 'max' => 12 ] );
    $this->add_control( 'columns', [
        'label'   => __( 'Columns', 'ocean-charter' ),
        'type'    => \Elementor\Controls_Manager::SELECT,
        'default' => '3',
        'options' => [ '1' => '1', '2' => '2', '3' => '3', '4' => '4' ],
    ] );
    $this->add_control( 'featured_only', [ 'label' => __( 'Featured Only', 'ocean-charter' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'no' ] );
    $this->add_control( 'section_heading', [ 'label' => __( 'Heading', 'ocean-charter' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Featured Vessels' ] );
    $this->add_control( 'section_eyebrow', [ 'label' => __( 'Eyebrow', 'ocean-charter' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Our Finest Fleet' ] );
    $this->add_control( 'view_all_label', [ 'label' => __( 'View All Label', 'ocean-charter' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'View Full Fleet' ] );
    $this->add_control( 'view_all_url',   [ 'label' => __( 'View All URL', 'ocean-charter' ),   'type' => \Elementor\Controls_Manager::URL, 'default' => [ 'url' => '/fleet/' ] ] );
    $this->add_control( 'excerpt_length', [ 'label' => __( 'Amenity Pills (max)', 'ocean-charter' ), 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 4, 'min' => 0, 'max' => 10 ] );
    $this->end_controls_section();

    /* ── STYLE ── */
    $this->oc_register_heading_style( '.oc-fv' );
    $this->oc_register_card_style( '.oc-fv .fv-card' );
    $this->oc_register_title_style( '.oc-fv .fv-card__name' );
    $this->oc_register_excerpt_style( '.oc-fv .fv-card__spec-lbl' );
    $this->oc_register_button_style( '.oc-fv .fv-card__btn' );
    $this->oc_register_grid_style( '.oc-fv__grid' );
    $this->oc_register_image_style( '.oc-fv .fv-card__img-wrap' );
    $this->oc_register_accent_style( [
        '{{WRAPPER}} .oc-fv .fv-card__badge'        => 'background: {{VALUE}};',
        '{{WRAPPER}} .oc-fv .fv-card__price-amount' => 'color: {{VALUE}};',
        '{{WRAPPER}} .oc-fv .fv-card__spec-icon'    => 'color: {{VALUE}};',
        '{{WRAPPER}} .oc-fv .fv-card__amenity'      => 'color: {{VALUE}}; border-color: {{VALUE}}33;',
        '{{WRAPPER}} .oc-fv__view-all'              => 'color: {{VALUE}}; border-color: {{VALUE}}4d;',
    ] );

    /* ── Price-specific style ── */
    $this->start_controls_section( 'style_price', [ 'label' => __( 'Price', 'ocean-charter' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
    $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
        'name' => 'price_typography', 'label' => __( 'Price Typography', 'ocean-charter' ),
        'selector' => '{{WRAPPER}} .oc-fv .fv-card__price-amount',
    ] );
    $this->add_control( 'price_color', [
        'label' => __( 'Price Color', 'ocean-charter' ), 'type' => \Elementor\Controls_Manager::COLOR,
        'default' => '#d9b230',
        'selectors' => [ '{{WRAPPER}} .oc-fv .fv-card__price-amount' => 'color: {{VALUE}};' ],
    ] );
    $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
        'name' => 'price_unit_typography', 'label' => __( '"per day" Typography', 'ocean-charter' ),
        'selector' => '{{WRAPPER}} .oc-fv .fv-card__price-unit',
    ] );
    $this->add_control( 'price_unit_color', [
        'label' => __( '"per day" Color', 'ocean-charter' ), 'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [ '{{WRAPPER}} .oc-fv .fv-card__price-unit' => 'color: {{VALUE}};' ],
    ] );
    $this->end_controls_section();

    /* ── Badge style ── */
    $this->start_controls_section( 'style_badge', [ 'label' => __( 'Top Rated Badge', 'ocean-charter' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
    $this->add_control( 'badge_bg', [
        'label' => __( 'Badge Background', 'ocean-charter' ), 'type' => \Elementor\Controls_Manager::COLOR,
        'default' => '#d9b230',
        'selectors' => [ '{{WRAPPER}} .oc-fv .fv-card__badge' => 'background: {{VALUE}};' ],
    ] );
    $this->add_control( 'badge_text_color', [
        'label' => __( 'Badge Text Color', 'ocean-charter' ), 'type' => \Elementor\Controls_Manager::COLOR,
        'default' => '#0a0f1a',
        'selectors' => [ '{{WRAPPER}} .oc-fv .fv-card__badge' => 'color: {{VALUE}};' ],
    ] );
    $this->end_controls_section();
}
```

- [ ] In `render()`, apply the columns CSS var on the wrapper:
```php
$cols = intval( $s['columns'] ?? 3 );
$this->add_render_attribute( 'fv_wrapper', 'class', 'oc-fv' );
$this->add_render_attribute( 'fv_wrapper', 'style', '--oc-fv-cols:' . $cols . ';' );
```
Change the `<style>` grid line to: `.oc-fv__grid{display:grid;grid-template-columns:repeat(var(--oc-fv-cols,3),1fr);gap:1.5rem}`
Change `<div class="oc-fv">` to `<div <?php $this->print_render_attribute_string('fv_wrapper'); ?>>`

- [ ] Also respect `excerpt_length` for amenity pills: `$amen = array_slice($amen, 0, intval($s['excerpt_length'] ?? 4));`

- [ ] PHP syntax check

---

## Chunk 2: Why Us — Repeater + Full Style Tab

### Task 3: Rewrite `OC_Why_Us_Widget`

**Files:**
- Modify: `inc/elementor-query-widgets.php` (class OC_Why_Us_Widget, lines ~1622–1706)

The four "features" (Expert Captains, Award-Winning Service, etc.) are currently hardcoded PHP arrays. Move them into an Elementor Repeater so editors can add, remove, and edit them live.

- [ ] Add `use OC_Widget_Style_Trait;` at top of class

- [ ] Replace `register_controls()` fully:

```php
protected function register_controls() {
    /* ── CONTENT ── */
    $this->start_controls_section( 'content', [ 'label' => __( 'Content', 'ocean-charter' ) ] );
    $this->add_control( 'eyebrow',     [ 'label' => __('Eyebrow','ocean-charter'), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'The Ocean Charter Difference' ] );
    $this->add_control( 'heading',     [ 'label' => __('Heading','ocean-charter'), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => '25 Years of Unmatched Maritime Experience' ] );
    $this->add_control( 'description', [ 'label' => __('Description','ocean-charter'), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => "Since 1999, we have been crafting extraordinary private yacht experiences for discerning travellers across the world's most coveted waters." ] );
    $this->end_controls_section();

    /* ── STATS ── */
    $this->start_controls_section( 'section_stats', [ 'label' => __('Stats Card','ocean-charter') ] );
    $this->add_control( 'stat_years',        [ 'label' => __('Years Number','ocean-charter'),   'type' => \Elementor\Controls_Manager::TEXT, 'default' => '25' ] );
    $this->add_control( 'stat_years_label',  [ 'label' => __('Years Label','ocean-charter'),    'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Years of Excellence' ] );
    $this->add_control( 'stat_charters',     [ 'label' => __('Charters Stat','ocean-charter'),  'type' => \Elementor\Controls_Manager::TEXT, 'default' => '2,400+' ] );
    $this->add_control( 'stat_destinations', [ 'label' => __('Destinations Stat','ocean-charter'), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => '60+' ] );
    $this->add_control( 'stat_rating',       [ 'label' => __('Rating Stat','ocean-charter'),    'type' => \Elementor\Controls_Manager::TEXT, 'default' => '★ 4.9' ] );
    $this->end_controls_section();

    /* ── IMAGES ── */
    $this->start_controls_section( 'section_images', [ 'label' => __('Images','ocean-charter') ] );
    $this->add_control( 'primary_image', [
        'label'   => __('Primary Image','ocean-charter'),
        'type'    => \Elementor\Controls_Manager::MEDIA,
        'default' => [ 'url' => 'https://images.pexels.com/photos/1118873/pexels-photo-1118873.jpeg?auto=compress&cs=tinysrgb&w=1200' ],
    ] );
    $this->add_control( 'secondary_image', [
        'label'   => __('Overlay Image','ocean-charter'),
        'type'    => \Elementor\Controls_Manager::MEDIA,
        'default' => [ 'url' => 'https://images.pexels.com/photos/1268856/pexels-photo-1268856.jpeg?auto=compress&cs=tinysrgb&w=800' ],
    ] );
    $this->end_controls_section();

    /* ── FEATURES REPEATER ── */
    $this->start_controls_section( 'section_features', [ 'label' => __('Feature Items','ocean-charter') ] );
    $repeater = new \Elementor\Repeater();
    $repeater->add_control( 'feature_icon', [
        'label'   => __('Icon','ocean-charter'),
        'type'    => \Elementor\Controls_Manager::SELECT,
        'options' => [ 'anchor'=>'Anchor','star'=>'Star','map'=>'Map','shield'=>'Shield','heart'=>'Heart','compass'=>'Compass','chart'=>'Chart' ],
        'default' => 'anchor',
    ] );
    $repeater->add_control( 'feature_title', [ 'label' => __('Title','ocean-charter'), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Expert Captains' ] );
    $repeater->add_control( 'feature_text',  [ 'label' => __('Description','ocean-charter'), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'Every vessel commanded by an ISM-certified captain with 10+ years of offshore experience.' ] );
    $this->add_control( 'features', [
        'label'       => __('Features','ocean-charter'),
        'type'        => \Elementor\Controls_Manager::REPEATER,
        'fields'      => $repeater->get_controls(),
        'default'     => [
            [ 'feature_icon' => 'anchor', 'feature_title' => 'Expert Captains',       'feature_text' => 'Every vessel commanded by an ISM-certified captain with 10+ years of offshore experience.' ],
            [ 'feature_icon' => 'star',   'feature_title' => 'Award-Winning Service', 'feature_text' => 'Consistently rated among the world\'s top charter operators by Condé Nast and Robb Report.' ],
            [ 'feature_icon' => 'map',    'feature_title' => 'Bespoke Itineraries',   'feature_text' => 'Every voyage is hand-crafted to your desires — from secluded bays to vibrant marina towns.' ],
            [ 'feature_icon' => 'shield', 'feature_title' => 'Fully Insured & Vetted','feature_text' => 'All vessels carry full commercial maritime insurance and exceed SOLAS safety standards.' ],
        ],
        'title_field' => '{{{ feature_title }}}',
    ] );
    $this->end_controls_section();

    /* ── STYLE ── */
    $this->oc_register_heading_style( '.oc-why-us' );
    $this->oc_register_title_style( '.oc-why-us__feature-body h4', 'Feature Title' );
    $this->oc_register_excerpt_style( '.oc-why-us__feature-body p' );
    $this->oc_register_button_style( '.oc-why-us__stat-card' );

    /* Years number specific */
    $this->start_controls_section( 'style_stat', [ 'label' => __('Stat Card','ocean-charter'), 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
    $this->add_control( 'stat_card_bg', [
        'label' => __('Background','ocean-charter'), 'type' => \Elementor\Controls_Manager::COLOR,
        'default' => '#d9b230',
        'selectors' => [ '{{WRAPPER}} .oc-why-us__stat-card' => 'background: {{VALUE}};' ],
    ] );
    $this->add_control( 'stat_num_color', [
        'label' => __('Number Color','ocean-charter'), 'type' => \Elementor\Controls_Manager::COLOR,
        'default' => '#0a0f1a',
        'selectors' => [ '{{WRAPPER}} .oc-why-us__stat-num' => 'color: {{VALUE}};' ],
    ] );
    $this->add_control( 'stat_num_size', [
        'label' => __('Number Size','ocean-charter'), 'type' => \Elementor\Controls_Manager::SLIDER,
        'size_units' => [ 'px','rem' ], 'range' => [ 'px' => [ 'min' => 20,'max' => 120 ] ],
        'default' => [ 'size' => 4, 'unit' => 'rem' ],
        'selectors' => [ '{{WRAPPER}} .oc-why-us__stat-num' => 'font-size: {{SIZE}}{{UNIT}};' ],
    ] );
    $this->end_controls_section();

    /* Overlay image position tweaks */
    $this->start_controls_section( 'style_overlay_img', [ 'label' => __('Overlay Image','ocean-charter'), 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
    $this->add_control( 'overlay_width', [
        'label' => __('Width','ocean-charter'), 'type' => \Elementor\Controls_Manager::SLIDER,
        'size_units' => [ '%','px' ], 'range' => [ '%' => [ 'min' => 20,'max' => 80 ] ],
        'default' => [ 'size' => 55,'unit' => '%' ],
        'selectors' => [ '{{WRAPPER}} .oc-why-us__img-overlay' => 'width: {{SIZE}}{{UNIT}};' ],
    ] );
    $this->add_control( 'overlay_bottom', [
        'label' => __('Bottom Offset','ocean-charter'), 'type' => \Elementor\Controls_Manager::SLIDER,
        'size_units' => [ 'px','rem' ], 'range' => [ 'px' => [ 'min' => -60,'max' => 60 ] ],
        'default' => [ 'size' => -2,'unit' => 'rem' ],
        'selectors' => [ '{{WRAPPER}} .oc-why-us__img-overlay' => 'bottom: {{SIZE}}{{UNIT}};' ],
    ] );
    $this->add_control( 'overlay_radius', [
        'label' => __('Border Radius','ocean-charter'), 'type' => \Elementor\Controls_Manager::SLIDER,
        'size_units' => [ 'px','rem' ],
        'default' => [ 'size' => 0.875,'unit' => 'rem' ],
        'selectors' => [ '{{WRAPPER}} .oc-why-us__img-overlay' => 'border-radius: {{SIZE}}{{UNIT}};' ],
    ] );
    $this->add_control( 'feature_icon_bg', [
        'label' => __('Icon Box Background','ocean-charter'), 'type' => \Elementor\Controls_Manager::COLOR,
        'default' => 'rgba(10,15,26,0.6)',
        'selectors' => [ '{{WRAPPER}} .oc-why-us__feature-icon' => 'background: {{VALUE}};' ],
    ] );
    $this->add_control( 'feature_icon_color', [
        'label' => __('Icon Color','ocean-charter'), 'type' => \Elementor\Controls_Manager::COLOR,
        'default' => '#d9b230',
        'selectors' => [ '{{WRAPPER}} .oc-why-us__feature-icon' => 'color: {{VALUE}};' ],
    ] );
    $this->end_controls_section();
}
```

- [ ] Update `render()` to iterate over `$s['features']` repeater items and use a PHP icon map:
```php
$icon_map = [
    'anchor'  => '<svg ...anchor svg...>',
    'star'    => '<svg ...star svg...>',
    'map'     => '<svg ...map svg...>',
    'shield'  => '<svg ...shield svg...>',
    'heart'   => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>',
    'compass' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/></svg>',
    'chart'   => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>',
];
// In the loop:
foreach ( $s['features'] as $f ) {
    $icon_key = $f['feature_icon'] ?? 'anchor';
    $icon_svg = $icon_map[ $icon_key ] ?? $icon_map['anchor'];
    // render icon_svg, feature_title, feature_text
}
```

- [ ] Use `$s['primary_image']['url']` and `$s['secondary_image']['url']` for image src (fall back to default Pexels URL if empty)

- [ ] PHP syntax check

---

## Chunk 3: Destinations Gallery + Services + Offers Style Tabs

### Task 4: Fix `OC_Destinations_Gallery_Widget` — remove tile, add View All button + Style tab

**Files:**
- Modify: `inc/elementor-query-widgets.php` (class OC_Destinations_Gallery_Widget)

- [ ] Add `use OC_Widget_Style_Trait;` to the class

- [ ] Add to Content controls:
```php
$this->add_control( 'view_all_label', [ 'label' => __('View All Label','ocean-charter'), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'View All Destinations' ] );
$this->add_control( 'view_all_url',   [ 'label' => __('View All URL','ocean-charter'), 'type' => \Elementor\Controls_Manager::URL, 'default' => [ 'url' => '/destinations/' ] ] );
$this->add_control( 'columns',        [ 'label' => __('Columns','ocean-charter'), 'type' => \Elementor\Controls_Manager::SELECT, 'default' => '3', 'options' => ['2'=>'2','3'=>'3','4'=>'4'] ] );
```

- [ ] Add Style sections via trait:
```php
$this->oc_register_heading_style( '.oc-dest-gal' );
$this->oc_register_grid_style( '.oc-dest-gal__grid' );
$this->oc_register_button_style( '.oc-dest-gal__view-all-btn' );
$this->oc_register_image_style( '.oc-dest-gal .gal-item' );
$this->oc_register_title_style( '.oc-dest-gal .gal-item__title', 'Destination Title' );
```

- [ ] In `render()`:
  - Remove the `<a class="gal-explore">` tile entirely
  - Apply `--oc-dg-cols:N` CSS var on wrapper, update grid CSS to `repeat(var(--oc-dg-cols,3),1fr)`
  - After the closing `</div>` of the grid, add:
```php
<?php if ( !empty($s['view_all_label']) && !empty($s['view_all_url']['url']) ): ?>
<div class="oc-dest-gal__footer">
    <a href="<?php echo esc_url($s['view_all_url']['url']); ?>" class="oc-dest-gal__view-all-btn">
        <?php echo esc_html($s['view_all_label']); ?> &rarr;
    </a>
</div>
<?php endif; ?>
```
  - Add CSS for the footer button: centered, pill, outlined gold style matching the Stitch "View All" links

- [ ] PHP syntax check

---

### Task 5: `OC_Service_Grid_Widget` — columns control + Style tab

**Files:**
- Modify: `inc/elementor-query-widgets.php` (class OC_Service_Grid_Widget)

- [ ] Add `use OC_Widget_Style_Trait;`

- [ ] Add to Content section:
```php
$this->add_control( 'columns', [ 'label' => __('Columns','ocean-charter'), 'type' => \Elementor\Controls_Manager::SELECT, 'default' => '4', 'options' => ['1'=>'1','2'=>'2','3'=>'3','4'=>'4'] ] );
$this->add_control( 'excerpt_length', [ 'label' => __('Excerpt Length (chars)','ocean-charter'), 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 120, 'min' => 0 ] );
```

- [ ] Add Style sections:
```php
$this->oc_register_heading_style( '.oc-service-grid' );
$this->oc_register_card_style( '.oc-service-grid .svc-card' );
$this->oc_register_title_style( '.oc-service-grid .svc-card__body h3' );
$this->oc_register_excerpt_style( '.oc-service-grid .svc-card__excerpt' );
$this->oc_register_button_style( '.oc-service-grid .svc-card__link' );
$this->oc_register_grid_style( '.oc-service-grid__grid' );
$this->oc_register_image_style( '.oc-service-grid .svc-card__img-wrap' );
$this->oc_register_accent_style( [ '{{WRAPPER}} .oc-service-grid .svc-card__tag' => 'color: {{VALUE}};', '{{WRAPPER}} .oc-service-grid .svc-card__eyebrow' => 'color: {{VALUE}};' ] );
```

- [ ] In render: apply CSS var for columns; truncate excerpt to `$s['excerpt_length']` chars with `mb_strimwidth($excerpt, 0, $len, '…')`

- [ ] PHP syntax check

---

### Task 6: `OC_Offer_Cards_Widget` — columns + excerpt length + Style tab

**Files:**
- Modify: `inc/elementor-query-widgets.php` (class OC_Offer_Cards_Widget)

- [ ] Add `use OC_Widget_Style_Trait;`

- [ ] Add to Content section:
```php
$this->add_control( 'columns',        [ 'label' => __('Columns','ocean-charter'), 'type' => \Elementor\Controls_Manager::SELECT, 'default' => '3', 'options' => ['1'=>'1','2'=>'2','3'=>'3','4'=>'4'] ] );
$this->add_control( 'excerpt_length', [ 'label' => __('Excerpt Length (chars)','ocean-charter'), 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 100, 'min' => 0 ] );
```

- [ ] Add Style sections:
```php
$this->oc_register_heading_style( '.oc-offer-cards' );
$this->oc_register_card_style( '.oc-offer-cards .offer-card' );
$this->oc_register_title_style( '.oc-offer-cards .offer-card__body h3' );
$this->oc_register_excerpt_style( '.oc-offer-cards .offer-card__excerpt' );
$this->oc_register_button_style( '.oc-offer-cards .offer-card__cta' );
$this->oc_register_grid_style( '.oc-offer-cards__grid' );
$this->oc_register_image_style( '.oc-offer-cards .offer-card__img-wrap' );
```
- [ ] Discount size slider:
```php
$this->start_controls_section('style_discount', [ 'label' => __('Discount Number','ocean-charter'), 'tab' => \Elementor\Controls_Manager::TAB_STYLE ]);
$this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [ 'name' => 'discount_typography', 'selector' => '{{WRAPPER}} .oc-offer-cards .offer-card__discount' ]);
$this->add_control('discount_color', [ 'label' => __('Color','ocean-charter'), 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#d9b230', 'selectors' => [ '{{WRAPPER}} .oc-offer-cards .offer-card__discount' => 'color: {{VALUE}};' ] ]);
$this->end_controls_section();
```

- [ ] In render: apply columns CSS var; truncate excerpt with `mb_strimwidth`

---

## Chunk 4: Testimonials, Press, FAQ, Vessel Grid, Itinerary, Package

### Task 7: `OC_Testimonial_Carousel_Widget` — Style tab + content editable

**Files:**
- Modify: `inc/elementor-query-widgets.php` (class OC_Testimonial_Carousel_Widget)

- [ ] Add `use OC_Widget_Style_Trait;`

- [ ] Add to Content section — testimonials come from `oc_testimonial` CPT (backend). Add:
```php
$this->add_control( 'section_heading',  [ 'label' => __('Heading','ocean-charter'), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Guest Experiences' ] );
$this->add_control( 'section_eyebrow',  [ 'label' => __('Eyebrow','ocean-charter'), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Reflections' ] );
$this->add_control( 'autoplay_speed',   [ 'label' => __('Autoplay (ms)','ocean-charter'), 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 5000, 'min' => 1000 ] );
```

- [ ] Add Style sections:
```php
$this->oc_register_heading_style( '.oc-testimonials' );
$this->oc_register_card_style( '.oc-testimonials .testi-card' );
$this->oc_register_excerpt_style( '.oc-testimonials .testi-card__quote' );
$this->oc_register_accent_style( [ '{{WRAPPER}} .oc-testimonials .testi-card__stars' => 'color: {{VALUE}};' ], 'Stars Color' );
```

- [ ] Pass `autoplay_speed` to JS via `data-autoplay` attribute on the carousel wrapper

- [ ] PHP syntax check

---

### Task 8: `OC_Press_Strip_Widget` — Style tab

**Files:**
- Modify: `inc/elementor-query-widgets.php` (class OC_Press_Strip_Widget)

- [ ] Add `use OC_Widget_Style_Trait;`

- [ ] Add to Content section:
```php
$this->add_control( 'section_heading', [...] );
$this->add_control( 'section_eyebrow', [...] );
$this->add_control( 'grayscale', [ 'label' => __('Logo Grayscale','ocean-charter'), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes', 'description' => 'Logos shown in grayscale, color on hover' ] );
```

- [ ] Add Style sections:
```php
$this->oc_register_heading_style( '.oc-press-strip' );
```

- [ ] In render: conditionally add `.oc-press-strip--grayscale` class; add CSS `.oc-press-strip--grayscale .press-logo img { filter:grayscale(1); opacity:.6; } .oc-press-strip--grayscale .press-logo:hover img { filter:grayscale(0); opacity:1; }`

---

### Task 9: `OC_FAQ_Accordion_Widget` — Style tab + content note

**Files:**
- Modify: `inc/elementor-query-widgets.php` (class OC_FAQ_Accordion_Widget)

FAQ items come from the `oc_faq` Custom Post Type. The question is the post title; the answer is the post content. Editors add/edit FAQs at wp-admin → FAQs (oc_faq).

- [ ] Add admin note control:
```php
$this->add_control( 'faq_source_notice', [
    'type' => \Elementor\Controls_Manager::RAW_HTML,
    'raw'  => '<div style="background:rgba(217,178,48,.1);border:1px solid rgba(217,178,48,.3);padding:.75rem;border-radius:.5rem;font-size:.85rem;color:#d9b230">📋 FAQ items are managed in <strong>wp-admin → FAQs</strong>. Add, edit, or reorder them there.</div>',
    'content_classes' => 'oc-info-notice',
] );
```

- [ ] Add `use OC_Widget_Style_Trait;` and Style sections:
```php
$this->oc_register_heading_style( '.oc-faq' );
// Question style
$this->start_controls_section('style_question', [ 'label'=>__('Question','ocean-charter'), 'tab'=>\Elementor\Controls_Manager::TAB_STYLE ]);
$this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [ 'name'=>'question_typography', 'selector'=>'{{WRAPPER}} .faq-item__question' ]);
$this->add_control('question_color', [ 'label'=>__('Color','ocean-charter'), 'type'=>\Elementor\Controls_Manager::COLOR, 'default'=>'#f0ece3', 'selectors'=>[ '{{WRAPPER}} .faq-item__question'=>'color: {{VALUE}};' ] ]);
$this->end_controls_section();
$this->oc_register_excerpt_style( '.oc-faq .faq-item__answer' );
$this->oc_register_accent_style( [ '{{WRAPPER}} .oc-faq .faq-item__icon'=>'color: {{VALUE}};' ], 'Icon Color' );
```

---

### Task 10: `OC_Vessel_Grid_Widget`, `OC_Package_Grid_Widget`, `OC_Itinerary_Grid_Widget` — columns + Style tabs

Apply the same pattern as Task 5 to each of these three:
- [ ] Add `use OC_Widget_Style_Trait;`
- [ ] Add columns SELECT (1/2/3/4) to Content
- [ ] Add excerpt_length NUMBER to Content
- [ ] Call trait helpers for heading, card, title, excerpt, button, grid, image, accent style sections
- [ ] Apply CSS var in render(), truncate excerpts

---

## Chunk 5: Static Widgets (Hero, CTA Strip, Stats Bar, Contact)

### Task 11: `OC_Hero_Widget` Style tab

**Files:**
- Modify: `inc/elementor-widgets.php` (class OC_Hero_Widget)

- [ ] Add `use OC_Widget_Style_Trait;`
- [ ] Add Style sections for: heading typography/color, sub-heading, button colors/border/radius
- [ ] Add Content control for background image (`MEDIA` type)

### Task 12: `OC_CTA_Strip_Widget` Style tab

- [ ] Add Style sections: heading, sub-text, primary button, secondary button, background color

### Task 13: `OC_Stats_Bar_Widget` Style tab

- [ ] Add Style: number typography/color, label typography/color, card background, divider color

---

## Chunk 6: Theme Settings Admin Page

### Task 14: Create `inc/theme-settings.php`

**Files:**
- Create: `inc/theme-settings.php`
- Modify: `functions.php` (require at bottom)

- [ ] Create admin settings page registered under Appearance:

```php
<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class OC_Theme_Settings {
    const OPTION = 'oc_theme_settings';

    public static function init() {
        add_action( 'admin_menu', [ __CLASS__, 'add_menu' ] );
        add_action( 'admin_init', [ __CLASS__, 'register_settings' ] );
    }

    public static function add_menu() {
        add_theme_page(
            'Ocean Charter Settings',
            'OC Theme Settings',
            'manage_options',
            'oc-theme-settings',
            [ __CLASS__, 'render_page' ]
        );
    }

    public static function register_settings() {
        register_setting( 'oc_theme_settings_group', self::OPTION, [ 'sanitize_callback' => [ __CLASS__, 'sanitize' ] ] );

        // Global Design
        add_settings_section( 'oc_design', 'Global Design', null, 'oc-theme-settings' );
        self::field( 'primary_color',   'Primary Gold Color',   'oc_design', 'color',  '#d9b230' );
        self::field( 'bg_dark',         'Dark Background',      'oc_design', 'color',  '#0a0f1a' );
        self::field( 'surface_color',   'Card Surface Color',   'oc_design', 'color',  '#111a28' );
        self::field( 'heading_font',    'Heading Font Family',  'oc_design', 'text',   'Playfair Display' );
        self::field( 'body_font',       'Body Font Family',     'oc_design', 'text',   'Inter' );

        // Contact & Brand
        add_settings_section( 'oc_contact', 'Contact & Brand', null, 'oc-theme-settings' );
        self::field( 'phone',        'Phone Number',     'oc_contact', 'text', '+1 (800) 555-0199' );
        self::field( 'email',        'Email Address',    'oc_contact', 'text', 'info@oceancharter.com' );
        self::field( 'address',      'Office Address',   'oc_contact', 'text', 'Monaco Yacht Club, Monte Carlo' );
        self::field( 'instagram',    'Instagram URL',    'oc_contact', 'url',  '' );
        self::field( 'facebook',     'Facebook URL',     'oc_contact', 'url',  '' );
        self::field( 'youtube',      'YouTube URL',      'oc_contact', 'url',  '' );
        self::field( 'whatsapp',     'WhatsApp Number',  'oc_contact', 'text', '' );

        // Footer
        add_settings_section( 'oc_footer', 'Footer', null, 'oc-theme-settings' );
        self::field( 'footer_tagline',  'Footer Tagline',     'oc_footer', 'text', 'Extraordinary yacht charters crafted for the discerning traveller.' );
        self::field( 'copyright_text',  'Copyright Text',     'oc_footer', 'text', '© 2025 Ocean Charter. All rights reserved.' );
        self::field( 'google_maps_key', 'Google Maps API Key','oc_footer', 'text', '' );
    }

    private static function field( $key, $label, $section, $type = 'text', $default = '' ) {
        add_settings_field( $key, $label, function() use ( $key, $type, $default ) {
            $opts = get_option( OC_Theme_Settings::OPTION, [] );
            $val  = $opts[ $key ] ?? $default;
            $val  = esc_attr( $val );
            if ( $type === 'color' ) {
                echo "<input type='color' name='" . OC_Theme_Settings::OPTION . "[$key]' value='$val' class='oc-color-picker'>";
            } elseif ( $type === 'url' ) {
                echo "<input type='url' name='" . OC_Theme_Settings::OPTION . "[$key]' value='$val' class='regular-text'>";
            } else {
                echo "<input type='text' name='" . OC_Theme_Settings::OPTION . "[$key]' value='$val' class='regular-text'>";
            }
        }, 'oc-theme-settings', $section );
    }

    public static function sanitize( $input ) {
        $clean = [];
        foreach ( $input as $k => $v ) {
            $clean[ sanitize_key($k) ] = sanitize_text_field( $v );
        }
        return $clean;
    }

    public static function render_page() {
        if ( ! current_user_can('manage_options') ) return;
        ?>
        <div class="wrap">
            <h1>⚓ Ocean Charter — Theme Settings</h1>
            <form method="post" action="options.php">
                <?php settings_fields('oc_theme_settings_group'); ?>
                <?php do_settings_sections('oc-theme-settings'); ?>
                <?php submit_button('Save Settings'); ?>
            </form>
        </div>
        <?php
    }
}
OC_Theme_Settings::init();
```

- [ ] Inject saved settings as CSS custom properties in `wp_head`:

```php
add_action('wp_head', function() {
    $opts = get_option('oc_theme_settings', []);
    $primary  = sanitize_hex_color( $opts['primary_color'] ?? '' ) ?: '#d9b230';
    $bg_dark  = sanitize_hex_color( $opts['bg_dark']       ?? '' ) ?: '#0a0f1a';
    $surface  = sanitize_hex_color( $opts['surface_color'] ?? '' ) ?: '#111a28';
    $hfont    = esc_attr( $opts['heading_font'] ?? 'Playfair Display' );
    $bfont    = esc_attr( $opts['body_font']    ?? 'Inter' );
    echo "<style>:root{
        --primary:{$primary};
        --bg-dark:{$bg_dark};
        --surface:{$surface};
        --font-heading:'{$hfont}',serif;
        --font-body:'{$bfont}',sans-serif;
    }</style>";
}, 5);
```

- [ ] PHP syntax check

---

## Chunk 7: Mobile Responsive + Code Cleanup

### Task 15: Mobile responsive audit and fixes

**Files:**
- Modify: `inc/elementor-query-widgets.php`, `inc/elementor-widgets.php`

- [ ] For every widget, ensure breakpoints exist:
  - `max-width:1024px` — tablet landscape: reduce font sizes
  - `max-width:768px`  — tablet portrait: 2 cols
  - `max-width:540px`  — mobile: 1 col, full width cards
  - `max-width:400px`  — small mobile: reduce padding

- [ ] Why Us section: at `max-width:900px`, stack to 1 column; stat card moves to `left:1rem; top:-1rem`; overlay image becomes `display:none` (too cluttered on mobile)

- [ ] Testimonial carousel: nav arrows hidden on mobile (`max-width:540px`), dots remain

- [ ] Hero widget: heading `clamp(2rem,6vw,4rem)`, sub `clamp(1rem,2vw,1.25rem)`, booking widget stacks vertically at `max-width:640px`

- [ ] Destinations gallery: 1 column at `max-width:540px`, first item `grid-row:span 1` (no span on mobile)

### Task 16: Code cleanup — remove inline `<style>` duplication

After all Style tab controls are in place using `selectors`, the base CSS in each widget's `<style>` block should be trimmed to only layout/structural defaults. All overridable visual properties (colors, typography, spacing) are now controlled via Elementor selectors and no longer need to be in the PHP `<style>` blocks.

- [ ] For each widget, audit its `<style>` block and remove any rule that is now covered by a `selectors` control
- [ ] Keep only: grid layout, flex layout, position rules, transition declarations, and default values for CSS vars
- [ ] Extract any CSS shared across multiple widgets into the theme's main `style.css`

---

## Final Verification

- [ ] Visit homepage in browser — all widgets render correctly
- [ ] Open Elementor on homepage — Featured Vessels shows Content + Style + Advanced tabs
- [ ] Change columns to 4 — grid updates live
- [ ] Edit Why Us feature title in Elementor — updates live
- [ ] Change primary color in OC Theme Settings — page CSS custom property updates
- [ ] Check on iPhone 390px viewport — all sections single column, no overflow
- [ ] PHP syntax check both widget files: `php -l inc/elementor-query-widgets.php && php -l inc/elementor-widgets.php`
- [ ] Clear Elementor cache: run `php /tmp/oc-clear-cache.php`
