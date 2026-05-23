<?php
/**
 * Ocean Charter — Query Widgets
 * Elementor widgets that pull live data from Ocean Charter CPTs.
 * Loaded by elementor-support.php after Elementor init.
 */
if ( ! defined( 'ABSPATH' ) || ! defined( 'ELEMENTOR_VERSION' ) ) exit;

/* ============================================================
   1. OC_Destination_Grid_Widget
   ============================================================ */
class OC_Destination_Grid_Widget extends \Elementor\Widget_Base {
    use OC_Widget_Style_Trait;

    public function get_name()        { return 'oc-destination-grid'; }
    public function get_title()       { return __( 'OC Destination Grid', 'ocean-charter' ); }
    public function get_icon()        { return 'eicon-gallery-grid'; }
    public function get_categories()  { return [ 'ocean-charter' ]; }

    protected function register_controls() {
        $this->start_controls_section( 'query', [ 'label' => __( 'Query', 'ocean-charter' ) ] );
        $this->add_control( 'posts_count', [
            'label'   => __( 'Posts Count', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::NUMBER,
            'default' => 6,
            'min'     => 1,
        ] );
        $this->add_control( 'columns', [
            'label'   => __( 'Columns', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => '4',
            'options' => [ '1' => '1', '2' => '2', '3' => '3', '4' => '4' ],
        ] );
        $this->add_control( 'show_filter', [
            'label'        => __( 'Show Region Filter', 'ocean-charter' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ] );
        $this->add_control( 'region', [
            'label'       => __( 'Filter by Region Slug', 'ocean-charter' ),
            'type'        => \Elementor\Controls_Manager::TEXT,
            'placeholder' => __( 'Blank = all', 'ocean-charter' ),
            'default'     => '',
        ] );
        $this->end_controls_section();

        $this->start_controls_section( 'labels', [ 'label' => __( 'Labels', 'ocean-charter' ) ] );
        $this->add_control( 'section_heading', [
            'label'   => __( 'Section Heading', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'Sailing Destinations', 'ocean-charter' ),
        ] );
        $this->add_control( 'section_eyebrow', [
            'label'   => __( 'Section Eyebrow', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'Where Will You Go', 'ocean-charter' ),
        ] );
        $this->end_controls_section();

        /* ── STYLE TABS ── */
        $this->oc_register_heading_style( '.oc-dest-grid-wrap' );
        $this->oc_register_card_style( '.oc-dest-grid .dest-card', 'Destination Card' );
        $this->oc_register_title_style( '.oc-dest-grid .dest-card__body h3', 'Card Title' );
        $this->oc_register_excerpt_style( '.oc-dest-grid .dest-card__body p' );
        $this->oc_register_grid_style( '.oc-dest-grid' );
        $this->oc_register_image_style( '.oc-dest-grid .dest-card__img-wrap' );
        $this->oc_register_accent_style( [
            '{{WRAPPER}} .oc-dest-grid .dest-card__popular'      => 'background: {{VALUE}};',
            '{{WRAPPER}} .oc-dest-grid .dest-card__explore'      => 'color: {{VALUE}};',
            '{{WRAPPER}} .oc-dest-grid .dest-card__vessel-count' => 'color: {{VALUE}};',
            '{{WRAPPER}} .oc-dest-grid-filters .filter-pill.is-active' => 'background: {{VALUE}}; border-color: {{VALUE}};',
        ], 'Accent / Filter Color' );

        /* ── Filter Pill Style ── */
        $this->start_controls_section( 'style_filter', [
            'label' => __( 'Filter Pills', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'filter_typography',
            'selector' => '{{WRAPPER}} .oc-dest-grid-filters .filter-pill',
        ] );
        $this->add_control( 'filter_bg', [
            'label'     => __( 'Inactive Background', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => 'rgba(255,255,255,0.05)',
            'selectors' => [ '{{WRAPPER}} .oc-dest-grid-filters .filter-pill' => 'background: {{VALUE}};' ],
        ] );
        $this->add_control( 'filter_text_color', [
            'label'     => __( 'Inactive Text Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .oc-dest-grid-filters .filter-pill' => 'color: {{VALUE}};' ],
        ] );
        $this->add_control( 'filter_border_color', [
            'label'     => __( 'Inactive Border Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .oc-dest-grid-filters .filter-pill' => 'border-color: {{VALUE}};' ],
        ] );
        $this->add_control( 'filter_active_text', [
            'label'     => __( 'Active Text Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#0a0f1a',
            'selectors' => [ '{{WRAPPER}} .oc-dest-grid-filters .filter-pill.is-active, {{WRAPPER}} .oc-dest-grid-filters .filter-pill:hover' => 'color: {{VALUE}};' ],
        ] );
        $this->end_controls_section();

        /* ── Footer Style ── */
        $this->start_controls_section( 'style_footer', [
            'label' => __( 'Card Footer', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_control( 'footer_border_color', [
            'label'     => __( 'Border Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .oc-dest-grid .dest-card__footer' => 'border-color: {{VALUE}};' ],
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'footer_typography',
            'selector' => '{{WRAPPER}} .oc-dest-grid .dest-card__footer',
        ] );
        $this->end_controls_section();
    }

    protected function render() {
        $s     = $this->get_settings_for_display();
        $count = ! empty( $s['posts_count'] ) ? intval( $s['posts_count'] ) : 6;
        $cols  = intval( $s['columns'] ?? 4 );
        $region_slug = sanitize_text_field( $s['region'] ?? '' );

        $args = [
            'post_type'      => 'oc_destination',
            'posts_per_page' => $count,
            'post_status'    => 'publish',
        ];
        if ( $region_slug ) {
            $args['tax_query'] = [ [
                'taxonomy' => 'oc_destination_region',
                'field'    => 'slug',
                'terms'    => $region_slug,
            ] ];
        }
        $query = new WP_Query( $args );

        // Gather all region terms for filter pills
        $region_terms = get_terms( [ 'taxonomy' => 'oc_destination_region', 'hide_empty' => true ] );
        $uid = 'ocdg-' . $this->get_id();
        ?>
        <style>
        .oc-dest-grid-wrap { width:100%; }
        .oc-dest-grid-wrap .oc-section-eyebrow { display:block; font-size:0.75rem; font-weight:700; letter-spacing:0.18em; text-transform:uppercase; color:var(--primary,#d9b230); margin-bottom:0.5rem; }
        .oc-dest-grid-wrap .oc-section-heading { font-family:var(--font-heading,'Playfair Display',serif); font-size:clamp(2rem,3.5vw,3.25rem); color:var(--text,#f0ece3); margin:0 0 2rem; font-weight:400; font-style:italic; }
        .oc-dest-grid-filters { display:flex; flex-wrap:wrap; gap:0.5rem; margin-bottom:2rem; }
        .oc-dest-grid-filters .filter-pill { padding:0.4rem 1.1rem; border-radius:9999px; font-size:0.8125rem; font-weight:600; cursor:pointer; border:1px solid rgba(217,178,48,0.3); background:rgba(255,255,255,0.05); color:var(--text-muted,rgba(148,163,184,1)); transition:background 0.2s,color 0.2s,border-color 0.2s; }
        .oc-dest-grid-filters .filter-pill.is-active,
        .oc-dest-grid-filters .filter-pill:hover { background:var(--primary,#d9b230); color:#0a0f1a; border-color:var(--primary,#d9b230); }
        .oc-dest-grid { display:grid; grid-template-columns:repeat(var(--oc-dg-cols,4),1fr); gap:1.5rem; }
        @media(max-width:900px){.oc-dest-grid{grid-template-columns:repeat(2,1fr);}}
        @media(max-width:540px){.oc-dest-grid{grid-template-columns:1fr;}}
        .oc-dest-grid .dest-card { background:var(--surface,#111a28); border-radius:var(--radius-lg,1rem); overflow:hidden; display:flex; flex-direction:column; transition:box-shadow 0.3s,transform 0.3s; text-decoration:none; color:inherit; }
        .oc-dest-grid .dest-card:hover { transform:translateY(-4px); box-shadow:0 12px 40px rgba(0,0,0,0.45); }
        .oc-dest-grid .dest-card.is-hidden { display:none; }
        .oc-dest-grid .dest-card__img-wrap { position:relative; aspect-ratio:4/5; overflow:hidden; }
        .oc-dest-grid .dest-card__img-wrap img { width:100%; height:100%; object-fit:cover; transition:transform 0.5s ease; }
        .oc-dest-grid .dest-card:hover .dest-card__img-wrap img { transform:scale(1.06); }
        .oc-dest-grid .dest-card__img-placeholder { width:100%; height:100%; background:linear-gradient(135deg,#0d1f35,#1a2d45); }
        .oc-dest-grid .dest-card__popular { position:absolute; top:1rem; left:1rem; background:var(--primary,#d9b230); color:#0a0f1a; font-size:0.7rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; padding:0.25rem 0.7rem; border-radius:9999px; }
        .oc-dest-grid .dest-card__body { padding:1.5rem; display:flex; flex-direction:column; flex-grow:1; }
        .oc-dest-grid .dest-card__body h3 { font-family:var(--font-heading,'Playfair Display',serif); font-size:1.2rem; color:var(--text,#f0ece3); margin:0 0 0.5rem; font-weight:600; }
        .oc-dest-grid .dest-card__body p { font-size:0.875rem; color:var(--text-muted,rgba(148,163,184,1)); line-height:1.6; flex-grow:1; margin:0 0 1rem; }
        .oc-dest-grid .dest-card__footer { display:flex; justify-content:space-between; align-items:center; padding-top:0.875rem; border-top:1px solid rgba(255,255,255,0.07); font-size:0.8125rem; }
        .oc-dest-grid .dest-card__vessel-count { color:var(--text-muted,rgba(148,163,184,1)); }
        .oc-dest-grid .dest-card__explore { color:var(--primary,#d9b230); font-weight:600; text-decoration:none; }
        .oc-dest-grid .dest-card__explore:hover { text-decoration:underline; }
        </style>

        <div class="oc-dest-grid-wrap" id="<?php echo esc_attr( $uid ); ?>" style="--oc-dg-cols:<?php echo $cols; ?>">
            <?php if ( $s['section_eyebrow'] ) : ?>
                <span class="oc-section-eyebrow"><?php echo esc_html( $s['section_eyebrow'] ); ?></span>
            <?php endif; ?>
            <?php if ( $s['section_heading'] ) : ?>
                <h2 class="oc-section-heading"><?php echo esc_html( $s['section_heading'] ); ?></h2>
            <?php endif; ?>

            <?php if ( $s['show_filter'] === 'yes' && ! is_wp_error( $region_terms ) && ! empty( $region_terms ) ) : ?>
                <div class="oc-dest-grid-filters">
                    <button class="filter-pill is-active" data-filter="all"><?php esc_html_e( 'All', 'ocean-charter' ); ?></button>
                    <?php foreach ( $region_terms as $term ) : ?>
                        <button class="filter-pill" data-filter="<?php echo esc_attr( $term->slug ); ?>"><?php echo esc_html( $term->name ); ?></button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="oc-dest-grid">
                <?php if ( $query->have_posts() ) :
                    while ( $query->have_posts() ) : $query->the_post();
                        $pid         = get_the_ID();
                        $thumb_url   = get_the_post_thumbnail_url( $pid, 'large' );
                        $excerpt     = get_the_excerpt();
                        $vessel_cnt  = get_post_meta( $pid, '_oc_vessel_count', true );
                        $is_popular  = get_post_meta( $pid, '_oc_is_popular', true );
                        $explore_url = get_post_meta( $pid, '_oc_explore_url', true ) ?: get_permalink();
                        // data-region: comma-separated term slugs
                        $card_terms  = get_the_terms( $pid, 'oc_destination_region' );
                        $card_regions = '';
                        if ( $card_terms && ! is_wp_error( $card_terms ) ) {
                            $card_regions = implode( ' ', wp_list_pluck( $card_terms, 'slug' ) );
                        }
                    ?>
                    <div class="dest-card" data-region="<?php echo esc_attr( $card_regions ); ?>">
                        <div class="dest-card__img-wrap">
                            <?php if ( $thumb_url ) : ?>
                                <img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy">
                            <?php else : ?>
                                <div class="dest-card__img-placeholder"></div>
                            <?php endif; ?>
                            <?php if ( $is_popular ) : ?>
                                <span class="dest-card__popular"><?php esc_html_e( 'Popular', 'ocean-charter' ); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="dest-card__body">
                            <h3><?php the_title(); ?></h3>
                            <?php if ( $excerpt ) : ?>
                                <p><?php echo esc_html( $excerpt ); ?></p>
                            <?php endif; ?>
                            <div class="dest-card__footer">
                                <span class="dest-card__vessel-count">
                                    <?php if ( $vessel_cnt ) : ?>
                                        <?php echo esc_html( $vessel_cnt ); ?> <?php esc_html_e( 'vessels', 'ocean-charter' ); ?>
                                    <?php endif; ?>
                                </span>
                                <a href="<?php echo esc_url( $explore_url ); ?>" class="dest-card__explore"><?php esc_html_e( 'Explore →', 'ocean-charter' ); ?></a>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; wp_reset_postdata(); ?>
                <?php else : ?>
                    <p style="color:var(--text-muted,rgba(148,163,184,1));"><?php esc_html_e( 'No destinations found.', 'ocean-charter' ); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <?php if ( $s['show_filter'] === 'yes' ) : ?>
        <script>
        (function(){
            var wrap = document.getElementById('<?php echo esc_js( $uid ); ?>');
            if (!wrap) return;
            var pills = wrap.querySelectorAll('.filter-pill');
            var cards = wrap.querySelectorAll('.dest-card');
            pills.forEach(function(pill){
                pill.addEventListener('click', function(){
                    pills.forEach(function(p){ p.classList.remove('is-active'); });
                    pill.classList.add('is-active');
                    var filter = pill.getAttribute('data-filter');
                    cards.forEach(function(card){
                        if (filter === 'all') {
                            card.classList.remove('is-hidden');
                        } else {
                            var regions = (card.getAttribute('data-region') || '').split(' ');
                            card.classList.toggle('is-hidden', regions.indexOf(filter) === -1);
                        }
                    });
                });
            });
        })();
        </script>
        <?php endif; ?>
        <?php
    }
}

/* ============================================================
   2. OC_Service_Grid_Widget
   ============================================================ */
class OC_Service_Grid_Widget extends \Elementor\Widget_Base {
    use OC_Widget_Style_Trait;

    public function get_name()        { return 'oc-service-grid'; }
    public function get_title()       { return __( 'OC Service Grid', 'ocean-charter' ); }
    public function get_icon()        { return 'eicon-apps'; }
    public function get_categories()  { return [ 'ocean-charter' ]; }

    protected function register_controls() {
        $this->start_controls_section( 'content', [ 'label' => __( 'Content', 'ocean-charter' ) ] );
        $this->add_control( 'posts_count', [
            'label'   => __( 'Posts Count', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::NUMBER,
            'default' => 4,
            'min'     => 1,
        ] );
        $this->add_control( 'columns', [
            'label'   => __( 'Columns', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => '4',
            'options' => [ '1' => '1', '2' => '2', '3' => '3', '4' => '4' ],
        ] );
        $this->add_control( 'excerpt_length', [
            'label'   => __( 'Excerpt Length (chars)', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::NUMBER,
            'default' => 120,
            'min'     => 0,
            'max'     => 400,
        ] );
        $this->add_control( 'section_heading', [
            'label'   => __( 'Section Heading', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'Onboard Services', 'ocean-charter' ),
        ] );
        $this->add_control( 'section_eyebrow', [
            'label'   => __( 'Section Eyebrow', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'The Finest Details', 'ocean-charter' ),
        ] );
        $this->end_controls_section();

        $this->oc_register_heading_style( '.oc-service-grid' );
        $this->oc_register_card_style( '.oc-service-grid .svc-card', 'Service Card' );
        $this->oc_register_title_style( '.oc-service-grid .svc-card__body h3', 'Card Title' );
        $this->oc_register_excerpt_style( '.oc-service-grid .svc-card__excerpt' );
        $this->oc_register_button_style( '.oc-service-grid .svc-card__link' );
        $this->oc_register_grid_style( '.oc-service-grid__grid' );
        $this->oc_register_image_style( '.oc-service-grid .svc-card__img-wrap' );
        $this->oc_register_accent_style( [
            '{{WRAPPER}} .oc-service-grid .svc-card__tag'    => 'color: {{VALUE}}; border-color: {{VALUE}}33;',
            '{{WRAPPER}} .oc-service-grid .svc-card__eyebrow' => 'color: {{VALUE}};',
        ], 'Accent / Tag Color' );
    }

    protected function render() {
        $s     = $this->get_settings_for_display();
        $count = ! empty( $s['posts_count'] ) ? intval( $s['posts_count'] ) : 4;
        $cols  = intval( $s['columns'] ?? 4 );
        $len   = intval( $s['excerpt_length'] ?? 120 );

        $query = new WP_Query( [
            'post_type'      => 'oc_service',
            'posts_per_page' => $count,
            'post_status'    => 'publish',
        ] );
        ?>
        <style>
        .oc-service-grid { width:100%; }
        .oc-service-grid .oc-section-eyebrow { display:block; font-size:0.75rem; font-weight:700; letter-spacing:0.18em; text-transform:uppercase; color:var(--primary,#d9b230); margin-bottom:0.5rem; }
        .oc-service-grid .oc-section-heading { font-family:var(--font-heading,'Playfair Display',serif); font-size:clamp(2rem,3.5vw,3.25rem); color:var(--text,#f0ece3); margin:0 0 2rem; font-weight:400; font-style:italic; }
        .oc-service-grid__grid { display:grid; grid-template-columns:repeat(var(--oc-sg-cols,4),1fr); gap:1.5rem; }
        @media(max-width:900px){.oc-service-grid__grid{grid-template-columns:repeat(2,1fr);}}
        @media(max-width:540px){.oc-service-grid__grid{grid-template-columns:1fr;}}
        .oc-service-grid .svc-card { background:var(--surface,#111a28); border-radius:var(--radius-lg,1rem); overflow:hidden; border:1px solid rgba(255,255,255,0.06); transition:box-shadow 0.3s,transform 0.3s; }
        .oc-service-grid .svc-card:hover { transform:translateY(-4px); box-shadow:0 12px 40px rgba(0,0,0,0.45); border-color:rgba(217,178,48,0.2); }
        .oc-service-grid .svc-card__img-wrap { aspect-ratio:3/2; overflow:hidden; }
        .oc-service-grid .svc-card__img-wrap img { width:100%; height:100%; object-fit:cover; transition:transform 0.5s ease; }
        .oc-service-grid .svc-card:hover .svc-card__img-wrap img { transform:scale(1.05); }
        .oc-service-grid .svc-card__img-placeholder { width:100%; height:100%; background:linear-gradient(135deg,#0d1f35,#1a2d45); }
        .oc-service-grid .svc-card__body { padding:1.5rem; }
        .oc-service-grid .svc-card__eyebrow { display:inline-block; font-size:0.7rem; font-weight:700; letter-spacing:0.14em; text-transform:uppercase; color:var(--primary,#d9b230); margin-bottom:0.5rem; }
        .oc-service-grid .svc-card__body h3 { font-family:var(--font-heading,'Playfair Display',serif); font-size:1.15rem; color:var(--text,#f0ece3); margin:0 0 0.5rem; }
        .oc-service-grid .svc-card__excerpt { font-size:0.875rem; color:var(--text-muted,rgba(148,163,184,1)); line-height:1.6; margin:0 0 1rem; display:-webkit-box; -webkit-box-orient:vertical; -webkit-line-clamp:2; overflow:hidden; max-height:calc(1.6em * 2); }
        .oc-service-grid .svc-card__tags { display:flex; flex-wrap:wrap; gap:0.375rem; margin-bottom:1rem; max-height:calc(1.45rem * 2 + 0.375rem); overflow:hidden; }
        .oc-service-grid .svc-card__tag { font-size:0.6rem; font-weight:600; padding:0.2rem 0.5rem; border-radius:9999px; background:rgba(217,178,48,0.1); color:var(--primary,#d9b230); border:1px solid rgba(217,178,48,0.2); white-space:nowrap; line-height:1; }
        .oc-service-grid .svc-card__link { display:block; text-align:center; font-size:.875rem; font-weight:700; color:var(--primary,#d9b230); text-decoration:none; border:1px solid rgba(217,178,48,.5); padding:.75rem 1rem; border-radius:.5rem; letter-spacing:.06em; text-transform:uppercase; transition:background 0.2s,color 0.2s,border-color .2s; }
        .oc-service-grid .svc-card__link:hover { background:var(--primary,#d9b230); color:#0a0f1a; border-color:var(--primary,#d9b230); }
        </style>

        <?php
        $this->add_render_attribute( 'sg_wrapper', 'class', 'oc-service-grid' );
        $this->add_render_attribute( 'sg_wrapper', 'style', '--oc-sg-cols:' . $cols . ';' );
        ?>
        <div <?php $this->print_render_attribute_string( 'sg_wrapper' ); ?>>
            <?php if ( $s['section_eyebrow'] ) : ?>
                <span class="oc-section-eyebrow"><?php echo esc_html( $s['section_eyebrow'] ); ?></span>
            <?php endif; ?>
            <?php if ( $s['section_heading'] ) : ?>
                <h2 class="oc-section-heading"><?php echo esc_html( $s['section_heading'] ); ?></h2>
            <?php endif; ?>

            <div class="oc-service-grid__grid">
                <?php if ( $query->have_posts() ) :
                    while ( $query->have_posts() ) : $query->the_post();
                        $pid        = get_the_ID();
                        $thumb_url  = get_the_post_thumbnail_url( $pid, 'large' );
                        $eyebrow    = get_post_meta( $pid, '_oc_eyebrow', true );
                        $features   = get_post_meta( $pid, '_oc_features', true );
                        $link_url   = get_permalink();
                        $feat_arr   = $features ? json_decode( $features, true ) : [];
                        $excerpt    = get_the_excerpt();
                        if ( $len > 0 && strlen( $excerpt ) > $len ) { $excerpt = mb_strimwidth( $excerpt, 0, $len, '…' ); }
                    ?>
                    <div class="svc-card">
                        <div class="svc-card__img-wrap">
                            <?php if ( $thumb_url ) : ?>
                                <img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy">
                            <?php else : ?>
                                <div class="svc-card__img-placeholder"></div>
                            <?php endif; ?>
                        </div>
                        <div class="svc-card__body">
                            <?php if ( $eyebrow ) : ?>
                                <span class="svc-card__eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
                            <?php endif; ?>
                            <h3><?php the_title(); ?></h3>
                            <?php if ( $excerpt ) : ?>
                                <p class="svc-card__excerpt"><?php echo esc_html( $excerpt ); ?></p>
                            <?php endif; ?>
                            <?php if ( ! empty( $feat_arr ) ) : ?>
                                <div class="svc-card__tags">
                                    <?php foreach ( $feat_arr as $feat ) : ?>
                                        <span class="svc-card__tag"><?php echo esc_html( $feat ); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            <a href="<?php echo esc_url( $link_url ); ?>" class="svc-card__link"><?php esc_html_e( 'Learn More', 'ocean-charter' ); ?></a>
                        </div>
                    </div>
                    <?php endwhile; wp_reset_postdata(); ?>
                <?php else : ?>
                    <p style="color:var(--text-muted,rgba(148,163,184,1));"><?php esc_html_e( 'No services found.', 'ocean-charter' ); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}

/* ============================================================
   3. OC_Package_Grid_Widget
   ============================================================ */
class OC_Package_Grid_Widget extends \Elementor\Widget_Base {
    use OC_Widget_Style_Trait;

    public function get_name()        { return 'oc-package-grid'; }
    public function get_title()       { return __( 'OC Package Grid', 'ocean-charter' ); }
    public function get_icon()        { return 'eicon-price-table'; }
    public function get_categories()  { return [ 'ocean-charter' ]; }

    protected function register_controls() {
        $this->start_controls_section( 'content', [ 'label' => __( 'Content', 'ocean-charter' ) ] );
        $this->add_control( 'posts_count', [
            'label'   => __( 'Posts Count', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::NUMBER,
            'default' => 3,
            'min'     => 1,
        ] );
        $this->add_control( 'columns', [
            'label'   => __( 'Columns', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => '3',
            'options' => [ '1' => '1', '2' => '2', '3' => '3', '4' => '4' ],
        ] );
        $this->add_control( 'excerpt_length', [
            'label'   => __( 'Excerpt Length (chars)', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::NUMBER,
            'default' => 120,
            'min'     => 0,
            'max'     => 400,
        ] );
        $this->add_control( 'section_heading', [
            'label'   => __( 'Section Heading', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'Charter Packages', 'ocean-charter' ),
        ] );
        $this->add_control( 'section_eyebrow', [
            'label'   => __( 'Section Eyebrow', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'Curated Experiences', 'ocean-charter' ),
        ] );
        $this->add_control( 'show_filter', [
            'label'        => __( 'Show Type Filter', 'ocean-charter' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ] );
        $this->end_controls_section();

        $this->oc_register_heading_style( '.oc-package-grid' );
        $this->oc_register_card_style( '.oc-package-grid .pkg-card', 'Package Card' );
        $this->oc_register_title_style( '.oc-package-grid .pkg-card__body h3' );
        $this->oc_register_excerpt_style( '.oc-package-grid .pkg-card__excerpt' );
        $this->oc_register_button_style( '.oc-package-grid .pkg-card__cta' );
        $this->oc_register_grid_style( '.oc-package-grid__grid' );
        $this->oc_register_image_style( '.oc-package-grid .pkg-card__img-wrap' );

        // ── Filter Tab Pills ────────────────────────────────────────────────
        $this->start_controls_section( 'style_filter_tabs', [
            'label' => __( 'Filter Tabs', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'filter_tab_typography',
            'label'    => __( 'Tab Typography', 'ocean-charter' ),
            'selector' => '{{WRAPPER}} .oc-package-grid .filter-pill',
        ] );
        $this->add_control( 'filter_tab_color', [
            'label'     => __( 'Text Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .oc-package-grid .filter-pill' => 'color: {{VALUE}};' ],
        ] );
        $this->add_control( 'filter_tab_color_active', [
            'label'     => __( 'Active Text Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#0a0f1a',
            'selectors' => [ '{{WRAPPER}} .oc-package-grid .filter-pill.is-active, {{WRAPPER}} .oc-package-grid .filter-pill:hover' => 'color: {{VALUE}};' ],
        ] );
        $this->add_control( 'filter_tab_bg', [
            'label'     => __( 'Background', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .oc-package-grid .filter-pill' => 'background: {{VALUE}};' ],
        ] );
        $this->add_control( 'filter_tab_bg_active', [
            'label'     => __( 'Active Background', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#d9b230',
            'selectors' => [ '{{WRAPPER}} .oc-package-grid .filter-pill.is-active, {{WRAPPER}} .oc-package-grid .filter-pill:hover' => 'background: {{VALUE}}; border-color: {{VALUE}};' ],
        ] );
        $this->add_control( 'filter_tab_border_color', [
            'label'     => __( 'Border Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .oc-package-grid .filter-pill' => 'border-color: {{VALUE}};' ],
        ] );
        $this->add_control( 'filter_tab_radius', [
            'label'      => __( 'Border Radius', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'rem' ],
            'default'    => [ 'size' => 9999, 'unit' => 'px' ],
            'selectors'  => [ '{{WRAPPER}} .oc-package-grid .filter-pill' => 'border-radius: {{SIZE}}{{UNIT}};' ],
        ] );
        $this->add_responsive_control( 'filter_tab_padding', [
            'label'      => __( 'Padding', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em', 'rem' ],
            'selectors'  => [ '{{WRAPPER}} .oc-package-grid .filter-pill' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );
        $this->add_responsive_control( 'filter_tabs_gap', [
            'label'      => __( 'Gap Between Tabs', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'rem' ],
            'default'    => [ 'size' => 8, 'unit' => 'px' ],
            'selectors'  => [ '{{WRAPPER}} .oc-package-grid .oc-pkg-filters' => 'gap: {{SIZE}}{{UNIT}};' ],
        ] );
        $this->end_controls_section();

        // ── Price & Inclusions ──────────────────────────────────────────────
        $this->start_controls_section( 'style_price_inclusions', [
            'label' => __( 'Price & Inclusions', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'price_typography',
            'label'    => __( 'Price Typography', 'ocean-charter' ),
            'selector' => '{{WRAPPER}} .oc-package-grid .pkg-card__price',
        ] );
        $this->add_control( 'price_color', [
            'label'     => __( 'Price Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#d9b230',
            'selectors' => [ '{{WRAPPER}} .oc-package-grid .pkg-card__price' => 'color: {{VALUE}};' ],
        ] );
        $this->add_control( 'duration_color', [
            'label'     => __( 'Duration Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .oc-package-grid .pkg-card__duration' => 'color: {{VALUE}};' ],
        ] );
        $this->add_control( 'inclusion_icon_color', [
            'label'     => __( 'Inclusion Check Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#d9b230',
            'selectors' => [ '{{WRAPPER}} .oc-package-grid .pkg-card__inclusions path[stroke]' => 'stroke: {{VALUE}};',
                             '{{WRAPPER}} .oc-package-grid .pkg-card__inclusions circle' => 'fill: color-mix(in srgb, {{VALUE}} 15%, transparent);' ],
        ] );
        $this->add_control( 'inclusion_text_color', [
            'label'     => __( 'Inclusion Text Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .oc-package-grid .pkg-card__inclusions li' => 'color: {{VALUE}};' ],
        ] );
        $this->add_responsive_control( 'inclusion_item_spacing', [
            'label'      => __( 'Item Spacing', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'rem' ],
            'selectors'  => [ '{{WRAPPER}} .oc-package-grid .pkg-card__inclusions' => 'gap: {{SIZE}}{{UNIT}};' ],
        ] );
        $this->end_controls_section();
    }

    protected function render() {
        $s     = $this->get_settings_for_display();
        $count = ! empty( $s['posts_count'] ) ? intval( $s['posts_count'] ) : 3;
        $cols  = intval( $s['columns'] ?? 3 );
        $len   = intval( $s['excerpt_length'] ?? 120 );

        /* Query BBC packages first, fall back to theme oc_package */
        $query = new WP_Query( [
            'post_type'      => 'bbc_package',
            'posts_per_page' => $count,
            'post_status'    => 'publish',
        ] );
        $using_bbc = $query->have_posts();
        if ( ! $using_bbc ) {
            $query = new WP_Query( [
                'post_type'      => 'oc_package',
                'posts_per_page' => $count,
                'post_status'    => 'publish',
            ] );
        }

        $type_terms = get_terms( [ 'taxonomy' => 'oc_package_type', 'hide_empty' => true ] );
        $uid = 'ocpg-' . $this->get_id();
        ?>
        <style>
        .oc-package-grid { width:100%; }
        .oc-package-grid .oc-section-eyebrow { display:block; font-size:0.75rem; font-weight:700; letter-spacing:0.18em; text-transform:uppercase; color:var(--primary,#d9b230); margin-bottom:0.5rem; }
        .oc-package-grid .oc-section-heading { font-family:var(--font-heading,'Playfair Display',serif); font-size:clamp(2rem,3.5vw,3.25rem); color:var(--text,#f0ece3); margin:0 0 2rem; font-weight:400; font-style:italic; }
        .oc-package-grid .oc-pkg-filters { display:flex; flex-wrap:wrap; gap:0.5rem; margin-bottom:2rem; }
        .oc-package-grid .filter-pill { padding:0.4rem 1.1rem; border-radius:9999px; font-size:0.8125rem; font-weight:600; cursor:pointer; border:1px solid rgba(217,178,48,0.3); background:rgba(255,255,255,0.05); color:var(--text-muted,rgba(148,163,184,1)); transition:background 0.2s,color 0.2s; }
        .oc-package-grid .filter-pill.is-active,
        .oc-package-grid .filter-pill:hover { background:var(--primary,#d9b230); color:#0a0f1a; border-color:var(--primary,#d9b230); }
        .oc-package-grid__grid { display:grid; grid-template-columns:repeat(var(--oc-pg-cols,3),1fr); gap:1.5rem; }
        @media(max-width:900px){.oc-package-grid__grid{grid-template-columns:repeat(2,1fr);}}
        @media(max-width:540px){.oc-package-grid__grid{grid-template-columns:1fr;}}
        .oc-package-grid .pkg-card { background:var(--surface,#111a28); border-radius:var(--radius-lg,1rem); overflow:hidden; border:1px solid rgba(255,255,255,0.06); transition:box-shadow 0.3s,transform 0.3s; }
        .oc-package-grid .pkg-card:hover { transform:translateY(-4px); box-shadow:0 12px 40px rgba(0,0,0,0.45); }
        .oc-package-grid .pkg-card.is-hidden { display:none; }
        .oc-package-grid .pkg-card__img-wrap { position:relative; aspect-ratio:3/2; overflow:hidden; }
        .oc-package-grid .pkg-card__img-wrap img { width:100%; height:100%; object-fit:cover; transition:transform 0.5s ease; }
        .oc-package-grid .pkg-card:hover .pkg-card__img-wrap img { transform:scale(1.05); }
        .oc-package-grid .pkg-card__img-placeholder { width:100%; height:100%; background:linear-gradient(135deg,#0d1f35,#1a2d45); }
        .oc-package-grid .pkg-card__tag { position:absolute; top:1rem; right:1rem; background:var(--primary,#d9b230); color:#0a0f1a; font-size:0.7rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; padding:0.25rem 0.7rem; border-radius:9999px; }
        .oc-package-grid .pkg-card__body { padding:1.5rem; }
        .oc-package-grid .pkg-card__body h3 { font-family:var(--font-heading,'Playfair Display',serif); font-size:1.2rem; color:var(--text,#f0ece3); margin:0 0 0.5rem; font-weight:600; }
        .oc-package-grid .pkg-card__excerpt { font-size:0.875rem; color:var(--text-muted,rgba(148,163,184,1)); line-height:1.6; margin:0 0 1rem; }
        .oc-package-grid .pkg-card__price-row { display:flex; align-items:baseline; gap:0.5rem; margin-bottom:1rem; padding-bottom:0.875rem; border-bottom:1px solid rgba(255,255,255,0.07); }
        .oc-package-grid .pkg-card__price { font-family:var(--font-heading,'Playfair Display',serif); font-size:1.5rem; color:var(--primary,#d9b230); }
        .oc-package-grid .pkg-card__duration { font-size:0.8rem; color:var(--text-muted,rgba(148,163,184,1)); }
        .oc-package-grid .pkg-card__inclusions { list-style:none; padding:0; margin:0 0 1.25rem; display:grid; grid-template-columns:repeat(2,1fr); gap:.25rem .75rem; }
        .oc-package-grid .pkg-card__inclusions li { font-size:0.8rem; color:var(--text-muted,rgba(148,163,184,1)); padding:0.3rem 0; display:flex; align-items:center; gap:0.35rem; }
        .oc-package-grid .pkg-card__inclusions svg { flex-shrink:0; }
        @media(max-width:540px){.oc-package-grid .pkg-card__inclusions{grid-template-columns:1fr}}
        .oc-package-grid .pkg-card__cta { display:block; text-align:center; background:var(--primary,#d9b230); color:#0a0f1a; font-size:0.875rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; padding:0.7rem 1.5rem; border-radius:9999px; text-decoration:none; transition:opacity 0.2s; }
        .oc-package-grid .pkg-card__cta:hover { opacity:0.88; }
        </style>

        <?php
        $this->add_render_attribute( 'pg_wrapper', 'class', 'oc-package-grid' );
        $this->add_render_attribute( 'pg_wrapper', 'id', esc_attr( $uid ) );
        $this->add_render_attribute( 'pg_wrapper', 'style', '--oc-pg-cols:' . $cols . ';' );
        ?>
        <div <?php $this->print_render_attribute_string( 'pg_wrapper' ); ?>>
            <?php if ( $s['section_eyebrow'] ) : ?>
                <span class="oc-section-eyebrow"><?php echo esc_html( $s['section_eyebrow'] ); ?></span>
            <?php endif; ?>
            <?php if ( $s['section_heading'] ) : ?>
                <h2 class="oc-section-heading"><?php echo esc_html( $s['section_heading'] ); ?></h2>
            <?php endif; ?>

            <?php if ( $s['show_filter'] === 'yes' && ! is_wp_error( $type_terms ) && ! empty( $type_terms ) ) : ?>
                <div class="oc-pkg-filters">
                    <button class="filter-pill is-active" data-filter="all"><?php esc_html_e( 'All', 'ocean-charter' ); ?></button>
                    <?php foreach ( $type_terms as $term ) : ?>
                        <button class="filter-pill" data-filter="<?php echo esc_attr( $term->slug ); ?>"><?php echo esc_html( $term->name ); ?></button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="oc-package-grid__grid">
                <?php if ( $query->have_posts() ) :
                    while ( $query->have_posts() ) : $query->the_post();
                        $pid        = get_the_ID();
                        $thumb_url  = get_the_post_thumbnail_url( $pid, 'large' );
                        $cta_url    = get_permalink();

                        if ( $using_bbc ) {
                            /* BBC package meta */
                            $tag        = get_post_meta( $pid, '_bbc_pkg_label', true );
                            $pkg_price  = floatval( get_post_meta( $pid, '_bbc_pkg_price', true ) );
                            $price      = $pkg_price > 0 ? '$' . number_format( $pkg_price, 0 ) : '';
                            $durations  = get_post_meta( $pid, '_bbc_pkg_durations', true );
                            $duration   = '';
                            if ( ! empty( $durations ) && is_array( $durations ) && isset( $durations[0]['label'] ) ) {
                                $duration = $durations[0]['label'];
                            }
                            $features = get_post_meta( $pid, '_bbc_pkg_features', true );
                            $inc_arr  = is_array( $features ) ? $features : [];
                            if ( ! $thumb_url ) {
                                $gallery = get_post_meta( $pid, '_bbc_pkg_gallery', true );
                                if ( ! empty( $gallery ) && is_array( $gallery ) ) {
                                    $thumb_url = wp_get_attachment_image_url( $gallery[0], 'large' );
                                }
                            }
                        } else {
                            /* OC theme package meta */
                            $tag        = get_post_meta( $pid, '_oc_tag', true );
                            $price      = get_post_meta( $pid, '_oc_price', true );
                            $duration   = get_post_meta( $pid, '_oc_duration', true );
                            $inclusions = get_post_meta( $pid, '_oc_inclusions', true );
                            $inc_arr    = $inclusions ? json_decode( $inclusions, true ) : [];
                        }
                        $card_terms = get_the_terms( $pid, 'oc_package_type' );
                        $card_types = '';
                        if ( $card_terms && ! is_wp_error( $card_terms ) ) {
                            $card_types = implode( ' ', wp_list_pluck( $card_terms, 'slug' ) );
                        }
                        $excerpt = get_the_excerpt();
                        if ( $len > 0 && mb_strlen( $excerpt ) > $len ) { $excerpt = mb_strimwidth( $excerpt, 0, $len, '…' ); }
                    ?>
                    <div class="pkg-card" data-type="<?php echo esc_attr( $card_types ); ?>">
                        <div class="pkg-card__img-wrap">
                            <?php if ( $thumb_url ) : ?>
                                <img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy">
                            <?php else : ?>
                                <div class="pkg-card__img-placeholder"></div>
                            <?php endif; ?>
                            <?php if ( $tag ) : ?>
                                <span class="pkg-card__tag"><?php echo esc_html( $tag ); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="pkg-card__body">
                            <h3><?php the_title(); ?></h3>
                            <?php if ( $excerpt ) : ?>
                                <p class="pkg-card__excerpt"><?php echo esc_html( $excerpt ); ?></p>
                            <?php endif; ?>
                            <?php if ( $price || $duration ) : ?>
                                <div class="pkg-card__price-row">
                                    <?php if ( $price ) : ?>
                                        <span class="pkg-card__price"><?php echo esc_html( $price ); ?></span>
                                    <?php endif; ?>
                                    <?php if ( $duration ) : ?>
                                        <span class="pkg-card__duration"><?php echo esc_html( $duration ); ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <?php if ( ! empty( $inc_arr ) ) : ?>
                                <ul class="pkg-card__inclusions">
                                    <?php foreach ( $inc_arr as $item ) : ?>
                                        <li>
                                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="7" cy="7" r="7" fill="rgba(217,178,48,0.15)"/><path d="M4 7l2 2 4-4" stroke="#d9b230" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            <?php echo esc_html( $item ); ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                            <a href="<?php echo esc_url( $cta_url ); ?>" class="pkg-card__cta"><?php esc_html_e( 'View Package', 'ocean-charter' ); ?></a>
                        </div>
                    </div>
                    <?php endwhile; wp_reset_postdata(); ?>
                <?php else : ?>
                    <p style="color:var(--text-muted,rgba(148,163,184,1));"><?php esc_html_e( 'No packages found.', 'ocean-charter' ); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <?php if ( $s['show_filter'] === 'yes' ) : ?>
        <script>
        (function(){
            var wrap = document.getElementById('<?php echo esc_js( $uid ); ?>');
            if (!wrap) return;
            var pills = wrap.querySelectorAll('.filter-pill');
            var cards = wrap.querySelectorAll('.pkg-card');
            pills.forEach(function(pill){
                pill.addEventListener('click', function(){
                    pills.forEach(function(p){ p.classList.remove('is-active'); });
                    pill.classList.add('is-active');
                    var filter = pill.getAttribute('data-filter');
                    cards.forEach(function(card){
                        if (filter === 'all') {
                            card.classList.remove('is-hidden');
                        } else {
                            var types = (card.getAttribute('data-type') || '').split(' ');
                            card.classList.toggle('is-hidden', types.indexOf(filter) === -1);
                        }
                    });
                });
            });
        })();
        </script>
        <?php endif; ?>
        <?php
    }
}

/* ============================================================
   4. OC_Testimonial_Carousel_Widget
   ============================================================ */
class OC_Testimonial_Carousel_Widget extends \Elementor\Widget_Base {
    use OC_Widget_Style_Trait;

    public function get_name()        { return 'oc-testimonial-carousel'; }
    public function get_title()       { return __( 'OC Testimonial Carousel', 'ocean-charter' ); }
    public function get_icon()        { return 'eicon-testimonial-carousel'; }
    public function get_categories()  { return [ 'ocean-charter' ]; }

    protected function register_controls() {
        $this->start_controls_section( 'content', [ 'label' => __( 'Content', 'ocean-charter' ) ] );
        $this->add_control( 'posts_count', [
            'label'   => __( 'Posts Count', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::NUMBER,
            'default' => 6,
            'min'     => 1,
        ] );
        $this->add_control( 'featured_only', [
            'label'        => __( 'Featured Only', 'ocean-charter' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => '',
        ] );
        $this->add_control( 'section_heading', [
            'label'   => __( 'Heading', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'Guest Experiences', 'ocean-charter' ),
        ] );
        $this->add_control( 'section_eyebrow', [
            'label'   => __( 'Eyebrow', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'Reflections', 'ocean-charter' ),
        ] );
        $this->add_control( 'autoplay_speed', [
            'label'   => __( 'Autoplay Speed (ms)', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::NUMBER,
            'default' => 5000,
            'min'     => 1000,
            'max'     => 15000,
        ] );
        $this->end_controls_section();

        $this->oc_register_heading_style( '.oc-testi-hero' );
        $this->oc_register_excerpt_style( '.oc-testi-hero .oc-testi-hero__quote', 'Quote' );

        // ── Style: Avatar ─────────────────────────────────────────────────────
        $this->start_controls_section( 'style_avatar', [
            'label' => __( 'Avatar / Thumbnail', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_control( 'avatar_shape', [
            'label'   => __( 'Shape', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => 'circle',
            'options' => [
                'circle'  => __( 'Circle', 'ocean-charter' ),
                'rounded' => __( 'Rounded Square', 'ocean-charter' ),
                'square'  => __( 'Square', 'ocean-charter' ),
            ],
            'selectors_dictionary' => [
                'circle'  => '50%',
                'rounded' => '12px',
                'square'  => '0',
            ],
            'selectors' => [
                '{{WRAPPER}} .oc-testi-hero__avatar-wrap' => 'border-radius: {{VALUE}};',
                '{{WRAPPER}} .oc-testi-hero__avatar'      => 'border-radius: {{VALUE}};',
                '{{WRAPPER}} .oc-testi-hero__avatar-placeholder' => 'border-radius: {{VALUE}};',
            ],
        ] );
        $this->add_control( 'avatar_size', [
            'label'      => __( 'Size (px)', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 40, 'max' => 160, 'step' => 2 ] ],
            'default'    => [ 'unit' => 'px', 'size' => 64 ],
            'selectors'  => [
                '{{WRAPPER}} .oc-testi-hero__avatar-wrap' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ] );
        $this->add_control( 'avatar_ring_width', [
            'label'      => __( 'Ring / Border Width (px)', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 8, 'step' => 1 ] ],
            'default'    => [ 'unit' => 'px', 'size' => 2 ],
            'selectors'  => [
                '{{WRAPPER}} .oc-testi-hero__avatar-wrap' => 'padding: {{SIZE}}{{UNIT}};',
            ],
        ] );
        $this->add_control( 'avatar_ring_color', [
            'label'     => __( 'Ring / Border Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#d9b230',
            'selectors' => [
                '{{WRAPPER}} .oc-testi-hero__avatar-wrap' => 'background: {{VALUE}};',
            ],
        ] );
        $this->add_control( 'avatar_ring_gradient', [
            'label'        => __( 'Use Gradient Ring', 'ocean-charter' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
            'selectors'    => [
                '{{WRAPPER}} .oc-testi-hero__avatar-wrap' => 'background: linear-gradient(135deg, {{avatar_ring_color.VALUE}}, rgba(217,178,48,0.4));',
            ],
            'condition'    => [ 'avatar_ring_gradient' => 'yes' ],
        ] );
        $this->end_controls_section();

        // Author name style
        $this->start_controls_section( 'style_author', [
            'label' => __( 'Author Name', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'author_typography',
            'label'    => __( 'Typography', 'ocean-charter' ),
            'selector' => '{{WRAPPER}} .oc-testi-hero .oc-testi-hero__name',
        ] );
        $this->add_control( 'author_color', [
            'label'     => __( 'Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#f0ece3',
            'selectors' => [ '{{WRAPPER}} .oc-testi-hero .oc-testi-hero__name' => 'color: {{VALUE}};' ],
        ] );
        $this->add_control( 'role_color', [
            'label'     => __( 'Role Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#d9b230',
            'selectors' => [ '{{WRAPPER}} .oc-testi-hero .oc-testi-hero__role' => 'color: {{VALUE}};' ],
        ] );
        $this->add_control( 'deco_color', [
            'label'     => __( 'Decorative Mark Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => 'rgba(217,178,48,0.18)',
            'selectors' => [ '{{WRAPPER}} .oc-testi-hero .oc-testi-hero__deco' => 'color: {{VALUE}};' ],
        ] );
        $this->end_controls_section();
    }

    protected function render() {
        $s     = $this->get_settings_for_display();
        $count = ! empty( $s['posts_count'] ) ? intval( $s['posts_count'] ) : 6;

        $args = [
            'post_type'      => 'oc_testimonial',
            'posts_per_page' => $count,
            'post_status'    => 'publish',
        ];
        if ( $s['featured_only'] === 'yes' ) {
            $args['meta_query'] = [ [ 'key' => '_oc_is_featured', 'value' => '1', 'compare' => '=' ] ];
        }
        $query = new WP_Query( $args );
        ?>
        <style>
        .oc-testi-hero{width:100%;text-align:center}
        .oc-testi-hero .oc-section-eyebrow{display:block;font-size:.75rem;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:var(--primary,#d9b230);margin-bottom:.5rem}
        .oc-testi-hero .oc-section-heading{font-family:var(--font-heading,'Playfair Display',serif);font-size:clamp(2rem,3.5vw,3.25rem);color:var(--text,#f0ece3);margin:0 0 3rem;font-weight:400;font-style:italic}
        .oc-testi-hero__stage{position:relative;min-height:220px;overflow:hidden}
        .oc-testi-hero__item{position:absolute;inset:0;opacity:0;transform:translateY(12px);transition:opacity .5s ease,transform .5s ease;pointer-events:none}
        .oc-testi-hero__item.is-active{position:relative;opacity:1;transform:translateY(0);pointer-events:auto}
        .oc-testi-hero__stars{display:flex;justify-content:center;gap:.25rem;margin-bottom:1.25rem}
        .oc-testi-hero__stars svg{width:20px;height:20px;fill:var(--primary,#d9b230);color:var(--primary,#d9b230)}
        .oc-testi-hero__deco{display:block;font-size:7rem;line-height:.8;color:rgba(217,178,48,.15);font-family:Georgia,serif;max-width:860px;margin:0 auto -2rem;pointer-events:none;user-select:none}
        .oc-testi-hero__quote{font-family:var(--font-heading,'Playfair Display',serif);font-size:clamp(1.1rem,2.2vw,1.5rem);font-style:italic;color:var(--text,#f0ece3);line-height:1.75;max-width:780px;margin:0 auto 2rem;padding:0 1.5rem}
        .oc-testi-hero__author{display:flex;flex-direction:column;align-items:center;gap:.5rem}
        .oc-testi-hero__avatar-wrap{width:64px;height:64px;border-radius:50%;padding:2px;background:linear-gradient(135deg,var(--primary,#d9b230),rgba(217,178,48,0.4));flex-shrink:0;box-sizing:border-box;overflow:hidden;aspect-ratio:1/1}
        .oc-testi-hero__avatar{width:100%;height:100%;border-radius:inherit;object-fit:cover;display:block;aspect-ratio:1/1}
        .oc-testi-hero__avatar-placeholder{width:100%;height:100%;border-radius:inherit;background:linear-gradient(135deg,#0d1f35,#1a3352);display:block;aspect-ratio:1/1}
        .oc-testi-hero__name{font-size:.8125rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--text,#f0ece3);margin:0}
        .oc-testi-hero__role{font-size:.75rem;color:var(--primary,#d9b230);margin:0}
        .oc-testi-hero__nav{display:flex;align-items:center;justify-content:center;gap:1.5rem;margin-top:2.5rem}
        .oc-testi-hero__btn{width:44px;height:44px;border-radius:50%;border:1px solid rgba(217,178,48,.3);background:rgba(10,15,26,0.5);backdrop-filter:blur(8px);color:var(--primary,#d9b230);font-size:1.1rem;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background .2s,color .2s,transform .2s}
        .oc-testi-hero__btn:hover{background:var(--primary,#d9b230);color:#0a0f1a;transform:scale(1.1)}
        .oc-testi-hero__dots{display:flex;gap:.5rem;align-items:center}
        .oc-testi-hero__dots button{width:8px;height:8px;border-radius:50%;border:none;background:rgba(217,178,48,.25);cursor:pointer;padding:0;transition:background .3s,transform .3s,width .3s}
        .oc-testi-hero__dots button.is-active{background:var(--primary,#d9b230);transform:scale(1.3);width:24px;border-radius:4px}
        .oc-testi-hero__counter{font-size:.75rem;color:var(--text-muted,rgba(148,163,184,1));letter-spacing:.05em;margin-top:1rem}
        </style>

        <?php
        $uid_tc = 'octc-' . $this->get_id();
        $total_cards = $query->post_count;
        $autoplay_speed = intval( $s['autoplay_speed'] ?? 5000 );
        ?>
        <div class="oc-testi-hero" id="<?php echo esc_attr( $uid_tc ); ?>" data-autoplay="<?php echo esc_attr( $autoplay_speed ); ?>">
            <?php if ( ! empty( $s['section_eyebrow'] ) ) : ?>
                <span class="oc-section-eyebrow"><?php echo esc_html( $s['section_eyebrow'] ); ?></span>
            <?php endif; ?>
            <?php if ( ! empty( $s['section_heading'] ) ) : ?>
                <h2 class="oc-section-heading"><?php echo esc_html( $s['section_heading'] ); ?></h2>
            <?php endif; ?>

            <div class="oc-testi-hero__stage">
                <?php if ( $query->have_posts() ) :
                    $testi_idx = 0;
                    while ( $query->have_posts() ) : $query->the_post();
                        $pid      = get_the_ID();
                        $quote    = get_post_meta( $pid, '_oc_quote', true ) ?: get_the_content();
                        $role     = get_post_meta( $pid, '_oc_author_role', true );
                        $location = get_post_meta( $pid, '_oc_charter_location', true );
                        // Prefer the dedicated Author Photo meta, fall back to the CPT featured image.
                        $avatar   = get_post_meta( $pid, '_oc_avatar_photo', true );
                        if ( ! $avatar ) {
                            $avatar = get_the_post_thumbnail_url( $pid, 'thumbnail' );
                        }
                        $role_loc = implode( ' · ', array_filter( [ $role, $location ] ) );
                        $active   = $testi_idx === 0 ? ' is-active' : '';
                ?>
                <?php
                    $rating = get_post_meta( $pid, '_oc_rating', true );
                    $stars  = $rating ? intval( $rating ) : 5;
                ?>
                <div class="oc-testi-hero__item<?php echo $active; ?>" data-testi-idx="<?php echo $testi_idx; ?>">
                    <span class="oc-testi-hero__deco">&#10077;</span>
                    <div class="oc-testi-hero__stars">
                        <?php for ( $star_i = 0; $star_i < $stars; $star_i++ ) : ?>
                            <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        <?php endfor; ?>
                    </div>
                    <?php if ( $quote ) : ?>
                        <blockquote class="oc-testi-hero__quote"><?php echo esc_html( wp_strip_all_tags( $quote ) ); ?></blockquote>
                    <?php endif; ?>
                    <div class="oc-testi-hero__author">
                        <div class="oc-testi-hero__avatar-wrap">
                            <?php if ( $avatar ) : ?><img class="oc-testi-hero__avatar" src="<?php echo esc_url( $avatar ); ?>" alt="<?php the_title_attribute(); ?>"><?php else : ?><span class="oc-testi-hero__avatar-placeholder"></span><?php endif; ?>
                        </div>
                        <p class="oc-testi-hero__name"><?php the_title(); ?></p>
                        <?php if ( $role_loc ) : ?><p class="oc-testi-hero__role"><?php echo esc_html( $role_loc ); ?></p><?php endif; ?>
                    </div>
                </div>
                <?php $testi_idx++; endwhile; wp_reset_postdata(); ?>
                <?php else : ?>
                    <p style="color:var(--text-muted,rgba(148,163,184,1));text-align:center;"><?php esc_html_e( 'No testimonials found.', 'ocean-charter' ); ?></p>
                <?php endif; ?>
            </div>

            <?php if ( $total_cards > 1 ) : ?>
            <div class="oc-testi-hero__nav">
                <button class="oc-testi-hero__btn oc-testi-hero__prev" aria-label="Previous">&#8592;</button>
                <div class="oc-testi-hero__dots">
                    <?php for ( $i = 0; $i < $total_cards; $i++ ) : ?>
                        <button class="<?php echo $i === 0 ? 'is-active' : ''; ?>" data-idx="<?php echo $i; ?>"></button>
                    <?php endfor; ?>
                </div>
                <button class="oc-testi-hero__btn oc-testi-hero__next" aria-label="Next">&#8594;</button>
            </div>
            <div class="oc-testi-hero__counter">1 / <?php echo $total_cards; ?></div>
            <?php endif; ?>
        </div>
        <script>
        (function(){
            var wrap  = document.getElementById('<?php echo esc_js( $uid_tc ); ?>');
            if (!wrap) return;
            var items = wrap.querySelectorAll('.oc-testi-hero__item');
            var dots  = wrap.querySelectorAll('.oc-testi-hero__dots button');
            var prev  = wrap.querySelector('.oc-testi-hero__prev');
            var next  = wrap.querySelector('.oc-testi-hero__next');
            var counter = wrap.querySelector('.oc-testi-hero__counter');
            var n = items.length;
            if (!n) return;
            var idx = 0;
            var autoplayMs = parseInt(wrap.getAttribute('data-autoplay'), 10) || 5000;
            function goTo(i) {
                items[idx].classList.remove('is-active');
                if (dots[idx]) dots[idx].classList.remove('is-active');
                idx = (i + n) % n;
                items[idx].classList.add('is-active');
                if (dots[idx]) dots[idx].classList.add('is-active');
                if (counter) counter.textContent = (idx + 1) + ' / ' + n;
            }
            if (prev) prev.addEventListener('click', function(){ goTo(idx - 1); });
            if (next) next.addEventListener('click', function(){ goTo(idx + 1); });
            dots.forEach(function(d,j){ d.addEventListener('click', function(){ goTo(j); }); });
            var timer = setInterval(function(){ goTo(idx + 1); }, autoplayMs);
            wrap.addEventListener('mouseenter', function(){ clearInterval(timer); });
            wrap.addEventListener('mouseleave', function(){ timer = setInterval(function(){ goTo(idx + 1); }, autoplayMs); });
            // Touch / swipe support
            var touchStartX = 0;
            var stage = wrap.querySelector('.oc-testi-hero__stage');
            if (stage) {
                stage.addEventListener('touchstart', function(e){ touchStartX = e.changedTouches[0].clientX; }, {passive:true});
                stage.addEventListener('touchend', function(e){
                    var diff = e.changedTouches[0].clientX - touchStartX;
                    if (Math.abs(diff) > 50) { diff > 0 ? goTo(idx - 1) : goTo(idx + 1); }
                }, {passive:true});
            }
        })();
        </script>
        <?php
    }
}

/* ============================================================
   5. OC_Team_Grid_Widget
   ============================================================ */
class OC_Team_Grid_Widget extends \Elementor\Widget_Base {
    public function get_name()        { return 'oc-team-grid'; }
    public function get_title()       { return __( 'OC Team Grid', 'ocean-charter' ); }
    public function get_icon()        { return 'eicon-person'; }
    public function get_categories()  { return [ 'ocean-charter' ]; }

    protected function register_controls() {
        $this->start_controls_section( 'content', [ 'label' => __( 'Content', 'ocean-charter' ) ] );
        $this->add_control( 'posts_count', [
            'label'   => __( 'Posts Count', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::NUMBER,
            'default' => 8,
            'min'     => 1,
        ] );
        $this->add_control( 'role_filter', [
            'label'       => __( 'Filter by Role Slug', 'ocean-charter' ),
            'type'        => \Elementor\Controls_Manager::TEXT,
            'placeholder' => __( 'oc_team_role slug', 'ocean-charter' ),
            'default'     => '',
        ] );
        $this->add_control( 'section_heading', [
            'label'   => __( 'Section Heading', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'Meet The Crew', 'ocean-charter' ),
        ] );
        $this->add_control( 'section_eyebrow', [
            'label'   => __( 'Section Eyebrow', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'Expert Hands', 'ocean-charter' ),
        ] );
        $this->end_controls_section();

        /* ── Style: Avatar ──────────────────────────────────── */
        $this->start_controls_section( 'style_avatar', [
            'label' => __( 'Avatar', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_responsive_control( 'avatar_size', [
            'label'      => __( 'Size', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 60, 'max' => 300 ] ],
            'default'    => [ 'size' => 160, 'unit' => 'px' ],
            'selectors'  => [ '{{WRAPPER}} .team-card__avatar-wrap' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};' ],
        ] );
        $this->add_control( 'avatar_shape', [
            'label'   => __( 'Shape', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => 'circle',
            'options' => [
                'circle'    => __( 'Circle', 'ocean-charter' ),
                'square'    => __( 'Square', 'ocean-charter' ),
                'rounded'   => __( 'Rounded Square', 'ocean-charter' ),
            ],
        ] );
        $this->add_control( 'avatar_border_color', [
            'label'     => __( 'Border Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => 'rgba(217,178,48,0.25)',
            'selectors' => [ '{{WRAPPER}} .team-card__avatar-wrap' => 'border-color: {{VALUE}};' ],
        ] );
        $this->add_responsive_control( 'avatar_border_width', [
            'label'      => __( 'Border Width', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 10 ] ],
            'default'    => [ 'size' => 3, 'unit' => 'px' ],
            'selectors'  => [ '{{WRAPPER}} .team-card__avatar-wrap' => 'border-width: {{SIZE}}{{UNIT}}; border-style: solid;' ],
        ] );
        $this->end_controls_section();

        /* ── Style: Card ────────────────────────────────────── */
        $this->start_controls_section( 'style_team_card', [
            'label' => __( 'Card', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_responsive_control( 'card_padding', [
            'label'      => __( 'Card Padding', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'rem' ],
            'selectors'  => [ '{{WRAPPER}} .team-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );
        $this->add_control( 'card_alignment', [
            'label'   => __( 'Alignment', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'left'   => [ 'title' => __( 'Left', 'ocean-charter' ),   'icon' => 'eicon-text-align-left' ],
                'center' => [ 'title' => __( 'Center', 'ocean-charter' ), 'icon' => 'eicon-text-align-center' ],
            ],
            'default'   => 'center',
            'selectors' => [ '{{WRAPPER}} .team-card' => 'text-align: {{VALUE}};' ],
        ] );
        $this->end_controls_section();

        /* ── Style: Tags ────────────────────────────────────── */
        $this->start_controls_section( 'style_team_tags', [
            'label' => __( 'Certification Tags', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'tag_typography',
            'label'    => __( 'Typography', 'ocean-charter' ),
            'selector' => '{{WRAPPER}} .team-card__tag',
        ] );
        $this->add_control( 'tag_color', [
            'label'     => __( 'Text Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .team-card__tag' => 'color: {{VALUE}};' ],
        ] );
        $this->add_control( 'tag_bg', [
            'label'     => __( 'Background', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .team-card__tag' => 'background: {{VALUE}};' ],
        ] );
        $this->end_controls_section();
    }

    protected function render() {
        $s     = $this->get_settings_for_display();
        $count = ! empty( $s['posts_count'] ) ? intval( $s['posts_count'] ) : 8;
        $role  = sanitize_text_field( $s['role_filter'] ?? '' );
        $shape = $s['avatar_shape'] ?? 'circle';

        $args = [
            'post_type'      => 'oc_team_member',
            'posts_per_page' => $count,
            'post_status'    => 'publish',
        ];
        if ( $role ) {
            $args['tax_query'] = [ [
                'taxonomy' => 'oc_team_role',
                'field'    => 'slug',
                'terms'    => $role,
            ] ];
        }
        $query = new WP_Query( $args );
        ?>
        <style>
        .oc-team-grid { width:100%; }
        .oc-team-grid .oc-section-eyebrow { display:block; font-size:0.75rem; font-weight:700; letter-spacing:0.18em; text-transform:uppercase; color:var(--primary,#d9b230); margin-bottom:0.5rem; }
        .oc-team-grid .oc-section-heading { font-family:var(--font-heading,'Playfair Display',serif); font-size:clamp(2rem,3.5vw,3.25rem); color:var(--text,#f0ece3); margin:0 0 2rem; font-weight:400; font-style:italic; }
        .oc-team-grid__grid { display:grid; grid-template-columns:repeat(4,1fr); gap:1.5rem; }
        @media(max-width:900px){.oc-team-grid__grid{grid-template-columns:repeat(2,1fr);}}
        @media(max-width:540px){.oc-team-grid__grid{grid-template-columns:1fr;}}
        .oc-team-grid .team-card { background:var(--surface,#111a28); border:1px solid rgba(255,255,255,0.06); border-radius:var(--radius-lg,1rem); padding:2rem 1.5rem; text-align:center; transition:box-shadow 0.3s,transform 0.3s; }
        .oc-team-grid .team-card:hover { transform:translateY(-4px); box-shadow:0 12px 36px rgba(0,0,0,0.4); border-color:rgba(217,178,48,0.2); }
        .oc-team-grid .team-card__avatar-wrap { width:160px; height:160px; margin:0 auto 1.25rem; overflow:hidden; border:3px solid rgba(217,178,48,0.25); }
        .oc-team-grid .team-card__avatar-wrap.shape-circle { border-radius:50%; }
        .oc-team-grid .team-card__avatar-wrap.shape-square { border-radius:0; }
        .oc-team-grid .team-card__avatar-wrap.shape-rounded { border-radius:1rem; }
        .oc-team-grid .team-card__avatar-wrap img { width:100%; height:100%; object-fit:cover; }
        .oc-team-grid .team-card__avatar-placeholder { width:100%; height:100%; background:linear-gradient(135deg,#1a2d45,#0d1f35); }
        .oc-team-grid .team-card__name { font-family:var(--font-heading,'Playfair Display',serif); font-size:1.1rem; color:var(--text,#f0ece3); margin:0 0 0.25rem; }
        .oc-team-grid .team-card__role { font-size:0.7rem; font-weight:700; letter-spacing:0.14em; text-transform:uppercase; color:var(--primary,#d9b230); margin:0 0 0.5rem; }
        .oc-team-grid .team-card__exp { display:inline-block; font-size:0.75rem; background:rgba(217,178,48,0.1); color:var(--primary,#d9b230); border:1px solid rgba(217,178,48,0.2); border-radius:9999px; padding:0.15rem 0.6rem; margin-bottom:0.75rem; }
        .oc-team-grid .team-card__bio { font-size:0.8125rem; color:var(--text-muted,rgba(148,163,184,1)); line-height:1.6; margin:0 0 0.875rem; }
        .oc-team-grid .team-card__tags { display:flex; flex-wrap:wrap; justify-content:center; gap:0.3rem; margin-bottom:0.6rem; }
        .oc-team-grid .team-card__tag { font-size:0.68rem; padding:0.15rem 0.55rem; border-radius:9999px; background:rgba(255,255,255,0.06); color:var(--text-muted,rgba(148,163,184,1)); border:1px solid rgba(255,255,255,0.08); }
        .oc-team-grid .team-card__languages { font-size:0.75rem; color:var(--text-muted,rgba(148,163,184,1)); }
        </style>

        <div class="oc-team-grid">
            <?php if ( $s['section_eyebrow'] ) : ?>
                <span class="oc-section-eyebrow"><?php echo esc_html( $s['section_eyebrow'] ); ?></span>
            <?php endif; ?>
            <?php if ( $s['section_heading'] ) : ?>
                <h2 class="oc-section-heading"><?php echo esc_html( $s['section_heading'] ); ?></h2>
            <?php endif; ?>

            <div class="oc-team-grid__grid">
                <?php if ( $query->have_posts() ) :
                    while ( $query->have_posts() ) : $query->the_post();
                        $pid    = get_the_ID();
                        $thumb  = get_the_post_thumbnail_url( $pid, 'medium' );
                        $role_t = get_post_meta( $pid, '_oc_role_title', true );
                        $exp    = get_post_meta( $pid, '_oc_years_exp', true );
                        $bio    = get_post_meta( $pid, '_oc_bio', true );
                        $certs  = get_post_meta( $pid, '_oc_certifications', true );
                        $langs  = get_post_meta( $pid, '_oc_languages', true );
                        $cert_arr = $certs ? json_decode( $certs, true ) : [];
                        $lang_arr = $langs ? json_decode( $langs, true ) : [];
                        $bio_excerpt = $bio ? mb_substr( wp_strip_all_tags( $bio ), 0, 100 ) . ( mb_strlen( wp_strip_all_tags( $bio ) ) > 100 ? '…' : '' ) : '';
                    ?>
                    <div class="team-card">
                        <div class="team-card__avatar-wrap shape-<?php echo esc_attr( $shape ); ?>">
                            <?php if ( $thumb ) : ?>
                                <img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy">
                            <?php else : ?>
                                <div class="team-card__avatar-placeholder"></div>
                            <?php endif; ?>
                        </div>
                        <h3 class="team-card__name"><?php the_title(); ?></h3>
                        <?php if ( $role_t ) : ?>
                            <p class="team-card__role"><?php echo esc_html( $role_t ); ?></p>
                        <?php endif; ?>
                        <?php if ( $exp ) : ?>
                            <span class="team-card__exp"><?php echo esc_html( $exp ); ?> <?php esc_html_e( 'yrs exp', 'ocean-charter' ); ?></span>
                        <?php endif; ?>
                        <?php if ( $bio_excerpt ) : ?>
                            <p class="team-card__bio"><?php echo esc_html( $bio_excerpt ); ?></p>
                        <?php endif; ?>
                        <?php if ( ! empty( $cert_arr ) ) : ?>
                            <div class="team-card__tags">
                                <?php foreach ( $cert_arr as $cert ) : ?>
                                    <span class="team-card__tag"><?php echo esc_html( $cert ); ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <?php if ( ! empty( $lang_arr ) ) : ?>
                            <p class="team-card__languages"><?php echo esc_html( implode( ', ', $lang_arr ) ); ?></p>
                        <?php endif; ?>
                    </div>
                    <?php endwhile; wp_reset_postdata(); ?>
                <?php else : ?>
                    <p style="color:var(--text-muted,rgba(148,163,184,1));"><?php esc_html_e( 'No team members found.', 'ocean-charter' ); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}

/* ============================================================
   6. OC_FAQ_Accordion_Widget
   ============================================================ */
class OC_FAQ_Accordion_Widget extends \Elementor\Widget_Base {
    use OC_Widget_Style_Trait;

    public function get_name()        { return 'oc-faq-accordion'; }
    public function get_title()       { return __( 'OC FAQ Accordion', 'ocean-charter' ); }
    public function get_icon()        { return 'eicon-toggle'; }
    public function get_categories()  { return [ 'ocean-charter' ]; }

    protected function register_controls() {
        $this->start_controls_section( 'content', [ 'label' => __( 'Content', 'ocean-charter' ) ] );
        $this->add_control( 'faq_source_notice', [
            'type' => \Elementor\Controls_Manager::RAW_HTML,
            'raw'  => '<div style="background:rgba(217,178,48,.1);border:1px solid rgba(217,178,48,.3);padding:.75rem;border-radius:.5rem;font-size:.8125rem;line-height:1.5;color:#d9b230">📋 <strong>FAQ items</strong> are managed in <strong>Dashboard → FAQs</strong>. Add, edit, or reorder them there.</div>',
            'content_classes' => 'oc-info-notice',
        ] );
        $this->add_control( 'section_heading', [
            'label'   => __( 'Heading', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'Frequently Asked Questions', 'ocean-charter' ),
        ] );
        $this->add_control( 'section_eyebrow', [
            'label'   => __( 'Eyebrow', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'Got Questions?', 'ocean-charter' ),
        ] );
        $this->add_control( 'posts_count', [
            'label'   => __( 'Posts Count (-1 = all)', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::NUMBER,
            'default' => -1,
        ] );
        $this->add_control( 'category', [
            'label'       => __( 'Filter by Category Slug', 'ocean-charter' ),
            'type'        => \Elementor\Controls_Manager::TEXT,
            'placeholder' => __( 'oc_faq_category slug', 'ocean-charter' ),
            'default'     => '',
        ] );
        $this->end_controls_section();

        $this->oc_register_heading_style( '.oc-faq-accordion' );

        // Question style
        $this->start_controls_section( 'style_faq_question', [
            'label' => __( 'Question', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'question_typography',
            'selector' => '{{WRAPPER}} .oc-faq-accordion summary',
        ] );
        $this->add_control( 'question_color', [
            'label'     => __( 'Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#f0ece3',
            'selectors' => [ '{{WRAPPER}} .oc-faq-accordion summary' => 'color: {{VALUE}};' ],
        ] );
        $this->end_controls_section();

        $this->oc_register_excerpt_style( '.oc-faq-accordion .faq-answer' );
        $this->oc_register_accent_style( [ '{{WRAPPER}} .oc-faq-accordion .faq-chevron' => 'color: {{VALUE}};' ], 'Icon Color' );
    }

    protected function render() {
        $s     = $this->get_settings_for_display();
        $count = isset( $s['posts_count'] ) ? intval( $s['posts_count'] ) : -1;
        $cat   = sanitize_text_field( $s['category'] ?? '' );
        $uid   = 'ocfaq-' . $this->get_id();

        $args = [
            'post_type'      => 'oc_faq',
            'posts_per_page' => $count,
            'post_status'    => 'publish',
            'meta_key'       => '_oc_sort_order',
            'orderby'        => 'meta_value_num',
            'order'          => 'ASC',
        ];
        if ( $cat ) {
            $args['tax_query'] = [ [
                'taxonomy' => 'oc_faq_category',
                'field'    => 'slug',
                'terms'    => $cat,
            ] ];
        }
        $query = new WP_Query( $args );
        ?>
        <style>
        .oc-faq-accordion { width:100%; }
        .oc-faq-accordion .oc-section-eyebrow { display:block; font-size:0.75rem; font-weight:700; letter-spacing:0.18em; text-transform:uppercase; color:var(--primary,#d9b230); margin-bottom:0.5rem; }
        .oc-faq-accordion .oc-section-heading { font-family:var(--font-heading,'Playfair Display',serif); font-size:clamp(2rem,3.5vw,3.25rem); color:var(--text,#f0ece3); margin:0 0 2rem; font-weight:400; font-style:italic; }
        .oc-faq-accordion__list { display:flex; flex-direction:column; gap:0.75rem; }
        .oc-faq-accordion details { background:var(--glass-bg,rgba(26,34,51,0.7)); backdrop-filter:blur(12px); -webkit-backdrop-filter:blur(12px); border:1px solid var(--glass-border,rgba(217,178,48,0.1)); border-radius:var(--radius,0.5rem); overflow:hidden; transition:border-color 0.25s; }
        .oc-faq-accordion details[open] { border-color:rgba(217,178,48,0.35); border-left:3px solid var(--primary,#d9b230); }
        .oc-faq-accordion summary { display:flex; justify-content:space-between; align-items:center; padding:1.1rem 1.5rem; cursor:pointer; list-style:none; font-size:0.9375rem; font-weight:600; color:var(--text,#f0ece3); gap:1rem; }
        .oc-faq-accordion summary::-webkit-details-marker { display:none; }
        .oc-faq-accordion summary .faq-chevron { flex-shrink:0; transition:transform 0.25s ease; color:var(--primary,#d9b230); }
        .oc-faq-accordion details[open] summary .faq-chevron { transform:rotate(180deg); }
        .oc-faq-accordion .faq-answer { padding:0 1.5rem 1.25rem; font-size:0.9rem; color:var(--text-muted,rgba(148,163,184,1)); line-height:1.75; }
        .oc-faq-accordion .faq-answer p { margin:0 0 0.75rem; }
        .oc-faq-accordion .faq-answer p:last-child { margin:0; }
        </style>

        <div class="oc-faq-accordion" id="<?php echo esc_attr( $uid ); ?>">
            <?php if ( $s['section_eyebrow'] ) : ?>
                <span class="oc-section-eyebrow"><?php echo esc_html( $s['section_eyebrow'] ); ?></span>
            <?php endif; ?>
            <?php if ( $s['section_heading'] ) : ?>
                <h2 class="oc-section-heading"><?php echo esc_html( $s['section_heading'] ); ?></h2>
            <?php endif; ?>

            <div class="oc-faq-accordion__list">
                <?php if ( $query->have_posts() ) :
                    while ( $query->have_posts() ) : $query->the_post();
                        $pid    = get_the_ID();
                        $answer = get_post_meta( $pid, '_oc_answer', true ) ?: get_the_content();
                    ?>
                    <details>
                        <summary>
                            <?php the_title(); ?>
                            <svg class="faq-chevron" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.5 6.75L9 11.25L13.5 6.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </summary>
                        <div class="faq-answer">
                            <?php echo wp_kses_post( $answer ); ?>
                        </div>
                    </details>
                    <?php endwhile; wp_reset_postdata(); ?>
                <?php else : ?>
                    <p style="color:var(--text-muted,rgba(148,163,184,1));"><?php esc_html_e( 'No FAQs found.', 'ocean-charter' ); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}

/* ============================================================
   7. OC_Offer_Cards_Widget
   ============================================================ */
class OC_Offer_Cards_Widget extends \Elementor\Widget_Base {
    use OC_Widget_Style_Trait;

    public function get_name()        { return 'oc-offer-cards'; }
    public function get_title()       { return __( 'OC Offer Cards', 'ocean-charter' ); }
    public function get_icon()        { return 'eicon-price-list'; }
    public function get_categories()  { return [ 'ocean-charter' ]; }

    protected function register_controls() {
        $this->start_controls_section( 'content', [ 'label' => __( 'Content', 'ocean-charter' ) ] );
        $this->add_control( 'posts_count', [
            'label'   => __( 'Posts Count', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::NUMBER,
            'default' => 3,
            'min'     => 1,
        ] );
        $this->add_control( 'columns', [
            'label'   => __( 'Columns', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => '3',
            'options' => [ '1' => '1', '2' => '2', '3' => '3', '4' => '4' ],
        ] );
        $this->add_control( 'excerpt_length', [
            'label'   => __( 'Excerpt Length (chars)', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::NUMBER,
            'default' => 100,
            'min'     => 0,
            'max'     => 400,
        ] );
        $this->add_control( 'featured_only', [
            'label'        => __( 'Featured Only', 'ocean-charter' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => '',
        ] );
        $this->add_control( 'section_heading', [
            'label'   => __( 'Section Heading', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'Special Offers', 'ocean-charter' ),
        ] );
        $this->add_control( 'section_eyebrow', [
            'label'   => __( 'Section Eyebrow', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'Limited Time', 'ocean-charter' ),
        ] );
        $this->end_controls_section();

        $this->oc_register_heading_style( '.oc-offer-cards' );
        $this->oc_register_card_style( '.oc-offer-cards .offer-card', 'Offer Card' );
        $this->oc_register_title_style( '.oc-offer-cards .offer-card__body h3', 'Card Title' );
        $this->oc_register_excerpt_style( '.oc-offer-cards .offer-card__excerpt' );
        $this->oc_register_button_style( '.oc-offer-cards .offer-card__cta' );
        $this->oc_register_grid_style( '.oc-offer-cards__grid' );
        $this->oc_register_image_style( '.oc-offer-cards .offer-card__img-wrap' );

        // Discount number
        $this->start_controls_section( 'style_discount', [
            'label' => __( 'Discount Number', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'discount_typography',
            'label'    => __( 'Typography', 'ocean-charter' ),
            'selector' => '{{WRAPPER}} .oc-offer-cards .offer-card__discount',
        ] );
        $this->add_control( 'discount_color', [
            'label'     => __( 'Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#d9b230',
            'selectors' => [ '{{WRAPPER}} .oc-offer-cards .offer-card__discount' => 'color: {{VALUE}};' ],
        ] );
        $this->end_controls_section();
    }

    protected function render() {
        $s     = $this->get_settings_for_display();
        $count = ! empty( $s['posts_count'] ) ? intval( $s['posts_count'] ) : 3;
        $cols  = intval( $s['columns'] ?? 3 );
        $len   = intval( $s['excerpt_length'] ?? 100 );

        $args = [
            'post_type'      => 'oc_offer',
            'posts_per_page' => $count,
            'post_status'    => 'publish',
        ];
        if ( $s['featured_only'] === 'yes' ) {
            $args['meta_query'] = [ [ 'key' => '_oc_is_featured', 'value' => '1', 'compare' => '=' ] ];
        }
        $query = new WP_Query( $args );
        ?>
        <style>
        .oc-offer-cards { width:100%; }
        .oc-offer-cards .oc-section-eyebrow { display:block; font-size:0.75rem; font-weight:700; letter-spacing:0.18em; text-transform:uppercase; color:var(--primary,#d9b230); margin-bottom:0.5rem; }
        .oc-offer-cards .oc-section-heading { font-family:var(--font-heading,'Playfair Display',serif); font-size:clamp(2rem,3.5vw,3.25rem); color:var(--text,#f0ece3); margin:0 0 2rem; font-weight:400; font-style:italic; }
        .oc-offer-cards__grid { display:grid; grid-template-columns:repeat(var(--oc-oc-cols,3),1fr); gap:1.5rem; }
        @media(max-width:900px){.oc-offer-cards__grid{grid-template-columns:repeat(2,1fr);}}
        @media(max-width:540px){.oc-offer-cards__grid{grid-template-columns:1fr;}}
        .oc-offer-cards .offer-card { background:var(--surface,#111a28); border:1px solid rgba(255,255,255,0.06); border-radius:var(--radius-lg,1rem); overflow:hidden; transition:box-shadow 0.3s,transform 0.3s; }
        .oc-offer-cards .offer-card:hover { transform:translateY(-4px); box-shadow:0 12px 40px rgba(0,0,0,0.45); }
        .oc-offer-cards .offer-card--featured { border-color:rgba(217,178,48,0.4); box-shadow:0 0 0 1px rgba(217,178,48,0.15),0 8px 32px rgba(217,178,48,0.08); }
        .oc-offer-cards .offer-card__img-wrap { position:relative; aspect-ratio:3/2; overflow:hidden; }
        .oc-offer-cards .offer-card__img-wrap img { width:100%; height:100%; object-fit:cover; transition:transform 0.5s ease; }
        .oc-offer-cards .offer-card:hover .offer-card__img-wrap img { transform:scale(1.05); }
        .oc-offer-cards .offer-card__img-placeholder { width:100%; height:100%; background:linear-gradient(135deg,#0d1f35,#1a2d45); }
        .oc-offer-cards .offer-card__badge { position:absolute; top:1rem; left:1rem; background:var(--primary,#d9b230); color:#0a0f1a; font-size:0.7rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; padding:0.25rem 0.7rem; border-radius:9999px; }
        .oc-offer-cards .offer-card__body { padding:1.5rem; }
        .oc-offer-cards .offer-card__discount { font-family:var(--font-heading,"Playfair Display",serif); font-size:2.5rem; color:var(--primary,#d9b230); line-height:1; margin-bottom:0.5rem; display:block; }
        .oc-offer-cards .offer-card__body h3 { font-family:var(--font-heading,'Playfair Display',serif); font-size:1.15rem; color:var(--text,#f0ece3); margin:0 0 0.5rem; }
        .oc-offer-cards .offer-card__excerpt { font-size:0.875rem; color:var(--text-muted,rgba(148,163,184,1)); line-height:1.6; margin:0 0 0.875rem; display:-webkit-box; -webkit-box-orient:vertical; -webkit-line-clamp:2; overflow:hidden; max-height:calc(1.6em * 2); }
        .oc-offer-cards .offer-card__validity { font-size:0.78rem; color:var(--text-muted,rgba(148,163,184,1)); margin-bottom:1.25rem; }
        .oc-offer-cards .offer-card__validity strong { color:var(--primary,#d9b230); }
        .oc-offer-cards .offer-card__cta { display:block; text-align:center; background:var(--primary,#d9b230); color:#0a0f1a; font-size:0.875rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; padding:0.7rem 1.5rem; border-radius:9999px; text-decoration:none; transition:opacity 0.2s; }
        .oc-offer-cards .offer-card__cta:hover { opacity:0.88; }
        </style>

        <?php
        $this->add_render_attribute( 'oc_wrapper', 'class', 'oc-offer-cards' );
        $this->add_render_attribute( 'oc_wrapper', 'style', '--oc-oc-cols:' . $cols . ';' );
        ?>
        <div <?php $this->print_render_attribute_string( 'oc_wrapper' ); ?>>
            <?php if ( $s['section_eyebrow'] ) : ?>
                <span class="oc-section-eyebrow"><?php echo esc_html( $s['section_eyebrow'] ); ?></span>
            <?php endif; ?>
            <?php if ( $s['section_heading'] ) : ?>
                <h2 class="oc-section-heading"><?php echo esc_html( $s['section_heading'] ); ?></h2>
            <?php endif; ?>

            <div class="oc-offer-cards__grid">
                <?php if ( $query->have_posts() ) :
                    while ( $query->have_posts() ) : $query->the_post();
                        $pid         = get_the_ID();
                        $thumb_url   = get_the_post_thumbnail_url( $pid, 'large' );
                        $badge       = get_post_meta( $pid, '_oc_badge_text', true );
                        $discount    = get_post_meta( $pid, '_oc_discount', true );
                        $valid_to    = get_post_meta( $pid, '_oc_valid_to', true );
                        $cta_url     = get_permalink();
                        $is_featured = get_post_meta( $pid, '_oc_is_featured', true );
                        $excerpt     = get_the_excerpt();
                        if ( $len > 0 && strlen( $excerpt ) > $len ) { $excerpt = mb_strimwidth( $excerpt, 0, $len, '…' ); }
                        $card_class  = 'offer-card' . ( $is_featured ? ' offer-card--featured' : '' );
                    ?>
                    <div class="<?php echo esc_attr( $card_class ); ?>">
                        <div class="offer-card__img-wrap">
                            <?php if ( $thumb_url ) : ?>
                                <img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy">
                            <?php else : ?>
                                <div class="offer-card__img-placeholder"></div>
                            <?php endif; ?>
                            <?php if ( $badge ) : ?>
                                <span class="offer-card__badge"><?php echo esc_html( $badge ); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="offer-card__body">
                            <?php if ( $discount ) : ?>
                                <span class="offer-card__discount"><?php echo esc_html( $discount ); ?></span>
                            <?php endif; ?>
                            <h3><?php the_title(); ?></h3>
                            <?php if ( $excerpt ) : ?>
                                <p class="offer-card__excerpt"><?php echo esc_html( $excerpt ); ?></p>
                            <?php endif; ?>
                            <?php if ( $valid_to ) : ?>
                                <p class="offer-card__validity"><?php esc_html_e( 'Valid until', 'ocean-charter' ); ?> <strong><?php echo esc_html( $valid_to ); ?></strong></p>
                            <?php endif; ?>
                            <a href="<?php echo esc_url( $cta_url ); ?>" class="offer-card__cta"><?php esc_html_e( 'Claim Offer', 'ocean-charter' ); ?></a>
                        </div>
                    </div>
                    <?php endwhile; wp_reset_postdata(); ?>
                <?php else : ?>
                    <p style="color:var(--text-muted,rgba(148,163,184,1));"><?php esc_html_e( 'No offers found.', 'ocean-charter' ); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}

/* ============================================================
   8. OC_Press_Strip_Widget
   ============================================================ */
class OC_Press_Strip_Widget extends \Elementor\Widget_Base {
    use OC_Widget_Style_Trait;

    public function get_name()        { return 'oc-press-strip'; }
    public function get_title()       { return __( 'OC Press Strip', 'ocean-charter' ); }
    public function get_icon()        { return 'eicon-review'; }
    public function get_categories()  { return [ 'ocean-charter' ]; }

    protected function register_controls() {
        $this->start_controls_section( 'content', [ 'label' => __( 'Content', 'ocean-charter' ) ] );
        $this->add_control( 'posts_count', [
            'label'   => __( 'Posts Count', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::NUMBER,
            'default' => 6,
            'min'     => 1,
        ] );
        $this->add_control( 'show_quotes', [
            'label'        => __( 'Show Quotes', 'ocean-charter' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ] );
        $this->add_control( 'section_heading', [
            'label'   => __( 'Heading', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'As Featured In', 'ocean-charter' ),
        ] );
        $this->add_control( 'section_eyebrow', [
            'label'   => __( 'Eyebrow', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'Recognition', 'ocean-charter' ),
        ] );
        $this->add_control( 'grayscale', [
            'label'        => __( 'Logos Grayscale', 'ocean-charter' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
            'description'  => __( 'Show logos in grayscale, color on hover', 'ocean-charter' ),
        ] );
        $this->end_controls_section();

        $this->oc_register_heading_style( '.oc-press-strip' );

        $this->start_controls_section( 'style_press_bg', [
            'label' => __( 'Background', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_control( 'press_bg_color', [
            'label'     => __( 'Background Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .oc-press-strip' => 'background: {{VALUE}};' ],
        ] );
        $this->end_controls_section();
    }

    protected function render() {
        $s     = $this->get_settings_for_display();
        $count = ! empty( $s['posts_count'] ) ? intval( $s['posts_count'] ) : 6;

        $query = new WP_Query( [
            'post_type'      => 'oc_press',
            'posts_per_page' => $count,
            'post_status'    => 'publish',
        ] );
        ?>
        <style>
        .oc-press-strip { width:100%; text-align:center; }
        .oc-press-strip .oc-section-heading { font-family:var(--font-heading,'Playfair Display',serif); font-size:clamp(1.25rem,2.5vw,2rem); color:var(--text,#f0ece3); margin:0 0 2rem; font-weight:400; font-style:italic; }
        .oc-press-strip__logo-row { display:flex; flex-wrap:wrap; justify-content:center; align-items:center; gap:2rem 3rem; }
        .oc-press-strip__logo-item { display:flex; align-items:center; }
        .oc-press-strip__logo-item img { max-height:40px; max-width:140px; object-fit:contain; filter:grayscale(1) brightness(0.7); opacity:0.7; transition:filter 0.3s,opacity 0.3s; }
        .oc-press-strip__logo-item img:hover { filter:grayscale(0) brightness(1); opacity:1; }
        .oc-press-strip__logo-text { font-size:1rem; font-weight:700; color:var(--text-muted,rgba(148,163,184,1)); letter-spacing:0.08em; text-transform:uppercase; transition:color 0.2s; }
        .oc-press-strip__logo-text:hover { color:var(--text,#f0ece3); }
        .oc-press-strip__cards-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(260px,1fr)); gap:1.5rem; text-align:left; }
        .oc-press-strip .press-card { background:var(--glass-bg,rgba(26,34,51,0.7)); backdrop-filter:blur(12px); -webkit-backdrop-filter:blur(12px); border:1px solid var(--glass-border,rgba(217,178,48,0.1)); border-radius:var(--radius-lg,1rem); padding:1.5rem; }
        .oc-press-strip .press-card__logo-wrap { margin-bottom:1rem; }
        .oc-press-strip .press-card__logo-wrap img { max-height:32px; object-fit:contain; filter:grayscale(1) brightness(0.8); }
        .oc-press-strip .press-card__pub { font-size:0.75rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--primary,#d9b230); margin-bottom:0.75rem; }
        .oc-press-strip .press-card__quote { font-size:0.9rem; line-height:1.7; color:var(--text,#f0ece3); font-style:italic; margin:0 0 1rem; }
        .oc-press-strip .press-card__link { font-size:0.8125rem; font-weight:600; color:var(--primary,#d9b230); text-decoration:none; }
        .oc-press-strip .press-card__link:hover { text-decoration:underline; }
        </style>

        <?php if ( ( $s['grayscale'] ?? '' ) === 'yes' ) : ?>
        <style>
        .oc-press-strip--grayscale .oc-press-strip__logo-item img { filter:grayscale(1); opacity:.6; transition:filter .3s,opacity .3s; }
        .oc-press-strip--grayscale .oc-press-strip__logo-item:hover img { filter:grayscale(0); opacity:1; }
        </style>
        <?php endif; ?>
        <div class="oc-press-strip<?php echo ( ( $s['grayscale'] ?? '' ) === 'yes' ) ? ' oc-press-strip--grayscale' : ''; ?>">
            <?php if ( ! empty( $s['section_eyebrow'] ) ) : ?>
                <span class="oc-section-eyebrow"><?php echo esc_html( $s['section_eyebrow'] ); ?></span>
            <?php endif; ?>
            <?php if ( $s['section_heading'] ) : ?>
                <h2 class="oc-section-heading"><?php echo esc_html( $s['section_heading'] ); ?></h2>
            <?php endif; ?>

            <?php if ( $s['show_quotes'] !== 'yes' ) : ?>
                <div class="oc-press-strip__logo-row">
                    <?php if ( $query->have_posts() ) :
                        while ( $query->have_posts() ) : $query->the_post();
                            $pid      = get_the_ID();
                            $logo_url = get_post_meta( $pid, '_oc_logo_url', true );
                        ?>
                        <div class="oc-press-strip__logo-item">
                            <?php if ( $logo_url ) : ?>
                                <img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy">
                            <?php else : ?>
                                <span class="oc-press-strip__logo-text"><?php the_title(); ?></span>
                            <?php endif; ?>
                        </div>
                        <?php endwhile; wp_reset_postdata(); ?>
                    <?php endif; ?>
                </div>
            <?php else : ?>
                <div class="oc-press-strip__cards-grid">
                    <?php if ( $query->have_posts() ) :
                        while ( $query->have_posts() ) : $query->the_post();
                            $pid         = get_the_ID();
                            $logo_url    = get_post_meta( $pid, '_oc_logo_url', true );
                            $quote       = get_post_meta( $pid, '_oc_quote', true );
                            $article_url = get_post_meta( $pid, '_oc_article_url', true ) ?: '#';
                        ?>
                        <div class="press-card">
                            <?php if ( $logo_url ) : ?>
                                <div class="press-card__logo-wrap">
                                    <img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy">
                                </div>
                            <?php endif; ?>
                            <p class="press-card__pub"><?php the_title(); ?></p>
                            <?php if ( $quote ) : ?>
                                <p class="press-card__quote">"<?php echo esc_html( $quote ); ?>"</p>
                            <?php endif; ?>
                            <a href="<?php echo esc_url( $article_url ); ?>" class="press-card__link" target="_blank" rel="noopener"><?php esc_html_e( 'Read Article →', 'ocean-charter' ); ?></a>
                        </div>
                        <?php endwhile; wp_reset_postdata(); ?>
                    <?php else : ?>
                        <p style="color:var(--text-muted,rgba(148,163,184,1));"><?php esc_html_e( 'No press items found.', 'ocean-charter' ); ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}

/* ============================================================
   9. OC_Vessel_Grid_Widget
   ============================================================ */
class OC_Vessel_Grid_Widget extends \Elementor\Widget_Base {
    use OC_Widget_Style_Trait;

    public function get_name()        { return 'oc-vessel-grid'; }
    public function get_title()       { return __( 'OC Vessel Grid', 'ocean-charter' ); }
    public function get_icon()        { return 'eicon-featured-image'; }
    public function get_categories()  { return [ 'ocean-charter' ]; }

    protected function register_controls() {
        $this->start_controls_section( 'content', [ 'label' => __( 'Content', 'ocean-charter' ) ] );
        $this->add_control( 'posts_count', [
            'label'   => __( 'Posts Count', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::NUMBER,
            'default' => 6,
            'min'     => 1,
        ] );
        $this->add_control( 'columns', [
            'label'   => __( 'Columns', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => '3',
            'options' => [ '1' => '1', '2' => '2', '3' => '3', '4' => '4' ],
        ] );
        $this->add_control( 'excerpt_length', [
            'label'   => __( 'Excerpt Length (chars)', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::NUMBER,
            'default' => 120,
            'min'     => 0,
            'max'     => 400,
        ] );
        $this->add_control( 'vessel_type', [
            'label'       => __( 'Filter by Vessel Type Slug', 'ocean-charter' ),
            'type'        => \Elementor\Controls_Manager::TEXT,
            'placeholder' => __( 'oc_vessel_type slug', 'ocean-charter' ),
            'default'     => '',
        ] );
        $this->add_control( 'section_heading', [
            'label'   => __( 'Section Heading', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'Our Fleet', 'ocean-charter' ),
        ] );
        $this->add_control( 'section_eyebrow', [
            'label'   => __( 'Section Eyebrow', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'Choose Your Vessel', 'ocean-charter' ),
        ] );
        $this->add_control( 'show_filter', [
            'label'        => __( 'Show Type Filter', 'ocean-charter' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ] );
        $this->add_control( 'show_price_filter', [
            'label'        => __( 'Show Price Filter', 'ocean-charter' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => '',
        ] );
        $this->add_control( 'show_date_filter', [
            'label'        => __( 'Show Date Filter', 'ocean-charter' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => '',
        ] );
        $this->add_control( 'show_guests_filter', [
            'label'        => __( 'Show Guests Filter', 'ocean-charter' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => '',
        ] );
        $this->end_controls_section();

        $this->oc_register_heading_style( '.oc-vessel-grid' );
        $this->oc_register_card_style( '.oc-vessel-grid .vessel-card', 'Vessel Card' );
        $this->oc_register_title_style( '.oc-vessel-grid .vessel-card__body h3' );
        $this->oc_register_button_style( '.oc-vessel-grid .vessel-card__btn-primary' );
        $this->oc_register_grid_style( '.oc-vessel-grid__grid' );
        $this->oc_register_image_style( '.oc-vessel-grid .vessel-card__img-wrap' );
    }

    protected function render() {
        $s          = $this->get_settings_for_display();
        $count      = ! empty( $s['posts_count'] ) ? intval( $s['posts_count'] ) : 6;
        $cols       = intval( $s['columns'] ?? 3 );
        $len        = intval( $s['excerpt_length'] ?? 120 );
        $type_slug  = sanitize_text_field( $s['vessel_type'] ?? '' );
        $uid        = 'ocvg-' . $this->get_id();

        /* ── Query BBC boat CPT ── */
        $args = [
            'post_type'      => 'boat',
            'posts_per_page' => $count,
            'post_status'    => 'publish',
        ];
        if ( $type_slug ) {
            $args['meta_query'] = [ [
                'key'   => '_bbc_boat_type',
                'value' => $type_slug,
            ] ];
        }
        $query = new WP_Query( $args );

        global $wpdb;
        $reviews_table = $wpdb->prefix . 'bbc_reviews';
        $boat_types    = $wpdb->get_col( "SELECT DISTINCT meta_value FROM {$wpdb->postmeta} WHERE meta_key = '_bbc_boat_type' AND meta_value != '' ORDER BY meta_value ASC" );
        $bbc_locations = $wpdb->get_col( "SELECT DISTINCT meta_value FROM {$wpdb->postmeta} WHERE meta_key = '_bbc_location' AND meta_value != '' ORDER BY meta_value ASC" );
        $type_labels   = [
            'yacht' => 'Yacht', 'sailboat' => 'Sailboat', 'catamaran' => 'Catamaran',
            'motorboat' => 'Motorboat', 'fishing' => 'Fishing Boat', 'rib' => 'RIB',
            'jet_ski' => 'Jet Ski', 'houseboat' => 'Houseboat', 'gulet' => 'Gulet',
            'mega_yacht' => 'Mega Yacht',
        ];
        ?>
        <style>
        .oc-vessel-grid{width:100%}
        .oc-vessel-grid .oc-section-eyebrow{display:block;font-size:.75rem;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:var(--primary,#d9b230);margin-bottom:.5rem}
        .oc-vessel-grid .oc-section-heading{font-family:var(--font-heading,'Playfair Display',serif);font-size:clamp(2rem,3.5vw,3.25rem);color:var(--text,#f0ece3);margin:0 0 2rem;font-weight:400;font-style:italic}
        /* ── Stitch-style filter bar ── */
        .oc-vg-filterbar{display:grid;grid-template-columns:1fr 1fr 1fr 1fr auto;align-items:stretch;background:var(--surface,#111a28);border:1px solid rgba(255,255,255,.08);border-radius:1rem;overflow:hidden;margin-bottom:2.5rem;padding:.5rem}
        .oc-vg-filterbar__group{padding:.6rem 1.25rem;display:flex;flex-direction:column;gap:.3rem;min-width:0}
        .oc-vg-filterbar__label{font-size:.55rem;font-weight:800;letter-spacing:.16em;text-transform:uppercase;color:var(--primary,#d9b230);white-space:nowrap}
        .oc-vg-filterbar__select{background:none;border:none;outline:none;font-size:.875rem;color:var(--text,#f0ece3);font-family:inherit;cursor:pointer;appearance:none;-webkit-appearance:none;padding:0;width:100%}
        .oc-vg-filterbar__select option{background:#0a101a;color:#f0ece3}
        .oc-vg-filterbar__input-wrap{display:flex;align-items:center;gap:.5rem}
        .oc-vg-filterbar__icon{flex-shrink:0;color:rgba(240,236,227,.35);display:flex}
        .oc-vg-filterbar__divider{width:1px;background:rgba(255,255,255,.08);align-self:center;height:36px;flex-shrink:0}
        .oc-vg-filterbar__more{display:flex;align-items:center;gap:.5rem;background:var(--primary,#d9b230);color:#0a0f1a;border:none;padding:.75rem 1.5rem;font-size:.8125rem;font-weight:800;letter-spacing:.04em;cursor:pointer;white-space:nowrap;font-family:inherit;border-radius:12px;transition:opacity .2s}
        .oc-vg-filterbar__more:hover{opacity:.88}
        @media(max-width:900px){.oc-vg-filterbar{grid-template-columns:1fr 1fr;gap:.25rem}.oc-vg-filterbar__divider{display:none}.oc-vg-filterbar__more{grid-column:1/-1}}
        @media(max-width:540px){.oc-vg-filterbar{grid-template-columns:1fr}}
        /* ── Grid ── */
        .oc-vessel-grid__grid{display:grid;grid-template-columns:repeat(var(--oc-vg-cols,3),1fr);gap:1.5rem}
        @media(max-width:900px){.oc-vessel-grid__grid{grid-template-columns:repeat(2,1fr)}}
        @media(max-width:540px){.oc-vessel-grid__grid{grid-template-columns:1fr}}
        /* ── Stitch-style vessel card ── */
        .oc-vessel-grid .vessel-card{background:var(--surface,#111a28);border:1px solid rgba(255,255,255,.06);border-radius:var(--radius-lg,1rem);overflow:hidden;transition:box-shadow .3s,transform .3s;display:flex;flex-direction:column}
        .oc-vessel-grid .vessel-card:hover{transform:translateY(-4px);box-shadow:0 12px 40px rgba(0,0,0,.45);border-color:rgba(217,178,48,.25)}
        .oc-vessel-grid .vessel-card.is-hidden{display:none}
        .oc-vessel-grid .vessel-card__img-wrap{position:relative;aspect-ratio:4/3;overflow:hidden}
        .oc-vessel-grid .vessel-card__img-wrap img{width:100%;height:100%;object-fit:cover;transition:transform .5s ease}
        .oc-vessel-grid .vessel-card:hover .vessel-card__img-wrap img{transform:scale(1.05)}
        .oc-vessel-grid .vessel-card__img-placeholder{width:100%;height:100%;background:linear-gradient(135deg,#0d1f35,#1a2d45)}
        .oc-vessel-grid .vessel-card__tag{position:absolute;top:.875rem;right:.875rem;background:var(--primary,#d9b230);color:#0a0f1a;font-size:.65rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;padding:.25rem .7rem;border-radius:9999px;z-index:1}
        .oc-vessel-grid .vessel-card__rating{position:absolute;bottom:.75rem;left:.875rem;display:flex;align-items:center;gap:.35rem;font-size:.8rem;color:var(--text,#f0ece3);text-shadow:0 1px 4px rgba(0,0,0,.6);z-index:1}
        .oc-vessel-grid .vessel-card__rating-star{color:var(--primary,#d9b230)}
        .oc-vessel-grid .vessel-card__rating-count{font-size:.7rem;color:rgba(240,236,227,.65)}
        .oc-vessel-grid .vessel-card__body{padding:1.25rem 1.5rem 1.5rem;display:flex;flex-direction:column;flex:1}
        /* Name + price on same line */
        .oc-vessel-grid .vessel-card__title-row{display:flex;align-items:baseline;justify-content:space-between;gap:.75rem;margin-bottom:.6rem;flex-wrap:wrap}
        .oc-vessel-grid .vessel-card__title-row h3{font-family:var(--font-heading,'Playfair Display',serif);font-size:1.1rem;color:var(--text,#f0ece3);margin:0;font-weight:600;flex-shrink:1;min-width:0}
        .oc-vessel-grid .vessel-card__price{font-size:1.15rem;color:var(--primary,#d9b230);font-weight:800;white-space:nowrap;margin:0;flex-shrink:0}
        .oc-vessel-grid .vessel-card__price-unit{font-size:.75rem;font-weight:400;color:var(--text-muted,rgba(148,163,184,1))}
        /* Guests + location row */
        .oc-vessel-grid .vessel-card__meta-row{display:flex;align-items:center;gap:1.25rem;font-size:.8125rem;color:var(--text-muted,rgba(148,163,184,1));margin-bottom:1.25rem}
        .oc-vessel-grid .vessel-card__meta-item{display:flex;align-items:center;gap:.3rem}
        .oc-vessel-grid .vessel-card__meta-item svg{flex-shrink:0;opacity:.6}
        /* Actions: Details + Quick Book */
        .oc-vessel-grid .vessel-card__actions{display:flex;gap:.75rem;margin-top:auto}
        .oc-vessel-grid .vessel-card__btn-secondary{flex:1;text-align:center;padding:.65rem 1rem;border-radius:.625rem;font-size:.8125rem;font-weight:600;border:1px solid rgba(255,255,255,.15);color:var(--text,#f0ece3);background:transparent;text-decoration:none;transition:background .2s,border-color .2s}
        .oc-vessel-grid .vessel-card__btn-secondary:hover{background:rgba(255,255,255,.06);border-color:rgba(255,255,255,.25)}
        .oc-vessel-grid .vessel-card__btn-primary{flex:1;text-align:center;padding:.65rem 1rem;border-radius:.625rem;font-size:.8125rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;background:var(--primary,#d9b230);color:#0a0f1a;text-decoration:none;transition:opacity .2s}
        .oc-vessel-grid .vessel-card__btn-primary:hover{opacity:.88}
        </style>

        <?php
        $this->add_render_attribute( 'vg_wrapper', 'class', 'oc-vessel-grid' );
        $this->add_render_attribute( 'vg_wrapper', 'id', esc_attr( $uid ) );
        $this->add_render_attribute( 'vg_wrapper', 'style', '--oc-vg-cols:' . $cols . ';' );
        /* bbc_locations already fetched above */
        ?>
        <div <?php $this->print_render_attribute_string( 'vg_wrapper' ); ?>>
            <?php if ( $s['section_eyebrow'] ) : ?>
                <span class="oc-section-eyebrow"><?php echo esc_html( $s['section_eyebrow'] ); ?></span>
            <?php endif; ?>
            <?php if ( $s['section_heading'] ) : ?>
                <h2 class="oc-section-heading"><?php echo esc_html( $s['section_heading'] ); ?></h2>
            <?php endif; ?>

            <!-- Stitch-style filter bar -->
            <div class="oc-vg-filterbar">
                <!-- TYPE -->
                <div class="oc-vg-filterbar__group">
                    <span class="oc-vg-filterbar__label"><?php esc_html_e( 'Type', 'ocean-charter' ); ?></span>
                    <div class="oc-vg-filterbar__input-wrap">
                        <select class="oc-vg-filterbar__select" data-vf="type">
                            <option value="all"><?php esc_html_e( 'All Yacht Types', 'ocean-charter' ); ?></option>
                            <?php foreach ( $boat_types as $bt ) : ?>
                                <option value="<?php echo esc_attr( $bt ); ?>"><?php echo esc_html( $type_labels[ $bt ] ?? ucfirst( str_replace( '_', ' ', $bt ) ) ); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="oc-vg-filterbar__icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg></span>
                    </div>
                </div>
                <div class="oc-vg-filterbar__divider"></div>
                <!-- CAPACITY -->
                <div class="oc-vg-filterbar__group">
                    <span class="oc-vg-filterbar__label"><?php esc_html_e( 'Capacity', 'ocean-charter' ); ?></span>
                    <div class="oc-vg-filterbar__input-wrap">
                        <select class="oc-vg-filterbar__select" data-vf="guests">
                            <option value="all"><?php esc_html_e( 'Any Guests', 'ocean-charter' ); ?></option>
                            <option value="1-6"><?php esc_html_e( '1–6 Guests', 'ocean-charter' ); ?></option>
                            <option value="7-12"><?php esc_html_e( '7–12 Guests', 'ocean-charter' ); ?></option>
                            <option value="13+"><?php esc_html_e( '13+ Guests', 'ocean-charter' ); ?></option>
                        </select>
                        <span class="oc-vg-filterbar__icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></span>
                    </div>
                </div>
                <div class="oc-vg-filterbar__divider"></div>
                <!-- PRICING -->
                <div class="oc-vg-filterbar__group">
                    <span class="oc-vg-filterbar__label"><?php esc_html_e( 'Pricing', 'ocean-charter' ); ?></span>
                    <div class="oc-vg-filterbar__input-wrap">
                        <select class="oc-vg-filterbar__select" data-vf="price">
                            <option value="all"><?php esc_html_e( 'Price Range', 'ocean-charter' ); ?></option>
                            <option value="0-5000"><?php esc_html_e( 'Under $5,000/hr', 'ocean-charter' ); ?></option>
                            <option value="5000-15000"><?php esc_html_e( '$5,000 – $15,000/hr', 'ocean-charter' ); ?></option>
                            <option value="15000+"><?php esc_html_e( '$15,000+/hr', 'ocean-charter' ); ?></option>
                        </select>
                        <span class="oc-vg-filterbar__icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></span>
                    </div>
                </div>
                <div class="oc-vg-filterbar__divider"></div>
                <!-- LOCATION -->
                <div class="oc-vg-filterbar__group">
                    <span class="oc-vg-filterbar__label"><?php esc_html_e( 'Location', 'ocean-charter' ); ?></span>
                    <div class="oc-vg-filterbar__input-wrap">
                        <select class="oc-vg-filterbar__select" data-vf="location">
                            <option value="all"><?php esc_html_e( 'Any Location', 'ocean-charter' ); ?></option>
                            <?php foreach ( $bbc_locations as $loc ) : ?>
                                <option value="<?php echo esc_attr( strtolower( $loc ) ); ?>"><?php echo esc_html( $loc ); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="oc-vg-filterbar__icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></span>
                    </div>
                </div>
                <!-- More Filters button -->
                <button class="oc-vg-filterbar__more" type="button">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="4" y1="6" x2="20" y2="6"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="18" x2="20" y2="18"/><circle cx="8" cy="6" r="1.5" fill="currentColor"/><circle cx="16" cy="12" r="1.5" fill="currentColor"/><circle cx="10" cy="18" r="1.5" fill="currentColor"/></svg>
                    <?php esc_html_e( 'More Filters', 'ocean-charter' ); ?>
                </button>
            </div>

            <div class="oc-vessel-grid__grid">
                <?php if ( $query->have_posts() ) :
                    while ( $query->have_posts() ) : $query->the_post();
                        $pid        = get_the_ID();
                        $thumb_url  = get_the_post_thumbnail_url( $pid, 'large' );
                        if ( ! $thumb_url ) {
                            $gallery = get_post_meta( $pid, '_bbc_gallery', true );
                            if ( ! empty( $gallery ) && is_array( $gallery ) ) {
                                $thumb_url = wp_get_attachment_image_url( $gallery[0], 'large' );
                            }
                        }
                        /* ── BBC meta fields ── */
                        $guests      = get_post_meta( $pid, '_bbc_max_guests', true );
                        $boat_type   = get_post_meta( $pid, '_bbc_boat_type', true );
                        $location    = get_post_meta( $pid, '_bbc_location', true );
                        $price_hour  = floatval( get_post_meta( $pid, '_bbc_price_hour', true ) );
                        $price_day_v = floatval( get_post_meta( $pid, '_bbc_price_day', true ) );
                        $price_wk_v  = floatval( get_post_meta( $pid, '_bbc_price_week', true ) );
                        $condition   = get_post_meta( $pid, '_bbc_condition', true );

                        /* Rating from BBC reviews table */
                        $rating_row   = $wpdb->get_row( $wpdb->prepare(
                            "SELECT ROUND(AVG(rating),1) as avg_rating, COUNT(*) as review_count FROM {$reviews_table} WHERE boat_id = %d AND status = 'approved'", $pid
                        ) );
                        $rating       = ( $rating_row && $rating_row->review_count > 0 ) ? $rating_row->avg_rating : '';
                        $review_count = ( $rating_row && $rating_row->review_count > 0 ) ? $rating_row->review_count : '';

                        /* Price display — prioritise hourly, then daily, then weekly */
                        $price_num = 0; $price_display = ''; $price_unit = '';
                        if ( $price_hour > 0 ) {
                            $price_num = $price_hour; $price_display = '$' . number_format( $price_hour, 0 ); $price_unit = '/hr';
                        } elseif ( $price_day_v > 0 ) {
                            $price_num = $price_day_v; $price_display = '$' . number_format( $price_day_v, 0 ); $price_unit = '/day';
                        } elseif ( $price_wk_v > 0 ) {
                            $price_num = $price_wk_v; $price_display = '$' . number_format( $price_wk_v, 0 ); $price_unit = '/week';
                        }

                        /* Tag badge from condition */
                        $tag = '';
                        if ( $condition === 'new' ) $tag = 'New Listing';
                        elseif ( $condition === 'like_new' ) $tag = 'Like New';
                        elseif ( $condition === 'excellent' ) $tag = 'Excellent';

                        $vtype_slugs = esc_attr( $boat_type );
                        $loc_slugs   = esc_attr( strtolower( $location ?: '' ) );
                        $guests_num  = intval( $guests );
                    ?>
                    <div class="vessel-card" data-vtype="<?php echo esc_attr( $vtype_slugs ); ?>" data-price="<?php echo esc_attr( $price_num ); ?>" data-guests="<?php echo esc_attr( $guests_num ); ?>" data-location="<?php echo esc_attr( $loc_slugs ); ?>">
                        <div class="vessel-card__img-wrap">
                            <?php if ( $thumb_url ) : ?>
                                <img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy">
                            <?php else : ?>
                                <div class="vessel-card__img-placeholder"></div>
                            <?php endif; ?>
                            <?php if ( $tag ) : ?>
                                <span class="vessel-card__tag"><?php echo esc_html( $tag ); ?></span>
                            <?php endif; ?>
                            <?php if ( $rating ) : ?>
                                <div class="vessel-card__rating">
                                    <span class="vessel-card__rating-star">★</span> <?php echo esc_html( $rating ); ?>
                                    <?php if ( $review_count ) : ?>
                                        <span class="vessel-card__rating-count">(<?php echo esc_html( $review_count ); ?> reviews)</span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="vessel-card__body">
                            <div class="vessel-card__title-row">
                                <h3><?php the_title(); ?></h3>
                                <?php if ( $price_display ) : ?>
                                    <span class="vessel-card__price"><?php echo esc_html( $price_display ); ?> <span class="vessel-card__price-unit"><?php echo esc_html( $price_unit ); ?></span></span>
                                <?php endif; ?>
                            </div>
                            <div class="vessel-card__meta-row">
                                <?php if ( $guests ) : ?>
                                    <span class="vessel-card__meta-item">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                                        <?php echo esc_html( $guests ); ?> <?php esc_html_e( 'Guests', 'ocean-charter' ); ?>
                                    </span>
                                <?php endif; ?>
                                <?php if ( $location ) : ?>
                                    <span class="vessel-card__meta-item">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                        <?php echo esc_html( $location ); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="vessel-card__actions">
                                <a href="<?php echo esc_url( get_permalink() ); ?>" class="vessel-card__btn-secondary"><?php esc_html_e( 'Details', 'ocean-charter' ); ?></a>
                                <a href="<?php echo esc_url( get_permalink() . '#booking' ); ?>" class="vessel-card__btn-primary"><?php esc_html_e( 'Quick Book', 'ocean-charter' ); ?></a>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; wp_reset_postdata(); ?>
                <?php else : ?>
                    <p style="color:var(--text-muted,rgba(148,163,184,1));"><?php esc_html_e( 'No vessels found.', 'ocean-charter' ); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <script>
        (function(){
            var wrap = document.getElementById('<?php echo esc_js( $uid ); ?>');
            if (!wrap) return;
            var cards = wrap.querySelectorAll('.vessel-card');
            var state = {type:'all',guests:'all',price:'all',location:'all'};

            function applyFilters(){
                cards.forEach(function(card){
                    var show = true;
                    if (state.type !== 'all' && show) {
                        var types = (card.getAttribute('data-vtype') || '').split(' ');
                        if (types.indexOf(state.type) === -1) show = false;
                    }
                    if (state.guests !== 'all' && show) {
                        var g = parseInt(card.getAttribute('data-guests') || '0');
                        var gr = state.guests;
                        if (gr === '1-6' && g > 6) show = false;
                        else if (gr === '7-12' && (g < 7 || g > 12)) show = false;
                        else if (gr === '13+' && g < 13) show = false;
                    }
                    if (state.price !== 'all' && show) {
                        var p = parseFloat(card.getAttribute('data-price') || '0');
                        var r = state.price;
                        if (r === '0-5000' && p >= 5000) show = false;
                        else if (r === '5000-15000' && (p < 5000 || p >= 15000)) show = false;
                        else if (r === '15000-50000' && (p < 15000 || p >= 50000)) show = false;
                        else if (r === '50000+' && p < 50000) show = false;
                    }
                    if (state.location !== 'all' && show) {
                        var loc = (card.getAttribute('data-location') || '').toLowerCase();
                        if (loc.indexOf(state.location.toLowerCase()) === -1) show = false;
                    }
                    card.classList.toggle('is-hidden', !show);
                });
            }

            wrap.querySelectorAll('.oc-vg-filterbar__select').forEach(function(sel){
                sel.addEventListener('change', function(){
                    var key = sel.getAttribute('data-vf');
                    if (key) { state[key] = sel.value; applyFilters(); }
                });
            });
        })();
        </script>
        <?php
    }
}

/* ============================================================
   10. OC_Itinerary_Grid_Widget
   ============================================================ */
class OC_Itinerary_Grid_Widget extends \Elementor\Widget_Base {
    use OC_Widget_Style_Trait;

    public function get_name()        { return 'oc-itinerary-grid'; }
    public function get_title()       { return __( 'OC Itinerary Grid', 'ocean-charter' ); }
    public function get_icon()        { return 'eicon-map-pin'; }
    public function get_categories()  { return [ 'ocean-charter' ]; }

    protected function register_controls() {
        $this->start_controls_section( 'content', [ 'label' => __( 'Content', 'ocean-charter' ) ] );
        $this->add_control( 'posts_count', [
            'label'   => __( 'Posts Count', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::NUMBER,
            'default' => 3,
            'min'     => 1,
        ] );
        $this->add_control( 'columns', [
            'label'   => __( 'Columns', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => '3',
            'options' => [ '1' => '1', '2' => '2', '3' => '3', '4' => '4' ],
        ] );
        $this->add_control( 'excerpt_length', [
            'label'   => __( 'Excerpt Length (chars)', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::NUMBER,
            'default' => 120,
            'min'     => 0,
            'max'     => 400,
        ] );
        $this->add_control( 'section_heading', [
            'label'   => __( 'Section Heading', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'Sample Itineraries', 'ocean-charter' ),
        ] );
        $this->add_control( 'section_eyebrow', [
            'label'   => __( 'Section Eyebrow', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'Chart Your Course', 'ocean-charter' ),
        ] );
        $this->end_controls_section();

        $this->oc_register_heading_style( '.oc-itinerary-grid' );
        $this->oc_register_card_style( '.oc-itinerary-grid .itin-card', 'Itinerary Card' );
        $this->oc_register_title_style( '.oc-itinerary-grid .itin-card__body h3' );
        $this->oc_register_excerpt_style( '.oc-itinerary-grid .itin-card__excerpt' );
        $this->oc_register_button_style( '.oc-itinerary-grid .itin-card__btn' );
        $this->oc_register_grid_style( '.oc-itinerary-grid__grid' );
        $this->oc_register_image_style( '.oc-itinerary-grid .itin-card__img-wrap' );
    }

    protected function render() {
        $s     = $this->get_settings_for_display();
        $count = ! empty( $s['posts_count'] ) ? intval( $s['posts_count'] ) : 3;
        $cols  = intval( $s['columns'] ?? 3 );
        $len   = intval( $s['excerpt_length'] ?? 120 );

        $query = new WP_Query( [
            'post_type'      => 'oc_itinerary',
            'posts_per_page' => $count,
            'post_status'    => 'publish',
        ] );
        ?>
        <style>
        .oc-itinerary-grid { width:100%; }
        .oc-itinerary-grid .oc-section-eyebrow { display:block; font-size:0.75rem; font-weight:700; letter-spacing:0.18em; text-transform:uppercase; color:var(--primary,#d9b230); margin-bottom:0.5rem; }
        .oc-itinerary-grid .oc-section-heading { font-family:var(--font-heading,'Playfair Display',serif); font-size:clamp(2rem,3.5vw,3.25rem); color:var(--text,#f0ece3); margin:0 0 2rem; font-weight:400; font-style:italic; }
        .oc-itinerary-grid__grid { display:grid; grid-template-columns:repeat(var(--oc-ig-cols,3),1fr); gap:1.5rem; }
        @media(max-width:900px){.oc-itinerary-grid__grid{grid-template-columns:repeat(2,1fr);}}
        @media(max-width:540px){.oc-itinerary-grid__grid{grid-template-columns:1fr;}}
        .oc-itinerary-grid .itin-card { background:var(--surface,#111a28); border:1px solid rgba(255,255,255,0.06); border-radius:var(--radius-lg,1rem); overflow:hidden; transition:box-shadow 0.3s,transform 0.3s; }
        .oc-itinerary-grid .itin-card:hover { transform:translateY(-4px); box-shadow:0 12px 40px rgba(0,0,0,0.45); border-color:rgba(217,178,48,0.2); }
        .oc-itinerary-grid .itin-card__img-wrap { position:relative; aspect-ratio:4/3; overflow:hidden; }
        .oc-itinerary-grid .itin-card__img-wrap img { width:100%; height:100%; object-fit:cover; transition:transform 0.5s ease; }
        .oc-itinerary-grid .itin-card:hover .itin-card__img-wrap img { transform:scale(1.05); }
        .oc-itinerary-grid .itin-card__img-placeholder { width:100%; height:100%; background:linear-gradient(135deg,#0d1f35,#1a2d45); }
        .oc-itinerary-grid .itin-card__duration { position:absolute; top:1rem; right:1rem; background:rgba(26,34,51,0.88); backdrop-filter:blur(8px); -webkit-backdrop-filter:blur(8px); border:1px solid rgba(217,178,48,0.2); border-radius:9999px; padding:0.25rem 0.75rem; font-size:0.78rem; font-weight:700; color:var(--primary,#d9b230); }
        .oc-itinerary-grid .itin-card__body { padding:1.25rem 1.5rem 1.5rem; }
        .oc-itinerary-grid .itin-card__region { font-size:0.7rem; font-weight:700; letter-spacing:0.12em; text-transform:uppercase; color:var(--primary,#d9b230); margin-bottom:0.4rem; display:block; }
        .oc-itinerary-grid .itin-card__body h3 { font-family:var(--font-heading,'Playfair Display',serif); font-size:1.15rem; color:var(--text,#f0ece3); margin:0 0 0.5rem; font-weight:600; }
        .oc-itinerary-grid .itin-card__excerpt { font-size:0.875rem; color:var(--text-muted,rgba(148,163,184,1)); line-height:1.6; margin:0 0 0.875rem; }
        .oc-itinerary-grid .itin-card__price { font-size:0.9rem; color:var(--primary,#d9b230); font-weight:700; margin-bottom:1rem; }
        .oc-itinerary-grid .itin-card__price span { font-size:0.78rem; font-weight:400; color:var(--text-muted,rgba(148,163,184,1)); }
        .oc-itinerary-grid .itin-card__btn { display:block; text-align:center; border:1px solid rgba(217,178,48,0.35); color:var(--primary,#d9b230); font-size:0.8125rem; font-weight:600; padding:0.6rem 1rem; border-radius:9999px; text-decoration:none; transition:background 0.2s,color 0.2s; }
        .oc-itinerary-grid .itin-card__btn:hover { background:var(--primary,#d9b230); color:#0a0f1a; }
        </style>

        <?php
        $this->add_render_attribute( 'ig_wrapper', 'class', 'oc-itinerary-grid' );
        $this->add_render_attribute( 'ig_wrapper', 'style', '--oc-ig-cols:' . $cols . ';' );
        ?>
        <div <?php $this->print_render_attribute_string( 'ig_wrapper' ); ?>>
            <?php if ( $s['section_eyebrow'] ) : ?>
                <span class="oc-section-eyebrow"><?php echo esc_html( $s['section_eyebrow'] ); ?></span>
            <?php endif; ?>
            <?php if ( $s['section_heading'] ) : ?>
                <h2 class="oc-section-heading"><?php echo esc_html( $s['section_heading'] ); ?></h2>
            <?php endif; ?>

            <div class="oc-itinerary-grid__grid">
                <?php if ( $query->have_posts() ) :
                    while ( $query->have_posts() ) : $query->the_post();
                        $pid      = get_the_ID();
                        $thumb    = get_the_post_thumbnail_url( $pid, 'large' );
                        $duration = get_post_meta( $pid, '_oc_duration', true );
                        $region   = get_post_meta( $pid, '_oc_region', true );
                        $price    = get_post_meta( $pid, '_oc_price', true );
                        $excerpt  = get_the_excerpt();
                        if ( $len > 0 && mb_strlen( $excerpt ) > $len ) { $excerpt = mb_strimwidth( $excerpt, 0, $len, '…' ); }
                    ?>
                    <div class="itin-card">
                        <div class="itin-card__img-wrap">
                            <?php if ( $thumb ) : ?>
                                <img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy">
                            <?php else : ?>
                                <div class="itin-card__img-placeholder"></div>
                            <?php endif; ?>
                            <?php if ( $duration ) : ?>
                                <span class="itin-card__duration"><?php echo esc_html( $duration ); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="itin-card__body">
                            <?php if ( $region ) : ?>
                                <span class="itin-card__region"><?php echo esc_html( $region ); ?></span>
                            <?php endif; ?>
                            <h3><?php the_title(); ?></h3>
                            <?php if ( $excerpt ) : ?>
                                <p class="itin-card__excerpt"><?php echo esc_html( $excerpt ); ?></p>
                            <?php endif; ?>
                            <?php if ( $price ) : ?>
                                <p class="itin-card__price"><span><?php esc_html_e( 'From', 'ocean-charter' ); ?> </span><?php echo esc_html( $price ); ?></p>
                            <?php endif; ?>
                            <a href="<?php echo esc_url( get_permalink() ); ?>" class="itin-card__btn"><?php esc_html_e( 'View Itinerary', 'ocean-charter' ); ?></a>
                        </div>
                    </div>
                    <?php endwhile; wp_reset_postdata(); ?>
                <?php else : ?>
                    <p style="color:var(--text-muted,rgba(148,163,184,1));"><?php esc_html_e( 'No itineraries found.', 'ocean-charter' ); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}

/* ============================================================
   11. OC_Featured_Vessels_Widget
   ============================================================ */
class OC_Featured_Vessels_Widget extends \Elementor\Widget_Base {
    use OC_Widget_Style_Trait;

    public function get_name()        { return 'oc-featured-vessels'; }
    public function get_title()       { return __( 'OC Featured Vessels', 'ocean-charter' ); }
    public function get_icon()        { return 'eicon-posts-justified'; }
    public function get_categories()  { return [ 'ocean-charter' ]; }

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

        /* ── Vessel Specs Repeater ── */
        $spec_repeater = new \Elementor\Repeater();
        $spec_repeater->add_control( 'spec_meta_key', [
            'label'   => __( 'Meta Field', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => '_bbc_length',
            'options' => [
                '_bbc_length'     => __( 'Length', 'ocean-charter' ),
                '_bbc_max_guests' => __( 'Max Guests', 'ocean-charter' ),
                '_bbc_speed'      => __( 'Speed', 'ocean-charter' ),
                '_bbc_max_speed'  => __( 'Max Speed', 'ocean-charter' ),
                '_bbc_cabins'     => __( 'Cabins', 'ocean-charter' ),
                '_bbc_crew'       => __( 'Crew', 'ocean-charter' ),
                '_bbc_year'       => __( 'Year Built', 'ocean-charter' ),
                '_bbc_builder'    => __( 'Builder', 'ocean-charter' ),
                '_bbc_beam'       => __( 'Beam', 'ocean-charter' ),
                '_bbc_boat_type'  => __( 'Boat Type', 'ocean-charter' ),
                '_bbc_location'   => __( 'Location', 'ocean-charter' ),
            ],
        ] );
        $spec_repeater->add_control( 'spec_suffix', [
            'label'       => __( 'Suffix / Label', 'ocean-charter' ),
            'type'        => \Elementor\Controls_Manager::TEXT,
            'default'     => '',
            'description' => __( 'Text after the value, e.g. "m", "Guests", "kts"', 'ocean-charter' ),
        ] );
        $spec_repeater->add_control( 'spec_icon', [
            'label'   => __( 'Icon', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => 'ruler',
            'options' => [
                'ruler'    => __( 'Ruler (length)', 'ocean-charter' ),
                'users'    => __( 'Users (guests)', 'ocean-charter' ),
                'wind'     => __( 'Wind (speed)', 'ocean-charter' ),
                'anchor'   => __( 'Anchor', 'ocean-charter' ),
                'calendar' => __( 'Calendar (year)', 'ocean-charter' ),
                'wrench'   => __( 'Wrench (builder)', 'ocean-charter' ),
                'compass'  => __( 'Compass', 'ocean-charter' ),
                'bed'      => __( 'Bed (cabins)', 'ocean-charter' ),
                'ship'     => __( 'Ship (type)', 'ocean-charter' ),
                'map-pin'  => __( 'Map Pin (location)', 'ocean-charter' ),
            ],
        ] );
        $this->add_control( 'vessel_specs', [
            'label'       => __( 'Vessel Specs', 'ocean-charter' ),
            'type'        => \Elementor\Controls_Manager::REPEATER,
            'fields'      => $spec_repeater->get_controls(),
            'default'     => [
                [ 'spec_meta_key' => '_bbc_length',     'spec_suffix' => 'm',      'spec_icon' => 'ruler' ],
                [ 'spec_meta_key' => '_bbc_max_guests', 'spec_suffix' => 'Guests', 'spec_icon' => 'users' ],
                [ 'spec_meta_key' => '_bbc_speed',      'spec_suffix' => 'kts',    'spec_icon' => 'wind' ],
                [ 'spec_meta_key' => '_bbc_cabins',     'spec_suffix' => 'Cabins', 'spec_icon' => 'bed' ],
                [ 'spec_meta_key' => '_bbc_crew',       'spec_suffix' => 'Crew',   'spec_icon' => 'users' ],
            ],
            'title_field' => '{{{ spec_suffix || spec_meta_key }}}',
        ] );

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

    protected function render() {
        $s = $this->get_settings_for_display();
        $count = max(1, intval($s['posts_count'] ?? 3));
        // Query the BBC 'boat' CPT so Featured Vessels link to the same
        // single-boat.php template used by the Fleet / Vessel Grid widget.
        $args = [ 'post_type' => 'boat', 'posts_per_page' => $count, 'post_status' => 'publish', 'orderby' => 'menu_order', 'order' => 'ASC' ];
        if ( ($s['featured_only'] ?? '') === 'yes' ) {
            $args['meta_query'] = [ [ 'key' => '_bbc_featured', 'value' => '1', 'compare' => '=' ] ];
        }
        $query = new WP_Query($args);
        $cols = intval( $s['columns'] ?? 3 );
        $this->add_render_attribute( 'fv_wrapper', 'class', 'oc-fv' );
        $this->add_render_attribute( 'fv_wrapper', 'style', '--oc-fv-cols:' . $cols . ';' );
        ?>
        <style>
        .oc-fv{width:100%}
        .oc-fv__header{display:flex;align-items:center;justify-content:space-between;margin-bottom:2.5rem;flex-wrap:wrap;gap:1rem}
        .oc-fv__header-text .oc-section-eyebrow{display:block;font-size:.7rem;font-weight:700;letter-spacing:.2em;text-transform:uppercase;color:var(--primary,#d9b230);margin-bottom:.6rem}
        .oc-fv__header-text .oc-section-heading{font-family:var(--font-heading,'Playfair Display',serif);font-size:clamp(2rem,3.5vw,3rem);color:var(--text,#f0ece3);margin:0;font-weight:400;font-style:normal;line-height:1.15}
        .oc-fv__view-all{display:inline-flex;align-items:center;gap:.4rem;font-size:.875rem;font-weight:600;color:var(--primary,#d9b230);text-decoration:none;white-space:nowrap;transition:opacity .2s}
        .oc-fv__view-all:hover{opacity:.75}
        .oc-fv__grid{display:grid;grid-template-columns:repeat(var(--oc-fv-cols,3),1fr);gap:1.25rem}
        @media(max-width:900px){.oc-fv__grid{grid-template-columns:repeat(2,1fr)}}
        @media(max-width:540px){.oc-fv__grid{grid-template-columns:1fr}}
        .oc-fv .fv-card{background:#0e1420;border-radius:1rem;overflow:hidden;display:flex;flex-direction:column;border:1px solid rgba(255,255,255,.08);transition:transform .3s,box-shadow .3s;text-decoration:none;color:inherit}
        .oc-fv .fv-card:hover{transform:translateY(-4px);box-shadow:0 20px 60px rgba(0,0,0,.6)}
        .oc-fv .fv-card__img-wrap{position:relative;aspect-ratio:4/5;overflow:hidden}
        .oc-fv .fv-card__img-wrap img{width:100%;height:100%;object-fit:cover;transition:transform .6s ease}
        .oc-fv .fv-card:hover .fv-card__img-wrap img{transform:scale(1.05)}
        .oc-fv .fv-card__img-placeholder{width:100%;height:100%;background:linear-gradient(160deg,#0d1f35,#1a3352)}
        .oc-fv .fv-card__badge{position:absolute;top:1rem;right:1rem;background:var(--primary,#d9b230);color:#0a0f1a;font-size:.65rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase;padding:.3rem .8rem;border-radius:9999px}
        .oc-fv .fv-card__body{padding:1.25rem 1.5rem 1.5rem;display:flex;flex-direction:column;flex-grow:1}
        .oc-fv .fv-card__title-row{display:flex;align-items:baseline;justify-content:space-between;gap:.75rem;margin-bottom:.75rem}
        .oc-fv .fv-card__name{font-family:var(--font-heading,'Playfair Display',serif);font-size:1.35rem;font-weight:400;color:var(--text,#f0ece3);margin:0;line-height:1.2}
        .oc-fv .fv-card__price{text-align:right;white-space:nowrap;flex-shrink:0}
        .oc-fv .fv-card__price-amount{font-size:1.2rem;font-weight:800;color:var(--primary,#d9b230);line-height:1}
        .oc-fv .fv-card__price-unit{font-size:.75rem;color:var(--primary,#d9b230);font-weight:600}
        .oc-fv .fv-card__specs{display:flex;align-items:center;gap:.5rem;padding:.7rem 0;border-top:1px solid rgba(255,255,255,.08);margin-bottom:1.1rem;flex-wrap:wrap}
        .oc-fv .fv-card__spec{display:flex;align-items:center;gap:.35rem;font-size:.8125rem;color:var(--text-muted,rgba(148,163,184,1))}
        .oc-fv .fv-card__spec-icon{display:flex;align-items:center;color:var(--text-muted,rgba(148,163,184,1));flex-shrink:0}
        .oc-fv .fv-card__spec-dot{color:rgba(148,163,184,.4);font-size:.6rem}
        .oc-fv .fv-card__btn{display:block;text-align:center;padding:.75rem 1rem;border-radius:.5rem;border:1px solid rgba(217,178,48,.5);color:var(--primary,#d9b230);font-weight:700;font-size:.875rem;text-decoration:none;letter-spacing:.06em;text-transform:uppercase;transition:background .2s,color .2s,border-color .2s;margin-top:auto}
        .oc-fv .fv-card__btn:hover{background:var(--primary,#d9b230);color:#0a0f1a;border-color:var(--primary,#d9b230)}
        </style>
        <div <?php $this->print_render_attribute_string('fv_wrapper'); ?>>
            <div class="oc-fv__header">
                <div class="oc-fv__header-text">
                    <?php if (!empty($s['section_eyebrow'])): ?><span class="oc-section-eyebrow"><?php echo esc_html($s['section_eyebrow']); ?></span><?php endif; ?>
                    <?php if (!empty($s['section_heading'])): ?><h2 class="oc-section-heading"><?php echo esc_html($s['section_heading']); ?></h2><?php endif; ?>
                </div>
                <?php if (!empty($s['view_all_label']) && !empty($s['view_all_url']['url'])): ?>
                    <a href="<?php echo esc_url($s['view_all_url']['url']); ?>" class="oc-fv__view-all"><?php echo esc_html($s['view_all_label']); ?> &rarr;</a>
                <?php endif; ?>
            </div>
            <div class="oc-fv__grid">
            <?php if ($query->have_posts()): while ($query->have_posts()): $query->the_post();
                $pid      = get_the_ID();
                $thumb    = get_the_post_thumbnail_url($pid,'large');
                if ( ! $thumb ) {
                    $gallery = get_post_meta( $pid, '_bbc_gallery', true );
                    if ( ! empty( $gallery ) && is_array( $gallery ) ) {
                        $thumb = wp_get_attachment_image_url( $gallery[0], 'large' );
                    }
                }
                // BBC meta keys (same CPT as Vessel Grid / Fleet page)
                $price_day = floatval( get_post_meta($pid,'_bbc_price_day',true) );
                $price     = $price_day ? '$' . number_format($price_day,0) : '';
                $length    = get_post_meta($pid,'_bbc_length',true);
                $guests    = get_post_meta($pid,'_bbc_max_guests',true);
                $speed     = get_post_meta($pid,'_bbc_speed',true);
                $feat      = get_post_meta($pid,'_bbc_featured',true);
                $vtype     = get_post_meta($pid,'_bbc_boat_type',true);
                $amen_raw  = get_post_meta($pid,'_bbc_amenities',true);
                $amen      = is_array($amen_raw) ? $amen_raw : ( $amen_raw ? json_decode($amen_raw,true) : [] );
                if (!is_array($amen)) $amen = [];
                $amen      = array_slice($amen,0,intval($s['excerpt_length'] ?? 4));

                // Build specs from repeater
                $spec_icons_map = [
                    'ruler'    => '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>',
                    'users'    => '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
                    'wind'     => '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.59 4.59A2 2 0 1 1 11 8H2m10.59 11.41A2 2 0 1 0 14 16H2m15.73-8.27A2.5 2.5 0 1 1 19.5 12H2"/></svg>',
                    'anchor'   => '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="5" r="3"/><line x1="12" y1="22" x2="12" y2="8"/><path d="M5 12H2a10 10 0 0 0 20 0h-3"/></svg>',
                    'calendar' => '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>',
                    'wrench'   => '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>',
                    'compass'  => '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/></svg>',
                    'bed'      => '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 4v16"/><path d="M2 8h18a2 2 0 0 1 2 2v10"/><path d="M2 17h20"/><path d="M6 8v9"/></svg>',
                    'ship'     => '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 21c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.5 0 2.5 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/><path d="M19.38 20A11.6 11.6 0 0 0 21 14l-9-4-9 4c0 2.9.94 5.34 2.81 7.76"/><path d="M19 13V7a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v6"/><path d="M12 1v4"/></svg>',
                    'map-pin'  => '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>',
                ];
                $vessel_specs = $s['vessel_specs'] ?? [];
                $rendered_specs = [];
                foreach ( $vessel_specs as $spec_item ) {
                    $meta_val = get_post_meta( $pid, $spec_item['spec_meta_key'], true );
                    if ( ! $meta_val ) continue;
                    $icon_svg = $spec_icons_map[ $spec_item['spec_icon'] ] ?? $spec_icons_map['ruler'];
                    $suffix   = $spec_item['spec_suffix'] ? ' ' . $spec_item['spec_suffix'] : '';
                    $rendered_specs[] = '<span class="fv-card__spec"><span class="fv-card__spec-icon">' . $icon_svg . '</span>' . esc_html( $meta_val . $suffix ) . '</span>';
                }
                ?>
                <a href="<?php echo esc_url(get_permalink()); ?>" class="fv-card">
                    <div class="fv-card__img-wrap">
                        <?php if ($thumb): ?><img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" loading="lazy"><?php else: ?><div class="fv-card__img-placeholder"></div><?php endif; ?>
                        <?php if ($feat): ?><span class="fv-card__badge">TOP RATED</span><?php endif; ?>
                    </div>
                    <div class="fv-card__body">
                        <div class="fv-card__title-row">
                            <h3 class="fv-card__name"><?php the_title(); ?></h3>
                            <?php if ($price): ?><div class="fv-card__price"><span class="fv-card__price-amount"><?php echo esc_html($price); ?></span><span class="fv-card__price-unit">/day</span></div><?php endif; ?>
                        </div>
                        <?php if ( ! empty( $rendered_specs ) ) : ?>
                        <div class="fv-card__specs">
                            <?php echo implode( '<span class="fv-card__spec-dot">·</span>', $rendered_specs ); ?>
                        </div>
                        <?php endif; ?>
                        <span class="fv-card__btn"><?php esc_html_e('Explore Vessel','ocean-charter'); ?></span>
                    </div>
                </a>
            <?php endwhile; wp_reset_postdata(); else: ?>
                <p style="color:var(--text-muted,rgba(148,163,184,1));"><?php esc_html_e('No vessels found.','ocean-charter'); ?></p>
            <?php endif; ?>
            </div>
        </div>
        <?php
    }
}

/* ============================================================
   12. OC_Why_Us_Widget
   ============================================================ */
class OC_Why_Us_Widget extends \Elementor\Widget_Base {
    use OC_Widget_Style_Trait;

    public function get_name()        { return 'oc-why-us'; }
    public function get_title()       { return __( 'OC Why Us', 'ocean-charter' ); }
    public function get_icon()        { return 'eicon-anchor'; }
    public function get_categories()  { return [ 'ocean-charter' ]; }

    protected function register_controls() {

        /* ── Content ─────────────────────────────────────────── */
        $this->start_controls_section( 'content', [ 'label' => __( 'Content', 'ocean-charter' ) ] );
        $this->add_control( 'eyebrow',     [ 'label' => __( 'Eyebrow', 'ocean-charter' ),     'type' => \Elementor\Controls_Manager::TEXT,     'default' => 'THE OCEAN ADVANTAGE' ] );
        $this->add_control( 'heading',     [ 'label' => __( 'Heading', 'ocean-charter' ),     'type' => \Elementor\Controls_Manager::TEXTAREA,  'default' => 'Crafting Memories That Sail Beyond the Ordinary' ] );
        $this->add_control( 'description', [ 'label' => __( 'Description', 'ocean-charter' ), 'type' => \Elementor\Controls_Manager::TEXTAREA,  'default' => "Since 1999, we have been crafting extraordinary private yacht experiences for discerning travellers across the world's most coveted waters. Every charter is a masterpiece of planning, expertise, and passion." ] );
        $this->end_controls_section();

        /* ── Stats Card ──────────────────────────────────────── */
        $this->start_controls_section( 'section_stats', [ 'label' => __( 'Stats Card', 'ocean-charter' ) ] );
        $this->add_control( 'stat_years',        [ 'label' => __( 'Years Number', 'ocean-charter' ),        'type' => \Elementor\Controls_Manager::TEXT, 'default' => '25' ] );
        $this->add_control( 'stat_years_label',  [ 'label' => __( 'Years Label (use &lt;br&gt; for line break)', 'ocean-charter' ), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'Years of Unmatched<br>Maritime Excellence' ] );
        $this->add_control( 'stat_charters',     [ 'label' => __( 'Charters Stat', 'ocean-charter' ),       'type' => \Elementor\Controls_Manager::TEXT, 'default' => '2,400+' ] );
        $this->add_control( 'stat_destinations', [ 'label' => __( 'Destinations Stat', 'ocean-charter' ),   'type' => \Elementor\Controls_Manager::TEXT, 'default' => '60+' ] );
        $this->add_control( 'stat_rating',       [ 'label' => __( 'Rating Stat', 'ocean-charter' ),         'type' => \Elementor\Controls_Manager::TEXT, 'default' => '★ 4.9' ] );
        $this->end_controls_section();

        /* ── Images ──────────────────────────────────────────── */
        $this->start_controls_section( 'section_images', [ 'label' => __( 'Images', 'ocean-charter' ) ] );
        $this->add_control( 'primary_image', [
            'label'   => __( 'Primary Image', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::MEDIA,
            'default' => [ 'url' => 'https://images.pexels.com/photos/1118873/pexels-photo-1118873.jpeg?auto=compress&cs=tinysrgb&w=1200' ],
        ] );
        $this->add_control( 'secondary_image', [
            'label'   => __( 'Overlay Image', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::MEDIA,
            'default' => [ 'url' => 'https://images.pexels.com/photos/1268856/pexels-photo-1268856.jpeg?auto=compress&cs=tinysrgb&w=800' ],
        ] );
        $this->end_controls_section();

        /* ── Feature Items (Repeater) ────────────────────────── */
        $this->start_controls_section( 'section_features', [ 'label' => __( 'Feature Items', 'ocean-charter' ) ] );
        $repeater = new \Elementor\Repeater();
        $repeater->add_control( 'feature_icon', [
            'label'   => __( 'Icon', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => 'anchor',
            'options' => [
                'anchor'  => __( 'Anchor',  'ocean-charter' ),
                'star'    => __( 'Star',    'ocean-charter' ),
                'map'     => __( 'Map',     'ocean-charter' ),
                'shield'  => __( 'Shield',  'ocean-charter' ),
                'heart'   => __( 'Heart',   'ocean-charter' ),
                'compass' => __( 'Compass', 'ocean-charter' ),
                'chart'   => __( 'Chart',   'ocean-charter' ),
            ],
        ] );
        $repeater->add_control( 'feature_title', [
            'label'   => __( 'Title', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => '',
        ] );
        $repeater->add_control( 'feature_text', [
            'label'   => __( 'Text', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::TEXTAREA,
            'default' => '',
        ] );
        $this->add_control( 'features', [
            'label'       => __( 'Features', 'ocean-charter' ),
            'type'        => \Elementor\Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'default'     => [
                [ 'feature_icon' => 'compass', 'feature_title' => 'Global Elite Network',   'feature_text' => 'Access to an exclusive worldwide fleet of luxury yachts in the most sought-after destinations.' ],
                [ 'feature_icon' => 'star',    'feature_title' => 'Bespoke Concierge',      'feature_text' => 'Dedicated concierge team crafting tailor-made itineraries, dining, and shore excursions.' ],
                [ 'feature_icon' => 'shield',  'feature_title' => 'Safety & Privacy',       'feature_text' => 'Rigorous vetting, ISM-certified crew, and full commercial insurance for peace of mind.' ],
            ],
            'title_field' => '{{{ feature_title }}}',
        ] );
        $this->end_controls_section();

        /* ── Style: Section Heading ──────────────────────────── */
        $this->oc_register_heading_style( '.oc-why-us' );

        /* ── Style: Feature Title ────────────────────────────── */
        $this->oc_register_title_style( '.oc-why-us__feature-body h4', 'Feature Title' );

        /* ── Style: Feature Body Text ────────────────────────── */
        $this->oc_register_excerpt_style( '.oc-why-us__feature-body p' );

        /* ── Style: Stat Card ────────────────────────────────── */
        $this->start_controls_section( 'style_stat_card', [
            'label' => __( 'Stat Card', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_control( 'stat_card_bg', [
            'label'     => __( 'Card Background', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#111a28',
            'selectors' => [ '{{WRAPPER}} .oc-why-us__stat-card' => 'background: {{VALUE}};' ],
        ] );
        $this->add_control( 'stat_num_color', [
            'label'     => __( 'Number Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#d9b230',
            'selectors' => [ '{{WRAPPER}} .oc-why-us__stat-num' => 'color: {{VALUE}};' ],
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'stat_num_typography',
            'label'    => __( 'Number Typography', 'ocean-charter' ),
            'selector' => '{{WRAPPER}} .oc-why-us__stat-num',
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'stat_label_typography',
            'label'    => __( 'Label Typography', 'ocean-charter' ),
            'selector' => '{{WRAPPER}} .oc-why-us__stat-label',
        ] );
        $this->add_control( 'stat_label_color', [
            'label'     => __( 'Label Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#f0ece3',
            'selectors' => [ '{{WRAPPER}} .oc-why-us__stat-label' => 'color: {{VALUE}};' ],
        ] );
        $this->add_control( 'stat_card_border_color', [
            'label'     => __( 'Border Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#d9b230',
            'selectors' => [ '{{WRAPPER}} .oc-why-us__stat-card' => 'border-color: {{VALUE}};' ],
        ] );
        $this->add_responsive_control( 'stat_card_border_width', [
            'label'      => __( 'Border Width (Thickness)', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 12, 'step' => 1 ] ],
            'default'    => [ 'size' => 2, 'unit' => 'px' ],
            'selectors'  => [ '{{WRAPPER}} .oc-why-us__stat-card' => 'border-width: {{SIZE}}{{UNIT}}; border-style: solid;' ],
        ] );
        $this->add_responsive_control( 'stat_card_h_offset', [
            'label'      => __( 'Horizontal Position', 'ocean-charter' ),
            'description'=> __( 'Move card left/right. 50% = centered.', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ '%', 'px' ],
            'range'      => [ '%' => [ 'min' => 0, 'max' => 100 ], 'px' => [ 'min' => -200, 'max' => 200 ] ],
            'default'    => [ 'size' => 50, 'unit' => '%' ],
            'selectors'  => [ '{{WRAPPER}} .oc-why-us__stat-card' => 'left: {{SIZE}}{{UNIT}};' ],
        ] );
        $this->add_responsive_control( 'stat_card_v_offset', [
            'label'      => __( 'Vertical Position', 'ocean-charter' ),
            'description'=> __( 'Distance from bottom of image area.', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'rem', 'px' ],
            'range'      => [ 'rem' => [ 'min' => -5, 'max' => 10, 'step' => 0.25 ], 'px' => [ 'min' => -80, 'max' => 160 ] ],
            'default'    => [ 'size' => 1.5, 'unit' => 'rem' ],
            'selectors'  => [ '{{WRAPPER}} .oc-why-us__stat-card' => 'bottom: {{SIZE}}{{UNIT}};' ],
        ] );
        $this->add_responsive_control( 'stat_card_padding', [
            'label'      => __( 'Card Padding', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'rem' ],
            'selectors'  => [ '{{WRAPPER}} .oc-why-us__stat-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );
        $this->add_responsive_control( 'stat_card_border_radius', [
            'label'      => __( 'Border Radius', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'rem' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 50 ], 'rem' => [ 'min' => 0, 'max' => 3, 'step' => 0.125 ] ],
            'default'    => [ 'size' => 1, 'unit' => 'rem' ],
            'selectors'  => [ '{{WRAPPER}} .oc-why-us__stat-card' => 'border-radius: {{SIZE}}{{UNIT}};' ],
        ] );
        $this->add_responsive_control( 'stat_card_width', [
            'label'      => __( 'Card Width', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', '%', 'rem' ],
            'range'      => [ 'px' => [ 'min' => 150, 'max' => 600 ], '%' => [ 'min' => 20, 'max' => 100 ], 'rem' => [ 'min' => 10, 'max' => 40 ] ],
            'selectors'  => [ '{{WRAPPER}} .oc-why-us__stat-card' => 'width: {{SIZE}}{{UNIT}};' ],
        ] );
        $this->add_responsive_control( 'stat_card_min_height', [
            'label'      => __( 'Card Min Height', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'rem' ],
            'range'      => [ 'px' => [ 'min' => 50, 'max' => 400 ], 'rem' => [ 'min' => 3, 'max' => 25 ] ],
            'selectors'  => [ '{{WRAPPER}} .oc-why-us__stat-card' => 'min-height: {{SIZE}}{{UNIT}};' ],
        ] );
        $this->end_controls_section();

        /* ── Style: Layout ──────────────────────────────────── */
        $this->start_controls_section( 'style_layout', [
            'label' => __( 'Layout', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_responsive_control( 'layout_gap', [
            'label'      => __( 'Gap (Image ↔ Text)', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'rem', 'px' ],
            'range'      => [ 'rem' => [ 'min' => 0, 'max' => 10, 'step' => 0.25 ], 'px' => [ 'min' => 0, 'max' => 160 ] ],
            'default'    => [ 'size' => 3.5, 'unit' => 'rem' ],
            'selectors'  => [ '{{WRAPPER}} .oc-why-us' => 'gap: {{SIZE}}{{UNIT}};' ],
        ] );
        $this->add_control( 'image_shape', [
            'label'   => __( 'Image Shape', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => '4/3',
            'options' => [
                '1/1'  => __( 'Square (1:1)',       'ocean-charter' ),
                '4/3'  => __( 'Landscape (4:3)',    'ocean-charter' ),
                '3/4'  => __( 'Portrait (3:4)',     'ocean-charter' ),
                '16/9' => __( 'Wide (16:9)',        'ocean-charter' ),
                '3/2'  => __( 'Classic (3:2)',      'ocean-charter' ),
                'auto' => __( 'Natural (no crop)',  'ocean-charter' ),
            ],
            'selectors' => [ '{{WRAPPER}} .oc-why-us__img-main, {{WRAPPER}} .oc-why-us__img-placeholder' => 'aspect-ratio: {{VALUE}};' ],
        ] );
        $this->add_responsive_control( 'image_border_radius', [
            'label'      => __( 'Image Border Radius', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'rem' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 60 ], 'rem' => [ 'min' => 0, 'max' => 4, 'step' => 0.125 ] ],
            'default'    => [ 'size' => 1.5, 'unit' => 'rem' ],
            'selectors'  => [ '{{WRAPPER}} .oc-why-us__img-main, {{WRAPPER}} .oc-why-us__img-placeholder' => 'border-radius: {{SIZE}}{{UNIT}};' ],
        ] );
        $this->end_controls_section();

        /* ── Style: Overlay Image ────────────────────────────── */
        $this->start_controls_section( 'style_overlay_image', [
            'label' => __( 'Overlay Image', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_control( 'overlay_width', [
            'label'      => __( 'Overlay Width', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ '%' ],
            'default'    => [ 'size' => 55, 'unit' => '%' ],
            'selectors'  => [ '{{WRAPPER}} .oc-why-us__img-overlay' => 'width: {{SIZE}}{{UNIT}};' ],
        ] );
        $this->add_control( 'overlay_bottom', [
            'label'      => __( 'Overlay Bottom Offset', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'rem' ],
            'range'      => [ 'px' => [ 'min' => -60, 'max' => 60 ] ],
            'default'    => [ 'size' => -2, 'unit' => 'rem' ],
            'selectors'  => [ '{{WRAPPER}} .oc-why-us__img-overlay' => 'bottom: {{SIZE}}{{UNIT}};' ],
        ] );
        $this->add_control( 'overlay_radius', [
            'label'      => __( 'Overlay Border Radius', 'ocean-charter' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'rem' ],
            'default'    => [ 'size' => 0.875, 'unit' => 'rem' ],
            'selectors'  => [ '{{WRAPPER}} .oc-why-us__img-overlay' => 'border-radius: {{SIZE}}{{UNIT}};' ],
        ] );
        $this->add_control( 'feature_icon_bg', [
            'label'     => __( 'Feature Icon Background', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => 'rgba(10,15,26,0.6)',
            'selectors' => [ '{{WRAPPER}} .oc-why-us__feature-icon' => 'background: {{VALUE}};' ],
        ] );
        $this->add_control( 'feature_icon_color', [
            'label'     => __( 'Feature Icon Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#d9b230',
            'selectors' => [ '{{WRAPPER}} .oc-why-us__feature-icon' => 'color: {{VALUE}};' ],
        ] );
        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();

        $icon_map = [
            'anchor'  => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="5" r="3"/><line x1="12" y1="8" x2="12" y2="22"/><path d="M5 12H2a10 10 0 0 0 20 0h-3"/></svg>',
            'star'    => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>',
            'map'     => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"/><line x1="8" y1="2" x2="8" y2="18"/><line x1="16" y1="6" x2="16" y2="22"/></svg>',
            'shield'  => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
            'heart'   => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>',
            'compass' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/></svg>',
            'chart'   => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>',
        ];

        $primary_url   = ! empty( $s['primary_image']['url'] )
            ? $s['primary_image']['url']
            : 'https://images.pexels.com/photos/1118873/pexels-photo-1118873.jpeg?auto=compress&cs=tinysrgb&w=1200';
        $secondary_url = $s['secondary_image']['url'] ?? '';
        ?>
        <style>
        .oc-why-us{display:grid;grid-template-columns:1.1fr 1fr;gap:3.5rem;align-items:center}
        .oc-why-us__visual{position:relative;padding-bottom:4rem}
        .oc-why-us__img-main{width:100%;aspect-ratio:4/3;object-fit:cover;border-radius:1.5rem;display:block}
        .oc-why-us__img-placeholder{width:100%;aspect-ratio:4/3;border-radius:1.5rem;background:linear-gradient(160deg,#0d1f35,#1a3352)}
        .oc-why-us__stat-card{position:absolute;bottom:1.5rem;left:50%;transform:translateX(-50%);background:#111a28;border:2px solid var(--primary,#d9b230);color:var(--text,#f0ece3);border-radius:1rem;padding:1.75rem 2.5rem;box-shadow:0 12px 48px rgba(0,0,0,.65);z-index:2;text-align:center;white-space:normal;box-sizing:border-box}
        @media(max-width:900px){.oc-why-us{grid-template-columns:1fr;gap:2rem}.oc-why-us__stat-card{position:static;transform:none;display:inline-block;margin:1.5rem auto 0;white-space:normal;}}
        .oc-why-us__stat-num{display:block;font-size:4rem;font-weight:900;line-height:1;font-family:var(--font-heading,'Playfair Display',serif);color:var(--primary,#d9b230)}
        .oc-why-us__stat-label{font-size:.7rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;margin-top:.5rem;color:var(--text,#f0ece3);display:block}
        .oc-why-us__eyebrow{display:block;font-size:.75rem;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:var(--primary,#d9b230);margin-bottom:.75rem}
        .oc-why-us__heading{font-family:var(--font-heading,"Playfair Display",serif);font-size:clamp(2rem,3.5vw,3rem);color:var(--text,#f0ece3);font-weight:400;line-height:1.25;margin:0 0 1.25rem}
        .oc-why-us__desc{font-size:1rem;line-height:1.8;color:var(--text-muted,rgba(148,163,184,1));margin:0 0 2.5rem}
        .oc-why-us__features{display:flex;flex-direction:column;gap:1.5rem}
        .oc-why-us__feature{display:flex;gap:1rem;align-items:flex-start}
        .oc-why-us__feature-icon{flex-shrink:0;width:48px;height:48px;background:rgba(10,15,26,.6);border:1px solid rgba(217,178,48,.2);border-radius:.75rem;display:flex;align-items:center;justify-content:center;color:var(--primary,#d9b230)}
        .oc-why-us__feature-body h4{font-size:.9375rem;font-weight:700;color:var(--text,#f0ece3);margin:0 0 .25rem}
        .oc-why-us__feature-body p{font-size:.875rem;color:var(--text-muted,rgba(148,163,184,1));line-height:1.6;margin:0}
        </style>
        <div class="oc-why-us">
            <div class="oc-why-us__visual">
                <?php if ( $primary_url ): ?><img class="oc-why-us__img-main" src="<?php echo esc_url( $primary_url ); ?>" alt="Charter yacht" loading="lazy"><?php else: ?><div class="oc-why-us__img-placeholder"></div><?php endif; ?>
                <div class="oc-why-us__stat-card">
                    <span class="oc-why-us__stat-num"><?php echo esc_html( $s['stat_years'] ?? '25+' ); ?></span>
                    <span class="oc-why-us__stat-label"><?php echo wp_kses_post( $s['stat_years_label'] ?? 'Years of Unmatched<br>Maritime Excellence' ); ?></span>
                </div>
            </div>
            <div class="oc-why-us__content">
                <?php if ( ! empty( $s['eyebrow'] ) ): ?><span class="oc-why-us__eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></span><?php endif; ?>
                <?php if ( ! empty( $s['heading'] ) ): ?><h2 class="oc-why-us__heading"><?php echo esc_html( $s['heading'] ); ?></h2><?php endif; ?>
                <?php if ( ! empty( $s['description'] ) ): ?><p class="oc-why-us__desc"><?php echo esc_html( $s['description'] ); ?></p><?php endif; ?>
                <div class="oc-why-us__features">
                    <?php foreach ( ( $s['features'] ?? [] ) as $f ) {
                        $icon_key = $f['feature_icon'] ?? 'anchor';
                        $icon_svg = $icon_map[ $icon_key ] ?? $icon_map['anchor'];
                    ?>
                    <div class="oc-why-us__feature">
                        <div class="oc-why-us__feature-icon"><?php echo $icon_svg; ?></div>
                        <div class="oc-why-us__feature-body"><h4><?php echo esc_html( $f['feature_title'] ?? '' ); ?></h4><p><?php echo esc_html( $f['feature_text'] ?? '' ); ?></p></div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php
    }
}

/* ============================================================
   13. OC_Destinations_Gallery_Widget
   ============================================================ */
class OC_Destinations_Gallery_Widget extends \Elementor\Widget_Base {
    use OC_Widget_Style_Trait;

    public function get_name()        { return 'oc-destinations-gallery'; }
    public function get_title()       { return __( 'OC Destinations Gallery', 'ocean-charter' ); }
    public function get_icon()        { return 'eicon-gallery-masonry'; }
    public function get_categories()  { return [ 'ocean-charter' ]; }

    protected function register_controls() {
        $this->start_controls_section( 'content', [ 'label' => __( 'Content', 'ocean-charter' ) ] );
        $this->add_control( 'posts_count', [ 'label' => __('Destinations to Show','ocean-charter'), 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 5, 'min' => 2 ] );
        $this->add_control( 'columns', [
            'label'   => __( 'Columns', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => '3',
            'options' => [ '2' => '2', '3' => '3', '4' => '4' ],
        ] );
        $this->add_control( 'view_all_label', [
            'label'   => __( 'View All Label', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'View All Destinations', 'ocean-charter' ),
        ] );
        $this->add_control( 'view_all_url', [
            'label'   => __( 'View All URL', 'ocean-charter' ),
            'type'    => \Elementor\Controls_Manager::URL,
            'default' => [ 'url' => '/destinations/' ],
        ] );
        $this->add_control( 'section_heading', [ 'label' => __('Heading','ocean-charter'), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'The World Awaits' ] );
        $this->add_control( 'section_eyebrow', [ 'label' => __('Eyebrow','ocean-charter'), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Our Destinations' ] );
        $this->end_controls_section();

        $this->oc_register_heading_style( '.oc-dest-gal' );
        $this->oc_register_grid_style( '.oc-dest-gal__grid' );
        $this->oc_register_title_style( '.oc-dest-gal .gal-item__title', 'Destination Title' );
        $this->oc_register_button_style( '.oc-dest-gal__view-all-btn' );

        // Region label color
        $this->start_controls_section( 'style_region', [
            'label' => __( 'Region Label', 'ocean-charter' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_control( 'region_color', [
            'label'     => __( 'Region Color', 'ocean-charter' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#d9b230',
            'selectors' => [ '{{WRAPPER}} .oc-dest-gal .gal-item__region' => 'color: {{VALUE}};' ],
        ] );
        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $count = max(2, intval($s['posts_count'] ?? 5));
        $cols = intval($s['columns'] ?? 3);
        $query = new WP_Query([
            'post_type'      => 'oc_destination',
            'posts_per_page' => $count,
            'post_status'    => 'publish',
            'orderby'        => 'menu_order date',
            'order'          => 'ASC',
        ]);
        ?>
        <style>
        .oc-dest-gal{width:100%}
        .oc-dest-gal__header{text-align:center;margin-bottom:2.5rem}
        .oc-dest-gal__header .oc-section-eyebrow{display:block;font-size:.75rem;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:var(--primary,#d9b230);margin-bottom:.5rem}
        .oc-dest-gal__header .oc-section-heading{font-family:var(--font-heading,'Playfair Display',serif);font-size:clamp(2rem,3.5vw,3.25rem);color:var(--text,#f0ece3);margin:0;font-weight:400;font-style:italic}
        .oc-dest-gal__grid{display:grid;grid-template-columns:repeat(var(--oc-dg-cols,3),1fr);grid-auto-rows:340px;gap:1rem}
        @media(max-width:1024px){.oc-dest-gal__grid{grid-template-columns:repeat(2,1fr) !important;grid-auto-rows:260px}}
        @media(max-width:540px){.oc-dest-gal__grid{grid-template-columns:1fr !important;grid-auto-rows:300px}.oc-dest-gal .gal-item:first-child{grid-row:span 1;}}
        .oc-dest-gal .gal-item{position:relative;overflow:hidden;border-radius:.875rem;display:block;text-decoration:none}
        .oc-dest-gal .gal-item:first-child{grid-row:span 2}
        .oc-dest-gal .gal-item:nth-child(4){grid-column:span 2}
        .oc-dest-gal .gal-item img{width:100%;height:100%;object-fit:cover;transition:transform .6s ease;display:block}
        .oc-dest-gal .gal-item:hover img{transform:scale(1.07)}
        .oc-dest-gal .gal-item__overlay{position:absolute;inset:0;background:linear-gradient(to top,rgba(10,15,26,.85) 0%,transparent 55%);pointer-events:none}
        .oc-dest-gal .gal-item__label{position:absolute;bottom:0;left:0;right:0;padding:1.25rem}
        .oc-dest-gal .gal-item__title{font-family:var(--font-heading,'Playfair Display',serif);font-size:1.05rem;color:#fff;font-weight:600;margin:0 0 .2rem}
        .oc-dest-gal .gal-item:first-child .gal-item__title{font-size:1.45rem}
        .oc-dest-gal .gal-item__region{font-size:.7rem;font-weight:600;text-transform:uppercase;letter-spacing:.1em;color:var(--primary,#d9b230)}
        .oc-dest-gal .gal-item__placeholder{width:100%;height:100%;background:linear-gradient(160deg,#0d1f35,#1a3352)}
        .oc-dest-gal__footer{text-align:center;margin-top:2.5rem}
        .oc-dest-gal__view-all-btn{display:inline-flex;align-items:center;gap:.5rem;font-size:.875rem;font-weight:600;color:var(--primary,#d9b230);text-decoration:none;border:1px solid rgba(217,178,48,.35);padding:.65rem 1.75rem;border-radius:9999px;transition:background .2s,color .2s}
        .oc-dest-gal__view-all-btn:hover{background:var(--primary,#d9b230);color:#0a0f1a}
        </style>
        <?php
        $this->add_render_attribute( 'dg_wrapper', 'class', 'oc-dest-gal' );
        $this->add_render_attribute( 'dg_wrapper', 'style', '--oc-dg-cols:' . $cols . ';' );
        ?>
        <div <?php $this->print_render_attribute_string( 'dg_wrapper' ); ?>>
            <?php if (!empty($s['section_eyebrow']) || !empty($s['section_heading'])): ?>
            <div class="oc-dest-gal__header">
                <?php if (!empty($s['section_eyebrow'])): ?><span class="oc-section-eyebrow"><?php echo esc_html($s['section_eyebrow']); ?></span><?php endif; ?>
                <?php if (!empty($s['section_heading'])): ?><h2 class="oc-section-heading"><?php echo esc_html($s['section_heading']); ?></h2><?php endif; ?>
            </div>
            <?php endif; ?>
            <div class="oc-dest-gal__grid">
            <?php if ($query->have_posts()): while ($query->have_posts()): $query->the_post();
                $pid    = get_the_ID();
                $thumb  = get_the_post_thumbnail_url($pid,'large');
                $link   = get_permalink();
                $terms  = get_the_terms($pid,'oc_destination_region');
                $region = ($terms && !is_wp_error($terms)) ? $terms[0]->name : '';
                ?>
                <a href="<?php echo esc_url($link); ?>" class="gal-item">
                    <?php if ($thumb): ?><img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" loading="lazy"><?php else: ?><div class="gal-item__placeholder"></div><?php endif; ?>
                    <div class="gal-item__overlay"></div>
                    <div class="gal-item__label">
                        <p class="gal-item__title"><?php the_title(); ?></p>
                        <?php if ($region): ?><span class="gal-item__region"><?php echo esc_html($region); ?></span><?php endif; ?>
                    </div>
                </a>
            <?php endwhile; wp_reset_postdata(); endif; ?>
            </div>
            <?php if ( ! empty( $s['view_all_label'] ) && ! empty( $s['view_all_url']['url'] ) ) : ?>
            <div class="oc-dest-gal__footer">
                <a href="<?php echo esc_url( $s['view_all_url']['url'] ); ?>" class="oc-dest-gal__view-all-btn">
                    <?php echo esc_html( $s['view_all_label'] ); ?> &rarr;
                </a>
            </div>
            <?php endif; ?>
        </div>
        <?php
    }
}
