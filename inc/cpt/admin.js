/**
 * Ocean Charter — Admin Meta Box JS
 *
 * Handles:
 *  1. Simple text repeater (oc-repeater)   → serialises rows to JSON hidden input
 *  2. Route stops repeater (oc-route-stops) → serialises name/x/y rows to JSON
 *  3. Image upload button via wp.media
 *
 * Runs on all OC CPT edit screens (enqueued conditionally in meta-fields.php).
 */
( function ( $ ) {
    'use strict';

    /* ── 1. Text repeater serialiser ─────────────────────────── */

    /**
     * Collect all repeater-input values for a given .oc-repeater container
     * and write the JSON array to the hidden .oc-repeater-value input.
     */
    function syncRepeater( $container ) {
        var values = [];
        $container.find( '.oc-repeater-input' ).each( function () {
            var v = $.trim( $( this ).val() );
            if ( v ) values.push( v );
        } );
        $container.find( '.oc-repeater-value' ).val( JSON.stringify( values ) );
    }

    // Add row
    $( document ).on( 'click', '.oc-repeater-add', function () {
        var $container = $( this ).closest( '.oc-repeater' );
        var placeholder = $container.data( 'placeholder' ) || 'Enter item';
        var $row = $(
            '<div class="oc-repeater-row">' +
                '<input type="text" class="oc-repeater-input" placeholder="' + placeholder + '">' +
                '<button type="button" class="oc-repeater-remove dashicons-before dashicons-no-alt" title="Remove"></button>' +
            '</div>'
        );
        $container.find( '.oc-repeater-rows' ).append( $row );
        $row.find( 'input' ).focus();
        syncRepeater( $container );
    } );

    // Remove row
    $( document ).on( 'click', '.oc-repeater-remove', function () {
        var $container = $( this ).closest( '.oc-repeater' );
        $( this ).closest( '.oc-repeater-row' ).remove();
        syncRepeater( $container );
    } );

    // Update on input change
    $( document ).on( 'input change', '.oc-repeater-input', function () {
        syncRepeater( $( this ).closest( '.oc-repeater' ) );
    } );

    /* ── 2. Route stops repeater serialiser ──────────────────── */

    function syncRouteStops( $container ) {
        var stops = [];
        $container.find( '.oc-route-row' ).each( function () {
            var name = $.trim( $( this ).find( '.oc-stop-name' ).val() );
            var lat  = parseFloat( $( this ).find( '.oc-stop-lat' ).val() ) || 0;
            var lng  = parseFloat( $( this ).find( '.oc-stop-lng' ).val() ) || 0;
            if ( name ) stops.push( { name: name, lat: lat, lng: lng } );
        } );
        $container.find( '.oc-route-value' ).val( JSON.stringify( stops ) );
    }

    // Add stop row
    $( document ).on( 'click', '.oc-route-add', function () {
        var $container = $( this ).closest( '.oc-route-stops' );
        var $row = $(
            '<div class="oc-route-row">' +
                '<input type="text"   class="oc-stop-name" placeholder="Stop name">' +
                '<input type="number" class="oc-stop-lat"  placeholder="Lat" step="any" style="width:100px">' +
                '<input type="number" class="oc-stop-lng"  placeholder="Lng" step="any" style="width:100px">' +
                '<button type="button" class="oc-route-remove dashicons-before dashicons-no-alt" title="Remove"></button>' +
            '</div>'
        );
        $container.find( '.oc-route-rows' ).append( $row );
        $row.find( '.oc-stop-name' ).focus();
        syncRouteStops( $container );
    } );

    // Remove stop row
    $( document ).on( 'click', '.oc-route-remove', function () {
        var $container = $( this ).closest( '.oc-route-stops' );
        $( this ).closest( '.oc-route-row' ).remove();
        syncRouteStops( $container );
    } );

    // Update on input change
    $( document ).on( 'input change', '.oc-stop-name, .oc-stop-lat, .oc-stop-lng', function () {
        syncRouteStops( $( this ).closest( '.oc-route-stops' ) );
    } );

    /* ── 3. Sync all repeaters before form submit ─────────────── */
    $( '#post' ).on( 'submit', function () {
        $( '.oc-repeater' ).each( function () { syncRepeater( $( this ) ); } );
        $( '.oc-route-stops' ).each( function () { syncRouteStops( $( this ) ); } );
    } );

    /* ── 4. Image uploader (wp.media) ────────────────────────── */
    $( document ).on( 'click', '.oc-upload-btn', function ( e ) {
        e.preventDefault();
        var $btn      = $( this );
        var targetId  = $btn.data( 'target' );
        var $input    = $( '#' + targetId );
        var $preview  = $btn.closest( '.oc-field--image' ).find( '.oc-image-preview' );

        var frame = wp.media( {
            title:    'Select Image',
            button:   { text: 'Use This Image' },
            multiple: false,
        } );

        frame.on( 'select', function () {
            var attachment = frame.state().get( 'selection' ).first().toJSON();
            var url = attachment.url;
            $input.val( url );
            $preview.html( '<img src="' + url + '" style="max-width:200px;max-height:150px;display:block;margin-bottom:8px;">' );
            $btn.text( 'Change Image' );
            // Show remove button if not present
            if ( ! $btn.next( '.oc-remove-image' ).length ) {
                $btn.after( '<button type="button" class="button oc-remove-image" data-target="' + targetId + '">Remove</button>' );
            }
        } );

        frame.open();
    } );

    // Remove image
    $( document ).on( 'click', '.oc-remove-image', function () {
        var targetId = $( this ).data( 'target' );
        $( '#' + targetId ).val( '' );
        $( this ).closest( '.oc-field--image' ).find( '.oc-image-preview' ).empty();
        $( this ).prev( '.oc-upload-btn' ).text( 'Upload Image' );
        $( this ).remove();
    } );

    /* ── 5. Initialise sync on page load (pre-filled values) ─── */
    $( function () {
        $( '.oc-repeater' ).each( function () { syncRepeater( $( this ) ); } );
        $( '.oc-route-stops' ).each( function () { syncRouteStops( $( this ) ); } );
    } );

} )( jQuery );
