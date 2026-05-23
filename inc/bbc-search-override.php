<?php
/**
 * BBC Search Form Override
 *
 * Replaces the [boat_search] shortcode with an OC-styled version:
 * - Glassmorphism background (no white outline)
 * - Flatpickr date pickers on both date fields
 * - Field order and visibility controlled from OC Theme Settings → Search Form
 * - Submits to /fleet/ (OC fleet page with JS client-side filtering)
 *
 * @package OceanCharter
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// ── Override BBC's boat_search shortcode at priority 20 ───────────────────────
add_action( 'init', function() {
    remove_shortcode( 'boat_search' );
    add_shortcode( 'boat_search', 'oc_boat_search_shortcode' );
}, 20 );

/**
 * Render the OC-styled boat search form.
 *
 * @param array $atts Shortcode attributes (unused — config comes from OC settings).
 * @return string HTML output.
 */
function oc_boat_search_shortcode( $atts = [] ) {
    // Enqueue Flatpickr — safe to call here; scripts are printed in footer
    wp_enqueue_style(
        'flatpickr',
        'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css',
        [],
        '4.6.13'
    );
    wp_enqueue_script(
        'flatpickr',
        'https://cdn.jsdelivr.net/npm/flatpickr',
        [],
        '4.6.13',
        true
    );

    // ── Settings ──────────────────────────────────────────────────────────────
    $field_order_raw  = OC_Theme_Settings::get( 'search_field_order',   'location,date_from,date_to,guests' );
    $hidden_raw       = OC_Theme_Settings::get( 'search_fields_hidden', '' );
    $button_text      = OC_Theme_Settings::get( 'search_button_text',   'Search Vessels' );
    $results_url      = OC_Theme_Settings::get( 'search_results_url',   '/fleet/' );

    $field_order = array_map( 'trim', explode( ',', $field_order_raw ) );
    $hidden      = array_filter( array_map( 'trim', explode( ',', $hidden_raw ) ) );
    $results_url = $results_url ?: '/fleet/';

    // ── Available boat locations from BBC meta ────────────────────────────────
    global $wpdb;
    $locations = $wpdb->get_col(
        "SELECT DISTINCT meta_value
         FROM {$wpdb->postmeta}
         WHERE meta_key = '_bbc_location'
           AND meta_value != ''
         ORDER BY meta_value ASC"
    );

    // ── Build output ──────────────────────────────────────────────────────────
    ob_start();
    ?>
    <style id="oc-bbc-search-css">
    .oc-bbc-search-wrapper {
        background: rgba(26,34,51,0.75);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(217,178,48,0.12);
        border-radius: 16px;
        padding: 2rem;
    }
    .oc-bbc-search-wrapper form {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: flex-end;
    }
    .oc-bbc-field {
        display: flex;
        flex-direction: column;
        gap: .4rem;
        flex: 1;
        min-width: 140px;
    }
    .oc-bbc-field label {
        font-size: .72rem;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: var(--text-muted, #94a3b8);
        font-weight: 500;
    }
    .oc-bbc-field input,
    .oc-bbc-field select {
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(217,178,48,0.2);
        border-radius: 8px;
        color: var(--text, #f0ece3);
        padding: .65rem 1rem;
        font-size: .95rem;
        width: 100%;
        box-sizing: border-box;
        appearance: none;
        -webkit-appearance: none;
        outline: none;
        transition: border-color .2s;
    }
    .oc-bbc-field input:focus,
    .oc-bbc-field select:focus {
        border-color: rgba(217,178,48,0.5);
    }
    .oc-bbc-field input::placeholder {
        color: var(--text-muted, #94a3b8);
        opacity: .7;
    }
    .oc-bbc-field select option {
        background: #111a28;
        color: #f0ece3;
    }
    .oc-bbc-submit-wrap {
        display: flex;
        align-items: flex-end;
    }
    .oc-bbc-submit {
        background: var(--primary, #d9b230);
        color: #0a0f1a;
        border: none;
        border-radius: 8px;
        padding: .72rem 2rem;
        font-size: .95rem;
        font-weight: 700;
        letter-spacing: .04em;
        cursor: pointer;
        white-space: nowrap;
        transition: opacity .2s;
        line-height: 1.4;
    }
    .oc-bbc-submit:hover { opacity: .88; }
    /* Override flatpickr calendar to match dark theme */
    .flatpickr-calendar {
        background: #111a28 !important;
        border: 1px solid rgba(217,178,48,0.2) !important;
        box-shadow: 0 12px 40px rgba(0,0,0,0.5) !important;
    }
    .flatpickr-day { color: #f0ece3 !important; }
    .flatpickr-day.selected,
    .flatpickr-day.selected:hover {
        background: #d9b230 !important;
        border-color: #d9b230 !important;
        color: #0a0f1a !important;
    }
    .flatpickr-day:hover { background: rgba(217,178,48,0.15) !important; }
    .flatpickr-day.flatpickr-disabled { color: #94a3b8 !important; opacity: .5; }
    .flatpickr-months .flatpickr-month,
    .flatpickr-weekdays,
    .flatpickr-weekday { background: #0a0f1a !important; color: #94a3b8 !important; }
    .flatpickr-current-month { color: #f0ece3 !important; }
    .flatpickr-prev-month svg,
    .flatpickr-next-month svg { fill: #d9b230 !important; }
    @media (max-width:640px) {
        .oc-bbc-search-wrapper form { flex-direction: column; }
        .oc-bbc-field { min-width: 100%; }
        .oc-bbc-submit-wrap { width: 100%; }
        .oc-bbc-submit { width: 100%; }
    }
    </style>

    <div class="oc-bbc-search-wrapper">
        <form method="get" action="<?php echo esc_url( $results_url ); ?>" role="search">
            <?php foreach ( $field_order as $slug ) :
                if ( in_array( $slug, $hidden, true ) ) continue;
                switch ( $slug ) :

                    case 'location': ?>
                        <div class="oc-bbc-field">
                            <label for="oc-bbc-location">Destination</label>
                            <?php if ( ! empty( $locations ) ) : ?>
                                <select id="oc-bbc-location" name="location">
                                    <option value="">All Destinations</option>
                                    <?php foreach ( $locations as $loc ) : ?>
                                        <option value="<?php echo esc_attr( strtolower( $loc ) ); ?>">
                                            <?php echo esc_html( $loc ); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else : ?>
                                <input type="text" id="oc-bbc-location" name="location" placeholder="e.g. Monaco">
                            <?php endif; ?>
                        </div>
                    <?php break;

                    case 'date_from': ?>
                        <div class="oc-bbc-field">
                            <label for="oc-bbc-date-from">Departure</label>
                            <input type="text"
                                   id="oc-bbc-date-from"
                                   name="date_from"
                                   class="oc-bbc-fp"
                                   data-fp-role="from"
                                   placeholder="Select date"
                                   autocomplete="off"
                                   readonly>
                        </div>
                    <?php break;

                    case 'date_to': ?>
                        <div class="oc-bbc-field">
                            <label for="oc-bbc-date-to">Return</label>
                            <input type="text"
                                   id="oc-bbc-date-to"
                                   name="date_to"
                                   class="oc-bbc-fp"
                                   data-fp-role="to"
                                   placeholder="Select date"
                                   autocomplete="off"
                                   readonly>
                        </div>
                    <?php break;

                    case 'guests': ?>
                        <div class="oc-bbc-field">
                            <label for="oc-bbc-guests">Guests</label>
                            <select id="oc-bbc-guests" name="guests">
                                <option value="">Any</option>
                                <?php for ( $g = 1; $g <= 20; $g++ ) : ?>
                                    <option value="<?php echo esc_attr( $g ); ?>">
                                        <?php echo esc_html( $g . ' ' . _n( 'Guest', 'Guests', $g, 'ocean-charter' ) ); ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    <?php break;

                endswitch;
            endforeach; ?>

            <div class="oc-bbc-submit-wrap">
                <button type="submit" class="oc-bbc-submit">
                    <?php echo esc_html( $button_text ); ?>
                </button>
            </div>
        </form>
    </div>

    <script>
    (function() {
        function initOCBBCPickers() {
            if (typeof flatpickr === 'undefined') {
                setTimeout(initOCBBCPickers, 50);
                return;
            }
            var fromEl = document.querySelector('.oc-bbc-fp[data-fp-role="from"]');
            var toEl   = document.querySelector('.oc-bbc-fp[data-fp-role="to"]');

            var fromFP = null;
            var toFP   = null;

            if (fromEl) {
                fromFP = flatpickr(fromEl, {
                    dateFormat: 'Y-m-d',
                    minDate: 'today',
                    disableMobile: true,
                    onChange: function(selected) {
                        if (toFP && selected[0]) {
                            toFP.set('minDate', selected[0]);
                        }
                    }
                });
            }

            if (toEl) {
                toFP = flatpickr(toEl, {
                    dateFormat: 'Y-m-d',
                    minDate: 'today',
                    disableMobile: true,
                });
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initOCBBCPickers);
        } else {
            initOCBBCPickers();
        }
    })();
    </script>
    <?php
    return ob_get_clean();
}
